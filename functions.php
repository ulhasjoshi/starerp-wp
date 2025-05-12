<?php
// Enqueue Styles and Bootstrap JS
function erp_theme_enqueue_assets() {
    wp_enqueue_style('erp-theme-style', get_stylesheet_uri(), [], filemtime(get_stylesheet_directory() . '/style.css'));
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', [], null, true);
}
add_action('wp_enqueue_scripts', 'erp_theme_enqueue_assets');

// Register Sidebar and Menus
function erp_theme_setup() {
    register_nav_menus([
        'sidebar-menu' => __('Sidebar Menu'),
        'top-menu'     => __('Top Menu'),
    ]);

    register_sidebar([
        'name'          => 'Sidebar Widgets',
        'id'            => 'sidebar-widgets',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5>',
        'after_title'   => '</h5>',
    ]);
}
add_action('after_setup_theme', 'erp_theme_setup');
?>
