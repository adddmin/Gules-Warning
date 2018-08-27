<?php 
//邮件样式
/* comment_mail_notify v1.0 by willin kan. (所有回覆都發郵件) */
function comment_mail_notify($comment_id) {
 $comment = get_comment($comment_id);
 $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
 $spam_confirmed = $comment->comment_approved;
 if (($parent_id != '') && ($spam_confirmed != 'spam')) {
 $wp_email = 'hi@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); //e-mail 發出點, no-reply 可改為可用的 e-mail.
 $to = trim(get_comment($parent_id)->comment_author_email);
 $subject = '[' . get_option("blogname") . ']的信使给您送信来啦!';
 $message = '
 <div style="background-color:#fff; border:1px solid #135685; color:#111;border-radius:8px; font-size:13px; width:702px; margin:0 auto; margin-top:10px; font-family:微软雅黑, Arial;">
 <div style="background:#135685; width:100%; height:60px; color:white; border-radius:6px 6px 0 0; "><span style="height:60px; line-height:60px; margin-left:30px; font-size:20px;">您在 <a style="text-decoration:none; color:#00bbff;font-weight:600;" href="' . get_option('home') . '" target="_blank">' . get_option('blogname') . '</a> 博客上的留言有最新回复啦！</span></div>
 <div style="width:90%; margin:0 auto">
 <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
 <p>您曾在《 <a style="text-decoration:none; color:#0473b2" href="' . get_option('home') . '">' . get_option('blogname') . '</a>》的文章(或页面) 《' . get_the_title($comment->comment_post_ID) . '》 的评论:</P>
 <p style="background-color: #f2f2f2;border-radius:6px;border: 1px solid #DDD;padding: 10px 5px;margin: 15px 0;">'. nl2br(get_comment($parent_id)->comment_content) . '</p>
 <p>' . trim($comment->comment_author) . ' 给您的回复如下:</p>
 <p style="background-color: #f2f2f2;border-radius:6px;border: 1px solid #DDD;padding:10px 5px;margin: 15px 0;">'. nl2br($comment->comment_content) . '</p>
 <p>您可以 <a style="text-decoration:none; color:#0473b2" href="' . htmlspecialchars(get_comment_link($parent_id)) . '">点击查看 </a>文章（或页面）中更多完整的回复內容；</p>
 <p>期待您再次光临 <a style="text-decoration:none; color:#0473b2" href="' . get_option('home') . '">' . get_option('blogname') . '</a>，谢谢您对《 <a style="text-decoration:none; color:#0473b2" href="' . get_option('home') . '">' . get_option('blogname') . '</a>》的厚爱！</p>
 <p style="color:#0473b2;">(温馨提示：本邮件由系统自动发出，请不要回复。如果需要联系本站管理员，可以到博客留言或给站长发Email，站长Email：'. get_bloginfo('admin_email') .' )</p>
 </div>
 </div>';
 $message = convert_smilies($message);
 $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
 $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
 wp_mail( $to, $subject, $message, $headers );
 //echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing
 }
}
add_action('comment_post', 'comment_mail_notify');
// -- END ----------------------------------------

// 自动勾选 
function add_checkbox() {
  echo '<p class="comment_notify"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" /><label id="comment_mail_title" for="comment_mail_notify">有人回复时邮件通知我</label></p>';
}
add_action('comment_form_after_fields', 'add_checkbox');
 ?>