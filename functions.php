<?php


//======================================================================
// Register and Load Scripts and Stylesheets
//====================================================================== 

function skeleton_scripts()  
    {  
         
        // Register scripts
		wp_register_script( 'scripts', get_template_directory_uri() . '/js/build/production.min.js', array( 'jquery'), '1.0', true);
		
        // Enqueue scripts 
		wp_enqueue_script( 'scripts' );
		
    }  
add_action( 'wp_enqueue_scripts', 'skeleton_scripts' );  

function skeleton_styles()  
	{
	
		// Register styles
		wp_register_style( 'output', get_template_directory_uri() . '/css/output.css', array(), '1.0', 'all' );

		// Enqueue styles 
		wp_enqueue_style( 'output' );
	
	}
add_action( 'wp_enqueue_scripts', 'skeleton_styles' );


//======================================================================
// Register Fun Things
//====================================================================== 

//-----------------------------------------------------
// Register and set post thumbnail sizes
//-----------------------------------------------------

add_theme_support( 'post-thumbnails' );

add_image_size( 'product_cat_image_size', 316, 316, true ); // product_cat_image
add_image_size( 'blog_image_size', 900, 400, true ); // blog image size
add_image_size( 'blog_full_image_size', 1600, 9999 ); // blog full image size
add_image_size( 'callout_image_size', 360, 240,true ); // callout module
add_image_size( 'sort_product_image_size', 263, 9999 ); // home module
 


//-----------------------------------------------------
// Register Navigations
//-----------------------------------------------------

add_action( 'init', 'my_custom_menus' );
function my_custom_menus() {
    register_nav_menus(
        array(
            'primary-menu' => __( 'Primary Menu' )
        )
    );
}



//-----------------------------------------------------
// Register Widget Areas
//-----------------------------------------------------

//widget support for a right sidebar
register_sidebar(array(
  'name' => 'Shop SideBar',
  'id' => 'shop-sidebar',
  'description' => 'Shop SideBar',
  'before_widget' => '<div id="%1$s" class="widget">',
  'after_widget'  => '</div>',  
  'before_title' => '<h4>',
  'after_title' => '</h4>'
));

register_sidebar(array(
  'name' => 'Blog SideBar',
  'id' => 'blog-sidebar',
  'description' => 'Blog SideBar',
  'before_widget' => '<div id="%1$s" class="widget">',
  'after_widget'  => '</div>',  
  'before_title' => '<h4>',
  'after_title' => '</h4>'
));


//======================================================================
// Setup things WordPress should do by default
//======================================================================

//-----------------------------------------------------
// Allow shortcodes in widgets
//-----------------------------------------------------

add_filter('widget_text', 'do_shortcode');

//-----------------------------------------------------
// Remove Generator for Security
//-----------------------------------------------------

remove_action( 'wp_head', 'wp_generator' );

//-----------------------------------------------------
// Change wordpress howdy text
//-----------------------------------------------------

add_action( 'admin_bar_menu', 'wp_admin_bar_my_custom_account_menu', 11 );

function wp_admin_bar_my_custom_account_menu( $wp_admin_bar ) {
$user_id = get_current_user_id();
$current_user = wp_get_current_user();
$profile_url = get_edit_profile_url( $user_id );

if ( 0 != $user_id ) {
/* Add the "My Account" menu */
$avatar = get_avatar( $user_id, 28 );
$howdy = sprintf( __('Welcome Back, %1$s'), $current_user->display_name );
$class = empty( $avatar ) ? '' : 'with-avatar';

$wp_admin_bar->add_menu( array(
'id' => 'my-account',
'parent' => 'top-secondary',
'title' => $howdy . $avatar,
'href' => $profile_url,
'meta' => array(
'class' => $class,
),
) );
}
};

//-----------------------------------------------------
// Remove admin WordPress logo
//-----------------------------------------------------

function site_admin_bar_remove() 
        {       
         global $wp_admin_bar;   
             /* Remove their stuff */ 
               $wp_admin_bar->remove_menu('wp-logo');
        }
add_action('wp_before_admin_bar_render', 'site_admin_bar_remove', 0); 


//======================================================================
// Customize WordPress admin
//======================================================================

//-----------------------------------------------------
// Replace default admin login logo and link
//----------------------------------------------------- 
 
function custom_loginlogo() {
echo '<style type="text/css">
.login h1 a  {background-image: url('.get_bloginfo('template_directory').'/images/admin-logo.jpg) !important;
    background-size: 320px 80px !important; width:320px !important; height:80px !important }
</style>';
}
add_action('login_head', 'custom_loginlogo');
add_filter( 'login_headerurl', 'custom_loginlogo_url' );
function custom_loginlogo_url($url) {
	return '/';
}

//-----------------------------------------------------
// Replace admin footer text
//----------------------------------------------------- 

function remove_footer_admin () {
echo 'Fueled by <a href="http://www.wordpress.org" target="_blank">WordPress</a> | Website by <a href="http://sonoradesignworks.com" target="_blank">Sonora DesignWorks</a></p>';
    }

add_filter('admin_footer_text', 'remove_footer_admin'); 


//======================================================================
// Disable things not needed for this specific theme
//======================================================================


//-----------------------------------------------------
// Remove Tags support for Posts
//----------------------------------------------------- 

add_action('init', 'unregister_posts_tag');
function unregister_posts_tag() {
    unregister_taxonomy_for_object_type('post_tag', 'post');
}

//-----------------------------------------------------
// Remove Welcome screen from dashboard
//----------------------------------------------------- 

remove_action('welcome_panel', 'wp_welcome_panel');

//-----------------------------------------------------
// Remove Dashboard Widgets
//----------------------------------------------------- 

function remove_dashboard_widgets() {
	global $wp_meta_boxes;
	//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal'); 
}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );




//-----------------------------------------------------
// Disable default widgets 
//----------------------------------------------------- 

function Wps_unregister_default_widgets() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    //unregister_widget('WP_Widget_Text');
    //unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
}
add_action('widgets_init', 'Wps_unregister_default_widgets', 1);



//-----------------------------------------------------
// Remove Admin Menus
//----------------------------------------------------- 

add_action('admin_menu', 'remove_menus', 102);
function remove_menus()
{
global $submenu;

//remove_menu_page( 'edit.php' ); // Posts
//remove_menu_page( 'upload.php' ); // Media
remove_menu_page( 'link-manager.php' ); // Links
//remove_menu_page( 'edit-comments.php' ); // Comments
//remove_menu_page( 'edit.php?post_type=page' ); // Pages
//remove_menu_page( 'plugins.php' ); // Plugins
//remove_menu_page( 'themes.php' ); // Appearance
//remove_menu_page( 'users.php' ); // Users
//remove_menu_page( 'tools.php' ); // Tools
//remove_menu_page('options-general.php'); // Settings

//remove_submenu_page ( 'index.php', 'update-core.php' );    //Dashboard->Updates
//remove_submenu_page ( 'themes.php', 'themes.php' ); // Appearance-->Themes
//remove_submenu_page ( 'themes.php', 'widgets.php' ); // Appearance-->Widgets
remove_submenu_page ( 'themes.php', 'theme-editor.php' ); // Appearance-->Editor
//remove_submenu_page ( 'options-general.php', 'options-general.php' ); // Settings->General
//remove_submenu_page ( 'options-general.php', 'options-writing.php' ); // Settings->writing
//remove_submenu_page ( 'options-general.php', 'options-reading.php' ); // Settings->Reading
//remove_submenu_page ( 'options-general.php', 'options-discussion.php' ); // Settings->Discussion
//remove_submenu_page ( 'options-general.php', 'options-media.php' ); // Settings->Media
//remove_submenu_page ( 'options-general.php', 'options-privacy.php' ); // Settings->Privacy
}


//======================================================================
// Shortcodes
//======================================================================

//-----------------------------------------------------
// Clear line break
//-----------------------------------------------------

add_shortcode('break', 'short_break');

function short_break () {
return '<br class="clear">';
}

//-----------------------------------------------------
// button shortcode
//-----------------------------------------------------

function myButton($atts, $content = null) {
   extract(shortcode_atts(array('link' => '#'), $atts));
   return '<a class="button" href="'.$link.'">' . do_shortcode($content) . '</a>';
}
add_shortcode('button', 'myButton');

//-----------------------------------------------------
// font awesome icon shortcode
//-----------------------------------------------------

function myIcon($atts, $content = null) {
   extract(shortcode_atts(array('link' => '#'), $atts));
   return '<a href="'.$link.'" class="icon-link" target="_blank"><i class="fa ' . do_shortcode($content) . ' " aria-hidden="true"></i></a>';
}
add_shortcode('icon', 'myIcon');

//-----------------------------------------------------
// jump target
//-----------------------------------------------------

function targetLink($atts, $content = null) {
   extract(shortcode_atts(array('id' => '#'), $atts));
   return '<a class="jumptarget" id="'.$id.'"></a>';
}
add_shortcode('target', 'targetLink');





//======================================================================
// Customize TinyMce editor
//======================================================================

//-----------------------------------------------------
// Enable font size & font family selects in the editor
//-----------------------------------------------------

if ( ! function_exists( 'wpex_mce_buttons' ) ) {
	function wpex_mce_buttons( $buttons ) {
		array_unshift( $buttons, 'fontselect' ); // Add Font Select
		array_unshift( $buttons, 'fontsizeselect' ); // Add Font Size Select
		return $buttons;
	}
}
add_filter( 'mce_buttons_2', 'wpex_mce_buttons' );

//-----------------------------------------------------
// Set font sizes
//-----------------------------------------------------

if ( ! function_exists( 'wpex_mce_text_sizes' ) ) {
	function wpex_mce_text_sizes( $initArray ){
		$initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 15px 16px 18px 20px 22px 24px 25px 28px 32px 36px";
		return $initArray;
	}
}
add_filter( 'tiny_mce_before_init', 'wpex_mce_text_sizes' );

//-----------------------------------------------------
// Set fonts
//-----------------------------------------------------

if ( ! function_exists( 'wpex_mce_google_fonts_array' ) ) {
	function wpex_mce_google_fonts_array( $initArray ) {
	    $initArray['font_formats'] = 'Arial=arial,helvetica,sans-serif;montserratlight=montserratlight;montserratregular=montserratregular;montserratmedium=montserratmedium;montserratbold=montserratbold;';
            return $initArray;
	}
}
add_filter( 'tiny_mce_before_init', 'wpex_mce_google_fonts_array' );

//======================================================================
// Gravity Forms
//======================================================================//


//-----------------------------------------------------
// Fix Gravity Form Tabindex Conflicts
//-----------------------------------------------------

add_filter( 'gform_tabindex', 'gform_tabindexer', 10, 2 );
function gform_tabindexer( $tab_index, $form = false ) {
    $starting_index = 1000; // if you need a higher tabindex, update this number
    if( $form )
        add_filter( 'gform_tabindex_' . $form['id'], 'gform_tabindexer' );
    return GFCommon::$tab_index >= $starting_index ? GFCommon::$tab_index : $starting_index;
}

//-----------------------------------------------------
// Hide labels
//-----------------------------------------------------

add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

//-----------------------------------------------------
// stop anchor jump
//-----------------------------------------------------


add_filter( 'gform_confirmation_anchor', '__return_false' );



//-----------------------------------------------------
// Hide ACF field group menu item
//-----------------------------------------------------
//add_filter('acf/settings/show_admin', '__return_false');

//-----------------------------------------------------
// Theme Options
//-----------------------------------------------------


if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> '404 Page Settings',
		'menu_title'	=> '404 Page',
		'parent_slug'	=> 'theme-general-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Header Settings',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'theme-general-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Shop',
		'menu_title'	=> 'Shop',
		'parent_slug'	=> 'theme-general-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Blog',
		'menu_title'	=> 'Blog',
		'parent_slug'	=> 'theme-general-settings',
	));
	
	
	
	
}

//======================================================================
// WOO
//======================================================================//


//-----------------------------------------------------
// remove related products
//-----------------------------------------------------

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

//-----------------------------------------------------
// remove tabs
//-----------------------------------------------------

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] );      	// Remove the description tab
    unset( $tabs['reviews'] ); 			// Remove the reviews tab
    //unset( $tabs['additional_information'] );  	// Remove the additional information tab

    return $tabs;

}

//-----------------------------------------------------
// Change the heading on the Additional Information tab
//-----------------------------------------------------

add_filter( 'woocommerce_product_additional_information_heading', 'isa_additional_info_heading' );
 
function isa_additional_info_heading() {
    return 'Product Specs';
}

//-----------------------------------------------------
// Move product tabs to right column
//-----------------------------------------------------
 
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60 );

//-----------------------------------------------------
// disable main Woo css sheet
//-----------------------------------------------------

add_filter( 'woocommerce_enqueue_styles', 'jk_dequeue_styles' );
function jk_dequeue_styles( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
	//unset( $enqueue_styles['woocommerce-layout'] );		// Remove the layout
	//unset( $enqueue_styles['woocommerce-smallscreen'] );	// Remove the smallscreen optimisation
	return $enqueue_styles;
}


//-----------------------------------------------------
// remove categories from single product page
//-----------------------------------------------------

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

//-----------------------------------------------------
// fix missing product title
//-----------------------------------------------------

remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5);
add_action('woocommerce_single_product_summary', 'woocommerce_my_single_title',5);

if ( ! function_exists( 'woocommerce_my_single_title' ) ) {
   function woocommerce_my_single_title() {
            echo'<h1 itemprop="name" class="product_title entry-title">';
			the_title();
			echo'</h1>';

    }
}

//-----------------------------------------------------
// remove default sorting dropdown
//-----------------------------------------------------
 
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

//-----------------------------------------------------
//declare WC support
//-----------------------------------------------------

function etco_theme_wc_support() {
  add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'etco_theme_wc_support' );


function print_menu_shortcode($atts, $content = null) {
extract(shortcode_atts(array( 'name' => null, ), $atts));
return wp_nav_menu( array( 'menu' => $name, 'echo' => false ) );
}
add_shortcode('menu', 'print_menu_shortcode');

//-----------------------------------------------------------------------------// 
//----[ Customize Woo Product search widget             
//-----------------------------------------------------------------------------//
 
 add_filter( 'get_product_search_form' , 'woo_custom_product_searchform' );


function woo_custom_product_searchform( $form ) {
	
	$form = '<form role="search" class="woocommerce-product-search"  method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
		<div>
			<label class="screen-reader-text" for="s">' . __( 'Search for:', 'woocommerce' ) . '</label>
			<input type="search" class="search-field"value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search', 'woocommerce' ) . '" />
			<input type="submit" id="searchsubmit" value="'. esc_attr__( 'Search', 'woocommerce' ) .'" />
			<input type="hidden" name="post_type" value="product" />
		</div>
	</form>';
	
	return $form;
	
}



//-----------------------------------------------------------------------------// 
//----[ Woo - Display Product Category Title on Shop Page            
//-----------------------------------------------------------------------------//

add_action( 'woocommerce_after_shop_loop_item_title', 'vee_add_product_cat', 25);
function vee_add_product_cat()
{
    global $product;
    $product_cats = wp_get_post_terms($product->id, 'product_cat');
    $count = count($product_cats);
    foreach($product_cats as $key => $cat)
    {
        echo 
		'<span class="vee_category_title">
		<span class="vee_category_title_'.$cat->slug.'">'.$cat->name.'</span></span>';
        if($key < ($count-1))
        {
            echo ' ';
        }
        else
        {
            echo ' ';
        }
    }
}





?>