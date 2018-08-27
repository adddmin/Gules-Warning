<?php global $salong;

$footer_logo    = $salong['footer_logo']['url'];
$app_android_qr = $salong['app_android_qr']['url'];
$app_apple_qr   = $salong['app_apple_qr']['url'];
$wechat_qr      = $salong['wechat_qr']['url'];
$weibo_url      = $salong['weibo_url'];
$qqzone_url     = $salong['qqzone_url'];


if ($salong['switch_link_go']) {
    $weibo_link  = external_link($weibo_url);
    $qqzone_link = external_link($qqzone_url);
} else {
    $weibo_link  = $weibo_url;
    $qqzone_link = $qqzone_url;
}

$sitemap = get_page_id_from_template('template-sitemap.php');

?>
<footer class="footer">
    <section class="wrapper">
        <div class="left">
            <!--页脚菜单-->
            <?php wp_nav_menu( array( 'container'=>'nav','container_class'=>'footer_menu', 'theme_location' => 'footer-menu','items_wrap'=>'<ul class="menu">%3$s</ul>' , 'fallback_cb'=>'Salong_footer_nav_fallback') ); ?>
            <section class="footer_contact">
                <?php echo $salong['footer_contact']; ?>
            </section>
            <section class="copyright">
                <?php echo $salong['copyright_text']; ?>
                <?php if($salong[ 'tracking_code'] && !current_user_can('level_10')){ echo '&nbsp;'.stripslashes($salong[ 'tracking_code']). ''; } ?>
            </section>
        </div>
        <div class="right">
            <?php if($footer_logo){ ?>
            <a href="<?php echo home_url(); ?>" class="footer_logo" <?php echo new_open_link(); ?>><img src="<?php echo $footer_logo; ?>" alt="<?php bloginfo('name'); ?>-<?php bloginfo('description'); ?>"></a>
            <?php } ?>
            <?php if($app_android_qr || $app_apple_qr || $wechat_qr || $weibo_url || $qqzone_url){ ?>
            <section class="footer_btn">
                <?php if ($app_android_qr){ ?>
                <a href="#android" title="<?php _e('客户端安卓版','salong'); ?>" rel="external nofollow">
                    <?php echo svg_android(); ?>
                </a>
                <?php } if ($app_apple_qr){ ?>
                <a href="#apple" title="<?php _e('客户端苹果版','salong'); ?>" rel="external nofollow">
                    <?php echo svg_apple(); ?>
                </a>
                <?php } if ($wechat_qr){ ?>
                <a href="#wechat" title="<?php _e('微信公众号','salong'); ?>" rel="external nofollow">
                    <?php echo svg_wechat(); ?>
                </a>
                <?php } if ($weibo_url){ ?>
                <a href="<?php echo $weibo_link; ?>" title="<?php _e('微博主页','salong'); ?>" rel="external nofollow"<?php echo new_open_link(); ?>>
                    <?php echo svg_sina(); ?>
                </a>
                <?php } if ($qqzone_url){ ?>
                <a href="<?php echo $qqzone_link; ?>" title="<?php _e('QQ 空间','salong'); ?>" rel="external nofollow"<?php echo new_open_link(); ?>>
                    <?php echo svg_qqzone(); ?>
                </a>
                <?php } if ($sitemap){ ?>
                <a href="<?php echo get_page_link($sitemap); ?>" title="<?php _e('网站地图','salong'); ?>"<?php echo new_open_link(); ?>>
                    <?php echo svg_rss(); ?>
                </a>
                <?php } ?>
            </section>
            <?php } ?>
        </div>
    </section>
    <?php get_template_part( 'content/footer','popup'); ?>
    <?php get_template_part( 'content/side','btn'); ?>
    <!--广告背景-->
    <div class="bg light"></div>
    <!--购物车-->
    <div class="bg cart"></div>
    <?php if (class_exists('woocommerce')){ get_template_part( 'woocommerce/ajax','cart'); } ?>
    <?php get_template_part( 'content/mobile','btn'); ?>
</footer>
<!--禁止复制-->
<?php if($salong['switch_copy']){ ?>
<script type="text/Javascript">
    document.oncontextmenu=function(e){return false;}; document.onselectstart=function(e){return false;};
</script>
<style>
    body {
        -moz-user-select: none;
    }

</style>
<SCRIPT LANGUAGE=javascript>
    if (top.location != self.location) top.location = self.location;

</SCRIPT>
<noscript>
    <iframe src=*.Html></iframe>
</noscript>
<?php } ?>
<?php wp_footer(); ?>
<?php if($salong[ 'switch_loadmore']){ get_template_part( 'includes/loadmore'); } ?>

<!--分享到微信 JDK-->
<?php if(salong_is_weixin() && $salong['switch_wechat_share']){
    global $salong,$post;
    $weixin_appid = $salong['weixin_appid'];
    $weixin_appsecret = $salong['weixin_appsecret'];
    /*微信分享 JDK*/
    $jssdk = new JSSDK($weixin_appid, $weixin_appsecret);
    $signPackage = $jssdk->GetSignPackage();
    
    /*分享图片*/
    $bd_img = get_post_meta($post->ID, "thumb", true);//自定义域图片
    $timthumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');//特色图像
    $content = $post->post_content;
    preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
    $n = count($strResult[1]);//文章第一种图片
    $default_img = $salong['default_thumb']['url'];
?>

<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
    /*
     * 注意：
     * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
     * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
     * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
     *
     * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
     * 邮箱地址：weixin-open@qq.com
     * 邮件主题：【微信JS-SDK反馈】具体问题
     * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
     */
    wx.config({
        debug: false,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','onMenuShareQZone']
    });
    wx.ready(function() {
        //分享到朋友圈
        wx.onMenuShareTimeline({
            title: '<?php the_title(); ?>', // 分享标题
            link: '<?php the_permalink() ?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?php if($bd_img) { echo $bd_img; }else if( has_post_thumbnail() ){ echo $timthumb[0]; } else if($n > 1){ echo $strResult[1][0]; } else { echo $default_img; } ?>', // 分享图标
            success: function() {
                // 用户确认分享后执行的回调函数
                var ajax_data = {
                    action: "wechat_share",
                    postid: <?php echo get_the_ID();?>
                }
                $.ajax({
                    type: "POST",
                    url: '<?php echo admin_url();?>/admin-ajax.php', //你的admin-ajax.php地址
                    data: ajax_data,
                    dataType: 'json',
                    success: function(data) {

                    }
                });
            },
        });
        //分享给朋友
        wx.onMenuShareAppMessage({
            title: '<?php the_title(); ?>', // 分享标题
            desc: '<?php if (has_excerpt()) { ?><?php echo strip_tags(get_the_excerpt()); ?><?php } else{ echo strip_tags(wp_trim_words(get_the_content(),66)); } ?>', // 分享描述
            link: '<?php the_permalink() ?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?php if($bd_img) { echo $bd_img; }else if( has_post_thumbnail() ){ echo $timthumb[0]; } else if($n > 1){ echo $strResult[1][0]; } else { echo $default_img; } ?>', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function() {
                // 用户确认分享后执行的回调函数
                var ajax_data = {
                    action: "wechat_share",
                    postid: <?php echo get_the_ID();?>
                }
                $.ajax({
                    type: "POST",
                    url: '<?php echo admin_url();?>/admin-ajax.php', //你的admin-ajax.php地址
                    data: ajax_data,
                    dataType: 'json',
                    success: function(data) {

                    }
                });
            },
        });
        //分享到QQ
        wx.onMenuShareQQ({
            title: '<?php the_title(); ?>', // 分享标题
            desc: '<?php if (has_excerpt()) { ?><?php echo strip_tags(get_the_excerpt()); ?><?php } else{ echo strip_tags(wp_trim_words(get_the_content(),66)); } ?>', // 分享描述
            link: '<?php the_permalink() ?>', // 分享链接
            imgUrl: '<?php if($bd_img) { echo $bd_img; }else if( has_post_thumbnail() ){ echo $timthumb[0]; } else if($n > 1){ echo $strResult[1][0]; } else { echo $default_img; } ?>', // 分享图标
            success: function() {
                // 用户确认分享后执行的回调函数
                var ajax_data = {
                    action: "wechat_share",
                    postid: <?php echo get_the_ID();?>
                }
                $.ajax({
                    type: "POST",
                    url: '<?php echo admin_url();?>/admin-ajax.php', //你的admin-ajax.php地址
                    data: ajax_data,
                    dataType: 'json',
                    success: function(data) {

                    }
                });
            },
        });
        //分享到腾讯微博
        wx.onMenuShareWeibo({
            title: '<?php the_title(); ?>', // 分享标题
            desc: '<?php if (has_excerpt()) { ?><?php echo strip_tags(get_the_excerpt()); ?><?php } else{ echo strip_tags(wp_trim_words(get_the_content(),66)); } ?>', // 分享描述
            link: '<?php the_permalink() ?>', // 分享链接
            imgUrl: '<?php if($bd_img) { echo $bd_img; }else if( has_post_thumbnail() ){ echo $timthumb[0]; } else if($n > 1){ echo $strResult[1][0]; } else { echo $default_img; } ?>', // 分享图标
            success: function() {
                // 用户确认分享后执行的回调函数
                var ajax_data = {
                    action: "wechat_share",
                    postid: <?php echo get_the_ID();?>
                }
                $.ajax({
                    type: "POST",
                    url: '<?php echo admin_url();?>/admin-ajax.php', //你的admin-ajax.php地址
                    data: ajax_data,
                    dataType: 'json',
                    success: function(data) {

                    }
                });
            },
        });
        //分享到QQ空间
        wx.onMenuShareQZone({
            title: '<?php the_title(); ?>', // 分享标题
            desc: '<?php if (has_excerpt()) { ?><?php echo strip_tags(get_the_excerpt()); ?><?php } else{ echo strip_tags(wp_trim_words(get_the_content(),66)); } ?>', // 分享描述
            link: '<?php the_permalink() ?>', // 分享链接
            imgUrl: '<?php if($bd_img) { echo $bd_img; }else if( has_post_thumbnail() ){ echo $timthumb[0]; } else if($n > 1){ echo $strResult[1][0]; } else { echo $default_img; } ?>', // 分享图标
            success: function() {
                // 用户确认分享后执行的回调函数
                var ajax_data = {
                    action: "wechat_share",
                    postid: <?php echo get_the_ID();?>
                }
                $.ajax({
                    type: "POST",
                    url: '<?php echo admin_url();?>/admin-ajax.php', //你的admin-ajax.php地址
                    data: ajax_data,
                    dataType: 'json',
                    success: function(data) {

                    }
                });
            },
        });
    });

</script>
<?php } ?>
</body>

</html>
