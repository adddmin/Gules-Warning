<?php

//评论模板
if ( ! function_exists( 'salong_comment' ) ) :
function salong_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( '引用:', 'salong' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '编辑', 'salong' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="comment_body">
            <div class="comment_author">
                <?php $user_id = $comment->user_id; $user_name = $comment->comment_author; echo salong_get_avatar($user_id,$user_name); ?>
            </div>
            <div class="comment_info">
                <span class="author_name"><?php global $salong; if($user_id){ echo '<a href="'.get_author_posts_url($user_id).'" class="url">'.$user_name.'</a>'; }else if ($salong['switch_link_go']) {commentauthor();} else {comment_author_link();} ?></span><?php _e( '发布于：', 'salong' ); ?>&nbsp;<time class="datetime"><?php echo get_comment_date().'&nbsp;'.get_comment_time() ?></time>
                <?php $rate = get_comment_meta($comment->comment_ID, 'rate', true); if ($rate && $salong['switch_comment_rate']) { echo movie_grade($rate); } ?>
            </div>
            <div class="comment_btn">
                <?php if(get_post_type() != 'product'){ ?>
                <?php edit_comment_link( __( '编辑', 'salong' ), '', '' ); ?>
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( '回复', 'salong' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                <?php }else{ ?>
                <?php if (class_exists('woocommerce')){ do_action( 'woocommerce_review_before_comment_meta', $comment ); } ?>
                <?php } ?>
                <?php if($salong['switch_like_comment'] && $salong['switch_like_btn']){ echo get_post_likes_button( get_comment_ID(), 1 ); } ?>
            </div>
			<div class="comment_content"><?php comment_text(); ?></div>
            <?php if ( $comment->comment_approved == '0' ) : ?>
                <div class="comment_awaiting_moderation"><?php _e( '您的评论正在等等审核。', 'salong' ); ?></div>
            <?php endif; ?>
		</article>
	<?php break; endswitch;
}
endif;



/*ajax评论代码*/
define('AC_VERSION','1.0.0');

if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	wp_die('请升级到4.4以上版本');
}

if(!function_exists('fa_ajax_comment_scripts')) :

    function fa_ajax_comment_scripts(){
        wp_enqueue_script( 'ajax-comment', get_template_directory_uri() . '/js/comment-ajax.js', array( 'jquery' ), AC_VERSION , true );
        wp_localize_script( 'ajax-comment', 'ajaxcomment', array(
            'ajax_url'   => admin_url('admin-ajax.php'),
            'order' => get_option('comment_order'),
            'formpostion' => 'bottom', //默认为bottom，如果你的表单在顶部则设置为top。
        ) );
    }

endif;

if(!function_exists('fa_ajax_comment_err')) :

    function fa_ajax_comment_err($a) {
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: text/plain;charset=UTF-8');
        echo $a;
        exit;
    }

endif;

if(!function_exists('fa_ajax_comment_callback')) :

    function fa_ajax_comment_callback(){
        $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
        if ( is_wp_error( $comment ) ) {
            $data = $comment->get_error_data();
            if ( ! empty( $data ) ) {
            	fa_ajax_comment_err($comment->get_error_message());
            } else {
                exit;
            }
        }
        $user = wp_get_current_user();
        do_action('set_comment_cookies', $comment, $user);
        $GLOBALS['comment'] = $comment; //根据你的评论结构自行修改，如使用默认主题则无需修改
        global $salong,$product,$woocommerce;
        ?>
        <li <?php comment_class(); ?>>
            <article id="comment-<?php comment_ID(); ?>" class="comment_body">
                <div class="comment_author">
                    <?php $user_id = $comment->user_id; $user_name = $comment->comment_author; echo salong_get_avatar($user_id,$user_name); ?>
                </div>
                <div class="comment_info">
                    <?php
                        printf( __( '%1$s 发布于 %2$s', 'salong' ),
                            sprintf( '<span class="author_name">%s</span>', get_comment_author_link() ),
                            sprintf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                                esc_url( get_comment_link( $comment->comment_ID ) ),
                                get_comment_time( 'c' ),
                                sprintf( __( '%1$s %2$s', 'salong' ), get_comment_date(), get_comment_time() )
                            )
                        );
                    ?>
                    <?php if (class_exists('woocommerce')){ do_action( 'woocommerce_review_before_comment_meta', $comment ); } ?>
                </div>
                <div class="comment_content"><?php comment_text(); ?></div>
                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <div class="comment_awaiting_moderation"><?php _e( '您的评论正在等等审核。', 'salong' ); ?></div>
                <?php endif; ?>
            </article>
        </li>
        <?php die();
    }

endif;

add_action( 'wp_enqueue_scripts', 'fa_ajax_comment_scripts' );
add_action('wp_ajax_nopriv_ajax_comment', 'fa_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'fa_ajax_comment_callback');