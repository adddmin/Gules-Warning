<?php get_header(); global $salong; ?>
<main class="container">
    <?php echo crumb(); ?>
    <div class="wrapper">
        <?php salong_ad('post_list'); ?>
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
