<?php

namespace TallStackUi;

use Illuminate\View\Component;
use TallStackUi\Foundation\Support\Concerns\BaseComponent\ManagesClasses;
use TallStackUi\Foundation\Support\Concerns\BaseComponent\ManagesCompilation;
use TallStackUi\Foundation\Support\Concerns\BaseComponent\ManagesOutput;
use TallStackUi\Foundation\Support\Concerns\BaseComponent\ManagesRender;

abstract class TallStackUiComponent extends Component
{
    use ManagesClasses;
    use ManagesCompilation;
    use ManagesOutput;
    use ManagesRender;
}
