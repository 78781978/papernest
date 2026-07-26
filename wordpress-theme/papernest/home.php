<?php
/**
 * Blog listing (the "posts page" set via page_for_posts in functions.php).
 * Grid of post cards matching the site's existing card language
 * (.product-card / .use-card), with featured image, date, excerpt.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>

<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <span>Blog</span></div>
    <h1>Blog</h1>
    <p>Porady, nowości i kulisy produkcji prosto od producenta wyrobów z&nbsp;papieru.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ( have_posts() ) : ?>
      <div class="blog-grid reveal-stagger">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <article <?php post_class( 'blog-card' ); ?>>
              <a href="<?php the_permalink(); ?>" class="thumb">
                <?php if ( has_post_thumbnail() ) : ?>
                  <?php the_post_thumbnail( 'medium_large' ); ?>
                <?php else : ?>
                  <?php echo papernest_svg_file( 'cardboard.svg' ); // phpcs:ignore ?>
                <?php endif; ?>
              </a>
              <div class="blog-card-body">
                <span class="blog-card-date"><?php echo esc_html( get_the_date() ); ?></span>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                <a href="<?php the_permalink(); ?>" class="blog-card-more">Czytaj więcej <?php echo papernest_icon( 'arrow' ); ?></a>
              </div>
            </article>
            <?php
        endwhile;
        ?>
      </div>
      <div class="blog-pagination">
        <?php
        the_posts_pagination(
            array(
                'prev_text' => '← Poprzednia',
                'next_text' => 'Następna →',
            )
        );
        ?>
      </div>
    <?php else : ?>
      <div class="notice-box"><?php echo papernest_icon( 'info' ); ?><p>Pierwsze wpisy pojawią się tu wkrótce.</p></div>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
