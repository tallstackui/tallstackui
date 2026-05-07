<?php

namespace TallStackUi\Components\CommandPalette;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\SelectSetup;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\CommandPaletteRuntime;
use TallStackUi\TallStackUiComponent;
use Throwable;

#[SoftCustomization('commandPalette')]
#[PassThroughRuntime(CommandPaletteRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use SelectSetup {
        SelectSetup::setup as boot;
    }

    public function __construct(
        public ?string $id = 'command-palette',
        public string|array|null $request = null,
        public Collection|array $options = [],
        public ?array $selectable = [],
        public ?array $placeholders = null,
        public ?bool $recycle = null,
        public ?string $shortcut = null,
        public ?bool $centered = null,
        public ?bool $overlay = null,
        #[SkipDebug]
        public ?bool $grouped = null,
        #[SkipDebug]
        public ?string $select = null,
    ) {
        $this->request ??= __ts_get_component_configuration(self::class, 'request');

        $this->request();

        $this->placeholders = array_merge(trans('ts-ui::messages.command-palette'), $this->placeholders ?? []);
    }

    public function blade(): View
    {
        return view('ts-ui::components.command-palette.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'backdrop' => 'fixed inset-0 bg-gray-400/75 transform transition-opacity',
            'blur' => [
                'sm' => 'backdrop-blur-sm',
                'md' => 'backdrop-blur-md',
                'lg' => 'backdrop-blur-lg',
                'xl' => 'backdrop-blur-xl',
            ],
            'wrapper' => 'fixed inset-0 flex justify-center sm:pt-[15vh]',
            'positions' => [
                'bottom' => 'items-end sm:items-start',
                'center' => 'items-center p-4 sm:p-0',
            ],
            'box' => 'w-full max-w-lg overflow-hidden bg-white shadow-2xl ring-1 ring-dark-900/5 dark:bg-dark-800 dark:ring-dark-700',
            'box.radius' => [
                'default' => 'rounded-t-xl sm:rounded-xl',
                'center' => 'rounded-xl',
            ],
            'input' => [
                'wrapper' => 'flex items-center border-b border-dark-100 px-4 dark:border-dark-700',
                'border-empty' => 'border-b-0!',
                'icon' => 'h-5 w-5 text-dark-400 dark:text-dark-500',
                'base' => 'h-12 w-full border-0 bg-transparent text-sm text-dark-900 placeholder-dark-400 focus:ring-0 focus:outline-none dark:text-dark-100 dark:placeholder-dark-500',
                'loading' => 'flex items-center',
            ],
            'loading' => [
                'icon' => 'h-5 w-5 animate-spin text-dark-400',
            ],
            'list' => 'max-h-72 scroll-py-2 overflow-y-auto p-2',
            'list-scrollbar' => 'command-palette-scrollbar',
            'option' => [
                'base' => 'flex w-full cursor-pointer items-center gap-x-3 rounded-lg px-3 py-2 text-left',
                'active' => 'bg-primary-50 dark:bg-dark-700',
                'disabled' => 'opacity-50 cursor-not-allowed',
                'image' => 'h-6 w-6 flex-shrink-0 rounded-full object-cover',
                'icon' => 'h-6 w-6 flex-shrink-0 text-dark-400 dark:text-dark-500 [&>svg]:h-full [&>svg]:w-full',
                'content' => 'flex flex-col overflow-hidden',
                'label' => 'truncate text-sm font-medium text-dark-600 dark:text-dark-300',
                'description' => 'truncate text-xs text-dark-500 dark:text-dark-400',
            ],
            'empty' => 'px-4 py-8 text-center text-sm text-dark-500 dark:text-dark-400',
            'footer' => 'hidden sm:flex items-center gap-x-4 border-t border-dark-100 px-4 py-2.5 text-xs text-dark-400 dark:border-dark-700 dark:text-dark-500',
        ]);
    }

    protected function setup(): void
    {
        $this->select ??= 'label:label|value:value|description:description|image:image|icon:icon';

        $this->boot();

        $parsed = array_reduce(
            explode('|', $this->select),
            function (array $result, string $item): array {
                $parts = explode(':', $item, 2);

                if (count($parts) === 2) {
                    $result[$parts[0]] = $parts[1];
                }

                return $result;
            },
            []
        );

        $this->selectable['icon'] = $parsed['icon'] ?? 'icon';
    }

    protected function validate(): void
    {
        if (! filled($this->request)) {
            __ts_validation_exception($this, 'The [request] must be configured either as an inline attribute or in the config file.');
        }

        $actionable = __ts_get_component_configuration(self::class, 'actionable');

        if ($actionable !== null && ! class_exists($actionable)) {
            __ts_validation_exception($this, 'The [actionable] class does not exist.');
        }

        if ($actionable !== null && ! method_exists($actionable, '__invoke')) {
            __ts_validation_exception($this, 'The [actionable] class must be invocable (__invoke).');
        }

        if (! is_array($this->request)) {
            return;
        }

        if (! isset($this->request['url'])) {
            __ts_validation_exception($this, 'The attribute [url] is required in the request array.');
        }

        $this->request['method'] = strtolower((string) ($this->request['method'] ?? 'get'));

        if (! in_array($this->request['method'], ['get', 'post'])) {
            __ts_validation_exception($this, 'The attribute [method] must be "get" or "post".');
        }

        if (isset($this->request['params']) && (! is_array($this->request['params']) || blank($this->request['params']))) {
            __ts_validation_exception($this, 'The attribute [params] must be an array and cannot be empty.');
        }
    }

    /** Resolve the request URL. */
    private function request(): void
    {
        if (! is_string($this->request) || str_starts_with($this->request, 'http')) {
            return;
        }

        try {
            $this->request = route($this->request);
        } catch (Throwable) {
            //
        }
    }
}
