<?php

namespace TallStackUi\Support\Personalizations\Traits;

use Exception;
use Illuminate\Support\Arr;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Elements\Color;
use TallStackUi\View\Components\Alert;
use TallStackUi\View\Components\Avatar\Index as Avatar;
use TallStackUi\View\Components\Avatar\Modelable;
use TallStackUi\View\Components\Badge;
use TallStackUi\View\Components\Button\Circle as ButtonCircle;
use TallStackUi\View\Components\Button\Index as Button;
use TallStackUi\View\Components\Button\Traits\DefaultButtonColorClasses;
use TallStackUi\View\Components\Errors;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Input;
use TallStackUi\View\Components\Form\Password;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Textarea;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Components\Tooltip;

/**
 * The purpose of this trait is to prepare internal content to be applied to
 * components even when the components are personalized. In other words, these
 * are classes outside the scope of component personalizations, even deep customization.
 */
trait InternalPersonalization
{
    use DefaultButtonColorClasses;
    use DefaultInputClasses;

    /** @throws Exception */
    public function internals(): array
    {
        $internal = (match (static::class) {
            Alert::class => fn () => $this->alert(),
            Avatar::class, Modelable::class => fn () => $this->avatar(),
            Badge::class => fn () => $this->badge(),
            Button::class, ButtonCircle::class => fn () => $this->button(),
            Errors::class => fn () => $this->errors(),
            Input::class => fn () => $this->input(),
            Password::class => fn () => $this->password(),
            Textarea::class => fn () => $this->textarea(),
            Radio::class, Checkbox::class => fn () => $this->radio(),
            Toggle::class => fn () => $this->toggle(),
            Tooltip::class => fn () => $this->tooltip(),
            default => throw new Exception('Unexpected match value'),
        })();

        return [...$internal];
    }

    /** Mandatory Alert Classes */
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
                'border border-gray-100' => $this->color === 'white',
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

    /** Mandatory Avatar Classes */
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

    /** Mandatory Badge Classes */
    private function badge(): array
    {
        return [
            'wrapper.color' => Arr::toCssClasses([
                TallStackUi::colors()
                    ->set('border', $this->color, $this->color === 'black' ? null : 500)
                    ->mergeWhen($this->style === 'solid', 'bg', $this->color, $this->color === 'black' ? null : 500)
                    ->get(),
                TallStackUi::colors()
                    ->set('text', $this->color === 'white' ? 'black' : $this->color, $this->color === 'white' ? null : 500)
                    ->get() => $this->style === 'outline',
            ]),
            'icon.color' => Arr::toCssClasses([
                'text-white' => $this->color !== 'white' && $this->style === 'solid',
                TallStackUi::colors()
                    ->set('text', $this->color, 500)
                    ->get() => $this->style === 'outline',
            ]),
        ];
    }

    /** Mandatory Buttons Classes */
    private function button(): array
    {
        return $this->tallStackUiButtonsColors();
    }

    /** Mandatory Errors Classes */
    private function errors(): array
    {
        $text = TallStackUi::colors()
            ->when($this->color === 'black', fn (Color $color) => $color->set('text', 'neutral', 800))
            ->unless($this->color === 'black', fn (Color $color) => $color->set('text', $this->color, 800))
            ->get();

        return [
            'wrapper.second.color' => TallStackUi::colors()
                ->when($this->color === 'white', fn (Color $color) => $color->set('bg', 'gray', 50))
                ->unless($this->color === 'white', fn (Color $color) => $color->set('bg', $this->color === 'black' ? 'neutral' : $this->color, 50))
                ->get(),
            'title.text.color' => $text,
            'title.wrapper.color' => TallStackUi::colors()
                ->set('border', $this->color, 200)
                ->get(),
            'body.list.color' => $text,
        ];
    }

    /** Mandatory Input Classes */
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

    /** Mandatory Input Password Classes */
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

    /** Mandatory Radio & Checkbox Classes */
    private function radio(): array
    {
        return ['input.color' => $this->radioColors()];
    }

    /** Mandatory Textarea Classes */
    private function textarea(): array
    {
        return ['input.round' => Arr::toCssClasses(['rounded-md' => ! $this->square])];
    }

    /** Mandatory Toogle Classes */
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

    /** Mandatory Tooltip Classes */
    private function tooltip(): array
    {
        return [
            'icon.color' => TallStackUi::colors()
                ->when($this->color === 'white', fn (Color $color) => $color->set('text', 'gray', 300))
                ->unless($this->color === 'white', fn (Color $color) => $color->set('text', $this->color, 500))
                ->get(),
        ];
    }
}
