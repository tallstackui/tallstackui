<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;
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
        return ['text' => 'mt-2 text-sm text-gray-500 dark:text-dark-400'];
    }
}
