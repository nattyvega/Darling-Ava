<?php
include_once( get_template_directory() . '/lib/init.php' );
//* Child theme
define( 'CHILD_THEME_NAME', 'Demo Wordpress Theme' );
define( 'CHILD_THEME_URL', 'http://darlingpixel.com' );
define( 'CHILD_THEME_VERSION', '1.0' );

add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
add_theme_support( 'genesis-responsive-viewport' );
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Primary Navigation Menu', 'genesis' ) ) );

//* Add support for custom header
add_theme_support( 'genesis-custom-header', array( 'width' => 1000, 'height' => 300 ) );
add_theme_support( 'custom-header', array(
    'default-image' => get_stylesheet_directory_uri() . '',
	'default-color' => 'efefe9',
	'header_image'    => '',
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'height'          => 300,
	'width'           => 1000,
) );

// Register responsive menu script
add_action( 'wp_enqueue_scripts', 'prefix_enqueue_scripts' );
function prefix_enqueue_scripts() {
    wp_enqueue_script( 'prefix-responsive-menu', get_stylesheet_directory_uri() . '/js/responsivemenu.js', array( 'jquery' ), '1.0.0', true ); // Change 'prefix' to your theme's prefix
}

function demo_scripts() {
	wp_enqueue_style( 'fontawesome', 'http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );
}
add_action( 'wp_enqueue_scripts', 'demo_scripts' );

//* Modify the WordPress read more link
add_filter( 'the_content_more_link', 'sp_read_more_link' );
function sp_read_more_link() {
	return '<a class="more-link" href="' . get_permalink() . '">Continue Reading</a>';
}
//Footer Text
function genesischild_footer_creds_text () {
  echo '<div class="creds">Blog Design by <a href="http://darlingpixel.com/" target="_blank">Darling Pixel</a>.</div>';
}
add_filter( 'genesis_footer_creds_text', 'genesischild_footer_creds_text' );

/** Custom Post Meta and Footer **/

//* Customize the entry meta in the entry header (requires HTML5 theme support)
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter($post_info) {
	$post_info = '[post_date] [post_edit]';
	return $post_info;
}

//* Remove the post meta function
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
 
// Add New Post footer
add_action( 'genesis_entry_footer', 'new_genesis_post_footer' );
function new_genesis_post_footer() {
if(!is_feed() && !is_page()) { ?>
	<span class="catlinkwrap"> Categories: <?php the_category(', '); ?></span>

	<?php if ( comments_open() ) : ?>
    <span class="comments-link">
      <?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a Comment', 'twentytwelve' ) . '</span>', __( '1 Comment', 'twentytwelve' ), __( '% Comments', 'twentytwelve' ) ); ?>
    </span>
    <!-- .comments-link -->
    
    <?php endif; // comments_open() ?>
<?php }
}

/** Define a default post thumbnail */
add_filter('genesis_get_image', 'default_image_fallback', 10, 2);
function default_image_fallback($output, $args) {
    global $post;
    if( $output || $args['size'] == 'full' )
        return $output;
 
    $thumbnail = CHILD_URL.'/images/thumb.jpg';
 
    switch($args['format']) {
 
        case 'html' :
            return '<img src="'.$thumbnail.'" class="attachment-'. $args['size'] .'" alt="'. get_the_title($post->ID) .'" />';
            break;
        case 'url' :
            return $thumbnail;
            break;
       default :
           return $output;
            break;
    }
}
//add new thumbnail size to genesis featured
add_image_size('sidebar-featured', 280, 280, TRUE);

//default favicon
add_filter( 'genesis_pre_load_favicon', 'new_icon' );
function new_icon( $icon) {
    $icon = CHILD_URL.'/images/favicon.ico';
    return $icon;
}

/** Unregister other site layouts */
genesis_unregister_layout('content-sidebar-sidebar');
genesis_unregister_layout('sidebar-sidebar-content');
genesis_unregister_layout('sidebar-content-sidebar');

//* Remove the header right widget area
unregister_sidebar( 'header-right' );
unregister_sidebar( 'sidebar-alt' );

/** Move Primary Nav Menu Above Header */
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_nav' );

//* Register widget areas
genesis_register_sidebar( array(
	'id'			=> 'sidebar-split-left',
	'name'			=> __( 'Sidebar Split Left', 'divine' ),
	'description'	=> __( 'This is the left side of the split sidebar', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'sidebar-split-right',
	'name'			=> __( 'Sidebar Split Right', 'divine' ),
	'description'	=> __( 'This is the right side of the split sidebar', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'sidebar-split-bottom',
	'name'			=> __( 'Sidebar Split Bottom', 'divine' ),
	'description'	=> __( 'This is the bottom of the split sidebar', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'before-content',
	'name'        => __( 'Home - Before Content', 'divine' ),
	'description' => __( 'This is the slider section on the home page.', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-slider',
	'name'        => __( 'Home - Slider', 'divine' ),
	'description' => __( 'This is the slider section on the home page.', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-top',
	'name'        => __( 'Home - Top', 'divine' ),
	'description' => __( 'This is the top section of the home page.', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-triple-bottom',
	'name'        => __( 'Home - Triple Bottom', 'divine' ),
	'description' => __( 'This is the bottom section of the home page.', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-double-bottom',
	'name'        => __( 'Home - Double Bottom', 'divine' ),
	'description' => __( 'This is the bottom section of the home page.', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'category-index',
	'name'        => __( 'Category Index', 'divine' ),
	'description' => __( 'This is the category index for the category index page template.', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'widget-above-header',
	'name'			=> __( 'Widget Above Header', 'divine' ),
	'description'	=> __( 'This is the widget area above the header', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'widget-before-footer',
	'name'			=> __( 'Widget Before Footer', 'divine' ),
	'description'	=> __( 'This is the widget area above the header', 'divine' ),
) );
genesis_register_sidebar( array(
	'id'          => 'nav-social-menu',
	'name'        => __( 'Nav Social Menu', 'divine' ),
	'description' => __( 'This is the nav social menu section.', 'divine' ),
) );
