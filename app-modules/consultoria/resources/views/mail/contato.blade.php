Nova solicitação pelo site da consultoria

Nome: {!! $lead['nome'] !!}
E-mail: {!! $lead['email'] !!}
Empresa ou órgão: {!! $lead['empresa'] ?: '-' !!}
Assunto: {!! $lead['interesse'] !!}
Origem: {!! $lead['origem'] ?: 'Site' !!}
Consentimento LGPD: sim, em {!! $consentimentoEm !!}

Mensagem:
{!! $lead['mensagem'] ?: '-' !!}
