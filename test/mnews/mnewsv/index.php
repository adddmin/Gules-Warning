<?php get_header();
global $salong;
$home_layout = $salong[ 'home_layout'][ 'enabled'];
$slides_mode = $salong['slides_mode'];
if($slides_mode == 'custom'){
    $slides_name = 'custom';
}else{
    $slides_name = 'sticky';
}
?>
<!-- 幻灯片 -->
<main class="container">
    <div class="wrapper">
        <?php get_template_part( 'content/home-slides', $slides_name); ?>
        <?php if ($home_layout): foreach ($home_layout as $fallthrume=>$value) {
            switch($fallthrume) {
                case 'home_new' : get_template_part( 'content/home', 'new'); break;
                case 'home_cat_post' : salong_home_cat('post','category'); break;
                case 'home_ad1' : home_ad(1); break;
                case 'home_topic' : get_template_part( 'content/home', 'topic'); break;
                case 'home_cat_topic' : salong_home_cat('topic','tcat'); break;
                case 'home_download' : get_template_part( 'content/home', 'download'); break;
                case 'home_cat_download' : salong_home_cat('download','dcat'); break;
                case 'home_ad2' : home_ad(2); break;
                case 'home_video' : get_template_part( 'content/home', 'video'); break;
                case 'home_cat_video' : salong_home_cat('video','vcat'); break;
                case 'home_product' : get_template_part( 'content/home', 'product'); break;
                case 'home_cat_product' : salong_home_cat('product','product_cat'); break;
                case 'home_link' : get_template_part( 'content/home', 'link'); break;
            }
        } endif; ?>
    </div>
</main>
<?php get_footer(); ?>
