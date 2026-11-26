<?php

declare(strict_types=1);

const LICITACOES_URL = 'http://consultoria.3pontos.test/licitacoes';
const HOME_URL = 'http://consultoria.3pontos.test/';

function licitacoesHtml(): string
{
    return (string) test()->get(LICITACOES_URL)->assertOk()->getContent();
}

/**
 * @return array<int, string>
 */
function idsOf(string $html): array
{
    preg_match_all('/\sid="([^"]+)"/', $html, $matches);

    return $matches[1];
}

it('renders the seven sections in the prototype order', function (): void {
    $html = licitacoesHtml();

    preg_match_all('/<section[^>]*id="([^"]+)"[^>]*data-chapter="(\d{2}) \/ ([^"]+)"/', $html, $matches);

    expect($matches[1])->toBe(['inicio', 'como-atendemos', 'modalidades', 'como-funciona', 'documentacao', 'faq', 'contato'])
        ->and($matches[2])->toBe(['01', '02', '03', '04', '05', '06', '07']);
});

it('wraps the sections in a single main landmark', function (): void {
    $html = licitacoesHtml();

    expect(mb_substr_count($html, '<main'))->toBe(1)
        ->and(mb_substr_count($html, '</main>'))->toBe(1)
        ->and(mb_strpos($html, '<main'))->toBeLessThan((int) mb_strpos($html, 'id="inicio"'));
});

it('has no duplicated ids', function (): void {
    $ids = idsOf(licitacoesHtml());

    expect($ids)->toBe(array_values(array_unique($ids)));
});

it('resolves every internal anchor to an element of the page', function (): void {
    $html = licitacoesHtml();
    $ids = idsOf($html);

    preg_match_all('/href="#([^"]+)"/', $html, $matches);

    // Pendências que a STORY 29 e a STORY 20 resolvem com a política e o LinkedIn.
    $pending = ['politica-de-privacidade', 'linkedin'];

    foreach (array_unique($matches[1]) as $anchor) {
        if (in_array($anchor, $pending, true)) {
            continue;
        }

        expect($ids)->toContain($anchor);
    }
});

it('points the cross-page links to anchors that exist on the home', function (): void {
    $homeIds = idsOf((string) $this->get(HOME_URL)->assertOk()->getContent());

    preg_match_all('/href="\/#([^"]+)"/', licitacoesHtml(), $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_unique($matches[1]) as $anchor) {
        expect($homeIds)->toContain($anchor);
    }
});

it('marks the public sector page as current in the menu and footer', function (): void {
    $html = licitacoesHtml();

    expect(mb_substr_count($html, 'href="/licitacoes" aria-current="page"'))->toBe(2)
        ->and($html)->toContain('href="/"');
});

it('lists the four contracting modalities', function (): void {
    $this->get(LICITACOES_URL)
        ->assertSeeInOrder(['Pregão eletrônico', 'Dispensa de licitação', 'Inexigibilidade', 'Ata de registro de preços'])
        ->assertSee('Do pregão à adesão de ata,', false);

    expect(mb_substr_count(licitacoesHtml(), 'class="suite__card"'))->toBe(4);
});

it('has four buttons for the four steps of how it works', function (): void {
    $html = licitacoesHtml();

    preg_match_all('/data-pin-go="(\d)"/', $html, $matches);

    expect($matches[1])->toBe(['0', '1', '2', '3'])
        ->and($html)->toContain('data-steps="4"');
});

it('lists the three documentation groups with split titles', function (): void {
    $html = licitacoesHtml();

    $section = mb_substr($html, (int) mb_strpos($html, 'id="documentacao"'), 2600);

    expect(mb_substr_count($section, '<h3 data-split>'))->toBe(3);

    $this->get(LICITACOES_URL)
        ->assertSeeInOrder(['Habilitação jurídica', 'Regularidade fiscal e trabalhista', 'Qualificação técnica e econômica']);
});

it('has its own faq with six questions', function (): void {
    expect(mb_substr_count(licitacoesHtml(), 'class="qa"'))->toBe(6);
});

it('ships the contact form with consent, honeypot and the public sector origin', function (): void {
    $html = licitacoesHtml();

    expect($html)
        ->toContain('id="f-lgpd" name="lgpd" type="checkbox" required')
        ->toContain('name="site" type="text" tabindex="-1" autocomplete="off"')
        ->toContain('<option selected>Setor público</option>')
        ->toContain('mailto:contato@3pontos.com')
        ->toContain('tel:+5511981230123');

    preg_match_all('/data-origem="(Setor público · [^"]+)"/', $html, $matches);

    expect($matches[1])->toContain('Setor público · hero', 'Setor público · modalidades');
});

it('keeps the call to action buttons pointed at the form', function (): void {
    $html = licitacoesHtml();

    preg_match_all('/<a class="btn[^"]*"[^>]*href="#contato"[^>]*data-interesse="Setor público"/', $html, $matches);

    expect(count($matches[0]))->toBeGreaterThanOrEqual(4);
});
