<?php

namespace TallStackUi\Components\Loading;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\RequireLivewireContext;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Spinner\Component as Spinner;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\LoadingRuntime;
use TallStackUi\TallStackUiComponent;

#[RequireLivewireContext]
#[SoftCustomization('loading')]
#[PassThroughRuntime(LoadingRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $zIndex = null,
        public ?string $text = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?bool $blur = null,
        public ?bool $opacity = true,
        public ?bool $overflow = null,
        public ?string $indicator = null,
        #[SkipDebug]
        public ?string $spinner = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.loading.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'fixed inset-0 bg-gray-300 dark:bg-dark-900',
                'second' => 'flex h-full items-center justify-center',
            ],
            'opacity' => 'bg-gray-300/75 dark:bg-dark-900/70',
            'blur' => 'backdrop-blur-sm',
            'spinner' => 'h-12 w-12 animate-spin text-primary-700 dark:text-white',
            'text' => 'inline-flex items-center text-lg font-semibold text-primary-500',
        ]);
    }

    protected function setup(): void
    {
        $this->indicator ??= __ts_get_component_configuration(self::class, 'indicator');

        if (blank($this->indicator)) {
            $this->indicator = null;

            return;
        }

        if ($this->indicator === 'spinner') {
            $this->spinner = __ts_get_component_configuration(Spinner::class, 'type') ?? 'ring';
        } elseif (str_starts_with($this->indicator, 'spinner.')) {
            $this->spinner = substr($this->indicator, 8);
        }
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (! str(__ts_get_component_configuration(self::class, 'z-index') ?? 'z-50')->startsWith('z-')) {
            __ts_validation_exception($this, 'The [z-index] must start with z- prefix');
        }

        if ($this->indicator === null) {
            return;
        }

        if (! in_array($this->spinner, Spinner::TYPES, true)) {
            __ts_validation_exception($this, 'The [indicator] must be [spinner] or [spinner.{type}], where type is one of ['.implode(', ', Spinner::TYPES).']');
        }
    }
}
