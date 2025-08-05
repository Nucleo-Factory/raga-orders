<?php

namespace RagaOrders\POConfirmation\Models;

use Illuminate\Database\Eloquent\Model;

class POConfirmationSetting extends Model
{
    protected $table = 'po_confirmation_settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description'
    ];

    /**
     * Obtener el valor de una configuración
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        switch ($setting->type) {
            case 'integer':
                return (int) $setting->value;
            case 'boolean':
                return (bool) $setting->value;
            case 'json':
                return json_decode($setting->value, true);
            default:
                return $setting->value;
        }
    }

    /**
     * Establecer el valor de una configuración
     */
    public static function setValue(string $key, $value, string $type = 'string', string $group = 'general', string $description = null): void
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            $setting = new self();
            $setting->key = $key;
            $setting->type = $type;
            $setting->group = $group;
            $setting->description = $description;
        }

        if ($type === 'json' && is_array($value)) {
            $setting->value = json_encode($value);
        } else {
            $setting->value = (string) $value;
        }

        $setting->save();
    }

    /**
     * Obtener todas las configuraciones por grupo
     */
    public static function getByGroup(string $group): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('group', $group)->orderBy('key')->get();
    }

    /**
     * Obtener todas las configuraciones como array
     */
    public static function getAllAsArray(): array
    {
        $settings = self::all();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->key] = self::getValue($setting->key);
        }

        return $result;
    }

    /**
     * Limpiar caché de configuraciones
     */
    public static function clearCache(): void
    {
        // En el futuro, implementar caché si es necesario
    }
}
