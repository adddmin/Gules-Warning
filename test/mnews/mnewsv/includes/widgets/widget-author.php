<?php global $salong;

$curauth_id    = get_the_author_meta('ID');
$curauth_name  = get_the_author_meta('display_name');

//作者头像背景
$author_img = $salong['author_img']['url'];

/*文章类型*/
$post_type    = get_post_type();
$type_object  = get_post_type_object( $post_type );
$type_name    = $type_object->labels->singular_name;

?>

<section class="sidebar_widget widget_post_author">
    <section class="author_info">
        <div class="avatar" <?php if($author_img){ ?> style="background-image: url(
            <?php echo $author_img; ?>);
            <?php } ?>">
            <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="<?php echo $curauth_name; ?>" class="avatar">
                <?php echo salong_get_avatar($curauth_id,$curauth_name); ?>
            </a>
        </div>
        <h3>
            <?php echo $curauth_name.salong_add_v($curauth_id); ?>
        </h3>
        <?php if($salong['switch_follow_btn']){ echo salong_get_follow_unfollow_links($curauth_id); } ?>
        <?php echo wpautop(get_the_author_meta('description')); ?>
    </section>
    <section class="author_post">
        <div class="title">
            <h4>
                <?php echo sprintf(__('最新%s','salong'),$type_name); ?>
            </h4>
            <span><?php echo sprintf(__('共 %s 篇'),salong_author_post_count($curauth_id,$post_type)); ?></span>
        </div>
        <ul>
            <?php $args=array( 'author'=> $curauth_id,'post_type' => $post_type,'post_status' => 'publish','posts_per_page' => $salong['author_post_count'],'ignore_sticky_posts'=> 1);$my_query = null;$my_query = new WP_Query($args);if( $my_query->have_posts() ) : while ($my_query->have_posts()) : $my_query->the_post(); ?>
            <li>
                <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="imglayout">
                    <?php the_title(); ?>
                </a>
            </li>
            <?php endwhile;endif;wp_reset_query(); ?>
        </ul>
    </section>
    <section class="author_count">
        <ul>
            <li>
                <span><?php _e('关注','salong'); ?></span>
                <strong><?php echo salong_following_count($curauth_id); ?></strong>
            </li>
            <li>
                <span><?php _e('粉丝','salong'); ?></span>
                <strong><?php echo salong_follower_count($curauth_id); ?></strong>
            </li>
            <li>
                <span><?php _e('点赞','salong'); ?></span>
                <strong><?php echo salong_all_post_field_count($curauth_id,'salong_post_like_count'); ?></strong>
            </li>
            <li>
                <span><?php _e('浏览','salong'); ?></span>
                <strong><?php echo salong_all_post_field_count($curauth_id,'views'); ?></strong>
            </li>
        </ul>
    </section>
</section>
