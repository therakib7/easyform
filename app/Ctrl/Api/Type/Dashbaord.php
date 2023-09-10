<?php
namespace Rhef\Ctrl\Api\Type;

use Rhef\Helper\Fns;
use Rhef\Model\Client;
use Rhef\Model\Deal;
use Rhef\Model\Lead;
use Rhef\Model\Project;

class Dashbaord
{
    public $months = [
        1 => "Jan",
        2 => "Feb",
        3 => "Mar",
        4 => "Apr",
        5 => "May",
        6 => "Jun",
        7 => "Jul",
        8 => "Aug",
        9 => "Sep",
        10 => "Oct",
        11 => "Nov",
        12 => "Dec",
    ];

    public function __construct()
    {
        add_action("rest_api_init", [$this, "rest_routes"]);
    }

    public function rest_routes()
    {
        register_rest_route("rhef/v1", "/dashboard", [
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

        if ($param["section"] == "summary") {
            $this->summary($param);
        }

        if ($param["section"] == "deal_tracking") {
            $this->deal_tracking($param);
        }

        if ($param["section"] == "deal_funnel") {
            $this->deal_funnel($param);
        }

        if (
            $param["section"] == "lead_level" ||
            $param["section"] == "lead_source"
        ) {
            $this->lead_level_source($param);
        }

        if ($param["section"] == "estimate" || $param["section"] == "invoice") {
            $this->estvoice($param);
        }
    }

    public function summary($param)
    {
        $total_estimate = 0;
        $accepted_estimate = 0;
        $total_invoice = 0;
        $paid_invoice = 0;

        $summary = [];
        $client = new Client();
        $lead = new Lead();
        $deal = new Deal();
        $project = new Project();
        $summary["total_client"] = $client->total();
        $summary["total_lead"] = $lead->total();
        $summary["total_deal"] = $deal->total();
        $summary["total_project"] = $project->total();

        $args = [
            "post_type" => "rhef_estinv",
            "post_status" => "publish",
            "posts_per_page" => -1,
        ];
        $query = new \WP_Query($args);
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();

            $path = get_post_meta($id, "path", true);
            $status = get_post_meta($id, "status", true);

            if ($path == "estimate") {
                $total_estimate++;
                if ($status == "accept") {
                    $accepted_estimate++;
                }
            } elseif ($path == "invoice") {
                $total_invoice++;
                if ($status == "paid") {
                    $paid_invoice++;
                }
            }
        }
        wp_reset_postdata();

        $summary["total_estimate"] = $total_estimate;
        $summary["accepted_estimate"] = $accepted_estimate;
        $summary["total_invoice"] = $total_invoice;
        $summary["paid_invoice"] = $paid_invoice;

        wp_send_json_success($summary);
    }

    public function deal_funnel($param)
    {
        $per_page = 10;
        $offset = 0;

        if (isset($param["per_page"])) {
            $per_page = $param["per_page"];
        }

        if (isset($param["page"]) && $param["page"] > 1) {
            $offset = $per_page * $param["page"] - $per_page;
        }

        $get_stage = Fns::get_terms("deal_stage");

        $column = [];
        $total_stage = count($get_stage) - 2;

        $item_percent = 100;
        $minus_percent = 100 / $total_stage;

        foreach ($get_stage as $stage):
            $stage_id = $stage->term_id;
            $stage_name = $stage->name;

            $items = [];
            $args = [
                "post_type" => "rhef_deal",
                "post_status" => "publish",
                "orderby" => "menu_order",
                "order" => "ASC",
                "posts_per_page" => $per_page,
                "offset" => $offset,
            ];

            $args["meta_query"] = [
                "relation" => "OR",
            ];

            $args["tax_query"] = [
                [
                    "taxonomy" => "rhef_deal_stage",
                    "terms" => $stage_id,
                    "field" => "term_id",
                ],
            ];

            $query = new \WP_Query($args);
            $total_data = $query->found_posts; //use this for pagination

            $type = get_term_meta($stage_id, "type", true);

            $percent = "";

            if ($type != "won" || $type != "lost") {
                if ($item_percent < 35) {
                    $item_percent = 35;
                }
                $percent = $item_percent . "%";
                $item_percent -= $minus_percent;
            }

            $bg_color = get_term_meta($stage_id, "bg_color", true);
            $color = get_term_meta($stage_id, "color", true);
            $stage_single = [
                "name" => $stage_name,
                "color" => $color ? $color : "#fff",
                "bg_color" => $bg_color ? $bg_color : "#345bde",
                "type" => $type,
                "percent" => $percent,
                "items" => $total_data,
            ];

            if ($type == "won") {
                $column["won"] = $stage_single;
            } elseif ($type == "lost") {
                $column["lost"] = $stage_single;
            } else {
                $column["common"][] = $stage_single;
            }
        endforeach;

        wp_send_json_success($column);
    }

    public function deal_tracking($param)
    {
        $year = $param["year"];

        $data = [];

        for ($i = 0; $i < 12; $i++) {
            $data[] = [
                "name" => $this->months[$i + 1],
                "won" => 0,
                "lost" => 0,
            ];
        }

        $args = [
            "post_type" => "rhef_deal",
            "post_status" => "publish",
            "posts_per_page" => -1,
        ];

        $args["date_query"] = [["year" => $year]];

        $query = new \WP_Query($args);
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();

            $query_data["stage_id"] = "";
            $stage = get_the_terms($id, "rhef_deal_stage");
            if ($stage) {
                $month_num = ((int) get_the_time("m")) - 1;
                $type = get_term_meta($stage[0]->term_id, "type", true);
                if ($type == "won") {
                    $data[$month_num]["won"]++;
                }

                if ($type == "lost") {
                    $data[$month_num]["lost"]++;
                }
            }
        }
        wp_reset_postdata();

        wp_send_json_success($data);
    }

    public function lead_level_source($param)
    {
        $per_page = 10;
        $offset = 0;

        if (isset($param["per_page"])) {
            $per_page = $param["per_page"];
        }

        if (isset($param["page"]) && $param["page"] > 1) {
            $offset = $per_page * $param["page"] - $per_page;
        }

        $get_tax = Fns::get_terms($param["section"]);

        $column = [];
        $total_posts = 0;
        foreach ($get_tax as $tax):
            $tax_id = $tax->term_id;
            $tax_name = $tax->name;

            $args = [
                "post_type" => "rhef_lead",
                "post_status" => "publish",
                "orderby" => "menu_order",
                "order" => "ASC",
                "posts_per_page" => $per_page,
                "offset" => $offset,
            ];
            $args["meta_query"] = [
                "relation" => "OR",
            ];
            $args["tax_query"] = [
                [
                    "taxonomy" => "rhef_" . $param["section"],
                    "terms" => $tax_id,
                    "field" => "term_id",
                ],
            ];

            $query = new \WP_Query($args);
            $total_data = $query->found_posts; //use this for pagination
            $total_posts += $total_data; //use this for pagination

            $bg_color = get_term_meta($tax_id, "bg_color", true);
            $tax_single = [
                "name" => $tax_name,
                "bg_color" => $bg_color ? $bg_color : "#B9C7FF",
                "item" => $total_data,
                "percent" => 0,
            ];

            $column[] = $tax_single;
        endforeach;

        $column_with_percent = [];
        foreach ($column as $col) {
            $col["percent"] =
                $col["item"] && $total_posts
                    ? round(($col["item"] / $total_posts) * 100)
                    : 0;
            $column_with_percent[] = $col;
        }

        wp_send_json_success($column_with_percent);
    }

    public function estvoice($param)
    {
        $type = $param["section"];
        $year = $param["year"];

        $data = [];

        for ($i = 0; $i < 12; $i++) {
            if ($type == "estimate") {
                $data[$i] = [
                    "name" => $this->months[$i + 1],
                    "sent" => 0,
                    "viewed" => 0,
                    "accepted" => 0,
                    "declined" => 0,
                ];
            } elseif ($type == "invoice") {
                $data[$i] = [
                    "name" => $this->months[$i + 1],
                    "sent" => 0,
                    "viewed" => 0,
                    "paid" => 0,
                    "overdue" => 0,
                ];
            }
        }

        $args = [
            "post_type" => "rhef_estinv",
            "post_status" => "publish",
            "posts_per_page" => -1,
        ];

        $args["date_query"] = [["year" => $year]];

        $query = new \WP_Query($args);
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();

            $path = get_post_meta($id, "path", true);
            $status = get_post_meta($id, "status", true);

            $month_num = ((int) get_the_time("m")) - 1;
            if ($type == "estimate" && $path == "estimate") {
                if ($status == "accept") {
                    $data[$month_num]["accepted"]++;
                }

                if ($status == "decline") {
                    $data[$month_num]["declined"]++;
                }
            } elseif ($type == "invoice" && $path == "invoice") {
                if ($status == "paid") {
                    $data[$month_num]["paid"]++;
                }
            }
        }
        wp_reset_postdata();

        wp_send_json_success($data);
    }

    // check permission
    public function get_per()
    {
        return current_user_can("rhef_dashboard");
    }
}
