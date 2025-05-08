<?php
/* Template Name: List View Page */
get_header();
?>

<div class="container py-5">
    <div class="row">
        <main class="col-md-12">
            <h2 class="mb-4"><?php the_title(); ?></h2>
            <?php
            while (have_posts()) : the_post();
                the_content(); // This renders the page content including any shortcodes
            endwhile;
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>