<?php
namespace Rhef\Ctrl\Api\Type;

use Rhef\Helper\Fns;

class Email
{
    public function __construct()
    {
        add_action("rest_api_init", [$this, "rest_routes"]);
    }

    public function rest_routes()
    {
        register_rest_route("rhef/v1", "/emails", [
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

        register_rest_route("rhef/v1", "/emails/(?P<id>\d+)", [
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

        register_rest_route("rhef/v1", "/emails/(?P<id>[0-9,]+)", [
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

        wp_send_json_success();
    }

    public function get_single($req)
    {
        $url_params = $req->get_url_params();
        $id = $url_params["id"];
        wp_send_json_success();
    }

    public function create($req)
    {
        $param = $req->get_params();
        $type = isset($param["type"]) ? $param["type"] : "";
        if ($type == "sent") {
            $this->sent($param);
        } elseif ($type == "feedback") {
            $this->feedback($param);
        } elseif ($type == "dashboard") {
            $this->dashboard($param);
        }
    }

    public function sent($param)
    {
        $org_id = isset($param["fromData"]) ? $param["fromData"]["id"] : "";
        $org_name = isset($param["fromData"]) ? $param["fromData"]["name"] : "";
        $org_img = "";
        $org_address = "";
        if ($org_id) {
            $queryMeta = get_post_meta($org_id);
            $logo_id = isset($queryMeta["logo"]) ? $queryMeta["logo"][0] : "";
            $address = isset($queryMeta["address"])
                ? $queryMeta["address"][0]
                : "";
            $email = isset($queryMeta["email"]) ? $queryMeta["email"][0] : "";
            $mobile = isset($queryMeta["mobile"])
                ? $queryMeta["mobile"][0]
                : "";

            if ($logo_id) {
                $logo_src = wp_get_attachment_image_src($logo_id, "thumbnail");
                if ($logo_src) {
                    $org_img = "<img src='" . $logo_src[0] . "' alt='' />";
                }
            }

            if ($address) {
                $org_address .= $address . "<br />";
            }
            $org_address .= $email;
            if ($mobile) {
                $org_address .= ",<br />" . $mobile;
            }
        }

        $mail_from = isset($param["fromData"])
            ? $param["fromData"]["email"]
            : "";
        $mail_to = isset($param["toData"]) ? $param["toData"]["email"] : "";
        $invoice_id = isset($param["invoice_id"]) ? $param["invoice_id"] : "";
        $path = isset($param["path"]) ? $param["path"] : "";
        $title = isset($param["title"]) ? $param["title"] : "";
        $mail_subject = isset($param["subject"]) ? $param["subject"] : "";
        $msg = isset($param["msg"]) ? nl2br($param["msg"]) : "";
        $mail_invoice_img = isset($param["invoice_img"])
            ? $param["invoice_img"]
            : "";

        $compnay_name = isset($param["fromData"])
            ? $param["fromData"]["name"]
            : "";

        $token = get_post_meta($invoice_id, "token", true);
        $url = sprintf(
            "%s?id=%s&token=%s",
            Fns::client_page_url($path),
            $invoice_id,
            $token
        );

        $subject = Fns::templateVariable($mail_subject, []);
        $template = rhef()->render("email/invoice", [], true);

        $body = Fns::templateVariable($template, [
            "msg" => $msg,
            "url" => $url,
            "path" => $path,
            "title" => $title,
            "org_name" => $org_name,
            "org_img" => $org_img,
            "org_address" => $org_address,
        ]);

        $headers = ["Content-Type: text/html; charset=UTF-8"];
        $headers[] = "From: " . $compnay_name . " <" . $mail_from . ">";
        //TODO: dynamic Cc later
        //$headers[] = 'Cc: Rakib <therakib7@gmail.com>';
        //$headers[] = 'Cc: therakib7@gmail.com'; // note you can just use a simple email address

        //attachment
        $attachments = [];

        $send_mail = wp_mail($mail_to, $subject, $body, $headers, $attachments);

        if ($send_mail) {
            $status = get_post_meta($invoice_id, "status", true);
            if ($status == "draft") {
                update_post_meta($invoice_id, "status", "sent");
            }
            wp_send_json_success($send_mail);
        } else {
            wp_send_json_error(["Something wrong: Email not sent"]);
        }
    }

    public function feedback($param)
    {
        $invoice_id = isset($param["invoice_id"]) ? $param["invoice_id"] : "";
        $feedback_type = isset($param["feedback_type"])
            ? $param["feedback_type"]
            : "";
        $note = isset($param["note"]) ? nl2br($param["note"]) : "";
        $attachment = isset($param["attachment"]) ? $param["attachment"] : "";

        if ($invoice_id) {
            update_post_meta($invoice_id, "status", $feedback_type);
            $feedback = [];
            $feedback["type"] = $feedback_type;
            $feedback["note"] = $note;
            $feedback["attachment"] = $attachment;
            $feedback["time"] = current_time("timestamp");
            update_post_meta($invoice_id, "feedback", $feedback);
        }

        wp_send_json_success();
    }

    public function dashboard($param)
    {
        $feedback_type = isset($param["feedback_type"])
            ? $param["feedback_type"]
            : "";
        $feedback_title = "";
        if ($feedback_type == "features") {
            $feedback_title = "Features Request: ";
        } else {
            $feedback_title = "Bug Information: ";
        }

        $current_user = wp_get_current_user();
        $name = isset($param["name"])
            ? $param["name"]
            : $current_user->display_name;
        $from = isset($param["from"])
            ? $param["from"]
            : $current_user->user_email;
        $subject = isset($param["subject"]) ? $param["subject"] : "";
        $details = isset($param["details"]) ? nl2br($param["details"]) : "";
        //TODO: change name email
        $easyform_mail = "support@easyform.com";

        $headers = ["Content-Type: text/html; charset=UTF-8"];
        $headers[] = "From: " . $name . " <" . $from . ">";

        //attachment
        $attachments = [];
        $subject = $feedback_title . $subject;
        $send_mail = wp_mail(
            $easyform_mail,
            $subject,
            $details,
            $headers,
            $attachments
        );

        if ($send_mail) {
            wp_send_json_success($send_mail);
        } else {
            wp_send_json_error(["Something wrong"]);
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
        return current_user_can("rhef_email");
    }

    public function create_per()
    {
        return true;
    }

    public function update_per()
    {
        return current_user_can("rhef_email");
    }

    public function del_per()
    {
        return current_user_can("rhef_email");
    }
}
