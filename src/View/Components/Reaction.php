<?php

namespace TallStackUi\View\Components;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Exceptions\InvalidSelectedPositionException;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('reaction')]
class Reaction extends BaseComponent implements Personalization
{
    protected const ICONS = [
        'smile' => '1f600',
        'thumbs-up' => '1f44d_1f3fb',
        'thumbs-down' => '1f44e_1f3fb',
        'heart' => '2764_fe0f',
        'laugh' => '1f62d',
        'love' => '1f60d',
        'rocket' => '1f680',
        'fire' => '1f525',
        'pray' => '1f64f_1f3fb',
        // TODO: increase to 20 most used emojis
    ];

    public function __construct(
        public ?array $only = null,
        public ?bool $animated = false,
        public ?string $quantity = null,
        public string $reactMethod = 'react',
        public ?string $position = 'auto'
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.reaction');
    }

    final public function content(): string
    {
        $icons = ! $this->only ? self::ICONS : Arr::only(self::ICONS, $this->only);

        $buttons = collect($icons)->map(function (string $icon, string $key) {
            $method = $this->reactMethod;
            $extension = $this->animated ? 'gif' : 'png';

            //TODO: pass icon class to blocks
            return <<<HTML
            <button type="button" wire:click="$method('$key')">
                <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/$icon/512.$extension" class="w-5 h-5">
            </button>
            HTML;
        })->implode('');

        return <<<HTML
        <div class="inline-flex items-center space-x-1">
            $buttons
        </div>
        HTML;
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }

    /** @throws Exception|InvalidSelectedPositionException */
    protected function validate(): void
    {
        if (blank($this->reactMethod)) {
            throw new Exception('The react method is required.');
        }

        if (($collect = collect($this->only)->diff(array_keys(self::ICONS)))->isNotEmpty()) {
            throw new Exception('Invalid icons: '.implode(', ', $collect->toArray()));
        }

        InvalidSelectedPositionException::validate(get_class($this), $this->position);
    }
}
