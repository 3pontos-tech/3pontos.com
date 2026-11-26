<?php

declare(strict_types=1);

namespace TresPontosTech\Consultoria\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
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

        RateLimiter::for('consultoria-contato', fn (Request $request): Limit => Limit::perMinute(
            config()->integer('consultoria.contato.limite_por_minuto', 5),
        )->by((string) $request->ip()));

        // Dados de SEO e do formulário compartilhados com as views da página.
        View::composer('consultoria::*', function (\Illuminate\View\View $view) use ($domain): void {
            $base = config()->string('consultoria.url', '');

            $view->with('consultoria', [
                'url' => mb_rtrim($base !== '' ? $base : 'https://'.$domain, '/'),
                'indexavel' => config()->boolean('consultoria.indexavel'),
                'politicaUrl' => config()->string('consultoria.politica_privacidade_url', ''),
            ]);
        });

        Route::middleware(['web', ConsultoriaHeaders::class])
            ->domain($domain)
            ->group(__DIR__.'/../../routes/domain/web.php');
    }
}
