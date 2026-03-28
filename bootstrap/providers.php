<?php

use App\Infra\Providers\AppServiceProvider;
use App\Infra\Providers\OrderServiceProvider;
use L5Swagger\L5SwaggerServiceProvider;

return [
    AppServiceProvider::class,
    OrderServiceProvider::class,
    L5SwaggerServiceProvider::class,
];
