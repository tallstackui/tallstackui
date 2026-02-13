<?php

namespace TallStackUi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TallStackUi\Support\CommandPalette\Callback;
use TallStackUi\Support\CommandPalette\ItemSelected;

class TallStackUiCommandPaletteController
{
    public function __invoke(Request $request): JsonResponse
    {
        abort_unless($request->hasValidSignature(), 403);

        $actionable = config('ts-ui.components.command-palette.1.actionable');

        abort_unless($actionable && class_exists($actionable), 404, '[TallStackUI] Actionable class not found.');

        abort_unless(method_exists($actionable, '__invoke'), 422, '[TallStackUI] Actionable class must have an __invoke method.');

        $item = (array) $request->input('item', []);

        /** @var Callback $callback */
        $callback = app($actionable)(new ItemSelected(
            search: $request->input('search', ''),
            label: $item['label'] ?? null,
            value: $item['value'] ?? null,
            description: $item['description'] ?? null,
            image: $item['image'] ?? null,
            icon: $item['icon'] ?? null,
            additional: $item['additional'] ?? [],
        ));

        return response()->json($callback->toArray());
    }
}
