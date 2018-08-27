<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
global $salong,$post,$product,$woocommere,$wp,$wp_query;

/*面包屑设置*/
if(is_tax()){
    $cat_ID  = get_queried_object()->term_id;
    $thumb   = get_term_meta($cat_ID,'thumb',true);
    $opacity = get_term_meta($cat_ID,'thumb_opacity',true);
    $desc    = category_description();
}else{
    $thumb    = $salong['product_bg']['url'];
    $opacity  = $salong['product_bg_rgba']['alpha'];
    $desc     = $salong['product_desc'];
}

/*****筛选设置*****/
//获取当前分类的url
$base_url     = home_url(add_query_arg(array(),$wp->request));

//获取筛选数组
$sift_array   = salong_get_sift_array();

//拆分数组，将all加上

$price_array['all']  = __('全部','salong');
$price_array         = array_merge( $price_array, $sift_array['price']);
$price_keys          = array_keys( $price_array);

$paixu_array['all']  = __('默认','salong');
$paixu_array         = array_merge( $paixu_array, $sift_array['paixu']);
$paixu_keys          = array_keys( $paixu_array);

//从url中获取要筛选的参数，放入数组中，默认为all
$sift_vars = array();
$sift_vars['price'] = get_query_var('price', 'all');
$sift_vars['paixu'] = get_query_var('paixu', 'all');

//为add_query_arg函数的参数做准备
$price_params = array();
$paixu_params = array();

//判断价格是否合法，给另外N项加上
if( in_array( $sift_vars['price'], $price_keys ) ){
    $paixu_params['price'] = $sift_vars['price'];
}
//判断排序是否合法，给另外N项加上
if( in_array( $sift_vars['paixu'], $paixu_keys ) ){
    $price_params['paixu'] = $sift_vars['paixu'];
}
$selected = ' class="current-cat"';

//获取搜索字段
$s = $_GET['s'];

if(is_search()){
    $base_url = $base_url.'?&post_type=product&s='.$s;
}

/**********分类**********/

if(is_search() || is_tax() || is_tag()){
    $current_cat        = $wp_query->queried_object;//当前分类
    $current_cat_ID     = $current_cat->term_id;//当前分类 ID
    $current_parent_id  = $current_cat->parent;//父级分类 ID
    $top_cat_id         = salong_category_top_parent_id ($current_cat_ID,'product_cat');//顶级分类ID
    $children_cat       = get_terms( 'product_cat', array('parent'=> $current_cat_ID,'hide_empty' => false) );//子分类

    if($current_parent_id && $top_cat_id != $current_parent_id){
        //三级分类
        $cate3_id     = $current_parent_id;
        $cate2_id     = $top_cat_id;
        $cate2_url    = get_term_link( (int)$top_cat_id, 'product_cat' );
        $cate3_url    = get_term_link( (int)$current_parent_id, 'product_cat' );
        $subli_class  = '';
        $level = 3;
    }else if($current_parent_id && $top_cat_id == $current_parent_id){
        //二级分类
        $cate3_id     = $current_cat_ID;
        $subsubli_class  = ' class="current-cat"';
        $cate2_id     = $current_parent_id;
        $cate2_url    = get_term_link( (int)$current_parent_id, 'product_cat' );
        $subli_class  = '';
        $level = 2;
    }else{
        //一级分类
        $cate2_id     = $current_cat_ID;
        $cate2_url    = get_term_link( (int)$current_cat_ID, 'product_cat' );
        $subli_class  = ' class="current-cat"';
        $level = 1;
    }
    
    $li_class = '';
    
    $args_cate2 = array('title_li'=>'','show_count'=>1,'orderby'=>'count','order'=>'DESC','taxonomy'=>'product_cat','child_of'=>$cate2_id,'depth'=>1);//二级分类
    $args_cate3 = array('title_li'=>'','show_count'=>1,'orderby'=>'count','order'=>'DESC','taxonomy'=>'product_cat','child_of'=>$cate3_id,'depth'=>1);//三级分类
    
    if(is_search()){
        $li_class = $selected;
    }else{
        $li_class = '';
    }
    
    /*面包屑背景透明*/
    $point = '.';
}else{
    $li_class = $selected;
    $point = '';
}
$args_cate1 = array( 'title_li'=>'','show_count'=>1,'orderby'=>'count','order'=>'DESC','taxonomy'=>'product_cat','depth'=>1);//一级分类


?>
    <main class="container">
        <?php if($thumb){ ?>
        <section class="crumbs_img" style="background-image: url(<?php echo $thumb; ?>);">
            <section class="crumbs_con">
                <h1>
                    <?php echo woocommerce_page_title(); ?>
                </h1>
                <?php if($desc){ echo '<p>'.$desc.'</p>'; } ?>
            </section>
            <?php if($opacity){ echo '<div class="bg" style="opacity: '.$point.$opacity.';"></div>'; } ?>
        </section>
        <?php } ?>
        <?php if($salong['switch_crumbs']){ echo salong_breadcrumbs(); } ?>
        <div class="wrapper">
            <?php salong_ad('product_list'); ?>
            <section class="product_sift">
                <div class="sift_li">
                    <h4>
                        <?php _e('分类','salong'); ?>
                    </h4>
                    <ul class="sift_more">
                        <li<?php echo $li_class; ?>>
                            <a href="<?php echo get_page_link(wc_get_page_id('shop')); ?>">
                                <?php _e('全部','salong'); ?>
                            </a>
                            (<?php echo wp_count_posts('product')->publish; ?>)
                        </li>
                        <?php wp_list_categories($args_cate1); ?>
                    </ul>
                </div>
                <?php if( ($children_cat && $level == 1 ) || $level == 2 || $level == 3){ ?>
                <div class="sift_li">
                    <h4><?php _e('二级分类','salong'); ?></h4>
                    <ul class="sift_more">
                        <li<?php echo $subli_class; ?>>
                            <a href="<?php echo $cate2_url; ?>">
                                <?php _e('全部','salong'); ?>
                            </a>
                            (<?php echo salong_category_post_count($cate2_id,'product_cat'); ?>)
                        </li>
                        <?php wp_list_categories($args_cate2); ?>
                    </ul>
                </div>
                <?php } ?>
                <?php if( ($children_cat && $level == 2 ) || $level == 3){ ?>
                <div class="sift_li">
                    <h4><?php _e('三级分类','salong'); ?></h4>
                    <ul class="sift_more">
                        <li<?php echo $subsubli_class; ?>>
                            <a href="<?php echo $cate3_url; ?>">
                                <?php _e('全部','salong'); ?>
                            </a>
                            (<?php echo salong_category_post_count($cate3_id,'product_cat'); ?>)
                        </li>
                        <?php wp_list_categories($args_cate3); ?>
                    </ul>
                </div>
                <?php } ?>
                <div class="sift_li">
                    <h4>
                        <?php _e('价格','salong'); ?>
                    </h4>
                    <ul class="sift_more">
                        <?php
                        foreach( $price_array as  $key=>$name ){
                            $price_params['price'] = $key;
                            if($key == 'all'){
                                $value = '';
                            }else{
                                $value = $key;
                            }
                            // 自定义值下的文章数量
                            if(!is_shop()){
                                if(is_tax('product_cat') || is_search()){
                                    $tax = 'product_cat';
                                }else{
                                    $tax = 'product_tag';
                                }
                                $tax_query = array(
                                    array(
                                        'taxonomy'  => $tax,
                                        'field'     => 'id',
                                        'terms'     => $current_cat_ID,
                                    ),
                                );
                            }
                            $price_args = array(
                                'post_type' =>'product',
                                'tax_query' => $tax_query,
                                's'         => $s,
                                'meta_query'=> array(
                                    array(
                                        'key'     =>'price',
                                        'value'   => $value,
                                        'compare' => 'LIKE'
                                    )
                                )
                            );
                            $price_args = new WP_Query( $price_args );
                            $price_count = $price_args->found_posts;
                        ?>
                        <li<?php if( $sift_vars[ 'price']==$key ) echo $selected; ?>>
                            <a href="<?php echo esc_url( add_query_arg( $price_params, $base_url ) ); ?>">
                                <?php echo $name; ?>
                            </a>
                            (<?php echo $price_count; ?>)
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="sift_li">
                    <h4>
                        <?php _e('排序','salong'); ?>
                    </h4>
                    <ul class="sift_more">
                        <?php
                        foreach( $paixu_array as  $key=>$name ){
                            $paixu_params['paixu'] = $key;
                        ?>
                        <li<?php if( $sift_vars[ 'paixu']==$key ) echo $selected; ?>>
                        <a href="<?php echo esc_url( add_query_arg( $paixu_params, $base_url ) ); ?>">
                            <?php echo $name; ?>
                        </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </section>
            <section class="product_all">
                <?php
                //价格
                if($_GET['price']){
                    $sift_price = explode(PHP_EOL,$salong['sift_price']);
                    foreach ($sift_price as $value) {
                        $price_arr = explode('=', $value );
                        switch ($_GET['price']) {
                            case $price_arr[0] :
                                $price = $price_arr[0];
                                break ;
                            case 'all' :
                                $price = '';
                                break ;
                        }
                    }
                }

                //排序
                switch ($_GET['paixu']){
                    case 'price' :
                        $order    = 'ASC';
                        $orderby  = 'meta_value_num';
                        $meta_key = '_price';
                        break;
                    case 'price-desc' :
                        $order    = 'DESC';
                        $orderby  = 'meta_value_num';
                        $meta_key = '_price';
                        break;
                    case 'popularity':
                        $order    = 'DESC';
                        $orderby  = 'meta_value_num';
                        $meta_key = 'total_sales';
                        break;
                    case 'view':
                        $order    = 'DESC';
                        $orderby  = 'meta_value_num';
                        $meta_key = 'views';
                        break;
                    case 'like':
                        $order    = 'DESC';
                        $orderby  = 'meta_value_num';
                        $meta_key = 'salong_post_like_count';
                        break;
                    case 'rating':
                        $order = 'DESC';
                        $orderby = array(
                            'meta_value_num' => 'DESC',
                            'ID'             => 'ASC',
                        );
                        $meta_key = '_wc_average_rating';
                        break;
                    case 'rand':
                        $order    = '';
                        $orderby  = 'rand';
                        $meta_key = '';
                        break;
                    default:
                        $order    = 'DESC';
                        $orderby  = 'date ID';
                        $meta_key = '';
                }

                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                $args = array(
                    'post_type'           => 'product',
                    'ignore_sticky_posts' => 1,
                    'paged'               => $paged,
                    'orderby'             => $orderby,
                    'order'               => $order,
                    'meta_key'            => $meta_key,
                    'meta_query'     => array(
                        array(
                            'key'     => '_visibility',
                            'value'   => array('catalog', 'visible'),
                            'compare' => 'IN',
                        )
                    ),
                    'meta_query'          => array(
                        'relation' => 'AND',
                        array(
                            'key'     => 'price',
                            'value'   => $price,
                            'compare' => 'LIKE'
                        )
                    )
                );
                if(is_tax() || is_tag() || is_search()){
                    $arms = array_merge($args, $wp_query->query);
                    query_posts($arms);
                }else{
                    $wp_query = new WP_Query( $args );
                }
                if ( $wp_query->have_posts() ) : ?>
                <ul class="layout_ul ajaxposts">
                    <?php while ( $wp_query->have_posts() ) : $wp_query->the_post();
                            wc_get_template_part( 'content', 'product' );
                    endwhile; ?>
                    <?php posts_pagination(); ?>
                </ul>
                <?php else: ?>
                <p class="warningbox">
                    <?php _e( '非常抱歉，没有相关产品文章。'); ?>
                </p>
                <?php endif; ?>
            </section>
        </div>
    </main>
    <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/readmore.min.js"></script>
    <script type="text/javascript">
        $window_width = $(window).width();

        if($window_width > 480){
            $height = 44;
        }else{
            $height = 36;
        }

        /*更多*/
        $('.sift_more').readmore({
            moreLink: '<a href="#" class="more"><?php _e('更多','salong'); ?></a>',
            lessLink: '<a href="#" class="more"><?php _e('收起','salong'); ?></a>',
            speed: 75,
            collapsedHeight: $height
        });

    </script>
    <?php
get_footer( 'shop' );
