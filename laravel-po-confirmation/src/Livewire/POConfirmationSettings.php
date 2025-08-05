<?php

namespace RagaOrders\POConfirmation\Livewire;

use Livewire\Component;
use RagaOrders\POConfirmation\Models\POConfirmationSetting;

class POConfirmationSettings extends Component
{
    public $timingSettings = [];
    public $emailSettings = [];
    public $generalSettings = [];
    public $showSuccessMessage = false;

    protected $rules = [
        'timingSettings.days_before_confirmation' => 'required|integer|min:1|max:30',
        'emailSettings.email_subject' => 'required|string|max:255',
        'emailSettings.email_greeting' => 'required|string|max:255',
        'emailSettings.email_body' => 'required|string|max:1000',
        'emailSettings.email_footer' => 'required|string|max:500',
        'generalSettings.success_message' => 'required|string|max:255',
        'generalSettings.expired_message' => 'required|string|max:255',
        'generalSettings.not_found_message' => 'required|string|max:255',
    ];

    protected $messages = [
        'timingSettings.days_before_confirmation.required' => 'Los días antes de confirmación son requeridos.',
        'timingSettings.days_before_confirmation.integer' => 'Los días deben ser un número entero.',
        'timingSettings.days_before_confirmation.min' => 'Los días deben ser al menos 1.',
        'timingSettings.days_before_confirmation.max' => 'Los días no pueden ser más de 30.',
        'emailSettings.email_subject.required' => 'El asunto del email es requerido.',
        'emailSettings.email_greeting.required' => 'El saludo del email es requerido.',
        'emailSettings.email_body.required' => 'El cuerpo del email es requerido.',
        'emailSettings.email_footer.required' => 'El pie de página del email es requerido.',
        'generalSettings.success_message.required' => 'El mensaje de éxito es requerido.',
        'generalSettings.expired_message.required' => 'El mensaje de expirado es requerido.',
        'generalSettings.not_found_message.required' => 'El mensaje de no encontrado es requerido.',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // Cargar configuraciones de timing
        $this->timingSettings = [
            'days_before_confirmation' => POConfirmationSetting::getValue('days_before_confirmation', 5)
        ];

        // Cargar configuraciones de email
        $this->emailSettings = [
            'email_subject' => POConfirmationSetting::getValue('email_subject', 'Confirmación de Orden de Compra - {order_number}'),
            'email_greeting' => POConfirmationSetting::getValue('email_greeting', 'Estimado proveedor,'),
            'email_body' => POConfirmationSetting::getValue('email_body', 'Le informamos que tenemos una orden de compra pendiente de confirmación. Por favor, revise los detalles y confirme si puede cumplir con la fecha de entrega especificada.'),
            'email_footer' => POConfirmationSetting::getValue('email_footer', 'Si tiene alguna pregunta, no dude en contactarnos. Gracias por su atención.')
        ];

        // Cargar configuraciones generales
        $this->generalSettings = [
            'success_message' => POConfirmationSetting::getValue('success_message', '¡Orden de compra confirmada exitosamente!'),
            'expired_message' => POConfirmationSetting::getValue('expired_message', 'El enlace de confirmación ha expirado. Por favor, contacte al administrador.'),
            'not_found_message' => POConfirmationSetting::getValue('not_found_message', 'Orden de compra no encontrada.')
        ];
    }

    public function saveSettings()
    {
        $this->validate();

        try {
            // Guardar configuraciones de timing
            POConfirmationSetting::setValue(
                'days_before_confirmation',
                $this->timingSettings['days_before_confirmation'],
                'integer',
                'timing',
                'Días antes de la fecha de entrega para enviar el email de confirmación'
            );

            // Guardar configuraciones de email
            POConfirmationSetting::setValue(
                'email_subject',
                $this->emailSettings['email_subject'],
                'string',
                'email',
                'Asunto del email de confirmación. Use {order_number} para el número de orden'
            );

            POConfirmationSetting::setValue(
                'email_greeting',
                $this->emailSettings['email_greeting'],
                'string',
                'email',
                'Saludo del email de confirmación'
            );

            POConfirmationSetting::setValue(
                'email_body',
                $this->emailSettings['email_body'],
                'string',
                'email',
                'Cuerpo principal del email de confirmación'
            );

            POConfirmationSetting::setValue(
                'email_footer',
                $this->emailSettings['email_footer'],
                'string',
                'email',
                'Pie de página del email de confirmación'
            );

            // Guardar configuraciones generales
            POConfirmationSetting::setValue(
                'success_message',
                $this->generalSettings['success_message'],
                'string',
                'general',
                'Mensaje de éxito al confirmar la PO'
            );

            POConfirmationSetting::setValue(
                'expired_message',
                $this->generalSettings['expired_message'],
                'string',
                'general',
                'Mensaje cuando el hash ha expirado'
            );

            POConfirmationSetting::setValue(
                'not_found_message',
                $this->generalSettings['not_found_message'],
                'string',
                'general',
                'Mensaje cuando la PO no se encuentra'
            );

            $this->showSuccessMessage = true;

            // Ocultar mensaje después de 3 segundos
            $this->dispatch('show-success-message');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar las configuraciones: ' . $e->getMessage());
        }
    }

    public function resetToDefaults()
    {
        $this->timingSettings = [
            'days_before_confirmation' => 5
        ];

        $this->emailSettings = [
            'email_subject' => 'Confirmación de Orden de Compra - {order_number}',
            'email_greeting' => 'Estimado proveedor,',
            'email_body' => 'Le informamos que tenemos una orden de compra pendiente de confirmación. Por favor, revise los detalles y confirme si puede cumplir con la fecha de entrega especificada.',
            'email_footer' => 'Si tiene alguna pregunta, no dude en contactarnos. Gracias por su atención.'
        ];

        $this->generalSettings = [
            'success_message' => '¡Orden de compra confirmada exitosamente!',
            'expired_message' => 'El enlace de confirmación ha expirado. Por favor, contacte al administrador.',
            'not_found_message' => 'Orden de compra no encontrada.'
        ];

        session()->flash('info', 'Configuraciones restauradas a valores por defecto.');
    }

    public function render()
    {
        return view('po-confirmation.settings.index');
    }
}
