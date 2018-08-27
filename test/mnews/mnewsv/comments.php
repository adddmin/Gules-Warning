<?php global $salong; ?>
<div id="comments">
    <?php if ( post_password_required() ) : ?>
    <p class="nopassword">
        <?php _e( '这篇文章是密码保护的，输入密码以查看任何评论。', 'salong' ); ?>
    </p>
</div>
<!-- #comments -->
<?php return; endif;?>

<?php if ( have_comments() ) : ?>
<section class="comment_title">
    <h3>
        <?php _e('评论：','salong'); ?>
    </h3>
    <?php if ( 'open'==$post->comment_status) {
        $my_email  = get_bloginfo ( 'admin_email' );
        $str       = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_approved = '1' AND comment_type = '' AND comment_author_email";
        $count_t   = $post->comment_count;
        $count_v   = $wpdb->get_var("$str != '$my_email'");
        $count_h   = $wpdb->get_var("$str = '$my_email'");
    ?>
    <span class="hint">
        <?php echo sprintf( __( '%s 条评论，访客：%s 条，站长：%s 条' , 'salong' ), esc_attr($count_t), esc_attr($count_v), esc_attr($count_h) ); ?>
        <?php if($numPingBacks>0) { ?><?php printf( __( '，当前引用：%s 条' , 'salong' ), esc_attr($numPingBacks)); ?><?php } ?>
    </span>
    <?php }else{ ?>
    <p class="hint">
        <?php _e( '评论已关闭，往期评论：', 'salong' ); ?>
    </p>
    <?php } ?>
</section>

<?php
if($salong['switch_comment_rate'] && $salong['switch_comment_ratio']){
    global $post,$comments; $args = array(
        'post_id' => $post->ID, // ignored (use post_id instead)
        'meta_key' => 'rate',
        'status'  => 'approve'
    );
    $comments = get_comments($args);
    $total = 0;
    $good = 0;
    $medium = 0;
    $difference = 0;
    foreach($comments as $comment) :
    $rate = get_comment_meta($comment->comment_ID, 'rate', true);
    if ($rate) {
        $total++;
        if ($rate == '5') {
            $good++;
        }else if ($rate == '4' || $rate == '3') {
            $medium++;
        }else if ($rate == '2' || $rate == '1') {
            $difference++;
        }
    }
    endforeach;
    if($total > 0){
        $ratio_good = round(($good/$total)*100,2);
        $ratio_medium = round(($medium/$total)*100,2);
        $ratio_difference = round(($difference/$total)*100,2);
    }else{
        $ratio_good = 0;
        $ratio_medium = 0;
        $ratio_difference = 0;
    }
    ?>
    <section class="comment_rate">
        <div class="title">
            <h4><span><?php echo $ratio_good.'%'; ?></span><?php _e( '好评', 'salong' ); ?></h4>
            <div class="rate_star">
                <div class="star_full" style="width: <?php echo $ratio_good.'%'; ?>">
                    <?php echo svg_star_full(); ?>
                    <?php echo svg_star_full(); ?>
                    <?php echo svg_star_full(); ?>
                    <?php echo svg_star_full(); ?>
                    <?php echo svg_star_full(); ?>
                </div>
                <div class="star_empty">
                    <?php echo svg_star_line(); ?>
                    <?php echo svg_star_line(); ?>
                    <?php echo svg_star_line(); ?>
                    <?php echo svg_star_line(); ?>
                    <?php echo svg_star_line(); ?>
                </div>
            </div>
        </div>
        <ul>
            <li>
                <span><?php echo _e( '好评：', 'salong' ).'('.$ratio_good.'%)'; ?></span>
                <div class="radio">
                    <div class="radio_up" style="width: <?php echo $ratio_good.'%'; ?>"></div>
                </div>
            </li>
            <li>
                <span><?php echo _e( '中评：', 'salong' ).'('.$ratio_medium.'%)'; ?></span>
                <div class="radio">
                    <div class="radio_up" style="width: <?php echo $ratio_medium.'%'; ?>"></div>
                </div>
            </li>
            <li>
                <span><?php echo _e( '差评：', 'salong' ).'('.$ratio_difference.'%)'; ?></span>
                <div class="radio">
                    <div class="radio_up" style="width: <?php echo $ratio_difference.'%'; ?>"></div>
                </div>
            </li>
        </ul>
    </section>
<?php } ?>

<!--评论列表-->
<ol class="commentlist">
    <?php wp_list_comments( array( 'callback' => 'salong_comment','type'=>'comment' ) ); ?>
</ol>
<!--评论分页-->
<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
<div class="pagination navigation">
    <?php echo paginate_comments_links( 'echo=0'); ?>
</div>
<?php endif; ?>


<?php endif; ?>

<?php if ( 'open'==$post->comment_status) {
    $fields = array(
        'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . $html_req . ' placeholder="'.__('昵称（必填）','salong').'" /></p>',
    'email'  => '<p class="comment-form-email"><input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . $html_req  . ' placeholder="'.__('邮箱（必填）','salong').'" /></p>',
    'url'    => '<p class="comment-form-url"><input id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" placeholder="'.__('网址','salong').'" /></p>',
    );
    if($salong['switch_comment_rate']){
        $args = array(
            'comment_notes_before' => '',
            'comment_notes_after'  => '',
            'comment_field' =>  '<div class="comment_rating"><label for="rate">'.__('评分','salong').'</label>
                <div class="comment_stars">
                    <a href="#" title="'.__('非常糟糕 - 1 star','salong').'" class="one-star" onclick="rateClick(1); return false;">'.svg_star_line().svg_star_full().'</a>
                    <a href="#" title="'.__('糟糕 - 2 星','salong').'" class="two-stars" onclick="rateClick(2); return false;">'.svg_star_line().svg_star_full().'</a>
                    <a href="#" title="'.__('好 - 3 星','salong').'" class="three-stars" onclick="rateClick(3); return false;">'.svg_star_line().svg_star_full().'</a>
                    <a href="#" title="'.__('非常好 - 4 星','salong').'" class="four-stars" onclick="rateClick(4); return false;">'.svg_star_line().svg_star_full().'</a>
                    <a href="#" title="'.__('优秀 - 5 星','salong').'" class="five-stars" onclick="rateClick(5); return false;">'.svg_star_line().svg_star_full().'</a>
                </div>
            </div>
            <input type="hidden" name="rate" id="rate" value="" />
            <p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" placeholder="'.$salong['comment_placeholder'].'" aria-required="true">' .
            '</textarea></p>',
            'fields' => apply_filters( 'comment_form_default_fields', $fields ),
        );
    }else{
        $args = array(
            'comment_notes_before' => '',
            'comment_notes_after'  => '',
            'comment_field' =>  '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" placeholder="'.$salong['comment_placeholder'].'" aria-required="true">' .
            '</textarea></p>',
            'fields' => apply_filters( 'comment_form_default_fields', $fields ),
        );
    }
    comment_form($args);
}else{ ?>
<p class="nocomments">
    <?php _e( '抱歉，评论已关闭！' , 'salong' ); ?>
</p>
<?php } ?>
</div>
