<?php

namespace TallStackUi\Foundation\Support\Components;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use TallStackUi\View\Components\Icon;

class IconGuide
{
    // All supported icons
    public const AVAILABLE = [
        'heroicons' => [
            'solid',
            'outline',
        ],
        'phosphoricons' => [
            'thin',
            'light',
            'regular',
            'bold',
            'fill',
            'duotone',
        ],
    ];

    // The idea of this constant is to be used as a map of internal icons in
    // components. As new icons are supported, this guide should be updated
    // and icon references should use generic names when possible.
    private const GUIDE = [
        'heroicons' => [
            'x-mark' => 'x-mark',
            'chevron-up' => 'chevron-up',
            'chevron-down' => 'chevron-down',
            'arrow-path' => 'arrow-path',
            'document-text' => 'document-text',
            'photo' => 'photo',
            'swatch' => 'swatch',
            'arrow-up-tray' => 'arrow-up-tray',
        ],
        'phosphoricons' => [
            'x-mark' => 'x',
            'chevron-up' => 'caret-up',
            'chevron-down' => 'caret-down',
            'arrow-path' => 'password',
            'document-text' => 'files',
            'photo' => 'file-image',
            'swatch' => 'swatches',
            'arrow-up-tray' => 'upload-simple',
        ],
    ];

    /** @throws Exception */
    public static function for(Icon $component): string
    {
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
            // to override the style at run time.
            $style = $attribute;
        }

        $name = $component->icon ?? $component->name;

        // For phosphoricons when the style is different from
        // "regular" we need to add the style right after the
        // name due to the way phosphoricons exports the files.
        if ($type === 'phosphoricons' && $style !== 'regular') {
            $name = $name.'-'.$style;
        }

        $base = str_replace('components', '', $view = $component->blade()->name());
        $icon = sprintf('%s.%s.%s', $type, $style, $name);

        if (! View::exists($view.'.'.$icon)) {
            throw new Exception("The icon [$icon] does not exist.");
        }

        return $base.'.'.$icon;
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
