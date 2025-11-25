<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\View\View;
use TresPontosTech\Portal\Pages\PortalPage;

final class GuestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('guest')
            ->path('')
            ->colors([
                'primary' => Color::Purple,
            ])
            ->defaultThemeMode(ThemeMode::Dark)
            ->topNavigation()
            ->brandLogo(fn (): View => view('he4rt::components.logo'))
            ->renderHook(PanelsRenderHook::SIDEBAR_NAV_END, fn () => Blade::render(<<<'BLADE'
               @guest
                    <div class="flex flex-col md:hidden mt-auto items-center space-y-4">
                        <x-he4rt::button icon="heroicon-s-arrow-top-right-on-square" variant="outline">
                            Github
                        </x-he4rt::button>

                        <x-he4rt::button icon-position="leading" icon="heroicon-o-user">Entrar agora</x-he4rt::button>
                    </div>
               @endguest
            BLADE
            ))
            ->renderHook(PanelsRenderHook::TOPBAR_END, fn () => Blade::render(<<<'BLADE'
               @guest
                    <div class="hidden md:flex items-center space-x-4">
                        <x-he4rt::button icon="heroicon-s-arrow-top-right-on-square" variant="outline">
                            Github
                        </x-he4rt::button>

                        <x-he4rt::button icon-position="leading" icon="heroicon-o-user">Entrar agora</x-he4rt::button>
                    </div>
               @endguest
            BLADE
            ))
            ->navigationItems([
                NavigationItem::make('Home')
                    ->url('#hero')
                    ->sort(0),
                NavigationItem::make('Nossos pilares')
                    ->url('#foundations')
                    ->sort(1),
                NavigationItem::make('Missão social')
                    ->url('#social-action')
                    ->sort(2),
                NavigationItem::make('Projects')
                    ->url('#projects')
                    ->sort(3),
                NavigationItem::make('Comunidade')
                    ->url('#community')
                    ->sort(4),
                NavigationItem::make('Eventos')
                    ->url('#events')
                    ->sort(5),
                NavigationItem::make('Contato')
                    ->url('#contact')
                    ->sort(6),
            ])
            ->viteTheme('app-modules/he4rt/resources/css/themes/3pontos/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                PortalPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ]);
    }
}
