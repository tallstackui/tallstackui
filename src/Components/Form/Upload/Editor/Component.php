<?php

namespace TallStackUi\Components\Form\Upload\Editor;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.upload.editor')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(public array $editing)
    {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.form.upload.editor');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex flex-col',
            'name' => 'truncate',
            'stage' => [
                'wrapper' => 'flex h-[60vh] w-full select-none items-center justify-center overflow-hidden bg-gray-100 p-4 dark:bg-dark-900',
                'frame' => 'relative touch-none',
                'canvas' => 'block',
            ],
            'crop' => [
                'box' => 'absolute cursor-move border border-white shadow-[0_0_0_9999px_rgba(0,0,0,0.5)]',
                'grid' => 'pointer-events-none absolute inset-0 grid grid-cols-3 grid-rows-3',
                'cell' => 'border border-white/30',
                'handle' => [
                    'base' => 'absolute h-3 w-3 rounded-full border border-white bg-primary-500',
                    'n' => '-top-1.5 left-1/2 -translate-x-1/2 cursor-ns-resize',
                    's' => '-bottom-1.5 left-1/2 -translate-x-1/2 cursor-ns-resize',
                    'e' => '-right-1.5 top-1/2 -translate-y-1/2 cursor-ew-resize',
                    'w' => '-left-1.5 top-1/2 -translate-y-1/2 cursor-ew-resize',
                    'ne' => '-right-1.5 -top-1.5 cursor-nesw-resize',
                    'nw' => '-left-1.5 -top-1.5 cursor-nwse-resize',
                    'se' => '-bottom-1.5 -right-1.5 cursor-nwse-resize',
                    'sw' => '-bottom-1.5 -left-1.5 cursor-nesw-resize',
                ],
            ],
            'footer' => [
                'tools' => 'flex items-center gap-1',
                'tool' => 'cursor-pointer rounded-md p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-dark-300 dark:hover:bg-dark-700 dark:hover:text-dark-100',
                'tool-icon' => 'h-5 w-5',
                'actions' => 'flex items-center gap-2',
            ],
        ]);
    }
}
