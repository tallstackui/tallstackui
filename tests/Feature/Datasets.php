<?php

use TallStackUi\Support\Personalizations\Components\Alert;
use TallStackUi\Support\Personalizations\Components\Avatar;
use TallStackUi\Support\Personalizations\Components\Badge;
use TallStackUi\Support\Personalizations\Components\Button\Circle;
use TallStackUi\Support\Personalizations\Components\Button\Index;
use TallStackUi\Support\Personalizations\Components\Card;
use TallStackUi\Support\Personalizations\Components\Error;
use TallStackUi\Support\Personalizations\Components\Errors;
use TallStackUi\Support\Personalizations\Components\Form\Checkbox;
use TallStackUi\Support\Personalizations\Components\Form\Input;
use TallStackUi\Support\Personalizations\Components\Form\Label;
use TallStackUi\Support\Personalizations\Components\Form\Password;
use TallStackUi\Support\Personalizations\Components\Form\Radio;
use TallStackUi\Support\Personalizations\Components\Form\Textarea;
use TallStackUi\Support\Personalizations\Components\Form\Toggle;
use TallStackUi\Support\Personalizations\Components\Hint;
use TallStackUi\Support\Personalizations\Components\Interactions\Dialog;
use TallStackUi\Support\Personalizations\Components\Interactions\Toast;
use TallStackUi\Support\Personalizations\Components\Modal;
use TallStackUi\Support\Personalizations\Components\Select\Searchable;
use TallStackUi\Support\Personalizations\Components\Select\Select;
use TallStackUi\Support\Personalizations\Components\Select\Styled;
use TallStackUi\Support\Personalizations\Components\Tabs\Index as TabWrapper;
use TallStackUi\Support\Personalizations\Components\Tabs\Items as TabItem;
use TallStackUi\Support\Personalizations\Components\Tooltip;
use TallStackUi\Support\Personalizations\Components\Wrapper\Input as InputWrapper;
use TallStackUi\Support\Personalizations\Components\Wrapper\Radio as RadioWrapper;
use TallStackUi\Support\Personalizations\Components\Wrapper\Select as SelectWrapper;

dataset('personalizations.keys', [
    'tallstack-ui::personalizations.alert',
    'tallstack-ui::personalizations.avatar',
    'tallstack-ui::personalizations.badge',
    'tallstack-ui::personalizations.button',
    'tallstack-ui::personalizations.button.circle',
    'tallstack-ui::personalizations.card',
    'tallstack-ui::personalizations.dialog',
    'tallstack-ui::personalizations.error',
    'tallstack-ui::personalizations.errors',
    'tallstack-ui::personalizations.form.input',
    'tallstack-ui::personalizations.form.label',
    'tallstack-ui::personalizations.form.password',
    'tallstack-ui::personalizations.form.checkbox',
    'tallstack-ui::personalizations.form.radio',
    'tallstack-ui::personalizations.form.textarea',
    'tallstack-ui::personalizations.form.toggle',
    'tallstack-ui::personalizations.hint',
    'tallstack-ui::personalizations.modal',
    'tallstack-ui::personalizations.select',
    'tallstack-ui::personalizations.select.searchable',
    'tallstack-ui::personalizations.select.styled',
    'tallstack-ui::personalizations.toast',
    'tallstack-ui::personalizations.tooltip',
    'tallstack-ui::personalizations.wrapper.input',
    'tallstack-ui::personalizations.wrapper.radio',
    'tallstack-ui::personalizations.wrapper.select',
]);

dataset('personalizations.classes', [
    Alert::class,
    Avatar::class,
    Badge::class,
    Index::class,
    Circle::class,
    Card::class,
    Dialog::class,
    Error::class,
    Errors::class,
    Input::class,
    Label::class,
    Password::class,
    Checkbox::class,
    Radio::class,
    Textarea::class,
    Toggle::class,
    Hint::class,
    Modal::class,
    Select::class,
    Searchable::class,
    Styled::class,
    TabWrapper::class,
    TabItem::class,
    Toast::class,
    Tooltip::class,
    InputWrapper::class,
    RadioWrapper::class,
    SelectWrapper::class,
]);

dataset('components', [
    TallStackUi\View\Components\Form\Input::class,
    TallStackUi\View\Components\Form\Textarea::class,
    TallStackUi\View\Components\Form\Password::class,
    TallStackUi\View\Components\Form\Toggle::class,
    TallStackUi\View\Components\Form\Radio::class,
    TallStackUi\View\Components\Form\Checkbox::class,
    TallStackUi\View\Components\Form\Label::class,
    TallStackUi\View\Components\Alert::class,
    TallStackUi\View\Components\Card::class,
    TallStackUi\View\Components\Badge::class,
    TallStackUi\View\Components\Avatar\Index::class,
    TallStackUi\View\Components\Avatar\Modelable::class,
    TallStackUi\View\Components\Hint::class,
    TallStackUi\View\Components\Error::class,
    TallStackUi\View\Components\Errors::class,
    TallStackUi\View\Components\Tabs\Index::class,
    TallStackUi\View\Components\Tabs\Items::class,
    TallStackUi\View\Components\Tooltip::class,
    TallStackUi\View\Components\Button\Index::class,
    TallStackUi\View\Components\Button\Circle::class,
    TallStackUi\View\Components\Select\Select::class,
    TallStackUi\View\Components\Select\Styled::class,
    TallStackUi\View\Components\Select\Searchable::class,
    TallStackUi\View\Components\Modal::class,
    TallStackUi\View\Components\Interaction\Toast::class,
    TallStackUi\View\Components\Interaction\Dialog::class,
    TallStackUi\View\Components\Wrapper\Input::class,
    TallStackUi\View\Components\Wrapper\Radio::class,
    TallStackUi\View\Components\Wrapper\Select::class,
]);
