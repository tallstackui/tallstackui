<?php

namespace TallStackUi\Foundation\Personalization;

use Exception;
use RuntimeException;
use TallStackUi\View\Components\Alert;
use TallStackUi\View\Components\Avatar;
use TallStackUi\View\Components\Badge;
use TallStackUi\View\Components\Banner;
use TallStackUi\View\Components\Boolean;
use TallStackUi\View\Components\Button\Button;
use TallStackUi\View\Components\Button\Circle;
use TallStackUi\View\Components\Card;
use TallStackUi\View\Components\Carousel;
use TallStackUi\View\Components\Clipboard;
use TallStackUi\View\Components\Dropdown\Dropdown;
use TallStackUi\View\Components\Dropdown\Items as DropdownItems;
use TallStackUi\View\Components\Dropdown\Submenu as DropdownSubmenu;
use TallStackUi\View\Components\Environment;
use TallStackUi\View\Components\Errors;
use TallStackUi\View\Components\Floating;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Color;
use TallStackUi\View\Components\Form\Currency;
use TallStackUi\View\Components\Form\Date;
use TallStackUi\View\Components\Form\Error;
use TallStackUi\View\Components\Form\Hint;
use TallStackUi\View\Components\Form\Input;
use TallStackUi\View\Components\Form\Label;
use TallStackUi\View\Components\Form\Number;
use TallStackUi\View\Components\Form\Password;
use TallStackUi\View\Components\Form\Pin;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Range;
use TallStackUi\View\Components\Form\Select\Native as SelectNative;
use TallStackUi\View\Components\Form\Select\Styled as SelectStyled;
use TallStackUi\View\Components\Form\Tag;
use TallStackUi\View\Components\Form\Textarea;
use TallStackUi\View\Components\Form\Time;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Form\Upload;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\KeyValue;
use TallStackUi\View\Components\Layout\Header;
use TallStackUi\View\Components\Layout\Layout;
use TallStackUi\View\Components\Layout\SideBar\Item as SideBarItem;
use TallStackUi\View\Components\Layout\SideBar\Separator;
use TallStackUi\View\Components\Layout\SideBar\SideBar;
use TallStackUi\View\Components\Link;
use TallStackUi\View\Components\Loading;
use TallStackUi\View\Components\Modal;
use TallStackUi\View\Components\Progress\Circle as ProgressCircle;
use TallStackUi\View\Components\Progress\Progress;
use TallStackUi\View\Components\Rating;
use TallStackUi\View\Components\Reaction;
use TallStackUi\View\Components\Signature;
use TallStackUi\View\Components\Slide;
use TallStackUi\View\Components\Stats;
use TallStackUi\View\Components\Step\Step;
use TallStackUi\View\Components\Tab\Tab;
use TallStackUi\View\Components\Table;
use TallStackUi\View\Components\ThemeSwitch;
use TallStackUi\View\Components\Tooltip;
use TallStackUi\View\Components\Wrapper\Input as InputWrapper;
use TallStackUi\View\Components\Wrapper\Radio as RadioWrapper;

/**
 * @internal
 */
class Personalization
{
    public function __construct(public ?string $component = null, public ?string $scope = null)
    {
        //
    }

    public function alert(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Alert::class);
    }

    public function avatar(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Avatar::class);
    }

    public function badge(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Badge::class);
    }

    public function banner(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Banner::class);
    }

    /**
     * Interact with the block to start the personalization.
     */
    public function block(string|array $name, string|callable|null $code = null): PersonalizationFactory
    {
        return $this->forward()->block($name, $code);
    }

    public function boolean(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Boolean::class);
    }

    public function button(?string $component = null, ?string $scope = null): PersonalizationFactory
    {
        $component ??= 'button';

        $this->scope ??= $scope;

        $class = match ($component) {
            'button' => Button::class,
            'circle' => Circle::class,
            default => $component,
        };

        return $this->component($class);
    }

    public function card(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Card::class);
    }

    public function carousel(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Carousel::class);
    }

    public function clipboard(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Clipboard::class);
    }

    public function dialog(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Dialog::class);
    }

    public function dropdown(?string $component = null, ?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        $component ??= 'dropdown';

        $class = match ($component) {
            'dropdown' => Dropdown::class,
            'submenu' => DropdownSubmenu::class,
            'items' => DropdownItems::class,
            default => $component,
        };

        return $this->component($class);
    }

    public function environment(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Environment::class);
    }

    public function errors(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Errors::class);
    }

    public function floating(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Floating::class);
    }

    public function form(?string $component = null, ?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        $component ??= 'input';

        $class = match ($component) {
            'checkbox' => Checkbox::class,
            'color' => Color::class,
            'currency' => Currency::class,
            'date' => Date::class,
            'error' => Error::class,
            'hint' => Hint::class,
            'input' => Input::class,
            'label' => Label::class,
            'number' => Number::class,
            'upload' => Upload::class,
            'password' => Password::class,
            'pin' => Pin::class,
            'range' => Range::class,
            'radio' => Radio::class,
            'tag' => Tag::class,
            'textarea' => Textarea::class,
            'time' => Time::class,
            'toggle' => Toggle::class,
            default => $component,
        };

        return $this->component($class);
    }

    /**
     * The purpose of this function is to forward the execution of
     * the methods as they are called from auxiliary methods, such
     * as: "form('input')", where in this example 'form' would be
     * the method and 'input' the parameter to be injected into the method.
     */
    public function forward(): PersonalizationFactory
    {
        if (! $this->component) {
            throw new RuntimeException('No component has been set');
        }

        if (str_contains($this->component, 'tallstack-ui::personalizations')) {
            $this->component = str_replace('tallstack-ui::personalizations.', '', $this->component);
        }

        // This is necessary for cases where personalization aims to
        // manipulate components like form.number. We explode to get
        // the namespace - form, and the component - number.
        $parts = explode('.', $this->component);

        $main = $parts[0];
        $secondary = $parts[1] ?? null;

        if (! method_exists($this, $main)) {
            throw new RuntimeException("The method [{$main}] is not supported");
        }

        return call_user_func([$this, $main], $main === $secondary ?: $secondary);
    }

    public function keyValue(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(KeyValue::class);
    }

    public function layout(?string $component = null, ?string $scope = null): PersonalizationFactory
    {
        $component ??= 'index';

        $this->scope ??= $scope;

        $class = match ($component) {
            'index' => Layout::class,
            'header' => Header::class,
            default => $component,
        };

        return $this->component($class);
    }

    public function link(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Link::class);
    }

    public function loading(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Loading::class);
    }

    public function modal(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Modal::class);
    }

    public function progress(?string $component = null, ?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        $component ??= 'progress';

        $class = match ($component) {
            'progress' => Progress::class,
            'circle' => ProgressCircle::class,
            default => $component,
        };

        return $this->component($class);
    }

    public function rating(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Rating::class);
    }

    public function reaction(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Reaction::class);
    }

    /**
     * Set the scope for the personalization.
     *
     * @param  $name  string
     */
    public function scope(string $name): self
    {
        $this->scope = $name;

        return $this;
    }

    public function select(?string $component = null, ?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        $component ??= 'native';

        $class = match ($component) {
            'native' => SelectNative::class,
            'styled' => SelectStyled::class,
            default => $component,
        };

        return $this->component($class);
    }

    public function sideBar(?string $component = null, ?string $scope = null): PersonalizationFactory
    {
        $component ??= 'side-bar';

        $this->scope ??= $scope;

        $class = match ($component) {
            'side-bar' => SideBar::class,
            'item' => SideBarItem::class,
            'separator' => Separator::class,
            default => $component,
        };

        return $this->component($class);
    }

    public function signature(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Signature::class);
    }

    public function slide(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Slide::class);
    }

    public function stats(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Stats::class);
    }

    public function step(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Step::class);
    }

    public function tab(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Tab::class);
    }

    public function table(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Table::class);
    }

    public function themeSwitch(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(ThemeSwitch::class);
    }

    public function toast(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Toast::class);
    }

    public function tooltip(?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Tooltip::class);
    }

    public function wrapper(?string $component = null, ?string $scope = null): PersonalizationFactory
    {
        $this->scope ??= $scope;

        $component ??= 'input';

        $class = match ($component) {
            'input' => InputWrapper::class,
            'radio' => RadioWrapper::class,
            default => $component,
        };

        return $this->component($class);
    }

    /**
     * Searches for the component from the list of all components using the customization attribute.
     *
     * @throws Exception
     */
    private function component(string $class): string|PersonalizationFactory
    {
        $component = __ts_search_component($class);

        // This is the strategy adopted for scope personalization. We create a temporary
        // key in the Laravel container and instead of returning the same instance - which
        // would normally happen, as in v1, we return a new instance of PersonalizationResources.
        if (($scope = $this->scope) !== null) {
            $this->scope = null; // Resetting the scope to avoid infinite recursion.

            $instance = new PersonalizationFactory($class, scope: $scope);

            app()->singleton(__ts_scope_container_key($component, $scope), fn () => $instance);

            return $instance;
        }

        return app($component);
    }
}
