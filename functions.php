<?php
// Theme setup
function bootstrap5_htmx_theme_setup() {
    register_nav_menus([
        'header' => __('Header Menu', 'bootstrap5-htmx'),
        'sidebar' => __('Sidebar Menu', 'bootstrap5-htmx'),
    ]);

    add_theme_support('widgets');
}
add_action('after_setup_theme', 'bootstrap5_htmx_theme_setup');

// Enqueue scripts and styles
function bootstrap5_htmx_enqueue_scripts() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    // Theme style
    wp_enqueue_style('theme-style', get_stylesheet_uri());

    // HTMX
    wp_enqueue_script('htmx', 'https://unpkg.com/htmx.org@1.9.12', [], null, true);

    // Bootstrap JS (with Popper included)
    wp_enqueue_script('bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', [], null, true);
}
add_action('wp_enqueue_scripts', 'bootstrap5_htmx_enqueue_scripts');

// Include custom navwalker
require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';

// Register widget areas
function bootstrap5_htmx_widgets_init() {
    $areas = [
        'dashboard-top-left',
        'dashboard-top-right',
        'dashboard-mid-left',
        'dashboard-mid-right',
        'dashboard-bottom'
    ];

    foreach ($areas as $area) {
        register_sidebar([
            'name' => ucwords(str_replace('-', ' ', $area)),
            'id' => $area,
            'before_widget' => '<div class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="widget-title">',
            'after_title' => '</h5>',
        ]);
    }
}
add_action('widgets_init', 'bootstrap5_htmx_widgets_init');