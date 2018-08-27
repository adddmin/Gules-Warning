<?php
/**
*Author: Ashuwp
*Author url: http://www.ashuwp.com
*Version: 5.8
**/

/**
*
* 页面类型 SEO 选项
*
**/
/*****Meta Box********/
$page_tab_conf = array(
    'title'   => __('页面选项','salong'),
    'id'      => 'page_tab_box',
    'page'    => array('page'),
    'context' => 'normal',
    'priority'=> 'low',
    'tab'     => true
);

$page_tab_meta = array();

/**第一个 TABS**/
$page_tab_meta[] = array(
    'name' => __('默认页面','salong'),
    'id'   => 'page_tab_first',
    'type' => 'open'
);

$page_tab_meta[] = array(
    'name' => __('设置说明','salong'),
    'desc' => __('此 tab 下的设置只针对默认页面有效，其它模块页面不需要设置。','salong'),
    'id'      => 'page_title',
    'type'    => 'title'
);

$page_tab_meta[] = array(
    'name'  => __('简码','salong'),
    'desc'  => __('有些页面是通过简码来添加，而且 WP 编辑器默认会添加 p 或者 br 标签，为了避免出现异常，请将页面编辑器中的简码添加到此。','salong'),
    'id'    => 'page_shortcode',
    'type'  => 'text',
    'std'   => ''
);

$page_tab_meta[] = array(
    'name'    => __('选择边栏','salong'),
    'desc'    => __('不选择则不显示边栏，可在『主题选项——边栏』中增加新的边栏。','salong'),
    'id'      => 'page_sidebar',
    'subtype' => 'sidebar',
    'type'    => 'select',
    'std'     => ''
);

$page_tab_meta[] = array(
    'type' => 'close'
);

/**第二个 TABS**/
$page_tab_meta[] = array(
    'name' => __('SEO 选项','salong'),
    'id'   => 'page_tab_second',
    'type' => 'open'
);

$page_tab_meta[] = array(
    'name' => __('标题','salong'),
    'desc' => __('自定义标题。','salong'),
    'id'   => 'seo_title',
    'type' => 'text',
    'std'  => ''
);

$page_tab_meta[] = array(
    'name' => __('关键词','salong'),
    'desc' => __('自定义关键词，多个使用英文逗号隔开，不输入则获取标签做为关键词。','salong'),
    'id'   => 'seo_tag',
    'type' => 'text',
    'std'  => ''
);

$page_tab_meta[] = array(
    'name' => __('描述','salong'),
    'desc' => __('自定义描述，留空则获取摘要或截取文章第一段一定的字数。','salong'),
    'id'   => 'seo_description',
    'type' => 'textarea',
    'std'  => ''
);

$page_tab_meta[] = array(
    'type' => 'close'
);

$page_tab_box = new ashuwp_postmeta_feild($page_tab_meta, $page_tab_conf);



/**
*
* 默认文章 TABS
*
**/

$post_tab_conf = array(
    'title'   => __('文章选项','salong'),
    'id'      => 'post_tab_box',
    'page'    => array('post'),
    'context' => 'normal',
    'priority'=> 'low',
    'tab'     => true
);

$post_tab_meta = array();

/**第一个 TABS**/
$post_tab_meta[] = array(
    'name' => __('基础设置','salong'),
    'id'   => 'post_tab_general',
    'type' => 'open'
);

$post_tab_meta[] = array(
    'name'        => __('自定义缩略图','salong'),
    'desc'        => __('建议大小比例：460*280，注：获取缩略图的顺序为自定义缩略图，特色图像、文章第一张图、默认图片。','salong'),
    'button_text' => __('上传','salong'),
    'id'          => 'thumb',
    'type'        => 'upload',
    'std'         => ''
);

$post_tab_meta[] = array(
    'name'        => __('文章顶部缩略图','salong'),
    'desc'        => __('最大宽度为：840，高度不限。','salong'),
    'button_text' => __('上传','salong'),
    'id'          => 'top_thumb',
    'type'        => 'upload',
    'std'         => ''
);

$post_tab_meta[] = array(
    'name'        => __('幻灯片推送','salong'),
    'desc'        => __('勾选后此文章将推送到首页幻灯片显示，请确保『主题选项—幻灯片』中的幻灯片模式为文章推送。','salong'),
    'id'          => 'slide_recommend',
    'type'        => 'checkbox',
    'subtype' => array(
        'push'  => '推送'
    ),
    'std'         => ''
);

$post_tab_meta[] = array(
    'name'        => __('所属专题','salong'),
    'desc'        => __('当前文章所属的专题列表，点击可编辑专题。','salong'),
    'id'          => 'topic_list',
    'type'        => 'belong_topic',
    'std'         => ''
);

$post_tab_meta[] = array(
    'type' => 'close'
);

/**第二个 TABS**/
$post_tab_meta[] = array(
    'name' => __('推荐阅读','salong'),
    'id'   => 'post_tab_recommended',
    'type' => 'open'
);

$post_tab_meta[] = array(
    'name' => __('标题','salong'),
    'desc' => __('推荐阅读标题。','salong'),
    'id'   => 'recommended_title',
    'type' => 'text',
    'std'  => __('推荐阅读','salong')
);

$post_tab_meta[] = array(
    'name' => __('文章 ID','salong'),
    'desc' => __('需要推荐阅读的文章 ID，多个 ID 使用英文逗号隔开，不输入则不显示推荐阅读。','salong'),
    'id'   => 'recommended_id',
    'type' => 'text',
    'std'  => ''
);

$post_tab_meta[] = array(
    'type' => 'close'
);

/**第三个 TABS**/
$post_tab_meta[] = array(
    'name' => __('SEO 选项','salong'),
    'id'   => 'post_tab_seo',
    'type' => 'open'
);

$post_tab_meta[] = array(
    'name' => __('标题','salong'),
    'desc' => __('自定义标题。','salong'),
    'id'   => 'seo_title',
    'type' => 'text',
    'std'  => ''
);

$post_tab_meta[] = array(
    'name' => __('关键词','salong'),
    'desc' => __('自定义关键词，多个使用英文逗号隔开，不输入则获取标签做为关键词。','salong'),
    'id'   => 'seo_tag',
    'type' => 'text',
    'std'  => ''
);

$post_tab_meta[] = array(
    'name' => __('描述','salong'),
    'desc' => __('自定义描述，留空则获取摘要或截取文章第一段一定的字数。','salong'),
    'id'   => 'seo_description',
    'type' => 'textarea',
    'std'  => ''
);

$post_tab_meta[] = array(
    'type' => 'close'
);


/**第四个 TABS**/
$post_tab_meta[] = array(
    'name' => __('文章来源','salong'),
    'id'   => 'post_tab_form',
    'type' => 'open'
);

$post_tab_meta[] = array(
    'name' => __('网站名称','salong'),
    'desc' => __('输入文章来源的网站名称。','salong'),
    'id'   => 'from_name',
    'type' => 'text',
    'std'  => ''
);

$post_tab_meta[] = array(
    'name' => __('网站链接','salong'),
    'desc' => __('输入文章来源的网站链接。','salong'),
    'id'   => 'from_link',
    'type' => 'text',
    'std'  => ''
);

$post_tab_meta[] = array(
    'type' => 'close'
);

$post_tab_box = new ashuwp_postmeta_feild($post_tab_meta, $post_tab_conf);

/**
*
* 专题文章 TABS
*
**/

$topic_tab_conf = array(
    'title'   => __('文章选项','salong'),
    'id'      => 'topic_tab_box',
    'page'    => array('topic'),
    'context' => 'normal',
    'priority'=> 'low',
    'tab'     => true
);

$topic_tab_meta = array();

/**第一个 TABS**/
$topic_tab_meta[] = array(
    'name' => __('基础设置','salong'),
    'id'   => 'topic_tab_1',
    'type' => 'open'
);

$topic_tab_meta[] = array(
    'name'        => __('自定义缩略图','salong'),
    'desc'        => __('建议大小比例：460*280，注：获取缩略图的顺序为自定义缩略图，特色图像、专题文章第一张图、默认图片。','salong'),
    'button_text' => __('上传','salong'),
    'id'          => 'thumb',
    'type'        => 'upload',
    'std'         => ''
);

$topic_tab_meta[] = array(
    'name'        => __('专题海报','salong'),
    'desc'        => __('建议大小比例：1920*280。','salong'),
    'button_text' => __('上传','salong'),
    'id'          => 'top_thumb',
    'type'        => 'upload',
    'std'         => ''
);

$topic_tab_meta[] = array(
    'type' => 'close'
);

/**第二个 TABS**/
$topic_tab_meta[] = array(
    'name' => __('专题文章','salong'),
    'id'   => 'topic_tab_2',
    'type' => 'open'
);

$topic_tab_meta[] = array(
    'name' => __('专题文章 ID','salong'),
    'desc' => __('输入当前专题下需要显示的文章 ID，多个文章 ID 使用英文逗号隔开。','salong'),
    'id'   => 'topic_post_id',
    'type' => 'text',
    'std'  => ''
);

$topic_tab_meta[] = array(
    'name'        => __('专题下的文章','salong'),
    'desc'        => __('当前专题下的文章列表，点击可编辑文章。','salong'),
    'id'          => 'topic_post_list',
    'type'        => 'topic_post',
    'std'         => ''
);

$topic_tab_meta[] = array(
    'type' => 'close'
);

/**第二个 TABS**/
$topic_tab_meta[] = array(
    'name' => __('推荐阅读','salong'),
    'id'   => 'topic_tab_recommended',
    'type' => 'open'
);

$topic_tab_meta[] = array(
    'name' => __('标题','salong'),
    'desc' => __('推荐阅读标题。','salong'),
    'id'   => 'recommended_title',
    'type' => 'text',
    'std'  => __('推荐阅读','salong')
);

$topic_tab_meta[] = array(
    'name' => __('专题文章 ID','salong'),
    'desc' => __('需要推荐阅读的专题文章 ID，多个 ID 使用英文逗号隔开，不输入则不显示推荐阅读。','salong'),
    'id'   => 'recommended_id',
    'type' => 'text',
    'std'  => ''
);

$topic_tab_meta[] = array(
    'type' => 'close'
);

/**第三个 TABS**/
$topic_tab_meta[] = array(
    'name' => __('SEO 选项','salong'),
    'id'   => 'topic_tab_3',
    'type' => 'open'
);

$topic_tab_meta[] = array(
    'name' => __('标题','salong'),
    'desc' => __('自定义标题。','salong'),
    'id'   => 'seo_title',
    'type' => 'text',
    'std'  => ''
);

$topic_tab_meta[] = array(
    'name' => __('关键词','salong'),
    'desc' => __('自定义关键词，多个使用英文逗号隔开，不输入则获取标签做为关键词。','salong'),
    'id'   => 'seo_tag',
    'type' => 'text',
    'std'  => ''
);

$topic_tab_meta[] = array(
    'name' => __('描述','salong'),
    'desc' => __('自定义描述，留空则获取摘要或截取文章第一段一定的字数。','salong'),
    'id'   => 'seo_description',
    'type' => 'textarea',
    'std'  => ''
);

$topic_tab_meta[] = array(
    'type' => 'close'
);

$topic_tab_box = new ashuwp_postmeta_feild($topic_tab_meta, $topic_tab_conf);

/**
*
* 下载文章 TABS
*
**/

$download_tab_conf = array(
    'title'   => __('文章选项','salong'),
    'id'      => 'download_tab_box',
    'page'    => array('download'),
    'context' => 'normal',
    'priority'=> 'low',
    'tab'     => true
);

$download_tab_meta = array();

/**第一个 TABS**/
$download_tab_meta[] = array(
    'name' => __('基础设置','salong'),
    'id'   => 'download_tab_first',
    'type' => 'open'
);

$download_tab_meta[] = array(
    'name'        => __('自定义缩略图','salong'),
    'desc'        => __('建议大小比例：460*280，注：获取缩略图的顺序为自定义缩略图，特色图像、下载文章第一张图、默认图片。','salong'),
    'button_text' => __('上传','salong'),
    'id'          => 'thumb',
    'type'        => 'upload',
    'std'         => ''
);

$download_tab_meta[] = array(
    'name'        => __('下载文章顶部缩略图','salong'),
    'desc'        => __('最大宽度为：840，高度不限。','salong'),
    'button_text' => __('上传','salong'),
    'id'          => 'top_thumb',
    'type'        => 'upload',
    'std'         => ''
);

$download_tab_meta[] = array(
    'type' => 'close'
);


/**第二个 TABS**/
$download_tab_meta[] = array(
    'name' => __('下载设置','salong'),
    'id'   => 'download_tab_second',
    'type' => 'open'
);

$download_tab_meta[] = array(
    'name' => __('添加简码','salong'),
    'desc' => __('设置完以下参数，需要将简码 [download_code] 添加到编辑器中（想要显示下载参数的位置）。','salong'),
    'id'      => 'download_title',
    'type'    => 'title'
);

$download_tab_meta[] = array(
    'name' => __('下载参数','salong'),
    'id'   => 'download_info',
    'std'  => '',
    'subtype' => array(
        array(
            'name' => __('名称','salong'),
            'desc' => __('输入名称，比如：软件名称。','salong'),
            'id'   => 'info_title',
            'type' => 'text',
            'std'  => ''
        ),
        array(
            'name' => __('参数','salong'),
            'desc' => __('输入参数，比如：Sketch。','salong'),
            'id'   => 'info_value',
            'type' => 'text',
            'std'  => ''
        ),
    ),
    'multiple' => true,
    'type'     => 'group'
);

$download_tab_meta[] = array(
    'name' => __('下载地址','salong'),
    'id'   => 'download_link',
    'std'  => '',
    'subtype' => array(
        array(
            'name' => __('名称','salong'),
            'desc' => __('输入名称，比如：MAC 版。','salong'),
            'id'   => 'link_title',
            'type' => 'text',
            'std'  => ''
        ),
        array(
            'name' => __('地址','salong'),
            'desc' => __('输入地址，比如：https://www.sketchapp.com/download/，如果直接输入文件链接，请关闭『主题选项——优化——外链跳转』功能，同时也不会记录下载次数。','salong'),
            'id'   => 'link_value',
            'type' => 'text',
            'std'  => ''
        ),
    ),
    'multiple' => true,
    'type'     => 'group'
);

$download_tab_meta[] = array(
    'name' => __('官网地址','salong'),
    'desc' => __('输入官网地址，需要带上 https:// 或者 http://。','salong'),
    'id'   => 'link_home',
    'type' => 'text',
    'std'  => ''
);

if (class_exists('woocommerce')){
$download_tab_meta[] = array(
    'name' => __('付费下载','salong'),
    'desc' => __('输入产品 ID，只有购买了该产品才能下载，不输入则不需要付费即可下载。','salong'),
    'id'   => 'product_id',
    'type' => 'text',
    'std'  => ''
);
}

$download_tab_meta[] = array(
  'type' => 'close'
);

/**第二个 TABS**/
$download_tab_meta[] = array(
    'name' => __('推荐阅读','salong'),
    'id'   => 'download_tab_recommended',
    'type' => 'open'
);

$download_tab_meta[] = array(
    'name' => __('标题','salong'),
    'desc' => __('推荐阅读标题。','salong'),
    'id'   => 'recommended_title',
    'type' => 'text',
    'std'  => __('推荐阅读','salong')
);

$download_tab_meta[] = array(
    'name' => __('下载文章 ID','salong'),
    'desc' => __('需要推荐阅读的下载文章 ID，多个 ID 使用英文逗号隔开，不输入则不显示推荐阅读。','salong'),
    'id'   => 'recommended_id',
    'type' => 'text',
    'std'  => ''
);

$download_tab_meta[] = array(
    'type' => 'close'
);

/**第三个 TABS**/
$download_tab_meta[] = array(
    'name' => __('SEO 选项','salong'),
    'id'   => 'download_tab_third',
    'type' => 'open'
);

$download_tab_meta[] = array(
    'name' => __('标题','salong'),
    'desc' => __('自定义标题。','salong'),
    'id'   => 'seo_title',
    'type' => 'text',
    'std'  => ''
);

$download_tab_meta[] = array(
    'name' => __('关键词','salong'),
    'desc' => __('自定义关键词，多个使用英文逗号隔开，不输入则获取标签做为关键词。','salong'),
    'id'   => 'seo_tag',
    'type' => 'text',
    'std'  => ''
);

$download_tab_meta[] = array(
    'name' => __('描述','salong'),
    'desc' => __('自定义描述，留空则获取摘要或截取文章第一段一定的字数。','salong'),
    'id'   => 'seo_description',
    'type' => 'textarea',
    'std'  => ''
);

$download_tab_meta[] = array(
    'type' => 'close'
);

$download_tab_box = new ashuwp_postmeta_feild($download_tab_meta, $download_tab_conf);

/**
*
* 视频文章 TABS
*
**/

$video_tab_conf = array(
    'title'   => __('文章选项','salong'),
    'id'      => 'video_tab_box',
    'page'    => array('video'),
    'context' => 'normal',
    'priority'=> 'low',
    'tab'     => true
);

$video_tab_meta = array();

/**视频设置**/
$video_tab_meta[] = array(
    'name' => __('视频设置','salong'),
    'id'   => 'video_tab_general',
    'type' => 'open'
);

$video_tab_meta[] = array(
    'name' => __('视频设置说明','salong'),
    'desc' => __('主题集成了优酷，阿里云视频点播和 mediaelement.js HTML5 视频三个视频播放器，此 TAB 为通用设置，后三个 TAB 请选择其一进行设置。','salong'),
    'id'      => 'video_title',
    'type'    => 'title'
);

$video_tab_meta[] = array(
    'name'        => __('自定义缩略图','salong'),
    'desc'        => __('建议大小比例：460*280，获取缩略图的顺序为自定义缩略图、特色图像、视频文章第一张图、默认图片。<br>注：如果设置了优酷视频，此参数在发布与更新文章时会自动获取，阿里云和 HTML5 视频需要单独设置此选项。','salong'),
    'button_text' => __('上传','salong'),
    'id'          => 'thumb',
    'type'        => 'upload',
    'std'         => ''
);

$video_tab_meta[] = array(
    'name' => __('视频时长','salong'),
    'desc' => __('格式：1时36分12秒.<br>注：优酷和阿里云视频在发布和更新文章时会自动获取，HTML5视频需要手动输入。','salong'),
    'id'   => 'time',
    'type' => 'text',
    'std'  => ''
);

$video_tab_meta[] = array(
    'name' => __('视频高度','salong'),
    'desc' => __('输入视频高度，默认675，因为视频宽度是1200，标准的1080比例来设置。<br>注：阿里云点播视频不需要设置高度，是自动的。','salong'),
    'id'   => 'video_height',
    'type' => 'text',
    'std'  => ''
);

if (class_exists('woocommerce')){
$video_tab_meta[] = array(
    'name' => __('付费查看','salong'),
    'desc' => __('输入产品 ID，只有购买了该产品才能查看当前视频，不输入则不需要付费即可查看视频。','salong'),
    'id'   => 'product_id',
    'type' => 'text',
    'std'  => ''
);
}

$video_tab_meta[] = array(
    'name'    => __('自动播放','salong'),
    'desc'    => __('默认不自动播放，勾选则自动播放。','salong'),
    'id'      => 'video_auto',
    'type'    => 'checkbox',
    'subtype' => array(
        'on' => __('启用','salong')
    ),
    'std'     => ''
);

$video_tab_meta[] = array(
    'name'    => __('目录列表','salong'),
    'desc'    => __('默认不显示，勾选则显示当前视频文章所属分类下的所有文章列表。','salong'),
    'id'      => 'video_list',
    'type'    => 'checkbox',
    'subtype' => array(
        'on' => __('显示','salong')
    ),
    'std'     => ''
);

$video_tab_meta[] = array(
  'type' => 'close'
);

/**优酷视频**/
$video_tab_meta[] = array(
    'name' => __('优酷视频','salong'),
    'id'   => 'video_tab_youku',
    'type' => 'open'
);

$video_tab_meta[] = array(
    'name' => __('视频 ID','salong'),
    'desc' => __('输入优酷视频 ID。','salong'),
    'id'   => 'youku_id',
    'type' => 'text',
    'std'  => ''
);

$video_tab_meta[] = array(
  'type' => 'close'
);

/**阿里云视频**/
$video_tab_meta[] = array(
    'name' => __('阿里云视频','salong'),
    'id'   => 'video_tab_ali',
    'type' => 'open'
);

$video_tab_meta[] = array(
    'name' => __('阿里云视频 ID','salong'),
    'desc' => __('输入阿里云视频 ID。','salong'),
    'id'   => 'ali_id',
    'type' => 'text',
    'std'  => ''
);

$video_tab_meta[] = array(
  'type' => 'close'
);

/**HTML5视频**/
$video_tab_meta[] = array(
    'name' => __('HTML5视频','salong'),
    'id'   => 'video_tab_html5',
    'type' => 'open'
);

$video_tab_meta[] = array(
    'name'        => __('MP4视频地址','salong'),
    'desc'        => __('输入MP4视频地址。','salong'),
    'id'          => 'source',
    'button_text' => __('上传','salong'),
    'type'        => 'upload',
    'std'         => ''
);

$video_tab_meta[] = array(
    'name'        => __('视频封面','salong'),
    'desc'        => __('请输入 HTML5 视频封面图片。','salong'),
    'button_text' => __('上传','salong'),
    'id'          => 'poster_html5',
    'type'        => 'upload',
    'std'         => ''
);

$video_tab_meta[] = array(
    'name' => __('字幕设置','salong'),
    'desc' => __('字幕只能上传到网站目录下，不能托管在第三方平台，最好使用 .srt、.vtt 后缀名的字幕文件。字幕如果乱码，请将字幕文件转换成 utf-8 格式的文件。','salong'),
    'id'      => 'subtitles_title',
    'type'    => 'title'
);

$video_tab_meta[] = array(
    'name' => __('字幕','salong'),
    'id'   => 'subtitles',
    'std'  => '',
    'subtype' => array(
        array(
            'name' => __('语言代码','salong'),
            'desc' => __('输入字幕语言代码，比如：zh。语言代码请点击<a href="https://baike.baidu.com/item/语言代码/6594123?fr=aladdin" target="_blank">这里</a>查看，其中简体中文为：zh-cn，繁体中文为：zh-tw','salong'),
            'id'   => 'lang',
            'type' => 'text',
            'std'  => ''
        ),
        array(
            'name'        => __('字幕','salong'),
            'desc'        => __('输入字幕地址或者上传字幕。','salong'),
            'button_text' => __('上传','salong'),
            'id'          => 'value',
            'type'        => 'upload',
            'std'         => ''
        ),
    ),
    'multiple' => true,
    'type'     => 'group'
);

$video_tab_meta[] = array(
  'type' => 'close'
);

/**第二个 TABS**/
$video_tab_meta[] = array(
    'name' => __('推荐阅读','salong'),
    'id'   => 'video_tab_recommended',
    'type' => 'open'
);

$video_tab_meta[] = array(
    'name' => __('标题','salong'),
    'desc' => __('推荐阅读标题。','salong'),
    'id'   => 'recommended_title',
    'type' => 'text',
    'std'  => __('推荐阅读','salong')
);

$video_tab_meta[] = array(
    'name' => __('视频文章 ID','salong'),
    'desc' => __('需要推荐阅读的视频文章 ID，多个 ID 使用英文逗号隔开，不输入则不显示推荐阅读。','salong'),
    'id'   => 'recommended_id',
    'type' => 'text',
    'std'  => ''
);

$video_tab_meta[] = array(
    'type' => 'close'
);

/**SEO 选项**/
$video_tab_meta[] = array(
    'name' => __('SEO 选项','salong'),
    'id'   => 'video_tab_seo',
    'type' => 'open'
);

$video_tab_meta[] = array(
    'name' => __('标题','salong'),
    'desc' => __('自定义标题。','salong'),
    'id'   => 'seo_title',
    'type' => 'text',
    'std'  => ''
);

$video_tab_meta[] = array(
    'name' => __('关键词','salong'),
    'desc' => __('自定义关键词，多个使用英文逗号隔开，不输入则获取标签做为关键词。','salong'),
    'id'   => 'seo_tag',
    'type' => 'text',
    'std'  => ''
);

$video_tab_meta[] = array(
    'name' => __('描述','salong'),
    'desc' => __('自定义描述，留空则获取摘要或截取文章第一段一定的字数。','salong'),
    'id'   => 'seo_description',
    'type' => 'textarea',
    'std'  => ''
);

$video_tab_meta[] = array(
    'type' => 'close'
);

$video_tab_box = new ashuwp_postmeta_feild($video_tab_meta, $video_tab_conf);

/**
*
* 产品文章 TABS
*
**/

$product_tab_conf = array(
    'title'   => __('文章选项','salong'),
    'id'      => 'product_tab_box',
    'page'    => array('product'),
    'context' => 'normal',
    'priority'=> 'low',
    'tab'     => true
);

$product_tab_meta = array();

/**产品设置**/
$product_tab_meta[] = array(
    'name' => __('产品设置','salong'),
    'id'   => 'product_tab_general',
    'type' => 'open'
);

$product_tab_meta[] = array(
    'name'        => __('自定义缩略图','salong'),
    'desc'        => __('建议大小比例：460*280，获取缩略图的顺序为自定义自定义缩略图、特色图像、产品文章第一张图、默认图片。','salong'),
    'button_text' => __('上传','salong'),
    'id'          => 'thumb',
    'type'        => 'upload',
    'std'         => ''
);

$product_tab_meta[] = array(
  'type' => 'close'
);

/**第五个 TABS**/
$product_tab_meta[] = array(
    'name' => __('筛选信息','salong'),
    'id'   => 'project_tab_sift',
    'type' => 'open'
);

$sift_array = salong_get_sift_array(); //获取筛选数组

$product_tab_meta[] = array(
    'name'    => __('价格','salong'),
    'id'      => 'price',
    'desc'    => '',
    'std'     => 'p2',
    'subtype' => $sift_array['price'], //分辨率选项
    'type'    => 'radio',
);

$product_tab_meta[] = array(
    'type' => 'close'
);

/**SEO 选项**/
$product_tab_meta[] = array(
    'name' => __('SEO 选项','salong'),
    'id'   => 'product_tab_seo',
    'type' => 'open'
);

$product_tab_meta[] = array(
    'name' => __('标题','salong'),
    'desc' => __('自定义标题。','salong'),
    'id'   => 'seo_title',
    'type' => 'text',
    'std'  => ''
);

$product_tab_meta[] = array(
    'name' => __('关键词','salong'),
    'desc' => __('自定义关键词，多个使用英文逗号隔开，不输入则获取标签做为关键词。','salong'),
    'id'   => 'seo_tag',
    'type' => 'text',
    'std'  => ''
);

$product_tab_meta[] = array(
    'name' => __('描述','salong'),
    'desc' => __('自定义描述，留空则获取摘要或截取文章第一段一定的字数。','salong'),
    'id'   => 'seo_description',
    'type' => 'textarea',
    'std'  => ''
);

$product_tab_meta[] = array(
    'type' => 'close'
);

$product_tab_box = new ashuwp_postmeta_feild($product_tab_meta, $product_tab_conf);


/**
*
* 问答选项
*
**/
/*****Meta Box********/
$qa_tab_conf = array(
    'title'   => __('SEO选项','salong'),
    'id'      => 'qa_tab_box',
    'page'    => array('dwqa-question'),
    'context' => 'normal',
    'priority'=> 'low',
    'tab'     => true
);

$qa_tab_meta[] = array(
    'name' => __('标题','salong'),
    'desc' => __('自定义标题。','salong'),
    'id'   => 'seo_title',
    'type' => 'text',
    'std'  => ''
);

$qa_tab_meta[] = array(
    'name' => __('关键词','salong'),
    'desc' => __('自定义关键词，多个使用英文逗号隔开，不输入则获取标签做为关键词。','salong'),
    'id'   => 'seo_tag',
    'type' => 'text',
    'std'  => ''
);

$qa_tab_meta[] = array(
    'name' => __('描述','salong'),
    'desc' => __('自定义描述，留空则获取摘要或截取文章第一段一定的字数。','salong'),
    'id'   => 'seo_description',
    'type' => 'textarea',
    'std'  => ''
);

$qa_tab_box = new ashuwp_postmeta_feild($qa_tab_meta, $qa_tab_conf);


/**
*
* 分类 META BOX
*
**/
/***** 所有分类字段 ******/

$category_meta = array();
$category_cof = array('category','dcat','tcat','vcat','product_cat');

$category_meta[] = array(
    'name' => __('SEO 标题','salong'),
    'desc' => __('输入 SEO 标题。','salong'),
    'id'   => 'seo_title',
    'type' => 'text',
    'std'  => ''
);

$category_meta[] = array(
    'name' => __('SEO 关键字','salong'),
    'desc' => __('输入 SEO 关键字，多个关键字用英文逗号隔开。','salong'),
    'id'   => 'seo_tag',
    'type' => 'text',
    'std'  => ''
);

$category_meta[] = array(
    'name'        => __('面包屑背景图片','salong'),
    'desc'        => __('不添加则不显示该模块，建议大小比例：1920*280。','salong'),
    'button_text' => __('上传','salong'),
    'id'          => 'thumb',
    'type'        => 'upload',
    'std'         => ''
);

$category_meta[] = array(
    'name' => __('面包屑背景明暗度','salong'),
    'desc' => __('输入面包屑背景明暗度，值为0-9，不输入则为默认图片明暗度。','salong'),
    'id'   => 'thumb_opacity',
    'type' => 'text',
    'std'  => ''
);

$category_feild = new ashuwp_termmeta_feild($category_meta, $category_cof);

/***** 问答分类字段 ******/

$qa_meta = array();
$qa_cof = array('dwqa-question_category');

$qa_meta[] = array(
    'name' => __('SEO 标题','salong'),
    'desc' => __('输入 SEO 标题。','salong'),
    'id'   => 'seo_title',
    'type' => 'text',
    'std'  => ''
);

$qa_meta[] = array(
    'name' => __('SEO 关键字','salong'),
    'desc' => __('输入 SEO 关键字，多个关键字用英文逗号隔开。','salong'),
    'id'   => 'seo_tag',
    'type' => 'text',
    'std'  => ''
);

$qa_feild = new ashuwp_termmeta_feild($qa_meta, $qa_cof);
