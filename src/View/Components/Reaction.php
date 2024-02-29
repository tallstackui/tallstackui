<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Exceptions\InvalidSelectedPositionException;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('reaction')]
class Reaction extends BaseComponent implements Personalization
{
    /**
     * Default supported icons.
     * References: https://googlefonts.github.io/noto-emoji-animation/
     */
    protected const ICONS = [
        // As the codes are visually indecipherable, we adopted
        // the idea of using alias names to refer to the icon.
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
        public ComponentSlot|string|null $quantity = null,
        public string $reactMethod = 'react',
        public ?string $position = 'auto',
        #[SkipDebug]
        public ?array $icons = null,
    ) {
        $this->icons = $this->only ? Arr::only(self::ICONS, $this->only) : self::ICONS;
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.reaction');
    }

    final public function content(string $component): string
    {
        $collect = collect($this->icons);
        $personalize = $this->personalization();

        $buttons = $collect->map(function (string $icon, string $reaction) use ($personalize) {
            $method = $this->reactMethod;
            $extension = $this->animated ? 'gif' : 'png';
            $class = $personalize['icon'];

            return <<<HTML
            <button type="button" x-on:click.prevent="react('$method', '$reaction')">
                <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/$icon/512.$extension" class="$class">
            </button>
            HTML;
        })->implode('');

        $class = $personalize['box.'.($collect->count() > 5 ? 'grid' : 'inline')];

        return <<<HTML
        <div x-data="{
                react (method, reaction) {
                    document.getElementById('$component')
                        .dispatchEvent(new CustomEvent('react', {detail: {reaction: { method, reaction }}}));
                
                    this.\$wire.call(method, reaction);
                }
            }" class="$class">
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
            'icon' => 'h-5 w-5',
            'quantity' => 'text-sm font-bold text-gray-700 dark:text-white',
        ]);
    }

    /** @throws InvalidArgumentException|InvalidSelectedPositionException */
    protected function validate(): void
    {
        if (blank($this->reactMethod)) {
            throw new InvalidArgumentException('The react [reactMethod] is required.');
        }

        if (array_diff($this->only ?? [], array_keys(self::ICONS)) !== []) {
            throw new InvalidArgumentException('The react [only] icons is invalid. Supported: '.implode(', ', array_keys(self::ICONS)));
        }

        InvalidSelectedPositionException::validate(static::class, $this->position);
    }
}
