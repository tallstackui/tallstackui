---
name: review-pr
description: Use this when the user says "review this PR", "valide esse PR" (pt-BR) or anything associated with the idea of validating a task.
---

# Review PR

This branch is part of a PR submitted by a TallStackUI user. I would appreciate your review to ensure that all code conforms to my preferred writing style. Please validate all changes in this branch, ensuring that everything mentioned below is correct.

If you find anything that doesn't conform to my standards, please mention it so I can correct it, or suggest a correction based on all the explanations mentioned here.

# PHP Code Validation

Component properties should follow the camel case:

Wrong ❌

```php
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(public ?string $simplevisual = null)
    {
        //
    }
}
```

Correct ✅

```php
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(public ?string $simpleVisual = null)
    {
        //
    }
}
```

Furthermore:

1. Ensure that property names make sense in relation to their intended purpose.
2. Ensure that class constructors are not too large.
3. Ensure that property/attribute names are not too "strange" or "unusual" – I have personal issues accepting property names like `$activeUsersCount`. Therefore, I prefer names like `$count`, when possible and there are no conflicts.

4. Ensure that the following validations are passing:

- PHP code written following PSR 12
- PHP constructor parameters are typed correctly
- Commands such as: `./vendor/bin/pest --type-coverage`, `./vendor/bin/pint --test --parallel` and `./vendor/bin/phpstan analyse --memory-limit=2G` and `php scripts/find-unused-customization-blocks.php` are passing successfully. If they do not pass, suggest corrections for the errors described.

5. Ensure that everything done makes sense according to the business logic rules of the library - scan CLAUDE.md again if you want to remember something from the project.

# JavaScript Code Validation

Ensure that the code was written in JavaScript and that the `npm run lint` and `npm run build` validations are passing successfully. If they do not pass, suggest corrections for the errors described.

# Test Validation

Validate Feature tests using: `./vendor/bin/pest --parallel --group Feature`

# Blade File Change Validation

We need to divide this into two parts:

1. If the changes involve things like HTML, ensure that the organization is correctly applied, according to the indentation of spaces in relation to the HTML tags of the blocks themselves.

2. If the changes involve AlpineJS, ensure that:

- If the changed/inserted AlpineJS logic is small, it can reside in the Blade file itself.

- If the AlpineJS logic is large (or if explicitly requested by me), it must be inserted into a separate alpine.js file, stored in the namespace of the inserted/changed component by the branch.

# Final Analyses

1. Ensure that there are no .md files added by the PR, such as: documentation, explanations, and things of that nature.

2. Ensure that what has been done makes sense and that there is nothing wrong with it, ESPECIALLY in relation to business rules OR performance.

3. Ensure that tests were added for the new functionality. If not, suggest adding them to validate if what has been done is correct.
