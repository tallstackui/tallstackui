<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\SignatureRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('signature')]
#[PassThroughRuntime(SignatureRuntime::class)]
//TODO: tests
//TODO: when using wire:model the content has `data:image/png;base64,` prepended to the value, this is really needed?
class Signature extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $invalidate = null,
        public ?string $color = '#000000', // test
        public ?string $background = 'transparent', // test
        public int|float|null $line = 2,
        public ?int $height = 150,
        public ?string $extension = 'png', // rename it
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.signature');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'dark:bg-dark-800 dark:border-dark-600 rounded-lg border border-gray-300 bg-white',
                'second' => 'dark:border-dark-600 flex items-center justify-between space-x-4 border-b border-gray-300 px-4 py-2',
                'button' => 'flex items-center space-x-4',
            ],
            'canvas' => 'dark:border-dark-600 w-full rounded-lg border border-dashed border-gray-300',
            'icons' => 'dark:text-dark-400 h-5 w-5 text-gray-500',
        ]);
    }
}
