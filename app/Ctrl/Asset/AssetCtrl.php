<?php

namespace Rhef\Ctrl\Asset;

use Rhef\Helper\Fns;
use Rhef\Helper\I18n;

class AssetCtrl
{
    private $suffix;
    private $version;
    public $current_user_caps;

    public function __construct()
    {
        $this->suffix = defined("SCRIPT_DEBUG") && SCRIPT_DEBUG ? "" : ".min";
        $this->version =
            defined("WP_DEBUG") && WP_DEBUG ? time() : rhef()->version;
        $this->current_user_caps = array_keys(wp_get_current_user()->allcaps);

        add_action("wp_enqueue_scripts", [$this, "public_scripts"], 9999);
        add_action("admin_enqueue_scripts", [$this, "admin_scripts"], 9999);

        //remove thank you text from easyform dashboard
        if (isset($_GET["page"]) && $_GET["page"] == "rhef") {
            add_filter("admin_footer_text", "__return_empty_string", 11);
            add_filter("update_footer", "__return_empty_string", 11);
        }

        add_filter("show_admin_bar", [$this, "hide_admin_bar"]);

        add_filter('script_loader_tag', [$this, 'add_type_attribute'] , 10, 3);

        add_action("current_screen", function () {
            if (!$this->is_plugins_screen()) {
                return;
            }

            add_action("admin_enqueue_scripts", [
                $this,
                "enqueue_feedback_dialog",
            ]);
        });
    }

    public function hide_admin_bar($show)
    {
        if (
            is_page_template([
                "workspace-template.php"
            ])
        ) {
            return false;
        }
        return $show;
    }

    private function admin_public_script()
    {
    }

    private function dashboard_script()
    {
        //font family
        if (
            (isset($_GET["page"]) && $_GET["page"] == "rhef-welcome") ||
            (isset($_GET["page"]) && $_GET["page"] == "rhef") ||
            is_page_template([
                "workspace-template.php"
            ]) ||
            $this->is_plugins_screen()
        ) {
            wp_enqueue_style(
                "rhef-google-font",
                "https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap",
                [],
                $this->version
            );
            wp_enqueue_style(
                "rhef-main",
                rhef()->get_asset_uri("css/main{$this->suffix}.css"),
                [],
                $this->version
            );
        }
        if (isset($_GET["page"]) && $_GET["page"] == "rhef-welcome") {
            wp_enqueue_style(
                "rhef-welcome",
                rhef()->get_asset_uri("css/welcome{$this->suffix}.css"),
                [],
                $this->version
            );
            wp_enqueue_script(
                "rhef-welcome",
                rhef()->get_asset_uri("/js/welcome{$this->suffix}.js"),
                ['wp-element'],
                $this->version,
                true
            );
            wp_localize_script("rhef-welcome", "rhef", [
                "apiUrl" => esc_url(rest_url()),
                "nonce" => wp_create_nonce("wp_rest"),
                "dashboard" => menu_page_url("rhef", false),
                "assetImgUri" => rhef()->get_asset_uri("img/"),
                "logo" => Fns::brand_logo(),
                "i18n" => I18n::dashboard(),
            ]);
        }

        if (
            (isset($_GET["page"]) && $_GET["page"] == "rhef") ||
            is_page_template([
                "workspace-template.php"
            ])
        ) {
            wp_enqueue_style(
                "rhef-dashboard",
                rhef()->get_asset_uri("css/dashboard{$this->suffix}.css"),
                [],
                $this->version
            );
            wp_enqueue_script(
                "rhef-vite-client",
                // rhef()->get_asset_uri("/js/dashboard{$this->suffix}.js"),
                'http://localhost:3000/@vite/client',
                ['wp-element'],
                $this->version,
                false
            );

            ob_start();
            ?> 
                import { injectIntoGlobalHook } from "http://localhost:3000/@react-refresh";
                injectIntoGlobalHook(window);
                window.$RefreshReg$ = () => {};
                window.$RefreshSig$ = () => (type) => type; 
            <?php
            $script = ob_get_clean();

            wp_add_inline_script('rhef-vite-client', $script);

            wp_enqueue_script(
                "rhef-dashboard",
                // rhef()->get_asset_uri("/js/dashboard{$this->suffix}.js"),
                'http://localhost:3000/src/main.tsx',
                ['wp-element'],
                $this->version,
                true
            );
            $current_user = wp_get_current_user();
            wp_localize_script("rhef-dashboard", "rhef", [
                "apiUrl" => esc_url(rest_url()),
                "version" => rhef()->version,
                "dashboard" => admin_url("admin.php?page=rhef"),
                "nonce" => wp_create_nonce("wp_rest"),
                "date_format" => Fns::phpToMomentFormat(
                    get_option("date_format")
                ),
                "assetImgUri" => rhef()->get_asset_uri("img/"),
                "logo" => Fns::brand_logo(),
                "assetUri" => NDPV_ASSEST,
                "profile" => [
                    "name" => $current_user->display_name,
                    "img" => get_avatar_url($current_user->ID, [
                        "size" => "36",
                    ]),
                    "logout" => wp_logout_url(get_permalink()),
                ],
                "i18n" => I18n::dashboard(),
                "caps" => $this->current_user_caps,
            ]);            
        }
    }

    public function public_scripts()
    {
        $this->admin_public_script();

        //wp_enqueue_style( 'easyform-main', rhef()->get_asset_uri( "public/css/main{$this->suffix}.css" ), array(), $this->version );

        $this->dashboard_script();
    }

    public function admin_scripts()
    {
        $this->admin_public_script();
        $this->dashboard_script();
    }

    public function add_type_attribute($tag, $handle, $src) {
    
        // Check if the script handle matches the one you want to modify
        if ( 
           'rhef-dashboard' == $handle || 
           'rhef-vite-client' == $handle
        ) {
            // Add the type="module" attribute to the script tag
            $tag = str_replace( '<script', '<script type="module"', $tag );
        }

        return $tag;
    }

    /**
     * Enqueue feedback dialog scripts.
     *
     * Registers the feedback dialog scripts and enqueues them.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueue_feedback_dialog()
    {
        add_action("admin_footer", [$this, "deactivate_feedback_dialog"]);
        wp_enqueue_script(
            "rhef-feedback",
            rhef()->get_asset_uri("/js/feedback{$this->suffix}.js"),
            [],
            $this->version,
            true
        );
        wp_localize_script("rhef-feedback", "rhef", [
            "ajaxurl" => esc_url(admin_url("admin-ajax.php")),
        ]);
    }

    /**
     * @since 1.0.1.5
     */
    public function deactivate_feedback_dialog()
    {
        rhef()->render("feedback/form");
    }

    /**
     * @since 1.0.1.5
     */
    private function is_plugins_screen()
    {
        if (!function_exists("get_current_screen")) {
            require_once ABSPATH . "/wp-admin/includes/screen.php";
        }

        if (is_admin()) {
            return in_array(get_current_screen()->id, [
                "plugins",
                "plugins-network",
            ]);
        } else {
            return false;
        }
    }
}
