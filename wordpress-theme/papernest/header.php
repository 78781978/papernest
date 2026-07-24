<?php
/**
 * Site header — nav, logo, header icons. Ported from the static prototype's
 * header() in tools/build.py.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main">Przejdź do treści</a>
<header class="site-header">
  <div class="container header-row">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand" aria-label="PaperNest — strona główna">
      <?php echo papernest_logo(); ?>
    </a>
    <nav class="main-nav" id="main-nav">
      <?php
      wp_nav_menu(
          array(
              'theme_location' => 'primary',
              'container'      => false,
              'items_wrap'     => '<ul>%3$s</ul>',
              'fallback_cb'    => 'papernest_default_nav',
          )
      );
      ?>
    </nav>
    <div class="header-actions">
      <button class="icon-btn" type="button" aria-label="Szukaj"><?php echo papernest_icon( 'search' ); ?></button>
      <a class="icon-btn" href="<?php echo esc_url( papernest_page_link( 'moje-konto' ) ); ?>" aria-label="Status zamówienia"><?php echo papernest_icon( 'user' ); ?></a>
      <a class="icon-btn" href="<?php echo esc_url( papernest_cart_link() ); ?>" aria-label="Koszyk"><?php echo papernest_icon( 'cart' ); ?><span class="cart-count"><?php echo absint( papernest_cart_count() ); ?></span></a>
      <button class="nav-toggle" id="nav-toggle" aria-label="Otwórz menu" aria-expanded="false" aria-controls="main-nav"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>
<div class="nav-scrim" id="nav-scrim"></div>
<main id="main">
