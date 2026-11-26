<?php

declare(strict_types=1);

function consultoriaHome(): string
{
    return test()->get('http://consultoria.3pontos.test/')->assertOk()->getContent();
}

it('renders every section of the home in the expected theme rhythm', function (): void {
    preg_match_all('/<(?:section|footer) class="([a-z\- ]+)"[^>]*?data-theme="(dark|light)"/s', consultoriaHome(), $matches, PREG_SET_ORDER);

    $order = array_map(fn (array $m): string => explode(' ', $m[1])[0].':'.$m[2], $matches);

    expect($order)->toBe([
        'hero:dark', 'band:dark', 'stance:dark', 'ia:dark', 'cases:light', 'rail:dark',
        'flow:light', 'story:dark', 'coda:dark', 'cat:light', 'callout:light', 'suite:dark',
        'suite:light', 'proc:dark', 'aud:light', 'manifesto:dark', 'faq:light', 'contact:dark', 'foot:dark',
    ]);
});

it('has the anchors used by the navigation', function (string $id): void {
    expect(consultoriaHome())->toContain('id="'.$id.'"');
})->with([
    'inicio', 'posicionamento', 'ia', 'ia-casos', 'ia-niveis', 'ia-90-dias', 'consultoria',
    'como-trabalhamos', 'metodologia', 'licencas', 'adobe', 'microsoft', 'processo', 'clientes', 'faq', 'contato',
]);

it('lists the four agent levels and the governance line', function (): void {
    $html = consultoriaHome();

    expect(mb_substr_count($html, 'class="ladder__row"'))->toBe(4)
        ->and($html)->toContain('Governança desde o primeiro agente: fontes autorizadas, uma pessoa sempre disponível e custo monitorado.');
});

it('shows the 90 day journey with four phases and the discovery day card', function (): void {
    $html = consultoriaHome();

    expect(mb_substr_count($html, 'class="journey__step"'))->toBe(4)
        ->and($html)->toContain('Primeiro dia gratuito', 'Protótipo em 3 semanas', 'Piloto em 90 dias')
        ->and($html)->toContain('data-interesse="IA · AI Discovery Day" data-origem="Seção de IA · AI Discovery Day"');
});

it('draws the double diamond with four named phases', function (): void {
    $html = consultoriaHome();

    expect(mb_substr_count($html, 'class="sd__phase"'))->toBe(4)
        ->and($html)->toContain('role="img"')
        ->and($html)->toContain('Duplo diamante');
});

it('has five panels in the horizontal rail, with talents after infrastructure', function (): void {
    $html = consultoriaHome();

    expect(mb_substr_count($html, '<article class="panel'))->toBe(5)
        ->and(mb_strpos($html, 'Especialistas certos, no ritmo do seu projeto.'))
        ->toBeGreaterThan(mb_strpos($html, 'Um olhar técnico para a decisão certa.'))
        ->toBeLessThan(mb_strpos($html, 'Seu cenário não está aqui?'));
});

it('has 7 general and 11 AI questions in the FAQ, with matching counters', function (): void {
    $html = consultoriaHome();

    preg_match('/id="faq-p1".*?id="faq-p2"(.*?)<\/section>/s', $html, $m);
    $general = mb_substr_count($m[0], '<details') - mb_substr_count($m[1], '<details');

    expect($general)->toBe(7)
        ->and(mb_substr_count($m[1], '<details'))->toBe(11)
        ->and($html)->toContain('<b>07</b>', '<b>11</b>');
});

it('keeps the FAQPage JSON-LD in sync with the 11 AI answers', function (): void {
    $html = consultoriaHome();

    preg_match('/<script type="application\/ld\+json">\s*(\{.*?"FAQPage".*?\})\s*<\/script>/s', $html, $m);
    $data = json_decode($m[1], true, flags: JSON_THROW_ON_ERROR);

    expect($data['mainEntity'])->toHaveCount(11);

    foreach ($data['mainEntity'] as $question) {
        expect($html)->toContain('<span>'.$question['name'].'</span>')
            ->and($html)->toContain($question['acceptedAnswer']['text']);
    }
});

it('exposes tab, menu, skip link and live region semantics', function (): void {
    $html = consultoriaHome();

    expect($html)->toContain('<a class="skip" href="#inicio">Pular para o conteúdo</a>')
        ->and($html)->toContain('id="burger" aria-expanded="false" aria-controls="menu"')
        ->and($html)->toContain('role="tablist" aria-label="Assuntos das dúvidas"')
        ->and($html)->toContain('aria-live="polite"')
        ->and(mb_substr_count($html, 'role="tab"'))->toBe(17)
        ->and(mb_substr_count($html, 'role="tabpanel"'))->toBe(17);
});

it('ships a reduced motion fallback in css and js', function (): void {
    $css = file_get_contents(public_path('consultoria/css/style.css'));
    $js = file_get_contents(public_path('consultoria/js/app.js'));

    expect($css)->toContain('@media (prefers-reduced-motion:reduce)')
        ->and($css)->toContain(':focus-visible{outline:2px solid')
        ->and($js)->toContain("matchMedia('(prefers-reduced-motion: reduce)')");
});
