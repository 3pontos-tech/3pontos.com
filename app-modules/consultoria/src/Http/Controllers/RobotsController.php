<?php

declare(strict_types=1);

namespace TresPontosTech\Consultoria\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController
{
    public function __invoke(): Response
    {
        $linhas = config()->boolean('consultoria.indexavel')
            ? ['User-agent: *', 'Allow: /']
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $linhas)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
