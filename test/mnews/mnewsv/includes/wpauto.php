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
    function post_thumbnail(){
        global $salong,$post,$product,$woocommerce;
        $thumb = get_post_meta($post->ID, "thumb", true);
        if($thumb) {
            echo '<img class="thumb" src="'.$salong['thumb_loading']['url'].'" data-original="'.$thumb.'" alt="'.$post->post_title.'" />';
        } else if( has_post_thumbnail() ){
            //缩略图
            if(get_post_type() == 'product'){
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'shop_single');
            }else{
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'medium');
            }
            echo '<img class="thumb" src="'.$salong['thumb_loading']['url'].'" data-original="'.$thumbnail[0].'" alt="'.$post->post_title.'" />';
        } else {
            $content = $post->post_content;
            preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
            $n = count($strResult[1]);
            if($n > 0){
                //第一张图片
                echo '<img class="thumb" src="'.$salong['thumb_loading']['url'].'" data-original="'.$strResult[1][0].'" alt="'.$post->post_title.'" />';
            } else {
                //默认图片
                echo '<img class="thumb" src="'.$salong['thumb_loading']['url'].'" data-original="'.$salong['default_thumb']['url'].'" alt="'.$post->post_title.'" />';
            }
        }
    }
    //小工具列表缩略图
    function widget_thumbnail(){
        global $salong,$post,$product;
        $thumb = get_post_meta($post->ID, "thumb", true);
        if($thumb) {
            echo '<img class="thumb" src="'.$salong['thumb_loading']['url'].'" data-original="'.$thumb.'" alt="'.$post->post_title.'" />';
        } else if( has_post_thumbnail() ){
            //缩略图
            if(get_post_type() == 'product'){
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'shop_catalog');
            }else{
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'medium');
            }
            echo '<img class="thumb" src="'.$salong['thumb_loading']['url'].'" data-original="'.$thumbnail[0].'" alt="'.$post->post_title.'" />';
        } else {
            $content = $post->post_content;
            preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
            $n = count($strResult[1]);
            if($n > 0){
                //第一张图片
                echo '<img class="thumb" src="'.$salong['thumb_loading']['url'].'" data-original="'.$strResult[1][0].'" alt="'.$post->post_title.'" />';
            } else {
                //默认图片
                echo '<img class="thumb" src="'.$salong['thumb_loading']['url'].'" data-original="'.$salong['default_thumb']['url'].'" alt="'.$post->post_title.'" />';
            }
        }
    }
}
else {
    //分类列表缩略图
    function post_thumbnail(){
        global $salong,$post,$product;
        //自定义域
        $thumb = get_post_meta($post->ID, "thumb", true);
        if($thumb) {
            echo '<img class="thumb" src="'.$thumb.'" alt="'.$post->post_title.'" />';
        } else if( has_post_thumbnail() ){
            //缩略图
            if(get_post_type() == 'product'){
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'shop_single');
            }else{
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'medium');
            }
            echo '<img class="thumb" src="'.$thumbnail[0].'" alt="'.$post->post_title.'" />';
        } else {
            $content = $post->post_content;
            preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
            $n = count($strResult[1]);
            if($n > 0){
                //第一张图片
                echo '<img class="thumb" src="'.$strResult[1][0].'" alt="'.$post->post_title.'" />';
            } else {
                //默认图片
                echo '<img class="thumb" src="'.$salong['default_thumb']['url'].'" alt="'.$post->post_title.'" />';
            }
        }
    }
    //小工具列表缩略图
    function widget_thumbnail(){
        global $salong,$post,$product;
        //自定义域
        $thumb = get_post_meta($post->ID, "thumb", true);
        if($thumb) {
            echo '<img class="thumb" src="'.$thumb.'" alt="'.$post->post_title.'" />';
        } else if( has_post_thumbnail() ){
            //缩略图
            if(get_post_type() == 'product'){
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'shop_catalog');
            }else{
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'medium');
            }
            echo '<img class="thumb" src="'.$thumbnail[0].'" alt="'.$post->post_title.'" />';
        } else {
            $content = $post->post_content;
            preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
            $n = count($strResult[1]);
            if($n > 0){
                //第一张图片
                echo '<img class="thumb" src="'.$strResult[1][0].'" alt="'.$post->post_title.'" />';
            } else {
                //默认图片
                echo '<img class="thumb" src="'.$salong['default_thumb']['url'].'" alt="'.$post->post_title.'" />';
            }
        }
    }
}

/*无图片延迟加载*/

function no_post_thumbnail(){
    global $salong,$post,$product;
    //自定义域
    $thumb = get_post_meta($post->ID, "thumb", true);
    if($thumb) {
        echo '<img class="thumb" src="'.$thumb.'" alt="'.$post->post_title.'" />';
    } else if( has_post_thumbnail() ){
        //缩略图
        if(get_post_type() == 'product'){
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'shop_single');
        }else{
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'medium');
        }
        echo '<img class="thumb" src="'.$thumbnail[0].'" alt="'.$post->post_title.'" />';
    } else {
        $content = $post->post_content;
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
        $n = count($strResult[1]);
        if($n > 0){
            //第一张图片
            echo '<img class="thumb" src="'.$strResult[1][0].'" alt="'.$post->post_title.'" />';
        } else {
            //默认图片
            echo '<img class="thumb" src="'.$salong['default_thumb']['url'].'" alt="'.$post->post_title.'" />';
        }
    }
}