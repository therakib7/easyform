<?php
namespace Rhef\Ctrl\Hook\Type;

class Filter
{
    public function __construct()
    {
        add_filter("body_class", [$this, "body_class"]);
        add_filter("admin_body_class", [$this, "admin_body_class"]);
        add_filter("ajax_query_attachments_args", [
            $this,
            "hide_bank_attachment",
        ]);
    }

    function body_class($classes)
    {
        if (
            is_page_template([
                "workspace-template.php",
                "invoice-template.php",
                "estimate-template.php",
            ])
        ) {
            $classes[] = "rhef";
            $classes[] = get_option("template") . "-theme";
        }
        return $classes;
    }

    function admin_body_class($classes)
    {
        if (
            (isset($_GET["page"]) && $_GET["page"] == "rhef") ||
            (isset($_GET["page"]) && $_GET["page"] == "rhef-welcome")
        ) {
            $classes .= " rhef " . get_option("template") . "-theme";
        }

        return $classes;
    }

    /**
     * Hide attachment files from the Media Library's overlay (modal) view
     * if they have a certain meta key and value set.
     *
     * @param array $args An array of query variables.
     */
    public function hide_bank_attachment($args)
    {
        // Bail if this is not the admin area.
        if (!is_admin()) {
            return;
        }

        // Modify the query.
        $args["meta_query"] = [
            "relation" => "OR",
            [
                "key" => "rhef_attach_type",
                "compare" => "NOT EXISTS",
            ],
            [
                "key" => "rhef_attach_type",
                "value" => "secret",
                "compare" => "!=",
            ],
        ];

        return $args;
    }
}
