<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

#[SoftPersonalization('form.textarea')]
class Textarea extends BaseComponent implements Personalization
{
    use DefaultInputClasses;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $resize = false,
        public ?bool $resizeAuto = false,
        public ?bool $invalidate = null,
        public ?bool $count = false,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.form.textarea');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'input' => [...$this->input()],
            'error' => $this->error(),
            'count' => [
                'base' => 'dark:text-dark-400 absolute right-0 mt-1 text-sm text-gray-500',
                'max' => 'font-semibold text-red-500 dark:text-red-500',
            ],
        ]);
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if ($this->attributes->has('rows') && $this->resizeAuto) {
            throw new InvalidArgumentException('The textarea cannot be used with [rows] and [resize-auto] at the same time because the rows will have no effect since resizing is automatic.');
        }
    }
}
