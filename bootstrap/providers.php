<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\GuestPanelProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    GuestPanelProvider::class,
];
