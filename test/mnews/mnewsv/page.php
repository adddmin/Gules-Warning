<?php get_header();
global $salong;
setPostViews(get_the_ID());
$page_shortcode = get_post_meta( $post->ID, 'page_shortcode', true);
$page_sidebar = get_post_meta( $post->ID, 'page_sidebar', true);
$sidebar = explode('-',$page_sidebar);
?>
<main class="container">
    <section class="wrapper">
        <?php if($page_sidebar){ ?>
        <section class="content" id="scroll">
            <section class="content_left">
                <?php } ?>
                <article class="entry">
                    <header class="page_header">
                        <h1>
                            <?php echo get_the_title(); ?>
                        </h1>
                    </header>
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <div class="content_post">
                        <?php the_content(); ?>
                    </div>
                    <!-- 文章end -->
                    <?php endwhile; endif; ?>
                    <?php salong_link_pages(); ?>
                    <?php echo do_shortcode($page_shortcode); ?>
                </article>
                <?php if ('open'==$post->comment_status) { comments_template(); } ?>
                <?php if($page_sidebar){ ?>
            </section>
            <?php salong_sidebar($sidebar[1]); ?>
        </section>
        <?php } ?>
    </section>
</main>
<?php get_footer(); ?>
