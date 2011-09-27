<?php get_header(); ?>
<div id="archive" class="archiveContent">
		<section>

		<?php if (have_posts()) : ?>

			<?php $post = $posts[0]; // hack: set $post so that the_date() works ?>
			<?php if (is_category()) { ?>
			<h1 class="archiveTitle">Archives for &ldquo;<?php single_cat_title(); ?>&rdquo; Category</h1>

			<?php } elseif(is_tag()) { ?>
			<h1 class="archiveTitle">Posts Tagged &ldquo;<?php single_tag_title(); ?>&rdquo;</h1>

			<?php } elseif (is_day()) { ?>
			<h1 class="archiveTitle">Archive for <?php the_time('F jS, Y'); ?></h1>

			<?php } elseif (is_month()) { ?>
			<h1 class="archiveTitle">Archive for <?php the_time('F, Y'); ?></h1>

			<?php } elseif (is_year()) { ?>
			<h1 class="archiveTitle">Archive for <?php the_time('Y'); ?></h1>

			<?php } elseif (is_author()) { ?>
			<h1 class="archiveTitle">Author Archive</h1>

			<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<h1 class="archiveTitle">Blog Archives</h1>

		<?php } ?>
		<?php while (have_posts()) : the_post(); ?>
			<ul class="archiveList">
			<article id="post-<?php the_ID(); ?>">
				
				<header>
				<li><p class="published"><span><?php the_time('F jS, Y'); ?></span> </p>

				
					
					<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					</li>
				</ul>	
				</header>
								
			</article>

			<?php endwhile; ?>

			

			<?php else : ?>

			<article>
				<h1>Not Found</h1>
				<p>Sorry, but the requested resource was not found on this site.</p>
				<?php get_search_form(); ?>
			</article>

			<?php endif; ?>

		</section>


</div><!--archive-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
