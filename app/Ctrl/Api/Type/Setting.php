<?php

namespace Rhef\Ctrl\Api\Type;

class Setting
{
    public function __construct()
    {
        add_action("rest_api_init", [$this, "rest_routes"]);
    }

    public function rest_routes()
    {
        register_rest_route("rhef/v1", "/settings", [
            "methods" => "GET",
            "callback" => [$this, "get"],
            "permission_callback" => [$this, "get_per"],
        ]);

        register_rest_route("rhef/v1", "/settings", [
            "methods" => "POST",
            "callback" => [$this, "create"],
            "permission_callback" => [$this, "create_per"],
        ]);
    }

    public function get($req)
    {
        $param = $req->get_params();
        $reg_errors = new \WP_Error();

        $tab = isset($param["tab"]) ? sanitize_text_field($param["tab"]) : null;

        if (empty($tab)) {
            $reg_errors->add(
                "field",
                esc_html__("Tab is missing", "easyform")
            );
        }

        if ($reg_errors->get_error_messages()) {
            wp_send_json_error($reg_errors->get_error_messages());
        } else {
            $data = [];

            if ($tab == "general_module") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["deactivate"] = [];
                }
            }

            if ($tab == "subscription") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    if (!isset($option["deactivate"])) {
                        $option["deactivate"] = [];
                    }
                    $data = $option;
                } else {
                    $data["deactivate"] = [];
                }
            }

            if ($tab == "email_footer") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                    $white_label = get_option("rhef_white_label");
                    $data["logo"] = null;
                    if ($white_label && isset($white_label["logo"])) {
                        $data["logo"] = $white_label["logo"];
                        $logo_id = $white_label["logo"];
                        $logoData = null;
                        if ($logo_id) {
                            $logo_src = wp_get_attachment_image_src(
                                $logo_id,
                                "thumbnail"
                            );
                            if ($logo_src) {
                                $logoData = [];
                                $logoData["id"] = $logo_id;
                                $logoData["src"] = $logo_src[0];
                            }
                        }
                        $data["logo"] = $logoData;
                    }
                } else {
                    $data["active"] = true;
                    $data["text"] = "<p>Powered by</p>
<h3>Easyform</h3>";
                    $data["logo"] = null;
                }
            }

            if ($tab == "estinv_currency") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["currency"] = "USD";
                    $data["lang"] = "en";
                }
            }

            if ($tab == "estimate_general") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["prefix"] = "Est-";
                }
            }

            if ($tab == "estimate_reminder") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["status"] = false;
                    $data["due_date"] = false;
                    $data["before"] = [];
                    $data["after"] = [];
                }
            }

            if ($tab == "invoice_general") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["prefix"] = "Inv-";
                }
            }

            if ($tab == "invoice_reminder") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["status"] = false;
                    $data["due_date"] = false;
                    $data["before"] = [];
                    $data["after"] = [];
                }
            }

            if ($tab == "payment_wc") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["status"] = false;
                }
            }

            if ($tab == "email_estimate_default") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["subject"] = rhef()->get_default(
                        "email_template",
                        "estimate",
                        "default",
                        "subject"
                    );
                    $data["msg"] = rhef()->get_default(
                        "email_template",
                        "estimate",
                        "default",
                        "msg"
                    );
                }
            }

            if ($tab == "email_estimate_reminder") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["subject"] = rhef()->get_default(
                        "email_template",
                        "estimate",
                        "reminder",
                        "subject"
                    );
                    $data["msg"] = rhef()->get_default(
                        "email_template",
                        "estimate",
                        "reminder",
                        "msg"
                    );
                }
            }

            if ($tab == "email_invoice_default") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["subject"] = rhef()->get_default(
                        "email_template",
                        "invoice",
                        "default",
                        "subject"
                    );
                    $data["msg"] = rhef()->get_default(
                        "email_template",
                        "invoice",
                        "default",
                        "msg"
                    );
                }
            }

            if ($tab == "email_invoice_reminder") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["subject"] = rhef()->get_default(
                        "email_template",
                        "invoice",
                        "reminder",
                        "subject"
                    );
                    $data["msg"] = rhef()->get_default(
                        "email_template",
                        "invoice",
                        "reminder",
                        "msg"
                    );
                }
            }

            if ($tab == "email_invoice_recurring") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["subject"] = rhef()->get_default(
                        "email_template",
                        "invoice",
                        "recurring",
                        "subject"
                    );
                    $data["msg"] = rhef()->get_default(
                        "email_template",
                        "invoice",
                        "recurring",
                        "msg"
                    );
                }
            }

            if ($tab == "estvoice_tax") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["item_tax"] = false;
                    // $data['item_tax_val_type'] = 'fixed';
                }
            }

            if ($tab == "smtp_other") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["host"] = "";
                    $data["port"] = "";
                    $data["secure"] = "";
                    $data["auth"] = false;
                    $data["user"] = "";
                    $data["pass"] = "";
                }
            }

            if ($tab == "smtp_sendinblue") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["key"] = "";
                    $data["web"] = "";
                }
            }

            if ($tab == "google_api_oauth2") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["client_id"] = "";
                    $data["client_secret"] = "";
                    $data["redirect_uri"] = "";
                    $data["api_key"] = "";
                }
            }

            if ($tab == "automation_zapier") {
                $option = get_option("rhef_" . $tab);

                if ($option) {
                    $data = $option;
                } else {
                    $data["status"] = false;
                    $data["name"] = "";
                    $data["url"] = "";
                    $data["actions"] = [
                        /* [
                            "id" => "new_lead",
                            "method" => "post",
                            "custom" => false,
                            "name" => '',
                            "url" => ''
                        ], */
                    ];
                }
            }

            wp_send_json_success($data);
        }
    }

    public function create($req)
    {
        $param = $req->get_params();
        $reg_errors = new \WP_Error();

        $tab = isset($param["tab"]) ? sanitize_text_field($param["tab"]) : null;

        if (empty($tab)) {
            $reg_errors->add(
                "field",
                esc_html__("Tab is missing", "easyform")
            );
        }

        if ($reg_errors->get_error_messages()) {
            wp_send_json_error($reg_errors->get_error_messages());
        } else {
            $data = [];

            if ($tab == "general_module") {
                $data["deactivate"] = isset($param["deactivate"])
                    ? array_map("sanitize_text_field", $param["deactivate"])
                    : [];
                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "subscription") {
                $data = $param;
                $data["deactivate"] = isset($param["deactivate"])
                    ? array_map("sanitize_text_field", $param["deactivate"])
                    : [];
                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "email_footer") {
                $data["active"] = isset($param["active"])
                    ? rest_sanitize_boolean($param["active"])
                    : null;
                $data["text"] = isset($param["text"]) ? $param["text"] : null;

                $option = update_option("rhef_" . $tab, $data);

                $white_label["logo"] = isset($param["logo"])
                    ? $param["logo"]["id"]
                    : null;

                update_option("rhef_white_label", $white_label);
            }

            if ($tab == "estinv_currency") {
                $data["currency"] = isset($param["currency"])
                    ? sanitize_text_field($param["currency"])
                    : null;
                $data["lang"] = isset($param["lang"])
                    ? sanitize_text_field($param["lang"])
                    : null;
                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "estimate_general") {
                $data["prefix"] = isset($param["prefix"])
                    ? sanitize_text_field($param["prefix"])
                    : "";
                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "invoice_general") {
                $data["prefix"] = isset($param["prefix"])
                    ? sanitize_text_field($param["prefix"])
                    : "";
                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "estimate_reminder" || $tab == "invoice_reminder") {
                //TODO: sanitization
                $data["status"] = isset($param["status"])
                    ? rest_sanitize_boolean($param["status"])
                    : null;
                $data["due_date"] = isset($param["due_date"])
                    ? $param["due_date"]
                    : null;
                $data["before"] = isset($param["before"])
                    ? $param["before"]
                    : null;
                $data["after"] = isset($param["after"])
                    ? $param["after"]
                    : null;

                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "payment_wc") {
                $data["status"] = isset($param["status"])
                    ? rest_sanitize_boolean($param["status"])
                    : null;

                $reg_errors = new \WP_Error();
                if ($data["status"] && !class_exists("woocommerce")) {
                    $reg_errors->add(
                        "field",
                        esc_html__(
                            "Please install and activate WooCommerce plugin.",
                            "easyform"
                        )
                    );
                }

                if ($reg_errors->get_error_messages()) {
                    wp_send_json_error($reg_errors->get_error_messages());
                }
                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "email_estimate_default") {
                $data["subject"] = isset($param["subject"])
                    ? sanitize_text_field($param["subject"])
                    : null;
                $data["msg"] = isset($param["msg"])
                    ? sanitize_textarea_field($param["msg"])
                    : null;
                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "email_estimate_reminder") {
                $data["subject"] = isset($param["subject"])
                    ? sanitize_text_field($param["subject"])
                    : null;
                $data["msg"] = isset($param["msg"])
                    ? sanitize_textarea_field($param["msg"])
                    : null;
                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "email_invoice_default") {
                $data["subject"] = isset($param["subject"])
                    ? sanitize_text_field($param["subject"])
                    : null;
                $data["msg"] = isset($param["msg"])
                    ? sanitize_textarea_field($param["msg"])
                    : null;
                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "email_invoice_reminder") {
                $data["subject"] = isset($param["subject"])
                    ? sanitize_text_field($param["subject"])
                    : null;
                $data["msg"] = isset($param["msg"])
                    ? sanitize_textarea_field($param["msg"])
                    : null;
                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "email_invoice_recurring") {
                $data["subject"] = isset($param["subject"])
                    ? sanitize_text_field($param["subject"])
                    : null;
                $data["msg"] = isset($param["msg"])
                    ? sanitize_textarea_field($param["msg"])
                    : null;
                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "estvoice_tax") {
                $data["item_tax"] = isset($param["item_tax"])
                    ? rest_sanitize_boolean($param["item_tax"])
                    : null;
                // $data['item_tax_val_type'] = isset( $param['item_tax_val_type'] ) ? sanitize_textarea_field( $param['item_tax_val_type'] ) : null;
                $option = update_option("rhef_" . $tab, $data);
            }

            if ($tab == "smtp_default") {
                update_option("rhef_smtp", null);
            }

            if ($tab == "smtp_other") {
                $data["host"] = isset($param["host"])
                    ? sanitize_text_field($param["host"])
                    : null;
                $data["port"] = isset($param["port"])
                    ? absint($param["port"])
                    : null;
                $data["secure"] = isset($param["secure"])
                    ? sanitize_text_field($param["secure"])
                    : null;
                $data["auth"] = isset($param["auth"])
                    ? rest_sanitize_boolean($param["auth"])
                    : null;
                $data["user"] = isset($param["user"])
                    ? sanitize_text_field($param["user"])
                    : null;
                $data["pass"] = isset($param["pass"])
                    ? sanitize_text_field($param["pass"])
                    : null;
                update_option("rhef_" . $tab, $data);
                update_option("rhef_smtp", "other");
            }

            if ($tab == "smtp_sendinblue") {
                //Check valid key here
                $data["key"] = isset($param["key"])
                    ? sanitize_text_field($param["key"])
                    : null;
                $data["web"] = isset($param["web"])
                    ? esc_url_raw($param["web"])
                    : null;
                update_option("rhef_" . $tab, $data);
                update_option("rhef_smtp", "sendinblue");
            }

            if ($tab == "google_api_oauth2") {
                //Check valid key here
                $data["client_id"] = isset($param["client_id"])
                    ? sanitize_text_field($param["client_id"])
                    : null;
                $data["client_secret"] = isset($param["client_secret"])
                    ? sanitize_text_field($param["client_secret"])
                    : null;
                $data["redirect_uri"] = isset($param["redirect_uri"])
                    ? sanitize_text_field($param["redirect_uri"])
                    : null;
                $data["api_key"] = isset($param["api_key"])
                    ? sanitize_text_field($param["api_key"])
                    : null;
                update_option("rhef_" . $tab, $data);
            }

            if ($tab == "automation_zapier") {
                $data["status"] = isset($param["status"])
                    ? rest_sanitize_boolean($param["status"])
                    : null;
                $data["name"] = isset($param["name"])
                    ? sanitize_text_field($param["name"])
                    : null;
                $data["url"] = isset($param["url"])
                    ? esc_url_raw($param["url"])
                    : null;
                $data["actions"] = isset($param["actions"])
                    ? $param["actions"]
                    : null;
                update_option("rhef_" . $tab, $data);
            }

            wp_send_json_success();
        }
    }

    public function get_per()
    {
        return current_user_can("rhef_setting");
    }

    public function create_per()
    {
        return current_user_can("rhef_setting");
    }
}
