<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Website;
use App\Models\Donation;
use Illuminate\Support\Str;
use App\Services\PaymentFunnelService;

class QRCodeDonationController extends Controller
{
    /**
     * Get the current domain for QR code generation
     */
    private function getCurrentDomain()
    {
        // Check if HTTPS
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') 
                    ? 'https://' : 'http://';
        
        // Get current host
        $domain = $_SERVER['HTTP_HOST'] ?? request()->getHost();
        
        $fullDomain = $protocol . $domain;
        
        // Log for debugging
        \Log::info('QR Code Domain Detection', [
            'protocol' => $protocol,
            'domain' => $domain,
            'full_domain' => $fullDomain,
            'http_host' => $_SERVER['HTTP_HOST'] ?? 'not set',
            'request_host' => request()->getHost(),
            'https' => $_SERVER['HTTPS'] ?? 'not set',
            'x_forwarded_proto' => $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'not set'
        ]);
        
        return $fullDomain;
    }
    
    /**
     * Generate QR code for a donation page
     */
    public function generate(Request $request)
    {
        $request->validate([
            'website_id' => 'required|exists:websites,id',
            'campaign_name' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:1',
            'type' => 'nullable|string|in:general,student,ticket,auction,investment'
        ]);

        $website = Website::findOrFail($request->website_id);
        
        // Generate unique QR code identifier
        $qrIdentifier = Str::random(10);
        
        // Build donation URL with QR parameters
        $params = [
            'qr' => $qrIdentifier,
            'website_id' => $website->id
        ];
        
        if ($request->campaign_name) {
            $params['campaign'] = $request->campaign_name;
        }
        
        if ($request->amount) {
            $params['amount'] = $request->amount;
        }
        
        if ($request->type) {
            $params['type'] = $request->type;
        }
        
        // Use current domain for QR code URL
        $donationUrl = $this->getCurrentDomain() . '/qr-donate?' . http_build_query($params);
        
        // Generate QR code
        $qrCode = QrCode::size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($donationUrl);
        
        return response()->json([
            'success' => true,
            'qr_code' => $qrCode,
            'qr_identifier' => $qrIdentifier,
            'donation_url' => $donationUrl,
            'website' => $website->name
        ]);
    }

    /**
     * Display QR donation page (mobile-optimized)
     */
    public function donate(Request $request)
    {
        $websiteId = $request->query('website_id');
        
        if (!$websiteId) {
            abort(404, 'Invalid QR code');
        }
        
        $website = Website::findOrFail($websiteId);
        
        // Get QR parameters
        $qrIdentifier = $request->query('qr') ?? 'legacy_' . Str::random(8);
        $campaignName = $request->query('campaign');
        $presetAmount = $request->query('amount');
        $donationType = $request->query('type', 'general');
        
        return view('qr-donate', compact(
            'website',
            'qrIdentifier',
            'campaignName',
            'presetAmount',
            'donationType'
        ));
    }

    /**
     * Process QR code donation
     */
    public function process(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|exists:websites,id',
                'amount' => 'required|numeric|min:1',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'qr_identifier' => 'required|string',
                'type' => 'nullable|string|in:general,student,ticket,auction,investment'
            ]);

            // Get website
            $website = Website::findOrFail($request->website_id);

            // Create donation record
            $donation = new Donation;
            $donation->first_name = $request->first_name;
            $donation->last_name = $request->last_name;
            $donation->email = $request->email;
            // $donation->phone = $request->phone;
            $donation->amount = $request->amount;
            $donation->website_id = $request->website_id;
            $donation->type = $request->type ?? 'general';
            $donation->status = 0; // Pending
            $donation->hide = $request->anonymous_donation ? 1 : 0;
            $donation->comment = $request->comment;
            
            // Process tip if enabled
            if ($request->input('tip_enabled') && $request->input('tip_amount') > 0) {
                $donation->tip_amount = $request->input('tip_amount');
                $donation->tip_percentage = $request->input('tip_percentage');
                $donation->tip_enabled = true;
            }
            
            // Add QR tracking metadata
            $donation->utm_source = 'qr_code';
            $donation->utm_medium = 'qr';
            $donation->utm_campaign = $request->campaign_name ?? 'qr_donation';
            $donation->referrer_url = 'qr://' . $request->qr_identifier;
            
            $donation->save();

            // Track payment initiation funnel event
            try {
                $funnelService = new PaymentFunnelService();
                $funnelService->trackPaymentInitiated(
                    $donation->type ?? 'general',
                    $donation->amount,
                    'authorize_net',
                    [
                        'first_name' => $donation->first_name,
                        'last_name' => $donation->last_name,
                        'email' => $donation->email,
                        'comment' => $donation->comment,
                        'anonymous' => $donation->hide ? true : false,
                        'source' => 'qr_code',
                        'qr_identifier' => $request->qr_identifier,
                        'campaign' => $request->campaign_name
                    ],
                    null, // user_id (QR donations are usually anonymous/public)
                    $website->id
                );
            } catch (\Exception $e) {
                \Log::error('Payment funnel tracking error in QR donation: ' . $e->getMessage());
            }

            // Build payment URL using website's domain
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') 
                        ? 'https://' : 'http://';
            
            $paymentUrl = $protocol . $website->domain . '/authorize/payment/donation/' . $donation->id;
            
            \Log::info('QR Donation Payment Redirect', [
                'donation_id' => $donation->id,
                'website_id' => $website->id,
                'website_domain' => $website->domain,
                'payment_url' => $paymentUrl
            ]);

            // Redirect to payment processing on the correct website domain
            return redirect($paymentUrl)
                ->with('success', 'Processing your donation...');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('QR Donation Validation Error', [
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Please check all required fields: ' . implode(', ', array_keys($e->errors())));
        } catch (\Exception $e) {
            \Log::error('QR Donation Processing Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Display QR code generator page
     */
    public function adminIndex()
    {
        $websites = Website::where('status', 1)->get();
        return view('admin.qr-codes.index', compact('websites'));
    }

    /**
     * Admin: Download QR code as PNG
     */
    public function download(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $qrCode = QrCode::format('png')
            ->size(500)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($request->url);

        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-donation-' . time() . '.png"');
    }

    /**
     * Admin: Generate QR code for specific campaign
     */
    public function generateCampaign(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|exists:websites,id',
                'campaign_name' => 'required|string|max:255',
                'preset_amount' => 'nullable|numeric|min:1',
                'size' => 'nullable|integer|min:100|max:1000'
            ]);

            $website = Website::findOrFail($request->website_id);
            $size = $request->size ?? 300;
            
            // Generate unique QR code identifier
            $qrIdentifier = Str::random(10);
            
            // Build campaign URL using website's domain
            $params = [
                'qr' => $qrIdentifier,
                'website_id' => $website->id,
                'campaign' => $request->campaign_name
            ];
            
            if ($request->preset_amount) {
                $params['amount'] = $request->preset_amount;
            }
            
            // Use website domain for QR code URL
            $donationUrl = $this->getCurrentDomain() . '/qr-donate?' . http_build_query($params);
            
            // Generate QR code as base64
            $qrCode = base64_encode(
                QrCode::format('png')
                    ->size($size)
                    ->margin(2)
                    ->errorCorrection('H')
                    ->generate($donationUrl)
            );
            
            return response()->json([
                'success' => true,
                'qr_code_base64' => 'data:image/png;base64,' . $qrCode,
                'donation_url' => $donationUrl,
                'campaign_name' => $request->campaign_name,
                'website' => $website->name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get QR code statistics
     */
    public function statistics(Request $request)
    {
        $websiteId = $request->query('website_id');
        $startDate = $request->query('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->query('end_date', now()->format('Y-m-d'));

        $query = Donation::where('utm_source', 'qr_code')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }

        $stats = [
            'total_scans' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'completed_donations' => $query->where('status', 1)->count(),
            'pending_donations' => $query->where('status', 0)->count(),
            'average_donation' => $query->avg('amount'),
            'by_campaign' => $query->select('utm_campaign', \DB::raw('COUNT(*) as count'), \DB::raw('SUM(amount) as total'))
                ->groupBy('utm_campaign')
                ->get()
        ];

        return response()->json([
            'success' => true,
            'statistics' => $stats,
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ]);
    }
}
