# Auditoria do TallStackUI 4.x

Data: 04/08/2026. Branch: `4.x`.

> **Rodada 1 de correções — branch `fixes-4x`.**
>
> Corrigidos e com teste de regressão: **A5, A6, A7, A8, A9, A10, A11, A12, A13, A14,
> A15, A16, A17, A18, A19, M1, M2, M3, M4, M5**.
>
> **A1 foi descartado** por decisão de produto: o `href` sem escape no button e no circle
> é intencional, para aceitar HTML cru. Ver a nota no próprio item.
>
> Cada correção tem teste que foi verificado falhando sem o patch, para não entrar
> falso-positivo. Os itens marcados `[CORRIGIDO]` abaixo trazem o que mudou e onde
> está o teste. O restante segue em aberto.

Isso é uma varredura atrás de bug de verdade, não de preferência de estilo. Cada item
abaixo tem arquivo, linha, o que dispara e o que quebra. O que eu não consegui confirmar
está marcado como parcial. O que eu chequei e estava certo está no fim, pra você não
pagar de novo.

## Como foi feito

20 agentes varreram o código em paralelo, cada um num pedaço: runtime, cores,
customização, blade, JS, testes, config, HTTP, console. Depois eu reverifiquei
pessoalmente todo achado de severidade alta, lendo o código e, quando dava, executando.
Alguns agentes erraram referência de arquivo ou premissa, e eu corrigi. Isso está
anotado onde aconteceu.

## Estado das ferramentas

Tudo passando. Isso importa porque significa que nada aqui seria pego por análise estática.

| Ferramenta | Resultado |
|---|---|
| PHPStan | 0 erros |
| Pint `--test` | passou |
| Pest type coverage | 100% |
| `find-unused-customization-blocks.php` | passou, mas com ponto cego (ver A19) |
| `check-html-escaped-tailwind-variants.php` | passou, 1145 arquivos |

Não rodei a suíte de navegador.

---

# Achados

## Alto

### A1. `href` sai sem escape no button e no circle — [DESCARTADO]

> **Descartado.** O `{!! !!}` é deliberado: um usuário precisou passar HTML cru pelo
> botão e o componente foi mudado para aceitar isso. O achado abaixo fica registrado
> como contexto, não como defeito a corrigir.


`src/resources/views/components/button/button.blade.php:5`
`src/resources/views/components/button/circle.blade.php:5`

```blade
<{{ $tag }} @if ($href) href="{!! $href !!}" @else
```

`$href` é `?string` puro, não é slot. E esses dois são os únicos assim. Varri todos
os outros sinks de `href` no projeto, dropdown.items, sidebar.item, dial.items, stats,
link, breadcrumbs, gallery.tile, os 4 paginadores, todos usam `{{ }}`. Ou seja, não é
decisão de design, é um caso isolado que passou.

Com `<x-button :href="$user->website" />` e o valor gravado sendo
`" onfocus=alert(document.domain) autofocus x="`, o HTML sai assim:

```html
<a href="" onfocus=alert(document.domain) autofocus x="" role="button" class="...">
```

Dispara sozinho no load, sem precisar de clique.

Correção: trocar por `href="{{ $href }}"` nos dois arquivos. Não muda comportamento
nenhum, já que o tipo é `?string`.

### A2. `wire:model` com ponto quebra a leitura do valor no servidor

`src/Support/Runtime/AbstractRuntime.php:142` e `:241`

```php
if (is_null($property) || ! property_exists($this->livewire, $property)) {
    return null;
}
return data_get($this->livewire, $property);
```

`property_exists` não resolve `"form.files"`. O `data_get` da linha seguinte resolveria
numa boa. A guarda derruba justamente o que ela protege.

Isso pega Livewire Form objects, que é padrão comum no Livewire 3/4, e qualquer array
aninhado. Confirmei que nenhum teste do projeto usa `wire:model` com ponto, grep deu 0.
Por isso passou despercebido.

Duas consequências concretas:

`<x-key-value wire:model="form.metadata" />` explode. `KeyValueRuntime.php:16-18`
chama `validate($value)` com `null`, e o `! is_array(null)` lança
`[TallStackUI] KeyValue: The [value] must be an array.` O componente simplesmente
não funciona com Form object.

`<x-upload wire:model="form.files" multiple delete />` sobe arquivo mas nunca lista.
`UploadRuntime.php:20` pega `null`, e `upload.blade.php:132` esconde a lista inteira
atrás de `@if ($value)`. Some a miniatura, o nome, o botão de excluir e o erro por
arquivo. Escrito como `wire:model="files"` funciona.

Degradam em silêncio também: `NumberRuntime` (min/max), `RatingRuntime` (check de int),
`UploadAsyncRuntime`, `CalendarRuntime`, `DateRuntime`, `TimeRuntime`.

Correção: checar só o primeiro segmento.

```php
if (is_null($property) || ! property_exists($this->livewire, str($property)->before('.')->value())) {
    return null;
}
```

E espelhar em `value()`.

### A3. Radio e checkbox em grupo geram id duplicado, e o label seleciona a opção errada

`src/Support/Blade/BindProperty.php:93-96`

```php
private function id(?string $property = null): ?string
{
    return $this->attributes->get('id') ?? $property;
}
```

O fallback do id é o nome da propriedade. Não tem `uniqid()` nesse caminho.

`radio/main.blade.php:6` emite `id="{{ $id }}"`, e `wrapper/radio.blade.php:7` emite
`for="{{ $id }}"`. Então isso aqui, que é o uso normal:

```blade
<x-radio wire:model="plan" label="Basic" value="basic" />
<x-radio wire:model="plan" label="Pro"   value="pro" />
<x-radio wire:model="plan" label="Team"  value="team" />
```

vira três `<input id="plan">` e três `<label for="plan">`.

Pela spec do HTML, quando o label tem `for`, ele ganha do input que está dentro dele.
E `for` resolve pelo primeiro elemento com aquele id. Resultado: clicar no texto "Pro"
ou "Team" marca "Basic". Só funciona se você acertar a bolinha.

Tem um efeito secundário que vale pra todos os componentes de form, não só radio.
Dois componentes Livewire na mesma página amarrados na mesma propriedade, tipo o padrão
Create e Update em modal, os dois com `<x-input wire:model="post.title" />`, geram dois
`<input id="post.title">`. Aí `$tsui.focus('post.title')` usa `getElementById`
(`js/globals/globals.js:137`) e foca o input do modal fechado. O fallback por
`x-ref` colide igual, porque `InputRuntime.php:27` faz `'ref' => $property ?? uniqid()`.

Os componentes `.group` não têm esse problema, `SelectionGroupRuntime.php:18` monta um
`reference` e o item vira `{$reference}-{$index}`. O padrão certo já existe no projeto.

### A4. Mensagem de erro repete uma vez por opção do grupo

`src/resources/views/components/wrapper/radio.blade.php:23-25`

Cada `<x-radio>` é um wrapper próprio, e cada um resolve `$property = 'plan'`. Com três
radios e a validação falhando, "The plan field is required." aparece três vezes empilhada.

Mesma raiz do A3. Os componentes `.group` centralizam o erro no `<fieldset>`
(`checkbox/group/main.blade.php:25-27`), o wrapper avulso não tem esse gancho.

### A5. Currency fora do Livewire envia o valor formatado — [CORRIGIDO]

> `currency.blade.php:22` passou a usar `->except(['name', 'value'])`, igual a date,
> time, color e tag. O hidden continua sendo o único portador do `name`, e o
> `getElementsByName(...)[0]` do alpine continua acertando ele.
>
> Testes em `src/Components/Form/Currency/BrowserTest.php`, numa página Blade pura sem
> nenhum componente Livewire (rota própria em `defineWebRoutes`): `native_form_submits_a_single_named_input`,
> `native_form_submits_the_raw_value`, `native_form_submits_the_decimal_value` e
> `native_form_renders_the_initial_value` — este último é o teste de não-regressão,
> passa antes e depois.


`src/resources/views/components/form/currency.blade.php:22`

```blade
{{ $attributes->whereDoesntStartWith('wire:model') }}
```

`name` não é parâmetro do construtor, então sobrevive ao filtro e é re-emitido no input
visível. Aí ficam dois inputs com o mesmo `name`, o hidden e o visível.

Todos os irmãos removem:

- `date.blade.php:30` faz `->except(['name', 'value'])`
- `time.blade.php:22` faz `->except('name')`
- `color.blade.php:25` faz `->except(['name', 'value'])`
- `tag.blade.php:38` faz `->except(['value', 'name'])`, e o comentário lá explica exatamente por quê

Currency é o único que não faz.

Num form normal com `<x-currency name="price" symbol currency />`, digitando `1234,56`,
o DOM fica com `<input hidden name="price" value="123456">` e depois
`<input name="price" value="1.234,56">`. PHP fica com o último. O servidor recebe
`1.234,56`, a validação `numeric` quebra ou o cast trunca pra `1`.

Correção: `{{ $attributes->whereDoesntStartWith('wire:model')->except(['name', 'value']) }}`.

### A6. Signature apaga a assinatura quando a janela muda de tamanho — [CORRIGIDO]

> Deu para preservar. Três partes: o listener virou `() => this.size()` (o `bind`
> repassava o Event como `clear`); o `size()` agora copia o canvas para um canvas
> auxiliar, redimensiona e redesenha escalado; e um flag `drawn` distingue "tem traço"
> de "canvas em branco", para que um resize sem desenho não fabrique um data URL
> branco no model. Também ganhou `destroy()` removendo o listener e soltando a pilha
> de undo.
>
> A pilha de undo é reiniciada no resize de propósito: as `ImageData` guardadas têm as
> dimensões antigas e o `putImageData` as repintaria sem escala.
>
> Testes em `src/Components/Signature/BrowserTest.php`:
> `keeps_the_drawing_when_the_window_is_resized` (conta pixels desenhados antes e depois)
> e `keeps_the_model_null_when_resized_without_drawing`.


`src/Components/Signature/alpine.js:28`

```js
window.addEventListener('resize', this.size.bind(this));
```

`bind` repassa os argumentos da chamada. O handler de resize recebe o Event, então roda
`size(Event)`, e `clear` fica truthy. A guarda `if (!clear) return` nunca dispara e o
`clear()` roda: limpa o canvas e faz `this.model = null`.

O default da assinatura é `size(clear = false)`, o que já mostra que a intenção era o
contrário.

Pra reproduzir: assinar, redimensionar a janela. Ou no celular, girar a tela, ou só
abrir o teclado. Some o desenho e o model vai pra `null`. Se o usuário salvar logo
depois, vai vazio.

Correção: `window.addEventListener('resize', () => this.size())`.

O componente também não tem `destroy()` nenhum, então o listener sobrevive a morph e a
`wire:navigate`, segurando o canvas e a pilha de undo (uma `ImageData` por traço).

### A7. Busca do select styled só enxerga a janela do `lazy` — [CORRIGIDO]

> Não existe o trade-off que parecia existir. O `lazy` passou a ser aplicado só quando
> não há termo de busca:
>
> ```js
> available = this.lazy && this.search === '' ? available.slice(0, this.lazy) : available;
> ```
>
> Com busca ativa o filtro varre a lista inteira, e a linha 932 (que já existia) recorta
> a janela **sobre o resultado**, não sobre o universo. Buscar `9999` em 10.000 itens
> devolve 1 resultado e ele aparece.
>
> A preocupação de "como exibir que ele selecionou um dos últimos itens" já estava
> resolvida antes desta mudança: o `hydrate()` (`alpine.js:551-555`) sempre cruza o model
> contra `this.options`, a lista completa, justamente para não perder seleção que está
> fora da fatia renderizada. O comentário lá diz isso com todas as letras. Ou seja, o que
> é exibido no gatilho vem de `selects`, não de `available`.
>
> Testes em `SelectStyledCommonBrowserTest.php`: `can_search_beyond_the_lazy_window`
> (lista de 500, `lazy=10`, busca "Option 499") e `keeps_the_lazy_window_while_no_search_is_active`,
> que garante que o scroll infinito continua recortando quando não há busca.


`src/Components/Form/Select/Styled/alpine.js:868`

```js
if (this.common) {
  available = this.lazy ? available.slice(0, this.lazy) : available;
}
```

Corta antes de filtrar. Com `<x-select.styled searchable :options="range(1,10000)" :lazy="10" />`,
que é o exemplo do próprio `FeatureTest.php:131`, digitar `9999` não acha nada.

O `lazy` existe pra lista grande, que é exatamente onde a busca importa mais. Sem `lazy`
funciona normal.

### A8. Valor `0` some no select styled fora do Livewire — [CORRIGIDO]

> `set input(value)` e `initAsVanilla()` passaram a testar `null`/`undefined`/`''` em vez
> de truthiness.
>
> Teste em `SelectStyledCommonBrowserTest.php`: `native_form_submits_a_zero_value`, numa
> página Blade pura, escolhendo a opção de valor `0` e conferindo o hidden e o que o
> servidor recebe. Os 54 testes de browser do Select continuam passando.


`src/Components/Form/Select/Styled/alpine.js:822` e `:89`

```js
input.value = !value ? '' : (...)
```

`!0` é `true`, então o hidden input recebe `''`. Num form com opções
`[['label'=>'Inativo','value'=>0], ['label'=>'Ativo','value'=>1]]`, escolher "Inativo"
manda `status=` vazio.

Correção: `value === null || value === undefined || value === ''`.

### A9. Botão de "hora atual" grava hora inválida no formato 12h — [CORRIGIDO]

> `this.hours = full ? hours : hours % 12 || 12;`, e o evento `current` passou a levar a
> hora já convertida.
>
> Testes em `src/Components/Form/Time/BrowserTest.php`, com o relógio do browser
> congelado (helper `freeze()`, sobrescreve `Date`) para não depender da hora em que a
> suíte roda: `current_time_helper_stays_within_the_twelve_hour_range` (13:45 → `01:45 PM`),
> `current_time_helper_writes_midnight_as_twelve_am` (00:30 → `12:30 AM`) e
> `current_time_helper_keeps_the_twenty_four_hour_reading` como não-regressão.


`src/Components/Form/Time/alpine.js:112-132`

```js
const hours = date.hour();
if (!full) this.interval = hours >= 12 ? 'PM' : 'AM';
this.hours = hours;
```

Não converte pra 12h. Com `<x-time format="12" wire:model="hora" helper />` às 13:45,
clicar no helper grava `"13:45 PM"`. À meia-noite e meia grava `"00:30 AM"`.

O slider é `min=1 max=12`, então o valor também fica fora de faixa. E o
`TimeRuntime::validate()` só exige a presença de `AM|PM`, não barra.

Correção: `this.hours = hours % 12 === 0 ? 12 : hours % 12;` no ramo `!full`.

### A10. Banner com array vazio derruba a página — [CORRIGIDO]

> O ternário virou `match (true)` com o caso do array vazio devolvendo `null`, que o
> tipo `string|array|Collection|null` já aceita.
>
> Testes em `src/Components/Banner/FeatureTest.php`: `does not throw when the text array
> is empty` e `does not throw when the text collection is empty`.


`src/Components/Banner/Component.php:114`

```php
$this->text = $this->rotate !== false
    ? implode($this->separator, $this->text)
    : $this->text[array_rand($this->text)];
```

`<x-banner :text="[]" />` ou `<x-banner :text="collect()" />` sem `rotate` lança
`ValueError: array_rand(): Argument #1 ($array) must not be empty`. Executei pra confirmar.

Array e Collection são entrada documentada, os testes cobrem
`:text="['Foo']"` e `:text="collect(['Foo'])"`. Coleção vazia é o caso degenerado natural,
tipo `<x-banner :text="$avisos" />` com a query sem resultado.

O ramo com `rotate` não quebra, `implode` com array vazio devolve string vazia.

Correção: `: ($this->text === [] ? null : $this->text[array_rand($this->text)]);`

### A11. Customização por escopo joga fora a customização global — [CORRIGIDO]

> `ManagesClasses.php:65` virou `$merge = array_merge($soft, $scoped);`. O `Arr::only`
> da linha 71 continua fazendo o recorte pelas chaves reais do componente.
>
> Teste em `tests/Feature/Support/CustomizationTest.php`:
> `keeps the global customization on a scoped instance`.


`src/Support/Concerns/BaseComponent/ManagesClasses.php:65`

```php
$merge = $scoped === [] ? $soft : Arr::only(array_merge($soft, $scoped), array_keys($scoped));
```

O `array_merge` já resolve a precedência. O `Arr::only` em cima disso apaga todo bloco
que o escopo não mencionou, e a linha 71 devolve esses blocos ao valor original.

```php
TallStackUi::customize()->alert()->block('wrapper')->append('brand-shadow');
TallStackUi::customize('alert', scope: 'flat')->block('text.title')->append('text-xl');
```

`<x-alert title="X" />` sai com `brand-shadow`. `<x-alert title="X" scope="flat" />` perde
o `brand-shadow`. Vale também pros escopos que o próprio pacote registra, tipo
`<x-card scope="card-shadowless">`, que descarta toda customização global de Card.

Correção: `$merge = array_merge($soft, $scoped);` e deixar o `Arr::only` da linha 71 fazer o resto.

### A12. `data_set` colapsa blocos irmãos no caminho com escopo — [CORRIGIDO]

> O `compile()` resolve o container do escopo primeiro e grava o bloco como chave plana
> dentro dele, em vez de mandar `"escopo.bloco"` inteiro para o `data_set`. Isso preserva
> o aninhamento para escopos com ponto no nome (`form.currency.input`, que o pacote usa
> internamente) sem deixar o ponto do **bloco** ser lido como caminho.
>
> O `get()` tinha exatamente o mesmo bug e foi corrigido junto — apareceu porque o teste
> `can extend a scope that has already been defined` quebrou na primeira rodada.
>
> Teste em `tests/Feature/Support/CustomizationTest.php`:
> `does not let a scoped block swallow its dot notation sibling`, usando o par
> `body` / `body.paddingless` do Card.


`src/Customization/CustomizationFactory.php:220`

```php
if ($this->scope !== null) {
    data_set($this->parts, $this->scope.'.'.$block, $compiled);
} else {
    $this->parts[$block] = $compiled;
}
```

Bloco é dot notation. Quando o componente tem `body` e `body.paddingless`, o `data_set`
trata `body` como nó intermediário e sobrescreve o irmão. Quem grava por último ganha,
o outro some.

```php
TallStackUi::customize('card', scope: 'flat')->block([
    'body'             => 'grow px-2 py-2',
    'body.paddingless' => 'p-0!',
]);
```

O caminho sem escopo é plano e não sofre disso.

Componentes com par colidente: Card, Modal, Slide, CommandPalette, Form\Select\Styled,
Layout\Header, Layout\SideBar\Item (8 pares), Layout\SideBar\Separator (6 pares).

Efeito colateral: `CustomizationFactory::get()` é tipado `?string`, mas depois da colisão
o `data_get` devolve array, o que dá `TypeError`.

### A13. Config publicada não consegue encurtar lista nenhuma — [CORRIGIDO]

> Novo helper `__ts_merge_configuration()` em `src/helpers.php`, usado no lugar do
> `array_replace_recursive`. A regra: **lista de escalares é substituída inteira**,
> qualquer outra coisa continua mesclando.
>
> Isso resolve os cinco casos da tabela e ainda preserva o motivo de existir do merge:
> as entradas de componente são tuplas `[Classe::class, [...opções]]`, que não são lista
> de escalares, então continuam mesclando e uma config publicada desatualizada não perde
> chaves novas de um release posterior.
>
> Testes em `tests/Feature/Support/ConfigurationTest.php`, cobrindo encurtar lista,
> esvaziar lista, preservar chave não mencionada, e o caso da tupla.


`src/TallStackUiServiceProvider.php:117-119`

```php
if ($published = config('tallstackui')) {
    config(['ts-ui' => array_replace_recursive(config('ts-ui'), $published)]);
}
```

`array_replace_recursive` mescla array de índice numérico por índice. Então a config
publicada só consegue aumentar ou trocar no lugar, nunca encurtar.

Três agentes acharam isso separadamente. Resultados reais:

| Chave | Padrão | Você publica | Vira |
|---|---|---|---|
| `table.1.quantity` | `[10,25,50,100]` | `[15,30]` | `[15,30,50,100]` |
| `editor.1.toolbar` | 20 botões | `['bold','italic']` | 20 itens com duplicata |
| `editor.1.sanitization.allowed_tags` | 24 tags | lista menor | não dá pra restringir |
| `editor.1.upload.mimes` | 4 | `['image/png']` | os 4 continuam |
| `debug.environments` | 3 | `['local']` | os 3 |

O caso do `allowed_tags` e `mimes` incomoda mais, porque a própria config descreve isso
como defesa em profundidade e o usuário não consegue apertar a whitelist. Impacto de
segurança é indireto (a sanitização real é client-side e o texto manda sanitizar no
servidor), mas o impacto funcional é direto.

Correção: mesclar recursivo só em array associativo, e substituir listas.

### A14. `<x-slot:label left>` é código morto no checkbox, radio e toggle — [CORRIGIDO]

> Agora é suportado. O `CheckboxRuntime` emite `labelPosition` em vez de `position`, que
> é o nome que colidia com a prop e era descartado pelo snapshot do Laravel. As três
> blades (`checkbox/main`, `radio/main`, `toggle`) passam `:position="$labelPosition"`
> para o `wrapper.radio`.
>
> A prop `position` continua funcionando como antes; o slot só ganha precedência quando
> traz o atributo `left`.
>
> Testes: `can render the label on the left through the slot` nos FeatureTest de
> Checkbox, Radio e Toggle, checando que o label sai **antes** do `<input>`.
>
> As outras colisões da mesma classe listadas no fim do item original (`AutocompleteRuntime`,
> `SelectStyledRuntime`, `SelectionGroupRuntime`, `UploadAsyncRuntime`, `StatsRuntime`)
> não foram tocadas: continuam sem impacto observável.


`src/Support/Runtime/Components/CheckboxRuntime.php:20`

```php
'position' => $slot && $label->attributes->has('left') ? 'left' : $this->data('position'),
```

Confirmei o mecanismo no framework. `ManagesRender.php:45` injeta o runtime na view, mas
o Laravel aplica o snapshot das props depois:
`vendor/laravel/framework/src/Illuminate/View/Concerns/ManagesComponents.php:96` chama o
closure e a `:99` faz `$view->with($data)`, onde `$data` são as props do componente.
`View::with()` é `array_merge`, então prop com nome igual ganha do runtime.

Checkbox, Radio e Toggle todos declaram `public ?string $position = 'right'`. O `'left'`
calculado é descartado.

```blade
<x-checkbox wire:model="agree">
    <x-slot:label left>I agree</x-slot:label>
</x-checkbox>
```

Label sai na direita. O irmão `alignment` funciona, porque não existe prop com esse nome.

Correção: emitir com outro nome, tipo `labelPosition`, e ler isso na blade.

Mesma classe de colisão, sem impacto hoje: `AutocompleteRuntime.php:14` (o `id` do bind
é descartado), `SelectStyledRuntime.php:22`, `SelectionGroupRuntime.php:17`,
`UploadAsyncRuntime.php:44`, `StatsRuntime.php:23`.

### A15. SideBar `smart` derruba a página quando a rota atual não tem nome — [CORRIGIDO]

> Corrigido junto com o A16, é a mesma linha:
>
> ```php
> if (blank($name = $route?->getName())) {
>     return false;
> }
> ```
>
> Teste em `src/Components/Layout/SideBar/Item/FeatureTest.php`:
> `does not match when the current route has no name`, com uma rota real declarada sem
> `->name()` e um request de verdade contra ela.


`src/Components/Layout/SideBar/Item/Component.php:94`

```php
return $this->route === route($route->getName(), ...);
```

Rota sem `->name()` faz `getName()` voltar `null`, e `route(null)` lança
`RouteNotFoundException`. Com `Route::get('/reports', fn () => 'ok');` e um layout com
`<x-side-bar smart>`, a página inteira vira `ViewException: Route [] not defined.`

Contorno: passar `:route="/reports"` (o ramo de string) ou usar `match=`.

### A16. SideBar `smart` derruba as páginas de erro — [CORRIGIDO]

> Mesma correção do A15. O `?->` cobre o `getCurrentRoute()` nulo das views de erro.
>
> Teste: `does not match when there is no current route`.


`src/Components/Layout/SideBar/Item/Component.php:90-94`

```php
$route = Route::getCurrentRoute();   // null quando nada casou
return $this->route === route($route->getName(), ...);
```

Numa view de erro não existe rota corrente. `resources/views/errors/404.blade.php` usando
um layout com `<x-side-bar smart>` dá
`Call to a member function getName() on null`. A própria página de erro passa a 500.

Mesma correção dos dois: `if (blank($name = $route?->getName())) { return false; }` antes
da comparação.

### A17. Table com `simplePaginate()` é fatal — [CORRIGIDO]

> Pelo caminho que você escolheu: forçar `simple` quando as linhas não são length-aware,
> em `TableRuntime::paginating()`:
>
> ```php
> 'simple' => $this->data('simplePagination') || ! $rows instanceof LengthAwarePaginator,
> ```
>
> A flag `simple-pagination` continua valendo por cima. Nenhuma view precisou de guarda.
>
> Teste em `src/Components/Table/FeatureTest.php`:
> `renders the simple paginator when the rows are not length aware`. Sem o patch ele
> reproduz o erro exato do relatório: `Method Illuminate\Support\Collection::total does not exist.`


`src/Support/Runtime/Components/TableRuntime.php:20` trata qualquer `AbstractPaginator`
como paginado, mas as 3 views de paginator chamam API que só existe no
`LengthAwarePaginator`: `total()`, `lastPage()` e `$elements`
(`paginators/simple.blade.php:66,99`, `minimal.blade.php:65,97`, `compact.blade.php:26,61`).

`<x-table :$headers :rows="User::simplePaginate(10)" paginate />` lança
`Method Illuminate\Support\Collection::total does not exist.`

O construtor aceita `Paginator` explicitamente (`Table/Component.php:32`) e a doc lista
`LengthAwarePaginator|Paginator|Collection|array`. Só funciona junto com a flag
`simple-pagination`.

Correção: guardar as seções com `@if ($paginator instanceof LengthAwarePaginatorContract)`,
ou forçar `$simple = true` quando `rows` não for length-aware.

### A18. Timeline ignora as props do pai quando os itens vêm por slot — [CORRIGIDO]

> Duas metades, como o diagnóstico previa.
>
> `horizontal`, `alternate` e `compact` saíram do construtor do `Timeline\Items`, então o
> `@aware` passa a alcançar o pai. O `timeline/main.blade.php` parou de repassá-los
> explicitamente, porque agora o `@aware` resolve nos dois modos.
>
> `color` e `style` não podiam sair do construtor (o `CompileColors` lê `$this->color` e
> `$this->style` antes da view rodar), então o default virou `null` e o `setup()` — que
> roda antes da compilação de cores — herda do pai via
> `$factory->getConsumableComponentData()`, a mesma API que o `@aware` usa por baixo.
>
> Testes em `src/Components/Timeline/FeatureTest.php`, incluindo um que garante que a cor
> **não** vaza de um ancestral qualquer: `does not inherit color from an ancestor other
> than the timeline` (um `<x-card color="red">` em volta não contamina o item).


`src/Components/Timeline/Items/Component.php:27,31,33,35,37` declaram `color`, `style`,
`horizontal`, `alternate` e `compact` com defaults próprios. Como o `@aware` lê primeiro
o `componentData` do próprio filho, nunca chega no pai.

`<x-timeline horizontal>` com filhos em slot: o wrapper sai `flex-row`, mas o item não
emite o par `data-timeline-line-left/right`, ou seja, layout vertical dentro de container
horizontal. `<x-timeline color="red">` com filhos em slot sai tudo `primary-`.

Passando `:items="[...]"` funciona, porque `timeline/main.blade.php:23-26` repassa explícito.

Correção: tirar `horizontal`, `alternate` e `compact` do construtor do Items, aí o `@aware`
resolve nos dois modos. `color` e `style` precisam ser resolvidos no runtime.

### A19. O script de CI conta 7 componentes que nunca chegou a verificar — [CORRIGIDO]

> Três mudanças em `scripts/find-unused-customization-blocks.php`:
>
> 1. `extractBladeViewName()` virou `extractBladeViewNames()`: extrai o corpo do
>    `blade()` e pega **todos** os literais de view, então o ternário do skeleton passa a
>    casar e as duas views são escaneadas.
> 2. O `$totalComponents++` e o `$totalKeys +=` só acontecem depois que a view resolve.
>    Componente cuja view não resolve entra numa lista `$unresolved` e o script **falha**
>    dizendo quantos ficaram sem verificação, em vez de somar ao total e seguir.
> 3. O `hasSpread`, que era detectado e ignorado, agora vira aviso na saída.
>
> Verificação: injetando um bloco falso no Card (um dos 7 com ternário), o script passou a
> apontá-lo, com o `skeleton.blade.php` na coluna de blade. Antes ele passava batido.
> Sem o bloco falso, segue em `86 components, 1672 customization keys` — o mesmo número
> de antes, só que agora os 86 foram de fato verificados.


`scripts/find-unused-customization-blocks.php:236`

```php
if (preg_match("/view\s*\(\s*'([^']+)'\s*\)/", $source, $match)) {
```

A regex exige string literal direto dentro de `view(`. Quem usa ternário não casa, volta
`null`, e cai no `continue` da linha 340. Só que o `$totalComponents++` está na 326 e o
`$totalKeys +=` na 331, ou seja, antes.

Os 7 com ternário: Chart, Card, Stats, Table, List\Items, List\Main, Step\Main.

Então "Scanned 86 components, 1672 customization keys. All customization blocks are in
use!" inclui 7 componentes e uns 252 blocos que ninguém olhou. O script passa e dá uma
garantia que não tem.

O script também tem um flag `hasSpread` que ele detecta e nunca usa, então blocos vindos
de spread (`...$this->input()`) também escapam.

---

## Médio

### M1. ESC dentro de modal fecha o dropdown e o modal junto — [CORRIGIDO]

> O floating continua fora do `__tsui_elements` (a decisão que evita o deadlock do
> scroll-lock segue de pé). O que entrou foi um registro separado, só para o escape:
> `window.__tsui_floating_open`, alimentado pelo `watch(showName, ...)` do
> `Floating/alpine.js`.
>
> O floating aberto **reivindica** a tecla (`escape_claim`, marca o próprio Event), e o
> modal e o slide consultam `escape_claimed($event)` antes de fechar. Precisa das duas
> metades porque os listeners estão todos em `window` e a ordem é a de registro: se o
> overlay roda primeiro, ele vê o popup ainda aberto no registro; se roda depois, vê a
> marca deixada no evento.
>
> Teste em `src/Components/Floating/BrowserTest.php`:
> `escape_closes_the_floating_without_closing_the_modal` — o primeiro ESC fecha só o
> select, o segundo fecha o modal.


`src/resources/views/components/floating/main.blade.php:11` não tem guarda:

```blade
x-on:keydown.escape.window="{{ $attributes->get('x-show', 'show') }} = false"
```

`modal/main.blade.php:18` e `slide/main.blade.php:13` têm:

```blade
x-on:keydown.escape.window="top_ui && (show = false)"
```

Os dois listeners são `.window`, então nenhum consegue barrar o outro. E o floating nunca
entra no `__tsui_elements`, isso é proposital, o comentário em `js/helpers.js:134-141`
diz explicitamente que floating fica fora da pilha "que decide qual overlay é dono do
escape e do click-outside". É essa decisão que cria o furo: o modal acha que é o topo.

```blade
<x-modal wire title="Editar">
  <x-select.styled :options="$options" wire:model="role" />
</x-modal>
```

Abrir o modal, abrir o select, apertar ESC. Fecha os dois. Perde o formulário em andamento.

Pega todo consumidor de `<x-floating>`: dropdown, submenu, select.styled, autocomplete,
date, time, color, password, upload, calendar, list.items.

O click-outside está certo, `floating/main.blade.php:9` tem `x-on:mousedown.stop`. Só o
ESC ficou de fora. E o teste que existe (`Modal/BrowserTest.php:471`) cobre modal + slide,
não modal + floating.

### M2. `position="auto"` é aceito pelo PHP e ignorado pelo Alpine — [CORRIGIDO]

> Pelo caminho que os dois agentes discordavam: resolvido dentro do
> `Floating\Component::anchor()`, sem mexer na lista `ALLOWED` compartilhada. Tooltip e
> Reaction, que usam o outro motor e suportam os 15 de verdade, ficaram intactos.
>
> `auto` → `bottom`, `auto-start` → `bottom-start`, `auto-end` → `bottom-end`. Mantém o
> lado para onde o Floating UI já caía e recupera o alinhamento start/end, que era o que
> se perdia em silêncio.
>
> Testes em `src/Components/Floating/FeatureTest.php`, com dataset para os três `auto*`
> mais um de não-regressão para posição concreta.


`src/Exceptions/InvalidSelectedPositionException.php:9` permite 15 valores, incluindo
`auto`, `auto-start` e `auto-end`.

`src/Components/Floating/Component.php:24` monta `x-anchor.{$this->position}` direto.

Só que o Alpine Anchor conhece 12. Confirmei no vendor,
`vendor/livewire/livewire/dist/livewire.esm.js:8312`:

```js
let positions = ["top", "top-start", "top-end", "right", "right-start", "right-end",
                 "bottom", "bottom-start", "bottom-end", "left", "left-start", "left-end"];
```

`auto*` não está lá. `placement` vira `undefined` e o Floating UI cai no default `bottom`.

`<x-dropdown text="Menu" position="auto-end">` passa na validação e renderiza centralizado
embaixo, não alinhado à ponta. Sem aviso no console.

Confunde mais porque a mensagem da exceção anuncia `auto*` como suportado, e Tooltip e
Reaction usam outro motor (`js/helpers/placement.js`) que suporta os 15 de verdade.
`<x-reaction>` inclusive tem `auto` como default e funciona.

Sobre a correção, dois agentes discordaram e vale registrar. Um sugeriu simplesmente tirar
`auto*` da lista `ALLOWED`. Isso está errado, porque Tooltip e Reaction usam outro motor
(`js/helpers/placement.js`), que suporta os 15 de verdade, e `<x-reaction>` tem `auto` como
default. Tirar da lista compartilhada quebraria os dois. O certo é resolver `auto*` dentro
do `Floating\Component::anchor()` antes de emitir o modifier, ou separar o validador por
motor.

### M3. `Livewire.hook('commit.prepare')` não existe no Livewire 4 — [CORRIGIDO]

> Trocado por `commit`, que é o hook que dispara enquanto a requisição está sendo montada
> (`livewire.esm.js:10759`). Ele entrega `{ component, commit, respond, succeed, fail }`.
>
> Aproveitei o `fail` para soltar o lock quando a requisição falha ou é cancelada: nesse
> caminho o morph nunca roda, então o `morph.updated` que já existia não bastaria e o
> overflow ficaria travado depois do spinner sumir.
>
> Teste em `src/Components/Loading/BrowserTest.php`:
> `locks_the_body_overflow_while_the_request_is_in_flight`, conferindo o overflow durante
> e depois do request.


`src/Components/Loading/alpine.js:5`

```js
Livewire.hook('commit.prepare', ({ component }) => {
  if (component.name !== name) return;
  overflow(true, 'loading', overflowing);
});
```

Enumerei todos os `trigger()` do `livewire.esm.js` da v4.3.3 instalada:
`commit`, `component.init`, `directive.global.init`, `directive.init`, `effect`, `effects`,
`element.init`, `island.morph`, `island.morphed`, `morph`, `morph.added`, `morph.adding`,
`morph.removed`, `morph.removing`, `morph.updated`, `morph.updating`, `morphed`,
`navigate.request`, `request`, `stream`.

`commit.prepare` não aparece em lugar nenhum do pacote. E `Livewire.hook` é só um
`listeners[name].push(callback)`, não valida nome, então falha calada.

O `composer.json` fixa `^4.3.0`, não tem fallback pra v3.

Resultado: o `overflow(true, 'loading')` nunca roda. Só o `morph.updated` roda, que faz
`overflow(false, ...)`. A opção `'overflow' => false` documentada em `src/config.php:462`
diz que serve pra "avoids hiding the overflow", ou seja, o padrão deveria travar o scroll
durante o loading. Não trava.

### M4. Toast flashado ressuscita e limpa os outros — [CORRIGIDO]

> Entrou um flag de instância `flashed`, então o bloco do flash roda uma vez só. Os dois
> caminhos de entrada do `init()` (`window.onload` e `livewire:navigated`) continuam
> cobertos — o primeiro a chegar consome, o segundo não duplica —, e o evento de janela
> deixa de arrastar o flash junto.
>
> Teste em `src/Components/Toast/BrowserTest.php`: `a_flashed_toast_is_consumed_once`.
> Ele espera o flash expirar sozinho antes de disparar o próximo toast, que é exatamente
> o momento em que o bug o ressuscitava.


`src/Components/Toast/toast-base.js:45-68`

(Nota: o agente que achou isso trocou os nomes dos arquivos no relatório. O `add()` está
no `toast-base.js`, não no `toast-loop.js`. Verifiquei.)

```js
add(event) {
  this.$nextTick(() => (this.show = true));

  if (flash) {
    this.flush();
    this.toasts.push(flash);
  }
  ...
}
```

`add()` é o handler de `x-on:ts-ui:toast.window="add($event)"`. O `flash` é o parâmetro do
closure, vindo de `@js(session()->pull('ts-ui:toast'))`, e nunca é zerado.

A guarda foi escrita pro caminho de carregamento inicial (commit `6f9258c6`), mas o mesmo
método atende o evento de janela.

Então: controller manda um toast e redireciona. Na página nova o toast aparece e some
normal. Aí qualquer ação Livewire que dispare outro toast faz `flush()`, que limpa tudo
que está na tela, e empurra o toast antigo de novo com timer novo. Repete o resto da vida
da página. Em modo `stacked` isso destrói a pilha inteira.

Dialog e Banner não têm esse problema, os dois só atribuem.

Não tem teste cobrindo. Dialog e Banner têm `can_display_flash_on_page_reload`, Toast não.

### M5. Timer do toast não é limpo no teardown — [CORRIGIDO]

> O id do interval saiu do `const` local e virou propriedade (`interval`), com um método
> `stop()` idempotente chamado pelo `destroy()`, pelo auto-clear e pelo caminho de timeout.
>
> Isso fecha o furo do `flush()` do pai, que remove o toast do array com `show === true` e
> por isso nunca acionava o auto-clear.
>
> Teste em `src/Components/Toast/BrowserTest.php`:
> `a_toast_dropped_by_sole_does_not_keep_its_timer_running`, que escuta `toast:timeout` no
> window e exige zero disparos depois de um `sole()` descartar o toast anterior.


`src/Components/Toast/toast-loop.js:26-47` cria o interval, e o `destroy()` na `:92`
desconecta o `ResizeObserver` e tira o listener de `visibilitychange`, mas não faz
`clearInterval`. O id do interval é `const` local dentro do `$nextTick`, então nem dá pra
alcançar de fora.

O auto-clear `if (!this.show) clearInterval(interval)` só cobre o caminho do `hide()`.
Não cobre remoção via `flush()` do pai (`toast-base.js:87`), que mexe no array direto e
deixa o filho com `show === true`.

Consequências:

`$this->toast()->info('B')->sole()->send()` com o toast A na tela destrói o A com
`show === true`. O interval do A continua e, ao vencer, dispara `event('toast:timeout')`
e, se A tinha hook de timeout, um `Livewire.find(...).call(...)` por um toast que o
usuário nunca viu expirar.

Se A foi destruído congelado (hover ou pilha expandida), o `elapsed >= max` nunca chega e
o interval roda pra sempre.

Depois de `wire:navigate`, `Livewire.find()` devolve `undefined` e o timer órfão lança
`TypeError`.

O M4 faz isso acontecer com frequência, porque chama `flush()` a cada toast.

### M6. Autocomplete não mostra erro de validação

`src/resources/views/components/form/autocomplete.blade.php:18-37` repassa só props
nomeadas pro input interno. Não passa `$attributes`, não passa `wire:model`, não passa
`name` e, diferente de date, time, color e currency, não passa `:alternative="$property"`.

`AutocompleteRuntime.php:13-17` até calcula `property`, `error` e `validate`, mas o
template não usa nenhum.

Aí o `Form\Input` interno resolve `property = null`, e `wrapper/input.blade.php:17`
(`@if ($property && !$invalidate)`) nunca renderiza o erro. O `$error` fica `false` e a
borda vermelha de `form/input.blade.php:30` também não aparece.

Com `wire:model="city"` e `$this->validate(['city' => 'required'])` falhando, o Livewire
registra o erro e o componente não mostra nada. Todos os outros mostram.

O componente ainda expõe uma prop `invalidate` cuja única função é suprimir a exibição do
erro, o que mostra que a exibição era pra existir.

Correção: passar `:alternative="$property"` no input interno, que é o mecanismo que date
e time já usam.

### M7. `disabled` no tag e no color é só visual

Nos dois o input de texto recebe `disabled` e o estilo muda, mas os controles continuam vivos.

Tag, `src/resources/views/components/form/tag.blade.php`: a linha 27 tem o botão de
remover chip com `x-on:click="remove(index)"` sem guarda, e as 52-57 têm o limpar tudo
com `x-on:click.prevent="erase()"` sem guarda. `Tag/alpine.js:84-102` também não checa nada.

Com `<x-tag wire:model="tags" disabled />` e `$tags = ['php', 'laravel']`, o input está
cinza e sem foco, mas clicar no × do chip apaga e manda pro Livewire.

Color, `src/resources/views/components/form/color.blade.php`: linhas 37-40 e 56-57 abrem a
paleta com `x-on:click="show = !show"`, e a 83 tem `x-on:click="set(color)"`.
`Color/alpine.js` não tem uma menção sequer a `disabled`.

Com `<x-color wire:model="brand" disabled />` dá pra abrir a paleta e trocar a cor.

Todo o resto guarda direito: date (37,44,51,52), time (29,45), number (32,40,48,56),
select.styled (46,55,92,110), checkbox e radio group (15). Só esses dois não.

### M8. `wire:change` não dispara em vários caminhos

Select styled, `alpine.js:320-324` e `:402-424`: adicionar item chama `wireChange`,
remover não. O `select()` sai cedo quando o item já está marcado, e o `clear()` sai antes
do `wireChange` no ramo `multiple`.

Com `<x-select.styled multiple wire:model="ids" wire:change="recalcular" />`, adicionar 3
roda o método 3 vezes, tirar um do × não roda.

Date e Calendar, `Date/alpine.js:241-268` e `:562-568` (mesmo padrão em
`Calendar/alpine.js:222-248` e `:625-631`): o `select()` faz `return this.refresh()` nos
ramos `multiple` e `range` antes do `wireChange`. E o `clear()` nunca chama `wireChange`
em modo nenhum.

Com `<x-date range wire:model="periodo" wire:change="filtrar" />`, escolher início e fim
não chama `filtrar`. Limpar a data também não.

### M9. `ErrorsColors` não recebeu as cores novas do Tailwind

`src/Support/Colors/Components/ErrorsColors.php:24-115`

`background()`, `border()` e `text()` param em `rose`. É o único conjunto de chaves do
pacote que não bate com as 29 canônicas, os outros 85 batem.

O commit `f5699484` que adicionou `mauve`, `olive`, `mist` e `taupe` mexeu no
`ErrorsColors.stub` (+12 linhas) e esqueceu a classe real.

`<x-errors color="mauve" />` passa, porque `Component.php:27` não valida nada, e cai em
`null` nas três chaves. O `Arr::toCssClasses` descarta null em silêncio, então a caixa sai
sem fundo, sem borda e com a cor do texto herdada.

### M10. Publicar o stub do Chart zera a paleta inteira

`src/Support/Colors/Components/ChartColors.php:16`

```php
$palette = array_merge($this->text(), (array) $this->get('text'));
```

Todo mundo filtra null antes de mesclar. `ToastColors.php:16-18` e `DialogColors.php:23-29`
usam `array_merge($this->x(), array_filter($x))`. ChartColors não.

Aí `php artisan tallstackui:setup-color` no Chart e deixar o arquivo publicado como veio
(que é o fluxo documentado, "sobrescreva só o que quiser") faz o array do usuário, com as
29 chaves em `null`, sobrescrever os defaults.

`ChartRuntime.php:258` resolve `''` pra toda série, fatia e legenda. Perdem o `text-*` e
caem no `currentColor`.

Correção: `array_merge($this->text(), array_filter((array) $this->get('text')));`

### M11. `<x-card bordered light>` perde a borda

`src/Support/Colors/Components/CardColors.php:37-69`

A variação `border` só tem o bucket `solid`. A `background` tem `solid` e `light`.

`Card/Component.php:51-52` calcula `style` e `variation` de forma independente, então
`<x-card color="red" bordered light>` procura `border.light.red`, não acha nem no array do
usuário nem no fallback, e volta `null`.

Cada um sozinho funciona. Juntos, o `bordered` é ignorado sem aviso.

### M12. `tallstackui:setup-color` quebra em 4 entradas do próprio menu

`src/Console/SetupColorCommand.php:61`

O menu é montado de `#[ColorsThroughOf]`, que dá 32 refs. Mas faltam 3 stubs:
`BackToTopColors.stub`, `DialColors.stub`, `TimelineColors.stub`.

Escolher `BackToTop`, `Dial`, `Timeline` ou `Timeline\Items` faz o `file_get_contents`
num caminho inexistente, o E_WARNING vira `ErrorException`, cai no catch e sai com
"Something went wrong: failed to open stream". Não corrompe nada, mas o fluxo documentado
não existe pra esses.

### M13. `timeout` do toast na config não é aplicado

`src/Interactions/Toast.php:172`

```php
'timeout' => $this->timeout,
'position' => $this->position ?? $configuration['position'] ?? 'top-right',
```

A linha do `position` lê a config, a do `timeout` não. A config só é consultada dentro de
`timeout(?int $seconds = null)` na linha 144, ou seja, só se você escrever
`->timeout()` sem argumento.

Então `'timeout' => 10` na config não muda nada em
`$this->toast()->success('x')->send()`, que continua usando o `3` hardcoded.

### M14. `color.1.custom` na config é chave morta

`src/config.php:163-174` define `'custom' => []` com o comentário "array of custom colors
to be used in the color picker".

`src/Support/Configurations/CompileConfigurations.php:107` lê outra:

```php
$component->colors ??= $configuration['colors'] ?? [];
```

Nada lê `color.1.custom`. Antes da v3 a chave era `'colors' => null`, e o commit `6c14ccb4`
renomeou na config sem mexer no leitor. Paleta global do color picker é ignorada.
Por instância (`:colors="[...]"`) continua funcionando.

### M15. Vazamentos de listener e de timer

Nenhum derruba nada sozinho, mas todos acumulam em sessão SPA longa.

`src/Components/Carousel/alpine.js`: o `destroy()` na 57 não faz `clearInterval`. O único
`clearInterval` está no `reset()` (:188), que só o `seek()` chama. Com `autoplay` dentro de
um componente Livewire, sair da página deixa o timer chamando `next()` num nó solto pra
sempre, segurando a árvore inteira e as imagens. O `Spinner/alpine.js:10-14` é o padrão
certo, no mesmo repositório.

`src/Components/CommandPalette/alpine.js:56`: `keydown` no `window`, closure anônima, sem
remoção. Depois de uma navegação SPA, `Ctrl+K` dispara dois `open()`, o `__tsui_elements`
fica com 2 entradas, e o `close()` não consegue soltar o scroll-lock (o gate de
`js/helpers.js:118` exige `siblings <= 1`). A página fica sem rolar.

`src/Components/Dropdown/Main/alpine.js:5`: `scroll` no `window`, anônima, sem `destroy()`,
sem `passive`. Uma tabela com um dropdown por linha deixa um handler por linha, cada um
fazendo `getBoundingClientRect()` a cada scroll.

`src/Components/Floating/alpine.js:165`: `Livewire.hook('commit', ...)` registrado por
instância e nunca cancelado. Esse caminho é atingido por todo `<x-select.styled>`, porque
`Form/Select/Styled/Component.php:98` define `'floating' => ['class' => 'w-full ...']`.
No Livewire 4 o `hook()` devolve a função de unsubscribe, então o conserto é trivial.

`src/Components/Form/Upload/alpine.js` e `Upload/Async/alpine.js`: sem `destroy()` nenhum,
e o tipo `upload` não está na lista `COUNTABLE` de `js/helpers.js:37`. Se o upload sumir do
DOM com o preview aberto, o `data-overflow` fica `upload` e nenhum outro componente
consegue mais soltar o lock (o gate da linha 118 compara com `holders(current) !== 0`, e
`holders` devolve `null` pra tipo não contável). Página trava até um reload inteiro.
Marco como parcial: o furo estrutural está confirmado por leitura, mas não reproduzi no
navegador.

### M16. Ordem das respostas em busca remota

Autocomplete, `src/Components/Form/Autocomplete/alpine.js:381-425`: tem `AbortController`,
mas o `.finally` faz `this._abort = null` sem checar se ainda é o dele. Quando o `finally`
da requisição A roda, `this._abort` já aponta pra B, e é zerado. Aí B nunca é abortado, e
o `loading = false` cai enquanto B ainda está no ar.

Select styled, `alpine.js:192-234`: `makeRequest()` não aborta, não tem token de sequência
e faz `this.response = data.map(...)` incondicional. Com endpoint lento, a resposta velha
sobrescreve a nova. O `debounce.500ms` do template reduz a frequência mas não elimina.

### M17. `?` duplicado na URL do select remoto

`src/Components/Form/Select/helpers.js:84`

```js
url += '?' + stringify(params);
```

Com `:request="route('api.users', ['type' => 'admin'])"`, que gera `/api/users?type=admin`,
a URL final vira `/api/users?type=admin?search=foo`. O PHP lê `type` como
`"admin?search=foo"` e o termo de busca nunca chega.

A codificação em si está certa, é só o separador.

Correção: `url += (url.includes('?') ? '&' : '?') + stringify(params);`

### M18. Colar código no pin concatena em vez de substituir

`src/Components/Form/Pin/alpine.js:333`

```js
input.value += data[index];
```

`maxlength="1"` não limita atribuição por script. Com dois dígitos já digitados, colar
`123456` num pin de 6 deixa o campo 1 com `"11"` e o 2 com `"22"`, e o `syncModel()` monta
`"1122456"`, com 7 caracteres. Como `model.length !== length`, o evento `filled` não dispara
e o auto-submit do `smart` não acontece.

### M19. Assets com caminho absoluto quebram em subdiretório

`src/Support/Blade/Directives.php:83`, `:119`, `:120` emitem `/tallstackui/script/...` e
`/tallstackui/style/...` hardcoded.

As rotas são registradas com prefixo, então resolvem pra `{basePath}/tallstackui/...`. App
servida em `https://host/myapp/` gera tag apontando pro lugar errado, 404, e nem o JS nem
o CSS carregam. Todo componente Alpine morre.

O pacote é inconsistente nisso, `CompileConfigurations.php:124` usa
`URL::signedRoute('tallstackui.command-palette.action')` direito.

Correção: `route('tallstackui.script', ['file' => $file])`.

### M20. CSS servido com cache de um ano e nome fixo

`src/Support/Blade/Directives.php:95` devolve `tallstackui.css`, nome constante entre
releases (o JS é hasheado pelo Vite e cache-busta certo).

O `Utils::pretendResponseIsFile` do Livewire manda `public, max-age=31536000`. Com isso o
browser nem revalida.

Depois de `composer update` que mude o CSS, quem já visitou continua com o CSS da versão
anterior por até um ano. Componente novo ou restilizado renderiza sem estilo. Só um hard
refresh resolve.

### M21. `alert(1)` esquecido num template publicado

`src/resources/views/components/form/time.blade.php:78`

```blade
x-on:change="change($event, 'hours');"
{{ $attributes->only('x-on:hour') }}
dusk="tallstackui_time_hours"
x-on:change="alert(1);"
```

Atributo duplicado no mesmo `<input type="range">`. Entrou no commit `1f71ef2a` em
março de 2024 e atravessou dois refactors.

Na prática não dispara, o parser HTML descarta atributo duplicado e o primeiro ganha. Mas
é debug commitado, gera HTML inválido, e está a uma mudança de parser de virar problema.
Blade não é bundleado, então isso vai direto pro consumidor (confirmei que não está no `dist/`).

Tem também um `// AI: remove it` em `src/Components/Form/Autocomplete/alpine.js:419`.

### M22. Table esconde a linha quando o array tem uma só e a chave não é 0

`src/resources/views/components/table/main.blade.php:119`

```blade
@if (is_array($rows) && (count($rows) === 1 && empty($rows[0])))
```

`array_filter` preserva chave. Então `:rows="array_filter($rows, fn ($r) => $r['active'])"`
sobrando uma linha no índice 7 cai nesse `@if` e a tabela mostra o empty state. O agente
reproduziu com `[7 => ['name' => 'AJ']]`: a linha não renderiza. Mesmo efeito com
`$collection->keyBy('id')->all()`.

Dado sumindo em silêncio é pior que erro.

Correção: `count($rows) === 1 && blank(reset($rows))`.

### M23. Slide mistura `position` global com flag inline

`src/Support/Configurations/CompileConfigurations.php:315-317` resolve cada flag isolada
com `??=`, então a flag inline não desliga a que veio da config.

Com `ts-ui.components.slide.1.position => 'left'` na config e `<x-slide bottom>` no
template, o painel sai com `bottom-0 left-0 pr-10 h-[32rem] w-[100dvw]` e transição
`-translate-x-full`. Ou seja, o bottom sheet entra deslizando na horizontal, ancorado no
canto de baixo à esquerda, com uma folga de 2.5rem.

Correção: resolver a posição uma vez e derivar as três flags dela.

### M24. Carousel não reindexa `images`

`src/Components/Carousel/Component.php:33` e `:37` fazem `collect($this->images)` e
`->toArray()` sem `->values()`. O `Gallery/Component.php:37` faz certo.

`<x-carousel :images="collect($images)->where('active', true)" />` gera
`tallstackui_carousel(JSON.parse('{"1":{...},"2":{...}}'), ...)`, objeto em vez de array.
Aí o `x-for="(image, index) in images"` recebe chave string, `current == index + 1` vira
`1 == "21"`, e `images.length` fica `undefined`. Não aparece slide nenhum.

Correção: `collect($this->images)->values()`.

### M25. Tab sem `selected` não renderiza painel nenhum

`tab/main.blade.php:5` e `src/Components/Tab/Main/alpine.js:1-3`. O alpine é
`(selected = null) => ({ selected: selected, tabs: [] ... })`, sem `init()` e sem clamp.

`<x-tab><x-tab.items tab="A">Foo</x-tab.items><x-tab.items tab="B">Bar</x-tab.items></x-tab>`
renderiza `tallstackui_tab(null)` e os painéis com `x-show="selected === 'A'"` e
`selected === 'B'`. Nenhum casa com `null`. Mesma coisa quando o `selected` passado não
bate com nenhum `tab`.

Correção: no `init()`, cair pro `this.tabs[0]?.tab` quando não casar.

### M26. Botão de finalizar do Step usa comparação estrita, o resto não

`step/main.blade.php:55` e `:60` usam `selected === steps.length`, enquanto
`step/items.blade.php:2` e todas as `variations/*.blade.php` usam `parseInt(selected)`, e
o botão de avançar na linha 43 usa `selected < steps.length`, que coage.

Com `public string $step = '2';` e dois itens: `"2" < 2` é falso e esconde o avançar
(certo), mas `"2" === 2` é falso e esconde o finalizar (errado). O último passo fica sem
botão nenhum.

Correção: `parseInt(selected) === steps.length` nas duas linhas.

### M27. `.blur` e `.lazy` no `wire:model` fazem o oposto do que o Livewire 4 define

`src/Support/Blade/Wireable.php:59`

```php
return $wire->hasModifier('live') || $wire->hasModifier('blur')
    ? Blade::render("\$wire.entangle('{$property}').live")
    : Blade::render("\$wire.entangle('{$property}')");
```

O Livewire 4 reescreveu a semântica dos modifiers
(`vendor/livewire/livewire/dist/livewire.esm.js:15189-15197`). Modifier antes do `.live`
controla sincronização no cliente, não rede. Então:

- `wire:model.blur` tem `shouldSendNetwork === false`, não faz requisição nenhuma. O
  comportamento antigo agora se escreve `wire:model.live.blur`.
- `wire:model.lazy` sem `.live` tem `shouldSendNetwork === true`, manda no `change`.

O próprio `@entangle` do Livewire concorda, só acrescenta `.live` pro modifier `live`,
nunca pro `blur` (`vendor/livewire/livewire/src/Features/SupportEntangle/SupportEntangle.php:14`).
E o `PRD-wire-model-modifiers.md` vendorizado documenta isso como breaking change.

Isso importa porque nesses componentes o `wire:model` do usuário é removido do DOM
(`select/styled.blade.php:25-34`, `date.blade.php:30` e o resto), então a diretiva do
Livewire nem roda e o mapeamento do TallStackUI é a ligação de verdade.

Resultado: `<x-tag wire:model.blur="tags" />` faz round trip a cada tag, quando não deveria
fazer nenhum. E `<x-select.styled wire:model.lazy="status" />` nunca commita, quando o
nativo commitaria no change. Pega uns 15 componentes.

Correção: seguir a regra do próprio Livewire, olhando só pro `live`, e tratar `lazy` sem
`live` como live.

---

## Baixo

### B1. Hook `reject` do dialog é inalcançável

`src/Components/Dialog/alpine.js:135` chama `dialog.hooks.reject`, mas
`DispatchInteraction.php:53` só aceita `['ok', 'close', 'dismiss']` pra Dialog. Passar
`->hook(['reject' => ...])` lança `InvalidArgumentException`. O botão Cancel de um dialog
estático não tem como ser gancheado.

### B2. Flash de sessão sobrescreve em silêncio

`src/Interactions/Traits/DispatchInteraction.php:99` usa uma chave fixa por tipo. Dois
`->flash()` seguidos antes do redirect, só o segundo aparece.

### B3. `value()` usa `?:` e descarta o `[]` que o `sanitize()` acabou de produzir

`src/Support/Runtime/AbstractRuntime.php:243`

O `sanitize()` na linha 161 mapeia a string `"[]"` pra array vazio de propósito. Aí o
`value()` trata `[]` como falsy e volta pra string crua `'[]'`.

`<x-date range value="[]" />` fora do Livewire lança
`The [value] must be an array when using the [range] or [multiple]`. Passar array de
verdade (`:value="[]"`) funciona.

Correção: trocar `?:` por `??`.

### B4. Escalar falsy não é selecionável no select styled

`alpine.js:316` faz `if (!option || option.disabled) return;`. Options planas são
suportadas e testadas. `<x-select.styled :options="range(0,10)" />` não deixa clicar no `0`.

### B5. `:only="0"` no date e calendar é ignorado

`Date/alpine.js:426` e `Calendar/alpine.js:462` fazem `this.only && day !== parseInt(this.only)`.
`0` é falsy, curto-circuita, e todos os dias ficam selecionáveis. Com `only="0"` string
funciona por acaso.

### B6. Currency com model `0` abre o campo vazio

`Currency/alpine.js:27` faz `if (this.model)`. O `format()` e o `$watch` tratam `0` certo,
só o `init()` não. Não perde dado, é inconsistência visual.

### B7. Paginator com namespace sem ponto é rejeitado pela própria validação

`Table/Component.php:219-224` aceita `::` ou `.`, mas o `validate()` na `:274` só aceita `.`.
`paginator="mypackage::pagination"` é barrado. Contorno trivial, usar caminho com ponto.

### B8. `$tsui.open.*` não normaliza o id como o PHP

`js/globals/globals.js:10-27` usa a string crua. O lado Blade faz
`str($id)->slug()->kebab()`. Com `id="user_profile"`, o Blade escuta
`modal:user-profile-open` e o JS dispara `modal:user_profile-open`. Não acontece nada, sem erro.

### B9. `@interact` zera o `$loop` de quem chamou

`src/Support/Blade/Directives.php:44` emite `$loop = null;` no escopo do consumidor. O
closure atribui o próprio `$loop` local, então esse `null` só serve pra destruir o de fora.

```blade
@foreach ($groups as $group)
    <x-table :rows="$group->rows" :headers="$headers">
        @interact('column_action', $row) ... @endinteract
    </x-table>
    <p>{{ $loop->iteration }}</p>  {{-- null aqui --}}
@endforeach
```

E o `getLoopStack()[0]` pega o loop mais externo, não o atual, então dentro do `@interact`
o `$loop` reporta a iteração do consumidor.

### B10. `json()` sem checagem quebra o Alpine com UTF-8 inválido

`src/Support/Blade/Wireable.php:66-69`. `json_encode` devolve `false`, `base64_encode(false)`
vira `''`, e o browser recebe `JSON.parse(atob(''))`, que lança `SyntaxError` no init. O
select renderiza sem opção nenhuma e sem erro no servidor pra diagnosticar.

Correção: `json_encode($data, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE)`.

### B11. `tallstackui:ide` apaga o `ide.json` do projeto

`src/Console/IdeCommand.php:44-48,67`. O `ide.json` é config do plugin Laravel Idea do
projeto todo. O comando avisa mas não pergunta, e faz `File::delete` seguido de escrita só
com a lista do TallStackUI.

### B12. Editor deixa passar `javascript:` em `href`

Três caminhos, nenhum checa o esquema da URL:

`alpine.js:684`, o diálogo de link: `escapeHtml` só cobre `& < > " '`. Digitar
`javascript:alert(1)` no campo de URL gera href vivo. O `insertImage()` na `:729-731` tem
guarda (`/^(https?:\/\/|data:image\/|\/)/`), link não.

`alpine.js:592-598`, o `sanitize()`: a allowlist é por tag e atributo, e `config.php:297`
libera `'a' => ['href','target','rel']`. Nunca olha o valor.

`parse.js:29`: `parse('[click](javascript:alert%281%29)')` devolve
`<a href="javascript:alert%281%29">`.

O que segura isso: `.ai/components/editor.md:203-207` diz explicitamente que a sanitização
do editor é defesa em profundidade e que o app deve sanitizar no servidor antes de gravar
e antes de renderizar. Por isso é Baixo e não Alto. O que mantém como achado é o caminho
do diálogo, onde a lib fabrica o payload a partir de campo comum, e o fato de a imagem ter
guarda e o link não.

O resto do editor está certo, e eu verifiquei: `<img src=x onerror=...>` colado é
neutralizado (parse em `<template>` inerte + remoção de atributo fora da allowlist),
`parse.js` recusa HTML cru, `allowed_styles` não é burlável por shorthand, não tem bypass
por maiúscula, e os regex de `autoformat.js` não têm ReDoS (testei os quatro com entrada
patológica, todos abaixo de 5ms).

### B13. Link markdown com parêntese corrompe a cada round trip

`serialize.js:58` emite o href cru, `parse.js:29` lê com `([^)\s]+)`, que não atravessa `)`.

`parse('[wiki](https://en.wikipedia.org/wiki/Foo_(bar))')` devolve
`<a href="https://en.wikipedia.org/wiki/Foo_(bar">wiki</a>)`. Href truncado e `)` sobrando
no texto. Piora a cada salvar e reabrir.

### B14. Regex de link do editor é quadrático

`parse.js:29`. Medido no módulo real: 40KB em 303ms, 80KB em 1196ms, 160KB em 5054ms.
Quadrático limpo, 4x por dobra.

Alcançável pelo `handlePaste` e, o que importa mais, pelo `init` e pelo `$watch`, que
processam valor vindo do servidor. Num fluxo de moderação, conteúdo do usuário A aberto no
editor do usuário B trava a aba do B.

Correção: limitar a classe, tipo `[^)\s]{1,2048}`.

### B15. Carousel dispara evento com a imagem errada

`alpine.js:265` faz `image: this.images[this.current]`, mas `current` é 1-based (a blade usa
`current == index + 1`). O evento entrega a próxima imagem, e `undefined` na última. O
`expandNext()` na `:127` acerta (`this.images[next - 1]`).

### B16. `static` do key-value é ignorado quando tem `limit`

`alpine.js:89-96`. O quarto parâmetro se chama `addable` mas a blade passa `$static`. A
segunda cláusula do getter não consulta `static`. Com
`<x-key-value wire:model="items" static :limit="5" />` o botão de adicionar aparece e
clicar cria uma linha com os inputs `readonly`, que o usuário não consegue preencher.

### B17. Modal e slide não prendem o foco

`modal/main.blade.php:11` declara `role="dialog" aria-modal="true"`, mas não existe
`x-trap`, `inert` nem `aria-hidden` no pacote, e o plugin `@alpinejs/focus` não é importado.
Também não tem foco inicial nem devolução de foco ao gatilho.

Tab dentro de um modal aberto passeia pelo conteúdo de trás, que continua interativo por
teclado enquanto o backdrop o esconde. O Alpine embutido no Livewire já traz `x-trap`,
então `x-trap.noscroll.inert="show"` no painel resolve trap, `inert` e retorno de foco de
uma vez.

### B18. `sanitize()` quebra escalar que tem vírgula

`AbstractRuntime.php:180-189` divide qualquer valor com vírgula, sem opt-out.
`<x-currency value="1,234.56" />` vira `[1, '234.56']`. Sobrevive porque
`String([1,'234.56'])` volta `'1,234.56'` e o alpine tira o que não é dígito. Não achei
caminho onde isso dê erro duro, só valor com tipo errado que o resto tolera.

### B19. Command palette com assinatura permanente

`routes/web.php:12` + `CompileConfigurations.php:124`. O `signedRoute()` é sem expiração,
então a assinatura é a mesma pra todo mundo, nunca vence, e vai embutida no HTML de toda
página que renderiza o palette. O controller não checa autenticação e a rota é hardcoded
com `web`, sem knob de config pra somar `auth` ou `throttle`.

O impacto real depende inteiramente do que o dev implementa no `actionable`, que é onde a
autorização deveria estar. Registro porque não existe ponto de extensão pra apertar isso.

Junto: `TallStackUiCommandPaletteController.php:22-33` tipa `search` como `string` e
`additional` como `array`. POST assinado com `{"search":{"a":1}}` dá `TypeError` e HTTP 500
em vez de 422.

### B20. `max_size` do upload assíncrono não limita bytes gravados

`src/Http/AsyncUpload/AsyncUploadHandler.php:208-214`. A única checagem roda sobre
`total_size`, que é valor declarado pelo cliente. As `rules` só rodam no `finalize()`,
depois de tudo estar em disco. O `'chunk' => ['required','file']` não tem `max:`, e nada
compara o tamanho real do chunk com o `chunk_size`.

Dá pra declarar `total_size` pequeno e mandar até 20000 chunks grandes. Sem completar o
conjunto, o `finalize()` nunca roda e nada é apagado até o
`tallstackui:async-upload:clear` (padrão 6h).

O comentário do código diz "this is the check a raw request cannot skip", o que não é
verdade hoje. Precisa de 20 mil requisições e passa pelo callback `authorize` se o dev
definir um, então não é urgente, mas a garantia declarada não existe.

### B21. Traversal no asset só em Windows

`TallStackUiAssetsController.php:15-22` concatena `$file` sem `basename`.

Em POSIX não é explorável, e eu confirmo: o Laravel faz `rawurldecode` antes de casar a
rota, então `..%2F..%2F` vira `../../` e não casa o `[^/]++`. `..` sozinho não passa no
`is_file()`.

Em Windows `\` é separador e não é filtrado, e casa `[^/]++`. Marco como parcial porque não
consegui executar em Windows. `basename()` resolve e não custa nada.

### B22. `block('nome')` na forma curta não valida o nome

`src/Customization/CustomizationFactory.php:109-113` retorna antes do `composer()`, que é
quem valida. Então `->block('wrappper')->append('shadow-xl')` com typo não lança nada, e o
bloco fantasma é descartado depois em `ManagesClasses.php:71`. O usuário conclui que o
append não funciona.

Agrava porque o bloco fantasma entra no `$soft`, e aí dispara o B24.

### B23. Escopo com maiúscula não bate

`src/helpers.php:196-199` faz `strtolower($key)` pra montar a chave do container, mas
`Customization.php:686` guarda o escopo cru e `ManagesClasses.php:60` lê com a string crua
da blade. O lookup do container acerta, o `data_get` seguinte erra e cai no default, que é
o array inteiro. Resultado: o escopo vira no-op e toda customização global do componente é
descartada naquela instância.

### B24. `floating.default` é anulado por uma condição grosseira

`ManagesClasses.php:101-107` condiciona a `$soft === []`, ou seja, a qualquer bloco
customizado do componente pai, não só ao `floating.default`.

Customizar `floating.default` por escopo é descartado quando nada global foi customizado.
E customizar qualquer outra coisa do componente faz o `floating.default` parar de ser
anulado, e aí a customização global de Floating deixa de valer pra ele.

Correção: trocar `$soft === []` por `! array_key_exists('floating.default', $merge)`.

### B25. `$this->block` vaza entre cadeias no singleton

`CustomizationFactory.php:188-192`. A guarda "No block has been set" só protege a primeira
cadeia daquele componente. Depois disso `$this->block` sobrevive, então uma chamada que
esqueceu o `block()` não lança nada e escreve no bloco da chamada anterior.

### B26. Slide com guarda constante

`slide/main.blade.php:43,55,60` usa `!$configurations['top'] || !$configurations['bottom']`,
que só é falso se os dois forem verdadeiros, combinação que o `CompileConfigurations::slide()`
trata como impossível. Ou seja, `h-full` é aplicado sempre e concorre com o
`h-[32rem] sm:max-h-[20rem]` que o slide resolve pra `top` e `bottom`. O padrão sugere que
era pra ser `&&`. Parcial: a condição constante está provada, o efeito visual não.

### B27. Barra de filtro da Table some com array vazio

`table/main.blade.php:14` usa `count((array) $rows) > 0`. O cast em objeto conta
propriedade, não item: `count((array) collect([]))` é 2 e
`count((array) new LengthAwarePaginator(collect([]),0,10,1))` é 12, mas `count((array) [])`
é 0.

Com `:rows="[]"`, que é combinação usada nos próprios testes
(`Table/FeatureTest.php:368-390`), o input de busca desaparece e o usuário não consegue
limpar a busca que zerou o resultado. Com Collection ou Paginator ele fica.

Correção: `blank($rows)`.

### B28. Slot nomeado vazio no Stats renderiza wrapper vazio

`stats/main.blade.php:31,47,73,95` usam `@if ($slot)` e `:44-45` usam `! $header`.
`ComponentSlot` é sempre truthy.

`<x-stats number="33" title="Users"><x-slot:icon></x-slot></x-stats>` renderiza um
`<div class="... h-12 w-12 ...">` vazio ocupando 3rem, e as classes
`wrapper.second-no-header/no-footer` nunca são aplicadas.

O resto do código já usa a forma certa, `StatsRuntime.php:26` usa `filled()`.

### B29. `square` por item no Dial não faz nada

`dial/items.blade.php:5` tem `@aware([... 'square' => null])`, mas
`Dial/Items/Component.php:21-27` não declara a prop. `<x-dial.items icon="pencil" square />`
continua `rounded-full` e ainda emite um atributo solto `square="square"` no `<button>`.

### B30. `options` do CommandPalette é aceito e nunca renderizado

`CommandPalette/Component.php:28` declara `Collection|array $options` e o `SelectSetup`
valida, mas `command-palette/main.blade.php` não referencia `$options` em lugar nenhum.
A lista vem só do `request`.

### B31. `wire:key` das linhas da Table é derivado do conteúdo

`table/main.blade.php:131` faz `$id = md5(serialize($value).$key)`, e esse mesmo `$id` vai
pro `wire:key` (`:138`), pro `toggle()` (`:145`) e pro `wire:key` da sub-linha (`:184`).

Como a chave sai do conteúdo serializado mais o índice na página, ela muda sempre que o
dado da linha muda ou que as linhas reordenam. Expandir uma linha e depois qualquer
re-render que altere aquele dado (um toggle de status na coluna de ação, um `wire:poll`)
gera um `$id` novo, o `expanded($id)` fica falso e a sub-linha fecha sozinha, deixando o id
velho preso no estado do Alpine. O morph também troca o `<tr>` inteiro em vez de remendar
célula, então componente Livewire aninhado na coluna de ação remonta.

Isso é design antigo, não regressão do Livewire 4. Marco como parcial, a lógica está
traçada mas não reproduzida.

Correção seria uma chave de identidade opt-in, tipo `data_get($value, $selectableProperty)`,
caindo no hash atual quando não tiver.

### B32. Chaves menores

- `gallery.thumbnails` é lido em `CompileConfigurations.php:186` mas não existe em
  `src/config.php`. Cai no default `'bottom'` em silêncio, só não dá pra configurar global.
- 3 ícones internos fora do `icon.custom.guide`: `computer-desktop`, `ellipsis-vertical`,
  `document-arrow-down`. Não quebra, o `IconGuideMap` cai no nome cru. Só não são remapeáveis.
- `SetupPrefixCommand::env()` não valida o prefixo. Um valor com aspas gera
  `TALLSTACKUI_PREFIX="ts""` no `.env` e o phpdotenv passa a lançar em todo boot. É erro do
  operador, mas o `text()` do Prompts aceita `validate:` e custa quase nada.
- `select/styled.blade.php:115` tem `id="select-clear"` hardcoded. Ids duplicados com mais
  de um select na página. Nada referencia esse id, então não achei quebra.

---

# Empacotamento e CI

Boa notícia primeiro: o `dist/` está em dia. Isso foi provado por três caminhos
independentes, incluindo procurar no bundle um identificador introduzido no commit mais
recente (`tallstackui:floating-flush`, presente). E a sintaxe PHP 8.1 declarada é real, o
`src/` inteiro (1145 arquivos) foi parseado contra a gramática 8.1 sem falha nenhuma.

O que tem problema é o resto.

### P1. Quatro arquivos de teste vão parar no pacote do consumidor

`.gitattributes` usa nome exato:

```
src/**/FeatureTest.php export-ignore
src/**/BrowserTest.php export-ignore
```

Isso pega os 154 arquivos com nome canônico e deixa passar os 4 que não seguem o padrão.
Confirmei rodando `git archive HEAD`:

```
src/Components/Form/Autocomplete/SelectEventCollisionBrowserTest.php
src/Components/Form/Select/Styled/SelectStyledApiBrowserTest.php
src/Components/Form/Select/Styled/SelectStyledCommonBrowserTest.php
src/Components/Toast/StackedBrowserTest.php
```

São umas 2700 linhas, declarando 9 classes no namespace `TallStackUi\`, sendo 5
componentes Livewire de teste. Todas estendem `Tests\Browser\BrowserTestCase` ou
`Livewire\Component`, e `tests/` não é distribuído, então a classe pai não existe lá.

### P2. Os mesmos 4 arquivos entram no classmap otimizado

`composer.json` tem o mesmo padrão em `exclude-from-classmap`. O Composer compila `**` pra
`.+?`, então exige `/` logo antes de `BrowserTest.php`.

O agente reproduziu isso de verdade, montando um sandbox com o `composer.json` real e os
arquivos reais, e rodando `composer dump-autoload -o`:

```
'TallStackUi\Components\Form\Select\Styled\SelectStyledApiBrowserTest'    => ...
'TallStackUi\Components\Form\Select\Styled\SelectStyledCommonBrowserTest' => ...
'TallStackUi\Components\Toast\StackedBrowserTest'                         => ...
```

No mesmo teste, `Alert/FeatureTest.php` e `Alert/BrowserTest.php` foram excluídos certo.
Então o padrão funciona, só não pra esses 4.

Sendo preciso no impacto: o `dump-autoload -o` não falha, sem warning. As classes só ficam
registradas no classmap que todo deploy de produção gera. O fatal
`Class "Tests\Browser\BrowserTestCase" not found` só acontece se alguma coisa percorrer o
classmap, tipo ide-helper ou análise estática sobre `vendor/`. Defeito confirmado, fatal
parcial.

P1 e P2 têm a mesma raiz e a mesma correção de uma linha em cada arquivo:

```
src/**/*FeatureTest.php export-ignore
src/**/*BrowserTest.php export-ignore
```

Detalhe correlato: o `phpstan.neon` já lista
`src/Components/**/StackedBrowserTest.php` e `SearchableBrowserTest.php` no `excludePaths`.
Ou seja, o problema dos nomes fora do padrão já foi encontrado uma vez e corrigido só no
PHPStan, não no empacotamento. E o `SearchableBrowserTest.php` nem existe mais, é exclude
morto.

### P3. PHPStan não roda no CI

Confirmei: `grep -riE "phpstan|analyse" .github/workflows/` não retorna nada.

O `composer ci` tem 7 passos e começa com `composer run analyse`. O workflow roda 6, e o
que falta é justamente o primeiro. Os steps do `tests.yml` são Pint, Unused Customization
Blocks, HTML-Escaped Tailwind Variants, Type Coverage, Clear Cache, Feature e Browser.

Regressão de PHPStan entra no merge sem sinal nenhum. Hoje está limpo, eu rodei.

### P4. O CI reconstrói o `dist/` e joga fora

Os dois jobs rodam `npm run build`, mas ninguém compara com o `dist/` commitado. Como o
pacote entrega asset pré-buildado, `dist/` defasado é invisível pro CI. Justamente o maior
risco desse repositório.

Hoje está em dia, então é lacuna de processo, não defeito ativo. `git diff --exit-code -- dist/`
depois do build resolve.

### P5. A matriz testa 1 de umas 7 combinações declaradas

`composer.json` declara `php: ^8.1` e `illuminate/*: ^10|^11|^12|^13`. O CI roda só
`php: [8.4]` × `laravel: ['13.*']`.

Mas o mínimo declarado não está contradito, e isso foi checado a sério: parse dos 1145
arquivos contra a gramática 8.1 sem falha, e grep dirigido pra `readonly class`,
`#[\Override]`, `json_validate(`, tipos DNF, constante de classe tipada, `private(set)`,
`array_find`, `mb_trim`, tudo zero. O único uso pós-Laravel-10 é
`Illuminate\Support\Number::fileSize` em
`src/Support/Miscellaneous/UploadComponentFileAdapter.php:55`, e está guardado por
`class_exists` na linha 27.

Então é lacuna de verificação, sem quebra encontrada. Um job extra com
`php: 8.1 / laravel: 10.*` já transforma suposição em fato. Não vale expandir a matriz toda.

### P6. Menores

- `pr-1329-review.md` está em `HEAD` e é distribuído. Está marcado como deletado na working
  tree mas ainda não commitado.
- `.github/workflows/tests.yml:49-51` no job `checks` faz `rm -f package-lock.json` antes do
  `npm install`. O lockfile commitado nunca é validado nesse job (o job `browser` usa).
  Hoje está em sincronia, mas os dois jobs podem resolver árvores diferentes.
- Prettier no CI só checa `js/**/*.js`. O `npm run format` local cobre também
  `src/Components/**/*.js`, que são 60 arquivos Alpine nunca verificados. ESLint cobre os dois.

---

# Testes

A suíte é mais saudável do que a razão componente/arquivo sugere. Boa parte das validações
está assertada nos `BrowserTest.php`, não nos `FeatureTest.php`. Minha premissa inicial de
"29 componentes sem teste" estava errada, e o agente me corrigiu: a maioria tem teste no
diretório pai ou validação assertada no browser test.

Zero teste desabilitado. Zero `->skip(`, `->todo(`, `->only(`, `->repeat(`. Zero teste sem
assertion. Isso é raro e vale registrar.

O problema é outro.

## T1. `expectException(ViewException::class)` sem mensagem

O Blade embrulha tudo que estoura numa view em `ViewException`. Então esses testes passam
com typo, `TypeError`, componente renomeado, ou com outra regra de validação disparando.

A prova de que é descuido e não convenção está em dois arquivos com o mesmo nome de teste:

`src/Components/Modal/FeatureTest.php:177`
```php
it('can thrown exception when z-index does not contains prefix', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Modal: The [z-index] must start with z- prefix');
```

`src/Components/Slide/FeatureTest.php:160`
```php
it('can thrown exception when z-index does not contains prefix', function () {
    $this->expectException(ViewException::class);
    // sem mensagem, e com um ->toContain('Bar Baz') morto depois do throw
```

Mesmo nome, mesma entrada, mesma regra copiada de `Modal/Component.php:113` pra
`Slide/Component.php:115`.

No repositório inteiro: 137 ocorrências de `expectException(ViewException::class)` contra
85 de `expectExceptionMessage`. Gallery é o caso mais agudo, `Component.php:138-201` tem
15 regras mutuamente alcançáveis e os 15 testes só distinguem por `ViewException::class`.

Correção: acrescentar `expectExceptionMessage`. A string literal já existe no componente.

## T2. Um teste valida uma regra que nunca é alcançada

`src/Components/Form/Color/FeatureTest.php:53`

```php
it('cannot render with invalid excluded step', function () {
    $this->expectException(ViewException::class);
    expect('<x-color excluded-step="999" />')->render();
});
```

Sem `picker`. Confirmei em `Form/Color/Component.php:103`: o
`! $this->picker && $this->excludedStep` dispara primeiro e lança "can only be used with
[picker]". A allowlist de steps, que está mais embaixo e é o que o nome do teste promete,
nunca é alcançada.

Ou seja, esse teste é duplicata comportamental do da linha 41. Dá pra apagar a validação de
allowlist de step inteira e os dois continuam verdes.

Correção: `<x-color picker excluded-step="999" />` mais a mensagem.

## T3. Assertion satisfeita por markup estático

`src/Components/Form/Pin/FeatureTest.php:26`

```php
it('can render with smart')->expect('<x-pin length="4" smart />')->render()->toContain('true');
```

`pin.blade.php:28` emite `x-on:paste="pasting = true; paste($event)"` sempre. O
`toContain('true')` passa com `<x-pin length="4" />` sem `smart` nenhum. A flag não é
verificada por nada.

## T4. Parâmetro de dataset que não chega em assertion nenhuma

Seis testes, umas 90 iterações, que só assertam o rótulo ecoado.

| arquivo:linha | dataset | assere |
|---|---|---|
| `BackToTop/FeatureTest.php:87` | 25 cores | `toContain('tallstackui_backToTop')`, o `x-data` da raiz, idêntico ao smoke da linha 8 |
| `Banner/FeatureTest.php:35` | 25 cores | `toContain('Foo bar')` |
| `Stats/FeatureTest.php:174` | 25 cores | `toContain('333')` |
| `Banner/FeatureTest.php:26` | sm/md/lg | `toContain('Foo bar')` |
| `Form/Range/FeatureTest.php:25` | sm/md/lg | `'<input'`, `'Bar baz'` |
| `Tooltip/FeatureTest.php:27` | 4 posições | `toContain('Foo bar')` |

O padrão certo já é a casa: `Progress/FeatureTest.php:33`, `Link/FeatureTest.php:80`,
`Button/Normal/FeatureTest.php:120` e `Dropdown/FeatureTest.php:30` todos derivam a classe
esperada do parâmetro (`match` virando `"bg-$colors-600"`) e assertam.

## T5. Teste de stub que não testa stub

`tests/Feature/Structure/StubTests.php:11`

```php
$content .= 'Colors(Component $component): array';
expect($content)->toContain($method);
```

O `$content` é capturado por valor no closure. Essa concatenação muta uma cópia que é
descartada. A intenção era assertar que o stub declara
`backgroundColors(Component $component): array`. Como está, o teste degrada pra "o texto
do stub contém a substring `background`", que passa até se `background` aparecer só como
chave de array em outro método.

Isso importa porque é a única guarda aparente sobre o caminho de cores publicadas, e
`SetupColors::setup()` faz `if (! method_exists($class, $method)) continue;`, ou seja,
volta pro palette interno em silêncio se o stub publicado divergir.

## T6. Assertions inalcançáveis depois de um throw esperado

Seis testes com cadeia depois do `->render()` que nunca executa:
`Modal/FeatureTest.php:51` e `:169`, `Slide/FeatureTest.php:150` e `:169`,
`Tooltip/FeatureTest.php:42`, `Dropdown/FeatureTest.php:162`. Inofensivo, mas anuncia
verificação que não acontece.

## T7. Onde falta cobertura de verdade

Não é "falta teste no arquivo X". É comportamento desprotegido com regressão plausível.

**Alert `rounded` / `square` / `bordered`.** O commit `6ea7cbf4` mexeu em 5 arquivos e
zero testes. `grep -c 'bordered\|rounded\|square'` nos dois arquivos de teste do Alert dá 0.
São três peças móveis: o parse de `bordered="left:red"` com fallback pra `$this->color`,
a resolução em `AlertColors` e a composição na view. Se o fallback ou o `data_get` voltar
`null`, a view só descarta a classe, sem exceção. `<x-alert bordered="left" color="red">`
sai com `border-l-4` transparente. É o único commit de feature recente que entrou sem
tocar em teste, e Alert é dos componentes mais usados.

**Table `selectable` sem `selectableProperty`.** `Table/Component.php:296` e `:300`. Se a
regra parar de disparar, a blade lê uma chave nula por linha, e toda linha resolve pra
mesma identidade. Selecionar uma seleciona todas, ou o array enche de null. É a única
regra não assertada cuja falha corrompe dado em vez de pixel, num fluxo de ação em massa.

**`Form/Time`.** Seis regras de `validate()`, zero assertadas. Maior razão regra/assertion
da lib, num componente sem `FeatureTest` nenhum.

**`Form/InputSelect`.** Único componente real sem arquivo de teste próprio em lugar nenhum.
O `InputSelectRuntime.php:19-24` lê slots `left`/`right` que não são props declaradas, via
`$this->data('left')`, e lança quando os dois faltam. Esse mecanismo (`slotStack`) já foi
retrabalhado uma vez pela issue #1276. Se quebrar pra um lado, todo `<x-input.select>`
estoura; pro outro, a guarda nunca dispara. Os 24 browser tests sempre passam slot válido,
então nenhuma direção é pega.

---

# Verificado e sem problema

Registro pra ninguém repetir o trabalho.

**Registros e consistência.** 88 componentes registrados, 88 no disco, 88 estendendo
`TallStackUiComponent`, zero classe faltando, zero componente não registrado. Todos os 88
`blade()` resolvem pra arquivo existente. Zero view órfã. 86 nomes de `#[SoftCustomization]`,
todos únicos, todos com método fluente correspondente. 54 refs de `PassThroughRuntime` pra
50 classes, todas existem, zero órfã. 32 refs de `ColorsThroughOf` pra 29 classes, idem.

**Config.** Não tem bug de namespace. A chave interna é `ts-ui`, a publicada é
`tallstackui`, e a `TallStackUiServiceProvider.php:117-119` reconcilia de propósito. Todas
as leituras `config('ts-ui.*')` do `src/` e `routes/` existem no `src/config.php`.
`config:cache` funciona, o `getFreshConfiguration()` bootstrapa antes de serializar.

**Mapas de tamanho e posição.** Modal com 11 tamanhos validados pra 11 no mapa. Slide igual,
11 × 4 posições. Modal `center` 5/5, `scrollbar` 2/2, blur 4/4 em Modal, Slide e
CommandPalette. Loading 4/4, Dialog 4/4, Toast position 6/6, Select 2/2. Nenhum valor
aceito e não mapeado.

**Traduções.** 15 locales × 140 chaves, conjuntos idênticos byte a byte. Zero chave faltando,
zero sobrando, zero chave usada no código e ausente do `en`.

**Invocação remota de método pelo dialog.** Não é vulnerabilidade, e eu tracei o caminho
todo. O `component.call(method, params)` vai pro endpoint do Livewire, e o
`HandleComponents.php:698-705` só permite método público declarado na subclasse do usuário,
com `render` removido. Um atacante editando o payload no DOM não ganha nada além do que já
teria montando a requisição na mão. O snapshot tem checksum. Sem bypass.

**Hooks das interactions.** Closure é executada no servidor, no momento do registro
(`DispatchInteraction.php:58`), e só o retorno atravessa. Nenhuma closure é serializada.

**XSS no editor por paste.** `<img src=x onerror=...>` colado é neutralizado. O `sanitize()`
faz parse num `<template>` inerte, sem fetch e sem registrar handler, e remove todo atributo
fora da allowlist. Todo paste rico passa por ali. Não achei bypass. O `parse.js` recusa HTML
cru. `allowed_styles` não é burlável por shorthand, o CSSOM já expandiu. Sem bypass por
maiúscula.

**Traversal nas rotas de asset em POSIX.** Não é explorável. O Laravel decodifica antes de
casar a rota, então `%2F` vira `/` e falha o `[^/]++`.

**Instanciação arbitrária no command palette.** Não existe. O `actionable` vem da config,
não do input, e passa por `class_exists` e `method_exists`.

**`x-html` no toast, dialog e banner.** É deliberado, commit `57c0def4`, "changing from
x-text to x-html to support html in interactions". Vale uma nota na doc de que
`toast()->error($title, $desc)` renderiza markup, mas não é sink escondido. Os botões usam
`x-text` corretamente.

**`{!! $entangle !!}`** nos 16 templates: seguro, o `Wireable::entangle()` monta a string a
partir do `wire:model` que o próprio desenvolvedor escreveu.

**`{!! trans(...) !!}`** nos paginadores: são arquivos de lang do app, mesmo padrão das
views de paginação do Laravel.

**`x-on:click="{!! $scroll !!}"`** nos paginadores: o `TableRuntime.php:111` usa
`Js::from($persistent)`, e já tem teste de regressão em `Table/BrowserTest.php:16`.

**`@js()` em todo lugar.** É `Illuminate\Support\Js::from()`, escape correto pra contexto
de atributo.

**`unique()`** em `js/helpers.js:185`: usa `crypto.getRandomValues`, não `Math.random`.
Uns 56 bits. Disponível em contexto inseguro também (só o `crypto.subtle` é restrito), então
dev server em `http://` funciona.

**Scroll-lock entre overlays contáveis.** Tracei modal→slide, slide→modal, modal→dialog,
dialog→modal, modais empilhados e floating↔modal nas duas ordens. O gate de ownership mais
o `holders()` mais o contador `siblings <= 1` funcionam, porque todo caller solta o lock
antes de desregistrar. O caso especial do `'floating'` é justamente o que evita deadlock.

**Tooltip.** Não usa tippy.js. Balão único global com `observer.disconnect()`,
`clearTimeout`, `cancelAnimationFrame`, `release()` no `hide()` e limpeza no
`livewire:navigating`. Sem vazamento.

**Chart.** Sem `eval`, sem `new Function`, sem `JSON.parse` de atributo. Todo caminho
degenerado tem guarda: menos de 2 pontos, range zero, total zero, dados vazios, esconder a
última série.

**Clipboard.** API assíncrona só quando existe, com fallback pro `execCommand` em origem
insegura e em qualquer rejeição. O valor copiado vem do estado, não do DOM.

**Table, seleção.** Select-all é por página nas duas direções, e seleção de outras páginas
sobrevive ao re-render. O `data-ids='@json($ids())'` é seguro, o `@json` do Laravel já usa
`JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_TAG|JSON_HEX_AMP`.

**Aritmética.** Rating, Progress (bar e circle), Carousel, Gallery, Table `onEachSide`,
ChartRuntime, Pin, Range, Step. Nenhum `DivisionByZeroError` nem loop infinito alcançável.

**`match` sem default.** Os 14 `match` das classes de componente têm default. Nenhum
`UnhandledMatchError` possível.

**Props lidas na view e não declaradas.** Varri as 88 classes contra as views. Todo "órfão"
é chave injetada pelo runtime, mágica do Alpine, `@aware`, ou slot guardado por `isset()`.
Zero variável indefinida.

**Error bag.** A busca funciona pra chave aninhada (`form.name`) e de array (`items.0.qty`).
O `UploadRuntime.php:22-23` usa a forma curinga `$property.'.*'` certa.

**`remove()` da customização.** É por token desde `:263-269`. `remove('border')` não toca
`border-gray-300`, `remove('p-4')` não toca `p-40`.

**`append()` e `prepend()`.** O `compile():217` normaliza espaço. Nunca gera `" foo"` nem `"ab"`.

**Blocos referenciados na blade que não existem.** Zero. Varredura de todos os
`$customization['...']` contra as chaves reais por reflection.

**`dist/` está fresco.** Provado por três vias: mesmo commit que as fontes, `git status`
limpo, e busca por identificador novo no bundle. Além disso o agente extraiu 284 literais
de string dos 69 arquivos JS-fonte e conferiu presença nos bundles, zero miss real (os 33
aparentes eram todos especificador de import ou texto dentro de comentário). O CSS idem, e
as 9 entradas do manifest apontam pra arquivo existente.

**PHP 8.1 declarado é real.** 1145 arquivos parseados contra a gramática 8.1 com
nikic/php-parser v5, zero falha. Nenhum uso de sintaxe 8.2+.

**Dependências JS.** Zero import não declarado, nada dependendo de hoisting. `qs` e
`clipboard` foram removidos corretamente e não sobrou import. `@tailwindcss/vite` só aparece
no `vite.config.mjs`, o que é legítimo.

**Plugins do dayjs.** Consistente. Carrega `updateLocale` e `isBetween`, os dois com
`extend()` nos arquivos que importam. Todos os `.format()` usam só token do core. Nenhuma
chamada de dois argumentos, então `customParseFormat` não é necessário.

**Sharding do CI.** Íntegro, `shard: [1,2,3,4]` com `--shard=${{ matrix.shard }}/4`, nenhum
omitido. Nenhum `continue-on-error` nem `if: always` em lugar nenhum dos workflows. Os 4
arquivos de nome fora do padrão são coletados pelo `phpunit.xml`, que usa sufixo simples.

**Entangle no Livewire 4.** A sintaxe `$wire.entangle('x')` e `.live` é válida na v4
(`dist/livewire.esm.js:9852-9888`). O entangle deferido chega no servidor de verdade, o
`getUpdates()` faz diff de `canonical` contra `ephemeral`. Não é descartado em silêncio.

**Qual componente Livewire resolve em aninhamento.** Correto. O `$shared['__livewire']` tem
escopo de push e pop por render, o mais interno ganha.

**`invade($factory)->slotStack`.** O `protected $slotStack` existe em
`ManagesComponents.php:45` e não mudou no Laravel 13. É interno, mas está guardado com
`?? []` e não está quebrado.

**SQL injection na Table.** Não existe. A coluna de ordenação vem sempre dos `headers`
definidos pelo desenvolvedor, e a biblioteca nunca monta query.

---

# Por onde eu começaria

## O que já foi feito (rodada 1, branch `fixes-4x`)

20 itens corrigidos com teste de regressão: A5, A6, A7, A8, A9, A10, A11, A12, A13, A14,
A15, A16, A17, A18, A19, M1, M2, M3, M4, M5. A1 foi descartado por decisão de produto.

Estado das ferramentas depois da rodada: PHPStan 0 erros, Pint limpo, type coverage 100%,
ESLint e Prettier limpos, `find-unused-customization-blocks.php` passando (agora de
verdade, ver A19), 2198 testes de feature passando. Os testes de navegador dos componentes
tocados foram rodados por filtro: Floating, Modal, Slide, Toast, Signature, Time, Currency,
Select, Loading, Timeline, Table, Banner, Checkbox, Radio e Dropdown, todos verdes. A
suíte completa de navegador fica para o CI.

## O que eu faria em seguida

1. **A2**, o `wire:model` com ponto. Quebra Livewire Form object, que é padrão comum no
   Livewire 4, e é uma linha em cada um dos dois métodos. É o maior dos altos que sobrou.
2. **A3** e **A4**, o id duplicado no radio. Clicar no label marcando a opção errada é o
   tipo de coisa que chega como "o radio não funciona". Os dois têm a mesma raiz.
3. **P1** e **P2**, o `*` faltando nos globs. Uma linha no `.gitattributes` e uma no
   `composer.json`, e resolve o vazamento de teste pro consumidor e o classmap. Vale
   fazer logo, porque esta rodada acrescentou testes justamente em dois dos quatro
   arquivos de nome fora do padrão.
4. **P3**: colocar o PHPStan no workflow. Ele está no `composer ci` e não está no CI, então
   a rede de proteção que você acha que tem, não tem.
5. **P4**: `git diff --exit-code -- dist/` depois do build no CI. Como esta rodada mexeu
   bastante em JS, um `dist/` defasado passa a ser um risco mais concreto do que era.
