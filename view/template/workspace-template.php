<?php
/*
 * Template Name: Easyform Dashboard
 * Description: Template for Easyform Dashboard
 */

use Rhef\Ctrl\Cleanup\Style;

add_action('wp_enqueue_scripts', [Style::getInstance(), 'clear_styles_and_scripts'], 100);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .rhef .pv-page-content {
            max-width: 30%;
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @media screen and (max-width: 550px) {
            .rhef .pv-page-content {
                max-width: 100%;
            }
        }

        .rhef .pv-page-content p {
            font-size: 24px;
            font-weight: 600;
            line-height: 40px;
            color: #4A5568;
        }

        @media screen and (max-width: 550px) {
            .rhef .pv-page-content p {
                font-size: 16px;
                line-height: 30px;
            }
        }
    </style>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php
    if (is_user_logged_in() && apply_filters('rhef_admin', current_user_can('rhef_core'))) {
        if (rhef()->wage()) {
            echo '<div id="rhef-dashboard"></div>';
        } else {
            rhef()->render('template/partial/403');
        }
    } else {
        //TODO: this css already has in all.scoped.css
    ?>

    <?php
        rhef()->render('template/partial/403');
    }
    ?>
    <?php wp_footer(); ?>
</body>

</html>