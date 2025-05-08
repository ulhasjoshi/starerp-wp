<?php
/* Template Name: CRUD Form Page */
get_header();
?>

<div class="container py-5">
    <div class="row">
        <main class="col-md-8 offset-md-2">
            <h2 class="mb-4">Add / Edit Record</h2>
            <?php
            if (is_user_logged_in()) {
                echo do_shortcode('[your_crud_form_shortcode]'); // Replace with your actual shortcode
            } else {
                echo '<div class="alert alert-warning">Please log in to use this form.</div>';
            }
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>