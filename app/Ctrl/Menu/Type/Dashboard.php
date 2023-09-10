<?php
namespace Rhef\Ctrl\Menu\Type;

use Rhef\Helper\Fns;

class Dashboard
{
    public function __construct()
    {
        if (current_user_can("rhef_core")) {
            add_action("admin_menu", [$this, "add_settings_menu"], 30);
        }
    }

    public function add_settings_menu()
    {
        add_menu_page(
            esc_html__("Testform", "easyform"),
            esc_html__("Testform", "easyform"),
            "rhef_core",
            "rhef",
            [$this, "render"],
            "dashicons-media-spreadsheet",
            2
        );

        add_submenu_page(
            "rhef",
            esc_html__("Dashboard", "easyform"),
            esc_html__("Dashboard", "easyform"),
            "rhef_dashboard",
            "rhef#",
            [$this, "render"]
        );

        $settings_menu = [
            [
                "id" => "form-builder",
                "label" => esc_html__("Test Form Builder", "easyform"),
                "capability" => "rhef_lead",
            ],
        ];

        $settings_menu = apply_filters("rhef_sidebar_menu", $settings_menu);

        foreach ($settings_menu as $menu) {
            $menu_id = $menu["id"];
            add_submenu_page(
                "rhef",
                $menu["label"],
                $menu["label"],
                $menu["capability"],
                "rhef#/" . $menu_id,
                [$this, "render"]
            );
        }

        add_submenu_page(
            "rhef",
            esc_html__("Settings", "easyform"),
            esc_html__("Settings", "easyform"),
            "rhef_setting",
            "rhef#/setting/general",
            [$this, "render"]
        );

        if (rhef()->wage()) {
            global $submenu;
            $permalink = Fns::client_page_url("workspace");
            if ($permalink) {
                $submenu["rhef"][] = [
                    esc_html__("Go to Frontend", "easyform"),
                    "rhef_workspace",
                    $permalink,
                ];
            }
        }

        if (!function_exists("rhefp")) {
            global $submenu;
            $submenu["rhef"][] = [
                "Upgrade to Pro",
                "rhef_core",
                "https://easyform.com",
            ];
        }

        remove_submenu_page("rhef", "rhef");
    }

    function render()
    {
        echo '<div class="wrap"><div id="rhef-dashboard"></div></div>';
    }
}
