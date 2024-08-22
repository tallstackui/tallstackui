<?php

namespace TallStackUi;

use Illuminate\View\Component;
use TallStackUi\Foundation\Support\Concerns\TallStackUiComponent\ManagesBindProperty;
use TallStackUi\Foundation\Support\Concerns\TallStackUiComponent\ManagesClasses;
use TallStackUi\Foundation\Support\Concerns\TallStackUiComponent\ManagesCompilation;
use TallStackUi\Foundation\Support\Concerns\TallStackUiComponent\ManagesOutput;
use TallStackUi\Foundation\Support\Concerns\TallStackUiComponent\ManagesRender;

abstract class TallStackUiComponent extends Component
{
    use ManagesBindProperty;
    use ManagesClasses;
    use ManagesCompilation;
    use ManagesOutput;
    use ManagesRender;
}
