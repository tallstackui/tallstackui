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
    public function blade(): View
    {
        return view('ts-ui::components.avatar.group');
    }

    public function customization(): array
    {
        return Arr::dot(['wrapper' => 'flex -space-x-2']);
    }
}
