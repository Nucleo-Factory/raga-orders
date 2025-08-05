<?php

namespace RagaOrders\POConfirmation\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class POConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $po;

    /**
     * Create a new message instance.
     */
    public function __construct($po)
    {
        $this->po = $po;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('PO Confirmada - ' . $this->po->order_number)
                    ->from(
                        config('po-confirmation.email_from_address', 'noreply@ragaorders.com'),
                        config('po-confirmation.email_from_name', 'Raga Orders')
                    )
                    ->view('po-confirmation::emails.confirmed')
                    ->with([
                        'po' => $this->po,
                        'vendor' => $this->po->vendor,
                    ]);
    }
}
