<?php

namespace TallStackUi\Customization;

use RuntimeException;
use TallStackUi\Components\Accordion\Items\Component as AccordionItems;
use TallStackUi\Components\Accordion\Main\Component as Accordion;
use TallStackUi\Components\Alert\Component as Alert;
use TallStackUi\Components\Avatar\Component as Avatar;
use TallStackUi\Components\BackToTop\Component as BackToTop;
use TallStackUi\Components\Badge\Component as Badge;
use TallStackUi\Components\Banner\Component as Banner;
use TallStackUi\Components\Boolean\Component as Boolean;
use TallStackUi\Components\Breadcrumbs\Component as Breadcrumbs;
use TallStackUi\Components\Button\Circle\Component as Circle;
use TallStackUi\Components\Button\Group\Component as ButtonGroup;
use TallStackUi\Components\Button\Normal\Component as Button;
use TallStackUi\Components\Calendar\Component as Calendar;
use TallStackUi\Components\Card\Component as Card;
use TallStackUi\Components\Carousel\Component as Carousel;
use TallStackUi\Components\Clipboard\Component as Clipboard;
use TallStackUi\Components\CommandPalette\Component as CommandPalette;
use TallStackUi\Components\Dial\Items\Component as DialItems;
use TallStackUi\Components\Dial\Main\Component as Dial;
use TallStackUi\Components\Dialog\Component as Dialog;
use TallStackUi\Components\Dropdown\Items\Component as DropdownItems;
use TallStackUi\Components\Dropdown\Main\Component as Dropdown;
use TallStackUi\Components\Dropdown\Submenu\Component as DropdownSubmenu;
use TallStackUi\Components\Environment\Component as Environment;
use TallStackUi\Components\Errors\Component as Errors;
use TallStackUi\Components\Floating\Component as Floating;
use TallStackUi\Components\Form\Autocomplete\Component as Autocomplete;
use TallStackUi\Components\Form\Checkbox\Component as Checkbox;
use TallStackUi\Components\Form\Color\Component as Color;
use TallStackUi\Components\Form\Currency\Component as Currency;
use TallStackUi\Components\Form\Date\Component as Date;
use TallStackUi\Components\Form\Error\Component as Error;
use TallStackUi\Components\Form\Hint\Component as Hint;
use TallStackUi\Components\Form\Input\Component as Input;
use TallStackUi\Components\Form\InputSelect\Component as InputSelect;
use TallStackUi\Components\Form\Label\Component as Label;
use TallStackUi\Components\Form\Number\Component as Number;
use TallStackUi\Components\Form\Password\Component as Password;
use TallStackUi\Components\Form\Pin\Component as Pin;
use TallStackUi\Components\Form\Radio\Component as Radio;
use TallStackUi\Components\Form\Range\Component as Range;
use TallStackUi\Components\Form\Select\Native\Component as SelectNative;
use TallStackUi\Components\Form\Select\Styled\Component as SelectStyled;
use TallStackUi\Components\Form\Tag\Component as Tag;
use TallStackUi\Components\Form\Textarea\Component as Textarea;
use TallStackUi\Components\Form\Time\Component as Time;
use TallStackUi\Components\Form\Toggle\Component as Toggle;
use TallStackUi\Components\Form\Upload\Component as Upload;
use TallStackUi\Components\Kbd\Component as Kbd;
use TallStackUi\Components\KeyValue\Component as KeyValue;
use TallStackUi\Components\Layout\Header\Component as Header;
use TallStackUi\Components\Layout\Main\Component as Layout;
use TallStackUi\Components\Layout\SideBar\Item\Component as SideBarItem;
use TallStackUi\Components\Layout\SideBar\Main\Component as SideBar;
use TallStackUi\Components\Layout\SideBar\Separator\Component as Separator;
use TallStackUi\Components\Link\Component as Link;
use TallStackUi\Components\List\Items\Component as ListItems;
use TallStackUi\Components\List\Main\Component as TsList;
use TallStackUi\Components\Loading\Component as Loading;
use TallStackUi\Components\Modal\Component as Modal;
use TallStackUi\Components\Progress\Bar\Component as Progress;
use TallStackUi\Components\Progress\Circle\Component as ProgressCircle;
use TallStackUi\Components\Rating\Component as Rating;
use TallStackUi\Components\Reaction\Component as Reaction;
use TallStackUi\Components\Signature\Component as Signature;
use TallStackUi\Components\Slide\Component as Slide;
use TallStackUi\Components\Stats\Component as Stats;
use TallStackUi\Components\Step\Main\Component as Step;
use TallStackUi\Components\Tab\Main\Component as Tab;
use TallStackUi\Components\Table\Component as Table;
use TallStackUi\Components\ThemeSwitch\Component as ThemeSwitch;
use TallStackUi\Components\Timeline\Items\Component as TimelineItems;
use TallStackUi\Components\Timeline\Main\Component as Timeline;
use TallStackUi\Components\Toast\Component as Toast;
use TallStackUi\Components\Tooltip\Component as Tooltip;
use TallStackUi\Components\Wrapper\Input\Component as InputWrapper;
use TallStackUi\Components\Wrapper\Radio\Component as RadioWrapper;

/**
 * @internal
 */
class Customization
{
    public function __construct(public ?string $component = null, public ?string $scope = null)
    {
        //
    }

    public function accordion(?string $component = null, ?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        $component ??= 'accordion';

        $class = match ($component) {
            'accordion' => Accordion::class,
            'items' => AccordionItems::class,
            default => $component,
        };

        return $this->component($class);
    }

    public function alert(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Alert::class);
    }

    public function avatar(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Avatar::class);
    }

    public function backToTop(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(BackToTop::class);
    }

    public function badge(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Badge::class);
    }

    public function banner(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Banner::class);
    }

    /**
     * Interact with the block to start the personalization.
     */
    public function block(string|array $name, string|callable|null $code = null): CustomizationFactory
    {
        return $this->forward()->block($name, $code);
    }

    public function boolean(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Boolean::class);
    }

    public function breadcrumbs(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Breadcrumbs::class);
    }

    public function button(?string $component = null, ?string $scope = null): CustomizationFactory
    {
        $component ??= 'button';

        $this->scope ??= $scope;

        $class = match ($component) {
            'button' => Button::class,
            'circle' => Circle::class,
            'group' => ButtonGroup::class,
            default => $component,
        };

        return $this->component($class);
    }

    public function calendar(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Calendar::class);
    }

    public function card(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Card::class);
    }

    public function carousel(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Carousel::class);
    }

    public function clipboard(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Clipboard::class);
    }

    public function commandPalette(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(CommandPalette::class);
    }

    public function dial(?string $component = null, ?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        $component ??= 'dial';

        $class = match ($component) {
            'dial' => Dial::class,
            'items' => DialItems::class,
            default => $component,
        };

        return $this->component($class);
    }

    public function dialog(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Dialog::class);
    }

    public function dropdown(?string $component = null, ?string $scope = null): CustomizationFactory
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

    public function environment(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Environment::class);
    }

    public function errors(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Errors::class);
    }

    public function floating(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Floating::class);
    }

    public function form(?string $component = null, ?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        $component ??= 'input';

        $class = match ($component) {
            'autocomplete' => Autocomplete::class,
            'checkbox' => Checkbox::class,
            'color' => Color::class,
            'currency' => Currency::class,
            'date' => Date::class,
            'error' => Error::class,
            'hint' => Hint::class,
            'input' => Input::class,
            'input.select' => InputSelect::class,
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
     * as "form('input')", where in this example 'form' would be
     * the method and 'input' the parameter to be injected into the method.
     */
    public function forward(): CustomizationFactory
    {
        if (! $this->component) {
            throw new RuntimeException('No component has been set');
        }

        if (str_contains($this->component, 'ts-ui::customization')) {
            $this->component = str_replace('ts-ui::customization.', '', $this->component);
        }

        // This is necessary for cases where personalization aims to
        // manipulate components like form.number. We explode to get
        // the namespace - form, and the component - number. For deeper
        // keys like form.input.select, we join the remaining parts.
        $parts = explode('.', $this->component);

        $main = $parts[0];
        $secondary = count($parts) > 2
            ? implode('.', array_slice($parts, 1))
            : ($parts[1] ?? null);

        if (! method_exists($this, $main)) {
            throw new RuntimeException("The method [{$main}] is not supported");
        }

        return call_user_func([$this, $main], $main === $secondary ?: $secondary);
    }

    public function globals(): Globals
    {
        return new Globals;
    }

    public function kbd(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Kbd::class);
    }

    public function keyValue(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(KeyValue::class);
    }

    public function layout(?string $component = null, ?string $scope = null): CustomizationFactory
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

    public function link(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Link::class);
    }

    public function list(?string $component = null, ?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        $component ??= 'list';

        $class = match ($component) {
            'list' => TsList::class,
            'items' => ListItems::class,
            default => $component,
        };

        return $this->component($class);
    }

    public function loading(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Loading::class);
    }

    public function modal(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Modal::class);
    }

    public function progress(?string $component = null, ?string $scope = null): CustomizationFactory
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

    public function rating(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Rating::class);
    }

    public function reaction(?string $scope = null): CustomizationFactory
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

    public function select(?string $component = null, ?string $scope = null): CustomizationFactory
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

    public function sideBar(?string $component = null, ?string $scope = null): CustomizationFactory
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

    public function signature(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Signature::class);
    }

    public function slide(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Slide::class);
    }

    public function stats(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Stats::class);
    }

    public function step(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Step::class);
    }

    public function tab(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Tab::class);
    }

    public function table(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Table::class);
    }

    public function themeSwitch(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(ThemeSwitch::class);
    }

    public function timeline(?string $component = null, ?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        $component ??= 'timeline';

        $class = match ($component) {
            'timeline' => Timeline::class,
            'items' => TimelineItems::class,
            default => $component,
        };

        return $this->component($class);
    }

    public function toast(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Toast::class);
    }

    public function tooltip(?string $scope = null): CustomizationFactory
    {
        $this->scope ??= $scope;

        return $this->component(Tooltip::class);
    }

    public function wrapper(?string $component = null, ?string $scope = null): CustomizationFactory
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
     */
    private function component(string $class): string|CustomizationFactory
    {
        $component = __ts_search_component($class);

        // This is the strategy adopted for scope personalization. We create a temporary
        // key in the Laravel container, and instead of returning the same instance - which
        // would normally happen, as in v1, we return a new instance of PersonalizationResources.
        if (($scope = $this->scope) !== null) {
            $this->scope = null; // Resetting the scope to avoid infinite recursion.

            $instance = new CustomizationFactory($class, scope: $scope);

            app()->singleton(__ts_scope_container_key($component, $scope), fn () => $instance);

            return $instance;
        }

        return app($component);
    }
}
