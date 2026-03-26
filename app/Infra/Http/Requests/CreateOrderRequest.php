<?php

namespace App\Infra\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'destination' => 'required|string',
            'departureDate' => 'required|date',
            'arrivalDate' => 'required|date|after:departureDate',
        ];
    }
}
