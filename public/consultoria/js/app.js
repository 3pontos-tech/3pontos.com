/* ══════════════════════════════════════════════════════════════
   3PONTOS — motor de scroll, revelação e microinterações

   Três regras que valem para o arquivo inteiro:
   1. Um único rAF comanda tudo. Nenhum listener faz trabalho pesado.
   2. Medida é cara: posições ficam em cache (M) e só são relidas em
      resize / load. O laço quente não lê layout.
   3. Nada que acompanha o scroll roda em frame alternado — é aí que
      nasce o tranco.
   ══════════════════════════════════════════════════════════════ */
(function () {
  'use strict';

  var doc = document, root = doc.documentElement, body = doc.body;
  var $  = function (s, c) { return (c || doc).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || doc).querySelectorAll(s)); };
  var clamp = function (v, a, b) { return v < a ? a : v > b ? b : v; };
  var lerp  = function (a, b, t) { return a + (b - a) * t; };
  var now   = function () { return performance.now(); };

  var REDUCED = matchMedia('(prefers-reduced-motion: reduce)').matches;
  var COARSE  = matchMedia('(pointer: coarse)').matches;
  var vh = innerHeight, vw = innerWidth;

  function absTop(el) { var t = 0; while (el) { t += el.offsetTop; el = el.offsetParent; } return t; }

  /* ══ 1. SCROLL ════════════════════════════════════════════
     O scroll nativo continua sendo a fonte da verdade (sticky,
     barra lateral e teclado seguem funcionando). Só interpolamos
     o destino — 0.11 é um deslize longo, que é o que as seções em
     pin pedem: a travessia de um passo ao outro tem de dar tempo de
     ser vista dissolvendo. */
  /* 0.16 por quadro: ~95ms para cobrir dois terços da distância. O
     0.11 de antes era um deslize longo demais — a roda parava e a
     página continuava andando, que é exatamente a sensação de peso
     de que ele reclamou. Ainda é deslize, só que curto. */
  var SMOOTH = 0.16;
  var S = { cur: scrollY, tgt: scrollY, last: -1, max: 0, anim: null, on: !REDUCED && !COARSE };

  var SCROLLERS = '.menu,textarea';
  function scrollable(el) {
    var n = el.closest && el.closest(SCROLLERS);
    return !!n && (n.scrollHeight > n.clientHeight + 2 || n.scrollWidth > n.clientWidth + 2);
  }

  addEventListener('wheel', function (e) {
    if (!S.on || e.ctrlKey) return;
    if (e.target && e.target.nodeType === 1 && scrollable(e.target)) return;
    e.preventDefault();
    S.anim = null;                                    // qualquer gesto cancela o tween
    var d = e.deltaY * (e.deltaMode === 1 ? 18 : e.deltaMode === 2 ? vh * 0.9 : 1);
    S.tgt = clamp(S.tgt + d, 0, S.max);
  }, { passive: false });

  /* Ir até um ponto é um tween com duração — não o mesmo lerp do
     scroll, que levaria uma eternidade para cobrir 10.000px. */
  function goTo(y) {
    y = clamp(y, 0, S.max);
    if (!S.on) { scrollTo({ top: y, behavior: 'smooth' }); return; }
    var from = scrollY;
    S.anim = { from: from, to: y, t0: now(), dur: clamp(300 + Math.abs(y - from) * 0.2, 400, 980) };
  }

  function stepScroll() {
    if (!S.on) return;
    if (S.anim) {
      var t = clamp((now() - S.anim.t0) / S.anim.dur, 0, 1);
      var e = t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
      S.cur = S.tgt = S.anim.from + (S.anim.to - S.anim.from) * e;
      if (t >= 1) S.anim = null;
    } else {
      if (Math.abs(scrollY - S.last) > 2) S.cur = S.tgt = scrollY;   // barra, teclado, toque
      S.cur = lerp(S.cur, S.tgt, SMOOTH);
      if (Math.abs(S.tgt - S.cur) < 0.4) S.cur = S.tgt;
    }
    var y = Math.round(S.cur);
    if (y !== S.last) { scrollTo(0, y); S.last = y; }
  }

  addEventListener('keydown', function (e) {
    if (!S.on || menuOpen) return;
    if (e.metaKey || e.ctrlKey || e.altKey) return;
    if (e.target && e.target.closest && e.target.closest('input,textarea,select,[contenteditable]')) return;
    var k = e.key, d = 0;
    if (k === 'PageDown' || (k === ' ' && !e.shiftKey)) d = vh * 0.85;
    else if (k === 'PageUp' || (k === ' ' && e.shiftKey)) d = -vh * 0.85;
    else if (k === 'ArrowDown') d = 130;
    else if (k === 'ArrowUp') d = -130;
    else if (k === 'Home') { e.preventDefault(); goTo(0); return; }
    else if (k === 'End') { e.preventDefault(); goTo(S.max); return; }
    if (!d) return;
    e.preventDefault(); S.anim = null;
    S.tgt = clamp(S.tgt + d, 0, S.max);
  });

  /* ══ 2. CACHE DE MEDIDAS ══════════════════════════════════ */
  var track = $('#railTrack'), railBar = $('#railBar');
  var railSteps = $$('.rail__steps span'), panels = $$('.rail__track .panel');
  var chapters = $$('[data-chapter]');
  var M = { chapters: [], meshes: [], focus: [] };

  /* Quem segura a tela é só o trilho: ele precisa de curso de scroll
     para andar na horizontal, e era assim desde o começo. Licenças,
     processo e clientes voltam a ser seções comuns — a coreografia
     continua inteira, só que por clique e pela travessia, sem prender
     o usuário. Pôr `index: true` aqui devolve o pin das três. */
  var PINNED = { rail: true, index: false, story: true };

  /* Para quem segura, `per` é quanta tela cada passo custa e `lead` é
     a folga antes do primeiro e depois do último — sem ela o
     movimento começa no mesmo pixel em que o pin prende e parece
     escorregão. */
  var PINS = $$('[data-pin]').map(function (sec) {
    return {
      sec: sec,
      kind: sec.dataset.pin,
      steps: parseInt(sec.dataset.steps, 10) || 0,
      per: parseFloat(sec.dataset.per) || 0.8,
      lead: parseFloat(sec.dataset.lead) || 0.4,
      stage: $('.pin__stage', sec),
      bar: $('[data-pin-bar]', sec),
      veil: $('[data-pin-veil]', sec),
      go: null,
      top: 0, span: 1, h: 0, dist: 0, leadF: 0.2, idx: -1, enter: -1, v: -1, on: false
    };
  });
  var railTargets = [];

  function measure() {
    vh = innerHeight; vw = innerWidth;

    /* altura de todas as seções em pin primeiro: uma desloca a outra,
       então só depois dá para ler as posições */
    /* No celular nem o trilho segura: a tela é pequena demais para
       prender o polegar, e ali ele volta a ser carrossel de arrastar,
       como era antes. */
    var railFree = vw <= 860;
    /* o método mostra desenho e narrativa lado a lado: abaixo de
       1080 não cabem os dois, e numa janela baixa o palco não cabe
       inteiro. Nos dois casos ele solta e o desenho vira topo fixo. */
    var storyFree = vw <= 1080 || vh < 640;

    PINS.forEach(function (p) {
      var holds = PINNED[p.kind] && !(p.kind === 'rail' && railFree)
                                 && !(p.kind === 'story' && storyFree);
      if (REDUCED || !holds) {
        p.on = false;
        p.sec.style.height = '';
        p.sec.classList.add('is-free');
        return;
      }
      p.sec.classList.remove('is-free');
      p.on = true;
      /* No toque o passo é mais curto: o polegar cobre muito mais
         scroll por gesto do que a roda, e o encaixe (pin__snap) é
         quem segura o passo, não a distância. */
      p.leadPx = vh * p.lead * (COARSE ? 0.7 : 1);
      var travel;
      if (p.kind === 'rail') {
        p.dist = track ? Math.max(0, track.scrollWidth - track.parentElement.clientWidth) : 0;
        travel = Math.max(vh * 0.5, p.dist * 0.95);
      } else {
        travel = Math.max(0, p.steps - 1) * vh * p.per * (COARSE ? 0.78 : 1);
      }
      p.h = Math.round(vh + travel + p.leadPx * 2);
      p.sec.style.height = p.h + 'px';
    });

    if (!REDUCED && track && !railFree) {
      /* O passo do trilho é o card: cada parada quer encostar um
         card na margem esquerda. Quando o curso é menor do que isso
         — e costuma ser, com três cards e meio na tela — as paradas
         encolhem na mesma proporção, para que nenhuma fique sem
         movimento e a última caia exatamente no fim do trilho. */
      var pin = PINS.filter(function (q) { return q.kind === 'rail'; })[0];
      var dist = pin ? pin.dist : 0;
      var gut = parseFloat(getComputedStyle(track).paddingLeft) || 0;
      railTargets = panels.map(function (el) { return el.offsetLeft - gut; });
      var last = railTargets[railTargets.length - 1] || 0;
      if (last > dist && last > 0) {
        var k = dist / last;
        railTargets = railTargets.map(function (v) { return v * k; });
      }
    } else if (track) {
      track.style.transform = '';
      panels.forEach(function (el) {
        el.style.opacity = ''; el.style.transform = ''; el.classList.remove('is-lead');
      });
    }

    PINS.forEach(function (p) {
      if (!p.on) { p.idx = -1; p.enter = -1; p.v = -1; return; }
      p.top = absTop(p.sec);
      p.span = Math.max(1, p.h - vh);
      p.leadF = clamp(p.leadPx / p.span, 0.02, 0.45);
      p.idx = -1; p.enter = -1; p.v = -1;
      syncSnaps(p);
    });

    S.max = Math.max(0, root.scrollHeight - vh);

    M.chapters = chapters.map(function (el) {
      var parts = (el.dataset.chapter || '').split(' / ');
      return { top: absTop(el), id: el.id, num: parts[0], name: parts[1] };
    });
    var audTab = $('.aud__rail button[aria-selected="true"]');
    if (audTab && audMarker) {
      audMarker.style.height = audTab.offsetHeight + 'px';
      audMarker.style.transform = 'translateY(' + audTab.offsetTop + 'px)';
    }

    M.focus = focusables;

    M.meshes = meshes.map(function (el) {
      var sec = el.parentElement;
      return { el: el, top: absTop(sec), h: sec.offsetHeight };
    });

    /* medir zerou o passo de cada pin; reaplicar agora, e não no
       próximo quadro, senão a seção passa um piscar sem passo ativo */
    updatePins(S.on ? S.cur : scrollY);
  }

  /* ══ 3. DIVISÃO DE TEXTO ══════════════════════════════════ */
  function splitWords(el) {
    var n = 0;
    (function walk(node) {
      Array.prototype.slice.call(node.childNodes).forEach(function (k) {
        if (k.nodeType === 3) {
          if (!k.textContent.trim()) return;
          var frag = doc.createDocumentFragment();
          k.textContent.split(/(\s+)/).forEach(function (w) {
            if (!w.length) return;
            if (!w.trim()) { frag.appendChild(doc.createTextNode(' ')); return; }
            var outer = doc.createElement('span'); outer.className = 'w';
            var inner = doc.createElement('span'); inner.className = 'wi';
            inner.textContent = w; inner.style.setProperty('--i', n++);
            outer.appendChild(inner); frag.appendChild(outer);
          });
          node.replaceChild(frag, k);
        } else if (k.nodeType === 1) walk(k);
      });
    })(el);
  }
  if (!REDUCED) $$('[data-split]').forEach(splitWords);

  /* ══ 4. REVELAÇÃO ═════════════════════════════════════════
     Só começa depois que a cortina sobe. Antes disso o hero
     animava escondido atrás do loader e chegava na tela pronto. */
  $$('[data-rise]').forEach(function (el) {
    if (el.dataset.delay) el.style.setProperty('--delay', el.dataset.delay);
  });
  $$('.ledger li').forEach(function (el, i) { el.style.setProperty('--delay', 4 + i); });


  /* qualquer container marcado distribui o índice entre os filhos: o
     escalonamento deixa de ser nth-child escrito à mão em cada seção */
  $$('[data-stagger]').forEach(function (box) {
    Array.prototype.forEach.call(box.children, function (kid, i) {
      kid.style.setProperty('--i', i);
    });
  });

  /* os ícones são desenhados traço a traço quando o card entra.
     pathLength=1 normaliza qualquer forma para o mesmo dasharray. */
  $$('.panel ul').forEach(function (ul) {
    Array.prototype.forEach.call(ul.children, function (li, i) { li.style.setProperty('--li', i); });
  });

  $$('.tile svg').forEach(function (svg) {
    Array.prototype.forEach.call(svg.children, function (shape, i) {
      shape.setAttribute('pathLength', '1');
      shape.style.setProperty('--s', i);
    });
  });

  var revealable = $$('[data-split],[data-rise],[data-reveal],.ledger li,.foot__word,.tile');
  var pending = [];
  function startReveals() { pending = revealable.slice(); stepReveals(); }
  function stepReveals() {
    if (!pending.length) return;
    for (var i = pending.length - 1; i >= 0; i--) {
      var r = pending[i].getBoundingClientRect();
      if (r.top < vh * 0.94 && r.bottom > -40) {
        pending[i].classList.add('is-in');
        pending.splice(i, 1);
      }
    }
  }

  /* ══ 5. LOADER ════════════════════════════════════════════ */
  (function loader() {
    var el = $('#loader');
    if (!el) { startReveals(); return; }
    if (REDUCED) { body.classList.add('is-loaded'); el.remove(); startReveals(); return; }

    var fill = $('.loader__fill', el), count = $('.loader__count', el);
    var t0 = now(), dur = 1050, done = false, ready = false;

    function finish() {
      if (done) return; done = true;
      body.classList.add('is-loaded');
      measure();
      startReveals();
      setTimeout(function () { el.remove(); }, 700);
    }
    (doc.fonts ? doc.fonts.ready : Promise.resolve()).then(function () { ready = true; });
    setTimeout(function () { ready = true; }, 2000);
    setTimeout(finish, 3500);                      // trava: rAF estrangulado em aba oculta

    (function tick() {
      if (done) return;
      var p = clamp((now() - t0) / dur, 0, 1);
      var e = 1 - Math.pow(1 - p, 3);
      fill.style.transform = 'scaleX(' + e + ')';
      count.textContent = String(Math.round(e * 100)).padStart(2, '0');
      if (p < 1 || !ready) return requestAnimationFrame(tick);
      finish();
    })();
  })();

  /* ══ 6. NAVEGAÇÃO ═════════════════════════════════════════ */
  var nav = $('#nav'), progress = $('#progress'), vswitch = $('.vswitch');
  var chapNum = $('[data-chapter-num]'), chapName = $('[data-chapter-name]');
  var navLinks = $$('.nav__links a');
  var navY = scrollY, navHidden = false, curChapter = -1, curNavId = null;

  $$('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var id = a.getAttribute('href');
      if (id === '#' || id.length < 2) return;
      var t = doc.getElementById(id.slice(1));
      if (!t) return;
      e.preventDefault();
      closeMenu();
      /* quem declara folga própria (scroll-margin-top) não pode parar
         debaixo da barra: é o caso do rótulo dentro do acordeão */
      var folga = parseFloat(getComputedStyle(t).scrollMarginTop) || 8;
      goTo(absTop(t) - (id === '#inicio' ? 0 : folga));
      history.replaceState(null, '', id);
    });
  });

  var burger = $('#burger'), menu = $('#menu'), menuOpen = false;
  $$('.menu__links a').forEach(function (a, i) { a.style.setProperty('--i', i); });
  function openMenu() {
    menuOpen = true; menu.hidden = false;
    body.classList.add('is-menu');
    requestAnimationFrame(function () { menu.classList.add('is-open'); });
    burger.setAttribute('aria-expanded', 'true');
    burger.setAttribute('aria-label', 'Fechar menu');
    nav.classList.remove('is-hidden'); navHidden = false;
  }
  function closeMenu() {
    if (!menuOpen) return;
    menuOpen = false;
    body.classList.remove('is-menu');
    menu.classList.remove('is-open');
    if (doc.activeElement && menu.contains(doc.activeElement)) burger.focus();
    burger.setAttribute('aria-expanded', 'false');
    burger.setAttribute('aria-label', 'Abrir menu');
    setTimeout(function () { if (!menuOpen) menu.hidden = true; }, 700);
  }
  burger.addEventListener('click', function () {
    if (menuOpen) { closeMenu(); return; }
    openMenu();
    var first = $('.menu__links a', menu);
    if (first) setTimeout(function () { first.focus(); }, 380);
  });
  menu.addEventListener('keydown', function (e) {        // laço de foco dentro do menu
    if (e.key !== 'Tab') return;
    var f = $$('a,button', menu); if (!f.length) return;
    var i = f.indexOf(doc.activeElement);
    if (e.shiftKey && i <= 0) { e.preventDefault(); f[f.length - 1].focus(); }
    else if (!e.shiftKey && i === f.length - 1) { e.preventDefault(); f[0].focus(); }
  });
  addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMenu(); });

  /* ══ 7. SEÇÕES EM PIN ═════════════════════════════════════
     Um motor só para o trilho horizontal, para o índice de
     licenças, para os passos do processo e para os perfis de
     cliente: a seção prende, o conteúdo anda passo a passo e o
     scroll só segue para a próxima depois do último passo. */
  /* Cada passo é pausa e depois travessia. A travessia é a maior
     parte do curso: é ela que se vê dissolvendo. A pausa é só o
     repouso curto em que o passo fica inteiro na tela. */
  /* A pausa é o que trava: nela a roda anda e a tela não. Ficou só o
     necessário para o passo ser lido inteiro antes de começar a
     travessia seguinte. */
  var HOLD_RAIL = 0.13, HOLD_IDX = 0.1;
  function easeIO(x) { return x < 0.5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2; }

  function updatePins(y) {
    for (var i = 0; i < PINS.length; i++) {
      var p = PINS[i];
      if (!p.on || y + vh < p.top - vh * 0.4 || y > p.top + p.h + vh * 0.4) continue;

      var raw = clamp((y - p.top) / p.span, 0, 1);

      var t = clamp((raw - p.leadF) / (1 - p.leadF * 2), 0, 1);
      if (Math.abs(t - p.enter) > 0.004) {
        p.enter = t;
        p.stage.style.setProperty('--t', t.toFixed(3));
      }

      if (p.kind === 'rail') railFrame(p, t);
      else if (p.kind === 'index') indexFrame(p, t);
    }
  }

  function indexFrame(p, t) {
    var n = p.steps;
    /* antes das abas serem ligadas não há o que fazer com o índice —
       e guardá-lo agora faria o primeiro passo nunca ser aplicado */
    if (n < 1 || !p.go) return;
    if (p.bar) p.bar.style.transform = 'scaleY(' + (n > 1 ? t : 1).toFixed(4) + ')';

    var seg = t * (n - 1);
    var i = clamp(Math.floor(seg), 0, Math.max(0, n - 2));
    var f = n > 1 ? clamp(seg - i, 0, 1) : 0;
    var k = clamp((f - HOLD_IDX) / (1 - HOLD_IDX * 2), 0, 1);

    /* A troca acontece debaixo de uma dissolvência presa ao scroll: o
       painel esmaece, o conteúdo muda no fundo do vale e ele volta.
       Sem isso o corte cai em cima do scroll e a seção parece pular
       de um passo para o outro — e a dissolvência, por ser guiada
       pela roda, continua respondendo mesmo durante a pausa. */
    if (p.veil) {
      var v = 1 - Math.sin(Math.PI * k) * 0.88;   // nunca some de todo
      if (Math.abs(v - p.v) > 0.003) {
        p.v = v;
        p.veil.style.opacity = v.toFixed(3);
        /* recua em profundidade em vez de descer: deslocamento
           vertical numa troca lê como salto, escala lê como foco */
        p.veil.style.transform = 'scale(' + (0.988 + v * 0.012).toFixed(4) + ')';
      }
    }

    var idx = clamp(k < 0.5 ? i : i + 1, 0, n - 1);
    if (idx === p.idx) return;
    p.idx = idx;
    p.go(idx);
  }

  function railFrame(p, t) {
    var n = railTargets.length; if (n < 2) return;
    var seg = t * (n - 1);
    var i = clamp(Math.floor(seg), 0, n - 2), f = clamp(seg - i, 0, 1);
    var k = easeIO(clamp((f - HOLD_RAIL) / (1 - HOLD_RAIL * 2), 0, 1));
    var x = clamp(lerp(railTargets[i], railTargets[i + 1], k), 0, p.dist);

    track.style.transform = 'translate3d(' + (-x).toFixed(2) + 'px,0,0)';
    if (railBar) railBar.style.transform = 'scaleX(' + t.toFixed(4) + ')';
    if (p.bar) p.bar.style.transform = 'scaleY(' + t.toFixed(4) + ')';

    for (var j = 0; j < panels.length; j++) {
      var d = clamp(Math.abs(j - seg), 0, 2);
      panels[j].style.opacity = (1 - d * 0.32).toFixed(3);
      panels[j].style.transform = 'translate3d(0,' + (d * 16).toFixed(1) + 'px,0) scale(' +
        (1 - d * 0.02).toFixed(4) + ')';
    }

    var lead = clamp(Math.round(seg), 0, n - 1);
    if (lead === p.idx) return;
    p.idx = lead;
    for (var q = 0; q < panels.length; q++) panels[q].classList.toggle('is-lead', q === lead);
    railSteps.forEach(function (sp, si) { sp.classList.toggle('is-on', si <= lead); });
  }

  /* Uma marca invisível na posição exata de cada passo. No toque o
     scroll da página encaixa nelas (scroll-snap, `proximity`), então
     um gesto de polegar para numa parada em vez de atravessar meia
     seção — é o que falta para o pin não virar um borrão no celular.
     Sem `scroll-snap-type` no html elas são inertes, e é assim que
     ficam no desktop, onde o scroll já é interpolado. */
  function syncSnaps(p) {
    var n = p.kind === 'rail' ? railTargets.length : p.steps;
    if (!p.snaps) p.snaps = [];
    while (p.snaps.length < n) {
      var el = doc.createElement('i');
      el.className = 'pin__snap';
      el.setAttribute('aria-hidden', 'true');
      p.sec.appendChild(el);
      p.snaps.push(el);
    }
    for (var i = 0; i < p.snaps.length; i++) {
      if (i >= n) { p.snaps[i].style.display = 'none'; continue; }
      var t = n > 1 ? i / (n - 1) : 0;
      p.snaps[i].style.display = '';
      p.snaps[i].style.top = Math.round((p.leadF + t * (1 - p.leadF * 2)) * p.span) + 'px';
    }
  }

  /* Clicar num passo leva o scroll até ele. Sem isso o próprio
     scroll desfaria a escolha no quadro seguinte. */
  function pinOf(el) {
    var sec = el.closest('[data-pin]');
    for (var i = 0; i < PINS.length; i++) if (PINS[i].sec === sec) return PINS[i];
    return null;
  }
  function pinGoTo(p, i) {
    if (!p || !p.on) return false;
    var n = p.kind === 'rail' ? railTargets.length : p.steps;
    var t = n > 1 ? clamp(i, 0, n - 1) / (n - 1) : 0;
    p.idx = i;
    goTo(p.top + (p.leadF + t * (1 - p.leadF * 2)) * p.span);
    return true;
  }

  /* ══ 7b. MÉTODO — A SEÇÃO QUE SE DESENHA ══════════════════
     Cinco tempos e um gráfico só: o tempo do projeto na horizontal,
     as possibilidades em aberto na vertical. Uma cortina presa ao
     scroll revela o traço da esquerda para a direita, acendendo os
     três pontos onde a curva encosta no chão — um sintoma, um
     problema, uma solução — e os rótulos no alto de cada leque.

     As paradas não são equidistantes, de propósito: o ponto de foco
     cai em 60% do traço porque é onde ele cai no tempo real do
     projeto, e é esse o argumento da seção.

     `t` vem do pin quando a seção segura a tela; quando ela está
     solta (estreito, janela baixa, movimento reduzido) vem da
     travessia da própria lista pela janela. O desenho responde ao
     scroll nos dois casos — só muda a origem do número. */
  var ST_STOPS = [0.03, 0.33, 0.60, 0.80, 1];
  var ST_PTS = [0.02, 0.595, 0.995];     // onde cada ponto acende
  var ST_PEAKS = [0.312, 0.80];         // o alto de cada leque
  var ST_NOW = ['O sintoma', 'Descobrir', 'Definir', 'Desenvolver', 'Entregar'];
  /* quase nenhuma pausa: aqui o desenho tem de andar em todo pixel de
     scroll, senão a seção parece ter travado no meio do caminho */
  var HOLD_ST = 0.05;

  var story = (function () {
    var sec = $('#metodologia'); if (!sec) return null;
    var sd = $('.sd', sec), tempos = $$('.tp', sec);
    if (!sd || !tempos.length) return null;
    return {
      sec: sec, sd: sd, tempos: tempos, pin: null,
      heads: $$('.tp button', sec),
      bodies: $$('.tp__body', sec),
      now: $('[data-story-now]', sec),
      ticks: $$('.sd__tick', sec),
      peaks: $$('.sd__peak', sec),
      phases: $$('[data-phase]', sec),
      pts: $$('.sd__pt', sec),
      bar: $('[data-pin-bar]', sec),
      lit: [null, null, null], idx: -1, p: -1
    };
  })();

  if (story) {
    for (var sq = 0; sq < PINS.length; sq++) {
      if (PINS[sq].sec === story.sec) story.pin = PINS[sq];
    }
    /* navegabilidade: qualquer tempo da história a um clique, tanto
       pela lista quanto pelos três pontos do desenho */
    $$('[data-story-go]', story.sec).forEach(function (b) {
      b.addEventListener('click', function () {
        var i = clamp(parseInt(b.dataset.storyGo, 10) || 0, 0, story.tempos.length - 1);
        if (story.pin && story.pin.on) { pinGoTo(story.pin, i); return; }
        goTo(absTop(story.tempos[i]) - vh * 0.38);
      });
      b.addEventListener('keydown', function (e) {
        var d = e.key === 'ArrowDown' || e.key === 'ArrowRight' ? 1
              : e.key === 'ArrowUp' || e.key === 'ArrowLeft' ? -1 : 0;
        if (!d) return;
        e.preventDefault();
        var j = clamp(story.idx + d, 0, story.tempos.length - 1);
        var h = story.heads[j]; if (h) h.focus();
        if (story.pin && story.pin.on) pinGoTo(story.pin, j);
        else goTo(absTop(story.tempos[j]) - vh * 0.38);
      });
    });
  }

  function storyT() {
    var p = story.pin;
    if (p && p.on) return p.enter < 0 ? 0 : p.enter;
    /* solta: o tempo é a travessia da própria lista pela janela */
    var a = story.tempos[0].getBoundingClientRect();
    var b = story.bodies[story.bodies.length - 1].getBoundingClientRect();
    var span = Math.max(1, b.bottom - a.top - vh * 0.3);
    return clamp((vh * 0.58 - a.top) / span, 0, 1);
  }

  function updateStory() {
    if (!story) return;
    var r = story.sec.getBoundingClientRect();
    if (r.bottom < -vh * 0.3 || r.top > vh * 1.3) return;

    var t = REDUCED ? 1 : storyT();
    var n = ST_STOPS.length;
    var seg = t * (n - 1);
    var i = clamp(Math.floor(seg), 0, n - 2);
    var f = clamp(seg - i, 0, 1);
    /* pausa curta no tempo da vez, travessia no resto: é a mesma
       respiração das outras seções em pin */
    var k = easeIO(clamp((f - HOLD_ST) / (1 - HOLD_ST * 2), 0, 1));
    var p = lerp(ST_STOPS[i], ST_STOPS[i + 1], k);

    if (Math.abs(p - story.p) > 0.0015) {
      story.p = p;
      story.sd.style.setProperty('--p', p.toFixed(4));
      if (story.bar) story.bar.style.transform = 'scaleY(' + t.toFixed(4) + ')';
      /* o ponto acende quando a cortina chega nele, não quando o
         texto muda: quem manda no desenho é o traço */
      for (var j = 0; j < 3; j++) {
        var on = p >= ST_PTS[j] - 0.001;
        if (on === story.lit[j]) continue;
        story.lit[j] = on;
        if (story.pts[j]) story.pts[j].classList.toggle('is-on', on);
        if (story.ticks[j]) story.ticks[j].classList.toggle('is-on', on);
      }
      /* o rótulo do alto de cada leque aparece quando a cortina passa
         pelo pico: é ele que diz o que a altura significa */
      for (var k2 = 0; k2 < story.peaks.length; k2++) {
        story.peaks[k2].classList.toggle('is-on', p >= ST_PEAKS[k2]);
      }
    }

    /* a troca de tempo acontece no meio da travessia, não no fim: o
       texto já está trocado quando o traço chega no vértice */
    var idx = clamp(k < 0.5 ? i : i + 1, 0, n - 1);
    if (idx === story.idx) return;
    story.idx = idx;
    for (var q = 0; q < story.tempos.length; q++) {
      story.tempos[q].classList.toggle('is-on', q === idx);
      story.bodies[q].classList.toggle('is-on', q === idx);
      if (story.heads[q]) story.heads[q].setAttribute('aria-current', q === idx ? 'step' : 'false');
    }
    /* o rótulo anda junto com a ponta do traço, dizendo o que está
       sendo desenhado naquele exato ponto da linha do tempo */
    if (story.now) story.now.textContent = ST_NOW[idx] || '';
    story.phases.forEach(function (el, q) { el.classList.toggle('is-on', q === idx - 1); });
  }

  /* ══ 8. PROCESSO ══════════════════════════════════════════ */
  var dialNum = $('[data-proc-num]'), dialFill = $('#dialFill'), procSteps = $$('.step');
  function setProcStep(i) {
    procSteps.forEach(function (el, j) { el.classList.toggle('is-on', j === i); });
    if (dialFill) dialFill.style.strokeDashoffset = 289 * (1 - (i + 1) / procSteps.length);
    if (!dialNum) return;
    var label = procSteps[i].dataset.step;
    if (dialNum.textContent === label) return;
    dialNum.classList.remove('roll');
    void dialNum.offsetWidth;                 // reinicia a animação
    dialNum.textContent = label;
    dialNum.classList.add('roll');
  }
  $$('[data-pin-go]').forEach(function (b) {
    b.addEventListener('click', function () {
      var i = parseInt(b.dataset.pinGo, 10);
      /* Com a seção solta não há passo para onde levar o pin: o clique
         centraliza o passo, e daí a travessia o escolhe sozinha. */
      if (!pinGoTo(pinOf(b), i)) {
        var li = b.closest('.step');
        if (li) goTo(absTop(li) + li.offsetHeight / 2 - vh / 2);
      }
    });
  });

  /* Com o processo solto, o acordeão volta a ser o da versão inicial:
     abre o passo mais perto do meio da tela, um de cada vez, e o
     mostrador gira junto. O scroll conduz sem segurar ninguém. */
  var procPin = null, procSpy = -1;
  function updateProcSpy() {
    if (!procSteps.length) return;
    if (procPin === null) {
      procPin = false;
      for (var k = 0; k < PINS.length; k++) {
        if (PINS[k].sec === procSteps[0].closest('[data-pin]')) { procPin = PINS[k]; break; }
      }
    }
    if (procPin && procPin.on) return;          // o pin já comanda
    var list = procSteps[0].parentElement;
    var r = list.getBoundingClientRect();
    if (r.bottom < -vh * 0.5 || r.top > vh * 1.1) return;
    /* A janela é a lista mais quase uma tela de folga. Medindo só
       o passo mais perto do centro, os quatro trocavam nos 235px que
       a lista ocupa e a passagem virava um pulo; assim eles se
       espalham pela travessia inteira da seção. */
    var n = procSteps.length;
    var span = r.height + vh * 0.9;
    var t = clamp((vh * 0.95 - r.top) / span, 0, 1);
    var best = clamp(Math.round(t * (n - 1)), 0, n - 1);
    if (best !== procSpy) { procSpy = best; setProcStep(best); }
  }

  /* ══ 8b. MALHA EM PERSPECTIVA ═════════════════════════════
     Os planos derivam devagar enquanto a seção cruza a tela. */
  var meshes = $$('.mesh');
  function updateMesh(y) {
    if (REDUCED) return;
    for (var i = 0; i < M.meshes.length; i++) {
      var m = M.meshes[i];
      var prog = (y + vh - m.top) / (m.h + vh);
      if (prog < -0.1 || prog > 1.1) continue;
      m.el.style.setProperty('--p', ((prog - 0.5) * 90).toFixed(1) + 'px');
    }
  }

  /* ══ 8c. FOCO POR SCROLL ══════════════════════════════════
     Em listas de cards altos, o que está no centro da tela ganha
     destaque e os outros recuam. A seção respira enquanto rola,
     em vez de congelar depois da entrada. */
  var focusables = $$('[data-focus] > *');
  var curFocus = -1;
  function updateFocus() {
    if (!M.focus.length || REDUCED) return;
    var mid = vh * 0.46, best = 0, bestD = Infinity;
    for (var i = 0; i < M.focus.length; i++) {
      var r = M.focus[i].getBoundingClientRect();
      if (r.bottom < 0 || r.top > vh) continue;
      var d = Math.abs(r.top + r.height / 2 - mid);
      if (d < bestD) { bestD = d; best = i; }
    }
    if (bestD === Infinity || best === curFocus) return;
    curFocus = best;
    for (var k = 0; k < M.focus.length; k++) M.focus[k].classList.toggle('is-focus', k === best);
  }

  /* ══ 9. HERO SAI DE CENA ══════════════════════════════════ */
  var heroInner = $('.hero__inner'), heroLedger = $('.ledger'), heroCue = $('.hero__cue'), heroSys = $('.sys');
  function updateHero(y) {
    if (!heroInner || REDUCED) return;
    var p = clamp(y / (vh * 0.72), 0, 1), e = p * p;
    var fade = clamp(1 - e * 1.45, 0, 1);
    heroInner.style.transform = 'translate3d(0,' + (-e * 78).toFixed(1) + 'px,0)';
    heroInner.style.opacity = fade.toFixed(3);
    heroInner.style.pointerEvents = fade < 0.3 ? 'none' : '';
    if (heroLedger) {
      heroLedger.style.transform = 'translate3d(0,' + (-e * 34).toFixed(1) + 'px,0)';
      heroLedger.style.opacity = clamp(1 - e * 1.25, 0, 1).toFixed(3);
    }
    if (heroCue) heroCue.style.opacity = clamp(1 - y / 220, 0, 1).toFixed(3);
    /* a roda é o centro da cena: sai por último, recuando em escala
       em vez de subir, para a saída ler como afastamento e não salto */
    if (heroSys) {
      heroSys.style.transform = 'translate3d(0,' + (-e * 30).toFixed(1) + 'px,0) scale(' + (1 - e * 0.1).toFixed(3) + ')';
      heroSys.style.opacity = clamp(1 - e * 1.1, 0, 1).toFixed(3);
    }
  }

  /* ══ 10. ABAS ═════════════════════════════════════════════ */
  function wireTabs(tabSel, panelSel, opts) {
    var tabs = $$(tabSel); if (!tabs.length) return null;
    var panels = $$(panelSel); opts = opts || {}; var cur = -1;

    function select(i, focus) {
      if (i === cur) { if (focus) tabs[i].focus(); return; }
      cur = i;
      tabs.forEach(function (t, j) {
        t.setAttribute('aria-selected', j === i ? 'true' : 'false');
        t.tabIndex = j === i ? 0 : -1;
      });
      panels.forEach(function (p, j) {
        p.hidden = j !== i;
        p.classList.toggle('is-on', j === i);
      });
      if (focus) tabs[i].focus();
      if (opts.after) opts.after(i, tabs[i]);
    }

    tabs.forEach(function (t, i) {
      t.addEventListener('click', function () { select(i); pinGoTo(pinOf(t), i); });
      if (!COARSE && opts.hover) {
        t.addEventListener('mouseenter', function () {
          clearTimeout(t._h);
          t._h = setTimeout(function () { select(i); }, 110);
        });
        t.addEventListener('mouseleave', function () { clearTimeout(t._h); });
      }
      t.addEventListener('focus', function () { if (opts.hover) select(i); });
      t.addEventListener('keydown', function (e) {
        var n = e.key === 'ArrowDown' || e.key === 'ArrowRight' ? 1
              : e.key === 'ArrowUp' || e.key === 'ArrowLeft' ? -1
              : e.key === 'Home' ? -i
              : e.key === 'End' ? tabs.length - 1 - i : 0;
        if (!n) return;
        e.preventDefault();
        var j = (i + n + tabs.length) % tabs.length;
        select(j, true); pinGoTo(pinOf(t), j);
      });
    });
    select(0);
    return select;
  }

  /* A troca em si é invisível: ela cai no fundo da dissolvência que
     `indexFrame` desenha. Aqui só se atualiza o conteúdo. */
  var catChrome = null;
  var catGhost = $('[data-cat-ghost]'), catList = $('.cat__list');
  /* no celular a lista é um trilho horizontal — a categoria da vez
     tem de vir sozinha para o meio, senão a navegação some do lado */
  function centerCat(i) {
    if (!catList || catList.scrollWidth <= catList.clientWidth + 2) return;
    var b = catList.children[i]; if (!b) return;
    var r = b.getBoundingClientRect(), lr = catList.getBoundingClientRect();
    catList.scrollLeft += (r.left - lr.left) - (lr.width - r.width) / 2;
  }
  var selectCat = wireTabs('.cat__list button', '.cat__panel', {
    after: function (i) {
      if (catChrome) catChrome.variant = i / 8;
      if (catGhost) catGhost.textContent = ('0' + (i + 1)).slice(-2);
      centerCat(i);
    }
  });

  var audMarker = $('.aud__marker');
  var selectAud = wireTabs('.aud__rail button', '.aud__panel', {
    after: function (i, tab) {
      if (!audMarker) return;
      audMarker.style.height = tab.offsetHeight + 'px';
      audMarker.style.transform = 'translateY(' + tab.offsetTop + 'px)';
    }
  });

  /* As áreas da seção de IA usam o mesmo motor de abas. Aqui não há
     pin para levar o scroll junto: a troca é clique e teclado. */
  var caseRail = $('.cases__rail');
  wireTabs('.cases__rail button', '.cases__panel', {
    after: function (i, tab) {
      /* no celular o trilho é horizontal: a área da vez vem para o meio */
      if (!caseRail || caseRail.scrollWidth <= caseRail.clientWidth + 2) return;
      var r = tab.getBoundingClientRect(), lr = caseRail.getBoundingClientRect();
      caseRail.scrollLeft += (r.left - lr.left) - (lr.width - r.width) / 2;
    }
  });

  /* ══ 10b. CARTÕES DE IA ═══════════════════════════════════
     O detalhe do serviço abre no próprio cartão, sem tirar a
     pessoa do lugar. A altura anima por grid-template-rows, que
     não exige medir nada. */
  $$('.ia-card__more').forEach(function (b) {
    var card = b.closest('.ia-card'), label = $('span', b);
    b.addEventListener('click', function () {
      var open = card.classList.toggle('is-open');
      b.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (label) label.textContent = open ? 'Fechar detalhe' : 'Ver detalhe';
    });
  });

  /* cada seção em pin recebe o que fazer em cada passo */
  PINS.forEach(function (p) {
    p.go = p.sec.id === 'licencas' ? selectCat
         : p.sec.id === 'processo' ? setProcStep
         : p.sec.id === 'clientes' ? selectAud : null;
  });

  /* ══ 11. FAQ ══════════════════════════════════════════════
     Altura animada com transitionend em vez de setTimeout: clicar
     rápido não deixa mais o painel travado numa altura fixa. */
  /* As abas partem as dúvidas em dois assuntos: só o painel da vez
     ocupa a tela, e a barra desliza de um rótulo ao outro. */
  var faqMarker = $('.faq__marker');
  function moveFaqMarker(tab) {
    if (!faqMarker || !tab) return;
    faqMarker.style.width = tab.offsetWidth + 'px';
    faqMarker.style.transform = 'translateX(' + tab.offsetLeft + 'px)';
  }
  var selectFaq = wireTabs('.faq__tabs button', '.faq__panel', {
    after: function (i, tab) { moveFaqMarker(tab); }
  });
  /* o atalho da seção de IA continua caindo nas dúvidas certas */
  if (selectFaq) $$('a[href="#ia-faq"]').forEach(function (a) {
    a.addEventListener('click', function () { selectFaq(1); });
  });

  var qas = $$('.qa');
  function settle(d, open) {
    var b = $('.qa__body', d);
    b.style.height = open ? 'auto' : '0px';
    if (!open) d.open = false;
    d._busy = false;
  }
  function animate(d, open) {
    var b = $('.qa__body', d);
    clearTimeout(d._t);
    d._busy = true;
    if (open) { d.open = true; b.style.height = '0px'; }
    else b.style.height = b.scrollHeight + 'px';
    void b.offsetHeight;
    b.style.height = open ? b.scrollHeight + 'px' : '0px';
    d._t = setTimeout(function () { settle(d, open); }, 580);
  }
  qas.forEach(function (d) {
    var b = $('.qa__body', d);
    b.style.height = d.open ? 'auto' : '0px';
    b.addEventListener('transitionend', function (e) {
      if (e.propertyName !== 'height' || !d._busy) return;
      clearTimeout(d._t);
      settle(d, b.style.height !== '0px');
    });
    $('summary', d).addEventListener('click', function (e) {
      e.preventDefault();
      if (d.open) { animate(d, false); return; }
      /* só recolhe as vizinhas da mesma aba: o painel escondido
         não tem altura para medir */
      var lista = d.parentNode;
      qas.forEach(function (o) { if (o !== d && o.open && o.parentNode === lista) animate(o, false); });
      animate(d, true);
    });
  });

  /* ══ 12. FORMULÁRIO ═══════════════════════════════════════ */
  var form = $('#form');
  if (form) {
    var area = $('#f-msg', form), counter = $('.field__count b', form);
    if (area) {
      area.setAttribute('maxlength', '600');
      area.addEventListener('input', function () {
        counter.textContent = area.value.length;
        area.style.height = 'auto';
        area.style.height = Math.min(area.scrollHeight, 260) + 'px';
      });
    }
    var MAIL = /^[^\s@]+@[^\s@]+\.[a-z]{2,}$/i;
    function check(input) {
      var f = input.closest('.field'), ok = true;
      if (input.type === 'checkbox') { if (input.required && !input.checked) ok = false; }
      else if (input.required && !input.value.trim()) ok = false;
      if (input.type === 'email' && input.value && !MAIL.test(input.value)) ok = false;
      f.classList.toggle('is-bad', !ok);
      input.setAttribute('aria-invalid', ok ? 'false' : 'true');
      return ok;
    }
    $$('input,select', form).forEach(function (i) {
      if (i.type !== 'checkbox') i.addEventListener('blur', function () { if (i.value) check(i); });
      i.addEventListener(i.type === 'checkbox' ? 'change' : 'input', function () {
        i.closest('.field').classList.remove('is-bad');
        i.setAttribute('aria-invalid', 'false');
      });
    });
    var trap = $('#f-site', form), done = $('.form__done', form), fail = $('.form__fail', form);
    var sendBtn = $('button[type="submit"]', form);
    var sendTxt = sendBtn ? $('span', sendBtn) : null;

    /* TODO back-end: troque o corpo de `enviar` por
       fetch('/api/contato', { method:'POST', body: dados }) ou pelo
       endpoint do CRM. As duas saídas já estão tratadas abaixo. */
    function enviar(dados) {
      return new Promise(function (resolve) { setTimeout(resolve, 520); });
    }
    function revelar(el) {
      if (!el) return;
      el.hidden = false;
      el.setAttribute('tabindex', '-1');
      el.focus();
    }
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var ok = $$('input[required],select[required]', form).map(check).every(Boolean);
      if (!ok) {
        var bad = $('.field.is-bad input,.field.is-bad select', form);
        if (bad) bad.focus();
        return;
      }
      /* isca preenchida é robô: some sem explicar por quê */
      if (trap && trap.value) { form.classList.add('is-done'); revelar(done); return; }

      if (sendBtn) { sendBtn.disabled = true; if (sendTxt) sendTxt.textContent = 'Enviando'; }
      enviar(new FormData(form)).then(function () {
        form.classList.add('is-done');
        revelar(done);
        evento('lead_enviado', {
          interesse: fInt ? fInt.value : '',
          origem: fOrigem ? fOrigem.value : 'Site'
        });
      }, function () {
        /* o envio falhou: a pessoa precisa de uma saída, não de um
           botão girando para sempre */
        if (sendBtn) { sendBtn.disabled = false; if (sendTxt) sendTxt.textContent = 'Enviar'; }
        form.classList.add('is-fail');
        revelar(fail);
      });
    });

    var retry = $('[data-form-retry]', form);
    if (retry) retry.addEventListener('click', function () {
      form.classList.remove('is-fail');
      if (fail) fail.hidden = true;
      var first = $('input:not([type="hidden"])', form);
      if (first) first.focus();
    });

    /* ══ 12b. MEDIÇÃO ══════════════════════════════════════
       Nenhum rastreador é carregado aqui e nada dispara sozinho:
       o evento só é empurrado se a página já tiver uma camada de
       dados (dataLayer), que o marketing liga depois do consentimento.
       É o gancho dos indicadores do documento de requisitos. */
    function evento(nome, dados) {
      if (!window.dataLayer || typeof window.dataLayer.push !== 'function') return;
      var e = { event: nome };
      for (var k in dados) if (Object.prototype.hasOwnProperty.call(dados, k)) e[k] = dados[k];
      window.dataLayer.push(e);
    }
    var iaSec = $('#ia');
    if (iaSec && window.IntersectionObserver) {
      var iaSpy = new IntersectionObserver(function (ents) {
        for (var i = 0; i < ents.length; i++) {
          if (!ents[i].isIntersecting) continue;
          evento('ver_secao_ia', { secao: 'ia' });
          iaSpy.disconnect();
          return;
        }
      }, { threshold: 0.25 });
      iaSpy.observe(iaSec);
    }

    /* ══ 12c. INTERESSE VINDO DO BLOCO ══════════════════════
       Cada botão de contato leva o assunto junto: o lead chega ao
       comercial já com o interesse escolhido e a origem marcada. */
    var fInt = $('#f-int'), fOrigem = $('#f-origem');
    $$('[data-interesse]').forEach(function (a) {
      a.addEventListener('click', function () {
        if (fInt) {
          var want = a.dataset.interesse;
          $$('option', fInt).forEach(function (o) {
            if (o.textContent.trim() === want) { o.selected = true; }
          });
          var field = fInt.closest('.field');
          if (field) {
            field.classList.remove('is-bad');
            field.classList.add('is-set');
            setTimeout(function () { field.classList.remove('is-set'); }, 1400);
          }
        }
        if (fOrigem) fOrigem.value = a.dataset.origem || 'Site';
        evento('clique_contato', { bloco: a.dataset.origem || 'Site', interesse: a.dataset.interesse || '' });
      });
    });
  }

  var news = $('#news');
  if (news) news.addEventListener('submit', function (e) {
    e.preventDefault();
    var i = $('input', news);
    if (!i.value.trim() || i.value.indexOf('@') < 0) { i.focus(); return; }
    $('.news__ok', news).hidden = false; i.value = ''; i.blur();
  });

  /* ══ 13. CURSOR + MAGNETISMO ══════════════════════════════ */
  var cursor = $('.cursor'), cDot = $('.cursor__dot'), cRing = $('.cursor__ring'), cLabel = $('.cursor__label');
  var P = { x: vw / 2, y: vh / 2, moved: false };
  var C = { rx: vw / 2, ry: vh / 2 };
  var HOT = 'a,button,summary,.chips li,.cat__list button,.aud__rail button';

  if (!COARSE && !REDUCED) {
    addEventListener('mousemove', function (e) {
      P.x = e.clientX; P.y = e.clientY; P.moved = true;
      if (!body.classList.contains('has-cursor')) body.classList.add('has-cursor');
    }, { passive: true });

    doc.addEventListener('mouseover', function (e) {
      var hot = e.target.closest && e.target.closest(HOT);
      body.classList.toggle('cursor-hot', !!hot);
      if (cLabel) cLabel.textContent = hot ? (hot.dataset.cursor || '') : '';
    });
    doc.addEventListener('mouseleave', function () { body.classList.remove('has-cursor'); });
  }

  function stepPointer() {
    if (COARSE || REDUCED || !cursor) return;
    cDot.style.transform = 'translate(' + P.x + 'px,' + P.y + 'px)';
    C.rx = lerp(C.rx, P.x, 0.17); C.ry = lerp(C.ry, P.y, 0.17);
    cRing.style.transform = 'translate(' + C.rx.toFixed(1) + 'px,' + C.ry.toFixed(1) + 'px)';
    cLabel.style.transform = cRing.style.transform;
  }

  /* brilho que segue o cursor dentro dos painéis do trilho */
  if (track && !COARSE && !REDUCED) {
    track.addEventListener('pointermove', function (e) {
      var pn = e.target.closest && e.target.closest('.panel');
      if (!pn) return;
      var r = pn.getBoundingClientRect();
      pn.style.setProperty('--mx', (((e.clientX - r.left) / r.width) * 100).toFixed(1) + '%');
      pn.style.setProperty('--my', (((e.clientY - r.top) / r.height) * 100).toFixed(1) + '%');
    }, { passive: true });
  }

  /* ══ 14. MARQUEE ══════════════════════════════════════════
     A fita duplicada fecha o laço: o translateX de -50% cai exatamente
     sobre a largura de uma volta. */
  $$('.invert__marquee').forEach(function (m) {
    m.appendChild(m.firstElementChild.cloneNode(true));
  });

  /* ══ 15. CROMO ════════════════════════════════════════════ */
  var chromes = [];
  if (!REDUCED && window.LiquidChrome) {
    /* só os canvas: o index agora usa o cromo em imagem, a v1 segue no WebGL */
    $$('canvas.chrome').forEach(function (cv) {
      var kind = cv.dataset.chrome;
      var cfg = kind === 'hero'    ? { variant: 0,    gain: 1,    scale: 0.72, seed: 0 }
              : kind === 'contact' ? { variant: 0.6,  gain: 0.95, scale: 0.62, seed: 40 }
              : kind === 'rail'    ? { variant: 0.45, gain: 0.9,  scale: 0.55, seed: 120 }
              : kind === 'aud'     ? { variant: 0.82, gain: 0.92, scale: 0.6,  seed: 200 }
              :                      { variant: 0.3,  gain: 0.85, scale: 0.5,  seed: 80 };
      var c = new LiquidChrome(cv, cfg);
      if (!c.gl) { cv.remove(); return; }
      c.section = cv.closest('section');
      /* o tema agora é da seção, então a inversão é fixa e não pisca */
      var scope = cv.closest('[data-theme]');
      c.invert = c._invert = (scope && scope.dataset.theme === 'light') ? 1 : 0;
      chromes.push(c);
      if (kind === 'cat') catChrome = c;
    });
  }

  function updateChrome(y, dt) {
    for (var i = 0; i < chromes.length; i++) {
      var c = chromes[i];
      if (c.section) {
        var r = c.section.getBoundingClientRect();
        c.visible = r.bottom > -vh * 0.15 && r.top < vh * 1.15;
      }
      if (!c.visible) continue;
      if (c.section) {
        var top = c._top == null ? (c._top = absTop(c.section)) : c._top;
        c.scroll = clamp((y - top + vh) / (c.section.offsetHeight + vh), 0, 1);
      }
      if (!COARSE) { c.mx = (P.x / vw) * 2 - 1; c.my = (P.y / vh) * 2 - 1; }
      c.render(dt);
    }
  }

  /* o cromo em imagem (render de vidro do Figma). O shader saiu, o
     movimento não: a peça anda com o scroll, se inclina com o ponteiro,
     deriva sozinha no CSS e leva o facho de luz por cima. */
  var imgChromes = [];
  if (!REDUCED) {
    $$('.chrome--img').forEach(function (el) {
      var o = { el: el, section: el.closest('section'), top: null, visible: false,
                depth: parseFloat(el.dataset.depth) || 1 };
      var im = el.querySelector('.chrome__obj');
      if (!im) return;
      if (im.complete && im.naturalWidth) el.classList.add('is-ready');
      else im.addEventListener('load', function () { el.classList.add('is-ready'); });
      imgChromes.push(o);
    });
  }

  function updateImgChrome(y) {
    for (var i = 0; i < imgChromes.length; i++) {
      var o = imgChromes[i], s = o.section;
      if (!s) continue;
      var r = s.getBoundingClientRect();
      o.visible = r.bottom > -vh * 0.2 && r.top < vh * 1.2;
      if (!o.visible) continue;
      var top = o.top == null ? (o.top = absTop(s)) : o.top;
      var p = clamp((y - top + vh) / (s.offsetHeight + vh), 0, 1);
      var st = o.el.style;
      st.setProperty('--cy', ((p - 0.5) * 110 * o.depth).toFixed(1) + 'px');
      if (!COARSE) {
        st.setProperty('--mx', (((P.x / vw) * 2 - 1) * 16 * o.depth).toFixed(1) + 'px');
        st.setProperty('--my', (((P.y / vh) * 2 - 1) * 10 * o.depth).toFixed(1) + 'px');
      }
    }
  }

  /* ══ 15b. O SISTEMA DO HERO ═══════════════════════════════
     A roda girando sozinha é desenho. O que faz dela argumento é o
     painel: ele conta o que a 3Pontos faz com a marca da vez, anda
     de marca em marca sem ninguém pedir e para na que o cursor ou o
     teclado escolher. Cada pílula é um link para a seção. */
  (function system() {
    var say = $('#sysSay'); if (!say) return;
    var chips = $$('.sys__chip'); if (!chips.length) return;

    var tier = $('.sys__tier', say), line = $('.sys__line', say), go = $('.sys__go', say);
    var goLabel = go ? $('span', go) : null;
    var cur = -1, timer = 0, held = false;

    function show(i) {
      if (i === cur) return;
      var c = chips[i]; if (!c) return;
      cur = i;
      chips.forEach(function (x, n) { x.classList.toggle('is-on', n === i); });
      if (tier) tier.textContent = c.dataset.tier || '';
      if (line) line.textContent = c.dataset.say || '';
      if (go) {
        go.setAttribute('href', c.getAttribute('href'));
        if (goLabel) goLabel.textContent = c.dataset.go || 'Ver mais';
      }
      say.classList.add('is-live');
    }

    chips.forEach(function (c, i) {
      c.addEventListener('mouseenter', function () { held = true; show(i); });
      c.addEventListener('mouseleave', function () { held = false; });
      c.addEventListener('focus', function () { held = true; show(i); });
      c.addEventListener('blur', function () { held = false; });
    });

    /* no toque não existe cursor: o painel segue o passeio sozinho, e
       tocar a pílula leva para a seção, que é o que serve ali */
    show(0);
    timer = setInterval(function () { if (!held) show((cur + 1) % chips.length); }, 4600);
  })();

  /* ══ 16. RODAPÉ VIVO ══════════════════════════════════════ */
  var yearEl = $('#year'); if (yearEl) yearEl.textContent = new Date().getFullYear();
  var clock = $('#clock');
  function tickClock() {
    if (!clock) return;
    clock.textContent = new Date().toLocaleTimeString('pt-BR', { timeZone: 'America/Sao_Paulo', hour12: false });
  }
  tickClock(); setInterval(tickClock, 1000);

  /* ══ 17. LAÇO ÚNICO ═══════════════════════════════════════ */
  var frame = 0, prev = now();
  function loop(t) {
    var dt = Math.min((t - prev) / 1000, 0.05); prev = t;
    stepScroll();

    var y = S.on ? S.cur : scrollY;

    /* tudo que acompanha o scroll: todo frame, sem exceção */
    if (progress) progress.style.transform = 'scaleX(' + clamp(y / (S.max || 1), 0, 1).toFixed(4) + ')';
    if (y < vh * 1.2) updateHero(y);
    updatePins(y);
    updateStory();
    stepPointer();
    updateMesh(y);
    updateFocus();
    updateProcSpy();
    updateChrome(y, dt);
    updateImgChrome(y);

    /* estado discreto: pode viver a 20fps */
    if (frame % 4 === 0) stepReveals();

    if (frame % 3 === 0) {
      /* a barra é fixa: nunca esconde ao descer (pedido da daily de 10/11) */
      if (nav && navHidden) { nav.classList.remove('is-hidden'); navHidden = false; }

      var mid = y + vh * 0.35, idx = 0;
      for (var i = M.chapters.length - 1; i >= 0; i--) {
        if (mid >= M.chapters[i].top) { idx = i; break; }
      }
      if (idx !== curChapter) {
        curChapter = idx;
        var ch = M.chapters[idx];
        if (ch && chapNum) { chapNum.textContent = ch.num; chapName.textContent = ch.name; }
        if (ch && ch.id !== curNavId) {
          curNavId = ch.id;
          /* um item da barra acende para a seção dele e para as seções que
             pertencem a ele (data-also): Licença de software vale também
             para Adobe, Microsoft e o processo de cotação */
          navLinks.forEach(function (a) {
            var ids = (a.getAttribute('href').slice(1) + ' ' + (a.dataset.also || '')).split(' ');
            a.classList.toggle('is-active', ids.indexOf(ch.id) > -1);
          });
        }
      }
    }
    if (frame % 40 === 0) S.max = Math.max(0, root.scrollHeight - vh);

    frame++;
    requestAnimationFrame(loop);
  }

  /* ══ 18. BOOT ═════════════════════════════════════════════ */
  var rt;
  addEventListener('resize', function () {
    clearTimeout(rt);
    rt = setTimeout(function () {
      measure();
      chromes.forEach(function (c) { c.resize(); c._top = null; });
      imgChromes.forEach(function (o) { o.top = null; });
      if (selectAud) {
        var tab = $('.aud__rail button[aria-selected="true"]');
        if (tab && audMarker) {
          audMarker.style.height = tab.offsetHeight + 'px';
          audMarker.style.transform = 'translateY(' + tab.offsetTop + 'px)';
        }
      }
      moveFaqMarker($('.faq__tabs button[aria-selected="true"]'));
      $$('.qa[open] .qa__body').forEach(function (b) { b.style.height = 'auto'; });
      curChapter = -1;
    }, 140);
  });

  if (REDUCED) body.classList.add('is-reduced');
  measure();
  if (doc.fonts) doc.fonts.ready.then(measure);
  setTimeout(measure, 1200);
  addEventListener('load', measure);
  requestAnimationFrame(loop);
})();
