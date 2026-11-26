<?php

declare(strict_types=1);

it('serves the consultoria home on its own domain', function (): void {
    $this->get('http://consultoria.3pontos.test/')
        ->assertOk()
        ->assertSee('<title>3Pontos | Consultoria em tecnologia e IA', false);
});

it('serves the licitacoes page on its own domain', function (): void {
    $this->get('http://consultoria.3pontos.test/licitacoes')
        ->assertOk()
        ->assertSee('<title>', false)
        ->assertSee('Licitações');
});

it('sends the security headers', function (): void {
    $this->get('http://consultoria.3pontos.test/')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
});

it('does not expose the admin panel on the consultoria domain', function (): void {
    $this->get('http://consultoria.3pontos.test/admin/login')->assertNotFound();
});

it('keeps the pages out of search engines until publication', function (string $path): void {
    $this->get('http://consultoria.3pontos.test'.$path)
        ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
})->with(['/', '/licitacoes']);

it('loads css, js and images from the static copy of the prototype', function (string $path): void {
    $this->get('http://consultoria.3pontos.test'.$path)
        ->assertSee('href="http://consultoria.3pontos.test/consultoria/css/style.css"', false)
        ->assertSee('src="http://consultoria.3pontos.test/consultoria/js/app.js"', false)
        ->assertSee('src="http://consultoria.3pontos.test/consultoria/img/cromo-trio.webp"', false);
})->with(['/', '/licitacoes']);

it('links the two pages through named routes', function (): void {
    $this->get('http://consultoria.3pontos.test/')->assertSee('href="/licitacoes"', false);
    $this->get('http://consultoria.3pontos.test/licitacoes')->assertSee('href="/#consultoria"', false);
});

it('ships every static file the pages reference', function (string $path): void {
    $html = $this->get('http://consultoria.3pontos.test'.$path)->getContent();

    preg_match_all('#/consultoria/((?:css|js|img)/[^"\')\s]+)#', (string) $html, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_unique($matches[1]) as $file) {
        expect(public_path('consultoria/'.$file))->toBeFile();
    }
})->with(['/', '/licitacoes']);
