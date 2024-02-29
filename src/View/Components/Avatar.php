<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('avatar')]
class Avatar extends BaseComponent implements Personalization
{
    public function __construct(
        public ?Model $model = null,
        public ?string $text = null,
        public ?string $color = 'primary',
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public bool $square = false,
        public ?string $property = 'name',
        public ?string $background = '0D8ABC',
        #[SkipDebug]
        public ?string $size = null,
        public ?array $options = [],
    ) {
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.avatar');
    }

    final public function modelable(): string
    {
        $params = Arr::query([
            'name' => $this->model->getAttribute($this->property),
            'background' => $this->background,
            'color' => $this->color,
            ...$this->options,
        ]);

        return "https://ui-avatars.com/api?{$params}";
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'inline-flex shrink-0 items-center justify-center overflow-hidden',
                'sizes' => [
                    'xs' => 'w-6 h-6 text-xs',
                    'sm' => 'w-8 h-8 text-sm',
                    'md' => 'w-12 h-12 text-md',
                    'lg' => 'w-14 h-14 text-lg',
                ],
            ],
            'content' => [
                'image' => [
                    'class' => 'shrink-0 object-cover object-center text-xl',
                    'sizes' => [
                        'xs' => 'w-6 h-6 text-xs',
                        'sm' => 'w-8 h-8 text-sm',
                        'md' => 'w-12 h-12 text-md',
                        'lg' => 'w-14 h-14 text-lg',
                    ],
                ],
                'text' => [
                    'class' => 'font-semibold',
                    'colors' => [
                        'colorful' => 'text-white',
                        'white' => 'text-black',
                    ],
                ],
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (! $this->model && ! $this->text) {
            return;
        }

        if ($this->text) {
            return;
        }

        $model = $this->model::class;
        $property = $this->model->getAttribute($this->property);

        if (blank($property)) {
            throw new InvalidArgumentException("The avatar property [{$this->property}] does not exists or is blank at the model [$model]");
        }
    }
}
