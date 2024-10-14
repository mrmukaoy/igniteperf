<?php
function yobaselayer_child_enqueue_styles() {
	$theme = wp_get_theme();

	wp_enqueue_style(
		'parent-style',
		get_template_directory_uri() . '/style.css', 
		array(),
		$theme->parent()->get('Version')
	); 

	wp_enqueue_style(
		'google-fonts',
		'https://fonts.googleapis.com/css2?family=Archivo:wght@400&family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap',
		false,
		null
	);

	wp_enqueue_style( 'amm-child-style', get_stylesheet_uri(),
		array('yobaselayer'),
		$theme->get('Version') // this only works if you have Version in the style header
	);

} 
add_action( 'wp_enqueue_scripts', 'yobaselayer_child_enqueue_styles' );


function yobaselayer_child_theme_setup() {

	$black       = '#000000';
	$blue_dark   = '#023047';
	$blue        = '#219ebc';
	$blue_light  = '#8ecae6';
	$orange      = '#f58300';
	$yellow      = '#ffc533';
	$white       = '#ffffff';

	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => esc_html__( 'Black' ),
				'slug'  => 'black',
				'color' => $black,
			),
			array(
				'name'  => esc_html__( 'Athletic Blue' ),
				'slug'  => 'blue-dark',
				'color' => $blue_dark,
			),
			array(
				'name'  => esc_html__( 'Ocean Blue' ),
				'slug'  => 'blue',
				'color' => $blue,
			),
			array(
				'name'  => esc_html__( 'Sky Blue' ),
				'slug'  => 'blue-light',
				'color' => $blue_light,
			),
			array(
				'name'  => esc_html__( 'Orange' ),
				'slug'  => 'orange',
				'color' => $orange,
			),
			array(
				'name'  => esc_html__( 'Yellow' ),
				'slug'  => 'yellow',
				'color' => $yellow,
			),
			array(
				'name'  => esc_html__( 'White' ),
				'slug'  => 'white',
				'color' => $white,
			),
		)
	);

	add_theme_support( 'editor-styles' );

	add_editor_style( 'editor-style.css' );
}
add_action( 'after_setup_theme', 'yobaselayer_child_theme_setup', 30 );

function yobaselayer_child_editor_css() {
	wp_enqueue_style( 'editor-css', get_stylesheet_directory_uri() .'/editor-style.css', false, '1.0', 'all' );
}
add_action('enqueue_block_editor_assets', 'yobaselayer_child_editor_css');


function ignite_body_classes( $classes ) {

	if ( is_page() && 'Coming Soon' == get_the_title() ) {
		$classes[] = 'coming-soon';
		return $classes;
	}
	if ( is_page() && 'Resources' == get_the_title() ) {
		$classes[] = 'resources';
		return $classes;
	}
}
add_filter( 'body_class','ignite_body_classes' );


// Register widget area.
function newyou_more_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Subfooter 1', 'yobaselayer' ),
			'id'            => 'subfooter-1',
			'description'   => esc_html__( 'Add widgets here.', 'yobaselayer' ),
			'before_widget' => '<aside id="%1$s" class="footer1-widgets widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Subfooter 2', 'yobaselayer' ),
			'id'            => 'subfooter-2',
			'description'   => esc_html__( 'Add widgets here.', 'yobaselayer' ),
			'before_widget' => '<aside id="%1$s" class="footer2-widgets widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Subfooter 3', 'yobaselayer' ),
			'id'            => 'subfooter-3',
			'description'   => esc_html__( 'Add widgets here.', 'yobaselayer' ),
			'before_widget' => '<aside id="%1$s" class="footer3-widgets widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'newyou_more_widgets_init' );

function twenty_twenty_one_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);
	echo '<p class="posted-on">';
	printf(
		esc_html__( '%s', 'twentytwentyone' ),
		$time_string // phpcs:ignore WordPress.Security.EscapeOutput
	);
	echo '</p>';
}


function twenty_twenty_one_entry_meta_footer() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	if ( ! is_single() ) { // Hide meta information on pages.
		if ( is_sticky() ) {
			echo '<p>' . esc_html_x( 'Featured post', 'Label for sticky posts', 'twentytwentyone' ) . '</p>';
		}

		$post_format = get_post_format();
		if ( 'aside' === $post_format || 'status' === $post_format ) {
			echo '<p><a href="' . esc_url( get_permalink() ) . '">' . twenty_twenty_one_continue_reading_text() . '</a></p>';
		}
		if ( has_category() || has_tag() ) {
			echo '<div class="post-taxonomies">';
			$tags_list = get_the_tag_list( '', '' );
			if ( $tags_list ) {
				printf(
					'<span class="tags-links">' . esc_html__( 'Tags: %s', 'twentytwentyone' ) . '</span>',
					$tags_list
				);
			}
			echo '</div>';
		}
	} else {
		if ( has_category() || has_tag() ) {

			echo '<div class="post-taxonomies">';

			$tags_list = get_the_tag_list( '', '' );
			if ( $tags_list ) {
				printf(
					'<span class="tags-links">' . esc_html__( 'Tags: %s', 'twentytwentyone' ) . '</span>',
					$tags_list
				);
			}
			echo '</div>';
		}
	}
}

function amm_child_excerpt_length( $length ) {
	if ( is_admin() ) {
		return $length;
	}
	return 25; // 25 words
}
add_filter( 'excerpt_length', 'amm_child_excerpt_length', 999 );

function amm_child_excerpt_link_text( $more ) {
	if ( is_admin() ) {
		return $more;
	}
	// Change text, remove the link, and return change
	return '&hellip;';
 }
 add_filter( 'excerpt_more', 'amm_child_excerpt_link_text', 999 );
?>