<?php

namespace TallStackUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

abstract class BaseComponent extends Component
{
    abstract public function blade(): View;

    public function render(): Closure
    {
        return function (array $data) {
            $this->before($data);

            return $this->blade()
                ->with($this->compile($data));
        };
    }

    private function before(array $data): void
    {

    }

    private function compile(array $data): array
    {


        return [...$data];
    }
}
