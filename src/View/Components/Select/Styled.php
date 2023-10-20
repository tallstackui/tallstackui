<?php

namespace TallStackUi\View\Components\Select;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use InvalidArgumentException;
use TallStackUi\View\Components\Select\Traits\InteractsWithSelectOptions;
use TallStackUi\View\Personalizations\Contracts\Personalize;
use Throwable;

class Styled extends Component implements Personalize
{
    use InteractsWithSelectOptions;

    /** @throws Throwable */
    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public Collection|array $options = [],
        public ?bool $multiple = false,
        public ?bool $searchable = false,
        public ?string $select = null,
        public ?array $selectable = [],
        public ?string $after = null,
        public ?bool $disable = false,
        public ?string $placeholder = null,
    ) {
        $this->options();

        if (isset($this->options[0]) && (is_array($this->options[0]) && ! $this->select)) {
            throw new InvalidArgumentException('The [select] parameter must be defined.');
        }

        $this->placeholder ??= __('tallstack-ui::messages.select.placeholder');

        throw_if(blank($this->placeholder), new Exception('The [placeholder] cannot be empty.'));
    }

    public function personalization(): array
    {
        return [
            'item' => 'inline-flex items-center rounded-lg bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 space-x-1 dark:text-dark-100 dark:bg-dark-700',
            'icon' => 'h-4 w-4 text-red-500',
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.select.styled');
    }
}
