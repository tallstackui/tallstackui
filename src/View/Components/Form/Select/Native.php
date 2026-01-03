<?php

namespace TallStackUi\View\Components\Form\Select;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\SelectNativeRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Form\Select\Traits\Setup;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftCustomization('select.native')]
#[PassThroughRuntime(SelectNativeRuntime::class)]
class Native extends TallStackUiComponent implements Customization
{
    use DefaultInputClasses;
    use Setup;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public Collection|array $options = [],
        public ?string $select = null,
        public ?array $selectable = [],
        public ?bool $invalidate = null,
        public ?bool $grouped = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.select.native');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'relative',
            'input' => [...$this->input()],
            'error' => $this->error('focus:ring-2'),
        ]);
    }
}
