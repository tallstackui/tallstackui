<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('form.hint')]
class Hint extends BaseComponent implements Personalization
{
    public function __construct(public ?string $hint = null)
    {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.hint');
    }

    public function personalization(): array
    {
        return ['text' => 'dark:text-dark-400 mt-1 block text-sm text-gray-500'];
    }
}
