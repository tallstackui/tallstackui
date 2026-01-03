<?php

namespace TallStackUi;

use Illuminate\View\Component;
use TallStackUi\Support\Concerns\BaseComponent\ManagesClasses;
use TallStackUi\Support\Concerns\BaseComponent\ManagesCompilation;
use TallStackUi\Support\Concerns\BaseComponent\ManagesOutput;
use TallStackUi\Support\Concerns\BaseComponent\ManagesRender;

abstract class TallStackUiComponent extends Component
{
    use ManagesClasses;
    use ManagesCompilation;
    use ManagesOutput;
    use ManagesRender;
}
