<?php

namespace TallStackUi\Components\Avatar\Group;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('avatar.group')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(public ?bool $reverse = false)
    {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.avatar.group');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'base' => 'inline-flex -space-x-2',
                'reverse' => '[&>*]:relative [&>*:nth-child(1)]:z-50 [&>*:nth-child(2)]:z-40 [&>*:nth-child(3)]:z-30 [&>*:nth-child(4)]:z-20 [&>*:nth-child(5)]:z-10 [&>*:nth-child(n+6)]:z-0',
            ],
        ]);
    }
}
