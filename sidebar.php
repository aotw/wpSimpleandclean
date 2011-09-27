		<aside>
			<ul>
			<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

				<li>
					<h3>Simple & clean</h3>
					<p>Simple & Clean is my first Wordpress theme and I'm happy to make it my first theme for release. Make sure to view the readme or e-mail me with any questions or concerns.</p><br />
				</li>
				<li>
					<h3>Search the Site</h3>
					<?php get_search_form(); ?>

				</li>

				<?php wp_list_categories('show_count=1&title_li=<h3>Categories</h3>'); ?>

				<li>
					<h3>Archives</h3>
					<ul>
						<?php wp_get_archives('type=monthly'); ?>
					</ul>
				</li>

				<?php if ( is_404() || is_category() || is_day() || is_month() || is_year() || is_search() || is_paged() ) { ?>

				<li>
					<?php if (is_404()) { ?>
					<p>Please contact us or try again with a new search using the form above.</p>

					<?php } elseif (is_category()) { ?>
					<p>You are currently browsing the archives for the <?php single_cat_title(''); ?> category.</p>

					<?php } elseif (is_day()) { ?>
					<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives for the day <?php the_time('l, F jS, Y'); ?>.</p>

					<?php } elseif (is_month()) { ?>
					<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives for <?php the_time('F, Y'); ?>.</p>

					<?php } elseif (is_year()) { ?>
					<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives for the year <?php the_time('Y'); ?>.</p>

					<?php } elseif (is_search()) { ?>
					<p>You have searched the <a href="<?php echo bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives for <strong>'<?php the_search_query(); ?>'</strong>.</p>

					<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
					<p>You are currently browsing the <a href="<?php echo bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives.</p>

					<?php } ?>
				</li>
				<?php }?>
				<?php wp_list_pages('title_li=<h3>Pages</h3>' ); ?>

				<?php if (is_home() || is_page() || is_single()) { ?>
				<?php wp_list_bookmarks(); ?>

				<li>
					<h3>Meta</h3>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<li><a href="http://validator.w3.org/check/referer" title="Built with valid HTML 5" rel="nofollow">Valid <abbr title="HyperText Markup Language">HTML</abbr> 5</a></li>
						<li><a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fdiggingintowordpress.com%2FThemePlayground%2Fwp-content%2Fthemes%2FH5%2Fstyle.css&profile=css21&usermedium=all&warning=1&lang=en" title="Styled with valid CSS" rel="nofollow">Valid CSS 2.1</a></li>
						<li><a href="http://wordpress.org/" title="Proudly Powered by WordPress" rel="nofollow">WordPress</a></li>
						<?php wp_meta(); ?>
					</ul>
				</li>
				<?php } ?>

				
			<?php endif; ?>
			</ul>
		</aside>