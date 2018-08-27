<?php get_header(); ?>
<?php global $salong; ?>
<main class="container">
    <?php if($salong['switch_crumbs']){ echo salong_breadcrumbs(); } ?>
    <div class="wrapper">
        <?php salong_ad('video_list'); ?>
        <section class="video_all">
            <ul class="layout_ul ajaxposts">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <li class="layout_li ajaxpost">
                    <?php get_template_part( 'content/list', 'video'); ?>
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
    </div>
</main>
<?php get_footer(); ?>

