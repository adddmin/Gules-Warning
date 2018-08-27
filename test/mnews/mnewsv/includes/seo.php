<?php
if (!function_exists('utf8Substr')) {
 function utf8Substr($str, $from, $len)
 {
     return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
          '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
          '$1',$str);
 }
}
if ( is_single() || is_page() ){
    /*自定义域页面描述*/
    global $post;
    $seo_description = get_post_meta($post->ID, "seo_description", true);
    if($seo_description){
        $description = $seo_description;
    } else {
        /*文章摘要或截取一段文字*/
        if ($post->post_excerpt) {
            $description  = $post->post_excerpt;
        } else {
            if(preg_match('/<p>(.*)<\/p>/iU',trim(strip_tags($post->post_content,"<p>")),$result)){
                $post_content = $result['1'];
            } else {
                $post_content_r = explode("\n",trim(strip_tags($post->post_content)));
                $post_content = $post_content_r['0'];
            }
            $description = utf8Substr($post_content,0,220);
        }
    }
}

global $post;
$seo_tag      = get_post_meta($post->ID, "seo_tag", true);
$term_id      = get_queried_object()->term_id;//当前分类ID
$cate_seo_tag = get_term_meta($term_id,'seo_tag',true);

if($seo_tag){
    $tags_name_str = $seo_tag;
} else {
    if(is_singular('topic')){
        //画廊标签
        $posttags = get_the_terms($post->ID, 'ttag' );
    } else if(is_singular('download')){
        //视频标签
        $posttags = get_the_terms($post->ID, 'dtag' );
    } else {
        //文章标签
        $posttags = get_the_terms($post->ID, 'post_tag' );
    }
    if ($posttags && ! is_wp_error($posttags)){
        $term_names_arr = array();
        foreach ($posttags as $tag) {
            $tag_names_arr[] = $tag->name;
        }
        $tags_name_str = join( ",", $tag_names_arr);
    }
}
echo "\n";?>
<?php global $salong; ?>
<?php if($salong[ 'google_key']){ ?><meta name="google-site-verification" content="<?php echo $salong[ 'google_key']; ?>" /><?php } ?>
<?php if($salong[ 'baidu_key']){ ?><meta name="baidu-site-verification" content="<?php echo $salong[ 'baidu_key']; ?>" /><?php } ?>
<?php if($salong[ '360_key']){ ?><meta name="360-site-verification" content="<?php echo $salong[ '360_key']; ?>" /><?php } ?>
<?php if (is_single()) { ?>
<meta name="description" content="<?php echo strip_tags($description); ?>" />
<meta name="keywords" content="<?php echo $tags_name_str; ?>" />
<link rel="canonical" href="<?php the_permalink(); ?>"/>
<?php }  elseif ( is_page() ) { ?>
<meta name="description" content="<?php echo strip_tags($description); ?>" />
<meta name="keywords" content="<?php if($tags_name_str){echo $tags_name_str;}else{the_title();} ?>" />
<link rel="canonical" href="<?php the_permalink(); ?>"/>
<?php }  elseif ( is_category() || is_tax() ) { ?>
<meta name="description" content="<?php echo deletehtml(category_description()); ?>" />
<meta name="keywords" content="<?php if($cate_seo_tag){ echo $cate_seo_tag; }else{ single_cat_title(); } ?>" />
<link rel="canonical" href="<?php echo salong_archive_link();?>"/>
<?php }  elseif ( is_tag() ) { ?>
<meta name="description" content="<?php echo single_tag_title(); ?>" />
<meta name="keywords" content="<?php echo single_tag_title(); ?>" />
<link rel="canonical" href="<?php echo salong_archive_link();?>"/>
<?php }  elseif ( is_home() ) { ?>
<meta name="description" content="<?php echo $salong['description']; ?>" />
<meta name="keywords" content="<?php echo $salong['keywords']; ?>" />
<link rel="canonical" href="<?php echo salong_archive_link();?>"/>
<?php } ?>
<!--熊掌号-->
<?php if( is_singular() && $salong['switch_xiongzhang'] ){ ?>
<script type="application/ld+json">
    {
        "@context": "https://ziyuan.baidu.com/contexts/cambrian.jsonld",
        "@id": "<?php echo get_permalink();?>",
        "appid": "<?php echo $salong['xiongzhang_id']; ?>",
        "title": "<?php echo salong_theme_title(); ?>",
        "images": ["<?php echo post_thumbnail_src();?>"],
        "description": "<?php if($post->post_excerpt){$printDescription = $post->post_excerpt;}else{$printDescription = preg_replace('/\s+/','',mb_strimwidth(strip_tags($post->post_content),0,145,''));} echo $printDescription;?>",
        "pubDate": "<?php echo get_the_time('Y-m-d\TG:i:s'); ?>"
    }
</script>
<script src="//msite.baidu.com/sdk/c.js?appid=<?php echo $salong['xiongzhang_id']; ?>"></script>
<?php } ?>