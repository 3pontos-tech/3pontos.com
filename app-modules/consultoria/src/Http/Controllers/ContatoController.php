<?php

declare(strict_types=1);

namespace TresPontosTech\Consultoria\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use TresPontosTech\Consultoria\Http\Requests\ContatoRequest;
use TresPontosTech\Consultoria\Mail\ContatoRecebido;

class ContatoController
{
    public function __invoke(ContatoRequest $request): JsonResponse
    {
        // Isca preenchida é robô: responde como sucesso e descarta.
        if ($request->ehRobo()) {
            return response()->json(['ok' => true]);
        }

        /** @var array{nome: string, email: string, empresa: ?string, interesse: string, mensagem: ?string, origem: ?string} $lead */
        $lead = $request->safe()->only(['nome', 'email', 'empresa', 'interesse', 'mensagem', 'origem']);

        Mail::to(config()->string('consultoria.contato.destinatario'))
            ->send(new ContatoRecebido($lead, now()->toIso8601String()));

        return response()->json(['ok' => true]);
    }
}
