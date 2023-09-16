<?php

namespace Rhef\Ctrl\Api\Type;

use Rhef\Model\FormBuilder as ModelFormBuilder;

class FormBuilder
{
    public function __construct()
    {
        add_action("rest_api_init", [$this, "rest_routes"]);
    }

    public function rest_routes()
    {
        register_rest_route("rhef/v1", "/form-builders", [
            [
                "methods" => "GET",
                "callback" => [$this, "get"],
                "permission_callback" => [$this, "get_per"],
            ],
            [
                "methods" => "POST",
                "callback" => [$this, "create"],
                "permission_callback" => [$this, "create_per"],
            ],
        ]);

        register_rest_route("rhef/v1", "/form-builders/(?P<id>\d+)", [
            "methods" => "GET",
            "callback" => [$this, "single"],
            "permission_callback" => [$this, "single_per"],
            "args" => [
                "id" => [
                    "validate_callback" => function ($param, $request, $key) {
                        return is_numeric($param);
                    },
                ],
            ],
        ]);

        register_rest_route("rhef/v1", "/form-builders/(?P<id>\d+)", [
            "methods" => "PUT",
            "callback" => [$this, "update"],
            "permission_callback" => [$this, "update_per"],
            "args" => [
                "id" => [
                    "validate_callback" => function ($param, $request, $key) {
                        return is_numeric($param);
                    },
                ],
            ],
        ]);

        register_rest_route("rhef/v1", "/form-builders/(?P<id>[0-9,]+)", [
            "methods" => "DELETE",
            "callback" => [$this, "delete"],
            "permission_callback" => [$this, "del_per"],
            "args" => [
                "id" => [
                    "sanitize_callback" => "sanitize_text_field",
                ],
            ],
        ]);
    }

    public function get($req)
    {
        $param = $req->get_params();

        $per_page = 10;
        $offset = 0;

        $s = isset($param["text"]) ? sanitize_text_field($param["text"]) : '';

        if (isset($param["per_page"])) {
            $per_page = $param["per_page"];
        }

        if (isset($param["page"]) && $param["page"] > 1) {
            $offset = $per_page * $param["page"] - $per_page;
        }

        $total = 0; 
        $resp = $data = [];

        $resp["data"] = $data;
        $resp["total"] = $total;

        wp_send_json_success($resp);
    }

    public function single($req)
    {
        $param = $req->get_params();

        $url_param = $req->get_url_params();
        $admin = isset($param["admin"]) ? true : false;

        $id = absint($url_param["id"]);

        $form_builder = new ModelFormBuilder();

        $resp = [];  
        $resp['fields'] = [];  
        $resp['canvas'] = [];  
        if ( $admin ) {
            $resp['fields'] = $form_builder->fields(); 
        }
        $resp['canvas'] = [
            [
                [
                    [
                        'label' => 'Text Field',
                        'type' => 'text',
                    ],
                    [
                        'label' => 'Email Field',
                        'type' => 'email',
                    ],
                ],
                [
                    [
                        'label' => 'Number Field',
                        'type' => 'number',
                    ],
                ],
            ],
            [
                [
                    [
                        'label' => 'Title',
                        'type' => 'title',
                    ],
                ],
            ],
        ];  

        wp_send_json_success($resp);
    }

    public function create($req)
    {
        $param = $req->get_params();
        $reg_errors = new \WP_Error();
        
        $from = isset($param["from"]) ? $param["from"] : '';

        if (!$from) {
            $reg_errors->add(
                "field",
                esc_html__("Business is missing", "easyform")
            );
        }
      

        if ($reg_errors->get_error_messages()) {
            wp_send_json_error($reg_errors->get_error_messages());
        } else {
                
            wp_send_json_success();
        }
    }

    public function update($req)
    {
        $param = $req->get_params();
        $reg_errors = new \WP_Error();
        
        $from = isset($param["from"]) ? $param["from"] : '';

        if (!$from) {
            $reg_errors->add(
                "field",
                esc_html__("Business is missing", "easyform")
            );
        }

        if ($reg_errors->get_error_messages()) {
            wp_send_json_error($reg_errors->get_error_messages());
        } else {
            $url_params = $req->get_url_params();
            $post_id = $url_params["id"];

            wp_send_json_success($post_id);
        }
    }

    public function delete($req)
    {
        $url_params = $req->get_url_params();

        $ids = explode(",", $url_params["id"]);
        foreach ($ids as $id) {
            wp_delete_post($id);
        }

        do_action("rhefp/webhook", "inv_del", $ids);

        wp_send_json_success($ids);
    }

    // check permission
    public function get_per()
    {
        return current_user_can("rhef_invoice");
    }

    public function single_per()
    {
        return current_user_can("rhef_invoice");
    }

    public function create_per()
    {
        return current_user_can("rhef_invoice");
    }

    public function update_per()
    {
        return current_user_can("rhef_invoice");
    }

    public function del_per()
    {
        return current_user_can("rhef_invoice");
    }
}
