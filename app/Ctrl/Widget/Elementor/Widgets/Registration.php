<?php 
namespace Rhef\Ctrl\Widget\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class Registration extends Widget_Base {

	public function get_name() {
		return 'rhef-registration';
	}

	public function get_title() {
		return esc_html__( 'Easyform: Registration', 'easyform' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return [ 'rhef-category' ];
	}

	/**
	 * Register Button_Arrow widget controls.
	 *
	 * @since 1.0
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'easyform' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);  
		
		// $this->add_control(
		// 	'btn_text',
		// 	[
		// 		'label' => esc_html__( 'Button Text', 'easyform' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'default' => 'Registration',
		// 	]
		// ); 

		$this->end_controls_section(); 
	}

	/**
	 * Render Button_Arrow widget output on the frontend.
	 *
	 * @since 1.0
	 */
	protected function render() {  
		$settings = $this->get_settings_for_display(); ?>   
		<?php rhef()->render('public/auth/registration'); ?> 
		<?php 
	} 
} 