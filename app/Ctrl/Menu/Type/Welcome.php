<?php 
namespace Rhef\Ctrl\Menu\Type;

class Welcome
{

	public function __construct()
	{
		add_action('admin_menu', [$this, 'add_settings_menu'], 30);
	}

	public function add_settings_menu()
	{
		add_menu_page(
			esc_html__('Easyform Welcome', 'easyform'),
			esc_html__('Easyform Welcome', 'easyform'),
			'manage_options',
			'rhef-welcome',
			array($this, 'main_settings'),
			'dashicons-groups',
			30
		);

		add_action('admin_menu', function () {
			remove_menu_page('rhef-welcome');
		}, 100);
	}

	function main_settings()
	{
		echo '<div class="wrap" id="rhef-welcome"></div>';
	}
}
