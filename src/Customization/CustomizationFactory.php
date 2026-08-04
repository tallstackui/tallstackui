<?php

namespace TallStackUi\Customization;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use RuntimeException;

/**
 * @internal
 *
 * @property-read Customization $and
 */
class CustomizationFactory implements Arrayable
{
    /**
     * Block name to be customized.
     */
    public ?string $block = null;

    /**
     * Blocks available for customization.
     */
    public array $blocks = [];

    /**
     * Original classes of the component with changes applied.
     */
    private array $changes = [];

    /**
     * Interactions, for when we are personalizing without $code for the block.
     */
    private array $interactions = [];

    /**
     * Original customization cached to avoid redundant container resolutions.
     */
    private array $original = [];

    /**
     * Parts of the component customization.
     */
    private array $parts = [];

    public function __construct(public readonly string $component, private readonly ?string $scope = null)
    {
        //
    }

    /**
     * Creating the ability to use Pest's style: ->and->block('name', 'content').
     */
    public function __get(string $property): Customization
    {
        if ($property === 'and') {
            return $this->and();
        }

        throw new RuntimeException("Property [{$property}] does not exist.");
    }

    /**
     * Customize sequentially creating a new instance of the Customization class.
     */
    public function and(): Customization
    {
        return new Customization;
    }

    /**
     * Append content to the block.
     *
     * @return $this
     */
    public function append(string $content): self
    {
        $this->interactions['append'] = $content;

        $this->compile();

        return $this;
    }

    /**
     * Interact with the block to start the customization.
     *
     * @return $this
     */
    public function block(string|array $name, string|callable|null $code = null): self
    {
        // The idea of this code existing in the file and not in the construct
        // is to avoid an unnecessary call every time the component is rendered,
        // even if it has no customizations to be applied.
        if ($this->original === []) {
            $this->original = app($this->component)->customization();
            $this->blocks = array_keys($this->original);
        }

        // Resuming from what earlier chains already compiled, so that two
        // customizations of the same block stack instead of the second one
        // silently discarding the first. This is also what lets a predefined
        // scope be extended: the factory is the same instance either way.
        $this->changes = array_merge($this->original, $this->compiled());

        // If the $code was not set, then we
        // are interacting with the shortcuts.
        if (is_string($name) && is_null($code)) {
            $this->block = $name;

            return $this;
        }

        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->composer($key, $value);
            }
        } else {
            $this->composer($name, $code);
        }

        return $this;
    }

    public function get(string $block): ?string
    {
        if ($this->scope !== null) {
            // Blocks live as flat keys inside the scope container, so the block
            // name must not be walked as a path. See compile().
            $scoped = data_get($this->parts, $this->scope, []);

            return is_array($scoped) ? ($scoped[$block] ?? null) : null;
        }

        return $this->parts[$block] ?? null;
    }

    /**
     * Prepend content to the block.
     *
     * @return $this
     */
    public function prepend(string $content): self
    {
        $this->interactions['prepend'] = $content;

        $this->compile();

        return $this;
    }

    /**
     * Remove content to the block.
     *
     * @return $this
     */
    public function remove(string|array $class): self
    {
        $this->interactions['remove'] = Arr::wrap($class);

        $this->compile();

        return $this;
    }

    /**
     * Replace content to the block.
     *
     * @return $this
     */
    public function replace(string|array $from, ?string $to = null): self
    {
        $this->interactions['replace'] = is_array($from) ? $from : [$from => $to];

        $this->compile();

        return $this;
    }

    /** {@inheritDoc} */
    public function toArray(): array
    {
        return $this->parts;
    }

    /**
     * Compile the customization.
     */
    private function compile(?string $block = null, ?string $content = null): void
    {
        $block ??= $this->block;

        if ($block === null) {
            throw new RuntimeException('No block has been set. Call block() before append(), prepend(), replace() or remove().');
        }

        // Writing the code into the working copy instead of straight into the
        // output, so that a shortcut chained after block($name, $code) builds
        // on top of the code rather than on the untouched original.
        if ($content !== null) {
            $this->changes[$block] = $content;
        }

        foreach (($this->interactions['replace'] ?? []) as $old => $new) {
            $this->changes[$block] = str_replace($old, $new, (string) ($this->changes[$block] ?? ''));
        }

        if ($append = ($this->interactions['append'] ?? null)) {
            $this->changes[$block] = ($this->changes[$block] ?? '').' '.$append;
        }

        if ($prepend = ($this->interactions['prepend'] ?? null)) {
            $this->changes[$block] = $prepend.' '.($this->changes[$block] ?? '');
        }

        foreach (($this->interactions['remove'] ?? []) as $class) {
            $this->changes[$block] = $this->except((string) ($this->changes[$block] ?? ''), $class);
        }

        $compiled = trim((string) preg_replace('/\s+/', ' ', trim((string) ($this->changes[$block] ?? ''))));

        if ($this->scope !== null) {
            // Resolving the scope container first, then writing the block as a flat key
            // inside it. Passing "scope.block" straight to data_set would read the dots
            // of the block as a path and let "body" overwrite its "body.paddingless" sibling.
            $scoped = data_get($this->parts, $this->scope, []);

            $scoped[$block] = $compiled;

            data_set($this->parts, $this->scope, $scoped);
        } else {
            $this->parts[$block] = $compiled;
        }

        $this->interactions = [];
    }

    /**
     * Blocks already compiled by earlier chains, flattened back to dot notation.
     */
    private function compiled(): array
    {
        $parts = $this->scope !== null
            ? Arr::dot(data_get($this->parts, $this->scope, []))
            : $this->parts;

        return array_filter($parts, 'is_string');
    }

    /**
     * Compiles the customization for the given block and code.
     */
    private function composer(string $block, string|callable|null $code = null): void
    {
        if (! in_array($block, $this->blocks)) {
            // Naming the component after its customization key, which is what
            // customize() accepts. The Blade view name would say "badge.main",
            // and feeding that back turns "main" into a scope instead.
            $component = str_replace('ts-ui::customization.', '', __ts_search_component($this->component));

            throw new InvalidArgumentException("Component [$component] does not have the block [$block] to be customized. Allowed: ".implode(', ', $this->blocks));
        }

        $this->block = $block;

        $this->compile($block, is_callable($code) ? $code([]) : $code);
    }

    /**
     * Drop whole classes from the block, matching by token rather than
     * by substring so that removing "border" leaves "border-gray-300" alone.
     */
    private function except(string $classes, string $class): string
    {
        $remove = preg_split('/\s+/', trim($class), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $tokens = preg_split('/\s+/', trim($classes), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return implode(' ', array_filter($tokens, fn (string $token): bool => ! in_array($token, $remove, true)));
    }
}
