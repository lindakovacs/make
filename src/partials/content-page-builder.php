<?php
/**
 * @package ttf-one
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php get_template_part( 'partials/entry', 'title' ); ?>
	</header>

	<div class="entry-content">
		<?php remove_filter( 'the_content', 'wpautop' ); ?>
		<?php get_template_part( 'partials/entry', 'content' ); ?>
		<?php add_filter( 'the_content', 'wpautop' ); ?>
	</div>

	<footer class="entry-footer">
		<?php get_template_part( 'partials/entry', 'author' ); ?>
	</footer>
</article>
