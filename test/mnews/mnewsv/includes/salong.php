<?php

//投稿简码////////////////////////////////////////////////////
function salong_contribute_post($atts){
    global $salong,$current_user,$post,$wp_query,$wpdb;
    $user_id      = $current_user->ID;//当前用户 ID
    $user_name    = $current_user->display_name;
    $user_email   = $current_user->user_email;
    $user_url     = $current_user->user_url;
    $current_url  = get_author_posts_url($user_id).'?tab=contribute';//当前页面链接
    
    extract(
        shortcode_atts(
            array(
                "post_type" => 'post'
            ),
            $atts
        )
    );
    
    $post_name      = __('文章','salong');
    $taxonomy_name  = 'category';
    $tg_max         = $salong['post_tg_max'];
    $tg_min         = $salong['post_tg_min'];
    
    // 投稿权限
    $contribute_access = $salong['contribute_access'];
    if (current_user_can( $contribute_access )) {

        if( isset($_POST['tougao_form']) && $_POST['tougao_form'] == 'send') {

            // 表单变量初始化
            $thumb      = isset( $_POST['tougao_thumb'] ) ? trim(htmlspecialchars($_POST['tougao_thumb'], ENT_QUOTES)) : '';
            $name       = isset( $_POST['tougao_authorname'] ) ? trim(htmlspecialchars($_POST['tougao_authorname'], ENT_QUOTES)) : '';
            $email      = isset( $_POST['tougao_authoremail'] ) ? trim(htmlspecialchars($_POST['tougao_authoremail'], ENT_QUOTES)) : '';
            $blog       = isset( $_POST['tougao_authorblog'] ) ? trim(htmlspecialchars($_POST['tougao_authorblog'], ENT_QUOTES)) : '';
            $from_name  = isset( $_POST['tougao_from_name'] ) ? trim(htmlspecialchars($_POST['tougao_from_name'], ENT_QUOTES)) : '';
            $from_link  = isset( $_POST['tougao_from_link'] ) ? trim(htmlspecialchars($_POST['tougao_from_link'], ENT_QUOTES)) : '';
            $title      = isset( $_POST['tougao_title'] ) ? trim(htmlspecialchars($_POST['tougao_title'], ENT_QUOTES)) : '';
            $category   = isset( $_POST['term_id'] ) ? (int)$_POST['term_id'] : 0;
            $content    = isset( $_POST['tougao_content'] ) ? $_POST['tougao_content'] : '';
            $status     = isset( $_POST['post_status'] ) ? $_POST['post_status'] : '';

            $last_post  = $wpdb->get_var("SELECT `post_date` FROM `$wpdb->posts` ORDER BY `post_date` DESC LIMIT 1");

            $post_content = '昵称:'.$name.'<br />Email:'.$email.'<br />博客:'.$blog.'<br />内容:<br />'.$content;

            $tougao = array(
                'post_title'    => $title,
                'post_content'  => $post_content,
                'post_author'   => $user_id,
                'post_type'     => $post_type,
                'ping_status'   => 'closed',
                'post_status'   => $status,
                'post_category' => array($category)
            );

            if ( (date_i18n('U') - strtotime($last_post)) < $salong['tg_time'] ) {
                echo '<span class="warningbox">'.__('您投稿也太勤快了吧，先歇会儿！','salong').'</span>';
            }else if ( empty($name) || mb_strlen($name) > 20 ) {
                echo '<span class="warningbox">'.sprintf(__('昵称必须填写，且长度不得超过20字，重新输入或者<a href="%s">点击刷新</a>','salong'),$current_url).'</span>';
            }else if ( empty($email) || strlen($email) > 60 || !preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
                echo '<span class="warningbox">'.sprintf(__('Email必须填写，且长度不得超过60字，必须符合Email格式，重新输入或者<a href="%s">点击刷新</a>','salong'),$current_url).'</span>';
            }else if ( empty($title) || mb_strlen($title) > 100 ) {
                echo '<span class="warningbox">'.sprintf(__('标题必须填写，且长度不得超过100字，重新输入或者<a href="%s">点击刷新</a>','salong'),$current_url).'</span>';
            }else if ( empty($content)) {
                echo '<span class="warningbox">'.sprintf(__('内容必须填写，重新输入或者<a href="%s">点击刷新</a>','salong'),$current_url).'</span>';
            }else if ( mb_strlen($content) > $tg_max ) {
                echo '<span class="warningbox">'.sprintf(__('内容长度不得超过%s字，重新输入或者<a href="%s">点击刷新</a>','salong'),$tg_max,$current_url).'</span>';
            }else if ( mb_strlen($content) < $tg_min) {
                echo '<span class="warningbox">'.sprintf(__('内容长度不得少于%s字，重新输入或者<a href="%s">点击刷新</a>','salong'),$tg_min,$current_url).'</span>';
            }else if ( $_POST['are_you_human'] == '' ) {
                echo '<span class="warningbox">'.sprintf(__('请输入本站名称：%s','salong'),get_option('blogname')).'</span>';
            }else if ( $_POST['are_you_human'] !== get_bloginfo( 'name' ) ) {
                echo '<span class="warningbox">'.sprintf(__('本站名称输入错误，正确名称为：%s','salong'),get_option('blogname')).'</span>';
            }else if ($tougao != 0) {

                // 将文章插入数据库
                $posts = wp_insert_post( $tougao );
                
                //添加自定义分类
                wp_set_object_terms( $posts, $category, $taxonomy_name);
                
                // 投稿成功给博主发送邮件
                if($status == 'pending'){
                    $email_content = $salong['contribute_email_pending'];
                }else{
                    $email_content = $salong['contribute_email_draft'];
                }
                wp_mail(get_option('admin_email'),get_option('blogname').__('用户投稿','salong'),$email_content);

                // 其中 salong_tougao_email 是自定义栏目的名称
                add_post_meta($posts, 'salong_tougao_email', $email, TRUE);
                /*添加缩略图*/
                if($thumb){
                    add_post_meta($posts, 'thumb', $thumb, TRUE);
                }
                if($from_name){
                    add_post_meta($posts, 'from_name', $from_name, TRUE);
                }
                if($from_link){
                    add_post_meta($posts, 'from_link', $from_link, TRUE);
                }

                echo '<span class="successbox">'.__('投稿成功！感谢投稿！','salong').'</span>';
            }else {
                echo '<span class="errorbox">'.__('投稿失败!','salong').'</span>';
            }
        }

        echo '<form class="contribute_form" method="post" action="'.$current_url.'">';
        echo '<p><label for="tougao_title"><b class="required">*</b>'.__('文章标题','salong').'</label><input type="text" value="" id="tougao_title" name="tougao_title" placeholder="'.__('请输入文章标题','salong').'" required /><span>'.sprintf(__('标题长度不得超过%s字。','salong'),100).'</span></p>';
        echo '<p><label for="tougao_category"><b class="required">*</b>'.__('文章分类','salong').'</label>';
        //投稿分类
        $contribute_cat_arr = $salong[ 'contribute_cat'];
        if($contribute_cat_arr){
            $contribute_cat = implode(',',$salong[ 'contribute_cat']);
        }else{
            $contribute_cat = '';
        }
        wp_dropdown_categories('include='.$contribute_cat.'&hide_empty=0&id=tougao_category&show_count=1&hierarchical=1&taxonomy='.$taxonomy_name.'&name=term_id&id=term_id');
        echo '</p>';
        echo '<p>'.wp_editor(  wpautop($post_content), 'tougao_content', array('media_buttons'=>true, 'quicktags'=>true, 'editor_class'=>'form-control' ) ).'<span>'.sprintf(__('内容必须填写，且长度不得超过 %s 字，不得少于 %s 字。','salong'),$tg_max,$tg_min).'</span></p>';
        if (current_user_can( 'edit_posts' ) || $salong['switch_contributor_uploads']) {
            echo '<div class="salong_field_main"><label for="tougao_thumb">'.__('缩略图','salong').'</label><div class="salong_field_area"><div class="salong_file_button"><a href="#" class="salong_upload_button"><b>+</b><span>'.__('上传封面','salong').'</span></a><div class="salong_file_preview"></div><div class="bg"></div><input class="salong_field_upload" type="hidden" value="" id="tougao_thumb" name="tougao_thumb" /></div><div class="salong_file_hint"><p>'.__('自定义缩略图，建议比例：460*280。','salong').'</p><span>'.__('支持≤3MB，JPG，JEPG，PNG格式文件','salong').'</span></div></div></div><hr>';
        }
        echo '<p><label for="tougao_authorname"><b class="required">*</b>'.__('昵称','salong').'</label><input type="text" value="'.$user_name.'" id="tougao_authorname" name="tougao_authorname" placeholder="'.__('请输入昵称','salong').'" required /></p>';
        echo '<p><label for="tougao_authoremail"><b class="required">*</b>'.__('邮箱','salong').'</label><input type="text" value="'.$user_email.'" id="tougao_authoremail" name="tougao_authoremail" placeholder="'.__('请输入邮箱','salong').'" required /></p>';
        echo '<p><label for="tougao_authorblog">'.__('博客','salong').'</label><input type="text" value="'.$user_url.'" id="tougao_authorblog" name="tougao_authorblog" placeholder="'.__('请输入博客','salong').'" /></p><hr>';
        echo '<p><label for="tougao_from_name">'.__('文章来源网站名称','salong').'</label><input type="text" value="" id="tougao_from_name" name="tougao_from_name" /></p>';
        echo '<p><label for="tougao_from_link">'.__('文章来源网站链接','salong').'</label><input type="text" value="" id="tougao_from_link" name="tougao_from_link" /></p><hr>';
        echo '<p><label for="are_you_human"><b class="required">*</b>'.sprintf(__('本站名称（请输入：%s）','salong'),get_option('blogname')).'<br/><input id="are_you_human" class="input" type="text" value="" name="are_you_human" required /></label></p>';
        echo '<p class="hint">'.$salong['contribute_info'].'</p><hr>';
        echo '<div class="status_btn">';
        echo '<select name="post_status"><option value="pending">'.__('提交审核','salong').'</option><option value="draft">'.__('保存草稿','um').'</option></select>';
        echo '<p><input type="hidden" value="send" name="tougao_form" /><input type="submit" value="'.__('提交','salong').'" class="submit" /><input type="reset" value="'.__('重填','salong').'" class="reset" /></p>';
        echo '</div>';
        echo '</form>';
    }else{
        echo '<div class="infobox">'.$salong['contribute_access_info'].'</div>';
    }
}
add_shortcode('contribute_post','salong_contribute_post');


//投稿发布后给投稿发送邮件
if($salong['switch_tougao_notify']){
    function salong_tougao_notify($mypost) {
        $email = get_post_meta($mypost->ID, "salong_tougao_email", true);

        if( !empty($email) ) {
            // 以下是邮件标题
            $subject = sprintf(__('您在 %s 的投稿已发布','salong'),get_option('blogname'));
            // 以下是邮件内容
            $message = sprintf(__('<p><strong> %s </strong> 提醒您: 您投递的文章 <strong> %s </strong> 已发布</p><p>您可以点击以下链接查看具体内容:<br /><a href="%s">点此查看完整內容</a></p><p>===================================================================</p><p><strong>感谢您对 <a href="%s" target="_blank">%s</a> 的关注和支持</strong></p><p><strong>该信件由系统自动发出, 请勿回复, 谢谢.</strong></p>','salong'),get_option('blogname'),$mypost->post_title,get_permalink( $mypost->ID ),get_home_url(),get_option('blogname'));

            add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
            @wp_mail( $email, $subject, $message );
        }
    }
    // 当投稿的文章从草稿状态变更到已发布时，给投稿者发提醒邮件
    add_action('pending_to_publish', 'salong_tougao_notify', 6);
}


//用户菜单
function salong_user_menu($curauth_id){

    global $salong,$wp_query,$current_user,$wpdb;
    $get_tab        = $_GET['tab'];//获取连接中 tab 后面的参数
    $current_id     = $current_user->ID; //登录用户
    
    $comments_count = get_comments( array('status' => '1', 'user_id'=>$curauth_id, 'count' => true) );//当前用户评论数量
    if($salong['switch_follow_btn']){
        $following      = salong_get_following_count($curauth_id);//关注的用户数量
        $follower       = salong_get_follower_count($curauth_id);//粉丝的用户数量
    }
    
    // 自己
    $oneself = $current_id==$curauth_id;
    
	// 获取未读消息的数量。
	$num_unread = (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'pm WHERE `recipient` = "' . $curauth_id . '" AND `read` = 0 AND `deleted` != "2"' );
    
    $message_total = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'pm WHERE `sender` = "' . $curauth_id . '" OR `recipient` = "' . $curauth_id . '"' );

    ?>
    <li<?php if( is_author() && $get_tab=='' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>">
           <?php echo svg_user(); ?>
            <h4><?php _e('资料','salong'); ?></h4>
        </a>
    </li>
    <li<?php if( $get_tab=='post' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=post">
            <?php echo svg_post(); ?>
            <h4><?php _e('文章','salong'); ?></h4>
            <?php if(salong_author_post_count($curauth_id,'post')){ ?>
            <span class="count">（<?php echo salong_author_post_count($curauth_id,'post'); ?>）</span>
            <?php } ?>
        </a>
    </li>
    <?php if(salong_author_post_count($curauth_id,'topic') && $salong[ 'switch_topic_type']){ ?>
    <li<?php if( $get_tab=='topic' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=topic">
            <?php echo svg_topic(); ?>
            <h4><?php _e('专题','salong'); ?></h4>
            <span class="count">（<?php echo salong_author_post_count($curauth_id,'topic'); ?>）</span>
        </a>
    </li>
    <?php } if(salong_author_post_count($curauth_id,'download') && $salong[ 'switch_download_type']){ ?>
    <li<?php if( $get_tab=='download' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=download">
            <?php echo svg_download(); ?>
            <h4><?php _e('下载','salong'); ?></h4>
            <span class="count">（<?php echo salong_author_post_count($curauth_id,'download'); ?>）</span>
        </a>
    </li>
    <?php } if(salong_author_post_count($curauth_id,'video') && $salong[ 'switch_video_type']){ ?>
    <li<?php if( $get_tab=='video' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=video">
            <?php echo svg_video(); ?>
            <h4><?php _e('视频','salong'); ?></h4>
            <span class="count">（<?php echo salong_author_post_count($curauth_id,'video'); ?>）</span>
        </a>
    </li>
    <?php }?>
    <li<?php if( $get_tab=='like' || $get_tab=='like-topic' || $get_tab=='like-download' || $get_tab=='like-video' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=like">
            <?php echo svg_like(); ?>
            <h4><?php _e('收藏','salong'); ?></h4>
            <?php if(salong_author_post_like_count('any',$curauth_id)){ ?>
            <span class="count">（<?php echo salong_author_post_like_count('any',$curauth_id); ?>）</span>
            <?php } ?>
        </a>
    </li>
    <li<?php if( $get_tab=='comment' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=comment">
            <?php echo svg_comment(); ?>
            <h4><?php _e('评论','salong'); ?></h4>
            <?php if($comments_count){ ?>
            <span class="count">（<?php echo $comments_count; ?>）</span>
            <?php } ?>
        </a>
    </li>
    <?php if($salong['switch_follow_btn']){ ?>
    <li<?php if( $get_tab=='following' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=following">
            <?php echo svg_following(); ?>
            <h4><?php _e('关注','salong'); ?></h4>
            <?php if($following){ ?>
            <span class="count">（<?php echo salong_following_count($curauth_id); ?>）</span>
            <?php } ?>
        </a>
    </li>
    <li<?php if( $get_tab=='follower' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=follower">
            <?php echo svg_follower(); ?>
            <h4><?php _e('粉丝','salong'); ?></h4>
            <?php if($follower){ ?>
            <span class="count">（<?php echo salong_follower_count($curauth_id); ?>）</span>
            <?php } ?>
        </a>
    </li>
    <?php } if( ( (is_user_logged_in() && $oneself ) || salong_is_administrator() ) && $salong['switch_messages'] ){ ?>
    <li class="message<?php if( $get_tab=='message' || $get_tab=='message-inbox' || $get_tab=='message-outbox' ){ echo ' current'; } ?>">
        <a href="<?php echo add_query_arg('tab', 'message', get_author_posts_url($curauth_id)); ?>"<?php if ( $num_unread ){ ?> title="<?php echo sprintf( __( '您有 %s 条新信息！', 'salong' ), $num_unread ); ?>"<?php } ?>>
            <?php echo svg_message(); ?>
            <h4><?php _e('私信','salong'); ?></h4>
            <?php if($message_total){ ?>
            <span class="count">（<?php echo $message_total; ?>）</span>
            <?php } ?>
            <!--未读提示-->
            <?php if ( $num_unread ){ echo '<b></b>'; } ?>
        </a>
    </li>
    <?php } if(is_user_logged_in() && $oneself){ ?>
    <li<?php if( $get_tab=='contribute' || $get_tab=='contribute-post' || $get_tab=='contribute-download' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($current_id); ?>?tab=contribute">
            <?php echo svg_contribute(); ?>
            <h4><?php _e('投稿','salong'); ?></h4>
        </a>
    </li>
    <li<?php if( $get_tab=='edit-profile' || $get_tab == 'edit-profile-extension' || $get_tab == 'edit-profile-password' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($current_id); ?>?tab=edit-profile">
            <?php echo svg_profile(); ?>
            <h4><?php _e('编辑','salong'); ?></h4>
        </a>
    </li>
    <li>
        <a href="<?php echo wp_logout_url($_SERVER['REQUEST_URI']); ?>">
            <?php echo svg_logout(); ?>
            <h4><?php _e('退出','salong'); ?></h4>
        </a>
    </li>
    <?php }
}

//友情链接
function salong_link_page(){
    global $salong;
    $linkcatorderby     = $salong[ 'link_category_orderby'];
    $linkcatorder       = $salong[ 'link_category_order'];
    if($salong[ 'exclude_link_category']) {
        $linkcatexclude = implode( ',',$salong[ 'exclude_link_category']);
    }
    $linkorderby        = $salong[ 'link_orderby'];
    $linkorder          = $salong[ 'link_order'];
    $linkexclude        = $salong[ 'exclude_link'];

?>
    <section id="link-page">
        <ul>
            <?php wp_list_bookmarks( 'orderby=rand&show_images=1&category_orderby='.$linkcatorderby. '&category_order='.$linkcatorder. '&exclude_category='.$linkcatexclude. '&orderby='.$linkorderby. '&order='.$linkorder. '&exclude='.$linkexclude. '&show_description=1&link_before=<span>&link_after=</span>'); ?>
        </ul>
        <?php if($salong[ 'switch_link_icon']){ ?>
        <script>
            $("#link-page a").each(function(e) {
                $(this).prepend("<img src=https://f.ydr.me/" + this.href.replace(/^(http:\/\/[^\/]+).*$/, '$1') + ">");
            });
        </script>
        <?php } ?>
    </section>
<?php }
add_shortcode('link','salong_link_page');



//客户留言
function salong_message_page(){
    global $salong,$wpdb;
    $excludeemail = $salong['exclude_email']; $messagecount = $salong['message_count'];
    $query="SELECT COUNT(comment_ID) AS cnt, comment_author, comment_author_url,user_id, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE comment_date > date_sub( NOW(), INTERVAL 24 MONTH ) AND comment_author_email != '$excludeemail' AND post_password='' AND comment_approved='1' AND comment_type='') AS tempcmt GROUP BY comment_author_email ORDER BY cnt DESC LIMIT $messagecount"; 
    $wall = $wpdb->get_results($query);
    $maxNum = $wall[0]->cnt;?>
    <ul class="readers-list layout_ul">
        <?php
        foreach ($wall as $comment){
            if($comment->user_id){
                $url = get_author_posts_url($comment->user_id);
            }else if( $comment->comment_author_url ){
                if ($salong['switch_link_go']) {
                    $url = commentauthor();
                } else {
                    $url = comment_author_link();
                }
            }else{
                $url = '#';
            }
            $r="rel='external nofollow'";
            ?>
            <li class="layout_li"><a target="_blank" href="<?php echo $url; ?>" <?php echo $r; ?> title="<?php _e('查看TA的站点','salong'); ?>">
            <?php $user_id = $comment->user_id; $user_name = $comment->comment_author; echo salong_get_avatar($user_id,$user_name).$user_name; ?>&nbsp;+&nbsp;<?php echo $comment->cnt;?>
            </a>
        </li>
        <?php } ?>
    </ul>
<?php }
add_shortcode('message','salong_message_page');


//标签云
function salong_tag_page(){
    global $salong,$wpdb;
    $taxonomies = array('post_tag','ttag','dtag','vtag'); 
    foreach ( $taxonomies as $taxonomy ) {
        $tag_name = get_taxonomy($taxonomy);
    ?>

    <?php $tag_args=array( 'order'=> 'DESC', 'taxonomy' => $taxonomy, 'orderby' => 'count', 'number' => $sitemap_tag_count ); $tag_tags_list = get_terms($tag_args); if ($tag_tags_list) { ?>
    <!--文章标签-->
    <section class="tags">
        <h3>
            <?php if($taxonomy == 'post_tag'){echo __('文章'); } echo $tag_name->labels->singular_name; ?>
        </h3>
        <section class="tag_could">
            <?php foreach($tag_tags_list as $tag) { ?>
            <a href="<?php echo get_tag_link($tag); ?>" title="<?php printf( __( '标签 %s 下有 %s 篇文章' , 'salong' ), esc_attr($tag->name), esc_attr($tag->count) ); ?>" target="_blank">
                <span><?php echo $tag->name; ?></span><b>(<?php echo $tag->count; ?>)</b></a>
            <?php } ?>
        </section>
    </section>
    <hr>
    <?php } ?>
    <?php } ?>
<?php }
add_shortcode('tag','salong_tag_page');

//置顶文章
function salong_sticky_like($atts){
    global $salong,$post,$wp_query;
    extract(
        shortcode_atts(
            array(
                'post_state' => 'sticky'
            ),
            $atts
        )
    );
    if(trim($post_state,'&quot;') == 'like'){
        $post_in  = '';
        $orderby  = 'meta_value_num';
        $meta_key = 'salong_post_like_count';
    }else{
        $post_in  = get_option('sticky_posts');
        $orderby  = '';
        $meta_key = '';
    }
    ?>
    <section class="sticky_like">
        <ul class="ajaxposts layout_ul">
            <?php $paged=( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;$args=array( 'post_type'=> 'post','ignore_sticky_posts' => 1,'paged' => $paged,'post__in'=> $post_in,'meta_key'=>$meta_key,'orderby'=>$orderby );$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();?>
            <li class="ajaxpost layout_li">
                <article class="sl_main">
                    <a href="<?php the_permalink() ?>" class="imgeffect" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>>
                        <?php post_thumbnail(); ?>
                        <h4><?php the_title(); ?></h4>
                        <?php if(trim($post_state,'&quot;') == 'like'){ ?>
                        <span class="count">
                            <?php echo get_post_meta( $post->ID, "salong_post_like_count", true ); ?>
                        </span>
                        <?php } ?>
                    </a>
                </article>
            </li>
            <?php endwhile; posts_pagination(); else: ?>
            <p class="warningbox">
                <?php _e( '非常抱歉，没有置顶文章。', 'salong'); ?>
            </p>
            <?php endif;wp_reset_query(); ?>
        </ul>
    </section>
    <?php }
add_shortcode('sticky_like','salong_sticky_like');

//分类列表
function salong_home_cat($post_type,$taxonomy){
    global $salong,$post;
    $cat_list   = $salong['home_cat_'.$post_type];
    if(empty($cat_list))
        return;
    $cat_count  = $salong['home_cat_count_'.$post_type];
    if($post_type == 'post'){
        $list_name  = 'grid';
        $class      = 'grid_post';
    }else{
        $list_name  = 'list';
        $class      = $post_type.'_list';
    }
    ?>

    <!--分类列表-->
    <?php foreach($cat_list as $cat){ $get_category = get_terms(array('include'=>$cat,'taxonomy'=>$taxonomy)); $cat_desc = $get_category[0]->description; ?>
    <section class="<?php echo $class; ?>">
        <!--标题-->
        <section class="home_title">
            <section class="title">
                <h3>
                    <?php echo $get_category[0]->name;?>
                </h3>
                <?php if($cat_desc && $salong['switch_home_cat_'.$post_type]){ ?>
                <span><?php echo $cat_desc; ?></span>
                <?php } ?>
            </section>
            <section class="button">
                <a href="<?php echo get_term_link( (int)$cat, $taxonomy );?>" title="<?php _e( '查看更多', 'salong' ); ?>" <?php echo new_open_link(); ?>><?php echo _e('更多','salong').svg_more(); ?></a>
            </section>
        </section>
        <!--标题end-->
        <ul class="layout_ul">
            <?php $args=array( 'post_type'=> $post_type,'posts_per_page' => $cat_count,'ignore_sticky_posts' => 1,'tax_query' => array( array( 'taxonomy' => $taxonomy, 'field' => 'id', 'terms' => $cat )));$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();?>
            <?php if($post_type == 'product'){ wc_get_template_part( 'content', 'product' ); }else{ ?>
            <li class="layout_li">
                <?php get_template_part( 'content/'.$list_name, $post_type); ?>
            </li>
            <?php } ?>
            <?php endwhile;endif; ?>
        </ul>
    </section>
    <?php }
}

//百度主动推送
function Baidu_Submit($post_ID) {
    global $salong,$post;
    $WEB_TOKEN  = $salong['web_token'];  //这里换成你的网站的百度主动推送的token值
    $WEB_DOMAIN = get_option('home');
    //已成功推送的文章不再推送
    if(get_post_meta($post_ID,'baidusubmit',true) == 1 && $salong['switch_baidu_submit']) return;
    $url = get_permalink($post_ID);
    $api = 'http://data.zz.baidu.com/urls?site='.$WEB_DOMAIN.'&token='.$WEB_TOKEN;
    $ch  = curl_init();
    $options =  array(
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $url,
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
    );
    curl_setopt_array($ch, $options);
    $result = json_decode(curl_exec($ch),true);
    //如果推送成功则在文章新增自定义栏目baidusubmit，值为1
    if (array_key_exists('success',$result)) {
        update_post_meta($post_ID, 'baidusubmit', 1, true);
    }
}

add_action('init', 'salong_type_baidu_submit', 100);
function salong_type_baidu_submit() {
    global $salong;
    // 自定义文章类型
    $bd_type = $salong['baidu_post_type'];
	if ( is_array($bd_type) ) {
		foreach($bd_type as $type) {
            add_action('publish_'.$type, 'Baidu_Submit', 0);
		}
	} 
}

//输出缩略图地址（熊掌号）
function post_thumbnail_src(){
    global $post,$salong;
    if( $values = get_post_custom_values("thumb") ) {   //输出自定义域图片地址
        $values = get_post_custom_values("thumb");
        $post_thumbnail_src = $values[0];
    } elseif( has_post_thumbnail() ){    //如果有特色缩略图，则输出缩略图地址
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $post_thumbnail_src = $thumbnail_src [0];
    } else {
        $post_thumbnail_src = '';
        ob_start();
        ob_end_clean();
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        if(!empty($matches[1][0])){
            $post_thumbnail_src = $matches[1][0];
        }else{  
            //如果日志中没有图片，则显示默认图片
            $post_thumbnail_src = $salong['default_thumb']['url'];
        }
    }
    return $post_thumbnail_src;
}

//插入熊掌号代码到文章类型
function salong_insert_xz($content){
    global $salong,$post;
    $index = '';
    $type = get_post_type();
    $xz_type = $salong['xiongzhang_post_type'];
    if( is_array($xz_type) && in_array($type,$xz_type) && $salong['switch_xiongzhang']){
        $index = "<script>cambrian.render('tail')</script>";
        $content = $content.$index;
    }
    return $content;
}
add_filter('the_content', 'salong_insert_xz');


/***************************************************************************************
安全
***************************************************************************************/
// 禁止全英文和日文评论
function BYMT_comment_post( $incoming_comment ) {
    $pattern = '/[一-龥]/u';
    $jpattern ='/[ぁ-ん]+|[ァ-ヴ]+/u';
    if(!preg_match($pattern, $incoming_comment['comment_content'])) {
        err( "写点汉字吧，博主英文过了四级，但还是不认识英文！Please write some chinese words！" );
    }
    if(preg_match($jpattern, $incoming_comment['comment_content'])){
        err( "日文滚粗！Japanese Get out！日本語出て行け！" );
    }
    return( $incoming_comment );
}
add_filter('preprocess_comment', 'BYMT_comment_post');

// 针对特定字符留言直接屏蔽
function in_comment_post_like($string, $array) {
    foreach($array as $ref) { if(strstr($string, $ref)) { return true; } }
    return false;
}
function drop_bad_comments() {
    if (!empty($_POST['comment'])) {
        global $salong;
        $bad_comments         = $salong['bad_comments'];
        $bad_comments_arr     = explode(PHP_EOL,$bad_comments);
        $post_comment_content = $_POST['comment'];
        $lower_case_comment   = strtolower($_POST['comment']);
        $bad_comment_content  = $bad_comments_arr;
        if (in_comment_post_like($lower_case_comment, $bad_comment_content)) {
            $comment_box_text = wordwrap(trim($post_comment_content), 80, "\n  ", true);
            $txtdrop = fopen('/var/log/httpd/wp_post-logger/nullamatix.com-text-area_dropped.txt', 'a');
            fwrite($txtdrop, "  --------------\n  [COMMENT] = " . $post_comment_content . "\n  --------------\n");
            fwrite($txtdrop, "  [SOURCE_IP] = " . $_SERVER['REMOTE_ADDR'] . " @ " . date("F j, Y, g:i a") . "\n");
            fwrite($txtdrop, "  [USERAGENT] = " . $_SERVER['HTTP_USER_AGENT'] . "\n");
            fwrite($txtdrop, "  [REFERER  ] = " . $_SERVER['HTTP_REFERER'] . "\n");
            fwrite($txtdrop, "  [FILE_NAME] = " . $_SERVER['SCRIPT_NAME'] . " - [REQ_URI] = " . $_SERVER['REQUEST_URI'] . "\n");
            fwrite($txtdrop, '--------------**********------------------'."\n");
            header("HTTP/1.1 406 Not Acceptable");
            header("Status: 406 Not Acceptable");
            header("Connection: Close");
            wp_die( '<p class="bad">'.$salong['bad_comment_text'].'</p>' );
        }
    }
}
add_action('init', 'drop_bad_comments');

//保护后台登录
if($salong['switch_admin_link']){
    add_action('login_enqueue_scripts','login_protection');
    function login_protection(){
        global $salong;
        if($_GET[''.$salong['admin_word'].''] != ''.$salong['admin_press'].'')header('Location: '.get_home_url().'');
    }
}

//禁止冒充管理员评论
if($salong['switch_incoming_comment']){
    function salong_usecheck($incoming_comment) {
        $isSpam = 0;
        global $salong;
        if (trim($incoming_comment['comment_author_email']) == ''.$salong['admin_email'].'')
            $isSpam = 1;
        if(!$isSpam)
            return $incoming_comment;
        wp_die(__('<p class="warningbox">请勿冒充博主发表评论</p>','salong'));
    }
    if(!is_user_logged_in())
        add_filter( 'preprocess_comment', 'salong_usecheck' );
}

// 网站维护
if ($salong['switch_weihu']) {
    function wp_maintenance_mode(){
        if(!current_user_can('edit_themes') || !is_user_logged_in()){
            wp_die(''.sprintf( __( '%s临时维护中，请稍后访问，给您带来的不便，敬请谅解！' , 'salong' ), esc_attr(get_option('blogname'))).'', ''.sprintf( __( '%s维护中' , 'salong' ), esc_attr(get_option('blogname'))).'', array('response' => '503'));
        }
    }
    add_action('get_header', 'wp_maintenance_mode');
}

//哪些权限的用户可以访问后台
function block_admin_access() {
    global $pagenow,$salong;

    if ( defined( 'WP_CLI' ) ) {
        return;
    }

    $access_level = $salong['admin_access'];
    $valid_pages  = array('admin-ajax.php', 'admin-post.php', 'async-upload.php', 'media-upload.php');

    if ( ! current_user_can( $access_level ) && !in_array( $pagenow, $valid_pages ) ) {
        wp_redirect(get_home_url());
        exit;
    }
}
add_action( 'admin_init', 'block_admin_access' );


//只对管理员显示工具栏
$access_level = $salong['admin_access'];
if($salong['switch_admin_bar']){
    if ( ! current_user_can( $access_level )) {
        add_filter('show_admin_bar', '__return_false');
    }
}else{
    add_filter('show_admin_bar', '__return_false');
}

if($salong['switch_author_id']){
    //去除作者和评论中的登录名
    function lxtx_remove_comment_body_author_class( $classes ) {
        foreach( $classes as $key => $class ) {
            if(strstr($class, "comment-author-")||strstr($class, "author-")) {
                unset( $classes[$key] );
            }
        }
        return $classes;
    }
    add_filter( 'comment_class' , 'lxtx_remove_comment_body_author_class' );
    add_filter('body_class', 'lxtx_remove_comment_body_author_class');

    //作者归档页面使用 ID
    add_filter( 'author_link', 'yundanran_author_link', 10, 2 );
    function yundanran_author_link( $link, $author_id) {
        global $wp_rewrite;
        $author_id = (int) $author_id;
        $link = $wp_rewrite->get_author_permastruct();

        if ( empty($link) ) {
            $file = home_url( '/' );
            $link = $file . '?author=' . $author_id;
        } else {
            $link = str_replace('%author%', $author_id, $link);
            $link = home_url( user_trailingslashit( $link ) );
        }

        return $link;
    }

    add_filter( 'request', 'yundanran_author_link_request' );
    function yundanran_author_link_request( $query_vars ) {
        if ( array_key_exists( 'author_name', $query_vars ) ) {
            global $wpdb;
            $author_id=$query_vars['author_name'];
            if ( $author_id ) {
                $query_vars['author'] = $author_id;
                unset( $query_vars['author_name'] );    
            }
        }
        return $query_vars;
    }
}

//页面重定向
function salong_center_page(){
    global $current_user,$salong,$pagenow,$wp_query;
    $current_id     = $current_user->ID;//登录用户 ID
    $current_email  = $current_user->user_email;
    $curauth        = $wp_query->get_queried_object();//当前用户
    $curauth_id     = $curauth->ID;//当前用户ID
    $scheme         = is_ssl() && !is_admin() ? 'https' : 'http';
    $current_url    = $scheme . '://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];//当前页面
    $author_url     = get_author_posts_url($current_id);//跳转到资料页面
    $profile_url    = get_author_posts_url($current_id).'?tab=edit-profile-password';//跳转到编辑资料页面
    $contribute_url = get_author_posts_url($current_id).'?tab=contribute';//跳转到投稿页面
    $get_tab        = $_GET['tab'];//获取连接中 tab 后面的参数
    $get_post_id    = $_GET['post_id'];//获取文章 ID
    $post           = get_post($get_post_id);
    $author_id      = $post->post_author;
    if (is_user_logged_in()){
        if (!$current_email && $current_url!=$profile_url && $salong['switch_user_add_email']){
            wp_redirect($profile_url);
            exit;
        }
        /*登录用户不是当前用户*/
        if ($curauth_id != $current_id){
            if ($get_tab == 'contribute'){
                wp_redirect($contribute_url);
                exit;
            }
            if ($get_tab == 'message'){
                wp_redirect($author_url.'?tab=message');
                exit;
            }
            if ($get_tab == 'message-inbox'){
                wp_redirect($author_url.'?tab=message-inbox');
                exit;
            }
            if ($get_tab == 'message-outbox'){
                wp_redirect($author_url.'?tab=message-outbox');
                exit;
            }
        }
        if ($get_tab == 'edit'){
            if ($author_id != $current_id || !$get_post_id || $curauth_id != $current_id){
                wp_redirect($author_url);
                exit;
            }
        }
    }else{
        if($get_tab == 'edit-profile' || $get_tab == 'edit-profile-extension' || $get_tab == 'edit-profile-password' || $get_tab == 'contribute' || $get_tab == 'edit' || $get_tab == 'message' || $get_tab == 'message-inbox' || $get_tab == 'message-outbox'){
            wp_redirect(home_url());
            exit;
        }
    }
}
add_action( 'wp', 'salong_center_page', 3 );
/***************************************************************************************
安全end
***************************************************************************************/


/***************************************************************************************
优化
***************************************************************************************/

// 压缩HTML代码
if($salong['switch_minify_html']){
    function salong_minify_html($html) {
        $search = array(
            '/\>[^\S ]+/s',  // 删除标签后面空格
            '/[^\S ]+\</s',  // 删除标签前面的空格
            '/(\s)+/s'       // 将多个空格合并成一个
        );
        $replace = array(
            '>',
            '<',
            '\\1'
        );
        $html = preg_replace($search, $replace, $html);
        return $html;
    }
    if(!is_admin()){
      add_action("wp_loaded", 'wp_loaded_minify_html');
      function wp_loaded_minify_html(){
        ob_start('salong_minify_html');
      }
    }
}

//格式化日期
if($salong['switch_filter_time']){
    function salong_filter_time(){
        global $post ;
        $to = time();
        $from = get_the_time('U') ;
        $diff = (int) abs($to - $from);
        if ($diff <= 3600) {
            $mins = round($diff / 60);
            if ($mins <= 1) {
                $mins = 1;
            }
            $time = sprintf(_n('%s 分钟', '%s 分钟', $mins), $mins) . __( '前' , 'salong' );
        }
        else if (($diff <= 86400) && ($diff > 3600)) {
            $hours = round($diff / 3600);
            if ($hours <= 1) {
                $hours = 1;
            }
            $time = sprintf(_n('%s 小时', '%s 小时', $hours), $hours) . __( '前' , 'salong' );
        }
        elseif ($diff >= 86400) {
            $days = round($diff / 86400);
            if ($days <= 1) {
                $days = 1;
                $time = sprintf(_n('%s 天', '%s 天', $days), $days) . __( '前' , 'salong' );
            }
            elseif( $days > 29){
                $time = get_the_time(get_option('date_format'));
            }
            else{
                $time = sprintf(_n('%s 天', '%s 天', $days), $days) . __( '前' , 'salong' );
            }
        }
        return $time;
    }
    add_filter('the_time','salong_filter_time');
}

//格式化数字
if($salong['switch_filter_count']){
    function salong_format_count( $number ) {
        $precision = 2;
        if ( $number >= 1000 && $number < 10000 ) {
            $formatted = number_format( $number/1000, $precision ).'K';
        } else if ( $number >= 10000 && $number < 1000000 ) {
            $formatted = number_format( $number/10000, $precision ).'W';
        } else if ( $number >= 1000000 && $number < 1000000000 ) {
            $formatted = number_format( $number/1000000, $precision ).'M';
        } else if ( $number >= 1000000000 ) {
            $formatted = number_format( $number/1000000000, $precision ).'B';
        } else {
            $formatted = $number; // Number is less than 1000
        }
        $formatted = str_replace( '.00', '', $formatted );
        return $formatted;
    }
}

//添加新窗口打开链接
function new_open_link(){
    global $salong;
    $output = '';
    if($salong['switch_new_open_link']){
        $output .= ' target="_blank"';
        return $output;
    }
}

if ($salong['switch_useradd_time']) {
    //后台显示注册用户时间
    class RRHE {
        // Register the column - Registered
        public static function registerdate($columns) {
            $columns['registerdate'] = __('注册时间', 'registerdate');
            return $columns;
        }

        // Display the column content
        public static function registerdate_columns( $value, $column_name, $user_id ) {
            if ( 'registerdate' != $column_name )
                return $value;
            $user = get_userdata( $user_id );
            $registerdate = get_date_from_gmt($user->user_registered);
            return $registerdate;
        }

        public static function registerdate_column_sortable($columns) {
            $custom = array(
              // meta column id => sortby value used in query
                'registerdate'    => 'registered',
                );
            return wp_parse_args($custom, $columns);
        }

        public static function registerdate_column_orderby( $vars ) {
            if ( isset( $vars['orderby'] ) && 'registerdate' == $vars['orderby'] ) {
                $vars = array_merge( $vars, array(
                    'meta_key' => 'registerdate',
                    'orderby' => 'meta_value'
                    ) );
            }
            return $vars;
        }

    }
    // Actions
    add_filter( 'manage_users_columns', array('RRHE','registerdate'));
    add_action( 'manage_users_custom_column',  array('RRHE','registerdate_columns'), 15, 3);
    add_filter( 'manage_users_sortable_columns', array('RRHE','registerdate_column_sortable') );
    add_filter( 'request', array('RRHE','registerdate_column_orderby') );
}

// 外链跳转
global $salong;
if ($salong['switch_link_go']) {
	add_filter('the_content','link_to_jump',999);
	function link_to_jump($content){
		preg_match_all('/<a(.*?)href="(.*?)"(.*?)>/',$content,$matches);
		if($matches){
		    foreach($matches[2] as $val){
			    if(strpos($val,'://')!==false && strpos($val,home_url())===false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i',$val) && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i',$val)){
			    	$content=str_replace("href=\"$val\"", "href=\"".get_page_link(get_page_id_from_template('template-go.php'))."?url=$val\" ",$content);
				}
			}
		}
		return $content;
	}

	// 评论者链接跳转并新窗口打开
	function commentauthor($comment_ID = 0) {
	    $url    = get_comment_author_url( $comment_ID );
	    $author = get_comment_author( $comment_ID );
	    if ( empty( $url ) || 'http://' == $url )
	    echo $author;
	    else
	    echo "<a href='".get_page_link(get_page_id_from_template('template-go.php'))."?url=$url' rel='external nofollow' target='_blank' class='url'>$author</a>";
	}
    
    //版权外链
	function external_link($url) {
	    if(strpos($url,'://')!==false && strpos($url,home_url())===false && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i',$url)) {
			$url = str_replace($url, get_page_link(get_page_id_from_template('template-go.php'))."?url=".$url,$url);
	     }
	     return $url;
	}

}

//找回上传设置
if($salong[ 'switch_upload_path']) {
    if(get_option('upload_path')=='wp-content/uploads'|| get_option('upload_path')==null){
        update_option('upload_path',WP_CONTENT_DIR.'/uploads');
    }
}

//重置系统时间为北京时间
if($salong[ 'switch_date_default']) {
    date_default_timezone_set("Asia/Shanghai");
}

// 去除分类category
if($salong[ 'remove_category_slug']){
    require_once get_template_directory() . '/includes/no-category.php';
}

//禁用RSS Feed防止rss采集
if($salong['switch_feed']){
    function salong_disable_feed() {
        wp_die(__('<h1>本博客不再提供 Feed，请访问网站<a href="'.get_bloginfo('url').'">首页</a>！</h1>'));
    }
    add_action('do_feed', 'salong_disable_feed', 1);
    add_action('do_feed_rdf', 'salong_disable_feed', 1);
    add_action('do_feed_rss', 'salong_disable_feed', 1);
    add_action('do_feed_rss2', 'salong_disable_feed', 1);
    add_action('do_feed_atom', 'salong_disable_feed', 1);
}

if($salong['switch_header_code']){
    // 移除头部冗余代码
    remove_action('wp_head', 'wp_generator'); //删除 head 中的 WP 版本号
    foreach (array('rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head') as $action) {
        remove_action($action, 'the_generator');
    }

    remove_action('wp_head', 'rsd_link'); //删除 head 中的 RSD LINK
    remove_action('wp_head', 'wlwmanifest_link'); //删除 head 中的 Windows Live Writer 的适配器？

    remove_action('wp_head', 'feed_links_extra', 3); //删除 head 中的 Feed 相关的link
    //remove_action( 'wp_head', 'feed_links', 2 );

    remove_action('wp_head', 'index_rel_link'); //删除 head 中首页，上级，开始，相连的日志链接
    remove_action('wp_head', 'parent_post_rel_link', 10);
    remove_action('wp_head', 'start_post_rel_link', 10);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);

    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0); //删除 head 中的 shortlink

    remove_action('wp_head', 'rest_output_link_wp_head', 10); // 删除头部输出 WP RSET API 地址

    remove_action('template_redirect', 'wp_shortlink_header', 11); //禁止短链接 Header 标签。
    remove_action('template_redirect', 'rest_output_link_header', 11); // 禁止输出 Header Link 标签。
}

if($salong['switch_capital_P_dangit']){
    //移除 WordPress 自动修正 WordPress 大小写函数
    remove_filter( 'the_content', 'capital_P_dangit' );
    remove_filter( 'the_title', 'capital_P_dangit' );
    remove_filter( 'comment_text', 'capital_P_dangit' );
}

if($salong['switch_shortcode_unautop']){
    //让 Shortcode 优先于 wpautop 执行
    remove_filter('the_content', 'wpautop');
    add_filter('the_content', 'wpautop', 12);
    remove_filter('the_content', 'shortcode_unautop');
    add_filter('the_content', 'shortcode_unautop', 13);
}

if($salong['switch_rest_api']){
    // 屏蔽 REST API
    remove_action( 'init',          'rest_api_init' );
    remove_action( 'rest_api_init', 'rest_api_default_filters', 10 );
    remove_action( 'parse_request', 'rest_api_loaded' );
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');
    // 移除头部 wp-json 标签和 HTTP header 中的 link 
    remove_action('wp_head', 'rest_output_link_wp_head', 10 );
    remove_action('template_redirect', 'rest_output_link_header', 11 );
}

if($salong['switch_wp_oembed']){
    //禁用 Auto Embeds 功能，Auto Embeds 基本不支持国内网站，禁用，加快页面解析速度。
    remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'run_shortcode' ), 8 );
    remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
    remove_action( 'pre_post_update', array( $GLOBALS['wp_embed'], 'delete_oembed_caches' ) );
    remove_action( 'edit_form_advanced', array( $GLOBALS['wp_embed'], 'maybe_run_ajax_cache' ) );
    
    //屏蔽文章 Embed 功能，添加带embed或视频链接到编辑器中，转不会被转换。
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );
    remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4 );
    add_filter( 'embed_oembed_discover', '__return_false' );
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    remove_filter( 'oembed_response_data',   'get_oembed_response_data_rich',  10, 4 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter( 'tiny_mce_plugins', 'salong_disable_post_embed_tiny_mce_plugin' );
    function salong_disable_post_embed_tiny_mce_plugin($plugins){
        return array_diff( $plugins, array( 'wpembed' ) );
    }
    add_filter('query_vars', 'salong_disable_post_embed_query_var');
    function salong_disable_post_embed_query_var($public_query_vars) {
        return array_diff($public_query_vars, array('embed'));
    }
}

if($salong['switch_dashboard_widgets']){
    //去除后台首页面板的功能
    add_action('wp_dashboard_setup', 'salong_remove_dashboard_widgets');
    function salong_remove_dashboard_widgets(){
        global $wp_meta_boxes;
        unset($wp_meta_boxes['dashboard']['normal']);
        unset($wp_meta_boxes['dashboard']['side']);
    }
}

if($salong['switch_staticize_emoji']){
    //禁止Emoji表情，提高网站加载速度
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');

    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    remove_action('embed_head', 'print_emoji_detection_script');

    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    add_filter('tiny_mce_plugins', 'wpjam_disable_emoji_tiny_mce_plugin');
    function wpjam_disable_emoji_tiny_mce_plugin($plugins)
    {
        return array_diff($plugins, array('wpemoji'));
    }

    add_filter('emoji_svg_url', '__return_false');
}

if($salong['switch_wp_cron']){
    //禁用 WP_CRON 文章定时发布功能，如果网站不需要定时发布功能可以禁用
    defined('DISABLE_WP_CRON');
    remove_action( 'init', 'wp_cron' );
}

if($salong['switch_xmlrpc_enabled']){
    //禁用 XML-RPC 接口，离线发布功能，无需通过 APP 客户端发布日志就禁用
    add_filter('xmlrpc_enabled', '__return_false');
}

//彻底关闭 pingback
if($salong['switch_pingback']){
    add_filter('xmlrpc_methods','salong_xmlrpc_methods');
    function salong_xmlrpc_methods($methods){
        $methods['pingback.ping'] = '__return_false';
        $methods['pingback.extensions.getPingbacks'] = '__return_false';
        return $methods;
    }
    //禁用 pingbacks, enclosures, trackbacks 
    remove_action( 'do_pings', 'do_all_pings', 10, 1 );
    //去掉 _encloseme 和 do_ping 操作。
    remove_action( 'publish_post','_publish_post_hook',5, 1 );
}

if($salong['switch_admin_color_schemes']){
    //移除后台管理界面配色方案
    remove_action( 'admin_init', 'register_admin_color_schemes', 1);
    remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
}

if($salong['switch_update_core']){
    //屏蔽后台功能提示，移除后台核心，插件和主题的更新提示
    add_filter ('pre_site_transient_update_core', '__return_null');
    remove_action ('load-update-core.php', 'wp_update_plugins');
    add_filter ('pre_site_transient_update_plugins', '__return_null');
    remove_action ('load-update-core.php', 'wp_update_themes');
    add_filter ('pre_site_transient_update_themes', '__return_null');
}

if($salong['switch_post_revision']){
    //文章修订版本
    add_filter( 'wp_revisions_to_keep', 'specs_wp_revisions_to_keep', 10, 2 );
    function specs_wp_revisions_to_keep( $num, $post ) {
        return 0;
    }
}

if($salong['switch_autosave']){
    //禁用后台自动保存
    add_action('admin_print_scripts', create_function( '$a', "wp_deregister_script('autosave');"));
}

if($salong['switch_recently_active_plugins']){
    //显示最近启用过的插件
    add_action('admin_head', 'disable_recently_active_plugins');
    function disable_recently_active_plugins() {
        update_option('recently_activated', array());
    }
}

if($salong['switch_max_srcset']){
    //禁用 WordPress 4.4+ 的响应式图片功能及缩略图裁剪的所有功能
    function add_image_insert_override( $sizes ){
        global $salong;
        if( $salong['thumb_mode']== 'timthumb'){
            unset( $sizes[ 'thumbnail' ]);
            unset( $sizes[ 'medium' ]);
            unset( $sizes[ 'shop_thumbnail' ]);
            unset( $sizes[ 'shop_catalog' ]);
            unset( $sizes[ 'shop_single' ]);
            unset( $sizes[ 'woocommerce_thumbnail' ]);
            unset( $sizes[ 'woocommerce_single' ]);
            unset( $sizes[ 'woocommerce_gallery_thumbnail' ]);
        }
        unset( $sizes[ 'medium_large' ] );
        unset( $sizes[ 'large' ]);
        unset( $sizes[ 'full' ] );
        return $sizes;
    }
    add_filter( 'intermediate_image_sizes_advanced', 'add_image_insert_override' );
}

if($salong['switch_login_errors']){
    //隐藏面板登陆错误信息
    function failed_login() {
        return '';
    }
    add_filter('login_errors', 'failed_login');
}

if($salong['switch_redirect_single_post']){
    //当搜索结果只有一篇时直接重定向到日志
    add_action('template_redirect', 'salong_redirect_single_post');
    function salong_redirect_single_post() {
        if (is_search()) {
            global $wp_query;
            if ($wp_query->post_count == 1) {
                wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
            }
        }
    }
}

if($salong['switch_search_by_title_only']){
    //只搜索标题
    function __search_by_title_only( $search,$wp_query ){
        global $wpdb;

        if ( empty( $search ) )
            return $search; // skip processing - no search term in query

        $q = $wp_query->query_vars;    
        $n = ! empty( $q['exact'] ) ? '' : '%';

        $search =
        $searchand = '';

        foreach ( (array) $q['search_terms'] as $term ) {
            $term = esc_sql( like_escape( $term ) );
            $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
            $searchand = ' AND ';
        }

        if ( ! empty( $search ) ) {
            $search = " AND ({$search}) ";
            if ( ! is_user_logged_in() )
                $search .= " AND ($wpdb->posts.post_password = '') ";
        }

        return $search;
    }
    add_filter( 'posts_search', '__search_by_title_only', 500, 2 );
}

if($salong['switch_remove_logo']){
    //移除 Admin Bar 上的 WordPress Logo
    function salong_admin_bar_remove(){
        global$wp_admin_bar;/* Remove their stuff */
        $wp_admin_bar->remove_menu('wp-logo');
    }
    add_action('wp_before_admin_bar_render','salong_admin_bar_remove',0);
}

if($salong['switch_shortcode_auto'] && is_single()){
    //禁止简码自动添加p与br标签
    remove_filter( 'the_content', 'wpautop' );
    add_filter( 'the_content', 'wpautop' , 12);
}

if($salong['switch_content_auto']){
    //禁止整个文章自动添加p与br标签
    remove_filter (  'the_content' ,  'wpautop'  );
    remove_filter (  'the_excerpt' ,  'wpautop'  );
}
/***************************************************************************************
优化end
***************************************************************************************/
