<?php

namespace TallStackUi\Foundation\Support\Components;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use TallStackUi\Foundation\Exceptions\InappropriateIconGuideExecution;

class IconGuide
{
    final public const AVAILABLE = [
        'heroicons' => [
            'solid',
            'outline',
        ],
        'phosphoricons' => [
            'thin',
            'light',
            'regular',
            'bold',
            'duotone',
        ],
        'google' => [
            'default',
        ],
    ];

    // The idea of this constant is to be used as a map of internal icons in
    // components. As new icons are supported, this guide should be updated
    // and icon references should use generic names when possible.
    private const GUIDE = [
        'heroicons' => [
            'arrow-path' => 'arrow-path',
            'check' => 'check',
            'check-circle' => 'check-circle',
            'chevron-down' => 'chevron-down',
            'chevron-left' => 'chevron-left',
            'chevron-right' => 'chevron-right',
            'chevron-up' => 'chevron-up',
            'chevron-up-down' => 'chevron-up-down',
            'clipboard' => 'clipboard',
            'clipboard-document' => 'clipboard-document',
            'cloud-arrow-up' => 'cloud-arrow-up',
            'document-check' => 'document-check',
            'document-text' => 'document-text',
            'exclamation-circle' => 'exclamation-circle',
            'eye' => 'eye',
            'eye-slash' => 'eye-slash',
            'information-circle' => 'information-circle',
            'magnifying-glass' => 'magnifying-glass',
            'minus' => 'minus',
            'photo' => 'photo',
            'plus' => 'plus',
            'question-mark-circle' => 'question-mark-circle',
            'swatch' => 'swatch',
            'trash' => 'trash',
            'x-circle' => 'x-circle',
            'x-mark' => 'x-mark',
        ],
        'phosphoricons' => [
            'arrow-path' => 'password',
            'check' => 'check',
            'check-circle' => 'check-circle',
            'chevron-down' => 'caret-down',
            'chevron-left' => 'caret-left',
            'chevron-right' => 'caret-right',
            'chevron-up' => 'caret-up',
            'chevron-up-down' => 'caret-up-down',
            'clipboard' => 'clipboard',
            'clipboard-document' => 'copy-simple',
            'cloud-arrow-up' => 'cloud-arrow-up',
            'document-check' => 'clipboard-text',
            'document-text' => 'file-text',
            'exclamation-circle' => 'info',
            'eye' => 'eye',
            'eye-slash' => 'eye-slash',
            'information-circle' => 'info',
            'magnifying-glass' => 'magnifying-glass',
            'minus' => 'minus',
            'photo' => 'image',
            'plus' => 'plus',
            'question-mark-circle' => 'question',
            'swatch' => 'swatches',
            'trash' => 'trash',
            'x-circle' => 'x-circle',
            'x-mark' => 'x',
        ],
        'google' => [
            'arrow-path' => 'sync',
            'check' => 'check',
            'check-circle' => 'check-circle',
            'chevron-down' => 'expand-more',
            'chevron-left' => 'chevron-left',
            'chevron-right' => 'chevron-right',
            'chevron-up' => 'expand-less',
            'chevron-up-down' => 'unfold-more',
            'clipboard' => 'content-paste',
            'clipboard-document' => 'content-copy',
            'cloud-arrow-up' => 'cloud-upload',
            'document-check' => 'assignment',
            'document-text' => 'file-text',
            'exclamation-circle' => 'info',
            'eye' => 'visibility',
            'eye-slash' => 'visibility-off',
            'information-circle' => 'info',
            'magnifying-glass' => 'search',
            'minus' => 'remove',
            'photo' => 'image',
            'plus' => 'add',
            'question-mark-circle' => 'help',
            'swatch' => 'palette',
            'trash' => 'delete',
            'x-circle' => 'cancel',
            'x-mark' => 'close',
        ],
    ];

    /** @throws Exception|InappropriateIconGuideExecution */
    public static function build(Component $component): string
    {
        InappropriateIconGuideExecution::validate($component::class);

        $config = self::configuration();

        $type = $config->get('type');
        $style = $config->get('style');

        self::validate($type);

        foreach (array_keys($component->attributes->getAttributes()) as $attribute) {
            if (! in_array($attribute, self::AVAILABLE[$type])) {
                continue;
            }

            // When some attribute matches one of the keys
            // available in the supported icons, then we want
            // to override the style through run time.
            $style = $attribute;
        }

        $name = $component->icon ?? $component->name; // @phpstan-ignore-line

        // For phosphoricons when the style is different from
        // "regular" we need to add the style right after the
        // name due to the way phosphoricons exports the files.
        if ($type === 'phosphoricons' && $style !== 'regular') {
            $name = $name.'-'.$style;
        }

        return sprintf('%s.%s.%s', $type, $style, $name);
    }

    /**
     * Determine internal icons using the guide.
     *
     * @throws Exception
     */
    public static function internal(string $key): string
    {
        $config = self::configuration();

        self::validate($type = $config->get('type'));

        return self::GUIDE[$type][$key] ?? $key;
    }

    private static function configuration(): Collection
    {
        return collect(config('tallstackui.icons'));
    }

    /** @throws Exception */
    private static function validate(string $type): void
    {
        if (in_array($type, array_keys(self::AVAILABLE))) {
            return;
        }

        throw new Exception("The icon type [$type] is not supported.");
    }
}
