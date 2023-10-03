# TasteUi, Contribution Guide

## Initial

First of all, thank you for reading this document, it means you want to contribute to TasteUi. TasteUi is maintained by me, [AJ Meireles](https://www.linkedin.com/in/devajmeireles/), **together with other amazing developers.** I ask that you carefully read this file to understand how your contribution will be accepted in our library - yes, TasteUi is ours, and you as a user or contributor are part of it.

## Structure

### Base

- PHP 8.1 and 8.2
- Laravel 10
- **Livewire 3**

### Tests

- Laravel Dusk _(Livewire Validations)_ & PestPHP _(Simple Tests)_

## PR Expectation

All PR is welcome as long as it follows some criteria created for our organization:

- English only
- Based on [PSR-12](https://www.php-fig.org/psr/psr-12/)
- Formatting using [Pint](https://laravel.com/docs/pint)
- Analyses using [PHPStan](https://phpstan.org/)
- Tests required - when applicable

## Guide

1. Fork the repository
2. Create a basic Laravel project using Laravel Breeze
3. Install and prepare **Livewire 3**
4. Prepare any authentication way to access the `/dashboard`
5. Create a folder in the project root called `packages`
6. Inside the `packages` create another folder called `tasteui`
7. Inside the `tasteui` folder, clone your TasteUi repository
8. The final structure should be: `packages/tasteui/tasteui`
9. Open your `composer.json` and put the following content: 
```json
"repositories": [
    {
        "type": "path",
        "url": "./packages/tasteui/tasteui"
    }
],
```
10. Change the `minimum-stability` to `dev`
11. Run the command:
```shell
composer require tasteui/tasteui:dev-develop
```
12. Install the TasteUi 👇

## Installing TasteUi

1. Open the `resources/view/layout/app.blade.php` and insert the `@tasteUiStyles` above of  `@vite` directive.
2. Insert the `@tasteUiScripts` above the closing `<body>` html tag.
3. On `head` session, insert this:
```html
<style>
    [x-cloak] { display: none; }
</style>
```
4. You're done to create components and see it:

```html
<!-- in livewire/todo.blade.php -->

<x-modal id="taste">
    TasteUi
</x-modal>

<x-button x-on:click="$modalOpen('taste')">TasteUi</x-button>
```

## Warning

Some of the TasteUi components use AlpineJs, which is only delivered when you have Livewire components on the page. Pay attention to this detail, sometimes if something is not working, check if you are actually inside a Livewire component.

## Observations

1. Sometimes it will be necessary to make changes to TasteUi that are reflected in Composer, so use the command: `composer update tasteui/tasteui` after make the changes.
2. If you want to execute Browser tests, you will need to have Chrome installed. After that, run these commands:
```shell
./vendor/bin/dusk-updater detect --no-interaction
./vendor/bin/dusk-updater detect --auto-update
```
