<?php

declare(strict_types=1);

namespace TresPontosTech\Consultoria\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use TresPontosTech\Consultoria\Http\Middleware\ConsultoriaHeaders;

class ConsultoriaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/consultoria.php', 'consultoria');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'consultoria');

        $domain = config()->string('consultoria.domain', '');

        if ($domain === '') {
            return;
        }

        Route::middleware(['web', ConsultoriaHeaders::class])
            ->domain($domain)
            ->group(__DIR__.'/../../routes/domain/web.php');
    }
}
