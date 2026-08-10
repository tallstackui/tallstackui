<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\ViewException;
use TallStackUi\Components\Avatar\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-avatar label="Lorem" />')
    ->render()
    ->toContain('Lorem');

it('can render the sizes through the shorthands', function (string $size, string $expected) {
    expect("<x-avatar label=\"Lorem\" {$size} />")->render()->toContain($expected);
})->with([
    'xs' => ['xs', 'w-6 h-6'],
    'sm' => ['sm', 'w-8 h-8'],
    'md' => ['md', 'w-12 h-12'],
    'lg' => ['lg', 'w-14 h-14'],
    'xl' => ['xl', 'w-16 h-16'],
    '2xl' => ['2xl', 'w-20 h-20'],
    '3xl' => ['3xl', 'w-24 h-24'],
    '4xl' => ['4xl', 'w-28 h-28'],
    '5xl' => ['5xl', 'w-32 h-32'],
    '6xl' => ['6xl', 'w-36 h-36'],
    '7xl' => ['7xl', 'w-40 h-40'],
]);

it('can render the shorthands through the bound form', function (string $size, string $expected) {
    expect("<x-avatar label=\"Lorem\" :{$size}=\"true\" />")->render()->toContain($expected);
})->with([
    'lg' => ['lg', 'w-14 h-14'],
    '7xl' => ['7xl', 'w-40 h-40'],
]);

it('can fall back to the default when the bound shorthand is false', function (string $size) {
    expect("<x-avatar label=\"Lorem\" :{$size}=\"false\" />")->render()->toContain('w-12 h-12');
})->with(['lg', '7xl']);

it('cannot leak the shorthand into the rendered tag', function (string $size) {
    expect("<x-avatar text=\"AJ\" {$size} />")->render()->not->toContain($size.'=');
})->with(['xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl']);

it('cannot use more than one size at a time', function () {
    $this->expectException(ViewException::class);

    expect('<x-avatar label="Lorem" sm 7xl />')->render();
});

it('can render md by default')
    ->expect('<x-avatar label="Lorem" />')
    ->render()
    ->toContain('w-12 h-12');

it('can render the sizes through the size attribute', function (string $size, string $expected) {
    expect("<x-avatar label=\"Lorem\" size=\"{$size}\" />")->render()->toContain($expected);
})->with([
    'xs' => ['xs', 'w-6 h-6'],
    'sm' => ['sm', 'w-8 h-8'],
    'md' => ['md', 'w-12 h-12'],
    'lg' => ['lg', 'w-14 h-14'],
    'xl' => ['xl', 'w-16 h-16'],
    '2xl' => ['2xl', 'w-20 h-20'],
    '3xl' => ['3xl', 'w-24 h-24'],
    '4xl' => ['4xl', 'w-28 h-28'],
    '5xl' => ['5xl', 'w-32 h-32'],
    '6xl' => ['6xl', 'w-36 h-36'],
    '7xl' => ['7xl', 'w-40 h-40'],
]);

it('can render the presence dot scaled to the size', function (string $size, string $expected) {
    expect("<x-avatar text=\"AJ\" size=\"{$size}\" presence />")->render()->toContain($expected);
})->with([
    'xl' => ['xl', 'h-4 w-4'],
    '2xl' => ['2xl', 'h-5 w-5'],
    '3xl' => ['3xl', 'h-6 w-6'],
    '4xl' => ['4xl', 'h-7 w-7'],
    '5xl' => ['5xl', 'h-8 w-8'],
    '6xl' => ['6xl', 'h-9 w-9'],
    '7xl' => ['7xl', 'h-10 w-10'],
]);

it('can prioritize the shorthand over the size attribute')
    ->expect('<x-avatar label="Lorem" size="7xl" sm />')
    ->render()
    ->toContain('w-8 h-8')
    ->not
    ->toContain('w-40 h-40');

it('can render the size from the configuration', function () {
    config()->set('ts-ui.components.avatar.1.size', '3xl');

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-avatar label="Lorem" />')->render()->toContain('w-24 h-24');
    } finally {
        config()->set('ts-ui.components.avatar.1.size', 'md');

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('can let the size attribute win over the configuration', function () {
    config()->set('ts-ui.components.avatar.1.size', '3xl');

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-avatar label="Lorem" size="5xl" />')->render()->toContain('w-32 h-32');
    } finally {
        config()->set('ts-ui.components.avatar.1.size', 'md');

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('cannot use an invalid size', function () {
    $this->expectException(ViewException::class);

    expect('<x-avatar label="Lorem" size="9xl" />')->render();
});

it('can render square')
    ->expect('<x-avatar label="Lorem" lg square />')
    ->render()
    ->not
    ->toContain('rounded-full');

it('can render placeholder')
    ->expect('<x-avatar />')
    ->render()
    ->toContain('svg');

it('can render image')
    ->expect('<x-avatar image="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png" />')
    ->render()
    ->toContain('src="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png"');

it('can render image with alt')
    ->expect('<x-avatar image="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png" text="Beca" />')
    ->render()
    ->toContain('src="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png"')
    ->toContain('alt="Beca');

it('can render presence')
    ->expect('<x-avatar text="AJ" presence />')
    ->render()
    ->toContain('relative inline-flex')
    ->toContain('bg-green-500');

it('cannot let the presence wrapper stretch as a flex item')
    ->expect('<x-avatar text="AJ" presence />')
    ->render()
    ->toContain('relative inline-flex w-fit');

it('cannot render presence without it')
    ->expect('<x-avatar text="AJ" />')
    ->render()
    ->not
    ->toContain('bg-green-500');

it('can render presence with custom color')
    ->expect('<x-avatar text="AJ" presence presence-color="red" />')
    ->render()
    ->toContain('bg-red-500');

it('can render presence at right-top by default')
    ->expect('<x-avatar text="AJ" presence />')
    ->render()
    ->toContain('top-0 right-0');

it('can render presence at right-bottom')
    ->expect('<x-avatar text="AJ" presence presence-position="right-bottom" />')
    ->render()
    ->toContain('bottom-0 right-0');

it('can render presence at left-top')
    ->expect('<x-avatar text="AJ" presence presence-position="left-top" />')
    ->render()
    ->toContain('top-0 left-0');

it('can render presence at left-bottom')
    ->expect('<x-avatar text="AJ" presence presence-position="left-bottom" />')
    ->render()
    ->toContain('bottom-0 left-0');

it('can nudge the presence dot into the corner when square', function (string $position, string $expected) {
    expect("<x-avatar text=\"AJ\" square presence presence-position=\"{$position}\" />")->render()->toContain($expected);
})->with([
    'right-top' => ['right-top', 'translate-x-[14.6%] -translate-y-[14.6%]'],
    'right-bottom' => ['right-bottom', 'translate-x-[14.6%] translate-y-[14.6%]'],
    'left-top' => ['left-top', '-translate-x-[14.6%] -translate-y-[14.6%]'],
    'left-bottom' => ['left-bottom', '-translate-x-[14.6%] translate-y-[14.6%]'],
]);

it('cannot nudge the presence dot when the avatar is round')
    ->expect('<x-avatar text="AJ" presence />')
    ->render()
    ->not
    ->toContain('translate-x-[14.6%]');

it('can render presence with pulse')
    ->expect('<x-avatar text="AJ" presence pulse />')
    ->render()
    ->toContain('animate-ping');

it('cannot render presence with pulse without pulse')
    ->expect('<x-avatar text="AJ" presence />')
    ->render()
    ->not
    ->toContain('animate-ping');

it('cannot use invalid presence position', function () {
    $this->expectException(ViewException::class);

    expect('<x-avatar text="AJ" presence presence-position="center" />')->render();
});

function avatar_model(array $attributes = ['name' => 'AJ Meireles', 'email' => 'AJ@Mail.com']): Model
{
    return new class($attributes) extends Model
    {
        public function __construct(array $attributes = [])
        {
            parent::__construct();

            $this->attributes = $attributes;
        }
    };
}

it('can render a gravatar from an inline email')
    ->expect('<x-avatar gravatar="aj@mail.com" />')
    ->render()
    ->toContain('https://gravatar.com/avatar/'.hash('sha256', 'aj@mail.com'));

it('can normalize the email before hashing it')
    ->expect('<x-avatar gravatar=" AJ@Mail.com " />')
    ->render()
    ->toContain(hash('sha256', 'aj@mail.com'));

it('can render a gravatar from the model email', function () {
    expect('<x-avatar :model="$user" gravatar />')
        ->render(['user' => avatar_model()])
        ->toContain(hash('sha256', 'aj@mail.com'));
});

it('can point the gravatar at another model column', function () {
    expect('<x-avatar :model="$user" gravatar="contact" />')
        ->render(['user' => avatar_model(['name' => 'AJ', 'contact' => 'other@mail.com'])])
        ->toContain(hash('sha256', 'other@mail.com'));
});

it('can fall back to the letters when the gravatar is missing')
    ->expect('<x-avatar gravatar="aj@mail.com" text="AJ" />')
    ->render()
    ->toContain('d='.urlencode('https://ui-avatars.com/api?name=AJ'));

it('can fall back to the gravatar default without a name')
    ->expect('<x-avatar gravatar="aj@mail.com" />')
    ->render()
    ->toContain('d=mp');

it('can ask gravatar for twice the rendered size', function (string $size, string $expected) {
    expect("<x-avatar gravatar=\"aj@mail.com\" size=\"{$size}\" />")->render()->toContain($expected);
})->with([
    'md' => ['md', 's=96'],
    'xl' => ['xl', 's=128'],
    '7xl' => ['7xl', 's=320'],
]);

it('can ask ui-avatars for twice the rendered size', function () {
    expect('<x-avatar :model="$user" 7xl />')
        ->render(['user' => avatar_model()])
        ->toContain('size=320');
});

it('can let the image win over the gravatar')
    ->expect('<x-avatar image="https://cdn.test/a.png" gravatar="aj@mail.com" />')
    ->render()
    ->toContain('src="https://cdn.test/a.png"')
    ->not
    ->toContain('gravatar.com');

it('can read the gravatar defaults from the configuration', function () {
    config()->set('ts-ui.components.avatar.1.gravatar', ['default' => 'identicon', 'rating' => 'pg']);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-avatar gravatar="aj@mail.com" />')->render()->toContain('d=identicon')->toContain('r=pg');
    } finally {
        config()->set('ts-ui.components.avatar.1.gravatar', ['default' => 'mp', 'rating' => 'g']);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('cannot use an invalid gravatar default', function () {
    $this->expectException(ViewException::class);

    expect('<x-avatar gravatar="aj@mail.com" gravatar-default="bogus" />')->render();
});

it('cannot use an invalid gravatar rating', function () {
    $this->expectException(ViewException::class);

    expect('<x-avatar gravatar="aj@mail.com" gravatar-rating="bogus" />')->render();
});

it('cannot use the gravatar without an email', function () {
    $this->expectException(ViewException::class);

    expect('<x-avatar text="AJ" gravatar />')->render();
});
