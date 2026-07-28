<?php

use Illuminate\Support\Collection;
use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-checkbox.group :options="$options" />')
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]])
    ->toContain('<fieldset')
    ->toContain('type="checkbox"')
    ->toContain('value="newsletter"')
    ->toContain('Newsletter')
    ->toContain('form-checkbox');

it('can render with the list variant by default')
    ->expect('<x-checkbox.group :options="$options" />')
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]])
    ->toContain('-space-y-px')
    ->toContain('first:rounded-t-md');

it('can render with the card variant')
    ->expect('<x-checkbox.group card :options="$options" />')
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]])
    ->toContain('grid gap-3')
    ->toContain('grid-cols-1 sm:grid-cols-3')
    ->not->toContain('sr-only');

it('can render with the panel variant hiding the control and showing the check')
    ->expect('<x-checkbox.group panel :options="$options" />')
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]])
    ->toContain('sr-only')
    ->toContain('group-has-checked:visible');

it('can render with the inline variant')
    ->expect('<x-checkbox.group inline :options="$options" />')
    ->render(['options' => [['label' => 'Bold', 'value' => 'bold', 'aside' => 'Skipped']]])
    ->toContain('inline-flex -space-x-px')
    ->toContain('sr-only')
    ->toContain('group-has-checked:text-white')
    ->not->toContain('Skipped');

it('can render with columns')
    ->expect('<x-checkbox.group card :columns="4" :options="$options" />')
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]])
    ->toContain('grid-cols-1 sm:grid-cols-2 lg:grid-cols-4');

it('can render taking the first variant flag when more than one is given')
    ->expect('<x-checkbox.group card panel inline :options="$options" />')
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]])
    ->toContain('grid gap-3')
    ->not->toContain('sr-only');

it('can render without leaking the variant flag into the markup')
    ->expect('<x-checkbox.group panel :options="$options" />')
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]])
    ->not->toContain('panel="');

it('can render with description, aside, icon and badge')
    ->expect('<x-checkbox.group :options="$options" />')
    ->render(['options' => [[
        'label' => 'Newsletter',
        'value' => 'newsletter',
        'description' => 'Weekly digest',
        'aside' => 'Free',
        'icon' => 'envelope',
        'badge' => 'New',
    ]]])
    ->toContain('Weekly digest')
    ->toContain('Free')
    ->toContain('New')
    ->toContain('<svg');

it('can render with a disabled option')
    ->expect('<x-checkbox.group :options="$options" />')
    ->render(['options' => [['label' => 'Legacy', 'value' => 'legacy', 'disabled' => true]]])
    ->toContain('disabled')
    ->toContain('cursor-not-allowed');

it('can render with a label and a hint')
    ->expect('<x-checkbox.group label="Features" hint="Pick as many as you want" :options="$options" />')
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]])
    ->toContain('<legend')
    ->toContain('Features')
    ->toContain('Pick as many as you want');

it('can render with a different color')
    ->expect('<x-checkbox.group color="green" :options="$options" />')
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]])
    ->toContain('has-checked:bg-green-50')
    ->toContain('has-checked:border-green-500');

it('can render remapping the option keys')
    ->expect('<x-checkbox.group select="label:name|value:id|badge:tag" :options="$options" />')
    ->render(['options' => [['name' => 'Alpha', 'id' => 1, 'tag' => 'Beta']]])
    ->toContain('Alpha')
    ->toContain('value="1"')
    ->toContain('Beta');

it('can render options given as a collection', function () {
    $options = new Collection([['label' => 'Newsletter', 'value' => 'newsletter']]);

    expect('<x-checkbox.group :options="$options" />')
        ->render(compact('options'))
        ->toContain('value="newsletter"');
});

it('can render checking every option present in the value array out of livewire', function () {
    $html = Blade::render('<x-checkbox.group name="features[]" :value="$value" :options="$options" />', [
        'value' => ['newsletter', 'reports'],
        'options' => [
            ['label' => 'Newsletter', 'value' => 'newsletter'],
            ['label' => 'Alerts', 'value' => 'alerts'],
            ['label' => 'Reports', 'value' => 'reports'],
        ],
    ]);

    $inputs = [];

    preg_match_all('/<input\b[^>]*>/', $html, $inputs);

    expect($inputs[0])->toHaveCount(3)
        ->and($inputs[0][0])->toContain('checked')
        ->and($inputs[0][1])->not->toContain('checked')
        ->and($inputs[0][2])->toContain('checked');
});

it('can render the asterisk without setting required on the inputs')
    ->expect('<x-checkbox.group label="Features" required :options="$options" />')
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]])
    ->toContain('>*<')
    ->not->toContain('required');

it('can render replacing the option body through interact')
    ->expect(<<<'BLADE'
    <x-checkbox.group :options="$options">
        @interact('option', $option)
            <span class="custom">{{ $option['label'] }}::{{ $option['tag'] }}</span>
        @endinteract
    </x-checkbox.group>
    BLADE)
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter', 'tag' => 'A']]])
    ->toContain('Newsletter::A')
    ->toContain('type="checkbox"');

it('can render omitting the description reference when interact replaces the option body')
    ->expect(<<<'BLADE'
    <x-checkbox.group :options="$options">
        @interact('option', $option)
            <span>{{ $option['label'] }}</span>
        @endinteract
    </x-checkbox.group>
    BLADE)
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter', 'description' => 'Weekly']]])
    ->not->toContain('aria-describedby');

it('can render naming the inputs as an array')
    ->expect('<x-checkbox.group id="features" :options="$options" />')
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]])
    ->toContain('name="features[]"');

it('can render preserving the name when it is explicitly given')
    ->expect('<x-checkbox.group id="features" name="custom" :options="$options" />')
    ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]])
    ->toContain('name="custom"')
    ->not->toContain('name="features[]"');

it('cannot render with columns out of range', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [columns] must be between 1 and 4.');

    expect('<x-checkbox.group :columns="0" :options="$options" />')
        ->render(['options' => [['label' => 'Newsletter', 'value' => 'newsletter']]]);
});

it('cannot render without the label key', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The key [label] is missing in the options array.');

    expect('<x-checkbox.group :options="$options" />')->render(['options' => [['value' => 'newsletter']]]);
});

it('cannot render without the value key', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The key [value] is missing in the options array.');

    expect('<x-checkbox.group :options="$options" />')->render(['options' => [['label' => 'Newsletter']]]);
});

it('cannot render with options that are not arrays', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [options] must be an array of arrays.');

    expect('<x-checkbox.group :options="$options" />')->render(['options' => ['Newsletter']]);
});
