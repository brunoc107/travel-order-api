<?php

namespace App\Infra\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CreateOrderRequest',
    required: ['destination', 'departureDate', 'arrivalDate'],
    type: 'object'
)]
class CreateOrderRequest extends FormRequest
{
    #[OA\Property(
        property: 'destination',
        description: 'Destination of the order',
        type: 'string',
        example: 'São Paulo - SP'
    )]
    public string $destination;

    #[OA\Property(
        property: 'departureDate',
        description: 'Departure date of the order',
        type: 'string',
        example: '2026-05-21'
    )]
    public string $departureDate;

    #[OA\Property(
        property: 'arrivalDate',
        description: 'Arrival date of the order',
        type: 'string',
        example: '2026-05-27'
    )]
    public string $arrivalDate;

    public function rules(): array
    {
        return [
            'destination' => 'required|string',
            'departureDate' => 'required|date|date_format:Y-m-d',
            'arrivalDate' => 'required|date|after:departureDate|date_format:Y-m-d',
        ];
    }
}
