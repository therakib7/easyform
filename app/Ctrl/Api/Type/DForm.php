<?php
namespace Rhef\Ctrl\Api\Type;

use Rhef\Model\Form as ModelForm;

class Form
{
    public function __construct()
    {
        add_action("rest_api_init", [$this, "rest_routes"]);
    }

    public function rest_routes()
    {
        register_rest_route("rhef/v1", "/forms", [
            [
                "methods" => "GET",
                "callback" => [$this, "get"],
                "permission_callback" => [$this, "get_per"],
            ],
        ]);
    }

    public function get($req)
    {
        $param = $req->get_params();
        $form = isset($param["form"])
            ? sanitize_text_field($param["form"])
            : null;

        $data = [];
        $model = new ModelForm();

        if ($form == "contact_form_7") {
            $data = $model->contact_form_7();
        } elseif ($form == "wpforms") {
            $data = $model->wpforms();
        } elseif ($form == "ninja_forms") {
            $data = $model->ninja_forms();
        } elseif ($form == "gravity_forms") {
            $data = $model->gravity_forms();
        } elseif ($form == "fluent_forms") {
            $data = $model->fluent_forms();
        }

        wp_send_json_success($data);
    }

    // check permission
    public function get_per()
    {
        return current_user_can("rhef_form");
    }
}
