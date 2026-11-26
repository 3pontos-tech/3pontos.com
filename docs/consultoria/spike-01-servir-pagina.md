# Spike 01: como servir a página em consultoria.3pontos.com

Story do Monday: 3PT - STORY - 01 (épico 3PT - ÉPICO - 01, Site Consultoria 3Pontos).

Branch: `feat/consultoria-site`.

## O que foi lido no repositório

- O projeto é Laravel 12 com PHP 8.4, Filament 4, Vite 7, Tailwind 4 e módulos locais em `app-modules` (pacote internachi/modular). Os namespaces dos módulos são `TresPontosTech\*`.
- `routes/web.php` está vazio. O `/` da home atual não vem dele.
- A home de 3pontos.com é uma página do Filament: `TresPontosTech\Portal\Pages\PortalPage`, registrada no painel `guest` (`App\Providers\Filament\GuestPanelProvider`) com `->path('')`. Ou seja, o painel guest já ocupa a raiz de qualquer domínio que apontar para a aplicação.
- O painel `admin` fica em `/admin`.
- A view da home é `app-modules/portal/resources/views/homepage.blade.php`, com seções em `components/sections/*`. Os componentes de interface e o tema 3pontos ficam no módulo `he4rt`.
- O Vite compila apenas os temas do Filament, o tema do módulo `he4rt`, `resources/css/app.css` e `resources/js/app.js` (ver `vite.config.js`).
- `public/` tem `images`, `videos`, `robots.txt` e `favicon.ico`.
- Não há configuração de deploy, DNS ou servidor neste repositório. O CI (`.github/workflows`) roda Pint, Rector, PHPStan e Pest, e nenhum deploy.
- Ambiente local verificado: não há PHP nem `vendor` instalados na máquina usada para este spike. Por isso nada abaixo foi executado, tudo vem de leitura de código. Os itens marcados como "a verificar" precisam ser testados com `php artisan route:list` antes de implementar.

## O que o protótipo exige

- HTML, CSS e JavaScript puros, sem build, com 2 páginas (`index.html` e `licitacoes.html`), imagens e fontes do Google.
- Um motor de scroll e animação próprio (`js/app.js`) que depende do CSS próprio (`css/style.css`). Passar isso por Tailwind ou pelos componentes do Filament mudaria o resultado visual.
- Por isso a página precisa ser servida como HTML standalone, sem herdar o layout do Filament.

## Opções

### A. Módulo novo `consultoria` com rota por domínio (recomendada)

- Criar o módulo `app-modules/consultoria` (`php artisan make:module consultoria`).
- Registrar as rotas no próprio módulo, dentro de `Route::domain(config('consultoria.domain'))`, com 2 rotas: `/` e `/licitacoes`.
- Colocar as duas páginas como views Blade standalone (sem layout do Filament), com o conteúdo do protótipo.
- Servir CSS, JavaScript e imagens como estáticos em `public/consultoria/` (cópia exata do protótipo), com `asset('consultoria/...')`. Assim a animação não passa por nenhum pipeline.
- Restringir o painel `guest` ao domínio principal com `->domain(...)`, para a raiz do subdomínio não cair na home atual (a verificar: ordem de resolução entre a rota por domínio do módulo e a rota `/` do Filament).
- Prós: isolado do resto da aplicação, fiel ao protótipo, testável com Pest, deploy junto com o app.
- Contras: duplica fontes e imagens fora do Vite; precisa de ajuste de domínio no painel guest.

### B. Painel Filament dedicado com `->domain()`

- Um novo `PanelProvider` apenas para a consultoria.
- Prós: segue o padrão atual do repositório.
- Contras: o Filament carrega layout, scripts e estilos próprios e atrapalha o motor de animação. Não recomendada.

### C. Site estático separado (Netlify) apontando o subdomínio

- O protótipo já traz `netlify.toml`, página 404 e `robots.txt`.
- Prós: zero código no Laravel, deploy independente.
- Contras: foge do repositório, do CI e do versionamento da equipe; formulário e medição ficam fora da aplicação.

## Recomendação

Opção A. Mantém a página dentro do repositório, preserva o protótipo exatamente como está e deixa o formulário pronto para ganhar um endpoint real no próprio Laravel (hoje o protótipo apenas simula o envio).

## Decisões

Respondidas pelo PO (Renan):

- A página de consultoria é uma página nova da 3Pontos, além da página principal de hoje. A home atual (Portal, painel `guest`) continua como está, no domínio principal. A consultoria vive em `consultoria.3pontos.com`, ao lado dela.
- Consequência técnica: as duas páginas convivem na mesma aplicação, separadas por domínio. Por isso a restrição do painel `guest` ao domínio principal passa de "cuidado" para requisito da opção A.
- Consequência de SEO: o canônico da consultoria é `https://consultoria.3pontos.com/`. O protótipo traz `https://www.3pontos.com.br/` e precisa ser trocado (STORY 22). As duas páginas não podem declarar o mesmo canônico.

Ainda em aberto, dependem do time:

1. Onde roda a produção de 3pontos.com e quem controla o DNS (não há essa informação no repositório). Sem isso não dá para criar o registro do subdomínio e o HTTPS.
2. Qual e-mail e qual endpoint recebem o formulário (hoje `contato@3pontos.com.br`, envio simulado).
3. Onde fica a Política de Privacidade (o formulário passa a exigir consentimento e hoje o link é provisório).

## Próximos passos (stories do épico)

- STORY 02: criar o módulo, as rotas por domínio, a página 404, o `robots.txt` e os cabeçalhos de cache e segurança, com a página ainda vazia.
- STORY 03 e seguintes: portar o protótipo v2 (`index.html`, `licitacoes.html`, `css/`, `js/`, `img/`) para o módulo, seção por seção.
- Critério de pronto do spike: este documento revisado por quem cuida do deploy e as decisões em aberto 1 a 3 respondidas.

## Confirmado com `php artisan route:list`

Antes da STORY 02, `GET /` responde `filament.guest.pages.portal-page` sem restrição de domínio. Qualquer domínio que apontar para a aplicação cai na home principal. Por isso a STORY 02 restringe os painéis ao domínio principal (`APP_MAIN_DOMAIN`) e registra as rotas da consultoria só quando `CONSULTORIA_DOMAIN` está definido.

## Como testar localmente (STORY 02)

1. Rodar `composer update 3pontos/consultoria` para registrar o módulo novo.
2. No `.env`, definir `APP_MAIN_DOMAIN=localhost` e `CONSULTORIA_DOMAIN=consultoria.localhost`.
3. Subir com `php artisan serve` e abrir `http://localhost:8000` (home principal) e `http://consultoria.localhost:8000` (consultoria). Os navegadores resolvem `*.localhost` para a própria máquina.
4. Rodar `php artisan route:list` e conferir que as rotas `consultoria.home` e `consultoria.licitacoes` aparecem com o domínio, e que `filament.guest.pages.portal-page` também aparece com o domínio principal.
5. Rodar Pint, PHPStan e Pest (`./vendor/bin/pint`, `./vendor/bin/phpstan analyse`, `./vendor/bin/pest`).

Com as variáveis vazias (padrão do `.env.example`), nada muda: os painéis respondem em qualquer domínio e a consultoria não é registrada.
