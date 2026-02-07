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

        $this->changes = $this->original;

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
            $this->changes[$block] = str_replace($class, '', (string) ($this->changes[$block] ?? ''));
        }

        $content = fn () => trim($content ?? preg_replace('/\s+/', ' ', trim($this->changes[$block] ?? '')));

        if ($this->scope) {
            data_set($this->parts, $this->scope.'.'.$block, $content());
        } else {
            $this->parts[$block] = $content();
        }

        $this->interactions = [];
    }

    /**
     * Compiles the customization for the given block and code.
     */
    private function composer(string $block, string|callable|null $code = null): void
    {
        if (! in_array($block, $this->blocks)) {
            $view = app($this->component)->blade()->name();

            $component = str_contains((string) $view, DIRECTORY_SEPARATOR)
                ? basename(dirname((string) $view))
                : str_replace('ts-ui::components.', '', (string) $view);

            throw new InvalidArgumentException("Component [$component] does not have the block [$block] to be customized. Allowed: ".implode(', ', $this->blocks));
        }

        $this->compile($block, is_callable($code) ? $code([]) : $code);
    }
}
