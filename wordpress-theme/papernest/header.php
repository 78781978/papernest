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
    <a href="<?php echo esc_url( papernest_shop_link() ); ?>" class="header-shop-link">Sklep</a>
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
      <?php if ( class_exists( 'WooCommerce' ) ) : ?>
        <a class="icon-btn" href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" aria-label="Moje konto"><?php echo papernest_icon( 'user' ); ?></a>
        <a class="icon-btn" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="Koszyk"><?php echo papernest_icon( 'cart' ); ?><span class="cart-count"><?php echo absint( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span></a>
      <?php else : ?>
        <a class="icon-btn" href="<?php echo esc_url( papernest_page_link( 'moje-konto' ) ); ?>" aria-label="Moje konto"><?php echo papernest_icon( 'user' ); ?></a>
        <a class="icon-btn" href="<?php echo esc_url( papernest_page_link( 'koszyk' ) ); ?>" aria-label="Koszyk"><?php echo papernest_icon( 'cart' ); ?><span class="cart-count">0</span></a>
      <?php endif; ?>
      <button class="nav-toggle" id="nav-toggle" aria-label="Otwórz menu" aria-expanded="false" aria-controls="main-nav"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>
<div class="nav-scrim" id="nav-scrim"></div>
<main id="main">
