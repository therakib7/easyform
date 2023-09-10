<?php
namespace Rhef\Ctrl\Install\Type;

use Rhef\Helper\Info;

class Update
{
    private $api = 'https://easyform.com/wp-json/rhefa/v1/';

    public function __construct()
    {
        $this->info();
    }

    public function info()
    {

        $version = get_option('rhef_version_install');

        if ( version_compare($version, NDPV_VERSION, '<') ) {
            update_option('rhef_version_install', NDPV_VERSION);
        }

        //get update
        $welcome = ( isset($_GET['page']) && $_GET['page'] == 'rhef-welcome' ) ? true : false;
        $data = null;

        $option = get_option('rhef_subscription');

        $is_welcome_loaded = ($option && isset( $option['welcome_loaded'] ) ) ? true : false;

        if ( $welcome ) {
            if ( !$is_welcome_loaded ) {
                $data['welcome_loaded'] = true;
                update_option('rhef_subscription', $data);
            }
        }

        if ( !$welcome && $is_welcome_loaded ) {
            $get_update = $share_info = true;
            $get_update_at = $share_info_at = false;
            if ( $option ) {
                $get_update = isset( $option['deactivate'] ) && is_array( $option['deactivate'] ) && in_array( 'get_update', $option['deactivate'] ) ? false : true;
                $share_info = isset( $option['deactivate'] ) && is_array( $option['deactivate'] ) && in_array( 'share_info', $option['deactivate'] ) ? false : true;

                $get_update_at = isset( $option['get_update_at'] ) ? true : false;
                $share_info_at = isset( $option['share_info_at'] ) ? true : false;
            }

            if ( ( !$get_update_at || !$share_info_at ) ) {

                $info = new Info;
                $data = $info->wp( $get_update, $share_info );

                wp_remote_post( $this->api . 'installer', [
                    'timeout' => 0.01,
                    'body' => $data,
                    'blocking' => false,
                    'sslverify' => false
                ] );

                if ( $option ) {
                    if ( !$get_update_at ) {
                        $option['get_update_at'] = current_time('timestamp');
                    }

                    if ( !$share_info_at ) {
                        $option['share_info_at'] = current_time('timestamp');
                    }

                    $data = $option;
                } else {
                    if ( !$get_update_at ) {
                        $data['get_update_at'] = current_time('timestamp');
                    }

                    if ( !$share_info_at ) {
                        $data['share_info_at'] = current_time('timestamp');
                    }
                }

                if ( $data ) {
                    update_option('rhef_subscription', $data);
                }
            }
        }
    }
}
