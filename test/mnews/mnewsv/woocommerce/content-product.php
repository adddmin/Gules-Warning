<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $salong,$post,$product,$woocommere;

/*缩略图大小*/
if( $salong['thumb_mode']== 'timthumb'){
    $width = $salong['product_cateimg_width'];
    $height = $salong['product_cateimg_height'];
} else {
    $width = '';
    $height = '';
}

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

?>
    <li class="layout_li<?php if( !is_home() && ( is_shop() || is_tag() || is_tax() || is_search() ) ){ echo ' ajaxpost'; } ?>">
        <article class="product_main">
            <div class="product_img">
                <a href="<?php the_permalink() ?>" class="imgeffect" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>>
                    <?php post_thumbnail($width,$height); ?>
                </a>
                <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
                <?php if( wc_customer_bought_product( '', get_current_user_id(), $product->get_id() )){ echo '<span class="purchased">'.__('已购买','salong').'</span>'; }else if ( $product->is_on_sale() ){ ?>
                <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>
                <?php } ?>
                <?php if($product->is_featured()){ echo '<span class="recommend">'.__('特色','salong').'</span>'; } ?>
            </div>
            <div class="product_con">
                <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>><?php the_title(); ?></a></h2>
                <?php echo woocommerce_template_loop_price(); ?>
            </div>
        </article>
    </li>
