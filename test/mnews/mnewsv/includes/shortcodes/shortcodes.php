<?php
// 远方的雪山简码
// http://salonglong.com

add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');


// 一、图标框
////////////////////////////////////////////////////////////

//1.信息
function sl_infobox($atts, $content=null, $code="") {
  $return = '<div class="infobox">';
  $return .= $content;
  $return .= '</div>';
  return $return;
}
add_shortcode('infobox' , 'sl_infobox' );
//简码：[infobox]远方的雪山[/infobox]


//2.成功
function sl_successbox($atts, $content=null, $code="") {
  $return = '<div class="successbox">';
  $return .= $content;
  $return .= '</div>';
  return $return;
}
add_shortcode('successbox' , 'sl_successbox' );
//简码：[successbox]远方的雪山[/successbox]

//3.警告
function sl_warningbox($atts, $content=null, $code="") {
  $return = '<div class="warningbox">';
  $return .= $content;
  $return .= '</div>';
  return $return;
}
add_shortcode('warningbox' , 'sl_warningbox' );
//简码：[warningbox]远方的雪山[/warningbox]

//4.错误
function sl_errorbox($atts, $content=null, $code="") {
  $return = '<div class="errorbox">';
  $return .= $content;
  $return .= '</div>';
  return $return;
}
add_shortcode('errorbox' , 'sl_errorbox' );
//简码：[errorbox]远方的雪山[/errorbox]

// 二、按钮
////////////////////////////////////////////////////////////

function scbutton( $atts, $content = null ) {
    extract(
        shortcode_atts(
            array(
                'link'      => '#',
                'target'    => '',
                'variation' => '',
                'size'      => '',
                'align'     => '',
            ),
            $atts
        )
    );

  $style = ($variation) ? ' '.$variation : '';
  $align = ($align) ? ' align'.$align : '';
  $size = ($size == 'large') ? ' large_button' : '';
  $target = ($target == 'blank') ? 'target="_blank"' : '';

  $out = '<a '.$target.' class="scbutton '.$style.$size.$align.'" href="'.$link.'">'.do_shortcode($content).'</a>';

    return $out;
}
add_shortcode('scbutton', 'scbutton');
//简码：[scbutton link="http://salonglong.com" size="large" align="right"]远方的雪山[/scbutton ]


// 二、列表
////////////////////////////////////////////////////////////
//1.小红点
function sl_redlist($atts, $content = null) {
    return '<div class="redlist">'.$content.'</div>';
}
add_shortcode('ssredlist', 'sl_redlist');
//简码：[ssredlist]<ul> <li>列表内容1</li> <li>列表内容2</li> <li>列表内容3</li> </ul>[/ssredlist]

//2.小黄点
function sl_yellowlist($atts, $content = null) {
    return '<div class="yellowlist">'.$content.'</div>';
}
add_shortcode('ssyellowlist', 'sl_yellowlist');
//简码：[ssyellowlist]<ul> <li>列表内容1</li> <li>列表内容2</li> <li>列表内容3</li> </ul>[/ssyellowlist]

//3.小蓝点
function sl_bluelist($atts, $content = null) {
    return '<div class="bluelist">'.$content.'</div>';
}
add_shortcode('ssbluelist', 'sl_bluelist');
//简码：[ssbluelist]<ul> <li>列表内容1</li> <li>列表内容2</li> <li>列表内容3</li> </ul>[/ssbluelist]

//4.小绿点
function sl_greenlist($atts, $content = null) {
    return '<div class="greenlist">'.$content.'</div>';
}
add_shortcode('ssgreenlist', 'sl_greenlist');
//简码：[ssgreenlist]<ul> <li>列表内容1</li> <li>列表内容2</li> <li>列表内容3</li> </ul>[/ssgreenlist]

//三、视频
/*优酷视频*/
function salong_youku($atts){
    global $salong,$post;
    extract(
        shortcode_atts(
            array(
                'id'      => '',
                'youku_id' => '',
                'height'  => 416
            ),
            $atts
        )
    );
    $items .= '<div id="'.$id.'" class="youku_post_video" style="height:'.$height.'px"></div>';
    $items .= '<script type="text/javascript" src="//player.youku.com/jsapi"></script>';
    $items .= '<script type="text/javascript">
                var player = new YKU.Player("'.$id.'", {
                    styleid: "0",
                    client_id: "'.$salong['client_youku'].'",
                    vid: "'.$youku_id.'",
                    newPlayer: true,
                    show_related: false,
                    autoplay: false,
                    wmode: "opaque"
                });
            </script>';
    return $items;
}
add_shortcode('youku', 'salong_youku');
// 简码：[youku id="youku1" youku_id="优酷视频 ID" height="416"][/youku]

/*阿里云视频点播视频*/
function salong_ali($atts){
    global $salong,$client_ali,$regionId;
    
    extract(
        shortcode_atts(
            array(
                'id'     => '',
                'ali_id' => ''
            ),
            $atts
        )
    );
    /*阿里云视频点播API接口*/
    $PlayAuth_object = salong_GetVideoPlayAuth($client_ali, $regionId, $ali_id);
    /*转换 object 为 array*/
    $PlayAuth_array  = get_object_vars($PlayAuth_object);
    $VideoMeta_array = get_object_vars($PlayAuth_array['VideoMeta']);

    $CoverURL        = $VideoMeta_array['CoverURL'];
    $PlayAuth        = $PlayAuth_array['PlayAuth'];
    $item .= '<div id="'.$id.'" class="ali_video"></div>';
    $item .= '<script type="text/javascript">
                var player = new Aliplayer({
                    id: "'.$id.'",
                    isLive: false,
                    autoplay: false,
                    width: "100%",
                    controlBarVisibility: "hover",
                    useH5Prism: true,
                    vid : "'.$ali_id.'",
                    playauth : "'.$PlayAuth.'",
                    cover: "'.$CoverURL.'"
                }, function(player) {
                    console.log("播放器创建了。");
                });
            </script>';
    return $item;
}
add_shortcode('ali', 'salong_ali');
// 简码：[ali id="ali1" ali_id="阿里云视频点播视频 ID"][/ali]

/*HTML5视频*/
function salong_html5_video($atts){
    global $salong;
    
    extract(
        shortcode_atts(
            array(
                'source' => '',
                'cover'  => '',
                'height' => 416
            ),
            $atts
        )
    );
    $item .= '<div class="html5_video"><video poster="'.$cover.'" height="'.$height.'" width="740" style="max-width:100%;"><source src="'.$source.'" type="video/mp4"></video></div>';
    return $item;
}
add_shortcode('sl_video', 'salong_html5_video');
// 简码：[sl_video source="HTML5视频地址" cover="HTML5视频封面地址" height="416"][/sl_video]

/*HTML5音频*/
function salong_html5_audio($atts){
    global $salong;
    
    extract(
        shortcode_atts(
            array(
                'source' => ''
            ),
            $atts
        )
    );
    $item .= '<div class="html5_video"><audio width="100%" src="'.$source.'"><source src="'.$source.'" type="audio/mp3"></audio></div>';
    return $item;
}
add_shortcode('sl_audio', 'salong_html5_audio');
// 简码：[sl_audio source="HTML5音频地址"][/sl_audio]

/******其它简码******/

//相关标签下的文章
function sl_related_posts( $atts ) {
    extract(shortcode_atts(array(
        'tagid' => '',
    ), $atts));
    $args=array(
        'include' => $tagid
    );
	$tags = get_tags($args);
	// 循环所有标签 
	foreach ($tags as $tag) {
		// 得到标签ID
        $tag_id = $tag->term_id; 
		// 得到标签下所有文章 
        $related .='<div class="related_tagposts">';
        $related .= '<h2>'.sprintf( __( '<a href="%s" title="查看“%s”标签的相关文章列表">“%s”的相关文章</a>' , 'salong' ), esc_attr(get_tag_link($tag)), esc_attr($tag->name), esc_attr($tag->name)).'</h2>';
        $related .='<ul>';
        $prefix_posts = new WP_Query(array('tag_id' => $tag_id,'order' => 'ASC'));if ($prefix_posts->have_posts()): while ($prefix_posts->have_posts()): $prefix_posts->the_post();
        $related .= '<li>';
        $related .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
        $related .= '</li>';
        endwhile;endif;wp_reset_postdata();
        $related .= '</ul>';
        $related .= '</div>';
    }
    return $related;
}
add_shortcode('related_posts', 'sl_related_posts');
//简码：[related_posts tagid="5"]


//短代码之评论可见
function reply_to_read($atts, $content=null) {
    extract(
        shortcode_atts(
            array(
                "notice" => '<div class="warningbox">'.__('<span style="color:red; font-size=13px;">温馨提示：</span>此处内容需要<a href="#respond" title="评论本文">评论本文</a>后才能查看。','salong').'</div>'
            ),
            $atts
        )
    );
    $email = null;
    $user_ID = (int) wp_get_current_user()->ID;
    if ($user_ID > 0) {
        $email = get_userdata($user_ID)->user_email;
        //对博主直接显示内容
        $admin_email = get_bloginfo ( 'admin_email' ); //博主Email
        if ($email == $admin_email) {
            return $content;
        }
    } else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
        $email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
    } else {
        return $notice;
    }
    if (empty($email)) {
        return $notice;
    }
    global $wpdb;
    $post_id = get_the_ID();
    $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
    if ($wpdb->get_results($query)) {
        return do_shortcode($content);
    } else {
        return $notice;
    }
}
add_shortcode('reply', 'reply_to_read'); 
//简码：[reply]评论后可见内容[/reply]或者[reply notice="自定义提醒回复内容"]自定义提醒回复内容[/reply]

//仅用户可以看到的内容
function sl_private_content($atts, $content = null) {
    if ( is_user_logged_in() ) {
        $items .= $content;
    }else{
        if ( class_exists( 'XH_Social' ) ){
            $login_url = '#login';
        }else{
            $login_url  = wp_login_url($_SERVER['REQUEST_URI']);//登录
        }
        $items .= sprintf('<div class="warningbox">'.__('当前内容只有登录了才能查看，如果您已经注册，<a href="%s" title="">请登录</a>。','salong').'</div>',$login_url);
    }
    return $items;
}
add_shortcode('private', 'sl_private_content');
//简码：[private]只有用户才能看到的内容[/private]

//哪些角色可查看的内容
function salong_role_content($atts, $content = null) {
    global $salong;
    extract(
        shortcode_atts(
            array(
                'id'  => ''
            ),
            $atts
        )
    );
    if($id == 1){
        $access = 'manage_options';
        $name = __( '仅管理员', 'salong' );
    }else if($id == 2){
        $access = 'edit_others_posts';
        $name = __( '管理员、编辑', 'salong' );
    }else if($id == 3){
        $access = 'publish_posts';
        $name = __( '管理员、编辑、作者', 'salong' );
    }else if($id == 4){
        $access = 'edit_posts';
        $name = __( '管理员、编辑、作者、投稿者', 'salong' );
    }else if($id == 5){
        $access = 'read';
        $name = __( '管理员、编辑、作者、投稿者、订阅者', 'salong' );
    }
    
    if ( is_user_logged_in() ) {
        if (current_user_can( $access )) {
            $items .= $content;
        }else{
            $items .= '<div class="warningbox">'.__('抱歉，您没有权限查看当前内容，如有疑问请联系管理员。','salong').'</div>';
        }
    }else{
        if ( class_exists( 'XH_Social' ) ){
            $login_url = '#login';
        }else{
            $login_url  = wp_login_url($_SERVER['REQUEST_URI']);//登录
        }
        $items .= sprintf('<div class="warningbox">'.__('当前内容只有[%s]了才能查看，如果您已经注册，<a href="%s" title="">请登录</a>。','salong').'</div>',$name,$login_url);
    }
    return $items;
}
add_shortcode('role', 'salong_role_content');
//简码：[role id="2"]哪些角色可查看的内容[/role]

//购买产品可见内容
function salong_buy_content($atts, $content = null) {
    global $salong,$current_user;
    extract(
        shortcode_atts(
            array(
                'product_id'  => ''
            ),
            $atts
        )
    );
    $access_level = $salong['vip_access'];
    if ( salong_is_administrator() || empty($product_id) || current_user_can( $access_level ) || wc_customer_bought_product( $current_user->email, $current_user->ID, $product_id ) || $current_user->roles[0] == 'vip' ) {
        $items .= $content;
    }else{
        if ( is_user_logged_in() ) {
            $items .= sprintf('<div class="warningbox">'.__('当前内容只有购买了&nbsp;【%s】&nbsp;产品的用户才能查看，点击&nbsp;<a href="%s" target="_blank" title="前往购买">前往购买</a>。','salong').'</div>',get_the_title($product_id),get_permalink($product_id));
        }else{
            if ( class_exists( 'XH_Social' ) ){
                $login_url = '#login';
            }else{
                $login_url  = wp_login_url($_SERVER['REQUEST_URI']);//登录
            }
            $items .= sprintf('<div class="warningbox">'.__('当前内容只有购买了&nbsp;【%s】&nbsp;产品的用户才能查看，点击&nbsp;<a href="%s" target="_blank" title="前往购买">前往购买</a>，如果您已经购买，<a href="%s" title="">请登录</a>。','salong').'</div>',get_the_title($product_id),get_permalink($product_id),$login_url);
        }
    }
    return $items;
}
add_shortcode('buy', 'salong_buy_content');

//简码：[buy product_id="产品 ID"]购买产品才能查看的内容[/buy]