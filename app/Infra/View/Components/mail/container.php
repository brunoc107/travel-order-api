<?php

namespace App\Infra\View\Components\mail;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class container extends Component
{
    public function __construct(
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.mail.container');
    }
}
