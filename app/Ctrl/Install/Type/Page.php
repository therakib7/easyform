<?php
namespace Rhef\Ctrl\Install\Type;

class Page {

    public function __construct() {
        $this->create_custom_page();
    }

    function create_custom_page() {
        //Workspace pro
        //Estimate
        //Invoice
        //Proposal

        if ( ! get_page_by_path( 'estimate' ) ) {
            $args = array(
                'post_title'    => 'Easyform Estimate',
                'post_name'     => 'estimate',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page',
            );
            $id = wp_insert_post( $args );
            add_post_meta($id, '_wp_page_template', 'estimate-template.php');
        }
    }
}
