<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>3Pontos | Licenças de software para o setor público: licitação, dispensa e pregão</title>
<meta name="description" content="Licenças Microsoft, Google e Adobe para órgãos públicos: pregão eletrônico, dispensa, inexigibilidade e adesão a ata, com documentação de habilitação e nota fiscal emitida no Brasil.">
{{-- Fica fora dos buscadores até a publicação (STORY 22 troca por index, follow). --}}<meta name="robots" content="noindex, nofollow">
<meta name="theme-color" content="#08090A">
<meta property="og:title" content="3Pontos | Licenças de software para o setor público">
<meta property="og:description" content="Pregão, dispensa e adesão a ata de licenças Microsoft, Google e Adobe, com a documentação de habilitação reunida em um só lugar.">
<meta property="og:image" content="https://www.3pontos.com.br/img/og.jpg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="https://www.3pontos.com.br/licitacoes.html">
<meta property="og:locale" content="pt_BR">
<meta name="twitter:card" content="summary_large_image">
<meta property="og:image:alt" content="Render de vidro da identidade da 3Pontos">
<meta property="og:type" content="website">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wdth,wght@0,62..125,100..900;1,62..125,100..900&family=Azeret+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="canonical" href="https://www.3pontos.com.br/licitacoes.html">
<link rel="stylesheet" href="{{ asset('consultoria/css/style.css') }}">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' fill='%2308090A'/%3E%3Cg fill='%23fff'%3E%3Ccircle cx='9' cy='20' r='3'/%3E%3Ccircle cx='16' cy='11' r='3'/%3E%3Ccircle cx='23' cy='20' r='3'/%3E%3C/g%3E%3C/svg%3E">
</head>
<body>

<div class="grain" aria-hidden="true"></div>
<div class="backdrop" aria-hidden="true"></div>

<!-- ░░ LOADER ░░ -->
<div class="loader" id="loader" aria-hidden="true">
  <div class="loader__bar"><span class="loader__fill"></span></div>
  <div class="loader__meta">
    <span class="loader__word">3pontos</span>
    <span class="loader__count"><i>0</i><i>0</i></span>
  </div>
</div>

<!-- ░░ CURSOR ░░ -->
<div class="cursor" aria-hidden="true"><span class="cursor__dot"></span><span class="cursor__ring"></span><span class="cursor__label"></span></div>

<a class="skip" href="#inicio">Pular para o conteúdo</a>

<!-- ░░ NAV ░░
     A navegação principal continua sendo a do index: as três frentes
     moram lá. Aqui ela só ganha o item da página em que se está. -->
<header class="nav" id="nav">
  <div class="nav__bar">
    <a class="nav__brand" href="{{ route('consultoria.home', absolute: false) }}" aria-label="3Pontos, início">
      <svg viewBox="0 0 34 18" aria-hidden="true"><circle cx="5" cy="13" r="4"/><circle cx="17" cy="5" r="4"/><circle cx="29" cy="13" r="4"/></svg>
      <span>3pontos</span>
    </a>
    <nav class="nav__links" aria-label="Navegação principal">
      <a href="{{ route('consultoria.home', absolute: false) }}#consultoria"><span>Consultoria e tecnologia</span></a>
      <a href="{{ route('consultoria.home', absolute: false) }}#licencas"><span>Licença de software</span></a>
      <a href="{{ route('consultoria.licitacoes', absolute: false) }}" aria-current="page"><span>Setor público</span></a>
    </nav>
    <div class="nav__right">
      <a class="btn btn--sm" href="#contato"><span>Pedir cotação</span></a>
      <button class="burger" id="burger" aria-expanded="false" aria-controls="menu" aria-label="Abrir menu"><i></i><i></i></button>
    </div>
  </div>
  <div class="nav__progress"><span id="progress"></span></div>
</header>

<!-- ░░ MENU MOBILE ░░ -->
<div class="menu" id="menu" hidden>
  <nav class="menu__links" aria-label="Navegação">
    <a href="#como-atendemos"><i>01</i><span>Como atendemos</span></a>
    <a href="#modalidades"><i>02</i><span>Modalidades</span></a>
    <a href="#como-funciona"><i>03</i><span>Como funciona</span></a>
    <a href="#documentacao"><i>04</i><span>Documentação</span></a>
    <a href="#faq"><i>05</i><span>Dúvidas</span></a>
    <a href="#contato"><i>06</i><span>Pedir cotação</span></a>
    <a href="{{ route('consultoria.home', absolute: false) }}"><i>07</i><span>Voltar para o site</span></a>
  </nav>
  <div class="menu__foot">
    <a href="mailto:contato@3pontos.com.br">contato@3pontos.com.br</a>
    <a href="tel:+5511981230123">+55 11 98123-0123</a>
  </div>
</div>

<!-- ░░ 01 — HERO ░░ -->
<section class="hero" id="inicio" data-theme="dark" data-chapter="01 / Setor público">
  <div class="hero__inner shell">

    <div class="chrome chrome--img chrome--sys" data-chrome="hero" data-depth="1" aria-hidden="true">
      <span class="chrome__drift">
        <img class="chrome__obj" src="{{ asset('consultoria/img/cromo-trio.webp') }}" alt="" width="2560" height="1440" decoding="async" fetchpriority="high">
        <i class="chrome__flare"><b></b></i>
      </span>
    </div>
    <div class="hero__veil" aria-hidden="true"></div>

    <div class="hero__text">
      <p class="kicker" data-rise>
        <span class="kicker__dot"></span>Setor público · Licitação, dispensa e pregão
      </p>

      <h1 class="hero__title" data-split>
        Licenças de software para o setor público, <em>com documentação em dia.</em>
      </h1>

      <p class="lede" data-rise data-delay="2">
        Licenças Microsoft, Google e Adobe para órgãos e entidades públicas, em pregão,
        dispensa, inexigibilidade ou adesão a ata — com a papelada da habilitação reunida em um
        só lugar e a proposta no formato que o edital pede.
      </p>

      <div class="hero__actions" data-rise data-delay="4">
        <a class="btn btn--solid" href="#contato" data-interesse="Setor público" data-origem="Setor público · hero"><span>Pedir cotação</span><svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3 13L13 3M13 3H5.5M13 3v7.5"/></svg></a>
        <a class="link" href="#documentacao"><span>Ver a documentação</span></a>
      </div>
    </div>
  </div>

  <ul class="ledger" aria-label="O que esta página reúne">
    <li data-rise data-delay="5"><a href="#modalidades"><i>01</i><h3>Modalidades</h3><p>Pregão, dispensa, inexigibilidade e adesão a ata.</p></a></li>
    <li data-rise data-delay="6"><a href="#documentacao"><i>02</i><h3>Documentação</h3><p>Habilitação jurídica, regularidade fiscal e qualificação técnica.</p></a></li>
    <li data-rise data-delay="7"><a href="#como-funciona"><i>03</i><h3>Entrega</h3><p>Licença original, nota fiscal no Brasil e suporte na vigência.</p></a></li>
  </ul>

  <div class="hero__cue" aria-hidden="true"><span>role</span><i></i></div>
</section>

<!-- ░░ 02 — COMO ATENDEMOS ░░ -->
<section class="stance" id="como-atendemos" data-theme="dark" data-chapter="02 / Como atendemos">
  <div class="shell stance__grid">
    <div class="stance__sticky">
      <p class="eyebrow" data-rise>Como atendemos</p>
      <h2 class="h-display h-display--mid" data-split>
        O edital exige fornecedor regular. <em>A 3Pontos chega com a papelada pronta.</em>
      </h2>
      <p class="muted" data-rise data-delay="2">
        Somos revenda autorizada dos principais fabricantes, com acesso pelos maiores
        distribuidores do país. Para o órgão, isso significa licença de origem rastreável, nota
        fiscal emitida no Brasil e um fornecedor que responde durante toda a vigência.
      </p>
      <a class="btn btn--solid stance__cta" href="#contato" data-interesse="Setor público" data-origem="Setor público · como atendemos" data-rise data-delay="3"><span>Falar com o comercial</span><svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3 13L13 3M13 3H5.5M13 3v7.5"/></svg></a>
    </div>

    <ol class="entries" data-stagger data-focus>
      <li class="entry" data-reveal>
        <i>01</i>
        <div>
          <h3 data-split>Proposta no formato do edital</h3>
          <p>Lemos o termo de referência, conferimos a descrição de cada item e devolvemos a
             proposta com marca, part number, quantidade e prazo — do jeito que o pregoeiro
             precisa para julgar.</p>
        </div>
      </li>
      <li class="entry" data-reveal>
        <i>02</i>
        <div>
          <h3 data-split>Dimensionamento antes do certame</h3>
          <p>Licença de servidor, CAL, núcleo, assinatura por usuário: conferimos o que o órgão
             realmente precisa antes da compra, porque licenciamento errado só aparece na
             auditoria.</p>
        </div>
      </li>
      <li class="entry" data-reveal>
        <i>03</i>
        <div>
          <h3 data-split>Entrega e vigência acompanhadas</h3>
          <p>Ativação no ambiente do órgão, documentação da compra para anexar ao processo e
             aviso antes do vencimento, para a renovação não parar a operação.</p>
        </div>
      </li>
    </ol>
  </div>
</section>

<!-- ░░ 03 — MODALIDADES ░░ -->
<section class="suite" id="modalidades" data-theme="light" data-chapter="03 / Modalidades">
  <div class="chrome chrome--adobe chrome--img chrome--clear" data-chrome="modalidades" data-depth=".55" aria-hidden="true">
    <span class="chrome__drift">
      <img class="chrome__obj" src="{{ asset('consultoria/img/cromo-anel.webp') }}" alt="" width="711" height="872" loading="lazy" decoding="async">
      <i class="chrome__flare"><b></b></i>
    </span>
  </div>
  <div class="shell suite__stage">

    <div class="suite__head">
      <div>
        <p class="eyebrow" data-rise>Modalidades de contratação</p>
        <h2 class="h-display h-display--mid h-display--flip" data-split>Do pregão à adesão de ata, <em>a 3Pontos participa.</em></h2>
      </div>
      <p class="muted" data-rise data-delay="2">
        Atendemos as formas de contratação previstas na Lei 14.133/2021, no âmbito federal,
        estadual e municipal. Se a sua unidade compra por um sistema próprio, a gente se
        cadastra nele.
      </p>
    </div>

    <ul class="suite__grid" data-stagger>

      <li class="suite__card" data-reveal>
        <span class="tile"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.2 20.2h15.6"/><path d="M7 17V9.4M12 17V6.2M17 17v-4.6"/></svg></span>
        <p class="suite__kicker">01 · Pregão eletrônico</p>
        <h3>Disputa aberta, com proposta e lance.</h3>
        <p>Participamos do pregão para registro de preços ou para compra imediata, com a
           documentação de habilitação enviada pelo próprio sistema do órgão.</p>
        <ul class="chips" data-stagger><li>Compras.gov.br</li><li>BEC</li><li>Licitações-e</li><li>Portal do órgão</li></ul>
      </li>

      <li class="suite__card" data-reveal>
        <span class="tile"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.4 3.6h7.4l4.3 4.3v12.5H6.4z"/><path d="M13.6 3.8v4.4h4.3"/><path d="M9.4 14.6l1.9 1.9 3.6-3.9"/></svg></span>
        <p class="suite__kicker">02 · Dispensa de licitação</p>
        <h3>Contratação direta, dentro do limite da lei.</h3>
        <p>Enviamos cotação formal com validade, dados da empresa e certidões, no formato que o
           setor de compras precisa para instruir o processo — sem idas e vindas.</p>
        <ul class="chips" data-stagger><li>Cotação formal</li><li>Certidões</li><li>Nota de empenho</li></ul>
      </li>

      <li class="suite__card" data-reveal>
        <span class="tile"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8.2"/><path d="M12 8.4v4.2l2.8 1.7"/></svg></span>
        <p class="suite__kicker">03 · Inexigibilidade</p>
        <h3>Quando só um fornecedor atende.</h3>
        <p>Para software com fornecedor exclusivo, apresentamos a carta do fabricante ou do
           distribuidor e a documentação que comprova a exclusividade e o preço praticado.</p>
        <ul class="chips" data-stagger><li>Carta de exclusividade</li><li>Justificativa de preço</li></ul>
      </li>

      <li class="suite__card" data-reveal>
        <span class="tile"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="7" height="7" rx="1.4"/><rect x="13" y="4" width="7" height="7" rx="1.4"/><rect x="4" y="13" width="7" height="7" rx="1.4"/><rect x="13" y="13" width="7" height="7" rx="1.4"/></svg></span>
        <p class="suite__kicker">04 · Ata de registro de preços</p>
        <h3>Registro de preços e adesão.</h3>
        <p>Participamos do registro e também atendemos adesão de outro órgão, respeitando o
           saldo e as condições da ata vigente.</p>
        <ul class="chips" data-stagger><li>Registro</li><li>Adesão</li><li>Saldo da ata</li></ul>
      </li>

    </ul>

    <div class="suite__foot">
      <ul class="facts" data-stagger>
        <li><i>Fabricantes</i><b>Microsoft · Google · Adobe</b></li>
        <li><i>Entrega</i><b>Digital, por chave ou tenant</b></li>
        <li><i>Nota fiscal</i><b>Emitida no Brasil</b></li>
        <li><i>Suporte</i><b>Durante a vigência</b></li>
      </ul>
      <a class="btn btn--solid" href="#contato" data-interesse="Setor público" data-origem="Setor público · modalidades"><span>Pedir cotação</span><svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3 13L13 3M13 3H5.5M13 3v7.5"/></svg></a>
    </div>

  </div>
</section>

<!-- ░░ 04 — COMO FUNCIONA ░░ -->
<section class="proc pin" id="como-funciona" data-pin="index" data-steps="4" data-per=".5" data-lead=".18" data-theme="light" data-chapter="04 / Como funciona">
  <div class="pin__stage">
   <div class="shell proc__grid">

    <div class="proc__left">
      <p class="eyebrow" data-rise>Como funciona</p>
      <h2 class="h-display h-display--mid" data-split>Do termo de referência à licença ativa, em <em>quatro passos.</em></h2>
      <div class="dial" aria-hidden="true">
        <div class="dial__num"><span data-proc-num>01</span></div>
        <svg class="dial__ring" viewBox="0 0 100 100">
          <circle cx="50" cy="50" r="46" class="dial__track"/>
          <circle cx="50" cy="50" r="46" class="dial__fill" id="dialFill"/>
        </svg>
      </div>
    </div>

    <ol class="proc__steps">
      <li class="step" data-step="01">
        <div class="step__rule"></div>
        <h3 class="step__head"><button type="button" data-pin-go="0"><i>01</i><span>Chega o edital ou a demanda</span></button></h3>
        <div class="step__body"><div>
          <p>Você manda o termo de referência, a planilha de itens ou só a necessidade. A gente
             lê e aponta o que está ambíguo antes de cotar.</p>
          <span class="step__tag">Sem custo</span>
        </div></div>
      </li>
      <li class="step" data-step="02">
        <div class="step__rule"></div>
        <h3 class="step__head"><button type="button" data-pin-go="1"><i>02</i><span>Conferimos e dimensionamos</span></button></h3>
        <div class="step__body"><div>
          <p>Marca, versão, modalidade e quantidade conferidas item a item, com alternativa
             equivalente quando a descrição restringe sem necessidade.</p>
          <span class="step__tag">Item a item</span>
        </div></div>
      </li>
      <li class="step" data-step="03">
        <div class="step__rule"></div>
        <h3 class="step__head"><button type="button" data-pin-go="2"><i>03</i><span>Proposta e habilitação</span></button></h3>
        <div class="step__body"><div>
          <p>Proposta assinada, com validade e condições, acompanhada das certidões e da
             documentação que o edital pedir.</p>
          <span class="step__tag">Até 48h úteis</span>
        </div></div>
      </li>
      <li class="step" data-step="04">
        <div class="step__rule"></div>
        <h3 class="step__head"><button type="button" data-pin-go="3"><i>04</i><span>Entrega, ativação e suporte</span></button></h3>
        <div class="step__body"><div>
          <p>Licença entregue com nota fiscal, ativação acompanhada e um contato só durante
             toda a vigência do contrato.</p>
          <span class="step__tag">Contato dedicado</span>
        </div></div>
      </li>
    </ol>

    <div class="pin__rail" aria-hidden="true"><span data-pin-bar></span></div>
   </div>
  </div>
</section>

<!-- ░░ 05 — DOCUMENTAÇÃO ░░ -->
<section class="stance" id="documentacao" data-theme="dark" data-chapter="05 / Documentação">
  <div class="shell stance__grid">
    <div class="stance__sticky">
      <p class="eyebrow" data-rise>Documentação</p>
      <h2 class="h-display h-display--mid" data-split>
        A papelada da habilitação, <em>reunida em um só lugar.</em>
      </h2>
      <p class="muted" data-rise data-delay="2">
        O que o pregoeiro costuma pedir, organizado para instruir o seu processo. Peça pelo
        formulário e enviamos o pacote atualizado, normalmente no mesmo dia útil.
      </p>
      <a class="btn btn--solid stance__cta" href="#contato" data-interesse="Setor público" data-origem="Setor público · documentação" data-rise data-delay="3"><span>Pedir o pacote de documentos</span><svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3 13L13 3M13 3H5.5M13 3v7.5"/></svg></a>
    </div>

    <ol class="entries" data-stagger data-focus>
      <li class="entry" data-reveal>
        <i>01</i>
        <div>
          <h3 data-split>Habilitação jurídica</h3>
          <p>Contrato social e alterações, inscrição no CNPJ e os dados cadastrais da empresa
             para o sistema de compras do órgão.</p>
        </div>
      </li>
      <li class="entry" data-reveal>
        <i>02</i>
        <div>
          <h3 data-split>Regularidade fiscal e trabalhista</h3>
          <p>Certidões federal, estadual e municipal, FGTS e CNDT — conferidas para estarem
             dentro da validade no dia da sessão.</p>
        </div>
      </li>
      <li class="entry" data-reveal>
        <i>03</i>
        <div>
          <h3 data-split>Qualificação técnica e econômica</h3>
          <p>Atestado de capacidade técnica, balanço e certidão de falência, além dos termos de
             autorização de revenda dos fabricantes.</p>
        </div>
      </li>
    </ol>
  </div>
</section>

<!-- ░░ 06 — DÚVIDAS ░░ -->
<section class="faq" id="faq" data-theme="dark" data-chapter="06 / Dúvidas">
  <div class="mesh" aria-hidden="true"><i></i><i></i></div>
  <div class="shell faq__grid">
    <div class="faq__head">
      <p class="eyebrow" data-rise>Dúvidas do setor público</p>
      <h2 class="h-display h-display--mid" data-split>Antes de <em>abrir o processo.</em></h2>
      <p class="muted" data-rise data-delay="2">Não achou a sua? <a class="link" href="#contato"><span>Pergunte direto</span></a></p>
    </div>

    <div class="faq__col">
      <div class="faq__tabs" role="tablist" aria-label="Assuntos das dúvidas">
        <span class="faq__marker" aria-hidden="true"></span>
        <button role="tab" id="faq-publico" aria-controls="faq-p1" aria-selected="true">
          <i>01</i><span>Setor público</span><b>06</b>
        </button>
      </div>

      <div class="faq__panel is-on" role="tabpanel" id="faq-p1" aria-labelledby="faq-publico" tabindex="0">
        <div class="faq__list" data-stagger>
          <details class="qa" data-reveal open>
            <summary><i>01</i><span>Vocês participam de pregão eletrônico?</span><b aria-hidden="true"></b></summary>
            <div class="qa__body"><p>Sim. Participamos do pregão para registro de preços ou para
              compra imediata, com a habilitação enviada pelo próprio sistema do órgão. Mande o
              número do edital e a gente avalia os itens de software.</p></div>
          </details>
          <details class="qa" data-reveal>
            <summary><i>02</i><span>Dá para comprar por dispensa?</span><b aria-hidden="true"></b></summary>
            <div class="qa__body"><p>Dá. Enviamos cotação formal com validade, dados da empresa e
              certidões, no formato que o setor de compras precisa para instruir o processo de
              contratação direta.</p></div>
          </details>
          <details class="qa" data-reveal>
            <summary><i>03</i><span>Vocês atendem adesão a ata?</span><b aria-hidden="true"></b></summary>
            <div class="qa__body"><p>Sim, respeitando o saldo e as condições da ata vigente. Se a
              sua unidade quiser aderir a uma ata em que fomos registrados, é só pedir a anuência
              pelo formulário.</p></div>
          </details>
          <details class="qa" data-reveal>
            <summary><i>04</i><span>Como é a entrega da licença?</span><b aria-hidden="true"></b></summary>
            <div class="qa__body"><p>Digital: a chave ou a atribuição no ambiente do órgão, com a
              documentação da compra para anexar ao processo e nota fiscal emitida no Brasil.</p></div>
          </details>
          <details class="qa" data-reveal>
            <summary><i>05</i><span>E o suporte depois da entrega?</span><b aria-hidden="true"></b></summary>
            <div class="qa__body"><p>Um contato só durante toda a vigência, para ativação, troca
              de usuário e renovação. Quem cota é quem acompanha.</p></div>
          </details>
          <details class="qa" data-reveal>
            <summary><i>06</i><span>Quais fabricantes vocês fornecem?</span><b aria-hidden="true"></b></summary>
            <div class="qa__body"><p>Microsoft, Google e Adobe são os principais, e cotamos outros
              fabricantes sob demanda. Mande a lista de itens do edital e verificamos a
              disponibilidade pelos distribuidores.</p></div>
          </details>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ░░ 10 — CONTATO ░░ -->
<section class="contact" id="contato" data-theme="dark" data-chapter="07 / Contato">
  <div class="mesh" aria-hidden="true"><i></i><i></i></div>
  <div class="chrome chrome--contact chrome--img" data-chrome="contact" data-depth=".8" aria-hidden="true">
    <span class="chrome__drift">
      <img class="chrome__obj" src="{{ asset('consultoria/img/cromo-trio.webp') }}" alt="" width="2560" height="1440" loading="lazy" decoding="async">
      <i class="chrome__flare"><b></b></i>
    </span>
  </div>
  <div class="shell contact__grid">

    <div class="contact__left">
      <h2 class="h-display" data-split>Tem um edital ou uma dispensa <em>em andamento?</em></h2>
      <p class="lede" data-rise data-delay="2">
        Mande o termo de referência ou a lista de itens. Nosso time comercial responde por
        e-mail com a proposta e a documentação — normalmente no mesmo dia útil.
      </p>
      <ul class="contact__meta" data-stagger data-rise data-delay="3">
        <li><i>E-mail</i><a href="mailto:contato@3pontos.com.br">contato@3pontos.com.br</a></li>
        <li><i>Telefone</i><a href="tel:+5511981230123">+55 11 98123-0123</a></li>
        <li><i>Endereço</i><span>R. Gomes de Carvalho, 1629 — Vila Olímpia, São Paulo, SP</span></li>
      </ul>
    </div>

    <form class="form" id="form" data-stagger novalidate>
      <div class="form__row">
        <div class="field" data-reveal>
          <label for="f-nome">Nome</label>
          <input id="f-nome" name="nome" type="text" required placeholder="Insira seu nome" autocomplete="name">
          <small class="field__err">Diga como podemos te chamar.</small>
        </div>
        <div class="field" data-reveal>
          <label for="f-mail">E-mail corporativo</label>
          <input id="f-mail" name="email" type="email" required placeholder="Insira seu e-mail" autocomplete="email">
          <small class="field__err">Confira o e-mail digitado.</small>
        </div>
      </div>
      <div class="form__row">
        <div class="field" data-reveal>
          <label for="f-org">Empresa ou órgão</label>
          <input id="f-org" name="empresa" type="text" placeholder="Escolha empresa ou órgão" autocomplete="organization">
        </div>
        <div class="field field--select" data-reveal>
          <label for="f-int">Assunto</label>
          <select id="f-int" name="interesse" required>
            <option value="" disabled>Selecione</option>
            <optgroup label="Serviços de IA">
              <option>IA · AI Discovery Day</option>
              <option>IA · Copilot e agentes</option>
              <option>IA · Segundo cérebro da empresa</option>
              <option>IA · Treinamento em IA e dados</option>
              <option>IA · Governança, LGPD e segurança</option>
              <option>IA · Infraestrutura (em breve)</option>
            </optgroup>
            <optgroup label="Licenças">
              <option>Licenças Adobe para empresas</option>
              <option>Licenças Microsoft para empresas</option>
              <option>Licenças de software</option>
            </optgroup>
            <optgroup label="Consultoria">
              <option>Consultoria · Diagnóstico e metodologia</option>
              <option>Consultoria em dados e BI</option>
              <option>Desenvolvimento de software</option>
              <option>Nuvem e infraestrutura</option>
              <option>Talentos de tecnologia</option>
            </optgroup>
            <option selected>Setor público</option>
            <option>Parceria / co-venda</option>
            <option>Outro assunto</option>
          </select>
          <svg class="field__chev" viewBox="0 0 16 16" aria-hidden="true"><path d="M4 6l4 4 4-4"/></svg>
          <small class="field__err">Escolha um assunto.</small>
        </div>
      </div>
      <div class="field field--area" data-reveal>
        <label for="f-msg">O que você precisa?</label>
        <textarea id="f-msg" name="mensagem" rows="3" placeholder="Escreva sua mensagem"></textarea>
        <span class="field__count"><b>0</b>/600</span>
      </div>

      <div class="field field--consent" data-reveal>
        <label class="consent" for="f-lgpd">
          <input id="f-lgpd" name="lgpd" type="checkbox" required>
          <span class="consent__box" aria-hidden="true"><svg viewBox="0 0 16 16"><path d="M3.4 8.4l3 3 6.2-6.6"/></svg></span>
          <span class="consent__txt">Concordo em receber contato da 3Pontos e com o tratamento dos meus dados conforme a <a class="link" href="#politica-de-privacidade" data-placeholder="politica-de-privacidade"><span>Política de Privacidade</span></a>.</span>
        </label>
        <small class="field__err">Marque a caixa para podermos responder.</small>
      </div>

      <!-- RF10: de onde veio o lead, preenchido pelos botões de cada bloco -->
      <input type="hidden" id="f-origem" name="origem" value="Site">
      <!-- RNF09: campo isca. Robô preenche, pessoa não vê. -->
      <div class="field field--trap" aria-hidden="true">
        <label for="f-site">Não preencha este campo</label>
        <input id="f-site" name="site" type="text" tabindex="-1" autocomplete="off">
      </div>

      <div class="form__foot">
        <button class="btn btn--lg btn--block" type="submit">
          <span>Enviar solicitação</span><svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3 13L13 3M13 3H5.5M13 3v7.5"/></svg>
        </button>
        <p class="form__note">Sem compromisso. Usamos os seus dados apenas para responder a esta solicitação e nunca os compartilhamos.</p>
      </div>

      <div class="form__done" hidden>
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12.5l5.2 5.2L20 7"/></svg>
        <p><b>Recebemos a sua mensagem.</b> Respondemos no e-mail informado, normalmente no mesmo dia útil.</p>
      </div>

      <div class="form__fail" hidden role="alert">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 7.4v6"/><path d="M12 16.4v.2"/><circle cx="12" cy="12" r="8.4"/></svg>
        <p><b>Não conseguimos enviar agora.</b> Tente de novo em alguns minutos ou escreva direto para
          <a href="mailto:contato@3pontos.com.br">contato@3pontos.com.br</a>.</p>
        <button class="btn btn--ghost" type="button" data-form-retry><span>Tentar de novo</span></button>
      </div>
    </form>
  </div>
</section>

</main>


<!-- ░░ FOOTER ░░ -->
<footer class="foot" data-theme="dark" data-chapter="08 / 3Pontos">
  <div class="foot__inner">
    <div class="foot__grid" data-stagger>

      <div class="foot__col foot__col--about" data-reveal>
        <a class="foot__brand" href="#inicio" aria-label="3Pontos, início">
          <svg viewBox="0 0 34 18" aria-hidden="true"><circle cx="5" cy="13" r="4"/><circle cx="17" cy="5" r="4"/><circle cx="29" cy="13" r="4"/></svg>
          <span>3pontos</span>
        </a>
        <h4>Nosso Endereço</h4>
        <span>R. Gomes de Carvalho, 1629 — Vila Olímpia, São Paulo, SP · 04547-006</span>

        <!-- O selo vem com a placa branca de fábrica justamente para ficar
             legível em qualquer fundo: no escuro ele entra como veio, sem
             versão invertida e sem recolorir. -->
        <span class="foot__badge">
          <img src="{{ asset('consultoria/img/adobe/selo-certified-reseller.png') }}" alt="Adobe Certified Reseller"
               width="150" height="70" loading="lazy" decoding="async">
        </span>
      </div>

      <nav class="foot__col" data-reveal aria-label="Links">
        <h4>Links</h4>
        <a href="#inicio">Início</a>
        <a href="{{ route('consultoria.home', absolute: false) }}#ia">Agentes de IA</a>
        <a href="{{ route('consultoria.home', absolute: false) }}#consultoria">Consultoria e tecnologia</a>
        <a href="{{ route('consultoria.home', absolute: false) }}#metodologia">Método 3 Pontos</a>
        <a href="{{ route('consultoria.home', absolute: false) }}#licencas">Licença de software</a>
        <a href="{{ route('consultoria.home', absolute: false) }}#adobe">Adobe</a>
        <a href="{{ route('consultoria.home', absolute: false) }}#microsoft">Microsoft</a>
        <a href="{{ route('consultoria.home', absolute: false) }}#processo">Processo</a>
        <a href="{{ route('consultoria.home', absolute: false) }}#clientes">Clientes</a>
        <a href="{{ route('consultoria.licitacoes', absolute: false) }}" aria-current="page">Setor público</a>
        <a class="btn btn--sm foot__cta" href="#contato"><span>Pedir cotação</span></a>
      </nav>

      <div class="foot__col" data-reveal>
        <h4>Contato e Endereço</h4>
        <a href="mailto:contato@3pontos.com.br">contato@3pontos.com.br</a>
        <a class="foot__social" href="#linkedin" data-placeholder="linkedin" aria-label="3Pontos no LinkedIn">
          <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="3.5" width="17" height="17" rx="3"/><path d="M8.2 10.4v5.4M8.2 7.9v.02M11.6 15.8v-5.4M11.6 12.9c0-1.6 1-2.6 2.3-2.6s2 .9 2 2.4v3.1"/></svg>
          <span>LinkedIn</span>
        </a>
        <a class="foot__tel" href="tel:+5511981230123">
          <svg class="foot__flag" viewBox="0 0 28 20" aria-hidden="true">
            <rect width="28" height="20" rx="2.5" fill="#009B3A"/>
            <path d="M14 2.6L25.4 10 14 17.4 2.6 10z" fill="#FEDF00"/>
            <circle cx="14" cy="10" r="4.1" fill="#002776"/>
          </svg>
          <span>+55 11 98123-0123</span>
        </a>
      </div>

      <div class="foot__col foot__col--news" data-reveal>
        <h4>Nossa Newsletter</h4>
        <p>Receba no e-mail as novidades de licenciamento e as boas práticas de TI que aplicamos nos projetos.</p>
        <form class="news" id="news">
          <input type="email" required placeholder="Digite seu e-mail" aria-label="Seu e-mail">
          <button class="btn" type="submit"><span>Assinar</span></button>
          <small class="news__ok" hidden>Pronto. Você está na lista.</small>
        </form>
      </div>

    </div>

    <p class="foot__legal">Adobe, Acrobat, o logotipo Adobe PDF, Adobe Express, o logotipo Adobe Express, Adobe Premiere, After Effects, Creative Cloud, Illustrator, InDesign, Lightroom, Photoshop e o logotipo Photoshop são marcas registradas ou marcas comerciais da Adobe nos Estados Unidos e/ou em outros países. Microsoft, Azure, Microsoft 365, SQL Server, Teams, Windows e Windows Server são marcas registradas ou marcas comerciais do grupo de empresas Microsoft. Google, Gemini, Google Workspace e Google Cloud são marcas da Google LLC. Claude e Anthropic são marcas da Anthropic PBC. OpenAI e ChatGPT são marcas da OpenAI. Autodesk e AutoCAD são marcas da Autodesk, Inc. As marcas citadas pertencem aos seus titulares, e a menção a elas não indica patrocínio ou endosso, salvo onde apresentada como credencial.</p>

    <div class="foot__base">
      <span>© <b id="year">2026</b> 3Pontos Tecnologia · CNPJ 00.000.000/0001-00</span>
      <span class="foot__clock"><i id="clock">--:--:--</i> São Paulo</span>
      <a href="#inicio" class="foot__top"><span>Voltar ao topo</span><svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8 13V3M8 3L4 7M8 3l4 4"/></svg></a>
    </div>
  </div>
</footer>


<script src="{{ asset('consultoria/js/app.js') }}"></script>
</body>
</html>
