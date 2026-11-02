<?php
/**
 * Léonore Portfolio — fonctions du thème.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'style', 'script' ) );
	register_nav_menus( array( 'principal' => 'Menu principal' ) );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'leonore-portfolio', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
} );

/** Remplace les raccourcis {{assets}} et {{home}} présents dans le contenu des pages. */
function lf_expand_placeholders( $content ) {
	return str_replace(
		array( '{{assets}}', '{{home}}' ),
		array( get_template_directory_uri() . '/assets', untrailingslashit( home_url() ) ),
		$content
	);
}
add_filter( 'the_content', 'lf_expand_placeholders', 1 );

/** Couleur de fond propre à chaque page (comme sur Wix). */
function lf_page_background() {
	$bg = is_singular() ? get_post_meta( get_queried_object_id(), '_lf_bg', true ) : '';
	return $bg ? $bg : 'rgb(232, 232, 232)';
}

/** Menu de secours si aucun menu n'est attribué. */
function lf_fallback_menu() {
	$items = array(
		array( 'Projets', home_url( '/' ), is_front_page() ),
		array( 'Parcours', home_url( '/parcours/' ), is_page( 'parcours' ) ),
		array( 'Contact', home_url( '/#contact' ), false ),
	);
	echo '<ul>';
	foreach ( $items as $it ) {
		printf( '<li class="%s"><a href="%s">%s</a></li>', $it[2] ? 'current-menu-item' : '', esc_url( $it[1] ), esc_html( $it[0] ) );
	}
	echo '</ul>';
}

/**
 * À l'activation : crée les pages (avec leur contenu), la page d'accueil,
 * les permaliens et le menu. Ne remplace jamais une page qui existe déjà.
 */
function lf_install() {
	$dir   = get_template_directory() . '/content/';
	$pages = json_decode( file_get_contents( $dir . 'pages.json' ), true );
	if ( ! $pages ) {
		return;
	}

	// Le contenu contient du HTML mis en forme : on désactive le filtrage le temps de l'import.
	kses_remove_filters();

	$ids = array();
	foreach ( $pages as $slug => $info ) {
		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $existing ) {
			$ids[ $slug ] = $existing->ID;
			continue;
		}
		$html = file_get_contents( $dir . $slug . '.html' );
		$id   = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $info['title'],
				'post_name'    => $slug,
				'post_content' => "<!-- wp:html -->\n" . trim( $html ) . "\n<!-- /wp:html -->",
				'menu_order'   => count( $ids ),
			),
			true
		);
		if ( ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_lf_bg', $info['bg'] );
			$ids[ $slug ] = $id;
		}
	}

	kses_init_filters();

	// Page d'accueil = Projets.
	if ( ! empty( $ids['projets'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['projets'] );
	}

	// Adresses lisibles (/parcours/, /l1s2/…).
	if ( '' === get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}
	flush_rewrite_rules();

	// Menu principal : PROJETS · PARCOURS · CONTACT.
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locations['principal'] ) ) {
		$menu = wp_get_nav_menu_object( 'Menu principal' );
		$menu_id = $menu ? $menu->term_id : wp_create_nav_menu( 'Menu principal' );
		if ( ! is_wp_error( $menu_id ) ) {
			if ( ! $menu ) {
				$order = 1;
				foreach ( array( 'projets' => 'PROJETS', 'parcours' => 'PARCOURS' ) as $slug => $label ) {
					if ( ! empty( $ids[ $slug ] ) ) {
						wp_update_nav_menu_item( $menu_id, 0, array(
							'menu-item-title'     => $label,
							'menu-item-object'    => 'page',
							'menu-item-object-id' => $ids[ $slug ],
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
							'menu-item-position'  => $order++,
						) );
					}
				}
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'    => 'CONTACT',
					'menu-item-url'      => home_url( '/#contact' ),
					'menu-item-type'     => 'custom',
					'menu-item-classes'  => 'lf-contact',
					'menu-item-status'   => 'publish',
					'menu-item-position' => $order,
				) );
			}
			$locations['principal'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}
}
add_action( 'after_switch_theme', 'lf_install' );
