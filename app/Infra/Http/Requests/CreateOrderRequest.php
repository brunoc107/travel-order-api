<?php

namespace App\Infra\Http\Requests;

use Whoops\Exception\Formatter;

class CreateOrderRequest extends Formatter
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
