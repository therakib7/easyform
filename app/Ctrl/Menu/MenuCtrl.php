<?php
namespace Rhef\Ctrl\Menu;

use Rhef\Ctrl\Menu\Type\Dashboard;
use Rhef\Ctrl\Menu\Type\Welcome;

class MenuCtrl {

	public function __construct() {
		if ( is_admin() ) {
			new Dashboard();
			new Welcome();
		}
	}
}