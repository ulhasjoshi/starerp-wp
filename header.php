<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<label for="sidebarToggle" class="menu-toggle">☰</label>
<input type="checkbox" id="sidebarToggle" hidden>

<?php get_template_part('sidebar'); ?>
