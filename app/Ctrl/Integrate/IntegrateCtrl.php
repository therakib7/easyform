<?php 
namespace Rhef\Ctrl\Integrate;

use Rhef\Ctrl\Integrate\Form\FormCtrl;
use Rhef\Ctrl\Integrate\Smtp\SmtpCtrl;

class IntegrateCtrl
{
    public function __construct()
    {
        new FormCtrl();
        new SmtpCtrl();
    } 
}
