<?php

namespace TallStackUi\Components\Form\Select\Native;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\FormDefaultInputClasses;
use TallStackUi\Components\Traits\SelectSetup;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\SelectNativeRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('select.native')]
#[PassThroughRuntime(SelectNativeRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use FormDefaultInputClasses;
    use SelectSetup;

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
        return view('ts-ui::components.form.select.native');
    }

    public function customization(): array
    {
        // The select is the control and the surface at once, so the transparent
        // base would outrank the locked background, emitted earlier by Tailwind.
        $input = $this->input();
        $input['base'] = str_replace('bg-transparent ', '', $input['base']);

        return Arr::dot([
            'wrapper' => 'relative',
            'input' => [
                ...$input,
                'round' => [
                    'left' => 'rounded-r-none!',
                    'right' => 'rounded-l-none!',
                ],
                'borderless' => 'ring-0! focus:ring-0!',
            ],
            'error' => $this->error('focus:ring-2'),
        ]);
    }
}
