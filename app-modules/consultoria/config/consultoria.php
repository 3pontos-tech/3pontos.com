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
];
