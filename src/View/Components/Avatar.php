<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use InvalidArgumentException;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use TallStackUi\View\Personalizations\Traits\InteractWithProviders;
use Throwable;

class Avatar extends Component implements Personalize
{
    use InteractWithProviders;

    public function __construct(
        public ?Model $model = null,
        public ?string $text = null,
        public ?string $color = 'primary',
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public bool $square = false,
        private readonly ?string $property = 'name',
        private readonly ?string $background = '0D8ABC',
        public ?string $size = null,
    ) {
        $this->text = $this->content();
        $this->size = $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');

        $this->colors();
    }

    public function alt(): string
    {
        return $this->model->getAttribute($this->property);
    }

    /** @throws Throwable */
    public function content(): ?string
    {
        if (! $this->model && ! $this->text) {
            return null;
        }

        if ($this->text) {
            return $this->text;
        }

        $property = $this->model->getAttribute($this->property);

        throw_if(blank($property), new InvalidArgumentException("The property {$this->property} does not exists or is blank"));

        return "https://ui-avatars.com/api/?name={$property}&background={$this->background}&color={$this->color}";
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'inline-flex shrink-0 items-center justify-center overflow-hidden',
                'sizes' => [
                    'sm' => 'w-8 h-8 text-xs',
                    'md' => 'w-12 h-12 text-lg',
                    'lg' => 'w-14 h-14 text-2xl',
                ],
            ],
            'content' => [
                'image' => [
                    'class' => 'shrink-0 object-cover object-center text-xl',
                    'sizes' => [
                        'sm' => 'w-8 h-8 text-sm',
                        'md' => 'w-12 h-12 text-lg',
                        'lg' => 'w-14 h-14 text-2xl',
                    ],
                ],
                'text' => [
                    'class' => 'font-semibold',
                    'colors' => [
                        'colorful' => 'text-white',
                        'white' => 'text-neutral-700',
                    ],
                ],
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.avatar');
    }
}
