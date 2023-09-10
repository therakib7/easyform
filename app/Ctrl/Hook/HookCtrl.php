<?php

namespace Rhef\Ctrl\Hook;

use Rhef\Ctrl\Hook\Type\Filter;
use Rhef\Ctrl\Hook\Type\Action\ActionCtrl;

class HookCtrl
{
    public function __construct()
    {
        new Filter();
        new ActionCtrl();
    }
}
