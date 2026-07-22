<?php
/**
 * Generic page template: page-hero + editable content. Automatically builds
 * a sticky table of contents when the page text contains <h6> paragraph
 * headings (used by the long legal pages: Regulamin etc.), matching the
 * static prototype's legal_page() layout.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

while ( have_posts() ) :
	the_post();

	$raw_content = apply_filters( 'the_content', get_the_content() );
	list( $content, $toc ) = papernest_legal_add_anchors( $raw_content );
	$has_toc = ! empty( $toc );
	?>

	<section class="page-hero" style="padding-block:56px 40px">
		<div class="container">
			<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <span><?php the_title(); ?></span></div>
			<h1><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php elseif ( $has_toc ) : ?>
				<p>Ostatnia aktualizacja: <?php echo esc_html( get_the_modified_date() ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<?php if ( $has_toc ) : ?>
				<div class="two-col cols-toc" style="align-items:start">
					<aside class="legal-toc reveal" style="position:sticky;top:110px">
						<h4>Spis paragrafów</h4>
						<?php foreach ( $toc as $item ) : ?>
							<a href="#<?php echo esc_attr( $item[0] ); ?>"><?php echo esc_html( $item[1] ); ?></a>
						<?php endforeach; ?>
					</aside>
					<div class="legal-shell reveal" style="max-width:none">
						<?php echo $content; // phpcs:ignore ?>
					</div>
				</div>
			<?php else : ?>
				<div class="legal-shell reveal" style="max-width:none">
					<?php echo $content; // phpcs:ignore ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php
endwhile;

get_footer();
