<?php
/**
 * 发送站内信页面
 */
function salong_send(){
	global $salong, $wpdb, $current_user;
    /*对前后台进行判断*/
    if(is_admin()){
        $multiple = 'multiple="multiple"';
    }else{
        $multiple = '';
    }
	?>
    <div class="wrap">
        <?php if(is_admin()){ ?>
        <h2>
            <?php _e( '发送站内信', 'salong' ); ?>
        </h2>
        <?php } ?>
        <?php
    
    
    /*角色发送站内信数量*/
    $role           = $current_user->roles[0];
    $sender         = $current_user->ID;
    $option_name    = 'messages_count_'.$role;
    $messages_count = $salong[$option_name];
    /*发送私信总数*/
    $total = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'pm WHERE `sender` = "' . $sender . '"' );
    if(!is_admin() && !salong_is_administrator()){
        echo '<p class="infobox">'.sprintf( __( '您最多可发送 %s 条信息，已经发送 %s 条信息。', 'salong' ), $messages_count,$total ).'</p>';
    }
    
    if ( $_REQUEST['page'] == 'salong_send' && isset( $_POST['submit'] ) ){
		$error = false;
		$status = array();

        
        // 检查当前用户的总站内信是否超过限制。
		if ( ( $messages_count != 0 ) && ( $total >= $messages_count ) ){
			$error = true;
			$status = '<p class="warningbox">'.__( '您的站内信数量已超过限制，发送新的站内信前请删除掉一些原来的信息。', 'salong' ).'</p>';
		}

		// 获取没有html标记的输入字段，所有的都被转义。
		$subject = strip_tags( $_POST['subject'] );
		$content = $_POST['content'];
        /*对输入方式进行判断，输入提示需要转化显示名为 ID*/
		$recipient = $salong['messages_recipient_select'] == 'autosuggest' ? explode( ',', $_POST['recipient'] ) : $_POST['recipient'];
		$recipient = array_map( 'strip_tags', $recipient );

		// 允许过滤的内容
		$content = apply_filters( 'salong_content_send', $content );
		
		// 在wp中自动删除斜线。
		$subject = stripslashes( $subject );
		$content = stripslashes( $content );
		$recipient = array_map( 'stripslashes', $recipient );

		// Escape sql
		$subject = esc_sql( $subject );
		$content = esc_sql( $content );
		$recipient = array_map( 'esc_sql', $recipient );

		// 删除重复和空收件人。
		$recipient = array_unique( $recipient );
		$recipient = array_filter( $recipient );
        
		// 检查输入字段
		if ( empty( $recipient ) ){
			$error = true;
			$status = '<p class="warningbox">'.__( '请输入收件人用户名。', 'salong' ).'</p>';
		}
		if ( empty( $subject ) ){
			$error = true;
			$status = '<p class="warningbox">'.__( '请输入信息的标题。', 'salong' ).'</p>';
		}
		if ( empty( $content ) ){
			$error = true;
			$status = '<p class="warningbox">'.__( '请输入信息内容。', 'salong' ).'</p>';
		}

		if ( !$error ){
			$numOK = $numError = 0;
            
            /*设置私信是创建还是回复方式*/
            if ( empty( $_GET['id'] ) ){
                $type = 'create';
            }else{
                $type = 'reply';
            }
			foreach ( $recipient as $rec ){
                
                /*如果是输入提示，则转换显示名为 ID*/
                if($salong['messages_recipient_select'] == 'autosuggest'){
                    $rec = $wpdb->get_var( "SELECT id FROM $wpdb->users WHERE display_name = '$rec' LIMIT 1" );
                }
				
				$new_message = array(
					'id'         => NULL,
					'subject'    => $subject,
					'content'    => $content,
					'sender'     => $sender,
					'recipient'  => $rec,
					'type'       => $type,
					'from_to'    => '{"from":'.$sender.',"to":'.$rec.'}',
					'date'       => current_time( 'mysql' ),
					'read'       => 0,
					'deleted'    => 0
				);
				// 插入到数据库
				if ( $wpdb->insert( $wpdb->prefix . 'pm', $new_message, array( '%d', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%d', '%d' ) ) ){
					$numOK++;
					unset( $_REQUEST['recipient'], $_REQUEST['subject'], $_REQUEST['content'] );

					// 发送邮件给用户
                    global $salong;
					if ( $salong['switch_messages_email'] ){
                        // 得到用户
                        $user_sender = get_user_by('id',$sender);//发件人
                        $user_rec = get_user_by('id',$rec);//收件人

						// 用值替换标签
						$tags = array( '%BLOG_NAME%', '%BLOG_ADDRESS%', '%SENDER%', '%INBOX_URL%' );
						$replacement = array( get_bloginfo( 'name' ), get_bloginfo( 'admin_email' ), $user_sender->display_name, add_query_arg('tab', 'message-inbox', get_author_posts_url($user_rec->ID)) );

						$email_name     = str_replace( $tags, $replacement, $salong['messages_email_name'] );
						$email_address  = str_replace( $tags, $replacement, $salong['messages_email_address'] );
						$email_subject  = str_replace( $tags, $replacement, $salong['messages_email_subject'] );
						$email_body     = str_replace( $tags, $replacement, $salong['messages_email_content'] );

						// 设置默认电子邮件的名称和地址如果错过。
						if ( empty( $email_name ) )
							$email_name = get_bloginfo( 'name' );

						if ( empty( $email_address ) )
							$email_address = get_bloginfo( 'admin_email' );

						$email_subject = strip_tags( $email_subject );
						if ( get_magic_quotes_gpc() )
						{
							$email_subject = stripslashes( $email_subject );
							$email_body = stripslashes( $email_body );
						}
						$email_body = nl2br( $email_body );

						$recipient_email = $user_rec->user_email;
						$mailtext = "<html><head><title>$email_subject</title></head><body>$email_body</body></html>";

						// 设置标题发送html电子邮件。
						$headers = "To: $recipient_email\r\n";
						$headers .= "From: $email_name <$email_address>\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= 'Content-Type: ' . get_bloginfo( 'html_type' ) . '; charset=' . get_bloginfo( 'charset' ) . "\r\n";

						wp_mail( $recipient_email, $email_subject, $mailtext, $headers );
					}
				}else{
					$numError++;
				}
			}

			$status = '<p class="successbox">'.sprintf( __( '%s 条信息已成功发送，%s 条信息已成功发送。', 'salong' ), $numOK,$numError ).'</p>';
		}

		echo '<div id="message" class="updated fade">'.$status.'</div>';
	}
	?>
    <?php do_action( 'salong_before_form_send' ); ?>
    <form method="post" action="" id="send-form" enctype="multipart/form-data">
        <input type="hidden" name="page" value="salong_send" />
        <table class="form-table">
            <tr>
                <th width="10%">
                    <?php _e( '收件人', 'salong' ); ?>
                </th>
                <td>
                    <?php
                    /*获取收件人 ID*/
                    $get_recipient = $_GET['recipient'];

                    // 如果消息没有发送(错误)或在回复时，所有输入都将被保存。
                    $recipient = !empty( $_POST['recipient'] ) ? $_POST['recipient'] : ( !empty( $get_recipient )
                        ? $get_recipient : '' );

                    // 如果需要带斜杠
                    $subject = isset( $_REQUEST['subject'] ) ? ( get_magic_quotes_gpc() ? stripcslashes( $_REQUEST['subject'] )
                        : $_REQUEST['subject'] ) : '';
                    $subject = urldecode( $subject );

                    if ( empty( $_GET['id'] ) ){
                        $content = isset( $_REQUEST['content'] ) ?  $_REQUEST['content']  : '';
                    }else{
                        $id = $_GET['id'];
                        $msg = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'pm WHERE `id` = "' . $id . '" LIMIT 1' );
                        $user = get_user_by('id',$msg->sender);

                        $content = '<p>&nbsp;</p>';
                        $content .= '<p>---</p>';
                        $content .= '<p><em>' . __( '在：', 'salong' ) . $msg->date . "\t" . $user->display_name . __( '写到：', 'salong' ) . '</em></p>';
                        $content .= wpautop( $msg->content );
                        $content  = stripslashes( $content );
                    }

                    // 从下拉列表中选择收件人，获取所有用户。
                    $args = array(
                        'order'   => 'ASC',
                        'orderby' => 'display_name'
                    );
                    $values = get_users( $args );
                    $values = apply_filters( 'salong_recipients', $values );
                    ?>
                    <!--选择收件人不同的方式-->
                    <?php if ( $salong['messages_recipient_select'] == 'autosuggest' ){
                        if($get_recipient){
                            $user_recipient = get_userdata($get_recipient);
                        ?>
                            <!--回复时收件人-->
                            <input id="recipient" type="text" name="recipient" class="large-text" value="<?php echo $user_recipient->display_name; ?>" />
                        <?php }else{ ?>
                            <!--默认发送私信时的输入框-->
                            <input id="recipient" type="text" name="recipient" class="large-text" placeholder="<?php _e('请输入收件人','salong'); ?>" />
                        <?php } ?>
                    <?php }else{ ?>
                    <select name="recipient[]" <?php echo $multiple; ?>>
                       <option value=""><?php _e('请选择收件人','salong'); ?></option>
                        <?php
                        foreach ( $values as $value ){
                            $selected = ( $value->ID == $recipient ) ? ' selected="selected"' : '';
                            echo "<option value='$value->ID'$selected>$value->display_name</option>";
                        }
                        ?>
                    </select>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th width="10%">
                    <?php _e( '主题', 'salong' ); ?>
                </th>
                <td><input type="text" name="subject" value="<?php echo $subject; ?>" class="large-text" placeholder="<?php _e('请输入主题','salong'); ?>" /></td>
            </tr>
            <tr>
                <th width="10%">
                    <?php _e( '内容', 'salong' ); ?>
                </th>
                <td>
                    <?php  wp_editor( $content, 'rw-text-editor', $settings = array( 'textarea_name' => 'content' ) );?>
                </td>
            </tr>
            <?php do_action( 'salong_form_send' ); ?>
        </table>
        <p class="submit"><input type="submit" value="<?php _e('发送','salong'); ?>" class="button-primary" id="submit" name="submit"></p>
    </form>
    <?php do_action( 'salong_after_form_send' ); ?>
    </div>
<?php if(is_admin()){ ?>
<!--自定义样式-->
<style type="text/css">
    #send-form .form-table th{
        width: auto;
    }
    #send-form .form-table input.large-text{
        padding: 8px;
    }
    #send-form .form-table td{
        padding: 0;
    }
    #send-form .form-table select{
        width: 99%;
    }
</style>
<?php }else{ ?>
<script type="text/javascript">
jQuery( document ).ready( function ( $ )
{

	/**
	 * Split string into multiple values, separated by commas
	 *
	 * @param val
	 *
	 * @return array
	 */
	function split( val )
	{
		return val.split( /,\s*/ );
	}

	/**
	 * Extract string Last into multiple values
	 * @param term
	 *
	 */
	function extract_last( term )
	{
		return split( term ).pop();
	}

	$( '#recipient' ).autocomplete( {
		source: function ( request, response )
		{
			var data = {
				action: 'salong_get_users',
				term  : extract_last( request.term )
			};
			$.post( ajaxurl, data, function ( r )
			{
				response( r );
			}, 'json' );
		},
		select: function ( event, ui )
		{
			var terms = split( this.value );
			terms.pop();
			terms.push( ui.item.value );
			terms.push( "" );
			this.value = terms.join( "," );
			return false;
		}
	} );

} );
</script>
<?php } ?>
<?php }
