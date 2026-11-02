<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> style="background-color: <?php echo esc_attr( lf_page_background() ); ?>">
<?php wp_body_open(); ?>
<div class="lf-page">
<header class="lf-header">
	<div class="lf-hstage">
		<a class="lf-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Accueil">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo.webp' ); ?>" alt="" width="63" height="63">
		</a>
		<a class="lf-title" href="<?php echo esc_url( home_url( '/' ) ); ?>">Portfolio Architecture</a>
		<nav class="lf-nav" aria-label="Menu principal">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'principal',
				'container'      => false,
				'depth'          => 1,
				'fallback_cb'    => 'lf_fallback_menu',
			) );
			?>
		</nav>
	</div>
</header>
<main>
