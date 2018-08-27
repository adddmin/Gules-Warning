<?php get_header();
global $salong,$wp_query;
$curauth     = $wp_query->get_queried_object();//当前用户
$user_id     = $curauth->ID;//当前用户 ID
$user_name   = $curauth->display_name;
$user_url    = $curauth->user_url;
$description = $curauth->description;
$get_tab     = $_GET['tab'];//获取连接中 tab 后面的参数

$author_bg   = $salong['author_bg']['url'];
$opacity     = $salong['author_bg_rgba']['alpha'];//作者页面 Banner

?>
<main class="container">
    <!--作者头部-->
    <section class="author_banner" style="background-image: url(<?php echo $author_bg; ?>);">
        <header class="author_header">
            <a href="<?php if($user_url){ echo $user_url;} ?>" class="avatar" target="_blank" rel="external nofollow" title="<?php _e('访问我的站点！','salong'); ?>">
                <?php echo salong_get_avatar($user_id,$user_name); ?>
            </a>
            <h1>
                <?php echo $user_name.salong_add_v($user_id); ?>
            </h1>

            <div class="desc">
                <p>
                    <?php if($description){ echo $description; }else{ echo __('<p>这家伙真懒，个人简介没有填写…</p>','salong'); } ?>
                </p>
            </div>
            <?php if($salong['switch_follow_btn']){ echo salong_get_follow_unfollow_links($user_id); } ?>
        </header>
        <div class="bg" style="opacity:<?php echo $opacity; ?>"></div>
    </section>
    <section class="wrapper">
        <!--作者 tabs-->
        <?php if(!wp_is_mobile()){ ?>
        <section class="author_tabs" id="scroll">
            <section class="nav_move">
                <section id="move">
                    <ul id="nav" class="tabs">
                        <?php echo salong_user_menu($user_id); ?>
                    </ul>
                </section>
            </section>
            <?php } ?>
            <section class="author_content">
            <?php if($get_tab == 'like' || $get_tab == 'like-topic' || $get_tab == 'like-download' || $get_tab == 'like-video' || $get_tab == 'like-product'){
                get_template_part( 'content/author', 'like');
            }else if($get_tab == 'message' || $get_tab == 'message-inbox' || $get_tab == 'message-outbox'){
                get_template_part( 'content/author', 'message');
            }else if($get_tab == 'edit-profile' || $get_tab == 'edit-profile-extension' || $get_tab == 'edit-profile-password'){
                get_template_part( 'content/author', 'profile');
            }else if($get_tab == 'topic' || $get_tab == 'download' || $get_tab == 'video'){
                get_template_part( 'content/author', 'post-custom');
            }else if( $get_tab ){
                get_template_part( 'content/author', $get_tab);
            }else{
                get_template_part( 'content/author', 'index');
            } ?>
            </section>
            <?php if(!wp_is_mobile()){ ?>
        </section>
        <?php } ?>
    </section>
</main>
<?php get_footer(); ?>
