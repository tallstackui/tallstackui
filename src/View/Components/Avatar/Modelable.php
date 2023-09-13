<?php

namespace TasteUi\View\Components\Avatar;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class Modelable extends Index
{
    /** @throws Throwable */
    public function __construct(
        public Model $model,
        public ?string $property = 'name',
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $square = null,
        public ?string $background = '0D8ABC',
        public ?string $color = 'FFFFFF',
    ) {
        $label = $this->avatar();

        parent::__construct(label: $label, sm: $sm, md: $md, lg: $lg, square: $square);
    }

    /** @throws Throwable */
    private function avatar(): string
    {
        $this->validate();

        return "https://ui-avatars.com/api/?name={$this->model->getAttribute($this->property)}&background={$this->background}&color={$this->color}";
    }

    /** @throws Throwable */
    private function validate(): void
    {
        throw_if(
            blank($this->model->getAttribute($this->property)),
            new Exception("The property {$this->property} does not exists or is blank")
        );
    }
}
