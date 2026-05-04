<?php

namespace TallStackUi\Components\Button\Group;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('button.group')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(public ?bool $vertical = false)
    {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.button.group');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'base' => 'isolate inline-flex shadow-xs [&>*]:relative [&>*:focus]:z-10',
                'layout' => [
                    'horizontal' => '[&>*]:rounded-none [&>*:first-child]:rounded-l-md [&>*:last-child]:rounded-r-md [&>*:not(:first-child)]:-ml-px',
                    'vertical' => 'flex-col [&>*]:rounded-none [&>*:first-child]:rounded-t-md [&>*:last-child]:rounded-b-md [&>*:not(:first-child)]:-mt-px',
                ],
            ],
        ]);
    }
}
