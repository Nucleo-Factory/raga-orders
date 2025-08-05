<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PO Confirmada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #10b981;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8fafc;
            padding: 30px;
            border: 1px solid #e2e8f0;
        }
        .po-details {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }
        .success {
            background-color: #d1fae5;
            border: 1px solid #10b981;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Orden de Compra Confirmada</h1>
        <p>Raga Orders - Notificación Automática</p>
    </div>

    <div class="content">
        <p>Se ha confirmado una nueva orden de compra:</p>

        <div class="po-details">
            <h3>Detalles de la Orden Confirmada</h3>
            <p><strong>Número de Orden:</strong> {{ $po->order_number }}</p>
            <p><strong>Proveedor:</strong> {{ $vendor->name ?? 'No especificado' }}</p>
            <p><strong>Email del Proveedor:</strong> {{ $vendor->email ?? 'No especificado' }}</p>
            <p><strong>Total:</strong> ${{ number_format($po->total, 2) }}</p>
            <p><strong>Moneda:</strong> {{ $po->currency ?? 'USD' }}</p>
            <p><strong>Fecha de Entrega Original:</strong> {{ $po->date_required_in_destination ? $po->date_required_in_destination->format('d/m/Y') : 'No especificada' }}</p>
            @if($po->update_date_po)
                <p><strong>Nueva Fecha de Entrega:</strong> {{ $po->update_date_po->format('d/m/Y') }}</p>
            @endif
            <p><strong>Fecha de Confirmación:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        <div class="success">
            <p><strong>✅ Confirmación Exitosa:</strong> El proveedor ha confirmado la orden de compra.</p>
        </div>

        <p>La orden está lista para ser procesada en el sistema.</p>
    </div>

    <div class="footer">
        <p>Esta es una notificación automática del sistema de confirmación de POs.</p>
    </div>
</body>
</html>
