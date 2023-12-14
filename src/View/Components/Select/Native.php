<?php

namespace TallStackUi\View\Components\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Components\Select\Traits\InteractsWithSelectOptions;

#[SoftPersonalization('select.native')]
class Native extends BaseComponent implements Personalization
{
    use DefaultInputClasses;
    use InteractsWithSelectOptions;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public Collection|array $options = [],
        public ?string $select = null,
        public ?array $selectable = [],
    ) {
        $this->options();
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.select.native');
    }

    public function personalization(): array
    {
        // TODO : Deal with bg-transparent
        return Arr::dot([
            'input' => [
                'class' => [...$this->input()],
            ],
            'error' => $this->error('focus:ring-2'),
        ]);
    }

    protected function validate(): void
    {
        if (is_array($this->options[0]) && ! $this->select) {
            throw new InvalidArgumentException('The [select] parameter must be defined');
        }
    }
}
