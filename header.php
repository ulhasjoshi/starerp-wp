<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://unpkg.com/htmx.org@1.9.12"></script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="bg-light border-bottom p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="h4 text-decoration-none text-dark" href="<?php echo esc_url(home_url('/')); ?>">
            <?php bloginfo('name'); ?>
        </a>
        <button class="btn btn-outline-primary d-md-none" id="sidebarToggle">☰</button>
    </div>
</header>
<div class="container-fluid">
    <div class="row">