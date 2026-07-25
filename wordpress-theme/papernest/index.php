<?php
/**
 * Fallback template (blog listing / anything with no more specific template).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<section class="page-hero" style="padding-block:56px 40px">
  <div class="container">
    <h1><?php is_search() ? printf( 'Wyniki wyszukiwania: %s', esc_html( get_search_query() ) ) : bloginfo( 'name' ); ?></h1>
  </div>
</section>
<section class="section">
  <div class="container">
    <?php if ( have_posts() ) : ?>
      <div class="use-grid cols-3">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <article <?php post_class( 'card card-pad' ); ?>>
              <h2><a href="<?php the_permalink(); ?>" style="color:var(--heading)"><?php the_title(); ?></a></h2>
              <p style="color:var(--ink-600)"><?php the_excerpt(); ?></p>
            </article>
            <?php
        endwhile;
        ?>
      </div>
      <?php the_posts_pagination(); ?>
    <?php else : ?>
      <p><?php esc_html_e( 'Brak treści do wyświetlenia.', 'papernest' ); ?></p>
    <?php endif; ?>
  </div>
</section>
<?php get_footer(); ?>
