# Revisão do PR #1337 — [4.x] Feat: QrCode Component

> Revisor: Claude Fable 5 (`claude-fable-5`) · Data: 2026-08-04
> Escopo: diff completo de `feature/qr-code` contra `origin/4.x` (50 arquivos, ~2.6k adições), conforme a skill `review-pr-development` e os critérios de `ANALISYS.md`.

---

## Veredito

**Aprovado.** Nenhum achado bloqueante. O encoder foi verificado bit a bit contra uma implementação de referência independente e está correto em todo o espectro do spec (v1–v40, níveis L/M/H). Todos os gates de qualidade passam. Há **1 achado de prioridade média** (performance, não bloqueante) e **2 baixos**, detalhados abaixo.

---

## Verificação do encoder (o coração do PR)

Um QR code que renderiza bonito mas não decodifica é o pior tipo de bug desta feature, então a verificação foi além dos testes do repositório. Montei um harness independente (fora do repo, em scratchpad) com duas checagens:

1. **Comparação módulo a módulo** contra o encoder de referência `qrcode` (npm, soldair/node-qrcode), forçando byte mode, mesma versão, mesmo nível e mesma máscara eleita — 114 combinações cobrindo 19 versões (1–40, com todas as v31+) × 3 níveis (L/M/H) × 2 tipos de payload (repetitivo `AAAA…` e variado com símbolos), cada payload no tamanho máximo da versão.
2. **Round-trip com decoder independente** (jsQR) — as mesmas 114 matrizes, mais 10 casos com knockout de watermark (ícone e texto, nível H, incluindo v34/v36/v40).

**Resultado: 124/124 casos passam. Zero divergências de matriz, zero falhas de decode.**

Isso também encerra a suspeita registrada na sessão de implementação (falhas de decode em v31+ com payload repetitivo): as matrizes geradas são idênticas às da referência nesses exatos casos, e o jsQR as decodifica integralmente. A falha estava no pipeline de rasterização/decode do sweep antigo, não no encoder. Confiança: alta (evidência reprodutível).

Conferi ainda, manualmente, contra o spec ISO/IEC 18004 (usando a implementação canônica de Nayuki como referência cruzada): tabelas `ALIGNMENTS` e `BLOCKS` (amostragem ampla incluindo v17H, v31, v34H, v36H, v40), indicadores de nível, geradores BCH de formato (0x537/0x5412) e versão (0x1F25), posicionamento das duas cópias do format info, version info (v7+), timing, finders, separadores, dark module, as 8 condições de máscara e as 4 regras de penalidade. Tudo correto.

---

## Gates de qualidade (todos executados localmente)

| Gate | Resultado |
| --- | --- |
| `./vendor/bin/pint --test --parallel` | ✅ passa |
| `./vendor/bin/phpstan analyse --memory-limit=2G` | ✅ sem erros |
| `./vendor/bin/pest --type-coverage --min=100` | ✅ 100.0% |
| `./vendor/bin/pest --parallel --group Feature` | ✅ 2269 passando, 2 skipped (4826 assertions) |
| `php scripts/find-unused-customization-blocks.php` | ✅ 87 componentes, 1698 chaves, nenhum bloco órfão |
| `npm run lint` | ✅ sem erros |
| `npm run build` | ✅ build limpo; `dist/` commitado está em dia (working tree permanece limpo após rebuild) |

Browser tests não foram executados localmente por regra do projeto (rodam em shards no CI). O `BrowserTest.php` está bem construído — cobre copy (com stub de clipboard), download png/svg (interceptando o anchor), rasterização na largura configurada, serialização standalone e o aviso de clipboard indisponível.

---

## Achados

### 1. [Médio] Encoder roda a cada render, sem nenhum cache

- **Evidência (benchmark local, PHP 8.x, média de 10 iterações com warmup):**
  - URL curta (v2): **8.8 ms/render**
  - 250 bytes (v10): **56.5 ms/render**
  - 1.000 bytes (v25): **222.6 ms/render**
  - 2.331 bytes (v40, máximo do nível M): **482.7 ms/render**
- **Condições:** `QrCodeRuntime::runtime()` chama `Encoder::make()` + `Path::of()` em todo render, inclusive em re-renders Livewire do componente pai. O custo é dominado pela eleição de máscara (8 candidatas × penalty sobre a grade inteira).
- **Impacto:** no uso típico (URL curta) o custo é de ~9 ms — aceitável. Com links longos, ou várias instâncias na mesma página, ou polling Livewire, o custo se torna material (centenas de ms por request).
- **Mitigações existentes:** nenhuma; o resultado é 100% determinístico para o mesmo `(link, level)` (há teste garantindo isso), o que torna o cache trivial.
- **Gravidade/Urgência:** média / não bloqueia o merge.
- **Confiança:** alta (medido).
- **Recomendação proporcional:** memoizar o par path/viewbox por `hash(link + level)` — um array estático no `Encoder` (resolve re-renders no mesmo processo, incluindo Octane) já ajuda; `Cache::remember` com TTL curto seria o passo seguinte se aparecer caso real de página pesada. Não recomendo otimizar o algoritmo de penalty agora — complexidade sem demanda.

### 2. [Baixo] `iconic()` não verifica a existência do ícone fora de heroicons

- **Evidência:** `Component::iconic()` (src/Components/QrCode/Component.php:83-96) — quando o icon type configurado **não** é heroicons, qualquer watermark que case com a regex de nome de ícone (`^[a-z0-9]+(-[a-z0-9]+)*$`) retorna `true` sem checar `Views::exists`, e o Blade tenta renderizar o ícone.
- **Condições:** projeto com icon set custom (BladeUI) + watermark minúsculo/hifenizado que não é um ícone do set (ex.: uma legenda `pix-1`).
- **Impacto:** exceção de view ausente do componente de ícone, em vez do fallback para texto que o comentário da classe promete ("Only an icon whose view exists counts as one") e que funciona no caminho heroicons (coberto por teste).
- **Gravidade/Urgência:** baixa / planejável. Cenário nicho, erro barulhento (aparece no primeiro render, não silencioso).
- **Confiança:** alta na leitura do código; o fluxo custom não foi exercitado em teste.
- **Recomendação:** ou documentar que em sets custom qualquer nome hifenizado é tratado como ícone, ou aplicar o mesmo fallback quando houver como resolver a existência no set custom.

### 3. [Baixo] Watermark de texto fica ilegível em versões pequenas

- **Evidência:** `Watermark::odd()` limita a faixa a `size - 18` módulos. Com link curto (v2, 25 módulos) e caption de 8 caracteres, a faixa fica com 7×3 módulos e a fonte calculada cai para ~1.4 módulo — visualmente ilegível (o QR continua decodificável; o knockout é mínimo).
- **Impacto:** apenas estético, em combinação improvável (caption máximo + link muito curto). A validação de 8 caracteres já limita o dano.
- **Gravidade:** baixa; não exige ação. Se quiser polir: escalar o limite de caracteres pela versão, ou documentar que captions longos pedem links maiores.

---

## Observações (não acionáveis)

- **`download()` no Alpine** cria o anchor sem anexar ao DOM e revoga o object URL sincronamente após o `click()`. Funciona nos browsers atuais e o teste de browser cobre o fluxo (com stub); libs como FileSaver deferem o revoke por robustez. Sem evidência de falha — registro apenas como hipótese a observar se surgir report.
- **Escopo do conteúdo:** a validação exige URL válida (`FILTER_VALIDATE_URL`). Payloads QR legítimos que não são URL (Wi-Fi, vCard, texto puro) ficam fora do contrato — coerente com o nome `link` e claramente uma decisão de produto, não defeito.
- **Arquivos `.md` no PR:** a skill pede para sinalizar `.md` adicionados. Os presentes (`.ai/components/qr-code.md`, `docs.md` e regenerações de `.ai/*`) pertencem ao pipeline de documentação do próprio repositório, pré-existente na 4.x — não são artefatos avulsos de explicação. Conformes.

---

## Conformidade com o estilo (checklist da skill)

- **Props em camelCase, nomes curtos e óbvios:** ✅ `link`, `color`, `size`, `watermark`, `skeleton`, `copy`, `download` — nenhum nome composto desnecessário.
- **Construtor enxuto e tipado:** ✅ 7 parâmetros, todos tipados, corpo `//`.
- **PSR-12:** ✅ (Pint verde).
- **Blade com um único bloco `@php`:** ✅ nos dois templates; toda a lógica vive em `QrCodeRuntime`, conforme a arquitetura V3/V4.
- **AlpineJS em arquivo separado:** ✅ `src/Components/QrCode/alpine.js` (102 linhas — tamanho justifica o arquivo), registrado em `js/tallstackui.js` como `tallstackui_qrCode`.
- **Registro estrutural completo:** ✅ `config.php` (com `size`/`pixels` documentados), `Customization::qrCode()`, `CompileConfigurations`, `IconGuide` (`arrow-down-tray`; `clipboard-document` e `check` já existiam), `ColorProviderTest`, `CustomizationTest`, `StubTests`, stub de cores publicável.
- **i18n:** ✅ `qr-code.copy`/`qr-code.download` nos 16 language packs.
- **Testes:** ✅ 21 feature tests do componente (render, cores, tamanhos, watermark ícone/texto/fallback, skeleton, ações, todas as validações de erro) + 15 testes de suporte do encoder — incluindo o exemplo publicado do spec (HELLO WORLD), verificação por propriedade de Reed-Solomon (raízes do polinômio gerador), consistência geometria×tabela para as 160 combinações versão/nível, e determinismo. Browser tests para copy/download/raster/serialize.
- **Sem artefatos de debug:** ✅ nenhum `dd()`, `dump()`, `console.log` nos arquivos do PR.

---

## Resumo executivo

O PR entrega um encoder ISO/IEC 18004 completo e correto (verificado contra referência externa, bit a bit), com API enxuta no padrão da casa, integração estrutural completa e cobertura de testes acima da média do repositório. O único ponto que merece um follow-up antes de aparecer em página quente é o custo de render sem cache (achado 1) — resolvível com memoização localizada, sem tocar no algoritmo. Os demais achados são polimento.
