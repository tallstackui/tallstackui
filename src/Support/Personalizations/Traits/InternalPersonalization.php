<?php

namespace TallStackUi\Support\Personalizations\Traits;

use Exception;
use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Elements\Color;
use TallStackUi\View\Components\Alert;
use TallStackUi\View\Components\Avatar\Index as Avatar;
use TallStackUi\View\Components\Button\Circle as ButtonCircle;
use TallStackUi\View\Components\Button\Index as Button;
use TallStackUi\View\Components\Button\Traits\DefaultButtonColorClasses;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Input;
use TallStackUi\View\Components\Form\Password;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Textarea;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Components\Form\Traits\DefaultSelectablesColorClasses;

trait InternalPersonalization
{
    use DefaultButtonColorClasses;
    use DefaultInputClasses;
    use DefaultSelectablesColorClasses;

    /** @throws Exception */
    public function internals(): array
    {
        $internal = (match (static::class) {
            Alert::class => fn () => $this->alert(),
            Avatar::class => fn () => $this->avatar(),
            Button::class, ButtonCircle::class => fn () => $this->button(),
            Input::class => fn () => $this->input(),
            Password::class => fn () => $this->password(),
            Textarea::class => fn () => $this->textarea(),
            Radio::class, Checkbox::class => fn () => $this->radio(),
            Toggle::class => fn () => $this->toggle(),
            default => throw new Exception('Unexpected match value'),
        })();

        return [...$internal];
    }

    private function alert(): array
    {
        $weight = $this->color === 'black' || $this->color === 'white' ? null : 900;
        $color = TallStackUi::colors()
            ->when($this->style === 'solid', fn (Color $color) => $color->set('text', $this->color === 'black' ? 'white' : ($this->color === 'white' ? 'black' : $this->color), $weight))
            ->unless($this->style === 'solid', fn (Color $color) => $color->set('text', $this->color === 'white' ? 'black' : $this->color, $weight))
            ->get();

        return [
            'wrapper.color' => Arr::toCssClasses([
                TallStackUi::colors()
                    ->when($this->style === 'solid', fn (Color $color) => $color->set('bg', $this->color, ! in_array($this->color, ['white', 'black']) ? 300 : null))
                    ->unless($this->style === 'solid', fn (Color $color) => $color->set('bg', $this->color === 'black' ? 'neutral' : $this->color, $this->color === 'black' ? 200 : 100))
                    ->get(),
                'border' => $this->color === 'white',
            ]),
            'title.base.color' => Arr::toCssClasses([$color => $this->title !== null]),
            'title.icon.color' => $color,
            'text.title.wrapper.color' => $color,
            'text.title.icon.color' => $color,
            'icon.color' => Arr::toCssClasses([
                TallStackUi::colors()
                    ->set('text', $this->color === 'black' ? 'white' : $this->color, $this->color === 'black' ? null : 500)
                    ->get() => $this->color !== 'white',
            ]),
        ];
    }

    private function avatar(): array
    {
        return [
            'wrapper.color' => Arr::toCssClasses([
                TallStackUi::colors()
                    ->set('bg', $this->color, $this->color === 'black' ? null : 500)
                    ->merge('border', $this->color, $this->color === 'black' ? null : 500)
                    ->get() => ! $this->modelable,
            ]),
        ];
    }

    private function button(): array
    {
        return $this->tallStackUiButtonsColors();
    }

    private function input(): array
    {
        return [
            'input.icon' => Arr::toCssClasses([
                'pl-10' => $this->icon && ($this->position === null || $this->position === 'left'),
                'pr-10' => $this->icon && $this->position === 'right',
            ]),
            'input.round' => Arr::toCssClasses([
                'rounded-md' => ! $this->square && ! $this->round,
                'rounded-full' => $this->round,
            ]),
        ];
    }

    private function password(): array
    {
        return [
            'input.icon' => 'pr-10',
            'input.round' => Arr::toCssClasses([
                'rounded-md' => ! $this->square && ! $this->round,
                'rounded-full' => $this->round,
            ]),
        ];
    }

    private function radio(): array
    {
        return ['input.color' => $this->tallStackUiRadioCheckboxColors()];
    }

    private function textarea(): array
    {
        return ['input.round' => Arr::toCssClasses(['rounded-md' => ! $this->square])];
    }

    private function toggle(): array
    {
        return [
            'wrapper.color' => TallStackUi::colors()
                ->clean(false)
                ->when($this->color === 'white', function (Color $color) {
                    return $color->set('peer-checked:bg', 'gray', 300)
                        ->set('peer-focus:ring', 'gray', 300)
                        ->set('group-focus:ring', 'gray', 300);
                })
                ->unless($this->color === 'white', function (Color $color) {
                    return $color->set('peer-checked:bg', $this->color, $this->color === 'black' ? null : 600)
                        ->set('peer-focus:ring', $this->color, $this->color === 'black' ? null : 600)
                        ->set('group-focus:ring', $this->color, $this->color === 'black' ? null : 600);
                })
                ->get(),
        ];
    }
}
