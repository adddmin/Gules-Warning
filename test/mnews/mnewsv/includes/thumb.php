<?php
// 缩略图，使用WP自带的缩略图功能
global $salong;
add_theme_support( 'post-thumbnails' );
if( $salong['switch_lazyload']== true ){
    // 为图片添加 data-original 属性，延迟加载功能
    add_filter ('the_content', 'lazyload');
    function lazyload($content) {
        global $salong;
        $loadimg = $salong['post_loading']['url'];
        if(!is_feed()||!is_robots) {
            $content=preg_replace('/<img(.+)src=[\'"]([^\'"]+)[\'"](.*)>/i',"<img\$1data-original=\"\$2\" src=\"$loadimg\"\$3>",$content);
        }
        return $content;
    }
    //分类列表缩略图
    function post_thumbnail($width = 460,$height = 280){
        global $post,$salong;
        $thumb = get_post_meta($post->ID, "thumb", true);
        if($thumb) {
            echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['thumb_loading']['url'].'&amp;h='.$height.'&amp;w='.$width.'" data-original="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$thumb.'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
        } else if( has_post_thumbnail() ){
            //缩略图
            $timthumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
            echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['thumb_loading']['url'].'&amp;h='.$height.'&amp;w='.$width.'" data-original="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$timthumb[0].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
        } else {
            $content = $post->post_content;
            preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
            $n = count($strResult[1]);
            if($n > 0){
                //第一张图片
                echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['thumb_loading']['url'].'&amp;h='.$height.'&amp;w='.$width.'" data-original="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$strResult[1][0].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
            } else {
                //默认图片
                echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['thumb_loading']['url'].'&amp;h='.$height.'&amp;w='.$width.'" data-original="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['default_thumb']['url'].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
            }
        }
    }
}
else {
    //分类列表缩略图
    function post_thumbnail($width = 460,$height = 280){
        global $post,$salong;
        $thumb = get_post_meta($post->ID, "thumb", true);
        if($thumb) {
            echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$thumb.'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
        } else if( has_post_thumbnail() ){
            //缩略图
            $timthumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
            echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$timthumb[0].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
        } else {
            $content = $post->post_content;
            preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
            $n = count($strResult[1]);
            if($n > 0){
                //第一张图片
                echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$strResult[1][0].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
            } else {
                //默认图片
                echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['default_thumb']['url'].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
            }
        }
    }
}

/*无图片延迟加载*/

function no_post_thumbnail($width = 690,$height = 420){
    global $post,$salong;
    $thumb = get_post_meta($post->ID, "thumb", true);
    if($thumb) {
        echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$thumb.'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
    } else if( has_post_thumbnail() ){
        //缩略图
        $timthumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$timthumb[0].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
    } else {
        $content = $post->post_content;
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
        $n = count($strResult[1]);
        if($n > 0){
            //第一张图片
            echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$strResult[1][0].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
        } else {
            //默认图片
            echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['default_thumb']['url'].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
        }
    }
}