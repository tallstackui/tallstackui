<?php

namespace TallStackUi\Support\Runtime\Components;

use Exception;
use TallStackUi\Components\Editor\Component;
use TallStackUi\Support\Miscellaneous\UploadEditorOptions;
use TallStackUi\Support\Runtime\AbstractRuntime;

class EditorRuntime extends AbstractRuntime
{
    private const GROUPS = [
        'style' => 'formatting',
        'blockquote' => 'formatting',
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
        'hr' => 'insert',
        'undo' => 'history',
        'redo' => 'history',
        'fullscreen' => 'view',
    ];

    /** Buttons without a Markdown equivalent. */
    private const MARKDOWN_INCOMPATIBLE = ['underline', 'align'];

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

        // This component also renders outside of Livewire, where there is no
        // component to read from.
        $value = $property && $this->wireable() ? $this->property($property) : $component->attributes->get('value');

        // The keys below avoid the component property names: a public property
        // shadows a runtime key of the same name.
        return [
            'id' => $id = $bind->get('id') ?? uniqid('tsui-editor-'),
            'property' => $property,
            'name' => $name,
            'error' => $bind->get('error'),
            // As Livewire itself: wire:model defers, wire:model.live pushes.
            'entangle' => $bind->get('entangle'),
            'wireable' => $this->wireable(),
            'value' => is_string($value) ? $value : '',
            'layout' => $this->layout($this->compatible($component->toolbar, $component->markdown)),
            'upload' => [
                'enabled' => $component->uploadProperty !== null && $component->uploadMethod !== null,
                'property' => $component->uploadProperty,
                'method' => $component->uploadMethod,
                'mimes' => $component->uploadMimes,
                'max_size' => $component->uploadMaxSize,
                'readable' => $this->readable($component->uploadMaxSize),
                'editor' => $this->editor($id),
            ],
            // Slugged the same way the modal derives its open and close events.
            'dialogs' => [
                'link' => str($id.'-link')->slug()->kebab()->value(),
                'image' => str($id.'-image')->slug()->kebab()->value(),
            ],
            'i18n' => trans('ts-ui::messages.editor'),
        ];
    }

    /** Drop the buttons the output format cannot express. */
    private function compatible(array $toolbar, bool $markdown): array
    {
        if (! $markdown) {
            return $toolbar;
        }

        return array_values(array_diff($toolbar, self::MARKDOWN_INCOMPATIBLE));
    }

    private function editor(string $id): ?array
    {
        /** @var Component $component */
        $component = $this->component;

        if ($component->uploadProperty === null || $component->uploadMethod === null) {
            return null;
        }

        $configuration = __ts_get_component_configuration(Component::class)['upload'] ?? [];

        return (new UploadEditorOptions($component, $component->uploadEditor, $component->uploadAspect, $configuration, $id, $this->livewire))();
    }

    /** Flatten the toolbar into buttons and dividers the view can loop over. */
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

    /** Turn the upload ceiling, given in KB, into a readable label. */
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
