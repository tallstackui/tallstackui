<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Components\Editor\Component;
use TallStackUi\Support\Runtime\AbstractRuntime;

class EditorRuntime extends AbstractRuntime
{
    private const GROUPS = [
        'style' => 'formatting',
        'bold' => 'inline',
        'italic' => 'inline',
        'underline' => 'inline',
        'strikethrough' => 'inline',
        'ordered-list' => 'lists',
        'unordered-list' => 'lists',
        'indent' => 'lists',
        'outdent' => 'lists',
        'align' => 'align',
        'code' => 'code',
        'code-block' => 'code',
        'clear-format' => 'code',
        'link' => 'insert',
        'image' => 'insert',
        'undo' => 'history',
        'redo' => 'history',
        'fullscreen' => 'view',
    ];

    /** @throws Exception */
    public function runtime(): array
    {
        /** @var Component $component */
        $component = $this->component;

        $bind = $this->bind();
        $property = $bind->get('property');
        $name = $component->attributes->get('name');

        if (! $property && ! $name) {
            __ts_validation_exception($component, 'The component requires either the [wire:model] or the [name] attribute.');
        }

        $this->validateToolbar($component->toolbar);
        $this->validateUpload();

        // Unlike most form components, this one also renders outside of
        // Livewire, where there is neither a component to read nor an error bag.
        $value = $property && $this->wireable() ? $this->property($property) : $component->attributes->get('value');

        // Everything below is deliberately named apart from the component's own
        // properties: a public property always shadows a runtime key that
        // carries the same name in the view data.
        return [
            'id' => $id = $bind->get('id') ?? uniqid('tsui-editor-'),
            'property' => $property,
            'name' => $name,
            'error' => $bind->get('error'),
            // Mirrors Livewire itself: wire:model defers the round trip and
            // wire:model.live pushes the HTML to the server right away.
            'entangle' => $bind->get('entangle'),
            'wireable' => $this->wireable(),
            'value' => is_string($value) ? $value : '',
            'layout' => $this->layout($component->toolbar),
            'upload' => [
                'enabled' => $component->uploadProperty !== null && $component->uploadMethod !== null,
                'property' => $component->uploadProperty,
                'method' => $component->uploadMethod,
                'mimes' => $component->uploadMimes,
                'max_size' => $component->uploadMaxSize,
                'readable' => $this->readable($component->uploadMaxSize),
            ],
            // Slugged here because the modal derives its open and close events
            // from the id the same way, and the browser has to name them.
            'dialogs' => [
                'link' => str($id.'-link')->slug()->kebab()->value(),
                'image' => str($id.'-image')->slug()->kebab()->value(),
            ],
            'i18n' => trans('ts-ui::messages.editor'),
        ];
    }

    /**
     * Build the toolbar as a flat list the view can loop over without holding
     * any local state, since the template is allowed a single @php block.
     */
    private function layout(array $toolbar): array
    {
        $layout = [];
        $previous = null;

        foreach ($toolbar as $slug) {
            $group = self::GROUPS[$slug];

            if ($previous !== null && $group !== $previous) {
                $layout[] = ['type' => 'divider', 'slug' => null];
            }

            $layout[] = ['type' => 'button', 'slug' => $slug];
            $previous = $group;
        }

        return $layout;
    }

    /**
     * Turn the upload ceiling, always given in KB, into something worth
     * painting next to the file picker.
     */
    private function readable(int $size): string
    {
        return $size >= 1024
            ? rtrim(rtrim(number_format($size / 1024, 1, '.', ''), '0'), '.').' MB'
            : $size.' KB';
    }

    private function validateToolbar(array $toolbar): void
    {
        $unknown = array_diff($toolbar, array_keys(self::GROUPS));

        if ($unknown === []) {
            return;
        }

        __ts_validation_exception($this->component, sprintf(
            'The toolbar has unknown button(s): [%s]. Allowed: [%s].',
            implode(', ', $unknown),
            implode(', ', array_keys(self::GROUPS)),
        ));
    }

    private function validateUpload(): void
    {
        /** @var Component $component */
        $component = $this->component;

        if ($component->uploadProperty === null && $component->uploadMethod === null) {
            return;
        }

        if ($component->uploadProperty === null || $component->uploadMethod === null) {
            __ts_validation_exception($component, 'The [upload-property] and [upload-method] must be used together.');
        }

        if (! $this->wireable()) {
            __ts_validation_exception($component, 'The image upload requires the component to be used within a Livewire context.');
        }
    }
}
