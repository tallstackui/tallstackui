<?php

namespace TasteUi\Support;

use Closure;
use InvalidArgumentException;
use TasteUi\Contracts\Personalizable as PersonalizableClass;
use TasteUi\Support\Personalizations\Components\Alert;
use TasteUi\Support\Personalizations\Components\Avatar;
use TasteUi\Support\Personalizations\Components\Badge;
use TasteUi\Support\Personalizations\Components\Button\Circle;
use TasteUi\Support\Personalizations\Components\Button\Index;
use TasteUi\Support\Personalizations\Components\Card;
use TasteUi\Support\Personalizations\Components\Error;
use TasteUi\Support\Personalizations\Components\Errors;
use TasteUi\Support\Personalizations\Components\Form\Checkbox;
use TasteUi\Support\Personalizations\Components\Form\Input;
use TasteUi\Support\Personalizations\Components\Form\Label;
use TasteUi\Support\Personalizations\Components\Form\Password;
use TasteUi\Support\Personalizations\Components\Form\Radio;
use TasteUi\Support\Personalizations\Components\Form\Textarea;
use TasteUi\Support\Personalizations\Components\Form\Toggle;
use TasteUi\Support\Personalizations\Components\Hint;
use TasteUi\Support\Personalizations\Components\Interactions\Dialog;
use TasteUi\Support\Personalizations\Components\Interactions\Toast;
use TasteUi\Support\Personalizations\Components\Modal;
use TasteUi\Support\Personalizations\Components\Select\Searchable;
use TasteUi\Support\Personalizations\Components\Select\Select;
use TasteUi\Support\Personalizations\Components\Select\Styled;
use TasteUi\Support\Personalizations\Components\Tabs\Index as TabWrapper;
use TasteUi\Support\Personalizations\Components\Tabs\Item as TabItem;
use TasteUi\Support\Personalizations\Components\Tooltip;
use TasteUi\Support\Personalizations\Components\Wrapper\Input as InputWrapper;
use TasteUi\Support\Personalizations\Components\Wrapper\Radio as RadioWrapper;
use TasteUi\Support\Personalizations\Components\Wrapper\Select as SelectWrapper;
use TasteUi\Support\Personalizations\Contracts\Personalizable as PersonalizableContract;

/**
 * @method PersonalizableContract alert()
 * @method PersonalizableContract avatar()
 * @method PersonalizableContract badge()
 * @method PersonalizableContract button(string $component)
 * @method PersonalizableContract card()
 * @method PersonalizableContract dialog()
 * @method PersonalizableContract error()
 * @method PersonalizableContract errors()
 * @method PersonalizableContract hint()
 * @method PersonalizableContract modal()
 * @method PersonalizableContract select(string $component)
 * @method PersonalizableContract tabs(string $component)
 * @method PersonalizableContract toast()
 * @method PersonalizableContract tooltip()
 * @method PersonalizableContract wrapper(string $component)
 */
final class Personalization
{
    public const PERSONALIZABLES = [
        'taste-ui::personalizations.alert' => Alert::class,
        'taste-ui::personalizations.avatar' => Avatar::class,
        'taste-ui::personalizations.badge' => Badge::class,
        'taste-ui::personalizations.button' => Index::class,
        'taste-ui::personalizations.button.circle' => Circle::class,
        'taste-ui::personalizations.card' => Card::class,
        'taste-ui::personalizations.dialog' => Dialog::class,
        'taste-ui::personalizations.error' => Error::class,
        'taste-ui::personalizations.errors' => Errors::class,
        'taste-ui::personalizations.form.input' => Input::class,
        'taste-ui::personalizations.form.label' => Label::class,
        'taste-ui::personalizations.form.password' => Password::class,
        'taste-ui::personalizations.form.checkbox' => Checkbox::class,
        'taste-ui::personalizations.form.radio' => Radio::class,
        'taste-ui::personalizations.form.textarea' => Textarea::class,
        'taste-ui::personalizations.form.toggle' => Toggle::class,
        'taste-ui::personalizations.hint' => Hint::class,
        'taste-ui::personalizations.modal' => Modal::class,
        'taste-ui::personalizations.select' => Select::class,
        'taste-ui::personalizations.select.searchable' => Searchable::class,
        'taste-ui::personalizations.select.styled' => Styled::class,
        'taste-ui::personalizations.tabs' => TabWrapper::class,
        'taste-ui::personalizations.tabs.item' => TabItem::class,
        'taste-ui::personalizations.toast' => Toast::class,
        'taste-ui::personalizations.tooltip' => Tooltip::class,
        'taste-ui::personalizations.wrapper.input' => InputWrapper::class,
        'taste-ui::personalizations.wrapper.radio' => RadioWrapper::class,
        'taste-ui::personalizations.wrapper.select' => SelectWrapper::class,
    ];

    public function __construct(
        public ?string $component = null
    ) {
        //
    }

    public function __call(string $main, array $secondary): PersonalizableContract
    {
        $component = $this->key($main, $secondary);

        $this->validate($component);

        return app($component);
    }

    public function block(string|array $name, string|Closure|PersonalizableClass $code = null): PersonalizableContract
    {
        return $this->instance()->block($name, $code);
    }

    public function instance(): PersonalizableContract
    {
        if (! $this->component) {
            throw new InvalidArgumentException('No component has been set');
        }

        $parts = explode('.', $this->component);
        $main = $parts[0];
        $secondary = $parts[1] ?? null;

        $component = $this->key($main, $main === $secondary ?: $secondary);

        $this->validate($component);

        return app($component);
    }

    public function validate(string $component): void
    {
        if (! array_key_exists($component, self::PERSONALIZABLES)) {
            throw new InvalidArgumentException("Component [{$component}] is not allowed to be personalized");
        }
    }

    private function key(string $main, string|array $secondary = null): string
    {
        $key = "taste-ui::personalizations.$main";

        if (is_string($secondary)) {
            $key .= ".$secondary";
        } elseif (is_array($secondary)) {
            $key .= '.'.implode('.', $secondary);
        }

        return rtrim($key, '.');
    }
}
