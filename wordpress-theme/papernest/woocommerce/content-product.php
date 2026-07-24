<?php
/**
 * Product card in shop/related grids — overrides WooCommerce's default loop
 * item template to match the static prototype's .product-card markup.
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product || ! $product->is_visible() ) {
	return;
}

$terms   = get_the_terms( get_the_ID(), 'product_cat' );
$badge   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : __( 'Nowość', 'papernest' );
?>
<article <?php wc_product_class( 'product-card', $product ); ?>>
	<a href="<?php the_permalink(); ?>" class="thumb">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'medium' );
		} else {
			echo wc_placeholder_img( 'medium' );
		}
		?>
		<span class="badge"><?php echo esc_html( $badge ); ?></span>
	</a>
	<div class="body">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<div class="meta">
			<span class="price"><?php echo $product->get_price_html(); // phpcs:ignore ?></span>
			<a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">Wybierz <?php echo papernest_icon( 'arrow' ); ?></a>
		</div>
	</div>
</article>
