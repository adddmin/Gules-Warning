<?php get_header();
global $salong;
setPostViews(get_the_ID());
$topic_post_id = explode(',',get_post_meta( $post->ID, 'topic_post_id', 'true' ));
foreach($topic_post_id as $post_count=>$post_id){
    $post_count++;
}
?>
<main class="container">
    <?php echo crumb(); ?>
    <section class="wrapper">
        <article class="entry_topic">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <h1><?php echo get_the_title(); ?></h1>
            <?php salong_ad('topic_single'); ?>
            <div class="content_post">
                <?php the_content(); ?>
            </div>
            <!-- 文章end -->
            <?php endwhile; endif; ?>
            <section class="like_info">
                <div class="info">
                    <span class="post_count"><?php echo sprintf(__('<b>%s</b>篇文章','salong'),$post_count); ?></span>
                    <span><?php echo sprintf(__('<b>%s</b>人已阅读','salong'),getPostViews(get_the_ID())); ?></span>
                </div>
                <!--文章点赞-->
                <?php salong_post_like(); ?>
            </section>
            <?php if($salong[ 'switch_topic_tagshare']) { get_template_part( 'content/tag', 'share'); } ?>
            <!--上下篇文章-->
            <?php if($salong[ 'switch_topic_prevnext']) { get_template_part( 'content/single', 'prevnext'); } ?>
        </article>
        <section class="content" id="scroll">
            <section class="content_left">
                <ul class="ajaxposts">
                    <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; $args=array( 'post_type'=> 'post','ignore_sticky_posts' => 1,'post__in'=>$topic_post_id,'paged' => $paged );$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();?>
                    <li class="ajaxpost">
                        <?php get_template_part( 'content/list', 'post'); ?>
                    </li>
                    <?php endwhile;endif; ?>
                    <!-- 分页 -->
                    <?php posts_pagination(); ?>
                    <?php wp_reset_query(); $wp_query=null; $wp_query=$temp_wp_query;?>
                </ul>
            </section>
            <!-- 博客边栏 -->
            <?php salong_sidebar(4); ?>
        </section>
        <!-- 相关文章 -->
        <?php if($salong[ 'switch_topic_related']) { get_template_part( 'content/single', 'related'); } ?>
        <?php get_template_part( 'content/single', 'recommended'); ?>
    </section>
</main>
<?php get_footer(); ?>
