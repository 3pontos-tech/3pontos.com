<?php

declare(strict_types=1);

namespace TresPontosTech\Portal\Pages;

use Filament\Pages\Dashboard;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class PortalPage extends Dashboard
{
    protected string $view = 'portal::homepage';

    protected static bool $shouldRegisterNavigation = false;

    protected string $description = 'Somos o ecossistema que une solução e conhecimento em um único lugar Aceleramos sua empresa. Fortalecemos sua carreira.';

    protected static ?string $title = 'Home';

    protected string $coverImage;

    protected string $url;

    protected Width|string|null $maxContentWidth = Width::Full;

    public function mount(): void
    {
        $this->coverImage = asset('images/seo.png');
        $this->url = url()->current();

        $this->registerMetaTags();
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    protected function registerMetaTags(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_START,
            fn (): string => Blade::render('components.metatags', [
                'url' => $this->url,
                'title' => $this->getTitle(),
                'description' => $this->description,
                'coverImage' => $this->coverImage,
            ]),
        );
    }
}
