<?php get_header(); ?>

<div class="main-content">
    <div class="container-fluid py-4">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <h1><?php the_title(); ?></h1>
            <div><?php the_content(); ?></div>
        <?php endwhile; else : ?>
            <p>No content found.</p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
