<?php get_header(); ?>
    <?php get_sidebar(); ?>
    <main class="col-md-9 p-4" id="post-container">
        <button class="btn btn-primary mb-3" 
                hx-get="<?php echo esc_url(home_url('/wp-json/wp/v2/posts')); ?>"
                hx-target="#post-container"
                hx-swap="innerHTML">
            Load Posts with HTMX
        </button>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article <?php post_class('mb-4'); ?>>
                <h2><?php the_title(); ?></h2>
                <div><?php the_content(); ?></div>
            </article>
        <?php endwhile; else : ?>
            <p><?php esc_html_e('No posts found.', 'bootstrap5-htmx'); ?></p>
        <?php endif; ?>
    </main>
<?php get_footer(); ?>