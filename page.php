<?php get_header(); ?>

	<?php query_posts(array('showposts' => 10, 'post_parent' => 0, 'post_type' => 'page', 'orderby' => 'menu_order', 'order' => 'ASC'));

	while (have_posts()) { the_post(); ?>

	<?php if ($post->ID == get_option('page_for_posts')) { // if page is news ?>
		<section class="section blog" id="post-<?php the_ID(); ?>">
			<article>
				<header class="container_12">
					<h2><?php the_title(); ?></h2>
				</header>

				<?php
				$num = (int) get_category('4')->count;
				$num_per_page = 6;
				$num_pages = ceil($num/6);
				$args = array( 'category' => 4, 'numberposts' => -1 );
				$lastposts = get_posts( $args );
				$i = 0;
				$p = 1; ?>

				<div class="scroll container_12">
					<div class="scrollcont" style="width: <?php echo $num_pages*960; ?>px">
				<?php
				foreach($lastposts as $post) : setup_postdata($post);
					$i++;
					if($i == 1) echo '<div class="postcont" id="page'.$p.'">';
					if(($i%($num_per_page+1)) == 0) {
						$p++;
						echo '<div class="postcont" id="page'.$p.'">';
						echo '<div id="col1">';
					}

					if(($i%($num_per_page+5)) == 0) echo '<div id="col2">';

					if($i == 1) echo '<div id="col1">';
					if($i == 4) echo '<div id="col2">';
				?>

					<a href="<?php the_permalink(); ?>" id="<?php the_ID(); ?>" class="blogpost">
					<div class="newsBox">
						<div class="cover">
							<?php
							if ( has_post_thumbnail() )
								the_post_thumbnail();
							else
								the_content(); ?>
						</div>
						<h3><?php the_title(); ?></h3>
					</div>
					</a>

				<?php
					if($i == 3) echo '</div>';
					if($i == 6) echo '</div>';
					if(($i%$num_per_page) == 0) echo '<div class="clear"></div></div>';
				?>
				<?php endforeach; ?>
					</div>
				</div>
			</article>
			<div id="navigation" class="container_12">
				<ul>
				<li id="pagelabel">Pages:</li>
				<?php
					for ($d = 1; $d <= $num_pages; $d++) { ?>
						<li id="pagelink<?php echo $d; ?>"><a href="#page<?php echo $d;?>"><?php echo $d; ?></a></li>
				<?php  }  ?>
				</ul>
			</div>
		</section>

	<?php } else { ?>
	<section class="section" id="post-<?php the_ID(); ?>">
		<article class="container_12">
			<header>
				<h2><?php the_title(); ?></h2>
			</header>

			<?php if (get_option('constellation_show_subpages')=='yes') {
				$subpages = wp_list_pages('title_li=&child_of='.$post->ID.'&echo=0&sort_column=menu_order&depth=1');
				if ($subpages) echo '<nav id="subpages"><ul>' . str_replace('</a>',' &rarr;</a>',$subpages) . '</ul></nav>';
			} ?>

			<?php the_content(''); ?>

		</article>
	</section>
	<?php } ?>

	<?php }
		wp_reset_query();  // Restore global post data
	?>

<?php get_footer(); ?>
