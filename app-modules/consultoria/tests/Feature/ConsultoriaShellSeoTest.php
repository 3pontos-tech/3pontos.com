<?php

declare(strict_types=1);

it('serves the canonical, og:url and og:image of the consultoria domain', function (string $path, string $url): void {
    $this->get('http://consultoria.3pontos.test'.$path)
        ->assertSee('<link rel="canonical" href="https://consultoria.3pontos.test'.$url.'">', false)
        ->assertSee('<meta property="og:url" content="https://consultoria.3pontos.test'.$url.'">', false)
        ->assertSee('<meta property="og:image" content="https://consultoria.3pontos.test/consultoria/img/og.jpg">', false)
        ->assertDontSee('www.3pontos.com.br');
})->with([['/', '/'], ['/licitacoes', '/licitacoes']]);

it('allows indexing only when the flag is on', function (): void {
    config()->set('consultoria.indexavel', true);

    $this->get('http://consultoria.3pontos.test/')
        ->assertSee('<meta name="robots" content="index, follow">', false);
});

it('ships the FAQPage json-ld with the 11 AI questions', function (): void {
    $html = (string) $this->get('http://consultoria.3pontos.test/')->getContent();

    preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);
    $json = json_decode($m[1], true, flags: JSON_THROW_ON_ERROR);

    expect($json['@type'])->toBe('FAQPage')->and($json['mainEntity'])->toHaveCount(11);
});

it('serves the custom 404 with noindex on the consultoria domain', function (): void {
    $this->get('http://consultoria.3pontos.test/pagina-inexistente')
        ->assertNotFound()
        ->assertSee('Esta página não existe.')
        ->assertSee('Voltar ao início')
        ->assertSee('<meta name="robots" content="noindex">', false)
        ->assertHeader('X-Content-Type-Options', 'nosniff');
});

it('serves robots.txt by route, blocking everything until indexing is enabled', function (): void {
    $this->get('http://consultoria.3pontos.test/robots.txt')
        ->assertOk()
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee('Disallow: /', false);

    config()->set('consultoria.indexavel', true);

    $this->get('http://consultoria.3pontos.test/robots.txt')
        ->assertSee("User-agent: *\nAllow: /", false);
});

it('sends the permissions policy header', function (): void {
    $this->get('http://consultoria.3pontos.test/')
        ->assertHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
});

it('keeps the measurement events wired in the script', function (): void {
    $js = (string) file_get_contents(public_path('consultoria/js/app.js'));

    expect($js)->toContain("evento('clique_contato'")
        ->toContain("evento('lead_enviado'")
        ->toContain("evento('ver_secao_ia'");
});
