<?php

namespace TallStackUi\View\Components\Step;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('step')]
class Step extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $selected = null, 
        public ?string $id = null,
        public ?bool $helpers = false,
        public ?bool $finish = true,
        public ?bool $panels = true,
        public ?bool $circles = false,
        public ?bool $simple = false,
        public ?string $variation = null,
    ) {
        $this->id ??= uniqid();
        $this->variation = $this->simple ? 'simple' : ($this->circles ? 'circles' : 'panels');
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.step.step');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'panels' => 'divide-y divide-gray-300 rounded-md border border-gray-300 dark:border-dark-700 dark:divide-dark-700 md:flex md:divide-y-0 overflow-auto soft-scrollbar',
                'simple' => 'space-y-4 md:flex md:space-x-8 md:space-y-0 overflow-auto soft-scrollbar pb-3',
                'circles' => 'relative flex flex-col md:flex-row',
            ],
            'content' => 'my-5',
        ]);
    }
}
