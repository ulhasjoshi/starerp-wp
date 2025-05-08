<?php
// Block wp-admin access for non-admins
add_action('admin_init', function () {
    if (!current_user_can('administrator') && !wp_doing_ajax()) {
        wp_redirect(home_url('/dashboard'));
        exit;
    }
});

// Optional: Block direct access to wp-login.php (only allow admin)
add_action('login_init', function () {
    if (!current_user_can('administrator') && !defined('DOING_AJAX')) {
        wp_redirect(home_url('/login'));
        exit;
    }
});

// Hide admin bar for non-admins
add_action('after_setup_theme', function () {
    if (!current_user_can('administrator')) {
        show_admin_bar(false);
    }
});

// Redirect users after login based on role
add_filter('login_redirect', function ($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles) && !in_array('administrator', $user->roles)) {
        return home_url('/dashboard');
    }
    return $redirect_to;
}, 10, 3);