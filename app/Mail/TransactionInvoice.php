<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;
use App\Models\Website;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $website;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaction $transaction, Website $website)
    {
        $this->transaction = $transaction;
        $this->website = $website;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $fee_percentage = 2.9; // Default fee
        if ($this->website->paymentSettings) {
            $fee_percentage = $this->website->paymentSettings->fee ?? 2.9;
        }
        $total_with_fee = $this->transaction->amount + (($this->transaction->amount / 100) * $fee_percentage);
        
        // Get transaction type specific data and customize subject/content
        $additionalData = $this->getTransactionTypeData();
        $subject = $this->getCustomSubject();
        
        // Generate PDF
        $pdf = Pdf::loadView('emails.invoice-pdf', array_merge([
            'transaction' => $this->transaction,
            'website' => $this->website,
            'total_with_fee' => $total_with_fee,
            'fee_percentage' => $fee_percentage
        ], $additionalData));

        // Set PDF options for better rendering
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        return $this->subject($subject)
                    ->from(config('mail.from.address', 'noreply@' . $this->website->domain), $this->website->name)
                    ->when(config('mail.reply_to.address'), function ($m) {
                        $m->replyTo(config('mail.reply_to.address'), config('mail.reply_to.name'));
                    })
                    ->view('emails.transaction-invoice')
                    ->with(array_merge([
                        'transaction' => $this->transaction,
                        'website' => $this->website,
                        'total_with_fee' => $total_with_fee,
                        'fee_percentage' => $fee_percentage
                    ], $additionalData))
                    ->attachData(
                        $pdf->output(),
                        $this->getFileName(),
                        [
                            'mime' => 'application/pdf',
                        ]
                    );
    }
    
    /**
     * Get transaction type specific data
     */
    private function getTransactionTypeData()
    {
        $data = [];
        
        switch($this->transaction->type) {
            case 'student':
                // Get donation details for student donations
                $donation = \App\Models\Donation::find($this->transaction->reference_id);
                $data['donation'] = $donation;
                $data['transaction_type_label'] = 'Student Donation';
                break;
                
            case 'general':
                // Get donation details for general donations  
                $donation = \App\Models\Donation::find($this->transaction->reference_id);
                $data['donation'] = $donation;
                $data['transaction_type_label'] = 'General Donation';
                break;
                
            case 'ticket':
                // Get ticket sale details
                $ticketSale = \App\Models\TicektSell::with('details.ticket')->find($this->transaction->reference_id);
                $data['ticket_sale'] = $ticketSale;
                $data['transaction_type_label'] = 'Ticket Purchase';
                break;
                
            case 'auction':
                // Get auction details
                $auction = \App\Models\Auction::find($this->transaction->reference_id);
                $data['auction'] = $auction;
                $data['transaction_type_label'] = 'Auction Bid';
                break;
                
            case 'investment':
                // Get investment details
                $investment = \App\Models\Investment::find($this->transaction->reference_id);
                $data['investment'] = $investment;
                $data['transaction_type_label'] = 'Investment Transaction';
                break;
                
            default:
                $data['transaction_type_label'] = 'Transaction';
        }
        
        return $data;
    }
    
    /**
     * Get custom subject based on transaction type
     */
    private function getCustomSubject()
    {
        switch($this->transaction->type) {
            case 'student':
            case 'general':
                return 'Donation Receipt #' . $this->transaction->transaction_id . ' - ' . $this->website->name;
                
            case 'ticket':
                return 'Ticket Purchase Confirmation #' . $this->transaction->transaction_id . ' - ' . $this->website->name;
                
            case 'auction':
                return 'Auction Bid Confirmation #' . $this->transaction->transaction_id . ' - ' . $this->website->name;
                
            case 'investment':
                return 'Investment Confirmation #' . $this->transaction->transaction_id . ' - ' . $this->website->name;
                
            default:
                return 'Transaction Receipt #' . $this->transaction->transaction_id . ' - ' . $this->website->name;
        }
    }
    
    /**
     * Get custom filename based on transaction type
     */
    private function getFileName()
    {
        switch($this->transaction->type) {
            case 'student':
            case 'general':
                return 'donation-receipt-' . $this->transaction->transaction_id . '.pdf';
                
            case 'ticket':
                return 'ticket-confirmation-' . $this->transaction->transaction_id . '.pdf';
                
            case 'auction':
                return 'auction-confirmation-' . $this->transaction->transaction_id . '.pdf';
                
            case 'investment':
                return 'investment-confirmation-' . $this->transaction->transaction_id . '.pdf';
                
            default:
                return 'transaction-receipt-' . $this->transaction->transaction_id . '.pdf';
        }
    }
}