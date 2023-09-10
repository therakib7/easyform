<?php
/**
 * @package   RakibulHasan - Easyform
 * @author    RakibulHasan <support@easyform.com>
 * @link      https://easyform.com
 * @copyright 2023 RakibulHasan
 *
 * Plugin Name: Easyform
 * Plugin URI: https://wordpress.org/plugins/easyform
 * Author: RakibulHasan
 * Author URI: https://easyform.com
 * Version: 1.0.0
 * Description: Create all kinds of form easy
 * Text Domain: easyform
 * Domain Path: /languages
 *
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Rhef class
 *
 * @class Rhef The class that holds the entire Rhef plugin
 *
 */
final class Rhef {

    /**
     * Plugin version
     *
     * @since 1.0.0
     * @var string
     */
    public $version = '1.0.0';

	/**
     * Instance of self
     * @since 1.0.0
     * @var Rhef
     */
    private static $instance = null;

    /**
     * Minimum PHP version required
     * @since 1.0.0
     * @var string
     */
    private $min_php = '7.2';

    /**
     * Constructor for the Rhef class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct() {

        require_once __DIR__ . '/vendor/autoload.php';

        $this->define_constants();

        //new Constant();
        // new Rhef\Ctrl\Install\InstallCtrl();
        $this->init_hooks();
    }

	/**
     * Initializes the Rhef() class
     *
     * Checks for an existing Rhef() instance
     * and if it doesn't find one, create it.
     */
    public static function init() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

	public function wage()
    {
        return function_exists('rhefp') && rhefp()->wage();
    }

    /**
     * Define all constants
     * @since 1.0.0
     * @return void
     */
    public function define_constants() {
        $this->define( 'NDPV_VERSION', $this->version );
        $this->define( 'NDPV_FILE', __FILE__ );
        $this->define( 'NDPV_PATH', plugin_dir_path(__FILE__) );
        $this->define( 'NDPV_URL', plugins_url('', __FILE__) );
        $this->define( 'NDPV_SLUG', basename(dirname(__FILE__)) );
        $this->define( 'NDPV_ASSEST', plugins_url( 'public', __FILE__ ) );
        $this->define( 'NDPV_TEMPLATE_DEBUG_MODE', false );
    }

    /**
     * Define constant if not already defined
     *
     * @since 1.0.0
     *
     * @param string      $name
     * @param string|bool $value
     *
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    private function init_hooks() {

        add_action('plugins_loaded', [$this, 'on_plugins_loaded'], -1);
        add_action('init', [$this, 'initial'], 1);
    }

    public function initial() {
        do_action('rhef_before_init');

        $this->localization_setup();

        new Rhef\Ctrl\MainCtrl();

        do_action('rhef_init');
    }

    public function on_plugins_loaded() {
        do_action('rhef_loaded');
    }

    /**
     * Initialize plugin for localization
     * @since 1.0.0
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'easyform', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    /**
     * What type of request is this?
     *
     * @param string $type admin, ajax, cron or public.
     * @since 1.0.0
     * @return bool
     */
    public function is_request( $type ) {
        switch( $type ) {
            case 'admin':
                return is_admin();
            case 'public':
                return ( !is_admin() || defined('DOING_AJAX') ) && !defined('DOING_CRON');
            case 'ajax':
                return defined('DOING_AJAX');
            case 'cron':
                return defined('DOING_CRON');
        }
    }

    /**
     * Get the plugin path.
     * @since 1.0.0
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit(plugin_dir_path(__FILE__));
    }

    /**
     * Get the template path.
     * @since 1.0.0
     * @return string
     */
    public function get_template_path() {
        return apply_filters('rhef_template_path', 'rhef/templates/');
    }

    /**
     * Get the template partial path.
     * @since 1.0.0
     * @return string
     */
    public function get_partial_path( $path = null, $args = []) {
        Rhef\Helper\Fns::get_template_part( 'partial/' . $path, $args );
    }

    /**
     * Get asset location
     *
     * @param $file
     * @since 1.0.0
     * @return string
     */
    public function get_asset_uri($file) {
        $file = ltrim($file, '/');

        return trailingslashit(NDPV_URL . '/public') . $file;
    }

    /**
     * @param $file
     * @since 1.0.0
     * @return string
     */
    public function render($viewName, $args = array(), $return = false) {
        $path = str_replace(".", "/", $viewName);
        $viewPath = NDPV_PATH . '/view/' . $path . '.php';
        if ( !file_exists($viewPath) ) {
            return;
        }

        if ( $args ) {
            extract($args);
        }

        if ( $return ) {
            ob_start();
            include $viewPath;

            return ob_get_clean();
        }
        include $viewPath;
    }

    /**
     * Get all optoins field value
     *
     * @since 1.0.0
     * @return void
     */
    public function get_options() {

        $option_field = func_get_args()[0];
        $result = get_option( $option_field );
        $func_args = func_get_args();
        array_shift( $func_args );

        foreach ( $func_args as $arg ) {
            if ( is_array($arg) ) {
                if ( !empty( $result[$arg[0]] ) ) {
                    $result = $result[$arg[0]];
                } else {
                    $result = $arg[1];
                }
            } else {
                if ( !empty($result[$arg] ) ) {
                    $result = $result[$arg];
                } else {
                    $result = null;
                }
            }
        }
        return $result;
    }

    /**
     * Get preset default data
     *
     * @since 1.0.0
     * @return void
     */
    public function get_default()
    {
        $preset = new Rhef\Helper\Preset;
        $result = $preset->data();
        $func_args = func_get_args();

        foreach ($func_args as $arg) {
            if (is_array($arg)) {
                if (!empty($result[$arg[0]])) {
                    $result = $result[$arg[0]];
                } else {
                    $result = $arg[1];
                }
            } else {
                if (!empty($result[$arg])) {
                    $result = $result[$arg];
                } else {
                    $result = null;
                }
            }
        }
        return $result;
    }

    /**
     * Get Workspace ID
     *
     * @since 1.0.0
     * @return init|null
     */

    public function get_workspace_id() {
        $option = get_option( 'rhef_workspace_default' );
        return $option ? absint( $option ) : null;
    }
}

/**
 * Load Dokan Plugin when all plugins loaded
 *
 * @return Rhef
 */
function rhef() {
    return Rhef::init();
}
rhef(); // Run Rhef Plugin