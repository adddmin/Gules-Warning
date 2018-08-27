<?php
global $salong;
header('Content-type: text/html; charset=utf-8');
load_theme_textdomain('salong', get_template_directory() . '/languages');
define(ThemeVersion, wp_get_theme()->get('Version'));
define(ThemeName, wp_get_theme()->get('Name'));
/* if (!class_exists('AM_License_Menu')) {
	require_once get_stylesheet_directory() . '/includes/salong-license.php';
	AM_License_Menu::instance(__FILE__, ThemeName, ThemeVersion, 'theme', 'https://salongweb.com/');
}
function is_local_server()
{
	return in_array(trim($_SERVER['HTTP_HOST']), array('localhost', '127.0.0.1'));
}
if (trim($_SERVER['HTTP_HOST']) != is_local_server() && !is_admin()) {
	$mnews_data = get_option('mnews_data');
	if (get_option('mnews_activated') != 'Activated' || !$mnews_data['api_key'] || !$mnews_data['activation_email']) {
		wp_die('购买正版主题请访问：<a href="https://salongweb.com/shop" target="_blank">萨龙网络主题商城</a>，如果您已经购买，请登录<a href="https://salongweb.com" target="_blank">萨龙网络</a>获取 API 许可密钥，最后在您的网站后台——设置中激活主题，感谢您的支持！');
	}
} */
if (!class_exists('ReduxFramework') && file_exists(get_template_directory() . '/admin/ReduxCore/framework.php')) {
	require_once get_template_directory() . '/admin/ReduxCore/framework.php';
}
if (!isset($redux_demo) && file_exists(get_template_directory() . '/admin/config.php')) {
	require_once get_template_directory() . '/admin/config.php';
}
if ($salong['thumb_mode'] == 'timthumb') {
	require_once get_template_directory() . '/includes/thumb.php';
} else {
	require_once get_template_directory() . '/includes/wpauto.php';
}
require_once get_template_directory() . '/includes/post-types.php';
require_once get_template_directory() . '/includes/shortcodes/shortcodespanel.php';
require_once get_template_directory() . '/includes/shortcodes/shortcodes.php';
require_once get_template_directory() . '/includes/notify.php';
require_once get_template_directory() . '/includes/comment-ajax.php';
if (is_admin()) {
	require_once get_template_directory() . '/includes/tutorial.php';
	if ($salong['switch_xiongzhang_submit']) {
		require_once get_template_directory() . '/includes/FanlySubmit.php';
	}
}
require_once get_template_directory() . '/includes/sift.php';
require get_template_directory() . '/includes/metabox/framework-core.php';
require get_template_directory() . '/includes/metabox/meta-box.php';
if ($salong['switch_messages']) {
	require_once get_template_directory() . '/includes/messages/index.php';
}
if ($salong['switch_like_btn']) {
	require_once get_template_directory() . '/includes/post-like.php';
}
if ($salong['switch_follow_btn']) {
	require_once get_template_directory() . '/includes/follow.php';
}
if (class_exists('woocommerce')) {
	require_once get_template_directory() . '/woocommerce/woo-config.php';
}
require_once get_template_directory() . '/includes/sidebars.php';
require_once get_template_directory() . '/includes/widgets/widget-post.php';
require_once get_template_directory() . '/includes/widgets/widget-follow-post.php';
require_once get_template_directory() . '/includes/widgets/widget-download.php';
require_once get_template_directory() . '/includes/widgets/widget-video.php';
require_once get_template_directory() . '/includes/widgets/widget-topic.php';
require_once get_template_directory() . '/includes/widgets/widget-about.php';
require_once get_template_directory() . '/includes/widgets/widget-tag.php';
require_once get_template_directory() . '/includes/widgets/widget-comments.php';
require_once get_template_directory() . '/includes/widgets/widget-word.php';
require_once get_template_directory() . '/includes/widgets/widget-user.php';
function unregister_default_widgets()
{
	unregister_widget('Akismet_Widget');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Search');
	unregister_widget('WP_Widget_Text');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Nav_Menu_Widget');
	unregister_widget('WP_Widget_Media_Gallery');
}
add_action('widgets_init', 'unregister_default_widgets', 11);
register_nav_menus(array('header-menu' => __('导航菜单', 'salong'), 'footer-menu' => __('页脚菜单', 'salong')));
function Salong_header_nav_fallback()
{
	echo '<nav class="header_menu"><ul class="empty"><li><a href="' . get_option('home') . '/wp-admin/nav-menus.php?action=locations">' . __('请在 "后台——外观——菜单" 添加导航菜单', 'salong') . '</a></ul></li></nav>';
}
function Salong_footer_nav_fallback()
{
	echo '<nav class="footer_menu"><ul class="empty"><li><a href="' . get_option('home') . '/wp-admin/nav-menus.php?action=locations">' . __('请在 "后台——外观——菜单" 添加页脚菜单', 'salong') . '</a></ul></li></nav>';
}
if (!function_exists('salong_script') && !is_admin()) {
	function salong_script()
	{
		global $salong, $post;
		wp_reset_query();
		wp_enqueue_style('style', get_stylesheet_uri(), array(), '2017.03.18');
		wp_enqueue_style('main', get_template_directory_uri() . '/stylesheets/main.css', false, '1.0', false);
		wp_deregister_script('jquery');
		wp_deregister_style('font-awesome');
		wp_enqueue_script('jquery', get_template_directory_uri() . '/js/jquery.min.js', false, '3.1.1', false);
		if ($salong['switch_header_show_hide']) {
			wp_enqueue_script('headroom', get_template_directory_uri() . '/js/headroom.min.js', false, '0.9.4', false);
		}
		if (is_home()) {
			wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.min.js', false, '1.1', false);
		}
		if (!is_singular(array('post', 'video', 'download', 'product'))) {
			wp_enqueue_script('ias', get_template_directory_uri() . '/js/jquery-ias.min.js', false, '2.2.2', true);
		}
		wp_enqueue_script('scrollchaser', get_template_directory_uri() . '/js/jquery.scrollchaser.min.js', false, '2.2.2', true);
		if ($salong['switch_lazyload']) {
			wp_enqueue_script('lazyload', get_template_directory_uri() . '/js/jquery.lazyload.min.js', false, '1.9.3', true);
		}
		if (in_array('gb2big5', $salong['side_metas'])) {
			wp_enqueue_script('gb2big5', get_template_directory_uri() . '/js/gb2big5.js', false, '1.0', true);
		}
		$_var_0 = $_GET['tab'];
		if ($_var_0 == 'edit-profile' || $_var_0 == 'edit' || $_var_0 == 'edit-profile-extension') {
			wp_enqueue_media();
		}
		if ($_var_0 != 'contribute' && $_var_0 != 'edit-profile' && $_var_0 != 'edit' && $_var_0 != 'edit-profile-extension') {
			wp_deregister_script('mediaelement');
		}
		if ($_var_0 == 'edit-profile-extension') {
			wp_enqueue_script('cityselect', get_template_directory_uri() . '/js/cityselect.js', false, '1.0', false);
		}
		if ($_var_0 == 'message') {
			wp_enqueue_script('jquery-ui-autocomplete');
		}
		wp_enqueue_script('custom', get_template_directory_uri() . '/js/custom-min.js', false, '1.0', true);
		if (!function_exists('salong_css_code')) {
			function salong_css_code()
			{
				global $salong;
				$_var_1 = $salong['css_code'];
				if (!empty($_var_1)) {
					$_var_2 = preg_replace('/\\s+/', ' ', $_var_1);
					$_var_3 = '<!-- Dynamic css -->
<style type="text/css">
' . $_var_2 . '
</style>';
					echo $_var_3;
				}
			}
		}
		add_action('wp_head', 'salong_css_code');
	}
	add_action('wp', 'salong_script');
}
function salong_single()
{
	global $salong, $post;
	if (is_singular()) {
		wp_enqueue_script('aliplayer-js', 'https://g.alicdn.com/de/prismplayer/2.5.0/aliplayer-h5-min.js', false, '2.5.0', false);
		wp_enqueue_style('aliplayer-css', 'https://g.alicdn.com/de/prismplayer/2.5.0/skins/default/aliplayer-min.css', false, '2.5.0', false);
		wp_enqueue_script('fancybox', get_template_directory_uri() . '/js/jquery.fancybox.min.js', false, '3.0.6', true);
		wp_enqueue_style('fancybox', get_template_directory_uri() . '/stylesheets/jquery.fancybox.min.css', false, '3.0.6', 'screen');
		wp_enqueue_script('mediaelementplayer', get_template_directory_uri() . '/js/mediaelement-and-player.min.js', false, '4.2.9', true);
		wp_enqueue_script('mediaelement-zh-cn', get_template_directory_uri() . '/js/zh-cn.js', false, '4.2.9', true);
		wp_enqueue_script('mediaelement-demo', get_template_directory_uri() . '/js/mediaelement-min.js', false, '4.2.9', true);
		wp_enqueue_style('mediaelementplayer', get_template_directory_uri() . '/stylesheets/mediaelementplayer.min.css', false, '4.2.9', 'screen');
		if ($salong['switch_highlight']) {
			wp_enqueue_style('highlight', get_template_directory_uri() . '/stylesheets/highlight.css', false, '3.0.3', 'screen');
		}
	}
	if (is_single()) {
		$_var_4 = get_post_type();
		$_var_5 = $salong['switch_' . $_var_4 . '_tagshare'];
		if (!$_var_5) {
			return;
		}
		wp_enqueue_script('qrcode-js', get_template_directory_uri() . '/js/jquery.qrcode.min.js', false, '1.0', false);
	}
}
add_action('wp_enqueue_scripts', 'salong_single');
function salong_breadcrumbs()
{
	global $salong, $post;
	if ($salong['switch_crumbs'] == 0 && !is_admin()) {
		return;
	}
	$_var_6 = '&nbsp;' . $salong['delimiter'] . '&nbsp;';
	$_var_7 = '<span class="current">';
	$_var_8 = '</span>';
	if (!is_home() && !is_front_page() || is_paged()) {
		global $post;
		echo '<article class="crumbs">';
		if (!is_singular(array('post', 'download', 'video'))) {
			echo '<div class="wrapper">';
		}
		$_var_9 = home_url();
		echo ' <a itemprop="breadcrumb" href="' . $_var_9 . '">' . svg_home() . __('首页', 'salong') . '</a>' . $_var_6 . '';
		if (is_category()) {
			global $wp_query;
			$_var_10 = $wp_query->get_queried_object();
			$_var_11 = $_var_10->term_id;
			$_var_12 = get_categories(array('include' => $_var_11, 'taxonomy' => 'any'));
			$_var_13 = isset($_var_12->parent);
			$_var_14 = get_categories(array('include' => $_var_13, 'taxonomy' => 'any'));
			$_var_15 = get_page_id_from_template('template-post.php');
			echo '<a itemprop="breadcrumb" href="' . get_permalink($_var_15) . '">' . get_the_title($_var_15) . '</a>' . $_var_6;
			if (isset($_var_12->parent) != 0) {
				$_var_16 = get_category_parents($_var_14, true, ' ' . $_var_6 . ' ');
				echo $_var_16 = str_replace('<a', '<a itemprop="breadcrumb"', $_var_16);
			}
			echo $_var_7 . '' . single_cat_title('', false) . '' . $_var_8;
		} else {
			if (is_tax()) {
				$_var_17 = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
				$_var_18 = $_var_17->parent;
				if (is_tax('tcat') || is_tax('ttag')) {
					$_var_15 = get_page_id_from_template('template-topic.php');
				} else {
					if (is_tax('dcat') || is_tax('dtag')) {
						$_var_15 = get_page_id_from_template('template-download.php');
					} else {
						if (is_tax('vcat') || is_tax('vtag')) {
							$_var_15 = get_page_id_from_template('template-video.php');
						} else {
							if (is_tax('product_cat') || is_tax('product_tag')) {
								$_var_15 = wc_get_page_id('shop');
							}
						}
					}
				}
				echo '<a itemprop="breadcrumb" href="' . get_permalink($_var_15) . '">' . get_the_title($_var_15) . '</a>' . $_var_6;
				while ($_var_18) {
					$_var_19[] = $_var_18;
					$_var_20 = get_term_by('id', $_var_18, get_query_var('taxonomy'));
					$_var_18 = $_var_20->parent;
				}
				if (!empty($_var_19)) {
					$_var_19 = array_reverse($_var_19);
					foreach ($_var_19 as $_var_18) {
						$_var_21 = get_term_by('id', $_var_18, get_query_var('taxonomy'));
						$_var_22 = get_post_type();
						$_var_23 = 'category';
						$_var_24 = get_bloginfo('url') . '/' . $_var_22 . '-' . $_var_23 . '/' . $_var_21->slug;
						echo '<a href="' . $_var_24 . '">' . $_var_21->name . '</a>' . $_var_6;
					}
				}
				echo $_var_7 . $_var_17->name . $_var_8;
			} else {
				if (is_day()) {
					echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $_var_6 . '';
					echo '<a itemprop="breadcrumb"  href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $_var_6 . '';
					echo $_var_7 . get_the_time('d') . $_var_8;
				} elseif (is_month()) {
					echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $_var_6 . '';
					echo $_var_7 . get_the_time('F') . $_var_8;
				} elseif (is_year()) {
					echo $_var_7 . get_the_time('Y') . $_var_8;
				} elseif (is_single() && !is_attachment()) {
					$_var_25 = get_post_type();
					if ($_var_25 == 'topic') {
						$_var_26 = 'tcat';
					} else {
						if ($_var_25 == 'download') {
							$_var_26 = 'dcat';
						} else {
							if ($_var_25 == 'video') {
								$_var_26 = 'vcat';
							} else {
								$_var_26 = 'category';
							}
						}
					}
					if (is_singular('product')) {
						global $salong;
						echo '<a itemprop="breadcrumb" href="' . get_page_link(wc_get_page_id('shop')) . '">' . get_page(wc_get_page_id('shop'))->post_title . '</a>' . $_var_6 . '';
						echo the_terms($post->ID, 'product_cat', '') . $_var_6;
					} else {
						if (is_singular($_var_25)) {
							global $salong;
							echo '<a itemprop="breadcrumb" href="' . get_page_link(get_page_id_from_template('template-' . $_var_25 . '.php')) . '">' . get_page(get_page_id_from_template('template-' . $_var_25 . '.php'))->post_title . '</a>' . $_var_6;
							echo the_terms($post->ID, $_var_26, '') . $_var_6;
						}
					}
					echo $_var_7 . __('正文', 'salong') . $_var_8;
				} elseif (is_attachment()) {
					$_var_18 = get_post($post->post_parent);
					$_var_27 = get_the_category($_var_18->ID);
					$_var_27 = $_var_27[0];
					echo '<a itemprop="breadcrumb" href="' . get_permalink($_var_18) . '">' . $_var_18->post_title . '</a>' . $_var_6 . '';
					echo $_var_7 . get_the_title() . $_var_8;
				} else {
					if (is_page() && !$post->post_parent) {
						echo $_var_7 . get_the_title() . $_var_8;
					} elseif (is_page() && $post->post_parent) {
						$_var_28 = $post->post_parent;
						$_var_29 = array();
						while ($_var_28) {
							$_var_30 = get_page($_var_28);
							$_var_29[] = '<a itemprop="breadcrumb" href="' . get_permalink($_var_30->ID) . '">' . get_the_title($_var_30->ID) . '</a>';
							$_var_28 = $_var_30->post_parent;
						}
						$_var_29 = array_reverse($_var_29);
						foreach ($_var_29 as $_var_31) {
							echo $_var_31 . '' . $_var_6 . '';
						}
						echo $_var_7 . get_the_title() . $_var_8;
					} elseif (is_search()) {
						echo $_var_7;
						printf(__('%s 的搜索结果', 'salong'), get_search_query());
						echo $_var_8;
					} elseif (is_tag()) {
						echo $_var_7;
						printf(__('%s 的标签存档', 'salong'), single_tag_title('', false));
						echo $_var_8;
					} elseif (is_author()) {
						global $author;
						$_var_32 = get_userdata($author);
						echo $_var_7;
						printf(__('%s 的个人中心', 'salong'), $_var_32->display_name);
						echo $_var_8;
					} elseif (is_404()) {
						echo $_var_7;
						__('404公益页面', 'salong');
						echo $_var_8;
					} else {
						if (class_exists('woocommerce')) {
							if (is_shop()) {
								echo $_var_7 . woocommerce_page_title() . $_var_8;
							}
						}
					}
				}
			}
		}
		if (get_query_var('paged')) {
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
				echo sprintf(__('（第%s页）', 'salong'), get_query_var('paged'));
			}
		}
		if (is_page_template('template-post.php')) {
			echo sprintf(__('<span class="count">共<b>%s</b>篇</span>', 'salong'), wp_count_posts('post')->publish);
		} else {
			if (is_page_template('template-topic.php')) {
				echo sprintf(__('<span class="count">共<b>%s</b>篇</span>', 'salong'), wp_count_posts('topic')->publish);
			} else {
				if (is_page_template('template-download.php')) {
					echo sprintf(__('<span class="count">共<b>%s</b>篇</span>', 'salong'), wp_count_posts('download')->publish);
				} else {
					if (is_page_template('template-video.php')) {
						echo sprintf(__('<span class="count">共<b>%s</b>篇</span>', 'salong'), wp_count_posts('video')->publish);
					} else {
						if (is_singular('topic')) {
							global $post, $salong;
							$_var_33 = explode(',', get_post_meta($post->ID, 'topic_post_id', 'true'));
							foreach ($_var_33 as $_var_34 => $_var_35) {
								$_var_34++;
							}
							echo sprintf(__('<span class="count">共<b>%s</b>篇</span>', 'salong'), $_var_34);
						} else {
							if (is_search()) {
								global $wp_query;
								$_var_36 = $_GET['post_type'];
								echo sprintf(__('<span class="count">共<b>%s</b>篇</span>', 'salong'), $wp_query->found_posts);
							} else {
								if (is_category() || is_tax() || is_tag()) {
									$_var_27 = get_queried_object();
									$_var_37 = $_var_27->term_id;
									$_var_38 = $_var_27->taxonomy;
									echo sprintf(__('<span class="count">共<b>%s</b>篇</span>', 'salong'), salong_category_post_count($_var_37, $_var_38));
								} else {
									if (class_exists('woocommerce')) {
										if (is_shop() && !is_search()) {
											echo sprintf(__('<span class="count">共<b>%s</b>篇</span>', 'salong'), wp_count_posts('product')->publish);
										}
									}
								}
							}
						}
					}
				}
			}
		}
		if (!is_singular(array('post', 'download', 'video'))) {
			echo '</div>';
		}
		echo '</article>';
	}
}
function home_ad($_var_39)
{
	global $salong;
	$_var_40 = $salong['home_ad' . $_var_39];
	if ($_var_40) {
		echo '<section class="ad">';
		echo $_var_40;
		echo '</section>';
	}
}
function salong_ad($_var_41)
{
	global $salong;
	$_var_42 = $salong['ad_' . $_var_41];
	if ($_var_42) {
		echo '<section class="ad">';
		echo $_var_42;
		echo '</section>';
	}
}
include_once get_template_directory() . '/includes/aliyun-php-sdk/aliyun-php-sdk-core/Config.php';
use vod\Request\V20170321 as vod;
$regionId = 'cn-shanghai';
$access_key_id = $salong['access_key_id'];
$access_key_secret = $salong['access_key_secret'];
$profile = DefaultProfile::getProfile($regionId, $access_key_id, $access_key_secret);
$client_ali = new DefaultAcsClient($profile);
function salong_GetVideoPlayAuth($_var_43, $_var_44, $_var_45)
{
	$_var_46 = new vod\GetVideoPlayAuthRequest();
	$_var_46->setAcceptFormat('JSON');
	$_var_46->setRegionId($_var_44);
	$_var_46->setVideoId($_var_45);
	$_var_47 = $_var_43->getAcsResponse($_var_46);
	return $_var_47;
}
function salong_ali_video($_var_48, $_var_49)
{
	global $salong, $client_ali, $regionId;
	$_var_50 = salong_GetVideoPlayAuth($client_ali, $regionId, $_var_49);
	$_var_51 = get_object_vars($_var_50);
	$_var_52 = get_object_vars($_var_51['VideoMeta']);
	if ($_var_48 == 'CoverURL') {
		return $_var_52['CoverURL'];
	} else {
		if ($_var_48 == 'Duration') {
			return $_var_52['Duration'];
		} else {
			if ($_var_48 == 'PlayAuth') {
				return $_var_51['PlayAuth'];
			}
		}
	}
}
function getSslPage($_var_53)
{
	$_var_54 = curl_init();
	curl_setopt($_var_54, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($_var_54, CURLOPT_HEADER, false);
	curl_setopt($_var_54, CURLOPT_URL, $_var_53);
	curl_setopt($_var_54, CURLOPT_REFERER, $_var_53);
	curl_setopt($_var_54, CURLOPT_RETURNTRANSFER, true);
	$_var_55 = curl_exec($_var_54);
	curl_close($_var_54);
	return $_var_55;
}
function get_youku_video($_var_56)
{
	global $post, $salong;
	$_var_57 = $salong['client_youku'];
	$_var_58 = get_post_meta($post->ID, 'youku_id', true);
	$_var_59 = "https://api.youku.com/videos/show.json?video_id={$_var_58}&client_id={$_var_57}";
	$_var_60 = getSslPage($_var_59);
	if ($_var_60) {
		$_var_61 = json_decode($_var_60, true);
		$_var_62 = $_var_61['data'][0];
		if ($_var_56 == 'thumb') {
			return $_var_61['bigThumbnail'];
		} else {
			if ($_var_56 == 'duration') {
				return $_var_61['duration'];
			}
		}
	}
}
add_action('wp_enqueue_scripts', 'sl_enqueue_scripts');
function sl_enqueue_scripts()
{
	wp_enqueue_script('post-like', get_template_directory_uri() . '/js/post-like-min.js', array('jquery'), '0.5', false);
	wp_localize_script('post-like', 'simpleLikes', array('ajaxurl' => admin_url('admin-ajax.php'), 'like' => __('赞', 'salong'), 'unlike' => __('已赞', 'salong')));
}
add_action('wp_ajax_nopriv_process_simple_like', 'process_simple_like');
add_action('wp_ajax_process_simple_like', 'process_simple_like');
function process_simple_like()
{
	$_var_63 = isset($_REQUEST['nonce']) ? sanitize_text_field($_REQUEST['nonce']) : 0;
	if (!wp_verify_nonce($_var_63, 'simple-likes-nonce')) {
		die(__('Not permitted', 'salong'));
	}
	$_var_64 = isset($_REQUEST['disabled']) && $_REQUEST['disabled'] == true ? true : false;
	$_var_65 = isset($_REQUEST['is_comment']) && $_REQUEST['is_comment'] == 1 ? 1 : 0;
	$_var_66 = isset($_REQUEST['post_id']) && is_numeric($_REQUEST['post_id']) ? $_REQUEST['post_id'] : '';
	$_var_67 = array();
	$_var_68 = NULL;
	$_var_69 = 0;
	if ($_var_66 != '') {
		$_var_70 = $_var_65 == 1 ? get_comment_meta($_var_66, 'salong_comment_like_count', true) : get_post_meta($_var_66, 'salong_post_like_count', true);
		$_var_70 = isset($_var_70) && is_numeric($_var_70) ? $_var_70 : 0;
		if (!already_liked($_var_66, $_var_65)) {
			if (is_user_logged_in()) {
				$_var_71 = get_current_user_id();
				$_var_68 = post_user_likes($_var_71, $_var_66, $_var_65);
				if (function_exists('woocommerce_points_rewards_my_points')) {
					global $wc_points_rewards;
					$_var_72 = get_option('wc_points_rewards_like_points');
					WC_Points_Rewards_Manager::increase_points(get_current_user_id(), $_var_72, 'like', $_var_66);
				}
				if ($_var_65 == 1) {
					$_var_73 = get_user_option('salong_comment_like_count', $_var_71);
					$_var_73 = isset($_var_73) && is_numeric($_var_73) ? $_var_73 : 0;
					update_user_option($_var_71, 'salong_comment_like_count', ++$_var_73);
					if ($_var_68) {
						update_comment_meta($_var_66, 'salong_user_comment_liked', $_var_68);
					}
				} else {
					$_var_73 = get_user_option('salong_user_like_count', $_var_71);
					$_var_73 = isset($_var_73) && is_numeric($_var_73) ? $_var_73 : 0;
					update_user_option($_var_71, 'salong_user_like_count', ++$_var_73);
					if ($_var_68) {
						update_post_meta($_var_66, 'salong_user_liked', $_var_68);
					}
				}
			} else {
				$_var_74 = sl_get_ip();
				$_var_68 = post_ip_likes($_var_74, $_var_66, $_var_65);
				if ($_var_68) {
					if ($_var_65 == 1) {
						update_comment_meta($_var_66, 'salong_user_comment_IP', $_var_68);
					} else {
						update_post_meta($_var_66, 'salong_user_IP', $_var_68);
					}
				}
			}
			$_var_69 = ++$_var_70;
			$_var_75['status'] = 'liked';
			$_var_75['icon'] = get_liked_icon();
		} else {
			if (is_user_logged_in()) {
				$_var_71 = get_current_user_id();
				$_var_68 = post_user_likes($_var_71, $_var_66, $_var_65);
				if (function_exists('woocommerce_points_rewards_my_points')) {
					global $wc_points_rewards;
					$_var_72 = '-' . get_option('wc_points_rewards_like_points');
					WC_Points_Rewards_Manager::increase_points(get_current_user_id(), $_var_72, 'like', $_var_66);
				}
				if ($_var_65 == 1) {
					$_var_73 = get_user_option('salong_comment_like_count', $_var_71);
					$_var_73 = isset($_var_73) && is_numeric($_var_73) ? $_var_73 : 0;
					if ($_var_73 > 0) {
						update_user_option($_var_71, 'salong_comment_like_count', --$_var_73);
					}
				} else {
					$_var_73 = get_user_option('salong_user_like_count', $_var_71);
					$_var_73 = isset($_var_73) && is_numeric($_var_73) ? $_var_73 : 0;
					if ($_var_73 > 0) {
						update_user_option($_var_71, 'salong_user_like_count', --$_var_73);
					}
				}
				if ($_var_68) {
					$_var_76 = array_search($_var_71, $_var_68);
					unset($_var_68[$_var_76]);
					if ($_var_65 == 1) {
						update_comment_meta($_var_66, 'salong_user_comment_liked', $_var_68);
					} else {
						update_post_meta($_var_66, 'salong_user_liked', $_var_68);
					}
				}
			} else {
				$_var_74 = sl_get_ip();
				$_var_68 = post_ip_likes($_var_74, $_var_66, $_var_65);
				if ($_var_68) {
					$_var_77 = array_search($_var_74, $_var_68);
					unset($_var_68[$_var_77]);
					if ($_var_65 == 1) {
						update_comment_meta($_var_66, 'salong_user_comment_IP', $_var_68);
					} else {
						update_post_meta($_var_66, 'salong_user_IP', $_var_68);
					}
				}
			}
			$_var_69 = $_var_70 > 0 ? --$_var_70 : 0;
			$_var_75['status'] = 'unliked';
			$_var_75['icon'] = get_unliked_icon();
		}
		if ($_var_65 == 1) {
			update_comment_meta($_var_66, 'salong_comment_like_count', $_var_69);
			update_comment_meta($_var_66, 'salong_comment_like_modified', date('Y-m-d H:i:s'));
		} else {
			update_post_meta($_var_66, 'salong_post_like_count', $_var_69);
			update_post_meta($_var_66, 'salong_post_like_modified', date('Y-m-d H:i:s'));
		}
		$_var_75['count'] = get_like_count($_var_69);
		$_var_75['testing'] = $_var_65;
		if ($_var_64 == true) {
			if ($_var_65 == 1) {
				wp_redirect(get_permalink(get_the_ID()));
				die;
			} else {
				wp_redirect(get_permalink($_var_66));
				die;
			}
		} else {
			wp_send_json($_var_75);
		}
	}
}
add_action('admin_menu', 'salong_activate');
function salong_activate()
{
	global $wpdb;
	$_var_78 = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'pm (
		`id` bigint(20) NOT NULL auto_increment,
		`subject` text NOT NULL,
		`content` text NOT NULL,
		`type` varchar(20),
		`sender` int,
		`recipient` int,
        `from_to` tinytext,
		`date` datetime NOT NULL,
		`read` tinyint(1) NOT NULL,
		`deleted` tinyint(1) NOT NULL,
		PRIMARY KEY (`id`)
	) COLLATE utf8_general_ci;';
	$wpdb->query($_var_78);
}
function salong_process_new_follow()
{
	if (isset($_POST['user_id']) && isset($_POST['follow_id'])) {
		if (salong_follow_user($_POST['user_id'], $_POST['follow_id'])) {
			echo 'success';
		} else {
			echo 'failed';
		}
	}
	die;
}
add_action('wp_ajax_follow', 'salong_process_new_follow');
function salong_process_unfollow()
{
	if (isset($_POST['user_id']) && isset($_POST['follow_id'])) {
		if (salong_unfollow_user($_POST['user_id'], $_POST['follow_id'])) {
			echo 'success';
		} else {
			echo 'failed';
		}
	}
	die;
}
add_action('wp_ajax_unfollow', 'salong_process_unfollow');
function salong_load_scripts()
{
	wp_enqueue_script('salong-follow', get_template_directory_uri() . '/js/follow-min.js', array('jquery'));
	wp_localize_script('salong-follow', 'salong_vars', array('processing_error' => __('处理请求时出现错误！', 'salong'), 'login_required' => __('呼，您必须登录才能关注用户！', 'salong'), 'logged_in' => is_user_logged_in() ? 'true' : 'false', 'ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('follow_nonce')));
}
add_action('wp_enqueue_scripts', 'salong_load_scripts');
if ($salong['switch_comment_rate']) {
	add_action('comment_post', 'comment_ratings');
	function comment_ratings($_var_79)
	{
		add_comment_meta($_var_79, 'rate', $_POST['rate']);
	}
	function movie_grade($_var_80)
	{
		switch ($_var_80) {
			case '0':
				$_var_81 = __('无 - 0 星', 'salong');
				break;
			case '1':
				$_var_81 = __('非常糟糕 - 1 星', 'salong');
				break;
			case '2':
				$_var_81 = __('糟糕 - 2 星', 'salong');
				break;
			case '3':
				$_var_81 = __('好 - 3 星', 'salong');
				break;
			case '4':
				$_var_81 = __('非常好 - 4 星', 'salong');
				break;
			case '5':
				$_var_81 = __('优秀 - 5 星', 'salong');
				break;
			default:
				$_var_81 = __('没有评分', 'salong');
				break;
		}
		$_var_82 .= '<div class="rate" title="' . $_var_81 . '">';
		if (!isset($_var_80) || $_var_80 == '') {
			$_var_82 .= $_var_81;
		} else {
			$_var_82 .= __('评分：', 'salong');
			for ($_var_83 = 1; $_var_83 < 6; $_var_83++) {
				if ($_var_80 >= $_var_83) {
					$_var_82 .= svg_star_full();
				} else {
					$_var_82 .= svg_star_line();
				}
			}
		}
		$_var_82 .= '</div>';
		return $_var_82;
	}
	function get_average_ratings($_var_84)
	{
		$_var_85 = get_approved_comments($_var_84);
		$_var_86 = 1;
		if ($_var_85) {
			$_var_87 = 0;
			$_var_88 = 0;
			foreach ($_var_85 as $_var_89) {
				$_var_90 = get_comment_meta($_var_89->comment_ID, 'rate');
				if (isset($_var_90[0]) && $_var_90[0] !== '') {
					$_var_87++;
					$_var_88 += $_var_90[0];
				}
			}
			if ($_var_87 == 0) {
				return false;
			} else {
				return round($_var_88 / $_var_87);
			}
		} else {
			return false;
		}
	}
}
function salong_user_main($_var_91, $_var_92, $_var_93)
{
	global $salong, $wp_query, $post;
	$_var_94 = $_var_91->ID;
	$_var_95 = get_the_author_meta('display_name', $_var_94);
	$_var_96 = get_the_author_meta('user_description', $_var_94);
	if ($salong['switch_follow_btn']) {
		$_var_97 = salong_get_following_count($_var_94);
		$_var_98 = salong_get_follower_count($_var_94);
	}
	$_var_99 .= '<li class="layout_li' . $_var_92 . '">';
	$_var_99 .= '<article class="user_main">';
	if ($_var_93 != null) {
		$_var_99 .= '<span class="num">' . $_var_93 . '</span>';
	}
	$_var_99 .= '<div class="img" title="' . $_var_96 . '">' . salong_get_avatar($_var_94, $_var_95) . '</div>';
	$_var_99 .= '<a href="' . get_author_posts_url($_var_94) . '" title="' . $_var_96 . '" class="title" title="' . $_var_96 . '"><h3>' . $_var_95 . salong_add_v($_var_94) . '</h3><span>' . user_role($_var_94) . '</span></a>';
	if ($salong['switch_follow_btn']) {
		$_var_99 .= salong_get_follow_unfollow_links($_var_94, $_var_100 = 0);
	}
	$_var_99 .= '<div class="post">';
	$_var_99 .= '<span>' . svg_post() . '<b>' . salong_author_post_count($_var_94, 'post') . '</b></span>';
	$_var_99 .= '<span>' . svg_view() . '<b>' . salong_all_post_field_count($_var_94, 'views') . '</b></span>';
	$_var_99 .= '<span>' . svg_like() . '<b>' . salong_all_post_field_count($_var_94, 'salong_post_like_count') . '</b></span>';
	$_var_99 .= '</div>';
	$_var_99 .= '</article>';
	$_var_99 .= '</li>';
	return $_var_99;
}
function salong_all_user()
{
	global $salong;
	$_var_101 = $salong['all_user_count'];
	$_var_102 = get_current_blog_id();
	$_var_103 = $salong['recommend_user'];
	if (!empty($_var_103)) {
		$_var_104 = implode(',', $_var_103);
	}
	$_var_105 = get_query_var('paged') ? get_query_var('paged') : 1;
	$_var_106 = ($_var_105 - 1) * $_var_101;
	$_var_107 = max(1, get_query_var('paged'));
	$_var_108 = get_users('blog_id=' . $_var_102 . '&exclude=' . $_var_104);
	$_var_109 = get_users('offset=' . $_var_106 . '&number=' . $_var_101 . '&exclude=' . $_var_104 . '&orderby=post_count&order=DESC');
	$_var_110 = count($_var_108);
	$_var_111 = ceil($_var_110 / $_var_101);
	$_var_112 .= '<section class="all_user_list">';
	$_var_112 .= '<ul class="layout_ul">';
	if (!empty($_var_103)) {
		$_var_113 = get_users('include=' . $_var_104 . '&orderby=post_count&order=DESC');
		foreach ($_var_113 as $_var_114 => $_var_115) {
			$_var_116 = ' recommend';
			$_var_117 = $_var_114 + 1;
			$_var_112 .= salong_user_main($_var_115, $_var_116, $_var_117);
		}
		$_var_112 .= '<hr>';
	}
	foreach ($_var_109 as $_var_115) {
		$_var_116 = ' other';
		$_var_117 = '';
		$_var_112 .= salong_user_main($_var_115, $_var_116, $_var_117);
	}
	$_var_112 .= '</ul>';
	$_var_112 .= '</section>';
	$_var_112 .= '<div class="pagination">';
	$_var_112 .= paginate_links(array('base' => get_pagenum_link(1) . '%_%', 'format' => '/page/%#%/', 'current' => $_var_107, 'total' => $_var_111, 'end_size' => 2, 'mid-size' => 3));
	$_var_112 .= '</div>';
	return $_var_112;
}
add_shortcode('all_user', 'salong_all_user');
function salong_edit_post()
{
	global $salong, $post;
	$_var_118 = $_GET['post_id'];
	$_var_119 = $salong['post_tg_max'];
	$_var_120 = $salong['post_tg_min'];
	$_var_121 = get_author_posts_url($_var_122) . '?tab=edit';
	$_var_123 = get_author_posts_url($_var_122) . '?tab=post';
	if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['post_id']) && !empty($_POST['post_title']) && isset($_POST['update_post_nonce']) && isset($_POST['post_content'])) {
		$_var_124 = $_POST['post_id'];
		$_var_125 = get_post_type($_var_124);
		$_var_126 = 'page' == $_var_125 ? 'edit_page' : 'edit_post';
		$_var_127 = isset($_POST['tougao_thumb']) ? trim(htmlspecialchars($_POST['tougao_thumb'], ENT_QUOTES)) : '';
		$_var_128 = isset($_POST['post_from_name']) ? trim(htmlspecialchars($_POST['post_from_name'], ENT_QUOTES)) : '';
		$_var_129 = isset($_POST['post_from_link']) ? trim(htmlspecialchars($_POST['post_from_link'], ENT_QUOTES)) : '';
		$_var_130 = isset($_POST['post_title']) ? trim(htmlspecialchars($_POST['post_title'], ENT_QUOTES)) : '';
		$_var_131 = isset($_POST['term_id']) ? (int) $_POST['term_id'] : 0;
		$_var_132 = isset($_POST['post_content']) ? $_POST['post_content'] : '';
		$_var_133 = isset($_POST['post_status']) ? $_POST['post_status'] : '';
		$post = array('ID' => $_var_124, 'post_content' => $_var_132, 'post_title' => $_var_130, 'post_status' => $_var_133, 'post_category' => array($_var_131));
		if (empty($_var_130) || mb_strlen($_var_130) > 100) {
			echo '<span class="warningbox">' . sprintf(__('标题必须填写，且长度不得超过100字，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $_var_121) . '</span>';
		} else {
			if (empty($_var_132)) {
				echo '<span class="warningbox">' . sprintf(__('内容必须填写，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $_var_121) . '</span>';
			} else {
				if (mb_strlen($_var_132) > $_var_119) {
					echo '<span class="warningbox">' . sprintf(__('内容长度不得超过%s字，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $_var_119, $_var_121) . '</span>';
				} else {
					if (mb_strlen($_var_132) < $_var_120) {
						echo '<span class="warningbox">' . sprintf(__('内容长度不得少于%s字，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $_var_120, $_var_121) . '</span>';
					} else {
						if ($_POST['are_you_human'] == '') {
							echo '<span class="warningbox">' . sprintf(__('请输入本站名称：%s', 'salong'), get_option('blogname')) . '</span>';
						} else {
							if ($_POST['are_you_human'] !== get_bloginfo('name')) {
								echo '<span class="warningbox">' . sprintf(__('本站名称输入错误，正确名称为：%s', 'salong'), get_option('blogname')) . '</span>';
							} else {
								if (current_user_can($_var_126, $_var_124) && wp_verify_nonce($_POST['update_post_nonce'], 'update_post_' . $_var_124)) {
									wp_update_post($post);
									if ($_var_128) {
										add_post_meta($_var_133, 'from_name', $_var_128, true);
									}
									if ($_var_129) {
										add_post_meta($_var_133, 'from_link', $_var_129, true);
									}
									if ($_var_127) {
										add_post_meta($_var_134, 'thumb', $_var_127, true);
									}
									if ($_var_133 == 'draft') {
										echo '<span class="successbox">' . __('草稿更新成功！', 'salong') . '</span>';
									}
								} else {
									echo '<span class="errorbox">' . __('你不能修改此文章！', 'salong') . '</span>';
								}
							}
						}
					}
				}
			}
		}
	}
	$_var_135 = get_post_status($_var_118);
	if ($_var_135 == 'pending') {
		echo '<span class="successbox">' . __('文章更新成功，已提交审核！', 'salong') . '</span>';
		$_var_136 = $salong['contribute_email_pending'];
		wp_mail(get_option('admin_email'), get_option('blogname') . __('用户投稿', 'salong'), $_var_136);
		return;
	}
	$_var_137 = array('post_type' => 'post', 'ignore_sticky_posts' => 1, 'p' => $_var_118);
	$_var_138 = new WP_Query($_var_137);
	if ($_var_138->have_posts()) {
		while ($_var_138->have_posts()) {
			$_var_138->the_post();
			echo '<form id="post" class="contribute_form" method="post" enctype="multipart/form-data">';
			echo '<input type="hidden" name="post_id" value="' . get_the_ID() . '" />';
			wp_nonce_field('update_post_' . get_the_ID(), 'update_post_nonce');
			echo '<p><label for="post_title"><b class="required">*</b>' . __('文章标题', 'salong') . '</label><input type="text" value="' . $post->post_title . '" id="post_title" name="post_title" required /><span>' . sprintf(__('标题长度不得超过%s字。', 'salong'), 100) . '</span></p>';
			echo '<p><label for="post_category"><b class="required">*</b>' . __('文章分类', 'salong') . '</label>';
			$_var_139 = $salong['contribute_cat'];
			if ($_var_139) {
				$_var_140 = implode(',', $salong['contribute_cat']);
			} else {
				$_var_140 = '';
			}
			$_var_128 = get_post_meta($_var_118, 'from_name', true);
			$_var_129 = get_post_meta($_var_118, 'from_link', true);
			$_var_127 = get_post_meta($_var_118, 'thumb', true);
			$_var_141 = get_the_category();
			$_var_142 = $_var_141[0]->cat_ID;
			$_var_135 = get_post_status($_var_118);
			wp_dropdown_categories('include=' . $_var_140 . '&selected=' . $_var_142 . '&hide_empty=0&id=post_category&show_count=1&hierarchical=1&taxonomy=category&name=term_id&id=term_id');
			echo '</p>';
			echo '<p>' . wp_editor(wpautop($post->post_content), 'post_content', array('media_buttons' => true, 'quicktags' => true, 'editor_class' => 'form-control')) . '<span>' . sprintf(__('内容必须填写，且长度不得超过 %s 字，不得少于 %s 字。', 'salong'), $_var_119, $_var_120) . '</span></p>';
			if (current_user_can('edit_posts') || $salong['switch_contributor_uploads']) {
				echo '<div class="salong_field_main"><label for="tougao_thumb">' . __('缩略图', 'salong') . '</label><div class="salong_field_area"><div class="salong_file_button';
				if ($_var_127) {
					echo ' active';
				}
				echo '"><a href="#" class="salong_upload_button"><b>+</b><span>' . __('更改图片', 'salong') . '</span></a><div class="salong_file_preview">';
				if ($_var_127) {
					echo '<img src="' . $_var_127 . '">';
				}
				echo '</div><div class="bg"></div><input class="salong_field_upload" type="hidden" value="' . $_var_127 . '" id="tougao_thumb" name="tougao_thumb" /></div><div class="salong_file_hint"><p>' . __('自定义缩略图，建议比例：460*280。', 'salong') . '</p><span>' . __('支持≤3MB，JPG，JEPG，PNG格式文件', 'salong') . '</span></div></div></div><hr>';
			}
			echo '<p><label for="post_from_name">' . __('文章来源网站名称', 'salong') . '</label><input type="text" value="' . $_var_128 . '" id="post_from_name" name="post_from_name" /></p>';
			echo '<p><label for="post_from_link">' . __('文章来源网站链接', 'salong') . '</label><input type="text" value="' . $_var_129 . '" id="post_from_link" name="post_from_link" /></p><hr>';
			echo '<p><label for="are_you_human"><b class="required">*</b>' . sprintf(__('本站名称（请输入：%s）', 'salong'), get_option('blogname')) . '<br/><input id="are_you_human" class="input" type="text" value="" name="are_you_human" required /></label></p>';
			echo '<div class="status_btn">';
			echo '<select name="post_status"><option value="pending"';
			if ($_var_135 == 'pending') {
				echo 'selected="selected"';
			}
			echo '>' . __('提交审核', 'salong') . '</option><option value="draft"';
			if ($_var_135 == 'draft') {
				echo 'selected="selected"';
			}
			echo '>' . __('保存草稿', 'salong') . '</option></select>';
			echo '<p><input type="submit" class="submit" value="' . __('更新', 'salong') . '" /></p>';
			echo '</div>';
			echo '</form>';
		}
	}
	wp_reset_query();
}
add_shortcode('edit_post', 'salong_edit_post');
function salong_download_code()
{
	global $salong, $post, $current_user;
	$_var_143 = get_post_meta($post->ID, 'download_info', true);
	$_var_144 = get_post_meta($post->ID, 'download_link', true);
	$_var_145 = get_post_meta($post->ID, 'link_home', true);
	$_var_146 = get_post_meta($post->ID, 'product_id', true);
	$_var_147 = $salong['vip_access'];
	$_var_148 .= '<section class="download_code">';
	$_var_148 .= '<h3>' . __('文件信息：', 'salong') . '</h3>';
	$_var_148 .= '<div class="download_info">';
	$_var_148 .= '<ol>';
	$_var_148 .= '<li>';
	$_var_148 .= '<span>';
	$_var_148 .= __('官方网站', 'salong');
	$_var_148 .= '</span>';
	$_var_148 .= '<a href="' . $_var_145 . '" target="_blank" rel="nofollow external">' . $_var_145 . '</a>';
	$_var_148 .= '</li>';
	foreach ($_var_143 as $_var_149) {
		$_var_148 .= '<li>';
		$_var_148 .= '<span>';
		$_var_148 .= $_var_149['info_title'];
		$_var_148 .= '</span>';
		$_var_148 .= $_var_149['info_value'];
		$_var_148 .= '</li>';
	}
	$_var_148 .= '</ol>';
	$_var_148 .= '</div>';
	if (salong_is_administrator() || empty($_var_146) || current_user_can($_var_147) || wc_customer_bought_product($current_user->email, $current_user->ID, $_var_146) || $current_user->roles[0] == 'vip') {
		$_var_148 .= '<div class="download_link">';
		$_var_148 .= '<h4>' . __('下载地址：', 'salong') . '</h4>';
		$_var_148 .= '<ol>';
		foreach ($_var_144 as $_var_150) {
			if ($salong['switch_link_go']) {
				$_var_151 = external_link($_var_150['link_value'] . '|' . $post->ID);
			} else {
				$_var_151 = esc_url($_var_150['link_value']);
			}
			$_var_148 .= '<li>';
			$_var_148 .= '<a href="' . $_var_151 . '" target="_blank" rel="nofollow external">' . $_var_150['link_title'] . '</a>';
			$_var_148 .= '</li>';
		}
		$_var_148 .= '</ol>';
		$_var_148 .= '</div>';
	} else {
		if (is_user_logged_in()) {
			$_var_148 .= sprintf('<div class="warningbox">' . __('当前下载链接只有购买了&nbsp;【%s】&nbsp;产品的用户才能查看，点击&nbsp;<a href="%s" target="_blank" title="前往购买">前往购买</a>。', 'salong') . '</div>', get_the_title($_var_146), get_permalink($_var_146));
		} else {
			if (class_exists('XH_Social')) {
				$_var_152 = '#login';
			} else {
				$_var_152 = wp_login_url($_SERVER['REQUEST_URI']);
			}
			$_var_148 .= sprintf('<div class="warningbox">' . __('当前下载链接只有购买了&nbsp;【%s】&nbsp;产品的用户才能查看，点击&nbsp;<a href="%s" target="_blank" title="前往购买">前往购买</a>，如果您已经购买，<a href="%s" title="">请登录</a>。', 'salong') . '</div>', get_the_title($_var_146), get_permalink($_var_146), $_var_152);
		}
	}
	$_var_148 .= '</section>';
	return $_var_148;
}
add_shortcode('download_code', 'salong_download_code');
function salong_map()
{
	require_once get_template_directory() . '/content/contact-map.php';
}
add_shortcode('map', 'salong_map');
if ($salong['switch_post_type_slug']) {
	global $salong;
	$posttypes = array('video' => 'video', 'download' => 'download', 'product' => 'product', 'dwqa-question' => 'dwqa-question');
	add_filter('post_type_link', 'custom_salong_link', 1, 3);
	function custom_salong_link($_var_153, $_var_154 = 0)
	{
		global $posttypes;
		if (in_array($_var_154->post_type, array_keys($posttypes))) {
			global $salong;
			if ($salong['post_type_slug'] == 'Postname') {
				$_var_155 = 'post_name';
			} else {
				$_var_155 = 'ID';
			}
			return home_url($posttypes[$_var_154->post_type] . '/' . $_var_154->{$_var_155} . '.html');
		} else {
			return $_var_153;
		}
	}
	add_action('init', 'custom_salong_rewrites_init');
	function custom_salong_rewrites_init()
	{
		global $posttypes;
		foreach ($posttypes as $_var_156 => $_var_157) {
			global $salong;
			if ($salong['post_type_slug'] == 'Postname') {
				add_rewrite_rule($_var_157 . '/([一-龥a-zA-Z0-9_-]+)?.html([\\s\\S]*)?$', 'index.php?post_type=' . $_var_156 . '&name=$matches[1]', 'top');
			} else {
				add_rewrite_rule($_var_157 . '/([0-9]+)?.html$', 'index.php?post_type=' . $_var_156 . '&p=$matches[1]', 'top');
			}
		}
	}
}
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($_var_158)
{
	return is_array($_var_158) ? array_intersect($_var_158, array('current-menu-item', 'current-post-ancestor', 'current-menu-ancestor', 'current-menu-parent', 'menu-item-has-children')) : '';
}
function salong_theme_title()
{
	global $salong, $post, $wp_query;
	$_var_159 = get_queried_object()->term_id;
	$_var_160 = $salong['delimiter'];
	$_var_161 = get_term_meta($_var_159, 'seo_title', true);
	$_var_162 = get_post_meta($post->ID, 'seo_title', 'true');
	$_var_163 = get_query_var('paged');
	$_var_164 = $wp_query->get('page');
	if (is_home()) {
		$_var_165 .= get_bloginfo('name') . $_var_160 . get_bloginfo('description');
	} else {
		if (is_category() || is_tax()) {
			$_var_165 .= single_cat_title();
			if ($_var_161) {
				$_var_165 .= $_var_160 . $_var_161;
			}
			$_var_165 .= $_var_160 . get_bloginfo('name');
		} else {
			if (is_singular()) {
				$_var_165 .= the_title();
				if ($_var_162) {
					$_var_165 .= $_var_160 . $_var_162;
				}
				$_var_165 .= $_var_160 . get_bloginfo('name');
				if (is_sticky()) {
					$_var_165 .= ${$_var_160} . __('置顶', 'salong');
				}
				if ($_var_164 && is_single()) {
					$_var_165 .= $_var_160 . sprintf(__('(第%s页)', 'salong'), $_var_164);
				}
			} else {
				if (is_author()) {
					global $salong, $wp_query;
					$_var_166 = $wp_query->get_queried_object();
					$_var_167 = $_var_166->ID;
					$_var_168 = $_var_166->display_name;
					$_var_169 = $_GET['tab'];
					$_var_165 .= $_var_168;
					$_var_165 .= $_var_160;
					if ($_var_169 == 'post') {
						$_var_165 .= __('文章', 'salong');
					} else {
						if ($_var_169 == 'topic') {
							$_var_165 .= __('专题', 'salong');
						} else {
							if ($_var_169 == 'download') {
								$_var_165 .= __('下载', 'salong');
							} else {
								if ($_var_169 == 'like') {
									$_var_165 .= __('点赞的文章', 'salong');
								} else {
									if ($_var_169 == 'like-topic') {
										$_var_165 .= __('点赞的专题', 'salong');
									} else {
										if ($_var_169 == 'like-download') {
											$_var_165 .= __('点赞的下载', 'salong');
										} else {
											if ($_var_169 == 'message') {
												$_var_165 .= __('发送私信', 'salong');
											} else {
												if ($_var_169 == 'message-inbox') {
													$_var_165 .= __('收件箱', 'salong');
												} else {
													if ($_var_169 == 'message-outbox') {
														$_var_165 .= __('发件箱', 'salong');
													} else {
														if ($_var_169 == 'comment') {
															$_var_165 .= __('评论', 'salong');
														} else {
															if ($_var_169 == 'following') {
																$_var_165 .= __('关注', 'salong');
															} else {
																if ($_var_169 == 'follower') {
																	$_var_165 .= __('粉丝', 'salong');
																} else {
																	if ($_var_169 == 'contribute') {
																		$_var_165 .= __('投稿', 'salong');
																	} else {
																		if ($_var_169 == 'profile') {
																			$_var_165 .= __('编辑资料', 'salong');
																		} else {
																			if ($_var_169 == 'edit') {
																				$_var_165 .= __('编辑文章', 'salong');
																			} else {
																				$_var_165 .= __('资料', 'salong');
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
					$_var_165 .= $_var_160 . get_bloginfo('name');
					if (is_sticky()) {
						global $salong;
						$_var_165 .= $salong['sticky_title'];
					}
				} else {
					$_var_165 .= wp_title($_var_160, true, 'right');
					$_var_165 .= get_bloginfo('name');
				}
			}
		}
	}
	if ($_var_163) {
		$_var_165 .= $_var_160 . sprintf(__('(第%s页)', 'salong'), $_var_163);
	}
	return $_var_165;
}
function user_role($_var_170)
{
	if (user_can($_var_170, 'administrator')) {
		$_var_171 .= __('管理员', 'salong');
	} else {
		if (user_can($_var_170, 'editor')) {
			$_var_171 .= __('编辑', 'salong');
		} else {
			if (user_can($_var_170, 'author')) {
				$_var_171 .= __('认证作者', 'salong');
			} else {
				if (user_can($_var_170, 'contributor')) {
					$_var_171 .= __('投稿者', 'salong');
				} else {
					if (user_can($_var_170, 'subscriber')) {
						$_var_171 .= __('订阅者', 'salong');
					} else {
						if (user_can($_var_170, 'shop_manager')) {
							$_var_171 .= __('产品管理者', 'salong');
						} else {
							if (user_can($_var_170, 'bbp_keymaster')) {
								$_var_171 .= __('Keymaster', 'salong');
							} else {
								if (user_can($_var_170, 'customer')) {
									$_var_171 .= __('顾客', 'salong');
								} else {
									if (user_can($_var_170, 'vip')) {
										$_var_171 .= __('VIP', 'salong');
									} else {
										if (user_can($_var_170, 'bbp_spectator')) {
											$_var_171 .= __('观众', 'salong');
										} else {
											if (user_can($_var_170, 'bbp_blocked')) {
												$_var_171 .= __('禁闭', 'salong');
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	return $_var_171;
}
function salong_archive_link($_var_172 = true)
{
	$_var_173 = false;
	if (is_front_page()) {
		$_var_173 = home_url('/');
	} else {
		if (is_home() && 'page' == get_option('show_on_front')) {
			$_var_173 = get_permalink(get_option('page_for_posts'));
		} else {
			if (is_tax() || is_tag() || is_category()) {
				$_var_174 = get_queried_object();
				$_var_173 = get_term_link($_var_174, $_var_174->taxonomy);
			} else {
				if (is_post_type_archive()) {
					$_var_173 = get_post_type_archive_link(get_post_type());
				} else {
					if (is_author()) {
						$_var_173 = get_author_posts_url(get_query_var('author'), get_query_var('author_name'));
					} else {
						if (is_archive()) {
							if (is_date()) {
								if (is_day()) {
									$_var_173 = get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day'));
								} else {
									if (is_month()) {
										$_var_173 = get_month_link(get_query_var('year'), get_query_var('monthnum'));
									} else {
										if (is_year()) {
											$_var_173 = get_year_link(get_query_var('year'));
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	if ($_var_172 && $_var_173 && get_query_var('paged') > 1) {
		global $wp_rewrite;
		if (!$wp_rewrite->using_permalinks()) {
			$_var_173 = add_query_arg('paged', get_query_var('paged'), $_var_173);
		} else {
			$_var_173 = user_trailingslashit(trailingslashit($_var_173) . trailingslashit($wp_rewrite->pagination_base) . get_query_var('paged'), 'archive');
		}
	}
	return $_var_173;
}
add_filter('the_content', 'v13_seo_wl');
function v13_seo_wl($_var_175)
{
	$_var_176 = '<a\\s[^>]*href=("??)([^" >]*?)\\1[^>]*>';
	if (preg_match_all("/{$_var_176}/siU", $_var_175, $_var_177, PREG_SET_ORDER)) {
		if (!empty($_var_177)) {
			$_var_178 = get_option('siteurl');
			for ($_var_179 = 0; $_var_179 < count($_var_177); $_var_179++) {
				$_var_180 = $_var_177[$_var_179][0];
				$_var_181 = $_var_177[$_var_179][0];
				$_var_182 = $_var_177[$_var_179][0];
				$_var_183 = '';
				$_var_184 = '/rel\\s*=\\s*"\\s*[n|d]ofollow\\s*"/';
				preg_match($_var_184, $_var_181, $_var_185, PREG_OFFSET_CAPTURE);
				if (count($_var_185) < 1) {
					$_var_183 .= ' rel="nofollow" ';
				}
				$_var_186 = strpos($_var_182, $_var_178);
				if ($_var_186 === false) {
					$_var_180 = rtrim($_var_180, '>');
					$_var_180 .= $_var_183 . '>';
					$_var_175 = str_replace($_var_181, $_var_180, $_var_175);
				}
			}
		}
	}
	$_var_175 = str_replace(']]>', ']]>', $_var_175);
	return $_var_175;
}
function salong_favicon()
{
	global $salong;
	$_var_187 = $salong['custom_favicon']['url'];
	$_var_188 = $salong['custom_ios_favicon']['url'];
	if ($_var_187) {
		echo '<link rel="shortcut icon" href="' . $_var_187 . '" />', '
';
	}
	if ($_var_188) {
		echo '<link rel="apple-touch-icon" sizes="120*120" href="' . $_var_188 . '" />', '
';
	}
}
add_action('wp_head', 'salong_favicon');
add_filter('the_content', 'fancybox_replace');
function fancybox_replace($_var_189)
{
	global $post;
	$_var_190 = '/<a(.*?)href=(\'|")([^>]*).(bmp|gif|jpeg|jpg|png)(\'|")(.*?)>(.*?)<\\/a>/i';
	$_var_191 = '<a$1href=$2$3.$4$5 data-fancybox="gallery"$6>$7</a>';
	$_var_189 = preg_replace($_var_190, $_var_191, $_var_189);
	return $_var_189;
}
add_filter('pre_option_link_manager_enabled', '__return_true');
function salong_avatar_name()
{
	global $salong, $wp_query, $comment, $current_user;
	$_var_192 = $current_user->ID;
	$GLOBALS['comment'] = $comment;
	$_var_193 = get_user_meta($_var_192, 'salong_avatar', true);
	$_var_194 = get_user_meta($_var_192, '_social_img', true);
	if ($_var_193) {
		$_var_195 = __('自定义头像', 'salong');
	} else {
		if ($_var_194) {
			$_var_195 = __('社交头像', 'salong');
		} else {
			$_var_195 = __('默认头像', 'salong');
		}
	}
	return $_var_195;
}
function salong_get_avatar($_var_196, $_var_197)
{
	global $salong, $comment;
	$GLOBALS['comment'] = $comment;
	$_var_198 = get_user_meta($_var_196, 'salong_avatar', true);
	$_var_199 = get_user_meta($_var_196, '_social_img', true);
	$_var_200 = $salong['default_avatar']['url'];
	$_var_201 = $salong['avatar_loading']['url'];
	if ($_var_198) {
		$_var_202 = $_var_198;
	} else {
		if ($_var_199) {
			$_var_202 = $_var_199;
		} else {
			$_var_202 = $_var_200;
		}
	}
	if ($salong['switch_lazyload']) {
		return '<img class="avatar" src="' . $_var_201 . '" data-original="' . $_var_202 . '" alt="' . $_var_197 . '" />';
	} else {
		return '<img class="avatar" src="' . $_var_202 . '" alt="' . $_var_197 . '" />';
	}
}
if (is_admin()) {
	function get_ssl_avatar($_var_203)
	{
		$_var_203 = preg_replace('/.*\\/avatar\\/(.*)\\?s=([\\d]+)&.*/', '<img src="https://secure.gravatar.com/avatar/$1?s=32" class="avatar avatar-32" height="32" width="32">', $_var_203);
		return $_var_203;
	}
} else {
	function get_ssl_avatar($_var_204)
	{
		global $current_user;
		$_var_205 = $current_user->ID;
		$_var_206 = $current_user->display_name;
		$_var_204 = preg_replace('/.*\\/avatar\\/(.*)\\?s=([\\d]+)&.*/', salong_get_avatar($_var_205, $_var_206), $_var_204);
		return $_var_204;
	}
}
add_filter('get_avatar', 'get_ssl_avatar');
function crumb()
{
	global $salong, $wp_query, $post;
	if (is_singular('topic')) {
		$_var_207 = get_post_meta($post->ID, 'top_thumb', true);
		if ($_var_207) {
			echo '<section class="crumbs_img" style="background-image: url(' . $_var_207 . ');">';
			echo '</section>';
		}
	} else {
		if (is_tax() || is_category()) {
			$_var_208 = get_queried_object()->term_id;
			$_var_207 = get_term_meta($_var_208, 'thumb', true);
			$_var_209 = '.' . get_term_meta($_var_208, 'thumb_opacity', true);
			$_var_210 = category_description();
			$_var_211 = $wp_query->queried_object->name;
		} else {
			if (is_page_template('template-post.php')) {
				$_var_207 = $salong['post_bg']['url'];
				$_var_209 = $salong['post_bg_rgba']['alpha'];
				$_var_210 = $salong['post_desc'];
				$_var_211 = get_the_title();
			} else {
				if (is_page_template('template-download.php')) {
					$_var_207 = $salong['download_bg']['url'];
					$_var_209 = $salong['download_bg_rgba']['alpha'];
					$_var_210 = $salong['download_desc'];
					$_var_211 = get_the_title();
				} else {
					if (is_page_template('template-topic.php')) {
						$_var_207 = $salong['topic_bg']['url'];
						$_var_209 = $salong['topic_bg_rgba']['alpha'];
						$_var_210 = $salong['topic_desc'];
						$_var_211 = get_the_title();
					} else {
						if (is_page_template('template-video.php')) {
							$_var_207 = $salong['video_bg']['url'];
							$_var_209 = $salong['video_bg_rgba']['alpha'];
							$_var_210 = $salong['video_desc'];
							$_var_211 = get_the_title();
						}
					}
				}
			}
		}
		if ($_var_207) {
			echo '<section class="crumbs_img" style="background-image: url(' . $_var_207 . ');">';
			echo '<section class="crumbs_con">';
			echo '<h1>' . $_var_211 . '</h1>';
			if ($_var_210) {
				echo '<p>' . $_var_210 . '</p>';
			}
			echo '</section>';
			if ($_var_209) {
				echo '<div class="bg" style="opacity: ' . $_var_209 . ';"></div>';
			}
			echo '</section>';
		}
	}
	if ($salong['switch_crumbs']) {
		echo salong_breadcrumbs();
	}
}
function salong_post_like()
{
	global $salong;
	if ($salong['switch_like_btn']) {
		echo '<div class="post_like">';
		echo get_post_likes_button(get_the_ID()) . salong_user_follow_post();
		echo '</div>';
	}
}
function posts_pagination()
{
	echo the_posts_pagination(array('mid_size' => 1, 'prev_text' => svg_more(), 'next_text' => svg_more()));
}
function salong_link_pages()
{
	$_var_212 = array('before' => '<div class="pagination"><p>' . __('分页：', 'salong') . '</p>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>', 'next_or_number' => 'number', 'separator' => '', 'nextpagelink' => svg_more(), 'previouspagelink' => svg_more(), 'pagelink' => '%', 'echo' => 1);
	wp_link_pages($_var_212);
}
function salong_add_v($_var_213)
{
	global $salong;
	$_var_214 = $salong['admin_field'];
	$_var_215 = get_userdata($_var_213)->roles;
	if ($_var_215[0] == 'author') {
		$_var_216 = '<span class="yellow addv">' . svg_v() . '</span>';
	}
	if ($_var_215[0] == 'administrator' && $_var_214) {
		$_var_216 = '<span class="admin_field">' . $_var_214 . '</span>';
	}
	return $_var_216;
}
function salong_sidebar($_var_217)
{
	global $wp_registered_sidebars, $salong;
	$_var_218 = 'm-' . $_var_217;
	$_var_219 = $wp_registered_sidebars[$_var_218]['name'];
	echo '<aside class="sidebar">';
	if (is_active_sidebar($_var_218)) {
		$_var_220 = get_post_type();
		$_var_221 = 'switch_author_' . $_var_220;
		if (is_singular($_var_220) && $salong[$_var_221]) {
			get_template_part('includes/widgets/widget', 'author');
		}
		dynamic_sidebar($_var_219);
		echo '<article id="move" class="move">';
		dynamic_sidebar(__('移动', 'salong'));
		echo '</article>';
	} else {
		echo '<article class="sidebar_widget widget_salong_init">';
		echo '<div class="sidebar_title">';
		echo '<h3>';
		echo __('温馨提示', 'salong');
		echo '</h3>';
		echo '</div>';
		echo '<div class="init"><a href="' . get_home_url() . '/wp-admin/widgets.php">';
		echo sprintf(__('请到后台外观——小工具中添加小工具到<b>%s</b>边栏中。', 'salong'), $_var_219);
		echo '</a></div>';
		echo '</article>';
	}
	echo '</aside>';
}
function add_search_to_wp_menu()
{
	global $salong, $current_user;
	$_var_222 = $current_user->id;
	$_var_223 = $current_user->display_name;
	if (class_exists('XH_Social')) {
		$_var_224 = '#login';
	} else {
		$_var_224 = wp_login_url($_SERVER['REQUEST_URI']);
	}
	$_var_225 = wp_registration_url();
	if ($salong['switch_search_menu']) {
		echo '<li class="search">';
		echo '<a href="#search" title="' . __('点击搜索', 'salong') . '">' . svg_search() . '</a>';
		echo '</li>';
	}
	if ($salong['switch_program_menu']) {
		echo '<li class="program">';
		echo '<a href="#program" title="' . $salong['program_title'] . '">' . svg_wechat() . '</a>';
		echo '</li>';
	}
	if ($salong['switch_loginreg_menu']) {
		if (is_user_logged_in()) {
			echo '<li class="center menu-item-has-children">';
			echo '<a href="' . get_author_posts_url($_var_222) . '" title="' . $_var_223 . '">' . salong_get_avatar($_var_222, $_var_223) . $_var_223 . '</a>';
			echo '<ul class="sub-menu">';
			echo salong_user_menu($_var_222);
			echo '</ul></li>';
			if ($salong['switch_contribute_menu']) {
				echo '<li class="contribute_btn"><a href="' . get_author_posts_url($_var_222) . '?tab=contribute">' . $salong['contribute_field'] . '</a></li>';
			}
		} else {
			echo '<li class="login"><a href="' . $_var_224 . '">' . __('登录', 'salong') . '</a></li>';
			if (get_option('users_can_register') == 1) {
				echo '<li class="reg"><a href="' . $_var_225 . '">' . __('注册', 'salong') . '</a></li>';
			}
			if ($salong['switch_contribute_menu']) {
				echo '<li class="contribute_btn"><a href="' . $_var_224 . '">' . $salong['contribute_field'] . '</a></li>';
			}
		}
	}
}
function salong_payqr($_var_226)
{
	global $salong;
	$_var_227 = get_user_meta($_var_226, 'salong_alipay', true);
	$_var_228 = get_user_meta($_var_226, 'salong_wechatpay', true);
	if ($_var_227 || $_var_228) {
		$_var_229 .= '<a id="payqr" class="overlay" rel="external nofollow" href="#m"></a>';
		$_var_229 .= '<article class="payqr popup">';
		$_var_229 .= '<section class="popup_main';
		if ($_var_227 && $_var_228) {
			$_var_229 .= ' two';
		}
		$_var_229 .= '">';
		$_var_229 .= '<h3>' . __('给 TA 打赏', 'salong') . '</h3>';
		if ($_var_227) {
			$_var_229 .= '<span class="alipay"><img src="' . $_var_227 . '" alt="' . __('支付宝收款二维码', 'salong') . '">' . svg_alipay() . __('支付宝收款二维码', 'salong') . '</span>';
		}
		if ($_var_228) {
			$_var_229 .= '<span class="wechatpay"><img src="' . $_var_228 . '" alt="' . __('微信收款二维码', 'salong') . '">' . svg_wechat() . __('微信收款二维码', 'salong') . '</span>';
		}
		$_var_229 .= '<a class="close" rel="external nofollow" href="#m">' . svg_close() . '</a>';
		$_var_229 .= '</section></article>';
	}
	return $_var_229;
}
function salong_all_post_field_count($_var_230, $_var_231)
{
	global $wpdb, $salong;
	$_var_232 = "SELECT SUM(meta_value+0) FROM {$wpdb->posts} left join {$wpdb->postmeta} on ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id) WHERE meta_key = '{$_var_231}' AND post_author = {$_var_230}";
	$_var_233 = intval($wpdb->get_var($_var_232));
	if ($salong['switch_filter_count']) {
		$_var_234 = salong_format_count($_var_233);
	} else {
		$_var_234 = $_var_233;
	}
	return $_var_234;
}
function salong_following_count($_var_235)
{
	global $salong;
	if ($salong['switch_follow_btn'] == 0) {
		return;
	}
	$_var_236 = salong_get_following_count($_var_235);
	if ($_var_236) {
		if ($salong['switch_filter_count']) {
			$_var_237 = salong_format_count($_var_236);
		} else {
			$_var_237 = $_var_236;
		}
	} else {
		$_var_237 = 0;
	}
	return $_var_237;
}
function salong_follower_count($_var_238)
{
	global $salong;
	if ($salong['switch_follow_btn'] == 0) {
		return;
	}
	$_var_239 = salong_get_follower_count($_var_238);
	if ($_var_239) {
		if ($salong['switch_filter_count']) {
			$_var_240 = salong_format_count($_var_239);
		} else {
			$_var_240 = $_var_239;
		}
	} else {
		$_var_240 = 0;
	}
	return $_var_240;
}
function salong_author_post_like_count($_var_241, $_var_242)
{
	global $salong;
	$_var_243 = array('post_type' => $_var_241, 'meta_query' => array(array('key' => 'salong_user_liked', 'value' => $_var_242, 'compare' => 'LIKE')));
	$_var_244 = new WP_Query($_var_243);
	if ($salong['switch_filter_count']) {
		$_var_245 = salong_format_count($_var_244->found_posts);
	} else {
		$_var_245 = $_var_244->found_posts;
	}
	return $_var_245;
}
function salong_author_post_count($_var_246, $_var_247)
{
	global $salong;
	$_var_248 = count_user_posts($_var_246, $_var_247);
	if ($salong['switch_filter_count']) {
		$_var_249 = salong_format_count($_var_248);
	} else {
		$_var_249 = $_var_248;
	}
	return $_var_249;
}
function salong_author_post_field_count($_var_250, $_var_251, $_var_252)
{
	global $salong, $post;
	$_var_253 = get_posts(array('posts_per_page' => -1, 'post_type' => $_var_250, 'post_status' => 'publish', 'author' => $_var_251));
	$_var_254 = 0;
	foreach ($_var_253 as $post) {
		$_var_255 = absint(get_post_meta($post->ID, $_var_252, true));
		$_var_254 += $_var_255;
	}
	if ($salong['switch_filter_count']) {
		$_var_256 = salong_format_count($_var_254);
	} else {
		$_var_256 = $_var_254;
	}
	return $_var_256;
}
function getPostViews($_var_257)
{
	global $salong;
	$_var_258 = 'views';
	$_var_259 = get_post_meta($_var_257, $_var_258, true);
	if ($salong['switch_filter_count']) {
		$_var_260 = salong_format_count($_var_259);
	} else {
		$_var_260 = $_var_259;
	}
	if ($_var_260 == '') {
		delete_post_meta($_var_257, $_var_258);
		add_post_meta($_var_257, $_var_258, '0');
		return '0';
	}
	return $_var_260 . '';
}
function setPostViews($_var_261)
{
	global $salong;
	$_var_262 = 'views';
	$_var_263 = get_post_meta($_var_261, $_var_262, true);
	if ($_var_263 == '') {
		$_var_263 = 0;
		delete_post_meta($_var_261, $_var_262);
		add_post_meta($_var_261, $_var_262, '0');
	} else {
		$_var_264 = range(1, $salong['views_loop_count']);
		foreach ($_var_264 as $_var_265) {
			$_var_263++;
		}
		update_post_meta($_var_261, $_var_262, $_var_263);
	}
}
function get_page_id_from_template($_var_266)
{
	global $wpdb;
	$_var_267 = $wpdb->get_var($wpdb->prepare("SELECT `post_id`\n                              FROM `{$wpdb->postmeta}`, `{$wpdb->posts}`\n                              WHERE `post_id` = `ID`\n                                    AND `post_status` = 'publish'\n                                    AND `meta_key` = '_wp_page_template'\n                                    AND `meta_value` = %s\n                                    LIMIT 1;", $_var_266));
	return $_var_267;
}
function salong_category_top_parent_id($_var_268, $_var_269)
{
	$_var_270 = get_term_by('id', $_var_268, $_var_269);
	while ($_var_270->parent != '0') {
		$_var_268 = $_var_270->parent;
		$_var_270 = get_term_by('id', $_var_268, $_var_269);
	}
	return $_var_270->term_id;
}
function salong_category_post_count($_var_271, $_var_272)
{
	$_var_273 = new Wp_query(array('post_type' => get_post_type(), 'posts_per_page' => -1, 'tax_query' => array(array('taxonomy' => $_var_272, 'field' => 'id', 'terms' => $_var_271))));
	while ($_var_273->have_posts()) {
		$_var_273->the_post();
		$_var_274 = $_var_273->post_count;
	}
	wp_reset_postdata();
	return $_var_274;
}
function get_today_post_count()
{
	$_var_275 = array(array('after' => '1 day ago'));
	$_var_276 = array('post_type' => 'post', 'post_status' => 'publish', 'date_query' => $_var_275, 'no_found_rows' => true, 'suppress_filters' => true, 'fields' => 'ids', 'posts_per_page' => -1);
	$_var_277 = new WP_Query($_var_276);
	return $_var_277->post_count;
}
if (salong_is_weixin() && $salong['switch_wechat_share']) {
	require_once get_template_directory() . '/includes/jssdk.php';
	add_action('wp_ajax_nopriv_wechat_share', 'wechat_share_callback');
	add_action('wp_ajax_wechat_share', 'wechat_share_callback');
	function wechat_share_callback()
	{
		$_var_278 = $_POST['postid'];
		$_var_279 = get_post_meta($_var_278, 'wechat_share_num', true) ? get_post_meta($_var_278, 'wechat_share_num', true) + 1 : 1;
		update_post_meta($_var_278, 'wechat_share_num', $_var_279);
		die;
	}
}
add_role('vip', 'VIP', array('read' => true, 'edit_posts' => true, 'upload_files' => true));
function salong_secsToStr($_var_280)
{
	if ($_var_280 >= 3600) {
		$_var_281 = floor($_var_280 / 3600);
		$_var_280 = $_var_280 % 3600;
		$_var_282 .= $_var_281 . __('小时', 'salong');
	}
	if ($_var_280 >= 60) {
		$_var_283 = floor($_var_280 / 60);
		$_var_280 = $_var_280 % 60;
		$_var_282 .= $_var_283 . __('分', 'salong');
	}
	$_var_282 .= (int) $_var_280 . __('秒', 'salong');
	return $_var_282;
}
function deletehtml($_var_284)
{
	$_var_284 = trim($_var_284);
	$_var_284 = strip_tags($_var_284, '');
	return $_var_284;
}
add_filter('category_description', 'deletehtml');
function enable_more_buttons($_var_285)
{
	$_var_285[] = 'hr';
	$_var_285[] = 'del';
	$_var_285[] = 'sub';
	$_var_285[] = 'sup';
	$_var_285[] = 'fontselect';
	$_var_285[] = 'fontsizeselect';
	$_var_285[] = 'cleanup';
	$_var_285[] = 'styleselect';
	$_var_285[] = 'wp_page';
	$_var_285[] = 'anchor';
	$_var_285[] = 'backcolor';
	return $_var_285;
}
add_filter('mce_buttons_3', 'enable_more_buttons');
function wp_remove_open_sans_from_wp_core()
{
	wp_deregister_style('open-sans');
	wp_register_style('open-sans', false);
	wp_enqueue_style('open-sans', '');
}
add_action('init', 'wp_remove_open_sans_from_wp_core');
add_action('wp_login', 'set_last_login');
function set_last_login($_var_286)
{
	$_var_287 = get_userdatabylogin($_var_286);
	update_usermeta($_var_287->ID, 'last_login', current_time('mysql'));
}
function get_last_login($_var_288)
{
	$_var_289 = get_user_meta($_var_288, 'last_login', true);
	$_var_290 = get_option('date_format') . ' ' . get_option('time_format');
	$_var_291 = mysql2date($_var_290, $_var_289, false);
	return $_var_291;
}
if ($salong['switch_smtp']) {
	function mail_smtp($_var_292)
	{
		global $salong;
		$_var_292->IsSMTP();
		$_var_292->FromName = sanitize_text_field($salong['smtp_name']);
		$_var_292->From = sanitize_text_field($salong['smtp_username']);
		$_var_292->Username = sanitize_text_field($salong['smtp_username']);
		$_var_292->Password = sanitize_text_field($salong['smtp_password']);
		$_var_292->Host = sanitize_text_field($salong['smtp_host']);
		$_var_292->Port = intval($salong['smtp_port']);
		$_var_292->SMTPAuth = true;
		if ($salong['switch_secure']) {
			$_var_292->SMTPSecure = 'ssl';
		}
	}
	add_action('phpmailer_init', 'mail_smtp');
}
remove_filter('the_content', 'wptexturize');
if ($salong['switch_user_media']) {
	function my_upload_media($_var_293)
	{
		global $current_user, $pagenow;
		if (!is_a($current_user, 'WP_User')) {
			return;
		}
		if ('admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments') {
			return;
		}
		if (!current_user_can('manage_options') && !current_user_can('manage_media_library')) {
			$_var_293->set('author', $current_user->ID);
		}
		return;
	}
	add_action('pre_get_posts', 'my_upload_media');
	function my_media_library($_var_294)
	{
		if (strpos($_SERVER['REQUEST_URI'], '/wp-admin/upload.php') !== false) {
			if (!current_user_can('manage_options') && !current_user_can('manage_media_library')) {
				global $current_user;
				$_var_294->set('author', $current_user->id);
			}
		}
	}
	add_filter('parse_query', 'my_media_library');
}
if ($salong['switch_upload_filter']) {
	add_filter('wp_handle_upload_prefilter', 'custom_upload_filter');
	function custom_upload_filter($_var_295)
	{
		$_var_296 = pathinfo($_var_295['name']);
		$_var_297 = $_var_296['extension'];
		$_var_298 = date('YmdHis') . rand(10, 99);
		$_var_295['name'] = $_var_298 . '.' . $_var_297;
		return $_var_295;
	}
}
function salong_allow_contributor_uploads()
{
	if (current_user_can('contributor')) {
		$_var_299 = get_role('contributor');
		global $salong;
		if ($salong['switch_contributor_uploads']) {
			$_var_299->add_cap('upload_files');
			$_var_299->add_cap('edit_published_posts');
		} else {
			$_var_299->remove_cap('upload_files');
			$_var_299->remove_cap('edit_published_posts');
		}
	}
}
add_action('wp', 'salong_allow_contributor_uploads');
function salong_check_upload_mimes($_var_300)
{
	global $salong;
	$_var_301 = explode(' ', $salong['salong_upload_filetypes']);
	$_var_302 = array();
	foreach ($_var_301 as $_var_303) {
		foreach ($_var_300 as $_var_304 => $_var_305) {
			if ($_var_303 != '' && strpos($_var_304, $_var_303) !== false) {
				$_var_302[$_var_304] = $_var_305;
			}
		}
	}
	return $_var_302;
}
if (!is_multisite() && !is_admin() && !current_user_can('manage_options')) {
	add_filter('upload_mimes', 'salong_check_upload_mimes');
}
if (!is_admin() && !current_user_can('manage_options')) {
	add_filter('wp_handle_upload_prefilter', 'salong_images_size_upload');
}
function salong_images_size_upload($_var_306)
{
	global $salong;
	$_var_307 = $salong['image_width'];
	$_var_308 = $salong['image_height'];
	$_var_309 = array('image/jpeg', 'image/png', 'image/gif');
	if (!in_array($_var_306['type'], $_var_309)) {
		return $_var_306;
	}
	$_var_310 = getimagesize($_var_306['tmp_name']);
	$_var_311 = array('width' => $_var_307, 'height' => $_var_308);
	if ($_var_310[0] > $_var_311['width']) {
		$_var_306['error'] = sprintf(__('图片太大了，最大宽度是%spx，当前上传的图片宽度是%spx'), $_var_311['width'], $_var_310[0]);
	} elseif ($_var_310[1] > $_var_311['height']) {
		$_var_306['error'] = sprintf(__('图片太大了，最大高度是%spx，当前上传的图片高度是%spx'), $_var_311['height'], $_var_310[1]);
	}
	return $_var_306;
}
add_filter('user_contactmethods', 'my_user_contactmethods');
function my_user_contactmethods($_var_312)
{
	$_var_312['salong_qq'] = 'QQ';
	$_var_312['salong_wechat'] = __('微信', 'salong');
	$_var_312['salong_weibo'] = __('微博', 'salong');
	$_var_312['salong_locality'] = __('坐标', 'salong');
	$_var_312['salong_gender'] = __('性别', 'salong');
	$_var_312['salong_phone'] = __('手机', 'salong');
	$_var_312['salong_company'] = __('公司', 'salong');
	$_var_312['salong_position'] = __('职位', 'salong');
	$_var_312['salong_avatar'] = __('头像', 'salong');
	$_var_312['salong_alipay'] = __('支付宝收款二维码', 'salong');
	$_var_312['salong_wechatpay'] = __('微信收款二维码', 'salong');
	$_var_312['salong_open'] = __('公开显示', 'salong');
	return $_var_312;
}
function salong_is_weixin()
{
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		return true;
	}
	return false;
}
function salong_is_administrator()
{
	$_var_313 = wp_get_current_user();
	if (!empty($_var_313->roles) && in_array('administrator', $_var_313->roles)) {
		return 1;
	} else {
		return 0;
	}
}
if (is_admin()) {
	function rd_duplicate_post_as_draft()
	{
		global $wpdb;
		if (!(isset($_GET['post']) || isset($_POST['post']) || isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'])) {
			wp_die(__('没有文章可以复制！', 'salong'));
		}
		$_var_314 = isset($_GET['post']) ? $_GET['post'] : $_POST['post'];
		$_var_315 = get_post($_var_314);
		$_var_316 = wp_get_current_user();
		$_var_317 = $_var_316->ID;
		if (isset($_var_315) && $_var_315 != null) {
			$_var_318 = array('comment_status' => $_var_315->comment_status, 'ping_status' => $_var_315->ping_status, 'post_author' => $_var_317, 'post_content' => $_var_315->post_content, 'post_excerpt' => $_var_315->post_excerpt, 'post_name' => $_var_315->post_name, 'post_parent' => $_var_315->post_parent, 'post_password' => $_var_315->post_password, 'post_status' => 'draft', 'post_title' => $_var_315->post_title, 'post_type' => $_var_315->post_type, 'to_ping' => $_var_315->to_ping, 'menu_order' => $_var_315->menu_order);
			$_var_319 = wp_insert_post($_var_318);
			$_var_320 = get_object_taxonomies($_var_315->post_type);
			foreach ($_var_320 as $_var_321) {
				$_var_322 = wp_get_object_terms($_var_314, $_var_321, array('fields' => 'slugs'));
				wp_set_object_terms($_var_319, $_var_322, $_var_321, false);
			}
			$_var_323 = $wpdb->get_results("SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id={$_var_314}");
			if (count($_var_323) != 0) {
				$_var_324 = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) ";
				foreach ($_var_323 as $_var_325) {
					$_var_326 = $_var_325->meta_key;
					$_var_327 = addslashes($_var_325->meta_value);
					$_var_328[] = "SELECT {$_var_319}, '{$_var_326}', '{$_var_327}'";
				}
				$_var_324 .= implode(' UNION ALL ', $_var_328);
				$wpdb->query($_var_324);
			}
			wp_redirect(admin_url('post.php?action=edit&post=' . $_var_319));
			die;
		} else {
			wp_die(__('复制失败，找不到原始文章：', 'salong') . $_var_314);
		}
	}
	add_action('admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft');
	function rd_duplicate_post_link($_var_329, $_var_330)
	{
		if (current_user_can('edit_posts')) {
			$_var_329['duplicate'] = '<a href="admin.php?action=rd_duplicate_post_as_draft&amp;post=' . $_var_330->ID . '" title="Duplicate this item" rel="permalink">' . __('复制', 'salong') . '</a>';
		}
		return $_var_329;
	}
	add_filter('post_row_actions', 'rd_duplicate_post_link', 10, 2);
	if ($salong['salong_install_page'] && is_admin()) {
		function salong_add_page($_var_331, $_var_332, $_var_333, $_var_334 = '')
		{
			$_var_335 = get_pages();
			$_var_336 = false;
			foreach ($_var_335 as $_var_337) {
				if (strtolower($_var_337->post_name) == strtolower($_var_332)) {
					$_var_336 = true;
				}
			}
			if ($_var_336 == false) {
				$_var_338 = wp_insert_post(array('post_title' => $_var_331, 'post_type' => 'page', 'post_name' => $_var_332, 'comment_status' => 'closed', 'ping_status' => 'closed', 'post_content' => '', 'post_status' => 'publish', 'post_author' => 1, 'menu_order' => 0, 'meta_input' => array('page_shortcode' => $_var_333)));
				if ($_var_338 && $_var_334 != '') {
					update_post_meta($_var_338, '_wp_page_template', $_var_334);
				}
			}
		}
		function salong_add_pages()
		{
			global $pagenow;
			salong_add_page(__('文章', 'salong'), 'posts', '', 'template-post.php');
			salong_add_page(__('专题', 'salong'), 'topics', '', 'template-topic.php');
			salong_add_page(__('下载', 'salong'), 'downloads', '', 'template-download.php');
			salong_add_page(__('视频', 'salong'), 'videos', '', 'template-video.php');
			salong_add_page(__('外链跳转', 'salong'), 'go', '', 'template-go.php');
			salong_add_page(__('站点地图', 'salong'), 'sitemaps', '', 'template-sitemap.php');
			salong_add_page(__('所有用户', 'salong'), 'all-user', '[all_user]', '');
			salong_add_page(__('友情链接', 'salong'), 'links', '[link]', '');
			salong_add_page(__('客户留言', 'salong'), 'messages', '[message]', '');
			salong_add_page(__('联系我们', 'salong'), 'contact', '[map]', '');
			salong_add_page(__('标签云', 'salong'), 'tags', '[tag]', '');
			salong_add_page(__('置顶文章', 'salong'), 'sticky-posts', '[sticky_like post_state="sticky"]', '');
			salong_add_page(__('点赞排行', 'salong'), 'like-posts', '[sticky_like post_state="like"]', '');
		}
		add_action('load-themes.php', 'salong_add_pages');
	}
	if ($salong['post_column_metas'] != 0) {
		function post_column_views($_var_339)
		{
			global $salong;
			if (in_array('modified', $salong['post_column_metas'])) {
				$_var_339['post_modified'] = __('修改时间', 'salong');
			}
			if (in_array('views', $salong['post_column_metas'])) {
				$_var_339['post_views'] = __('浏览', 'salong');
			}
			if (in_array('likes', $salong['post_column_metas'])) {
				$_var_339['post_likes'] = __('点赞', 'salong');
			}
			if (in_array('post_id', $salong['post_column_metas'])) {
				$_var_339['post_id'] = __('ID', 'salong');
			}
			if (in_array('thumb', $salong['post_column_metas'])) {
				$_var_339['post_thumb'] = __('缩略图', 'salong');
			}
			if (in_array('baidusubmit', $salong['post_column_metas']) && $salong['switch_baidu_submit']) {
				$_var_339['baidusubmit'] = __('百度推送', 'salong');
			}
			return $_var_339;
		}
		add_filter('manage_post_posts_columns', 'post_column_views');
		add_filter('manage_topic_posts_columns', 'post_column_views');
		add_filter('manage_download_posts_columns', 'post_column_views');
		add_filter('manage_video_posts_columns', 'post_column_views');
		if (in_array('slide', $salong['post_column_metas'])) {
			function default_post_column_views($_var_340)
			{
				$_var_340['slide_recommend'] = __('幻灯片推送', 'salong');
				return $_var_340;
			}
			add_filter('manage_post_posts_columns', 'default_post_column_views');
		}
		if (in_array('time', $salong['post_column_metas'])) {
			function default_video_column_time($_var_341)
			{
				$_var_341['video_time'] = __('时长', 'salong');
				return $_var_341;
			}
			add_filter('manage_video_posts_columns', 'default_video_column_time');
		}
		function post_custom_column_views($_var_342, $_var_343)
		{
			switch ($_var_342) {
				case 'post_modified':
					$_var_344 = 'Y-m-d';
					$_var_345 = get_post(get_the_ID());
					echo get_the_modified_date($_var_344, $_var_345);
					break;
				case 'post_views':
					echo getPostViews(get_the_ID());
					break;
				case 'post_likes':
					$_var_346 = get_post_meta(get_the_ID(), 'salong_post_like_count', true);
					echo $_var_346 != 0 ? $_var_346 : 0;
					break;
				case 'video_time':
					echo get_post_meta(get_the_ID(), 'time', true);
					break;
				case 'post_id':
					echo get_the_ID();
					break;
				case 'slide_recommend':
					$_var_347 = get_post_meta(get_the_ID(), 'slide_recommend', true);
					echo $_var_347 ? __('已推送', 'salong') : __('未推送', 'salong');
					break;
				case 'post_thumb':
					$_var_348 = get_post_meta(get_the_ID(), 'thumb', true);
					if ($_var_348) {
						echo '<img style="width: 80px" src="' . $_var_348 . '" />';
					}
					break;
				case 'baidusubmit':
					$_var_349 = get_post_meta(get_the_ID(), 'baidusubmit', true);
					echo $_var_349 == 1 ? __('已推送', 'salong') : __('未推送', 'salong');
					break;
			}
		}
		add_action('manage_post_posts_custom_column', 'post_custom_column_views', 10, 2);
		add_action('manage_topic_posts_custom_column', 'post_custom_column_views', 10, 2);
		add_action('manage_download_posts_custom_column', 'post_custom_column_views', 10, 2);
		add_action('manage_video_posts_custom_column', 'post_custom_column_views', 10, 2);
		function register_post_column_views_sortable($_var_350)
		{
			global $salong;
			if (in_array('modified', $salong['post_column_metas'])) {
				$_var_350['post_modified'] = 'post_modified';
			}
			if (in_array('views', $salong['post_column_metas'])) {
				$_var_350['post_views'] = 'post_views';
			}
			if (in_array('likes', $salong['post_column_metas'])) {
				$_var_350['post_likes'] = 'post_likes';
			}
			if (in_array('time', $salong['post_column_metas'])) {
				$_var_350['video_time'] = 'video_time';
			}
			if (in_array('post_id', $salong['post_column_metas'])) {
				$_var_350['post_id'] = 'post_id';
			}
			if (in_array('thumb', $salong['post_column_metas'])) {
				$_var_350['post_thumb'] = 'post_thumb';
			}
			if (in_array('slide', $salong['post_column_metas'])) {
				$_var_350['slide_recommend'] = 'slide_recommend';
			}
			if (in_array('baidusubmit', $salong['post_column_metas'])) {
				$_var_350['baidusubmit'] = 'baidusubmit';
			}
			return $_var_350;
		}
		add_filter('manage_edit-download_sortable_columns', 'register_post_column_views_sortable');
		add_filter('manage_edit-topic_sortable_columns', 'register_post_column_views_sortable');
		add_filter('manage_edit-post_sortable_columns', 'register_post_column_views_sortable');
		add_filter('manage_edit-video_sortable_columns', 'register_post_column_views_sortable');
		if (in_array('modified', $salong['post_column_metas'])) {
			function sort_modified_column($_var_351)
			{
				if (isset($_var_351['orderby']) && 'modified' == $_var_351['orderby']) {
					$_var_351 = array_merge($_var_351, array('orderby' => 'post_modified'));
				}
				return $_var_351;
			}
			add_filter('request', 'sort_modified_column');
		}
		if (in_array('views', $salong['post_column_metas'])) {
			function sort_views_column($_var_352)
			{
				if (isset($_var_352['orderby']) && 'post_views' == $_var_352['orderby']) {
					$_var_352 = array_merge($_var_352, array('meta_key' => 'views', 'orderby' => 'meta_value_num'));
				}
				return $_var_352;
			}
			add_filter('request', 'sort_views_column');
		}
		if (in_array('thumb', $salong['post_column_metas'])) {
			function sort_thumb_column($_var_353)
			{
				if (isset($_var_353['orderby']) && 'post_thumb' == $_var_353['orderby']) {
					$_var_353 = array_merge($_var_353, array('meta_key' => 'thumb', 'orderby' => 'post_date'));
				}
				return $_var_353;
			}
			add_filter('request', 'sort_thumb_column');
		}
		if (in_array('likes', $salong['post_column_metas'])) {
			function sort_likes_column($_var_354)
			{
				if (isset($_var_354['orderby']) && 'post_likes' == $_var_354['orderby']) {
					$_var_354 = array_merge($_var_354, array('meta_key' => 'salong_post_like_count', 'orderby' => 'meta_value_num'));
				}
				return $_var_354;
			}
			add_filter('request', 'sort_likes_column');
		}
		if (in_array('time', $salong['post_column_metas'])) {
			function sort_video_time_column($_var_355)
			{
				if (isset($_var_355['orderby']) && 'video_time' == $_var_355['orderby']) {
					$_var_355 = array_merge($_var_355, array('meta_key' => 'time', 'orderby' => 'meta_value_num'));
				}
				return $_var_355;
			}
			add_filter('request', 'sort_video_time_column');
		}
		if (in_array('post_id', $salong['post_column_metas'])) {
			function sort_post_id_column($_var_356)
			{
				if (isset($_var_356['orderby']) && 'post_id' == $_var_356['orderby']) {
					$_var_356 = array_merge($_var_356, array('orderby' => 'ID'));
				}
				return $_var_356;
			}
			add_filter('request', 'sort_post_id_column');
		}
		if (in_array('slide', $salong['post_column_metas'])) {
			function sort_slide_recommend_column($_var_357)
			{
				if (isset($_var_357['orderby']) && 'slide_recommend' == $_var_357['orderby']) {
					$_var_357 = array_merge($_var_357, array('meta_key' => 'slide_recommend', 'orderby' => 'post_date'));
				}
				return $_var_357;
			}
			add_filter('request', 'sort_slide_recommend_column');
		}
		if (in_array('baidusubmit', $salong['post_column_metas']) && $salong['switch_baidu_submit']) {
			function sort_baidusubmit_column($_var_358)
			{
				if (isset($_var_358['orderby']) && 'baidusubmit' == $_var_358['orderby']) {
					$_var_358 = array_merge($_var_358, array('meta_key' => 'baidusubmit', 'orderby' => 'post_date'));
				}
				return $_var_358;
			}
			add_filter('request', 'sort_baidusubmit_column');
		}
		$get_tab = $_GET['post_type'];
		if ($get_tab != 'page' && $get_tab != 'product') {
			add_action('admin_head', 'my_column_width');
			function my_column_width()
			{
				echo '<style type="text/css">';
				echo '.widefat.posts th { width:10%}';
				echo '.widefat.posts th.column-post_likes,.widefat.posts th.column-post_views,.widefat.posts th.column-post_id { width:80px}';
				echo '.widefat.posts th.column-post_thumb,.widefat.posts th.column-post_modified { width:100px}';
				echo '.widefat.posts th.column-slide_recommend,.widefat.posts th.column-baidusubmit { width:120px}';
				echo '.widefat.posts th.column-title { width:40%}';
				echo '</style>';
			}
		}
	}
	function rudr_posts_taxonomy_filter()
	{
		global $typenow;
		if ($typenow == 'topic' || $typenow == 'download' || $typenow == 'video') {
			if ($typenow == 'topic') {
				$_var_359 = array('tcat');
			} else {
				if ($typenow == 'download') {
					$_var_359 = array('dcat');
				} else {
					if ($typenow == 'video') {
						$_var_359 = array('vcat');
					}
				}
			}
			foreach ($_var_359 as $_var_360) {
				$_var_361 = isset($_GET[$_var_360]) ? $_GET[$_var_360] : '';
				$_var_362 = get_taxonomy($_var_360);
				$_var_363 = strtolower($_var_362->labels->name);
				$_var_364 = get_terms($_var_360);
				if (count($_var_364) > 0) {
					echo "<select name='{$_var_360}' id='{$_var_360}' class='postform'>";
					echo "<option value=''>所有{$_var_363}</option>";
					foreach ($_var_364 as $_var_365) {
						echo '<option value=' . $_var_365->slug, $_var_361 == $_var_365->slug ? ' selected="selected"' : '', '>' . $_var_365->name . ' (' . $_var_365->count . ')</option>';
					}
					echo '</select>';
				}
			}
		}
	}
	add_action('restrict_manage_posts', 'rudr_posts_taxonomy_filter');
	function salong_video_field($_var_366)
	{
		global $salong, $post, $client_ali, $regionId;
		$_var_367 = get_post_type($_var_366);
		$_var_368 = get_post_meta($_var_366, 'youku_id', true);
		$_var_369 = get_post_meta($_var_366, 'ali_id', true);
		if (empty($_var_368) && empty($_var_369) && $_var_367 != 'video') {
			return;
		}
		$_var_370 = get_post_meta($_var_366, 'time', true);
		$_var_371 = get_post_meta($_var_366, 'thumb', true);
		if ($_var_368) {
			$_var_372 = get_youku_video('duration');
			if (!$_var_371) {
				add_post_meta($_var_366, 'thumb', get_youku_video('thumb'), true);
			}
		} else {
			if ($_var_369) {
				$_var_372 = salong_ali_video('Duration', $_var_369);
			}
		}
		if (!$_var_370) {
			add_post_meta($_var_366, 'time', salong_secsToStr($_var_372), true);
		}
	}
	add_action('save_post', 'salong_video_field');
	add_action('publish_product', 'add_custom_field_automatically');
	function add_custom_field_automatically($_var_373)
	{
		global $wpdb;
		if (!wp_is_post_revision($_var_373)) {
			add_post_meta($_var_373, 'salong_post_like_count', 0, true);
			add_post_meta($_var_373, 'views', 0, true);
		}
	}
	function change_footer_admin()
	{
		return '<a href="https://www.mobanzhu.com" title="Xiao伟破解必出精品">感谢使用Xiao伟破解的主题：mnews</a>';
	}
	add_filter('admin_footer_text', 'change_footer_admin', 9999);
	function change_footer_version()
	{
		return '&nbsp;';
	}
	add_filter('update_footer', 'change_footer_version', 9999);
}