<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div id="singlepostcont" class="container_12">
            <div class="returntomain grid_12">
                <a href="#post-12">Back</a>
            </div>
            <article <?php post_class() ?> id="post-<?php the_ID(); ?>">

                <h1 class="entry-title"><?php the_title(); ?></h1>

                <div class="entry-content">

                    <?php the_content(); ?>

                    <?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>

                    <?php the_tags( 'Tags: ', ', ', ''); ?>

                    <?php include (TEMPLATEPATH . '/_/inc/meta.php' ); ?>

                </div>

                <?php edit_post_link('Edit this entry','','.'); ?>

            </article>

            <div class="returntomain grid_12">
                <a href="#post-12">Back</a>
            </div>
            <div class="clear"></div>
	<?php //comments_template(); ?>
        </div>
	<?php endwhile; endif; ?>

<?php get_footer(); ?>
