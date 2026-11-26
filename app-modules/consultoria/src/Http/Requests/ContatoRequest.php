<?php

declare(strict_types=1);

namespace TresPontosTech\Consultoria\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContatoRequest extends FormRequest
{
    /**
     * Assuntos aceitos, espelho do select do formulário.
     *
     * @var list<string>
     */
    public const array ASSUNTOS = [
        'IA · AI Discovery Day',
        'IA · Copilot e agentes',
        'IA · Segundo cérebro da empresa',
        'IA · Treinamento em IA e dados',
        'IA · Governança, LGPD e segurança',
        'IA · Infraestrutura (em breve)',
        'Licenças Adobe para empresas',
        'Licenças Microsoft para empresas',
        'Licenças de software',
        'Consultoria · Diagnóstico e metodologia',
        'Consultoria em dados e BI',
        'Desenvolvimento de software',
        'Nuvem e infraestrutura',
        'Talentos de tecnologia',
        'Setor público',
        'Parceria / co-venda',
        'Outro assunto',
    ];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'regex:/^[^\s@]+@[^\s@]+\.[a-z]{2,}$/i', 'max:160'],
            'empresa' => ['nullable', 'string', 'max:160'],
            'interesse' => ['required', 'string', Rule::in(self::ASSUNTOS)],
            'mensagem' => ['nullable', 'string', 'max:600'],
            'origem' => ['nullable', 'string', 'max:120'],
            'lgpd' => ['accepted'],
            'site' => ['nullable', 'string'],
        ];
    }

    /**
     * Campo isca: pessoas não veem, robôs preenchem.
     */
    public function ehRobo(): bool
    {
        return $this->filled('site');
    }
}
