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
        'laugh' => '1f62d',
        'love' => '1f60d',
        'screaming' => '1f631',
        'rage' => '1f621',
        'pray' => '1f64f_1f3fb',
        'thumbs-up' => '1f44d_1f3fb',
        'thumbs-down' => '1f44e_1f3fb',
        'heart' => '2764_fe0f',
        'broken-heart' => '1f494',
        'clap' => '1f44f_1f3fb',
        'rocket' => '1f680',
        'fire' => '1f525',
        'mind-blown' => '1f92f',
        'sick' => '1f922',
        'poop' => '1f4a9',
        'eyes' => '1f440',
        'party-popper' => '1f389',
        'clown' => '1f921',
        'check-mark' => '2705',
    ];

    public function __construct(
        public ?array $only = null,
        public ?bool $animated = false,
        public ?string $quantity = null,
        public string $reactMethod = 'react',
        public ?string $position = 'auto',
        public ?array $icons = null,
    ) {
        $this->icons = ! $this->only ? self::ICONS : Arr::only(self::ICONS, $this->only);
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.reaction');
    }

    final public function content(): string
    {
        $personalize = $this->personalization();
        $collect = collect($this->icons);

        $buttons = $collect->map(function (string $icon, string $key) use ($personalize) {
            $method = $this->reactMethod;
            $extension = $this->animated ? 'gif' : 'png';
            $class = $personalize['icon'];

            return <<<HTML
            <button type="button" wire:click="$method('$key')">
                <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/$icon/512.$extension" class="$class">
            </button>
            HTML;
        })->implode('');

        $class = $collect->count() > 5 ? $personalize['box.grid'] : $personalize['box.inline'];

        return <<<HTML
        <div class="$class">
            $buttons
        </div>
        HTML;
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'inline-flex cursor-pointer items-center rounded-md px-2 py-1',
                'second' => 'flex -space-x-1 overflow-hidden p-1',
            ],
            'box' => [
                'grid' => 'grid grid-cols-5 gap-2',
                'inline' => 'flex justify-center gap-2',
            ],
            'image' => 'dark:ring-dark-800 dark:bg-dark-700 inline-block h-5 w-5 rounded-full bg-white p-0.5',
            'icon' => 'h-6 w-6',
            'quantity' => 'text-sm font-bold text-gray-700 dark:text-white',
        ]);
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
