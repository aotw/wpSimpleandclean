		<div id="footer">

			<p class="fltLeft">&copy; <?= date('Y'); ?> <a href="<?php bloginfo('url'); ?>/" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a> &bull; <a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform">WordPress <?php bloginfo('version'); ?></a> &bull; <a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to Posts Feed">(RSS)</a></p>
		
<p class="fltRight nPage"><?php posts_nav_link('&nbsp;&bull;&nbsp;'); ?>

</div>

		
		<?php wp_footer(); ?>

	</body>
</html>