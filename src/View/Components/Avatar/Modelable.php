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
        public ?string $background = '0D8ABC',
        public ?string $color = 'FFFFFF',
        public bool $square = false,
    ) {
        parent::__construct(
            label: $this->avatar(),
            sm: $sm,
            md: $md,
            lg: $lg,
            square: $square,
            modelable: true
        );
    }

    /** @throws Throwable */
    private function avatar(): string
    {
        throw_if(
            blank($this->model->getAttribute($this->property)),
            new Exception("The property {$this->property} does not exists or is blank")
        );

        return "https://ui-avatars.com/api/?name={$this->model->getAttribute($this->property)}&background={$this->background}&color={$this->color}";
    }

    public function alt(): mixed
    {
        return $this->model->getAttribute($this->property);
    }
}
