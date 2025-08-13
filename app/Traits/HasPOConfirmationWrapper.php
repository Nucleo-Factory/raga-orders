<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Wrapper para el trait HasPOConfirmation del módulo PO Confirmation
 * Este trait siempre está disponible y verifica si el módulo está activo
 */
trait HasPOConfirmationWrapper
{
    /**
     * Verifica si el módulo PO Confirmation está disponible y activo
     */
    protected function isPOConfirmationAvailable(): bool
    {
        return class_exists('RagaOrders\POConfirmation\Traits\HasPOConfirmation') &&
               config('po-confirmation.enabled', false);
    }

    /**
     * Obtiene el trait real si está disponible
     */
    protected function getPOConfirmationTrait()
    {
        if ($this->isPOConfirmationAvailable()) {
            return new class {
                use \RagaOrders\POConfirmation\Traits\HasPOConfirmation;
            };
        }
        return null;
    }

    // Métodos que delegan al trait real si está disponible

    public function scopePendingConfirmation(Builder $query): Builder
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->scopePendingConfirmation($query);
        }
        return $query->whereRaw('1 = 0'); // Retorna resultados vacíos
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->scopeConfirmed($query);
        }
        return $query->whereRaw('1 = 0'); // Retorna resultados vacíos
    }

    public function scopeRejected(Builder $query): Builder
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->scopeRejected($query);
        }
        return $query->whereRaw('1 = 0'); // Retorna resultados vacíos
    }

    public function isPendingConfirmation(): bool
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->isPendingConfirmation();
        }
        return false;
    }

    public function isConfirmed(): bool
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->isConfirmed();
        }
        return false;
    }

    public function isRejected(): bool
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->isRejected();
        }
        return false;
    }

    public function getConfirmationStatus(): ?string
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->getConfirmationStatus();
        }
        return null;
    }

    public function getConfirmationDate(): ?string
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->getConfirmationDate();
        }
        return null;
    }

    public function getRejectionReason(): ?string
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->getRejectionReason();
        }
        return null;
    }

    public function generateConfirmationHash(): ?string
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->generateConfirmationHash();
        }
        return null;
    }

    public function isConfirmationHashValid(string $hash): bool
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->isConfirmationHashValid($hash);
        }
        return false;
    }

    public function confirmPO(?string $newDeliveryDate = null): bool
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->confirmPO($newDeliveryDate);
        }
        return false;
    }

    public function updateDeliveryDate(string $newDate): bool
    {
        if ($this->isPOConfirmationAvailable()) {
            return $this->getPOConfirmationTrait()->updateDeliveryDate($newDate);
        }
        return false;
    }
}
