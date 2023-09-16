<?php
namespace Rhef\Ctrl\Install;

use Rhef\Ctrl\Install\Type\DB;
use Rhef\Ctrl\Install\Type\Update;
use Rhef\Ctrl\Install\Type\Page;
use Rhef\Ctrl\Install\Type\Taxonomy;

class InstallCtrl
{
    public function __construct()
    {
        register_activation_hook(NDPV_FILE, array($this, 'plugin_activate'));
        add_filter('cron_schedules', array($this, 'custom_schedule'));
        register_activation_hook(NDPV_FILE, array($this, 'schedule_my_cron'));

        add_action('admin_init', array($this, 'insert_data'));
        add_action('admin_init', array($this, 'plugin_redirect'));
    }

    public function custom_schedule($schedules)
    {
        $schedules['half_minute'] = array(
            'interval'  => 30,
            'display'   => esc_html__('Every Half Minute', 'easyform')
        );

        $schedules['one_minute'] = array(
            'interval'  => 60,
            'display'   => esc_html__('Every 1 Minute', 'easyform')
        );
        return $schedules;
    }

    public function schedule_my_cron()
    {

        //set cron job
        if (!wp_next_scheduled('rhef_hourly_event')) {
            wp_schedule_event(time(), 'hourly', 'rhef_hourly_event');
        }

        if (!wp_next_scheduled('rhef_half_minute_event')) {
            wp_schedule_event(time(), 'half_minute', 'rhef_half_minute_event');
        }

        if (!wp_next_scheduled('rhef_one_minute_event')) {
            wp_schedule_event(time(), 'one_minute', 'rhef_one_minute_event');
        }
    }

    public function plugin_activate()
    {
        add_option('rhef_active', true);
    }

    public function plugin_redirect()
    {
        if ( get_option('rhef_active', false) ) {
            delete_option('rhef_active');

            wp_redirect(admin_url('admin.php?page=rhef-welcome'));
        }
    }

    public function insert_data()
    {
        $version = get_option('rhef_version', '0.1.0');
        if (version_compare($version, NDPV_VERSION, '<')) {
            update_option('rhef_version', NDPV_VERSION);
        }

        if ( version_compare($version, '1.0.0', '<') ) {
            new Page();
            new DB();
            new Taxonomy();

            // $uploads_dir = trailingslashit(wp_upload_dir()['basedir']) . 'easyform';
            // wp_mkdir_p($uploads_dir);
        }

        if ( version_compare($version, '1.0.1.4', '<') ) {
            $term_id = wp_insert_term(
                'Unit', // the term
                'rhef_estinv_qty_type', // the taxonomy
            );
            if ( !is_wp_error($term_id) ) {
                update_term_meta($term_id['term_id'], 'tax_pos', $term_id['term_id']);
            }
        }

        new Update();
    }
}
