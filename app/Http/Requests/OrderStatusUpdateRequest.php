<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order;

class OrderStatusUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $order = $this->route('order');
        
        return [
            'status' => [
                'required',
                'in:pending,paid,processing,completed,cancelled,needs_revision',
                function ($attribute, $value, $fail) use ($order) {
                    // ✅ PHASE 2: Validate status transition
                    if (!$order instanceof Order) {
                        return;
                    }
                    
                    $currentStatus = $order->status;
                    $validTransitions = [
                        'pending' => ['paid', 'cancelled'],
                        'paid' => ['processing', 'completed', 'cancelled'],
                        'processing' => ['completed', 'cancelled', 'needs_revision'],
                        'needs_revision' => ['processing', 'cancelled'],
                        'completed' => [], // Cannot transition from completed
                        'cancelled' => [], // Cannot transition from cancelled
                    ];
                    
                    if (!isset($validTransitions[$currentStatus]) || 
                        !in_array($value, $validTransitions[$currentStatus])) {
                        \App\Services\SecurityLogger::logBusinessLogicViolation('Invalid status transition', [
                            'order_id' => $order->id,
                            'current_status' => $currentStatus,
                            'attempted_status' => $value,
                        ]);
                        $fail("Status tidak dapat diubah dari {$currentStatus} ke {$value}");
                    }
                },
            ],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}







