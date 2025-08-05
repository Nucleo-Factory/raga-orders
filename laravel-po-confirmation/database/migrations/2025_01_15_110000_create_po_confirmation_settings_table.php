<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('po_confirmation_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->string('group')->default('general'); // general, email, timing
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insertar configuraciones por defecto
        $this->insertDefaultSettings();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_confirmation_settings');
    }

    /**
     * Insertar configuraciones por defecto
     */
    private function insertDefaultSettings(): void
    {
        $settings = [
            [
                'key' => 'days_before_confirmation',
                'value' => '5',
                'type' => 'integer',
                'group' => 'timing',
                'description' => 'Días antes de la fecha de entrega para enviar el email de confirmación'
            ],
            [
                'key' => 'email_subject',
                'value' => 'Confirmación de Orden de Compra - {order_number}',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Asunto del email de confirmación. Use {order_number} para el número de orden'
            ],
            [
                'key' => 'email_greeting',
                'value' => 'Estimado proveedor,',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Saludo del email de confirmación'
            ],
            [
                'key' => 'email_body',
                'value' => 'Le informamos que tenemos una orden de compra pendiente de confirmación. Por favor, revise los detalles y confirme si puede cumplir con la fecha de entrega especificada.',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Cuerpo principal del email de confirmación'
            ],
            [
                'key' => 'email_footer',
                'value' => 'Si tiene alguna pregunta, no dude en contactarnos. Gracias por su atención.',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Pie de página del email de confirmación'
            ],
            [
                'key' => 'success_message',
                'value' => '¡Orden de compra confirmada exitosamente!',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Mensaje de éxito al confirmar la PO'
            ],
            [
                'key' => 'expired_message',
                'value' => 'El enlace de confirmación ha expirado. Por favor, contacte al administrador.',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Mensaje cuando el hash ha expirado'
            ],
            [
                'key' => 'not_found_message',
                'value' => 'Orden de compra no encontrada.',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Mensaje cuando la PO no se encuentra'
            ]
        ];

        foreach ($settings as $setting) {
            DB::table('po_confirmation_settings')->insert($setting);
        }
    }
};
