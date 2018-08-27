<?php get_header();
global $salong;

if($salong['switch_post_cat_order']){
    switch ($_GET['order']){
        case 'like' : $orderby = 'meta_value_num'; $meta_key = 'salong_post_like_count';
            break;
        case 'views' : $orderby = 'meta_value_num'; $meta_key = 'views';
            break;
        case 'title' : $orderby = 'title'; $meta_key = '';
            break;
        case 'comment' : $orderby = 'comment_count'; $meta_key = '';
            break;
        case 'rand' : $orderby = 'rand'; $meta_key = '';
            break;
        default : $orderby = 'post_date'; $meta_key = '';
    }
    /*筛选*/
    $args= array(
        'meta_key'=> $meta_key,
        'orderby' => $orderby,
        'order'   => 'DESC'
    );
    $arms = array_merge($args, $wp_query->query);
    query_posts($arms);
}
?>
<main class="container">
    <?php echo crumb(); ?>
    <div class="wrapper">
        <?php salong_ad('post_list'); if($salong['switch_post_cat_order']){ get_template_part( 'content/sift', 'cat'); } ?>
        <section class="content" id="scroll">
            <section class="content_left">
                <ul class="ajaxposts">
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <li class="ajaxpost">
                        <?php get_template_part( 'content/list', 'post'); ?>
                    </li>
                    <?php endwhile; else: ?>
                    <p class="warningbox">
                        <?php _e( '非常抱歉，没有相关文章。'); ?>
                    </p>
                    <?php endif; ?>
                    <!-- 分页 -->
                    <?php posts_pagination(); ?>
                </ul>
            </section>
            <!--边栏-->
            <?php salong_sidebar(2); ?>
        </section>
    </div>
</main>
<?php get_footer(); ?>
