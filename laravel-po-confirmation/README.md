# Laravel PO Confirmation

Módulo de confirmación de órdenes de compra via email para Laravel.

## Características

- ✅ **Detectar POs pendientes** automáticamente
- ✅ **Generar URLs seguras** con hash único de 64 caracteres
- ✅ **Envío de emails** automáticos a proveedores
- ✅ **Confirmación via web** con formulario opcional para actualizar fecha de entrega
- ✅ **Actualización de datos** automática al confirmar
- ✅ **Dashboard administrativo** con estadísticas y controles
- ✅ **Comandos Artisan** para instalación y gestión
- ✅ **Configuración flexible** via archivo de configuración
- ✅ **Seguridad** con hashes únicos y expiración temporal
- ✅ **Automatización** con tareas programadas

## Instalación

### 1. Instalar el paquete

```bash
composer require raga-orders/laravel-po-confirmation
```

### 2. Ejecutar la instalación

```bash
php artisan po-confirmation:install
```

### 3. Configurar el modelo PurchaseOrder

Agregar el trait `HasPOConfirmation` a tu modelo `PurchaseOrder`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use RagaOrders\POConfirmation\Traits\HasPOConfirmation;

class PurchaseOrder extends Model
{
    use HasPOConfirmation;

    protected $fillable = [
        // ... otros campos
        'confirmation_hash',
        'hash_expires_at',
        'confirmation_email_sent',
        'confirmation_email_sent_at',
        'update_date_po',
        'confirm_update_date_po'
    ];

    protected $casts = [
        'hash_expires_at' => 'datetime',
        'confirmation_email_sent_at' => 'datetime',
        'update_date_po' => 'date',
        'confirm_update_date_po' => 'boolean'
    ];
}
```

### 4. Configurar variables de entorno

Agregar al archivo `.env`:

```env
PO_CONFIRMATION_ENABLED=true
PO_CONFIRMATION_HASH_EXPIRY=72
PO_CONFIRMATION_FROM_NAME="Raga Orders"
PO_CONFIRMATION_FROM_ADDRESS=noreply@ragaorders.com
PO_CONFIRMATION_AUTO_SEND=true
PO_CONFIRMATION_CHECK_INTERVAL=hourly
PO_CONFIRMATION_NOTIFY_ADMIN=true
PO_CONFIRMATION_ADMIN_EMAIL=admin@ragaorders.com
```

### 5. Configurar el cron job (opcional)

Para automatización completa, agregar al cron:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Uso

### Comandos Artisan

#### Instalar el módulo
```bash
php artisan po-confirmation:install
```

#### Desinstalar el módulo
```bash
php artisan po-confirmation:uninstall
```

#### Procesar POs pendientes manualmente
```bash
php artisan po:check-pending
```

#### Limpiar hashes expirados
```bash
php artisan po:check-pending --clean
```

### Dashboard Administrativo

Agregar el componente Livewire a tu vista administrativa:

```blade
@livewire('po-confirmation-manager')
```

### API del Servicio

```php
use RagaOrders\POConfirmation\Services\POConfirmationService;

// Procesar POs pendientes
$service = app(POConfirmationService::class);
$results = $service->processPendingPOs();

// Confirmar PO por hash
$result = $service->confirmPOByHash($hash, $newDeliveryDate);

// Obtener estadísticas
$stats = $service->getStatistics();

// Limpiar hashes expirados
$cleaned = $service->cleanExpiredHashes();
```

### Métodos del Trait

```php
// Generar hash de confirmación
$hash = $po->generateConfirmationHash();

// Validar hash
$isValid = $po->isConfirmationHashValid($hash);

// Confirmar PO
$po->confirmPO($newDeliveryDate);

// Actualizar fecha de entrega
$po->updateDeliveryDate($newDate);

// Marcar email como enviado
$po->markEmailAsSent();

// Obtener URL de confirmación
$url = $po->getConfirmationUrl();

// Verificar si puede ser confirmada
$canConfirm = $po->canBeConfirmed();

// Verificar si está confirmada
$isConfirmed = $po->isConfirmed();
```

### Scopes del Modelo

```php
// POs pendientes de confirmación
$pendingPOs = PurchaseOrder::pendingConfirmation()->get();

// POs con hash válido
$validHashPOs = PurchaseOrder::withValidHash()->get();

// POs con hash expirado
$expiredHashPOs = PurchaseOrder::withExpiredHash()->get();
```

## Configuración

### Variables de Entorno

| Variable | Descripción | Valor por defecto |
|----------|-------------|-------------------|
| `PO_CONFIRMATION_ENABLED` | Activar/desactivar módulo | `false` |
| `PO_CONFIRMATION_HASH_EXPIRY` | Horas de expiración del hash | `72` |
| `PO_CONFIRMATION_FROM_NAME` | Nombre del remitente | `"Raga Orders"` |
| `PO_CONFIRMATION_FROM_ADDRESS` | Email del remitente | `noreply@ragaorders.com` |
| `PO_CONFIRMATION_AUTO_SEND` | Envío automático de emails | `true` |
| `PO_CONFIRMATION_CHECK_INTERVAL` | Intervalo de verificación | `hourly` |
| `PO_CONFIRMATION_NOTIFY_ADMIN` | Notificar al admin | `true` |
| `PO_CONFIRMATION_ADMIN_EMAIL` | Email del admin | `admin@ragaorders.com` |

### Archivo de Configuración

El archivo `config/po-confirmation.php` se crea automáticamente durante la instalación.

## Rutas

El módulo registra automáticamente las siguientes rutas:

- `GET /po/confirm/{hash}` - Mostrar formulario de confirmación
- `POST /po/confirm/{hash}` - Procesar confirmación
- `GET /po/success` - Página de éxito
- `GET /po/error` - Página de error

## Estructura de Base de Datos

El módulo agrega los siguientes campos a la tabla `purchase_orders`:

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `confirmation_hash` | VARCHAR(64) | Hash único para confirmación |
| `hash_expires_at` | TIMESTAMP | Fecha de expiración del hash |
| `confirmation_email_sent` | BOOLEAN | Si el email fue enviado |
| `confirmation_email_sent_at` | TIMESTAMP | Fecha de envío del email |
| `update_date_po` | DATE | Nueva fecha de entrega |
| `confirm_update_date_po` | BOOLEAN | Si se actualizó la fecha |

## Seguridad

- **Hashes únicos**: Generados con `Str::random(64)`
- **Expiración temporal**: Enlaces válidos por 72 horas (configurable)
- **Validación de integridad**: Verificación de hash y expiración
- **Protección CSRF**: Tokens CSRF en formularios
- **Validación de datos**: Validación de fechas y formatos

## Automatización

El módulo incluye tareas programadas automáticas:

- **Verificación periódica**: Revisa POs pendientes cada hora (configurable)
- **Limpieza automática**: Elimina hashes expirados
- **Envío automático**: Envía emails de confirmación automáticamente

## Personalización

### Templates de Email

Los templates se encuentran en `resources/views/po-confirmation/emails/`:

- `confirmation.blade.php` - Email de confirmación
- `confirmed.blade.php` - Notificación al admin

### Vistas Web

Las vistas de confirmación están en `resources/views/po-confirmation/`:

- `confirm.blade.php` - Formulario de confirmación
- `success.blade.php` - Página de éxito
- `error.blade.php` - Página de error

### Componente Livewire

El dashboard administrativo está en `resources/views/po-confirmation/components/manager.blade.php`.

## Troubleshooting

### Problemas Comunes

1. **Emails no se envían**
   - Verificar configuración de email en `.env`
   - Revisar logs de Laravel

2. **Hashes no se generan**
   - Verificar que el trait esté agregado al modelo
   - Revisar migraciones ejecutadas

3. **Rutas no funcionan**
   - Verificar que el módulo esté habilitado
   - Revisar configuración de rutas

### Logs

El módulo registra logs detallados en `storage/logs/laravel.log`:

- Confirmaciones exitosas
- Errores de envío de email
- Hashes expirados
- Errores de validación

## Soporte

Para soporte técnico, contactar a:
- Email: info@ragaorders.com
- Documentación: [URL de documentación]

## Licencia

Este paquete está bajo la licencia MIT. Ver el archivo `LICENSE` para más detalles.

## Contribuir

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

## Changelog

### v1.0.0
- Lanzamiento inicial
- Funcionalidad completa de confirmación de POs
- Dashboard administrativo
- Automatización con tareas programadas
- Templates de email profesionales
