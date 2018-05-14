<?php



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