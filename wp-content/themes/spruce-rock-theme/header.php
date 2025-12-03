<!DOCTYPE html>
<html lang="<?php echo get_locale(); ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Young+Serif&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://use.typekit.net/wqz3nuq.css">
    <!-- Disable automatic link creation in Safari -->
    <meta name="format-detection" content="telephone=no">
    <title>
        <?php wp_title('|', true, 'right') ?>
        <?php bloginfo('name'); ?>
    </title>

    <!-- WP HEAD -->
    <?php wp_head(); ?>
    <!-- /WP HEAD -->

    <?php tracking_codes('tracking_before_head') ?>

</head>

<body>
<?php tracking_codes('tracking_after_body') ?>
<?php
get_template_part('parts/barmenu');
//get_template_part('parts/mob-menu');