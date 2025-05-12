<div class="sidebar">
    <?php
    wp_nav_menu([
        'theme_location' => 'sidebar-menu',
        'container'      => false,
        'menu_class'     => 'nav flex-column',
    ]);

    if (is_active_sidebar('sidebar-widgets')) {
        dynamic_sidebar('sidebar-widgets');
    }
    ?>
</div>
