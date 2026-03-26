<?php

namespace App\Infra\View\Components\mail;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class order_table extends Component
{
    public function __construct(
        public string $orderId,
        public string $destination,
        public string $departureDate,
        public string $arrivalDate,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.mail.order-table');
    }
}
