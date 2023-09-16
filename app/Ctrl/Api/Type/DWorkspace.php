<?php
namespace Rhef\Ctrl\Api\Type;

class Workspace
{
    public function __construct()
    {
        add_action("rest_api_init", [$this, "rest_routes"]);
    }

    public function rest_routes()
    {
        register_rest_route("rhef/v1", "/workspaces", [
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

        register_rest_route("rhef/v1", "/workspaces/(?P<id>\d+)", [
            "methods" => "GET",
            "callback" => [$this, "get_single"],
            "permission_callback" => [$this, "get_per"],
            "args" => [
                "id" => [
                    "validate_callback" => function ($param, $request, $key) {
                        return is_numeric($param);
                    },
                ],
            ],
        ]);

        register_rest_route("rhef/v1", "/workspaces/(?P<id>\d+)", [
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

        register_rest_route("rhef/v1", "/workspaces/(?P<id>[0-9,]+)", [
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
        $request = $req->get_params();

        $per_page = 10;
        $offset = 0;

        if (isset($request["per_page"])) {
            $per_page = $request["per_page"];
        }

        if (isset($request["page"]) && $request["page"] > 1) {
            $offset = $per_page * $request["page"] - $per_page;
        }

        $args = [
            "post_type" => "rhef_workspace",
            "post_status" => "publish",
            "posts_per_page" => $per_page,
            "offset" => $offset,
        ];

        $args["meta_query"] = [
            "relation" => "OR",
        ];

        if (isset($request["default"])) {
            $args["meta_query"][] = [
                [
                    "key" => "default",
                    "value" => 1,
                    "compare" => "LIKE",
                ],
            ];
        }

        $query = new \WP_Query($args);
        $total_data = $query->found_posts; //use this for pagination
        $result = $data = [];
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();

            $query_data = [];
            $query_data["id"] = $id;

            $query_data["first_name"] = get_post_meta($id, "first_name", true);
            $query_data["last_name"] = get_post_meta($id, "last_name", true);
            $query_data["email"] = get_post_meta($id, "email", true);
            $query_data["org_name"] = get_post_meta($id, "org_name", true);
            $query_data["web"] = get_post_meta($id, "web", true);
            $query_data["mobile"] = get_post_meta($id, "mobile", true);
            $query_data["country"] = get_post_meta($id, "country", true);
            $query_data["region"] = get_post_meta($id, "region", true);
            $query_data["address"] = get_post_meta($id, "address", true);

            $img_id = get_post_meta($id, "img", true);
            $imgData = null;
            if ($img_id) {
                $img_src = wp_get_attachment_image_src($img_id, "thumbnail");
                if ($img_src) {
                    $imgData = [];
                    $imgData["id"] = $img_id;
                    $imgData["src"] = $img_src[0];
                }
            }
            $query_data["img"] = $imgData;

            $query_data["date"] = get_the_time(get_option("date_format"));
            $data[] = $query_data;
        }
        wp_reset_postdata();

        $result["result"] = $data;
        $result["total"] = $total_data;

        wp_send_json_success($result);
    }

    public function get_single($req)
    {
        $url_params = $req->get_url_params();
        $id = $url_params["id"];
        $query_data = [];
        $query_data["id"] = absint($id);
        $query_data["tab_id"] = $id;

        $query_data["level_id"] = "";

        $level = get_the_terms($id, "rhef_lead_level");
        if ($level) {
            $query_data["level_id"] = [
                "id" => $level[0]->term_id,
                "label" => $level[0]->name,
            ];
        }

        $query_data["tags"] = [];

        $tags = get_the_terms($id, "rhef_tag");
        if ($tags) {
            $tagList = [];
            foreach ($tags as $tag) {
                $tagList[] = [
                    "id" => $tag->term_id,
                    "label" => $tag->name,
                ];
            }
            $query_data["tags"] = $tagList;
        }

        $workspace_id = $id;
        $workspaceData = [];

        if ($workspace_id) {
            $workspaceData["id"] = absint($workspace_id);
            $workspaceMeta = get_post_meta($workspace_id);
            $workspaceData["first_name"] = isset($workspaceMeta["first_name"])
                ? $workspaceMeta["first_name"][0]
                : "";
            $workspaceData["last_name"] = isset($workspaceMeta["last_name"])
                ? $workspaceMeta["last_name"][0]
                : "";
            $workspaceData["org_name"] = isset($workspaceMeta["org_name"])
                ? $workspaceMeta["org_name"][0]
                : "";
            $workspaceData["email"] = isset($workspaceMeta["email"])
                ? $workspaceMeta["email"][0]
                : "";
            $workspaceData["mobile"] = isset($workspaceMeta["mobile"])
                ? $workspaceMeta["mobile"][0]
                : "";
            $workspaceData["web"] = isset($workspaceMeta["web"])
                ? $workspaceMeta["web"][0]
                : "";
            $workspaceData["country"] = isset($workspaceMeta["country"])
                ? $workspaceMeta["country"][0]
                : "";
            $workspaceData["region"] = isset($workspaceMeta["region"])
                ? $workspaceMeta["region"][0]
                : "";
            $workspaceData["address"] = isset($workspaceMeta["address"])
                ? $workspaceMeta["address"][0]
                : "";
        }
        $query_data["workspace"] = $workspaceData;

        $query_data["date"] = get_the_time(get_option("date_format"));

        wp_send_json_success($query_data);
    }

    public function create($req)
    {
        $param = $req->get_params();
        $reg_errors = new \WP_Error();

        $first_name = isset($param["first_name"])
            ? sanitize_text_field($req["first_name"])
            : null;
        $last_name = isset($param["last_name"])
            ? sanitize_text_field($req["last_name"])
            : null;
        $email = isset($param["email"])
            ? strtolower(sanitize_email($req["email"]))
            : null;
        $org_name = isset($param["org_name"])
            ? sanitize_text_field($req["org_name"])
            : null;
        $web = isset($param["web"]) ? esc_url_raw($req["web"]) : null;
        $mobile = isset($param["mobile"])
            ? sanitize_text_field($req["mobile"])
            : null;
        $country = isset($param["country"])
            ? sanitize_text_field($req["country"])
            : null;
        $region = isset($param["region"])
            ? sanitize_text_field($req["region"])
            : null;
        $address = isset($param["address"])
            ? sanitize_text_field($req["address"])
            : null;
        $img =
            isset($param["img"]) && isset($param["img"]["id"])
                ? absint($param["img"]["id"])
                : null;

        if (empty($first_name)) {
            $reg_errors->add(
                "field",
                esc_html__("Name field is missing", "easyform")
            );
        }

        if (!is_email($email)) {
            $reg_errors->add(
                "email_invalid",
                esc_html__("Email id is not valid!", "easyform")
            );
        }

        if ($reg_errors->get_error_messages()) {
            wp_send_json_error($reg_errors->get_error_messages());
        } else {
            $data = [
                "post_type" => "rhef_workspace",
                "post_title" => $first_name,
                "post_content" => "",
                "post_status" => "publish",
                "post_author" => get_current_user_id(),
            ];
            $post_id = wp_insert_post($data);

            if (!is_wp_error($post_id)) {
                if ($first_name) {
                    update_post_meta($post_id, "first_name", $first_name);
                }

                if ($last_name) {
                    update_post_meta($post_id, "last_name", $last_name);
                }

                if ($email) {
                    update_post_meta($post_id, "email", $email);
                }

                if ($org_name) {
                    update_post_meta($post_id, "org_name", $org_name);
                }

                if ($web) {
                    update_post_meta($post_id, "web", $web);
                }

                if ($mobile) {
                    update_post_meta($post_id, "mobile", $mobile);
                }

                if ($country) {
                    update_post_meta($post_id, "country", $country);
                }

                if ($region) {
                    update_post_meta($post_id, "region", $region);
                }

                if ($address) {
                    update_post_meta($post_id, "address", $address);
                }

                if ($img) {
                    update_post_meta($post_id, "img", $img);
                }

                wp_send_json_success($post_id);
            } else {
                wp_send_json_error();
            }
        }
    }

    public function update($req)
    {
        $param = $req->get_params();
        $reg_errors = new \WP_Error();

        $first_name = isset($param["first_name"])
            ? sanitize_text_field($req["first_name"])
            : null;
        $last_name = isset($param["last_name"])
            ? sanitize_text_field($req["last_name"])
            : null;
        $email = isset($param["email"])
            ? strtolower(sanitize_email($req["email"]))
            : null;
        $org_name = isset($param["org_name"])
            ? sanitize_text_field($req["org_name"])
            : null;
        $web = isset($param["web"]) ? esc_url_raw($req["web"]) : null;
        $mobile = isset($param["mobile"])
            ? sanitize_text_field($req["mobile"])
            : null;
        $country = isset($param["country"])
            ? sanitize_text_field($req["country"])
            : null;
        $region = isset($param["region"])
            ? sanitize_text_field($req["region"])
            : null;
        $address = isset($param["address"])
            ? sanitize_text_field($req["address"])
            : null;
        $img =
            isset($param["img"]) && isset($param["img"]["id"])
                ? absint($param["img"]["id"])
                : null;

        if (empty($first_name)) {
            $reg_errors->add(
                "field",
                esc_html__("Name field is missing", "easyform")
            );
        }

        if (!is_email($email)) {
            $reg_errors->add(
                "email_invalid",
                esc_html__("Email id is not valid!", "easyform")
            );
        }

        if ($reg_errors->get_error_messages()) {
            wp_send_json_error($reg_errors->get_error_messages());
        } else {
            $url_params = $req->get_url_params();
            $post_id = $url_params["id"];

            $data = [
                "ID" => $post_id,
                "post_title" => $first_name,
                "post_author" => get_current_user_id(),
            ];
            $post_id = wp_update_post($data);

            if (!is_wp_error($post_id)) {
                if ($first_name) {
                    update_post_meta($post_id, "first_name", $first_name);
                }

                if ($last_name) {
                    update_post_meta($post_id, "last_name", $last_name);
                }

                if ($email) {
                    update_post_meta($post_id, "email", $email);
                }

                if ($org_name) {
                    update_post_meta($post_id, "org_name", $org_name);
                }

                if ($web) {
                    update_post_meta($post_id, "web", $web);
                }

                if ($mobile) {
                    update_post_meta($post_id, "mobile", $mobile);
                }

                if ($country) {
                    update_post_meta($post_id, "country", $country);
                }

                if ($region) {
                    update_post_meta($post_id, "region", $region);
                }

                if ($address) {
                    update_post_meta($post_id, "address", $address);
                }

                if ($img) {
                    update_post_meta($post_id, "img", $img);
                }

                wp_send_json_success($post_id);
            } else {
                wp_send_json_error();
            }
        }
    }

    public function delete($req)
    {
        $url_params = $req->get_url_params();

        $ids = explode(",", $url_params["id"]);
        foreach ($ids as $id) {
            wp_delete_post($id);
        }
        wp_send_json_success($ids);
    }

    // check permission
    public function get_per()
    {
        return current_user_can("rhef_workspace");
    }

    public function create_per()
    {
        return current_user_can("rhef_workspace");
    }

    public function update_per()
    {
        return current_user_can("rhef_workspace");
    }

    public function del_per()
    {
        return current_user_can("rhef_workspace");
    }
}
