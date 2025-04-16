<?php

namespace App\Services;

use App\Models\Authorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class AuthorizationService
{
    /**
     * Create a new authorization request
     */
    public function createRequest(Model $model, string $operationType, array $data = []): Authorization
    {
        // Check if the model uses the HasAuthorizations trait
        if (method_exists($model, 'createAuthorizationRequest')) {
            // Use the trait's method if available
            return $model->createAuthorizationRequest($operationType, $data);
        }

        // Legacy fallback for models that don't use the trait
        return Authorization::create([
            'operation_id' => Str::uuid(),
            'authorizable_type' => get_class($model),
            'authorizable_id' => $model->id,
            'requester_id' => Auth::id(),
            'operation_type' => $operationType,
            'status' => Authorization::STATUS_PENDING,
            'data' => $data,
        ]);
    }

    /**
     * Approve an authorization request
     */
    public function approve(Authorization $authorization, ?string $notes = null): bool
    {
        $result = $authorization->update([
            'status' => Authorization::STATUS_APPROVED,
            'authorizer_id' => Auth::id(),
            'authorized_at' => now(),
            'notes' => $notes,
        ]);

        // If the authorization is for a model with status, update the model's status
        if ($result && $authorization->authorizable &&
            method_exists($authorization->authorizable, 'isApproved')) {

            $authorization->authorizable->update(['status' => Authorization::STATUS_APPROVED]);
        }

        return $result;
    }

    /**
     * Reject an authorization request
     */
    public function reject(Authorization $authorization, ?string $notes = null): bool
    {
        $result = $authorization->update([
            'status' => Authorization::STATUS_REJECTED,
            'authorizer_id' => Auth::id(),
            'authorized_at' => now(),
            'notes' => $notes,
        ]);

        // If the authorization is for a model with status, update the model's status
        if ($result && $authorization->authorizable &&
            method_exists($authorization->authorizable, 'isRejected')) {

            $authorization->authorizable->update(['status' => Authorization::STATUS_REJECTED]);
        }

        return $result;
    }

    /**
     * Get all pending authorization requests
     */
    public function getPendingRequests(): Collection
    {
        return Authorization::with(['requester', 'authorizable'])
                           ->where('status', Authorization::STATUS_PENDING)
                           ->latest()
                           ->get();
    }

    /**
     * Check if there is a pending operation of a specific type
     */
    public function isOperationPending(string $operationType, Model $model): bool
    {
        // Check if the model uses the HasAuthorizations trait
        if (method_exists($model, 'hasAuthorizationPending')) {
            // Use the trait's method if available
            return $model->hasAuthorizationPending($operationType);
        }

        // Legacy fallback for models that don't use the trait
        return Authorization::where('operation_type', $operationType)
                           ->where('authorizable_type', get_class($model))
                           ->where('authorizable_id', $model->id)
                           ->where('status', Authorization::STATUS_PENDING)
                           ->exists();
    }

    /**
     * Get all authorization requests (pending, approved, or rejected) for a model
     */
    public function getAllRequestsForModel(Model $model): Collection
    {
        // Check if the model uses the HasAuthorizations trait
        if (method_exists($model, 'authorizations')) {
            // Use the trait's method if available
            return $model->authorizations()
                        ->with(['requester', 'authorizer'])
                        ->latest()
                        ->get();
        }

        // Legacy fallback for models that don't use the trait
        return Authorization::with(['requester', 'authorizer'])
                          ->where('authorizable_type', get_class($model))
                          ->where('authorizable_id', $model->id)
                          ->latest()
                          ->get();
    }

    /**
     * Get all authorizations by status for a specific model
     */
    public function getRequestsByStatus(Model $model, string $status): Collection
    {
        // Check if the model uses the HasAuthorizations trait
        if (method_exists($model, 'authorizations')) {
            // Use the trait's method if available
            return $model->authorizations()
                        ->with(['requester', 'authorizer'])
                        ->where('status', $status)
                        ->latest()
                        ->get();
        }

        // Legacy fallback for models that don't use the trait
        return Authorization::with(['requester', 'authorizer'])
                          ->where('authorizable_type', get_class($model))
                          ->where('authorizable_id', $model->id)
                          ->where('status', $status)
                          ->latest()
                          ->get();
    }

    /**
     * Find the most recent authorization of a specific type for a model
     */
    public function findAuthorizationByType(Model $model, string $operationType, string $status = null)
    {
        // Check if the model uses the HasAuthorizations trait
        if (method_exists($model, 'findAuthorizationByType')) {
            // Use the trait's method if available
            return $model->findAuthorizationByType($operationType, $status);
        }

        // Legacy fallback for models that don't use the trait
        $query = Authorization::where('operation_type', $operationType)
                            ->where('authorizable_type', get_class($model))
                            ->where('authorizable_id', $model->id)
                            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->first();
    }
}
