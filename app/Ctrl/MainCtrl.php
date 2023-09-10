<?php

namespace Rhef\Ctrl;

use Rhef\Ctrl\Ajax\AjaxCtrl;
use Rhef\Ctrl\Api\ApiCtrl;
use Rhef\Ctrl\Asset\AssetCtrl;
use Rhef\Ctrl\Cron\CronCtrl;
use Rhef\Ctrl\Template\TemplateCtrl;
use Rhef\Ctrl\Hook\HookCtrl;
use Rhef\Ctrl\Integrate\IntegrateCtrl;
use Rhef\Ctrl\Assist\AssistCtrl;
use Rhef\Ctrl\Meta\MetaCtrl;
use Rhef\Ctrl\Menu\MenuCtrl;
use Rhef\Ctrl\Taxonomy\TaxonomyCtrl;
use Rhef\Ctrl\Widget\WidgetCtrl;
use Rhef\Ctrl\Cleanup\Style;

class MainCtrl
{

    public function __construct()
    {

        //if ( is_admin() ) {
        new TaxonomyCtrl();
        new MenuCtrl();
        new AssistCtrl();
        //}
        new AssetCtrl();
        new TemplateCtrl();
        new WidgetCtrl();
        new AjaxCtrl();
        new HookCtrl();
        new MetaCtrl();
        new ApiCtrl();
        new CronCtrl();
        new IntegrateCtrl();
        // Style::getInstance()->init();
    }
}
