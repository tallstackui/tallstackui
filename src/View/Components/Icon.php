<?php

namespace TallStackUi\View\Components;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;
use Throwable;

class Icon extends Component
{
    public function __construct(
        public ?string $svg = null,
        public ?bool $error = false,
        public ?bool $solid = false,
        public ?bool $outline = false,
        public ?string $left = null,
        public ?string $right = null,
    ) {
        //
    }

    /**
     * Extract the icon svg name from the attributes.
     *
     * @throws Throwable
     */
    public function extract(ComponentAttributeBag $bag): string
    {
        $attributes = $bag->getAttributes();

        $booleans = collect($attributes)->filter(fn (mixed $value) => is_bool($value));

        $icon = $booleans->keys()->first();

        throw_if(! $this->svg && ! $icon, new Exception('You must set the icon using [svg] or direct attribute name.'));

        throw_if(! $this->check('outline', $icon) || ! $this->check('solid', $icon), new Exception('The icon ['.$icon.'] is not a valid Heroicon icon.'));

        return $icon;
    }

    public function render(): View
    {
        return view('tallstack-ui::components.icon');
    }

    private function check(string $type, string $icon): bool
    {
        return file_exists(sprintf(__DIR__.'/../../resources/views/components/icon/%s/%s.blade.php', $type, $icon));
    }
}
