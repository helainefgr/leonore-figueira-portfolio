<?php
get_header();

while ( have_posts() ) :
	the_post();
	$raw = get_the_content();
	if ( false !== strpos( $raw, 'lf-sec' ) ) {
		// Pages issues de Wix : mise en page complète déjà dans le contenu.
		the_content();
	} else {
		// Nouvelles pages ou articles : mise en page simple.
		echo '<div class="lf-plain">';
		the_title( '<h1>', '</h1>' );
		the_content();
		echo '</div>';
	}
endwhile;

get_footer();
