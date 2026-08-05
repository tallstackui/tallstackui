# Revisão do PR #1337 — QrCode Component

PR: <https://github.com/tallstackui/tallstackui/pull/1337>  
Base: `4.x` (`3be6ec958ca5b4fdc7cdccc34da2e9f637cd6a9d`)  
Head revisado: `690a93211fbef742818f757aaec8e20fed0c62f5`

## Conclusão

**Solicitar alterações antes do merge.** O encoder básico está consistente, a tabela de blocos coincide com uma implementação independente e toda a suíte focada passa. Ainda assim, há três falhas em fluxos públicos do novo componente: o QR padrão perde compatibilidade no tema escuro e em exportações sobre fundo escuro; alguns captions permitidos produzem símbolos que leitores independentes não decodificam; e o fallback de caption lança exceção quando o projeto usa ícones SVG locais/customizados.

Nenhum achado é crítico ou emergencial: não há perda de dados, exposição ou indisponibilidade ampla. Os dois primeiros são **altos** porque atingem a função central do componente — produzir um QR escaneável — em combinações aceitas pela API. Como o componente ainda está entrando agora, a urgência proporcional é corrigi-los antes do merge, em vez de publicar as limitações como comportamento normal.

## Spec

O PR não possui descrição nem issue associada. O contrato verificável usado nesta revisão foi o corpo do commit `30018d1c`, a seção adicionada em [`docs.md`](docs.md#L4098) e a documentação pública em [`.ai/components/qr-code.md`](.ai/components/qr-code.md#L1).

### 1. Alto — o QR padrão não preserva fundo claro nem a quiet zone nos fluxos suportados

**Existência:** confirmada.  
**Gravidade:** alta.  
**Urgência:** corrigir antes do merge.  
**Confiança:** alta.

**Evidência.** A cor padrão é `text-gray-900` no tema claro, mas muda para `dark:text-white` no escuro ([`QrCodeColors.php:21`](src/Support/Colors/Components/QrCodeColors.php#L21)). O SVG desenha apenas os módulos escuros, sem um retângulo claro de fundo ([`main.blade.php:7`](src/resources/views/components/qr-code/main.blade.php#L7)). A exportação clona esse SVG e o canvas mantém as áreas restantes transparentes ([`alpine.js:61`](src/Components/QrCode/alpine.js#L61), [`alpine.js:91`](src/Components/QrCode/alpine.js#L91)). A própria documentação confirma tanto a inversão no tema escuro quanto o PNG sem fundo ([`docs.md:4131`](docs.md#L4131), [`docs.md:4151`](docs.md#L4151)).

Na reprodução independente, o mesmo QR v1-M foi decodificado normalmente em preto sobre branco. Em branco sobre preto, o `QRCodeDetector` do OpenCV 5.0.0 não o decodificou; o ZXing-C++ 3.1.1 também falhou com a tentativa de inversão desativada e só passou quando `try_invert` foi habilitado. Isso confirma a incompatibilidade descrita no próprio PR: leitores que esperam módulos escuros e quiet zone clara deixam de funcionar.

**Condições e impacto.** O problema ocorre por padrão quando a aplicação ativa o tema escuro e não passa `color`. O PNG copiado/baixado também depende da superfície em que a transparência será composta: um QR escuro pode desaparecer ou perder a quiet zone sobre fundo escuro. Usuários de leitores com inversão automática permanecem protegidos; passar uma cor escura e garantir externamente uma superfície branca contorna a exibição, mas não torna o arquivo autossuficiente.

**Ação recomendada.** O símbolo deve carregar seu próprio fundo claro, incluindo a quiet zone, tanto no SVG exibido quanto no serializado. Os módulos devem permanecer escuros por padrão, independentemente do tema. Se inversão continuar desejável, ela deve ser uma opção explícita com a limitação de compatibilidade clara, não o default.

### 2. Alto — captions aceitos podem produzir QR indecodificável

**Existência:** confirmada.  
**Gravidade:** alta.  
**Urgência:** corrigir antes do merge.  
**Confiança:** alta para as combinações reproduzidas; a extensão para todas as versões ainda precisa ser medida.

**Evidência.** A API aceita captions de até oito caracteres ([`Component.php:110`](src/Components/QrCode/Component.php#L110)). Com watermark, o Runtime troca para nível H e remove toda a caixa calculada ([`QrCodeRuntime.php:37`](src/Support/Runtime/Components/QrCodeRuntime.php#L37), [`Path.php:23`](src/Support/QrCode/Path.php#L23)). A largura dessa caixa cresce com `altura × quantidade de caracteres × 0,75`, limitada apenas pelas margens externas ([`Watermark.php:38`](src/Support/QrCode/Watermark.php#L38), [`Watermark.php:76`](src/Support/QrCode/Watermark.php#L76)). Isso também pode apagar alignment patterns, que não são protegidos por Reed-Solomon.

Reprodução concreta:

```blade
<x-qr-code
    :link="'https://a.co?q=' . str_repeat('a', 141)"
    watermark="ABCDEFGH"
/>
```

O link tem 156 bytes e seleciona v13-H. Renderizei o path e o `<text>` produzidos pelo PR em 1400×1400, sobre branco. OpenCV 5.0.0 e ZXing-C++ 3.1.1 falharam ao decodificar; o mesmo payload sem watermark foi decodificado pelos dois. O resultado também se repetiu em v21-H com um link de 383 bytes e o mesmo caption. Como controle, v14-H com o mesmo caption passou, portanto a falha depende da combinação versão/matriz/knockout e não significa que todo watermark esteja quebrado.

**Condições e impacto.** URLs de aproximadamente 156 bytes são plausíveis em links com tracking. A API aceita o link e o caption sem aviso, mas o QR entregue pode não cumprir sua única função. O nível H e o afastamento das linhas 6/8 são mitigações reais, porém insuficientes para todas as matrizes suportadas. Os testes atuais verificam propriedades internas e a remoção de módulos, mas nenhum leitor independente ([`QrCodeTest.php:210`](tests/Feature/Support/QrCodeTest.php#L210)).

**Ação recomendada.** Preservar todos os function patterns, limitar a área removida com base na versão e no orçamento real de dano e validar uma grade representativa de versões, payloads, masks e captions com pelo menos um decoder independente. O limite não deve depender apenas da quantidade de caracteres. A documentação não deve afirmar que H, sozinho, “paga” pela remoção até essa validação passar.

### 3. Médio — o fallback para texto quebra com ícones SVG locais/customizados

**Existência:** confirmada.  
**Gravidade:** média.  
**Urgência:** corrigir antes do merge ou, no máximo, antes do lançamento de 4.x.  
**Confiança:** alta.

**Evidência.** O contrato diz que um valor apoiado por uma view de ícone existente vira ícone e qualquer outro vira `<text>` ([`docs.md:4139`](docs.md#L4139)). `iconic()` consulta `View::exists()` apenas para Heroicons; para qualquer tipo configurado diferente, retorna `true` sem verificar o alvo ([`Component.php:82`](src/Components/QrCode/Component.php#L82)). Ícones SVG locais são uma configuração explicitamente suportada ([`src/config.php:334`](src/config.php#L334)).

Configurei o tipo como `views/components/svg` e renderizei `watermark="foo-bar"` sem criar `svg.foo-bar`. Em vez do caption, o render terminou com `ViewException: Unable to locate a class or view for component [svg.foo-bar]`, a partir do componente dinâmico em [`main.blade.php:15`](src/resources/views/components/qr-code/main.blade.php#L15).

**Condições e impacto.** Afeta projetos que usam o backend local/BladeUI e fornecem como caption um texto minúsculo/hifenizado que não existe como ícone. Heroicons já possui a proteção correta; captions com maiúsculas não casam com a regex e também degradam para texto.

**Ação recomendada.** Verificar a existência no resolvedor de cada backend antes de classificar o watermark como ícone, ou tornar a escolha ícone/caption explícita. Um alvo inexistente deve degradar para `<text>`, conforme o contrato.

### Scope creep não funcional

O commit `8f3bb0c4` reformata tabelas em sete documentos `.ai` sem relação com QrCode, por exemplo [`.ai/components/form/autocomplete.md`](.ai/components/form/autocomplete.md#L211). Não é defeito funcional, mas retirar esse ruído reduziria o diff e o risco de conflito.

## Standards

Não encontrei violação dura relevante dos standards documentados. A mudança segue a arquitetura Runtime/Alpine/Blade, mantém um único bloco `@php` nos templates e passa nas ferramentas de estilo e análise estática.

Como recomendações baixas, sem motivo para bloquear isoladamente:

- **Possível Duplicated Code / Primitive Obsession:** a política `watermark !== null ? 'H' : 'M'` aparece em [`Component.php:131`](src/Components/QrCode/Component.php#L131) e [`QrCodeRuntime.php:39`](src/Support/Runtime/Components/QrCodeRuntime.php#L39). Centralizar essa escolha evita divergência futura entre limite validado e nível realmente codificado.
- **Possível Mysterious Name:** `ICON`, `WATERMARK` e `iconic()` em [`Component.php:28`](src/Components/QrCode/Component.php#L28) significam, respectivamente, padrão de nome de ícone, limite textual e resolução do watermark. Nomes como `ICON_NAME_PATTERN`, `MAX_WATERMARK_TEXT_LENGTH` e `watermarkUsesIcon()` seriam mais precisos. É apenas legibilidade, sem impacto funcional demonstrado.

## Validações executadas

| Validação | Resultado |
|---|---|
| Testes focados de encoder + componente | 45 passaram, 619 assertions |
| BrowserTest focado de QrCode | 6 passaram, 13 assertions |
| PHPStan (`composer analyse`) | passou, 0 erros |
| Pint (`pint --test --parallel`) | passou |
| Type coverage (`composer type`) | passou, 100% |
| ESLint (`npm run lint`) | passou |
| Tabela RS contra `python-qrcode` | 0 divergências em 40 versões × 4 níveis |
| Decodificação externa | falhas reproduzidas em OpenCV 5.0.0 e ZXing-C++ 3.1.1 nos casos descritos |
| GitHub Actions do PR | 5 checks passaram (Laravel + 4 shards de browser) |

O CI verde não contradiz os achados: ele não tenta decodificar o resultado com um leitor independente, não testa fundo escuro/sem fundo e não cobre o fallback de watermark sob configuração local de ícones.

**Resumo por eixo:** Standards — 0 violações duras e 2 recomendações baixas; Spec — 3 achados acionáveis (2 altos, 1 médio), sendo o pior a entrega de símbolos aceitos pela API que leitores comuns não conseguem decodificar.
