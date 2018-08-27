<?php
global $salong,$product,$post,$woocommerce;

//ajax 加入购物车
require_once get_template_directory() . '/woocommerce/woo-hooks.php';

//移除相关产品的联锁产品
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
//移除促销
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
// 移除WooCommerce默认面包屑
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

//添加一个『已发货』的订单状态
function register_already_shipped_order_status() {
    register_post_status( 'wc-awaiting-shipment', array(
        'label'                     => __('已发货','salong'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Awaiting shipment <span class="count">(%s)</span>', __('已发货','salong').' <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_already_shipped_order_status' );

function add_already_shipped_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-awaiting-shipment'] = __('已发货','salong');
        }
    }
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_already_shipped_to_order_statuses' );


//在商城页面启用禁止输出 p 和 br 标签
if(is_page_template('template-shop.php')){
    remove_filter (  'the_content' ,  'wpautop'  );
    remove_filter (  'the_excerpt' ,  'wpautop'  );
}

//立即购买
function buy_now_submit_form() { ?>
  <script type="text/javascript">jQuery(document).ready(function(){jQuery("#buy_now_button").click(function(){jQuery("#is_buy_now").val("1");jQuery("form.cart").submit()})});</script>
 <?php
}
add_action('woocommerce_after_add_to_cart_form', 'buy_now_submit_form');

add_filter('woocommerce_add_to_cart_redirect', 'redirect_to_checkout');
function redirect_to_checkout($redirect_url) {
  if (isset($_REQUEST['is_buy_now']) && $_REQUEST['is_buy_now']) {
     global $woocommerce;
     $redirect_url = wc_get_checkout_url();
  }
  return $redirect_url;
}

// 在主题中声明对WooCommerce的支持
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

// 禁用 WooCommerce样式
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

// WooCommerce相关产品数量
add_filter( 'woocommerce_output_related_products_args', 'wc_custom_related_products_args' );
function wc_custom_related_products_args( $args ){
    global $salong;
    $product_count = $salong['product_related_count'];
    $args = array(
        'posts_per_page' => $product_count,
        'orderby' => 'rand'
    );
    return $args;
}

/*去除不必要的小工具*/
function unregister_woo_widgets() {

//    unregister_widget( 'WC_Widget_Products' );//产品
//    unregister_widget( 'WC_Widget_Product_Categories' );//产品分类
    unregister_widget( 'WC_Widget_Product_Tag_Cloud' );//产品标签
    unregister_widget( 'WC_Widget_Rating_Filter' );//平均评分过滤器
//    unregister_widget( 'WC_Widget_Cart' );//购物车
    unregister_widget( 'WC_Widget_Layered_Nav' );//产品属性筛选
    unregister_widget( 'WC_Widget_Layered_Nav_Filters' );//Layered Nav 过滤器
    unregister_widget( 'WC_Widget_Price_Filter' );//价格筛选
    unregister_widget( 'WC_Widget_Product_Search' );//产品搜索
//    unregister_widget( 'WC_Widget_Top_Rated_Products' );//热门评分产品
//    unregister_widget( 'WC_Widget_Recent_Reviews' );//最近评论
//    unregister_widget( 'WC_Widget_Recently_Viewed' );//最近浏览
}
add_action("widgets_init", "unregister_woo_widgets", 11);



if($salong['switch_woo_fields']){
    /*我的帐户*/
    /*去除姓和名的必填属性*/
    add_filter( 'woocommerce_save_account_details_required_fields', 'salong_account_required_fields');
    function salong_account_required_fields($fields){
        unset($fields['account_first_name']);
        unset($fields['account_last_name']);
        unset($fields['account_display_name']);
        return $fields;
    }

    /*保存nickname的代码*/
    add_action( 'woocommerce_save_account_details', 'salong_woocommerce_save_account_details' );
    function salong_woocommerce_save_account_details( $user_id ) {
        update_user_meta( $user_id, 'nickname', $_POST[ 'account_nickname' ] );
    }

    /*删除结算表单*/
    add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
    function custom_override_checkout_fields( $fields ) {
        //unset($fields['order']['order_comments']);
        unset( $fields['billing']['billing_country'] );
        //unset( $fields['billing']['billing_first_name'] );
        unset( $fields['billing']['billing_last_name'] );
        unset( $fields['billing']['billing_company'] );
        unset( $fields['billing']['billing_address_1'] );
        unset( $fields['billing']['billing_address_2'] );
        unset( $fields['billing']['billing_city'] );
        unset( $fields['billing']['billing_state'] );
        unset( $fields['billing']['billing_postcode'] );
        //unset($fields['billing']['billing_email']);
        //unset( $fields['billing']['billing_phone'] );
        $fields['billing']['billing_first_name']['class'] = array('form-row-wide');
        $fields['billing']['billing_first_name']['label'] = __('姓名','salong');
        return $fields;
    }

    /*结算页面*/
    add_filter( 'woocommerce_billing_fields', 'wc_npr_filter_email', 10, 1 );
    function wc_npr_filter_email( $address_fields ) {
        $address_fields['billing_country']['required'] = false;
        $address_fields['billing_address_2']['required'] = false;
        $address_fields['billing_phone']['required'] = false;
        $address_fields['billing_email']['required'] = false;
        return $address_fields;
    }

    //移除我的账户相关页面

    add_filter ( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );
    function misha_remove_my_account_links( $menu_links ){
        unset( $menu_links['edit-address'] ); // Addresses
        //unset( $menu_links['points-and-rewards'] ); // 积分
        //unset( $menu_links['dashboard'] ); // Dashboard
        //unset( $menu_links['payment-methods'] ); // Payment Methods
        //unset( $menu_links['orders'] ); // Orders
        //unset( $menu_links['downloads'] ); // Downloads
        //unset( $menu_links['edit-account'] ); // Account details
        //unset( $menu_links['customer-logout'] ); // Logout
        return $menu_links;
    }
}

/*添加灯箱支持*/
add_action( 'after_setup_theme', 'salong_lightbbox' );
 
function salong_lightbbox() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}