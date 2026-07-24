<?php

namespace TallStackUi\Support\Runtime\Components;

use Illuminate\View\ComponentSlot;
use TallStackUi\Support\Runtime\AbstractRuntime;

class ListItemsRuntime extends AbstractRuntime
{
    public function runtime(): array
    {
        $caption = $this->content($this->data('caption'));

        return [
            'content' => [
                'action' => $this->content($this->data('action')),
                'caption' => $caption,
                'menu' => $this->content($this->data('menu')),
                // Plain text projection of the caption so the Alpine search keeps
                // matching rows when the caption is given as a slot with markup.
                'searchable' => $this->searchable($caption),
            ],
        ];
    }

    private function content(mixed $content): ComponentSlot|string|null
    {
        if ($content instanceof ComponentSlot) {
            return $content->isEmpty() ? null : $content;
        }

        return blank($content) ? null : (string) $content;
    }

    private function searchable(ComponentSlot|string|null $caption): string
    {
        if ($caption === null) {
            return '';
        }

        $text = $caption instanceof ComponentSlot ? strip_tags($caption->toHtml()) : $caption;

        return trim((string) preg_replace('/\s+/', ' ', $text));
    }
}
