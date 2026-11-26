<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use TresPontosTech\Consultoria\Mail\ContatoRecebido;

const CONTATO_URL = 'http://consultoria.3pontos.test/contato';

/**
 * @param  array<string, mixed>  $override
 * @return array<string, mixed>
 */
function contatoPayload(array $override = []): array
{
    return array_merge([
        'nome' => 'Maria Souza',
        'email' => 'maria@empresa.com.br',
        'empresa' => 'Empresa SA',
        'interesse' => 'IA · AI Discovery Day',
        'mensagem' => 'Queremos um diagnóstico.',
        'origem' => 'Seção de IA · AI Discovery Day',
        'lgpd' => 'on',
        'site' => '',
    ], $override);
}

beforeEach(function (): void {
    RateLimiter::clear('consultoria-contato');
    Mail::fake();
});

it('sends the lead by e-mail to the configured recipient', function (): void {
    config()->set('consultoria.contato.destinatario', 'comercial@exemplo.test');

    $this->postJson(CONTATO_URL, contatoPayload())->assertOk()->assertJson(['ok' => true]);

    Mail::assertSent(ContatoRecebido::class, fn (ContatoRecebido $mail): bool => $mail->hasTo('comercial@exemplo.test')
        && $mail->hasReplyTo('maria@empresa.com.br')
        && $mail->lead['interesse'] === 'IA · AI Discovery Day'
        && $mail->lead['origem'] === 'Seção de IA · AI Discovery Day');
});

it('defaults the recipient to contato@3pontos.com', function (): void {
    expect(config('consultoria.contato.destinatario'))->toBe('contato@3pontos.com');
});

it('renders the lead data and the lgpd consent in the e-mail', function (): void {
    $mail = new ContatoRecebido(
        ['nome' => 'Maria', 'email' => 'm@e.com', 'empresa' => null, 'interesse' => 'Talentos de tecnologia', 'mensagem' => 'Olá & tudo', 'origem' => null],
        '2026-01-01T00:00:00+00:00',
    );

    $mail->assertSeeInText('Talentos de tecnologia');
    $mail->assertSeeInText('Consentimento LGPD: sim');
    $mail->assertSeeInText('Olá & tudo');
});

it('accepts the new subjects', function (string $assunto): void {
    $this->postJson(CONTATO_URL, contatoPayload(['interesse' => $assunto]))->assertOk();

    Mail::assertSent(ContatoRecebido::class);
})->with(['IA · AI Discovery Day', 'Talentos de tecnologia']);

it('rejects invalid input without sending', function (array $override, string $campo): void {
    $this->postJson(CONTATO_URL, contatoPayload($override))->assertUnprocessable()->assertJsonValidationErrors($campo);

    Mail::assertNothingSent();
})->with([
    'sem nome' => [['nome' => ''], 'nome'],
    'e-mail inválido' => [['email' => 'nome@empresa'], 'email'],
    'sem assunto' => [['interesse' => ''], 'interesse'],
    'assunto desconhecido' => [['interesse' => 'Qualquer coisa'], 'interesse'],
    'mensagem longa demais' => [['mensagem' => str_repeat('a', 601)], 'mensagem'],
    'sem consentimento' => [['lgpd' => null], 'lgpd'],
]);

it('answers success to the honeypot but sends nothing', function (): void {
    $this->postJson(CONTATO_URL, contatoPayload(['site' => 'http://spam.test']))->assertOk();

    Mail::assertNothingSent();
});

it('rate limits the endpoint', function (): void {
    config()->set('consultoria.contato.limite_por_minuto', 2);
    // O limitador lê o limite na hora da requisição.
    foreach (range(1, 2) as $_) {
        $this->postJson(CONTATO_URL, contatoPayload())->assertOk();
    }

    $this->postJson(CONTATO_URL, contatoPayload())->assertTooManyRequests();
});

it('wires the page form to the endpoint with csrf, consent and the new subjects', function (string $path): void {
    $this->get('http://consultoria.3pontos.test'.$path)
        ->assertSee('action="http://consultoria.3pontos.test/contato"', false)
        ->assertSee('name="_token"', false)
        ->assertSee('name="lgpd" type="checkbox" required', false)
        ->assertSee('IA · AI Discovery Day')
        ->assertSee('Talentos de tecnologia');
})->with(['/', '/licitacoes']);

it('keeps the privacy link as placeholder until a url is configured', function (): void {
    $this->get('http://consultoria.3pontos.test/')
        ->assertSee('href="#politica-de-privacidade" data-placeholder="politica-de-privacidade"', false);

    config()->set('consultoria.politica_privacidade_url', 'https://exemplo.test/privacidade');

    $this->get('http://consultoria.3pontos.test/')
        ->assertSee('href="https://exemplo.test/privacidade"', false)
        ->assertDontSee('data-placeholder="politica-de-privacidade"', false);
});

it('points the page script at the real endpoint instead of the simulation', function (): void {
    $js = (string) file_get_contents(public_path('consultoria/js/app.js'));

    expect($js)->toContain('fetch(form.action')->not->toContain('setTimeout(resolve, 520)');
});
