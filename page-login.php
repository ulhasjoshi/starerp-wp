<?php
/* Template Name: Custom Login Page */
get_header();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <main class="col-md-6">
            <h2 class="mb-4">Login</h2>

            <?php if (is_user_logged_in()) : ?>
                <div class="alert alert-success">
                    You are already logged in. <a href="<?php echo esc_url(home_url('/dashboard')); ?>">Go to dashboard</a>
                </div>
            <?php else : ?>
                <form method="post" action="<?php echo esc_url(wp_login_url()); ?>">
                    <div class="mb-3">
                        <label for="user_login" class="form-label">Username or Email</label>
                        <input type="text" name="log" id="user_login" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_pass" class="form-label">Password</label>
                        <input type="password" name="pwd" id="user_pass" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="rememberme" value="forever" class="form-check-input" id="rememberme">
                        <label class="form-check-label" for="rememberme">Remember me</label>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Log In">
                </form>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>