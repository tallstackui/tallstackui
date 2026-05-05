---
name: open-pr
description: Use ONLY when the user explicitly asks to open a PR — phrases like "open a PR", "abra um PR" (pt-BR), "abra um pull request" (pt-BR), "mande pra 3.x", "send the PR". Never trigger this skill on your own initiative, including under auto mode or after finishing a task. Wait for the explicit request.
---

# Open PR

Open a pull request the way I prefer it. Follow every section below — every time.

## Activation Rule

This skill is **manual-only**. Use it **only** when I explicitly ask to open the PR. Do not start opening a PR:

- Just because the task we worked on is finished
- Just because tests are green
- Just because auto mode is active
- Just because the branch looks ready

If you finish a task and the working tree is dirty, stop there. Tell me what is ready and wait. The decision to open a PR — and which moment to do it — is mine. Auto mode does **not** override this.

If anything is unclear or there is a conflict (wrong base branch, dirty working tree, mismatched account), stop and ask me before pushing or creating the PR.

# Pre-flight

## GitHub Account

The active `gh` account must be `devajmeireles`.

```shell
gh auth status
```

If the active account is anything else, run `gh auth switch` first.

## Base Branch

- **Default — `3.x`:** every feature, enhancement, refactor, and non-stable bugfix
- **Exception — `2.x`:** only hotfixes targeting the stable release

When in doubt, ask me before pushing.

## Branch Naming

Use kebab-case, prefixed by the change type:

- `feature/<name>` — features and enhancements (e.g. `feature/avatar-group-reverse`, `feature/button-group`)
- `fix/<name>` — bugfixes on `3.x`
- `hotfix/<name>` — bugfixes on `2.x`

# Commits

## Coverage

Before opening the PR, commit **every file** that is still pending — including changes that are not part of the direct context of the task we worked on. The branch must reflect the full working tree state. Run `git status` and confirm it is clean **before** invoking `gh pr create`.

If a pending file should not ship in this PR, move it out of the working tree (separate branch, stash, or revert) **before** opening — never leave it sitting modified or untracked.

## Build Assets

Before the final commit pass, always run:

```shell
npm run build
```

This regenerates `dist/` so any new asset produced by the changes (compiled JS bundles, the Tailwind v4 stylesheet, manifest entries) is part of the PR. If `git status` shows files under `dist/` modified after the build, commit them in their own `building assets` commit — never bundled with feature/fix commits.

Do not skip this step even when the change feels purely PHP/Blade. Tailwind class scanning, view detection, and the manifest can all change.

## Convention

Conventional Commits with the affected scope (component or area):

```
feat(avatar/group): add reverse prop to flip stacking direction
refactor(key-value): drop hover underline from add button
fix(floating): close popups before container hides their anchor
chore: exclude colocated test files from package distribution
```

## Separation

Split commits by **logical change**. Never bundle a feature, an unrelated refactor, and the build artifacts in one commit.

When `dist/` is regenerated, commit it separately with the message:

```
building assets
```

## What NOT to do

- Never sign commits with `Co-Authored-By: Claude` or any AI metadata
- Never bypass hooks (`--no-verify`, `--no-gpg-sign`)
- Never amend a published commit — create a new one instead
- Never `git push --force` to `main`, `2.x`, or `3.x`

# PR Title

Format:

```
[<base-branch>] <Verb> `<component>` <Short Description>
```

The title must:

1. Start with the **version prefix** in brackets matching the base branch
2. Use a strong action verb (`Add`, `Fixing`, `Refactor`, `Improve`, `Drop`, `Remove`)
3. Wrap component names in backticks
4. Be in **English**, in Title Case
5. Be a practical summary of what the PR does — not a vague label

Wrong ❌

```
[3.x] avatar group reverse
[3.x] feat: button.group
[3.x] PR for new component
```

Correct ✅

```
[3.x] Add `button.group` Component
[3.x] Add `avatar.group` Reverse Prop
[3.x] Fixing `select.styled` Floating Issue
[3.x] Refactor `form/error` Resolution
```

# PR Body

The body **must** reproduce the project template at `.github/PULL_REQUEST_TEMPLATE.md` literally and only fill what each section asks for. **Never invent new top-level sections** (`## Summary`, `## Test plan`, etc.). The four `###` sections from the template are the entire body.

**Language:** every line of the body — together with the PR title — is written in **English**, with no exceptions. This applies even when the conversation that produced the PR was in pt-BR.

## Template (literal — do not deviate)

```markdown
### Checklist:

- [x] I read the [CONTRIBUTION GUIDE](https://tallstackui.com/docs/contribution)
- [x] I created a new branch on my fork for this pull request
- [x] I ensure that the current tests are passing
- [x] I added tests that prove this pull request is working

### What:

- [x] Feature
- [ ] Enhancements
- [ ] Bugfix

### Description:

<...>

### Demonstration & Notes:

<...>
```

## Filling each section

### `### Checklist:`

**Always mark all four items `[x]`.** Do not leave any unchecked.

### `### What:`

Mark **exactly one** of the three categories, based on the actual nature of the PR. Never mark more than one. Guide:

- **Feature** — adds a new component, prop, or capability
- **Enhancements** — improves an existing component without behavior change
- **Bugfix** — corrects broken behavior

### `### Description:`

Short and practical — **not detailed**. The audience is another AI that will read the PR to know what to document in the library docs site. Give it clear, factual instructions about what shipped and what needs to be documented.

Keep it to a few short paragraphs or a tight bullet list. **Do not** include code examples, soft-customization tables, or test plan checklists in this section — those belong elsewhere (the implementation, the tests, the validation skill).

What to include:

- What changed (component, prop, behavior) — one sentence per item
- Public API surface that must be documented (new prop names, accepted values, defaults, scopes)
- Anything a documenter must know to write the docs page accurately (breaking changes for previous customization keys, deprecations, soft-customization block names that were added or renamed)

What to leave out:

- Implementation details
- Internal refactors that do not change the public API
- Reasoning that does not affect how the feature is documented or used

### `### Demonstration & Notes:`

Useful examples of what was shipped, **direct and example-led**.

- **Bugfix** — usually nothing to demonstrate. Leave empty or add a one-line note only if there is a non-obvious reproduction or migration step.
- **Feature / Enhancements** — show the new component, prop, or alternative **directly through code examples** (Blade preferred; PHP when relevant). One example per use case. No prose intro, no "here is how to use it" — let the snippet speak.

Add explanation **only** when it is genuinely useful (a non-obvious caveat, an interaction with another prop, a breaking change for previous customizations). Otherwise avoid filler text.

Wrong ❌ — wrapping prose around the snippet:

> This new component is very useful for many situations. You can use it as follows in a default scenario, where it will render with the standard configuration. Below is an example demonstrating its basic usage. You can also use it with the `bar` prop, which changes its behavior...

Correct ✅ — snippets only, one per use case:

```blade
<x-foo />

<x-foo bar />

<x-foo bar="primary" :items="$items" />
```

## Anti-Reference

**PR https://github.com/tallstackui/tallstackui/pull/1263 is an example of a body I do NOT want.** Concretely:

- Invented top-level sections (`## Summary`, `## Test plan`) instead of reproducing the official template
- Skipped the four template sections (`### Checklist:`, `### What:`, `### Description:`, `### Demonstration & Notes:`) entirely
- Long prose paragraphs explaining each use case
- Extra sub-headings (`### 1. Default stacking`, `### 2. Reversed stacking`, etc.) that bloat the description
- Soft-customization tables, breaking-change callouts, and verbose test-plan checklists embedded in the body
- Multiple unrelated changes documented at full length in the same PR

If you find yourself producing anything resembling this shape, stop and trim. Description should be short and factual. Demonstration should be code-led and silent. The official template stays intact.

# Command

Use a heredoc on `gh pr create` to preserve formatting. Always pass `--base` explicitly — never rely on the default.

## Escape rules — read this every time

The command has two regions with **different** escaping rules. Get this wrong and the body renders broken on GitHub.

| Region | Quoting | Backticks |
|---|---|---|
| `--title "..."` | shell double quotes | **must** be escaped as `\`` so the shell does not run them as command substitution |
| `--body "$(cat <<'EOF' ... EOF)"` | heredoc with **single-quoted** delimiter (`'EOF'`) — no shell interpretation inside | **never escape**. Backticks are literal. Triple backticks for code fences are written as plain ` ``` ` |

Escaping triple backticks inside the body is what broke PR #1264 (`\`\`\`blade` rendered literally). Do not repeat.

## Full example

The block below shows the full command — including a real ` ```blade ` fence inside the body, unescaped.

````shell
gh pr create --base 3.x --head feature/<branch> --title "[3.x] Add \`<component>\` <Description>" --body "$(cat <<'EOF'
### Checklist:

- [x] I read the [CONTRIBUTION GUIDE](https://tallstackui.com/docs/contribution)
- [x] I created a new branch on my fork for this pull request
- [x] I ensure that the current tests are passing
- [x] I added tests that prove this pull request is working

### What:

- [x] Feature
- [ ] Enhancements
- [ ] Bugfix

### Description:

<short factual paragraph(s) — see Filling each section above>

### Demonstration & Notes:

```blade
<x-foo />
```
EOF
)"
````

# Before Pushing

Run through this checklist before pushing the branch and opening the PR:

1. Active GitHub account is `devajmeireles` (`gh auth status`)
2. Base branch is correct (`3.x` for features and enhancements; `2.x` only for stable hotfixes)
3. Branch name follows the convention (`feature/`, `fix/`, `hotfix/`)
4. `npm run build` was executed and any resulting `dist/` change is committed
5. `git status` is clean — every pending file (related or not to the task) is committed or moved out of the working tree
6. Commits are split by logical change and follow Conventional Commits; `dist/` sits in its own `building assets` commit
7. Quality gate is green — invoke the `validate-task-development` skill if not already verified
8. The PR title respects the format and the body fills the official template

If any of these fail, fix it first. Do not open the PR with known violations.
