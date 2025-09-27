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
        
        // Generate PDF
        $pdf = Pdf::loadView('emails.invoice-pdf', [
            'transaction' => $this->transaction,
            'website' => $this->website,
            'total_with_fee' => $total_with_fee
        ]);

        // Set PDF options for better rendering
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        return $this->subject('Invoice for Transaction #' . $this->transaction->transaction_id . ' - ' . $this->website->name)
                    ->from(config('mail.from.address', 'noreply@' . $this->website->domain), $this->website->name)
                    ->view('emails.transaction-invoice')
                    ->with([
                        'transaction' => $this->transaction,
                        'website' => $this->website,
                        'total_with_fee' => $total_with_fee,
                        'fee_percentage' => $fee_percentage
                    ])
                    ->attachData(
                        $pdf->output(),
                        'invoice-' . $this->transaction->transaction_id . '.pdf',
                        [
                            'mime' => 'application/pdf',
                        ]
                    );
    }
}