<aside class="col-md-3 bg-light p-3 border-end" style="min-height: 100vh;">
    <div class="d-flex align-items-center mb-4">
        <?php
        $user = wp_get_current_user();
        echo get_avatar($user->ID, 40);
        ?>
        <span class="ms-2"><?php echo esc_html($user->display_name); ?></span>
    </div>

    <?php
    wp_nav_menu([
        'theme_location' => 'sidebar',
        'menu_class'     => 'nav nav-pills flex-column',
        'container'      => false,
        'fallback_cb'    => '__return_false',
        'depth'          => 2,
        'walker'         => new WP_Bootstrap_Navwalker(),
    ]);
    ?>
</aside>