<?php

namespace TallStackUi\View\Components;

use Illuminate\View\Component;
use TallStackUi\Foundation\Traits\BaseComponent\ManagesBindProperty;
use TallStackUi\Foundation\Traits\BaseComponent\ManagesClasses;
use TallStackUi\Foundation\Traits\BaseComponent\ManagesCompilation;
use TallStackUi\Foundation\Traits\BaseComponent\ManagesOutput;
use TallStackUi\Foundation\Traits\BaseComponent\ManagesRender;

abstract class BaseComponent extends Component
{
    use ManagesBindProperty;
    use ManagesClasses;
    use ManagesCompilation;
    use ManagesOutput;
    use ManagesRender;
}
