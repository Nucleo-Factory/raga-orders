<?php

namespace RagaOrders\POConfirmation\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class POConfirmationMail extends Mailable
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
        // Obtener configuraciones dinámicas
        $emailSettings = [
            'greeting' => $this->getEmailSetting('email_greeting', 'Estimado proveedor,'),
            'body' => $this->getEmailSetting('email_body', 'Le informamos que tenemos una orden de compra pendiente de confirmación. Por favor, revise los detalles y confirme si puede cumplir con la fecha de entrega especificada.'),
            'footer' => $this->getEmailSetting('email_footer', 'Si tiene alguna pregunta, no dude en contactarnos. Gracias por su atención.')
        ];

        return $this->subject('Confirmación de Orden de Compra - ' . $this->po->order_number)
                    ->from(
                        config('po-confirmation.email_from_address', 'noreply@ragaorders.com'),
                        config('po-confirmation.email_from_name', 'Raga Orders')
                    )
                    ->view('po-confirmation.emails.confirmation')
                    ->with([
                        'po' => $this->po,
                        'confirmationUrl' => $this->po->getConfirmationUrl(),
                        'expiryHours' => config('po-confirmation.hash_expiry_hours', 72),
                        'emailSettings' => $emailSettings
                    ]);
    }

    /**
     * Get email setting value
     */
    private function getEmailSetting(string $key, string $default = ''): string
    {
        if (class_exists('RagaOrders\POConfirmation\Models\POConfirmationSetting')) {
            return \RagaOrders\POConfirmation\Models\POConfirmationSetting::getValue($key, $default);
        }
        return $default;
    }
}
