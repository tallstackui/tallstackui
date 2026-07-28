<?php

namespace TallStackUi\Components\Editor;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Runtime\Components\EditorRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('editor')]
#[PassThroughRuntime(EditorRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?string $placeholder = null,
        public ?bool $markdown = null,
        public ?array $toolbar = null,
        public ?string $uploadProperty = null,
        public ?string $uploadMethod = null,
        public ?array $uploadMimes = null,
        public ?int $uploadMaxSize = null,
        public ?bool $counters = null,
        public ?string $minHeight = null,
        public ?string $maxHeight = null,
        public bool $readonly = false,
        public bool $disabled = false,
        public bool $required = false,
        public bool $spellcheck = true,
        public ?bool $invalidate = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.editor.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'base' => 'dark:border-dark-600 dark:bg-dark-800 flex flex-col overflow-hidden rounded-lg border border-gray-300 bg-white',
                'fullscreen' => 'fixed inset-0 z-40 rounded-none border-0',
                'disabled' => 'pointer-events-none opacity-60',
            ],
            'toolbar' => [
                'wrapper' => 'dark:border-dark-600 dark:bg-dark-900 flex items-center gap-x-1 overflow-x-auto border-b border-gray-300 bg-gray-50 px-2 py-1.5 soft-scrollbar',
                'divider' => 'dark:bg-dark-600 mx-1 h-5 w-px shrink-0 bg-gray-300',
                'button' => [
                    'base' => 'dark:text-dark-300 dark:hover:bg-dark-700 inline-flex h-8 min-w-8 shrink-0 cursor-pointer items-center justify-center rounded px-2 text-sm text-gray-600 transition hover:bg-gray-200',
                    'active' => 'dark:bg-primary-900/40 dark:text-primary-300 bg-primary-50 text-primary-700',
                ],
                'dropdown' => [
                    'trigger' => 'dark:text-dark-300 dark:hover:bg-dark-700 inline-flex h-8 shrink-0 cursor-pointer items-center gap-x-1 rounded px-2 text-sm whitespace-nowrap text-gray-600 transition hover:bg-gray-200',
                    'active' => 'dark:text-primary-300 text-primary-700',
                    'style' => [
                        'paragraph' => 'text-sm!',
                        'h1' => 'text-2xl! font-bold',
                        'h2' => 'text-xl! font-bold',
                        'h3' => 'text-lg! font-bold',
                    ],
                ],
                'icon' => 'h-4 w-4',
            ],
            'editable' => [
                'container' => 'flex flex-1 overflow-hidden',
                'wrapper' => 'flex-1 overflow-y-auto soft-scrollbar',
                'content' => 'dark:text-dark-200 relative w-full px-4 py-3 text-sm text-gray-700 outline-none focus:outline-none',
                'placeholder' => 'dark:data-[empty=true]:before:text-dark-500 data-[empty=true]:before:pointer-events-none data-[empty=true]:before:absolute data-[empty=true]:before:text-gray-400 data-[empty=true]:before:content-[attr(data-placeholder)]',
                'typography' => [
                    'headings' => '[&_h1]:mb-2 [&_h1]:text-2xl [&_h1]:font-bold [&_h2]:mb-2 [&_h2]:text-xl [&_h2]:font-bold [&_h3]:mb-1 [&_h3]:text-lg [&_h3]:font-bold [&_h4]:mb-1 [&_h4]:text-base [&_h4]:font-bold [&_h5]:mb-1 [&_h5]:text-sm [&_h5]:font-bold',
                    'lists' => '[&_ol]:my-2 [&_ol]:list-decimal [&_ol]:pl-6 [&_ul]:my-2 [&_ul]:list-disc [&_ul]:pl-6 [&_li]:my-1',
                    'code' => 'dark:[&_code]:bg-dark-700 [&_code]:rounded [&_code]:bg-gray-100 [&_code]:px-1 [&_code]:py-0.5 [&_code]:font-mono [&_code]:text-sm dark:[&_pre]:bg-black [&_pre]:my-2 [&_pre]:overflow-x-auto [&_pre]:rounded [&_pre]:bg-gray-900 [&_pre]:p-3 [&_pre]:font-mono [&_pre]:text-sm [&_pre]:text-white [&_pre_code]:bg-transparent [&_pre_code]:p-0 [&_pre_code]:text-white',
                    'link' => 'dark:[&_a]:text-primary-400 [&_a]:text-primary-600 [&_a]:underline',
                    'image' => '[&_img]:my-2 [&_img]:inline-block [&_img]:max-w-full [&_img]:rounded',
                    'paragraph' => '[&_p]:my-1',
                    'quote' => 'dark:[&_blockquote]:border-dark-600 dark:[&_blockquote]:text-dark-400 [&_blockquote]:my-2 [&_blockquote]:border-l-4 [&_blockquote]:border-gray-300 [&_blockquote]:pl-4 [&_blockquote]:text-gray-500 [&_blockquote]:italic',
                    'rule' => 'dark:[&_hr]:border-dark-600 [&_hr]:my-4 [&_hr]:border-t [&_hr]:border-gray-300',
                ],
            ],
            'footer' => [
                'wrapper' => 'dark:border-dark-600 dark:bg-dark-900 dark:text-dark-400 flex items-center justify-end gap-x-3 border-t border-gray-300 bg-gray-50 px-3 py-1.5 text-xs text-gray-500',
                'counter' => 'tabular-nums',
            ],
            'dialog' => [
                'fields' => 'flex flex-col gap-y-3',
                'error' => 'text-xs text-red-500',
            ],
            'image' => [
                'upload' => [
                    'area' => 'dark:border-dark-600 dark:text-dark-400 hover:border-primary-400 hover:text-primary-600 flex cursor-pointer flex-col items-center justify-center gap-y-2 rounded-md border-2 border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500 transition',
                    'button' => 'inline-flex items-center gap-x-1.5 text-sm font-medium',
                    'hint' => 'dark:text-dark-500 text-xs text-gray-400',
                    'progress' => [
                        'wrapper' => 'dark:bg-dark-700 mt-2 h-1 w-full overflow-hidden rounded bg-gray-200',
                        'bar' => 'bg-primary-500 h-full transition-all',
                    ],
                ],
                'divider' => 'dark:text-dark-500 dark:[&>span]:border-dark-600 flex items-center gap-x-2 text-xs text-gray-400 uppercase [&>span]:flex-1 [&>span]:border-t [&>span]:border-gray-200',
                'preview' => 'dark:border-dark-600 max-h-48 w-full rounded border border-gray-200 object-contain',
            ],
        ]);
    }

    protected function validate(): void
    {
        if ($this->readonly && $this->disabled) {
            __ts_validation_exception($this, 'The [readonly] and [disabled] cannot be used together.');
        }

        if ($this->toolbar !== null && $this->toolbar === []) {
            __ts_validation_exception($this, 'The [toolbar] cannot be empty.');
        }
    }
}
