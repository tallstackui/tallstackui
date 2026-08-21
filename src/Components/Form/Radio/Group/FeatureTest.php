<?php

use Illuminate\Support\Collection;
use Illuminate\View\ViewException;
use TallStackUi\Components\Form\Radio\Group\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    $this->components = config('ts-ui.components');
});

afterEach(function () {
    config()->set('ts-ui.components', $this->components);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render')
    ->expect('<x-radio.group :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->toContain('<fieldset')
    ->toContain('type="radio"')
    ->toContain('value="startup"')
    ->toContain('Startup')
    ->toContain('form-radio');

it('can render with the list variant by default')
    ->expect('<x-radio.group :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->toContain('first:rounded-t-md')
    ->toContain('border-b-0')
    ->toContain('last:border-b')
    ->not->toContain('-space-y-px');

it('can render with the card variant')
    ->expect('<x-radio.group card :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->toContain('grid gap-3')
    ->toContain('grid-cols-1 sm:grid-cols-3')
    ->not->toContain('sr-only');

it('can render with the panel variant hiding the control and showing the check')
    ->expect('<x-radio.group panel :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->toContain('sr-only')
    ->toContain('group-has-checked:visible');

it('can render with the inline variant')
    ->expect('<x-radio.group inline :options="$options" />')
    ->render(['options' => [['label' => 'Monthly', 'value' => 'monthly', 'description' => 'Skipped']]])
    ->toContain('inline-flex rounded-md')
    ->toContain('border-r-0')
    ->toContain('last:border-r')
    ->not->toContain('-space-x-px')
    ->toContain('sr-only')
    ->toContain('has-checked:bg-primary-50')
    ->not->toContain('has-checked:bg-primary-500')
    ->not->toContain('Skipped');

it('can render with columns')
    ->expect('<x-radio.group card :columns="2" :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->toContain('grid-cols-1 sm:grid-cols-2');

it('can render taking the first variant flag when more than one is given')
    ->expect('<x-radio.group card panel inline :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->toContain('grid gap-3')
    ->not->toContain('sr-only');

it('can render without leaking the variant flag into the markup')
    ->expect('<x-radio.group panel :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->not->toContain('panel="');

it('can render with description, aside, icon and badge')
    ->expect('<x-radio.group :options="$options" />')
    ->render(['options' => [[
        'label' => 'Startup',
        'value' => 'startup',
        'description' => 'Up to 5 job postings',
        'aside' => '$29 / mo',
        'icon' => 'rocket-launch',
        'badge' => 'Popular',
    ]]])
    ->toContain('Up to 5 job postings')
    ->toContain('$29 / mo')
    ->toContain('Popular')
    ->toContain('<svg');

it('can render with image taking precedence over icon')
    ->expect('<x-radio.group :options="$options" />')
    ->render(['options' => [[
        'label' => 'Startup',
        'value' => 'startup',
        'icon' => 'rocket-launch',
        'image' => 'https://tallstackui.com/avatar.png',
    ]]])
    ->toContain('<img src="https://tallstackui.com/avatar.png"')
    ->toContain('alt="Startup"');

it('can render with a disabled option')
    ->expect('<x-radio.group :options="$options" />')
    ->render(['options' => [['label' => 'Free', 'value' => 'free', 'disabled' => true]]])
    ->toContain('disabled')
    ->toContain('cursor-not-allowed');

it('can render with a label and a hint')
    ->expect('<x-radio.group label="Plan" hint="Pick one" :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->toContain('<legend')
    ->toContain('Plan')
    ->toContain('Pick one');

it('can render with the required asterisk and the required attribute on the inputs', function () {
    $html = Blade::render('<x-radio.group label="Plan" required :options="$options" />', [
        'options' => [['label' => 'Startup', 'value' => 'startup']],
    ]);

    $inputs = [];

    preg_match_all('/<input\b[^>]*>/', $html, $inputs);

    expect($html)->toContain('>*<')
        ->and($inputs[0][0])->toContain('required');
});

it('can render with the asterisk coming from the label convention')
    ->expect('<x-radio.group label="Plan *" :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->toContain('>*<')
    ->not->toContain('Plan *');

it('can render with the right position')
    ->expect('<x-radio.group position="right" :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->toContain('order-last ml-auto');

it('can render with a different size')
    ->expect('<x-radio.group lg :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->toContain('h-6 w-6');

it('can render with a different color')
    ->expect('<x-radio.group color="green" :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->toContain('has-checked:bg-green-50')
    ->toContain('has-checked:border-green-500');

it('can render remapping the option keys')
    ->expect('<x-radio.group select="label:name|value:id|description:note" :options="$options" />')
    ->render(['options' => [['name' => 'Alpha', 'id' => 1, 'note' => 'First one']]])
    ->toContain('Alpha')
    ->toContain('value="1"')
    ->toContain('First one');

it('can render remapping the option keys from the global configuration', function () {
    config()->set('ts-ui.components', [
        ...config('ts-ui.components'),
        'radio.group' => [Component::class, ['select' => 'label:name|value:id|description:note']],
    ]);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-radio.group :options="$options" />')
        ->render(['options' => [['name' => 'Alpha', 'id' => 1, 'note' => 'First one']]])
        ->toContain('Alpha')
        ->toContain('value="1"')
        ->toContain('First one');
});

it('can override the global configuration keys inline', function () {
    config()->set('ts-ui.components', [
        ...config('ts-ui.components'),
        'radio.group' => [Component::class, ['select' => 'label:name|value:id']],
    ]);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-radio.group select="label:title|value:uuid" :options="$options" />')
        ->render(['options' => [['title' => 'Alpha', 'uuid' => 1]]])
        ->toContain('Alpha')
        ->toContain('value="1"');
});

it('can render options given as a collection', function () {
    $options = new Collection([['label' => 'Startup', 'value' => 'startup']]);

    expect('<x-radio.group :options="$options" />')
        ->render(compact('options'))
        ->toContain('value="startup"');
});

it('can render checking the option matching the value out of livewire', function () {
    $html = Blade::render('<x-radio.group name="plan" value="business" :options="$options" />', ['options' => [
        ['label' => 'Startup', 'value' => 'startup'],
        ['label' => 'Business', 'value' => 'business'],
    ]]);

    $inputs = [];

    preg_match_all('/<input\b[^>]*>/', $html, $inputs);

    expect($html)->toContain('name="plan"')
        ->and($inputs[0])->toHaveCount(2)
        ->and($inputs[0][0])->toContain('value="startup"')->not->toContain('checked')
        ->and($inputs[0][1])->toContain('value="business"')->toContain('checked');
});

it('can render replacing the option body through interact')
    ->expect(<<<'BLADE'
    <x-radio.group :options="$options">
        @interact('option', $option)
            <span class="custom">{{ $option['label'] }}::{{ $option['tag'] }}</span>
        @endinteract
    </x-radio.group>
    BLADE)
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup', 'tag' => 'A']]])
    ->toContain('Startup::A')
    ->toContain('type="radio"');

it('can render omitting the description reference when interact replaces the option body')
    ->expect(<<<'BLADE'
    <x-radio.group :options="$options">
        @interact('option', $option)
            <span>{{ $option['label'] }}</span>
        @endinteract
    </x-radio.group>
    BLADE)
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup', 'description' => 'Up to 5']]])
    ->not->toContain('aria-describedby');

it('can render sharing the same name between the inputs', function () {
    $html = Blade::render('<x-radio.group id="plan" :options="$options" />', ['options' => [
        ['label' => 'Startup', 'value' => 'startup'],
        ['label' => 'Business', 'value' => 'business'],
    ]]);

    $inputs = [];

    preg_match_all('/<input\b[^>]*>/', $html, $inputs);

    expect($inputs[0])->toHaveCount(2)
        ->and($inputs[0][0])->toContain('id="plan-0"')->toContain('name="plan"')
        ->and($inputs[0][1])->toContain('id="plan-1"')->toContain('name="plan"');
});

it('can render preserving the name when it is explicitly given')
    ->expect('<x-radio.group id="plan" name="custom" :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]])
    ->toContain('name="custom"')
    ->not->toContain('name="plan"');

it('cannot render with columns out of range', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [columns] must be between 1 and 4.');

    expect('<x-radio.group :columns="5" :options="$options" />')
        ->render(['options' => [['label' => 'Startup', 'value' => 'startup']]]);
});

it('cannot render without the label key', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The key [label] is missing in the options array.');

    expect('<x-radio.group :options="$options" />')->render(['options' => [['value' => 'startup']]]);
});

it('cannot render without the value key', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The key [value] is missing in the options array.');

    expect('<x-radio.group :options="$options" />')->render(['options' => [['label' => 'Startup']]]);
});

it('cannot render with options that are not arrays', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [options] must be an array of arrays.');

    expect('<x-radio.group :options="$options" />')->render(['options' => ['Startup']]);
});

it('hands the seam over to the checked option instead of overlapping borders')
    ->expect('<x-radio.group :options="$options" />')
    ->render(['options' => [['label' => 'Startup', 'value' => 'startup'], ['label' => 'Business', 'value' => 'business']]])
    ->toContain('last:border-b has-checked:border-b')
    ->toContain('[&:has(:checked)+*]:border-t-0');

it('hands the seam over to the checked option on the inline variant')
    ->expect('<x-radio.group inline :options="$options" />')
    ->render(['options' => [['label' => 'Monthly', 'value' => 'monthly'], ['label' => 'Yearly', 'value' => 'yearly']]])
    ->toContain('last:border-r has-checked:border-r')
    ->toContain('[&:has(:checked)+*]:border-l-0');
