<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Domínio da página de consultoria
    |--------------------------------------------------------------------------
    |
    | As rotas da consultoria só são registradas quando este domínio está
    | definido (ex.: consultoria.3pontos.com). Vazio significa que a página não
    | existe naquele ambiente, e a home principal continua sozinha na raiz.
    |
    */
    'domain' => env('CONSULTORIA_DOMAIN', ''),

    /*
    |--------------------------------------------------------------------------
    | URL pública
    |--------------------------------------------------------------------------
    |
    | Base absoluta usada em canonical, og:url e og:image. Quando vazia, deriva
    | de https:// + domínio.
    |
    */
    'url' => env('CONSULTORIA_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Indexação
    |--------------------------------------------------------------------------
    |
    | Enquanto false, as páginas saem com "noindex, nofollow" e o robots.txt
    | bloqueia tudo. Ligue (true) só quando a publicação for decidida.
    |
    */
    'indexavel' => (bool) env('CONSULTORIA_INDEXAVEL', false),

    /*
    |--------------------------------------------------------------------------
    | Formulário de contato
    |--------------------------------------------------------------------------
    */
    'contato' => [
        // Quem recebe as solicitações do formulário.
        'destinatario' => env('CONSULTORIA_CONTATO_EMAIL', 'contato@3pontos.com'),
        // Tentativas por minuto, por IP.
        'limite_por_minuto' => (int) env('CONSULTORIA_CONTATO_LIMITE', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Política de Privacidade
    |--------------------------------------------------------------------------
    |
    | URL da política publicada (decisão em aberto, STORY 33). Vazia mantém o
    | link como espaço reservado (#politica-de-privacidade).
    |
    */
    'politica_privacidade_url' => env('CONSULTORIA_POLITICA_URL', ''),
];
