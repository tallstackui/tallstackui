# Review guide — stacked toasts

A feature on the Toast component: instead of growing into an endless vertical list,
the toasts pile on top of each other and the pile expands back into a list while the
pointer is over it. Off by default, behind a global config key.

Inspired by Sonner, and by how Nuxt UI exposes it (`toaster.expand: false`).

This document is a review brief. It states what the feature is supposed to do, how
it is built, what was already verified, and where the author believes the weak
points are. **Treat the "known weak points" section as claims to be challenged,
not as settled conclusions.**

Both pieces below are intended to ship. Review with that bar — this is not a
throwaway prototype.

## Scope

Two separate pieces of work are in the working tree, and they should be reviewed
separately.

| Piece | Commit |
| ----- | ------ |
| `top-center` / `bottom-center` toast positions | `542b8bf7` |
| `stacked` pile | Uncommitted |

The positions work landed first and is the baseline the pile sits on. If a finding
applies to both, say so — the fix may belong in the committed part.

## Files

```
src/config.php                                          + 'stacked' => false
src/Components/Toast/Component.php                      + stack.* customization blocks
src/Components/Toast/toast-base.js                      pile state and geometry
src/Components/Toast/toast-loop.js                      per-toast measuring and timer hold
src/resources/views/components/toast/main.blade.php     pile wrapper and positioned item
dist/                                                   rebuilt (npm run build)
```

Reviewing `dist/` is not useful — it is generated. Read the sources.

## What it is supposed to do

1. With `stacked` off, behave exactly as before. This is the primary regression
   risk and the first thing worth checking.
2. With `stacked` on, the toasts overlap. The **most recent toast is the front of
   the pile**, anchored to the edge the position points at. Older toasts sit behind
   it, offset toward the center of the screen, slightly smaller.
3. Only three layers peek out. Deeper toasts go to `opacity: 0` and wait — they are
   still in the queue and reappear as the ones in front leave.
4. In the closed pile, **only the front card shows content**. The ones behind are
   reduced to their card shape. This is deliberate (see "History" below).
5. Hovering the pile expands it into the regular list, and **freezes every timer and
   progress bar in it** until the pointer leaves.
6. Works in all six positions, in light and dark, and with the existing toast
   features: `persistent`, `expandable`, `sole`, confirm/cancel buttons, hooks.

## How it is built

### Where the state lives

`toast-base.js` (the `tallstackui_toastBase` Alpine component) owns the pile:
`expanded`, `heights`, and the geometry functions `style(index)`, `content(index)`,
`scale(index)`, `depth(index)`, `container`.

`toast-loop.js` (`tallstackui_toastLoop`, one per toast) owns its own card: it
measures its height and holds its own countdown.

### Positioning

Cards are `absolute` in **both** states. The pile's wrapper is `relative` and gets
an explicit `height` from the `container` getter, which is what gives the pointer
something to hover — absolute children alone would leave it at zero height.

`translateY` is computed per state:

- **Collapsed:** `min(depth, VISIBLE) * OFFSET`, a fixed step per layer.
- **Expanded:** the summed measured heights of the toasts in front, plus `GAP` each.

Because the positioning mode never changes, the transition between pile and list is
one continuous animation. This was a deliberate choice over swapping `position`
between `absolute` and static, which does not animate.

Geometry constants are at the top of `toast-base.js`: `OFFSET = 16`, `GAP = 12`,
`VISIBLE = 3`. They are not configurable — only the on/off switch is.

### Height measurement

Each `toast-loop` observes its own card with a `ResizeObserver` and dispatches
`ts-ui:toast-measured` with `{ id, height }`. The pile's wrapper listens and stores
it in `heights`. Re-measuring matters because `expandable` changes a card's height
when its description opens.

### Why the DOM is shaped the way it is

The positioned element (`stack.item`, the one carrying `x-bind:style="style(index)"`)
sits **outside** the `x-data` of `tallstackui_toastLoop`:

```blade
<template x-for="(toast, index) in toasts" :key="toast.id">
    <div class="stack.item" x-bind:style="style(index)">   {{-- parent scope --}}
        <div x-data="tallstackui_toastLoop(toast, stacked)">   {{-- child scope --}}
```

The reason is that `style()` writes an inline `transform`, and the element carrying
`x-show` / `x-transition` must not be the same one. In normal mode the enter
transition works through transform *classes*, and Alpine's `transition-*` classes
would fight the permanent `transition-all` on `stack.item`. Separating them keeps the
pile's transform under the pile's control and the enter transition under Alpine's.

The child is still handed everything it needs explicitly, which keeps the boundary
one-directional:

- `stacked` as a constructor argument
- `expanded` via `x-effect="freeze(expanded)"` — `expanded` is read as an expression,
  `freeze` is the child's own method
- height reported upward as an event, not by writing to the parent

**Correction from review:** an earlier version of this document justified the split by
claiming Alpine does not guarantee a merged `this` inside methods of a nested
`x-data`. That justification was wrong, and the template itself disproves it:
`content(index)` is evaluated on elements *inside* the child's `x-data`, calls a
parent method, and that method reads `this.expanded`, `this.depth(index)` and
`this.toasts` — all on the parent. Scope merging through `mergeProxies` has been
stable since v3. The DOM shape stands, but on the structural ground above.

### Off mode

Both wrappers are always rendered. When `stacked` is off they get
`stack.inert` = `contents`, so they generate no box and the layout collapses to
exactly what it was. That is why off mode is a regression surface: it depends on
`display: contents` being fully transparent to the surrounding flex layout, at two
nesting levels.

## History — two bugs already found and fixed

Both were found by manual QA, and both are useful context because a reviewer may
find the fixes incomplete.

### The text disappearing oddly

**Symptom:** when a card was pushed back, its text vanished in a way the tester
could not describe.

**Cause:** every card in the pile showed content. A card recuding kept its text,
and the incoming card entered with a plain fade — so for 300ms the text behind was
visible *through* the semi-transparent card in front, the two mixing until one won.
Worse in `top-*`, where the visible strip of a card behind is its bottom edge, which
is exactly where the progress bar sits: stacked progress bars.

**Fix:** `content(index)` — in the closed pile only depth 0 renders content;
the rest go to `opacity: 0` and become shapes.

### Toasts never expiring

**Symptom:** toasts stayed on screen past their timeout, with progress bars frozen
at different points.

**Cause:** two independent sources of truth for the same clock. A `paused` closure
driven by `mouseover`/`mouseout` on each card, plus the `frozen` flag added for the
pile — each also writing `animationPlayState` directly. In the pile the cards slide
**under a stationary pointer**, so `mouseout` never fires on them: the mouse did not
move, the element did. `paused` latched at `true` and that toast's timer never
resumed.

**Fix:** `paused` and `piled` as properties with a derived `frozen` getter, a single
`animate(running)` writing to the bar, and — in stacked mode — the per-card hover
listeners are not registered at all. The pile's wrapper does not move, so its hover
is the reliable one.

**Note:** the coupling between `paused` and the bar predates this work, and the fix
touches committed behaviour — worth a look. What it covers in **both** modes is only
the bar/timer unification. The latch itself — a card sliding out from under a
stationary pointer — is still reachable in normal mode, where the per-card listeners
remain registered: a toast above expires, the ones below rise, and `mouseout` may not
fire. Rare and recoverable by moving the mouse, and left as known debt rather than
claimed as fixed.

## Known weak points

Stated by the author. Each is a claim to verify, and the assessments may be wrong.

1. **A new toast has no entry movement.** It fades in already at its final position
   while the older cards slide back on their own. It reads as "the ones behind fled"
   rather than "a new one arrived and pushed". Sonner slides the new toast in from
   the edge, opaque. Not done because it needs a per-toast entering state, and the
   author preferred one change at a time. This is the most likely remaining
   complaint.
2. **No cap on how many toasts the expanded pile shows.** A `max` — Nuxt UI's
   `toaster.max` — was explicitly declined when the feature was scoped. With enough
   toasts the expanded pile overflows the viewport and the lower cards become
   unreachable, which undercuts the whole reason hover-expand exists. The reasoning
   for accepting it was that the queue drains and many simultaneous toasts are
   exceptional; that reasoning was made while this was still framed as an experiment
   and **deserves a second opinion now that it ships.** This is the author's pick for
   the most likely real defect in the design.
3. ~~**A fully faded card is still clickable.**~~ **Fixed after review.** The claim
   that `visibility: hidden` "flips at 50% of the transition" was wrong: CSS
   Transitions gives `visibility` a special case, mapping every step between the
   endpoints to `visible`, so going to `hidden` the flip lands at the very end and the
   fade survives intact. `style()` now writes `visibility` alongside `opacity`, which
   also takes the buried cards out of the accessibility tree. `pointer-events: none`
   genuinely does not work here — `wrapper.third` sets `pointer-events-auto` and the
   property does not cascade — but that was never the only option.
4. **`expanded` cannot re-arm under a stationary pointer.** If the pile empties while
   hovered, `remove()` forces `collapse()` — otherwise `expanded` would latch and the
   next toast would arrive with a frozen timer. The cost: if a new toast arrives while
   the pointer is still over that area, it starts collapsed, because `mouseenter`
   already fired. Collapsed was chosen as the safe state, since timers keep running.
5. **First frame may have `height: 0`.** Until a card reports its height, `container`
   is 0. The `ResizeObserver` corrects it on its first notification. Believed to be
   one frame and imperceptible; not measured.
6. **A pre-existing leak was left alone.** Each toast adds a `visibilitychange`
   listener to `document` and never removes it. A `destroy()` hook now exists (for the
   `ResizeObserver`) and could clean it up, but the leak is not part of this feature
   and was left untouched on purpose.

## What has been verified

Run in this worktree, after the last change:

| Check | Result |
| ----- | ------ |
| `composer test:feature` | 1948 passed, 2 skipped |
| `./vendor/bin/phpstan analyse` | no errors |
| `./vendor/bin/pint --test --parallel` | passed |
| `npx eslint` on both JS files | no issues |
| `scripts/find-unused-customization-blocks.php` | all blocks in use |
| `scripts/check-html-escaped-tailwind-variants.php` | clean |
| `npm run build` | rebuilt |

Verified by driving a headless Chrome against the playground, with four toasts in
`top-right`:

- Collapsed: `container` 126px = front card (78) + 3 × 16. Offsets 0/16/32/48,
  scales 1/.95/.9/.85, ascending `zIndex`, fourth card at `opacity: 0`.
- Expanded on hover: 90px step = measured height (78) + gap (12). `container`
  348px = 4 × 78 + 3 × 12. All at `opacity: 1`, `scale: 1`.
- Browser console clean.

## What has NOT been verified

Be skeptical of the feature where the evidence is thin.

- **No automated tests were written.** Deliberate — the repository owner will direct
  how to write them. The existing `src/Components/Toast/BrowserTest.php` has no
  coverage of positions or of the pile.
- **The 14 existing toast browser tests were not run against the stacked changes.**
  Port 8001 was held by a Dusk server from another working directory and was not
  killed. Those tests passed at commit `542b8bf7` (the positions work), but not after
  any of the `stacked` commits-to-be. **This is the biggest gap in the evidence.**
- **Off mode was not verified in a browser** after the DOM was restructured into two
  `display: contents` levels. Reasoned about, not observed.
- **Not checked:** the six positions in the pile (only `top-right` was driven), dark
  mode, mobile widths, `sole`, hooks, and `flash`/`flashGlobal` (session-flashed
  toasts) in stacked mode.

## How to exercise it

The playground at `/Users/aj/Workspace/tallstack/playground` has a `/toasts` page
prepared for this, and its `vendor/tallstackui/tallstackui` is symlinked to this
worktree. `stacked` is already on in its `config/tallstackui.php`.

```bash
cd /Users/aj/Workspace/tallstack/playground && php artisan serve
```

| Section | Exercises |
| ------- | --------- |
| 1 | each of the six positions, one toast at a time |
| 2 | three at once per position |
| 3 | `persistent` and confirm/cancel |
| 4 | rejection of an invalid position |
| 5 | full pile (six at once) and mixed heights, including `expandable` |
| 6 | global default and customization snippets |

To compare against the old behaviour, set `components.toast.1.stacked` to `false`
in the playground config and reload. The `1` is the settings slot of the
`[class, settings]` pair the package keeps per component; a partial override needs
it, and omitting it fails silently. That quirk predates this work and applies to
every component setting.

## Questions the review should answer

1. Does off mode behave identically to before? This matters most.
2. Is the scope indirection described above necessary, or is it working around
   something Alpine actually guarantees?
3. Is the missing cap on the expanded pile (weak point 2) acceptable in a shipping
   feature, or does it invalidate the design?
4. Is the timer unification correct in **normal** mode too? It changed code paths
   that ship today.
5. Anything wrong with the geometry — the `depth` inversion, the anchor flip for
   `bottom-*`, the `container` arithmetic?
6. Is `content(index)` hiding the right things? It is applied at two points in the
   card: the content block and the progress bar wrapper.

---

# Resultado da revisão — 2026-07-29

Revisão de código somente (nenhuma alteração feita). Nenhum achado crítico ou
alto. O recurso está apto a seguir, com um ajuste barato recomendado (achado 1)
e observações não bloqueantes.

## Evidência nova produzida nesta revisão

- **Os 14 browser tests do Toast foram rodados agora, contra o código stacked**
  (a porta 8001 estava livre): `14 passed, 78 assertions, 28.6s`. Como o default
  de `stacked` é `false` no ambiente de teste, isso também **valida o off mode em
  browser real** após a reestruturação em dois níveis de `display: contents` —
  os dois maiores gaps listados em "What has NOT been verified" estão fechados.
- Ponte de config verificada: `TallStackUiServiceProvider.php:118` faz
  `array_replace_recursive(config('ts-ui'), $published)` — config publicada de
  versão anterior, sem a chave `stacked`, herda o default do pacote. Sem risco
  de upgrade.
- Payload de flash verificado: `DispatchInteraction.php:80` garante
  `$data['id'] ??= Str::uuid()`, então `flash` em modo stacked tem `id` para
  `:key`, `heights` e `remove()`. Análise estática apenas — não exercitado em
  browser.

## Respostas às perguntas

**1. Off mode idêntico?** Sim. Em off mode os dois wrappers `display: contents`
não carregam nenhum listener ou binding Alpine (tudo atrás de
`@if ($configurations['stacked'])`), não geram box, e os cards voltam a ser
filhos flex diretos de `wrapper.first` — `gap-y-2` e alinhamentos intactos.
Evidência: 14 browser tests verdes + 1948 feature tests (do autor).

**2. A indireção de escopo é necessária?** Não pelo motivo declarado — e o
próprio template prova isso: `content(index)` é avaliado em elementos **dentro**
do `x-data` do `tallstackui_toastLoop` (`main.blade.php:45` e `:139`), chama um
método do pai e esse método lê `this.expanded`, `this.depth(index)` e
`this.toasts` — exatamente o mecanismo de `this` mesclado que o wrapper extra
evita para `style()`. Ou seja, o código já depende desse comportamento; a
cautela é inconsistente. Os docs do Alpine garantem herança de escopo do pai em
expressões de filhos; o `this` mesclado em métodos decorre da semântica de
`with` sobre o proxy de escopos (`mergeProxies`) e é estável desde o v3, embora
não documentado como contrato. **Veredito:** manter o DOM como está é correto,
mas pela razão estrutural, não pela de escopo — o `transform` inline de
`style()` não deve dividir elemento com `x-show`/`x-transition` (no modo normal
o enter usa classes de transform, e as classes `transition-*` do Alpine
brigariam com o `transition-all` permanente do `stack.item`). Sugiro corrigir a
justificativa onde ela estiver registrada.

**3. Falta de cap invalida o design?** Não. A pilha expandida que estoura o
viewport reproduz o comportamento que o off mode já tem hoje (lista sem limite
dentro de `fixed inset-0`, sem scroll, cards inalcançáveis). A pilha fechada é
estritamente melhor; a expandida degrada, no pior caso, para o status quo.
Aceitável para ship com a limitação documentada (já está em `docs.md`); um
`max` estilo Nuxt UI fica como follow-up natural, não bloqueante.

**4. Unificação de timer correta em modo normal?** Sim, com um bônus e uma
ressalva. Preserva a semântica: mesmo par `mouseover`/`mouseout`, mesmo guard
(`!frozen` ≡ `!paused` quando `piled` nunca liga). Bônus: o guard de
`animate()` (`toast-loop.js:101`) corrige um crash latente do código antigo —
com `progress => false` na config, o span não renderiza e o handler de
`mouseover` antigo fazia `progress.style...` em `undefined` (TypeError a cada
hover). Ressalva: a frase do brief "the fix covers both modes" está
superestimada — o **latch** (card desliza sob ponteiro parado, `mouseout` nunca
dispara) continua possível em modo normal, porque lá os listeners por card
seguem registrados. O que cobre os dois modos é só a unificação bar/timer.
Pré-existente e raro; ver achado 3.

**5. Geometria.** Correta. `depth` (front = último índice), o flip de âncora
para `bottom-*` (translateY negativo, `top/bottom` sempre escritos), a
aritmética de `container` nos dois estados e o clamp `min(depth, VISIBLE)`
conferem com o observado no QA (126px / passo de 90px). Duas observações não
bloqueantes: (a) **inversão de cronologia em `top-*`** — a lista off mode mostra
o mais antigo na borda (ordem DOM, `justify-start`); a pilha expandida mostra o
mais novo na borda. Em `bottom-*` as ordens coincidem. É o comportamento do
Sonner e decorre de "front = mais novo", mas ligar o switch muda a leitura da
lista em posições `top-*` — vale uma linha na doc. (b) Card de trás mais baixo
que o da frente não gera strip visível (fica todo coberto) — cosmético,
o Sonner tem o mesmo.

**6. `content(index)` esconde as coisas certas?** Sim — `wrapper.fourth` e o
wrapper do progress são exatamente o conteúdo, e `wrapper.third` mantém a forma
do card. O que `opacity: 0` **não** faz é remover os elementos do hit-test e da
árvore de acessibilidade — ver achado 1, que resolve os dois.

## Achados

### 1. Médio — a premissa que descartou `visibility: hidden` está errada; o weak point 3 tem correção barata

- **Evidência:** weak point 3 afirma que `visibility` "flips at 50% of the
  transition and would cut the fade". A spec de CSS Transitions trata
  `visibility` como caso especial: quando um dos extremos é `visible`, todo
  progresso intermediário mapeia para `visible` — indo de `visible` para
  `hidden`, o flip acontece **no fim** da transição; voltando, no início. A
  regra dos 50% vale para propriedades discretas genéricas, não para
  `visibility`.
- **Consequência:** adicionar `visibility` ao objeto retornado por `style()`
  (`toast-base.js:193`) — `hidden` quando `!expanded && depth >= VISIBLE` —
  preserva o fade completo (o `transition-all` do `stack.item` já cobre
  `visibility`), remove os cards invisíveis do hit-test (elemento `hidden` não
  é alvo de hit-test, independente do `pointer-events-auto` do `wrapper.third`)
  e, de quebra, os tira da árvore de acessibilidade.
- **Impacto atual sem o ajuste:** strip invisível de ~12px clicável (o próprio
  brief admite dispensar um toast que o usuário não vê) e toasts invisíveis
  lidos por screen reader. Mouse mitiga via hover-expand; touch não tem hover.
- **Gravidade/urgência:** médio/baixa — defeito de UX limitado, correção
  localizada em uma linha.
- **Confiança:** alta na spec; recomendo confirmar o fade no playground ao
  aplicar.

### 2. Baixo (pré-existente, fora do escopo) — handler de `visibilitychange` sem guard de `progress`

- `toast-loop.js:64-77`: com `progress => false` na config e toast não
  persistente, voltar para a aba executa `progress.style.animationDuration`
  com `progress === undefined` → TypeError. O mesmo caminho que `animate()`
  passou a proteger no hover. Pré-existente, não introduzido por este trabalho;
  se tocar nesse arquivo de novo, vale guardar junto (e o `destroy()` novo já é
  o lugar para remover o listener vazado do weak point 6).

### 3. Baixo (pré-existente) — latch de `paused` continua possível em modo normal

- Cenário: modo normal, toast acima expira, os de baixo sobem sob um ponteiro
  parado; `mouseout` do card que estava sob o cursor pode não disparar e
  `paused` fica travado em `true`. O trabalho atual corrigiu isso **apenas** em
  stacked (listeners não registrados). Raro, recuperável (basta mover o mouse),
  não bloqueante — registrar como dívida conhecida em vez de "coberto".

### 4. Baixo — lista de posições duplicada em dois arquivos (peça das posições, commit `542b8bf7`)

- `Interactions/Toast.php:92` e `Toast/Component.php:103` mantêm o mesmo array
  literal. O próprio commit existiu porque essas listas divergiram. Uma
  constante compartilhada (ex.: `Component::POSITIONS`) elimina a classe de
  drift. Nit de manutenção, não defeito.

## Vereditos sobre os weak points do autor

| # | Alegação | Veredito |
| - | -------- | -------- |
| 1 | Toast novo sem movimento de entrada | Procede; cosmético, backlog. Não bloqueia. |
| 2 | Sem cap na pilha expandida | Aceitável — degrada para o comportamento atual do off mode (ver Q3). Follow-up `max`. |
| 3 | Card sumido continua clicável | Correção barata existe — a premissa sobre `visibility` estava errada (achado 1). |
| 4 | `expanded` não re-arma sob ponteiro parado | Escolha segura, aceito. Alternativa futura: re-checar `matches(':hover')` do wrapper no `add()`. |
| 5 | Primeiro frame com `height: 0` | Benigno confirmado por análise: o card é `absolute` e aparece inteiro de imediato; só a área de hover (invisível) anima de 0. |
| 6 | Leak de `visibilitychange` deixado | Decisão correta para este escopo; ver achado 2 quando tocar no arquivo. |

## O que segue não verificado

Touch/mobile (relevante para o achado 1), dark mode, as seis posições com a
pilha montada (só `top-right` foi dirigida), e `flash`/`sole`/hooks em stacked
no browser — para `flash` e `sole`, a leitura estática não indica problema
(`flush()` zera `heights` e colapsa; flash tem `id`). O roteiro do playground
acima cobre tudo isso.

---

# Resposta do autor à revisão — 2026-07-29

Todos os quatro achados foram aceitos e aplicados. Nenhuma discordância.

### Achado 1 — `visibility` (aceito, aplicado)

A premissa estava errada, confirmado na spec de CSS Transitions Level 1: `visibility`
é um caso especial, com todo passo entre os extremos mapeando para `visible`. Eu havia
aplicado a regra genérica de propriedades discretas (troca em 50%) a uma propriedade
que não a segue.

`style()` agora escreve `visibility` junto de `opacity`. Verificado em browser com
cinco toasts: os dois enterrados saem em `visibility: hidden` (computed), e ao abrir a
pilha vão para `visible` **imediatamente**, enquanto a opacidade ainda anima. Se o
browser aplicasse os 50%, eles ficariam invisíveis por 150ms — o comportamento
observado só é possível com o caso especial.

### Achado 2 — guard de `progress` no `visibilitychange` (aceito, aplicado)

Fora do escopo do recurso, mas é crash real e o `destroy()` que este trabalho
introduziu era o lugar natural para o listener vazado do weak point 6. O handler passou
a ser uma propriedade (`listener`), com guard de `progress` e `removeEventListener` no
`destroy()`. **Marcado à parte para poder ser retirado do commit** se a preferência for
manter o escopo estrito.

### Achado 3 — latch em modo normal (aceito, texto corrigido)

A crítica está certa: "the fix covers both modes" estava superestimado. O que cobre os
dois modos é só a unificação bar/timer. O texto foi corrigido aqui e em `docs.md`, e o
latch em modo normal está registrado como dívida conhecida em vez de resolvido. Não
corrigi o latch porque a correção (derivar de `matches(':hover')` em vez de flag) mexe
em caminho que ship hoje, sem sintoma reportado.

### Achado 4 — lista de posições duplicada (aceito, aplicado)

`Component::POSITIONS`, consumida pelos dois validadores. Concordo com o argumento: foi
essa divergência que originou o trabalho, então sincronizar duas cópias à mão preservava
a causa raiz.

### Q2 — justificativa do DOM (aceito, corrigido)

A refutação está correta e o contraexemplo é decisivo: `content(index)` já roda dentro
do `x-data` do filho e lê estado do pai pelo `this` mesclado. A seção "Why the DOM is
shaped the way it is" foi reescrita com a razão estrutural — o `transform` inline não
deve dividir elemento com `x-show`/`x-transition` — e registra a correção.

### Q5(a) — inversão de cronologia em `top-*` (aceito, documentado)

Comportamento observável que não estava em lugar nenhum. Documentado em `docs.md` e em
`.ai/components/toast.md`.

### Q3 — cap da pilha (aceito o veredito)

O argumento de que a pilha expandida degrada para o status quo do off mode — lista sem
limite em `fixed inset-0`, sem scroll — é melhor que o meu, que apostava na fila
drenando. Segue sem `max`, com a limitação documentada e o enquadramento correto. `max`
fica como follow-up.

## Adicionado depois da revisão

`top-on-mobile` (config, default `false`), a pedido do dono do repositório: abaixo de
`md` os toasts vão para o topo, seja qual for a posição. Não estava no escopo revisado.

O ponto de risco é a pilha, que resolve a âncora em JavaScript e por isso não pode
delegar a decisão a uma media query como a lista faz. Resolvido com `matchMedia` sobre
o breakpoint `md`, espelhado como constante em `toast-base.js`. Verificado em browser na
matriz cruzada:

| Viewport | Position | `justify` | Âncora |
| -------- | -------- | --------- | ------ |
| 1280px | `top-right` | `flex-start` | `top`, `+translateY` |
| 1280px | `bottom-center` | `flex-end` | `bottom`, `−translateY` |
| 420px | `top-right` | `flex-start` | `top`, `+translateY` |
| 420px | `bottom-center` | `flex-start` | `top`, `+translateY` |

A última linha é o caso crítico: CSS e JS concordam ao inverter. **Os 14 browser tests
não foram rodados novamente após esta adição** — a evidência acima é dirigida, não de
suíte.
