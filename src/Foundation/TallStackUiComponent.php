<?php

namespace TallStackUi\Foundation;

use Illuminate\View\Component;
use TallStackUi\Foundation\Components\Concerns\TallStackUiComponent\ManagesBindProperty;
use TallStackUi\Foundation\Components\Concerns\TallStackUiComponent\ManagesClasses;
use TallStackUi\Foundation\Components\Concerns\TallStackUiComponent\ManagesCompilation;
use TallStackUi\Foundation\Components\Concerns\TallStackUiComponent\ManagesOutput;
use TallStackUi\Foundation\Components\Concerns\TallStackUiComponent\ManagesRender;

abstract class TallStackUiComponent extends Component
{
    use ManagesBindProperty;
    use ManagesClasses;
    use ManagesCompilation;
    use ManagesOutput;
    use ManagesRender;
}
