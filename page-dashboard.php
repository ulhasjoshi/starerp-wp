<?php
/* Template Name: Dashboard Page */
get_header();
?>

<div class="container py-5">
    <div class="row">
        <?php get_sidebar(); ?>
        <main class="col-md-9">
            <h2 class="mb-4">Dashboard</h2>

            <?php if (is_user_logged_in()) : ?>

                <div class="row">
                    <div class="col-md-6">
                        <?php dynamic_sidebar('dashboard-top-left'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php dynamic_sidebar('dashboard-top-right'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?php dynamic_sidebar('dashboard-mid-left'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php dynamic_sidebar('dashboard-mid-right'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?php dynamic_sidebar('dashboard-bottom'); ?>
                    </div>
                </div>

            <?php else : ?>
                <div class="alert alert-warning">
                    Please <a href="<?php echo esc_url(wp_login_url()); ?>">log in</a> to view your dashboard.
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>