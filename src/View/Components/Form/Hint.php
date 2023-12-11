<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;

#[SoftPersonalization('form.hint')]
class Hint extends Component implements Personalization
{
    public function __construct()
    {
        //
    }

    public function personalization(): array
    {
        return ['text' => 'mt-2 text-sm text-gray-500 dark:text-dark-400'];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.form.hint');
    }
}
