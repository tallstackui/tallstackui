<?php

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

dataset('personalizations.keys', [
    'taste-ui::personalizations.alert',
    'taste-ui::personalizations.avatar',
    'taste-ui::personalizations.badge',
    'taste-ui::personalizations.button',
    'taste-ui::personalizations.button.circle',
    'taste-ui::personalizations.card',
    'taste-ui::personalizations.dialog',
    'taste-ui::personalizations.error',
    'taste-ui::personalizations.errors',
    'taste-ui::personalizations.form.input',
    'taste-ui::personalizations.form.label',
    'taste-ui::personalizations.form.password',
    'taste-ui::personalizations.form.checkbox',
    'taste-ui::personalizations.form.radio',
    'taste-ui::personalizations.form.textarea',
    'taste-ui::personalizations.form.toggle',
    'taste-ui::personalizations.hint',
    'taste-ui::personalizations.modal',
    'taste-ui::personalizations.select',
    'taste-ui::personalizations.select.searchable',
    'taste-ui::personalizations.select.styled',
    'taste-ui::personalizations.toast',
    'taste-ui::personalizations.tooltip',
    'taste-ui::personalizations.wrapper.input',
    'taste-ui::personalizations.wrapper.radio',
    'taste-ui::personalizations.wrapper.select',
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
    TasteUi\View\Components\Form\Input::class,
    TasteUi\View\Components\Form\Textarea::class,
    TasteUi\View\Components\Form\Password::class,
    TasteUi\View\Components\Form\Toggle::class,
    TasteUi\View\Components\Form\Radio::class,
    TasteUi\View\Components\Form\Checkbox::class,
    TasteUi\View\Components\Form\Label::class,
    TasteUi\View\Components\Alert::class,
    TasteUi\View\Components\Card::class,
    TasteUi\View\Components\Badge::class,
    TasteUi\View\Components\Avatar\Index::class,
    TasteUi\View\Components\Avatar\Modelable::class,
    TasteUi\View\Components\Hint::class,
    TasteUi\View\Components\Error::class,
    TasteUi\View\Components\Errors::class,
    TasteUi\View\Components\Tabs\Index::class,
    TasteUi\View\Components\Tabs\Item::class,
    TasteUi\View\Components\Tooltip::class,
    TasteUi\View\Components\Button\Index::class,
    TasteUi\View\Components\Button\Circle::class,
    TasteUi\View\Components\Select\Select::class,
    TasteUi\View\Components\Select\Styled::class,
    TasteUi\View\Components\Select\Searchable::class,
    TasteUi\View\Components\Modal::class,
    TasteUi\View\Components\Interaction\Toast::class,
    TasteUi\View\Components\Interaction\Dialog::class,
    TasteUi\View\Components\Wrapper\Input::class,
    TasteUi\View\Components\Wrapper\Radio::class,
    TasteUi\View\Components\Wrapper\Select::class,
]);
