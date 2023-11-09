<?php

namespace TallStackUi\View\Personalizations\Support;

use Exception;

class OriginalComponentPersonalization
{
    public function __construct(
        protected string $component,
        protected ?string $block = null,
        protected array $defaults = [],
    ) {
        $this->defaults = app($component, ['ignoreValidations' => true])->personalization();
    }

    public function __toString(): string
    {
        return trim($this->defaults[$this->block]);
    }

    public function append(string $content): self
    {
        $this->defaults[$this->block] .= $content;

        return $this;
    }

    public function prepend(string $content): self
    {
        $this->defaults[$this->block] = $content.$this->defaults[$this->block];

        return $this;
    }

    public function remove(string $content): self
    {
        $this->defaults[$this->block] = str_replace($content, '', $this->defaults[$this->block]);

        return $this;
    }

    public function replace(string|array $from, string $to = null): self
    {
        if (is_string($from)) {
            throw_if(! $to, new Exception('The [to] parameter is required when [from] is a string.'));

            throw_if(! str_contains($this->defaults[$this->block], $from), new Exception("The [{$from}] was not found in the [{$this->block}] block."));

            $this->defaults[$this->block] = str_replace($from, $to, $this->defaults[$this->block]);
        } else {
            foreach ($from as $old => $new) {
                throw_if(! str_contains($this->defaults[$this->block], $old), new Exception("The [{$old}] was not found in the [{$this->block}] block."));

                $this->defaults[$this->block] = str_replace($old, $new, $this->defaults[$this->block]);
            }
        }

        return $this;
    }

    public function tap(string $block): self
    {
        $this->block = $block;

        //TODO: create and reutilize an exception class
        //throw_if(! isset($this->defaults[$this->block]), new Exception("Component [$this->component] does not have the block [$block] to be personalized. Alloweds: ".implode(', ', array_keys($this->defaults))));

        return $this;
    }
}
