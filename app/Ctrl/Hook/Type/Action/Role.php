<?php
namespace Rhef\Ctrl\Hook\Type\Action;

class Role
{
    public static $rhef_caps = [
        "rhef_core" => true,
        "rhef_dashboard" => true,
        "rhef_lead" => true,
        "rhef_deal" => true,
        "rhef_estimate" => true,
        "rhef_invoice" => true,
        "rhef_client" => true,
        "rhef_project" => true,
        "rhef_action" => true,
        "rhef_business" => true,
        "rhef_contact" => true,
        "rhef_email" => true,
        "rhef_file" => true,
        "rhef_form" => true,
        "ndvp_media" => true,
        "rhef_note" => true,
        "rhef_org" => true,
        "rhef_payment" => true,
        "rhef_person" => true,
        "rhef_setting" => true,
        "rhef_task" => true,
        "rhef_taxonomy" => true,
        "rhef_webhook" => true,
        "rhef_workspace" => true,
    ];

    public function __construct()
    {
        add_action("init", [$this, "update_admin_caps"], 11);
        add_filter("woocommerce_prevent_admin_access", "__return_false");
    }

    public function update_admin_caps()
    {
        $admin_role = get_role("administrator");

        foreach (self::$rhef_caps as $cap => $perm) {
            $admin_role->add_cap($cap, $perm);
        }
    }
}
