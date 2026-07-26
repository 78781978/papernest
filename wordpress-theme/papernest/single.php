<?php
/**
 * Single blog post: page-hero with title/date, featured image, styled
 * content (reuses .legal-shell prose typography), CTA banner at the end.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

while ( have_posts() ) :
	the_post();
	$blog_page_id  = (int) get_option( 'page_for_posts' );
	$blog_page_url = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/' );
	?>

	<section class="page-hero">
		<div class="container">
			<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <a href="<?php echo esc_url( $blog_page_url ); ?>">Blog</a> <span>/</span> <span><?php the_title(); ?></span></div>
			<?php
			papernest_breadcrumb_schema(
				array(
					array( 'name' => 'Strona główna', 'url' => home_url( '/' ) ),
					array( 'name' => 'Blog', 'url' => $blog_page_url ),
					array( 'name' => get_the_title(), 'url' => null ),
				)
			);
			?>
			<h1><?php the_title(); ?></h1>
			<p>Opublikowano <?php echo esc_html( get_the_date() ); ?><?php echo get_the_author() ? ' przez ' . esc_html( get_the_author() ) : ''; ?></p>
		</div>
	</section>

	<?php if ( has_post_thumbnail() ) : ?>
		<section class="section-tight">
			<div class="container">
				<div class="single-post-thumb reveal"><?php the_post_thumbnail( 'large' ); ?></div>
			</div>
		</section>
	<?php endif; ?>

	<section class="section">
		<div class="container">
			<div class="legal-shell reveal">
				<?php the_content(); ?>
			</div>
		</div>
	</section>

	<section class="section-tight">
		<div class="container">
			<?php
			echo papernest_reveal(
				'<div class="cta-banner">
					<div>
						<h2>Potrzebujesz papieru dla swojej firmy?</h2>
						<p>Sprawdź naszą ofertę wypełniaczy papierowych, papieru dla piskląt i tektury budowlanej.</p>
					</div>
					<div class="cta-actions">
						<a href="' . esc_url( papernest_shop_link() ) . '" class="btn" style="background:#fff;color:#4f7309;box-shadow:0 14px 30px rgba(0,0,0,.35)">Przejdź do sklepu</a>
						<a href="' . esc_url( papernest_page_link( 'kontakt' ) ) . '" class="btn btn-outline" style="border-color:rgba(11,35,69,.3)">Skontaktuj się ' . papernest_icon( 'arrow' ) . '</a>
					</div>
				</div>'
			);
			?>
		</div>
	</section>

	<?php
endwhile;

get_footer();
