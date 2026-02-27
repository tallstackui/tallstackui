---
name: validate-task
description: Use when user says "validate the task", "ensure everything is passing", "is everything ok?", "valide a entrega" (PT-BR), "garanta que tudo está funcionando" (PT-BR), "certifique-se de que está tudo ok" (PT-BR), or anything associated with the idea of validating a task.
---

# Validate Task

First of all running the following commands before any check:

```shell
npm install
```

```shell
npm run build
```

```shell
composer install
```

# Checklist

## Laravel Pint

Ensure the Laravel Pint is passing using:

```shell
./vendor/bin/pint --parallel --test
```

If pass successfully, proceed and check the next one. If you noticed any issue, ask if you can fix it.

## NPM ESLint

Ensure the Eslint is passing using:

```shell
npm run lint
```

```shell
npm run build
```

If pass successfully, proceed and check the next one. If you noticed any issue, ask if you can fix it.

## Pest Type Coverage

Ensure all tests are passing using:

```shell
./vendor/bin/pest --type-coverage --parallel --min=100
```

If pass successfully, proceed and check the next one. If you noticed any issue, ask if you can fix it.

## PhpStan Analysis

Ensure all tests are passing using:

```shell
./vendor/bin/phpstan analyse --memory-limit=2G
```

If pass successfully, proceed and check the next one. If you noticed any issue, ask if you can fix it.

## Feature Tests

Ensure all tests are passing using:

```shell
./vendor/bin/pest --parallel --filter Feature
```

If pass successfully, proceed and check the next one. If you noticed any issue, ask if you can fix it.

## Browser Tests

Ensure all tests are passing using:

```shell
composer test:browser:ci
```

If pass successfully, proceed and check the next one. If you noticed any issue, ask if you can fix it.

## Final

If everything described above is correct, consider the delivery validated and ready. Use a green check mark icon to
indicate this (✅). Feel free to draw a table with two columns: check and status, and then the indication of whether the
check passed or failed.
