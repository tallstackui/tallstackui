<?php

namespace Tests\Browser;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

trait BrowserFunctions
{
    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        $router->get('/searchable-simple', fn () => [
            [
                'label' => 'delectus aut autem',
                'value' => 'delectus aut autem',
            ],
            [
                'label' => 'quis ut nam facilis et officia qui',
                'value' => 'quis ut nam facilis et officia qui',
            ],
            [
                'label' => 'fugiat veniam minus',
                'value' => 'fugiat veniam minus',
            ],
            [
                'label' => 'et porro tempora',
                'value' => 'et porro tempora',
            ],
            [
                'label' => 'laboriosam mollitia et enim quasi adipisci quia provident illum',
                'value' => 'laboriosam mollitia et enim quasi adipisci quia provident illum',
            ],
        ])->name('searchable.simple');

        $router->get('/searchable-filtered', function (Request $request) {
            $options = collect([
                [
                    'label' => 'delectus aut autem',
                    'value' => 'delectus aut autem',
                ],
                [
                    'label' => 'quis ut nam facilis et officia qui',
                    'value' => 'quis ut nam facilis et officia qui',
                ],
                [
                    'label' => 'fugiat veniam minus',
                    'value' => 'fugiat veniam minus',
                ],
                [
                    'label' => 'et porro tempora',
                    'value' => 'et porro tempora',
                ],
                [
                    'label' => 'laboriosam mollitia et enim quasi adipisci quia provident illum',
                    'value' => 'laboriosam mollitia et enim quasi adipisci quia provident illum',
                ],
            ]);

            if (! $request->has('search')) {
                return $options;
            }

            return [
                [
                    'label' => 'et porro tempora',
                    'value' => 'et porro tempora',
                ],
            ];
        })->name('searchable.filtered');
    }
}
