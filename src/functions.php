<?php

use TallStackUi\Actions\Banner;
use TallStackUi\Actions\Dialog;
use TallStackUi\Actions\Toast;

if (! function_exists('banner')) {
    function banner(): Banner
    {
        $banner = new Banner();

        $banner->flash();

        return $banner;
    }
}

if (! function_exists('dialog')) {
    function dialog(): Dialog
    {
        $dialog = new Dialog();

        $dialog->flash();

        return $dialog;
    }
}

if (! function_exists('toast')) {
    function toast(): Toast
    {
        $toast = new toast();

        $toast->flash();

        return $toast;
    }
}
