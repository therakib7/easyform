<?php
namespace Rhef\Model;

class Project {

    public function total( $id = null )
    {

        $args = array(
            'post_type' => 'rhef_project',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );

        $args['meta_query'] = array(
            'relation' => 'OR'
        );

        if ( $id ) {
            $args['meta_query'][] = array(
                array(
                    'key'   => 'person_id',
                    'value' => $id
                )
            );

            $args['meta_query'][] = array(
                array(
                    'key'   => 'org_id',
                    'value' => $id
                )
            );
        }

        $query = new \WP_Query($args);
        $total_data = $query->found_posts;
        wp_reset_postdata();

        return $total_data;
    }
}