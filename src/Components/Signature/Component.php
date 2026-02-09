<?php

namespace TallStackUi\Components\Signature;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\RequireLivewireContext;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\SignatureRuntime;
use TallStackUi\TallStackUiComponent;

#[RequireLivewireContext]
#[SoftCustomization('signature')]
#[PassThroughRuntime(SignatureRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $invalidate = null,
        public ?string $color = '#000000',
        public ?string $background = 'transparent',
        public int|float|null $line = 2,
        public ?int $height = 150,
        public ?bool $jpeg = null,
        public ?bool $clearable = null,
        public ?bool $exportable = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.signature.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'dark:bg-dark-800 dark:border-dark-600 rounded-lg border border-gray-300 bg-white',
                'second' => 'dark:border-dark-600 flex items-center justify-between space-x-4 border-b border-gray-300 px-4 py-2',
                'button' => 'flex items-center space-x-4',
            ],
            'canvas' => [
                'base' => 'dark:border-dark-600 w-full rounded-lg border border-dashed border-gray-300',
                'wrapper' => 'p-3',
            ],
            'icons' => 'dark:text-dark-400 h-5 w-5 text-gray-500',
        ]);
    }

    protected function validate(): void
    {
        if (is_null($this->line)) {
            __ts_validation_exception($this, 'The [line] must be a number.');
        }

        if ($this->height < 10) {
            __ts_validation_exception($this, 'The [height] must be at least 10.');
        }
    }
}
