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
        $search = $request->input('search', '');

        /** @var Callback $callback */
        $callback = app($actionable)(new ItemSelected(...$item, search: $search));

        return response()->json($callback->toArray());
    }
}
