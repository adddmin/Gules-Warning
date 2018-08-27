<?php
/**
 * 收件箱页面
 */
function salong_inbox(){
	global $wpdb,$wp_query,$current_user;
    
    if(is_admin()){
        $tab = '';
        $user_id = $current_user->ID;//登录用户 ID
    }else{
        $get_tab = $_GET['tab'];//获取连接中 tab 后面的参数
        $tab = 'tab='.$get_tab.'&';
        $curauth = $wp_query->get_queried_object();//当前用户
        $user_id = $curauth->ID;//当前用户 ID
    }
    
    // 查看信息
	if ( isset( $_GET['action'] ) && 'view' == $_GET['action'] && !empty( $_GET['id'] ) ){
		$id = $_GET['id'];

		check_admin_referer( "salong_view_inbox_msg_$id" );

		// 标志信息为已读
		$wpdb->update( $wpdb->prefix . 'pm', array( 'read' => 1 ), array( 'id' => $id ) );

		// 选择信息详情
		$msg = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'pm WHERE `id` = "' . $id . '" LIMIT 1' );
        $user = get_user_by('id',$msg->sender);
    ?>
    <div class="wrap view_box">
        <h2>
            <?php _e( '浏览收到的信息', 'salong' ); ?>
        </h2>
        <p>
            <a href="?<?php echo $tab; ?>page=salong_inbox">
                <?php _e( '返回收件箱', 'salong' ); ?>
            </a>
        </p>
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th class="manage-column" width="20%">
                        <?php _e( '详情', 'salong' ); ?>
                    </th>
                    <th class="manage-column">
                        <?php _e( '信息', 'salong' ); ?>
                    </th>
                    <th class="manage-column" width="15%">
                        <?php _e( '动作', 'salong' ); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php printf( __( '<b>发件人</b>：%s<br /><b>日期</b>：%s', 'salong' ), $user->display_name, $msg->date ); ?></td>
                    <td>
                        <?php printf( __( '<p><b>主题</b>：%s</p><p><b>内容</b>：%s</p>', 'salong' ), stripcslashes( $msg->subject ) , nl2br( stripcslashes( $msg->content ) ) ); ?>
                    </td>
                    <td>
                        <span class="delete">
							<a class="delete" href="<?php echo wp_nonce_url( "?$tab&page=salong_inbox&action=delete&id=$msg->id", 'salong_delete_inbox_msg_' . $msg->id ); ?>"><?php _e( '删除', 'salong' ); ?></a>
						</span>
                        <span class="reply">
							| <a class="reply" href="<?php echo wp_nonce_url( "?tab=message&page=salong_send&recipient=$msg->sender&id=$msg->id&subject=" .__('回复：','salong'). stripcslashes( $msg->subject ), 'salong_reply_inbox_msg_' . $msg->id ); ?>"><?php _e( '回复', 'salong' ); ?></a>
						</span>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="manage-column" width="20%">
                        <?php _e( '详情', 'salong' ); ?>
                    </th>
                    <th class="manage-column">
                        <?php _e( '信息', 'salong' ); ?>
                    </th>
                    <th class="manage-column" width="15%">
                        <?php _e( '动作', 'salong' ); ?>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php return;
    }

	// 信息被标记为已读
	if ( isset( $_GET['action'] ) && 'mar' == $_GET['action'] && !empty( $_GET['id'] ) ){
		$id = $_GET['id'];

		if ( !is_array( $id ) ){
			check_admin_referer( "salong_mar_inbox_msg_$id" );
			$id = array( $id );
		}else{
			check_admin_referer( "salong_bulk-action_inbox" );
		}
		$n = count( $id );
		$id = implode( ',', $id );
		if ( $wpdb->query( 'UPDATE ' . $wpdb->prefix . 'pm SET `read` = "1" WHERE `id` IN (' . $id . ')' ) ){
            $status = '<p class="successbox">'.sprintf(__('%s 条信息已标记为已读。', 'salong'), count($id)).'</p>';
		}else{
            $status = '<p class="warningbox">'.__('信息标记错误，请重试。', 'salong').'</p>';
		}
	}

	// 删除信息
	if ( isset( $_GET['action'] ) && 'delete' == $_GET['action'] && !empty( $_GET['id'] ) ){
		$id = $_GET['id'];

		if ( !is_array( $id ) ){
			check_admin_referer( "salong_delete_inbox_msg_$id" );
			$id = array( $id );
		}else{
			check_admin_referer( "salong_bulk-action_inbox" );
		}

		$error = false;
		foreach ( $id as $msg_id ){
			// 检查发件人是否已删除此消息。
			$sender_deleted = $wpdb->get_var( 'SELECT `deleted` FROM ' . $wpdb->prefix . 'pm WHERE `id` = "' . $msg_id . '" LIMIT 1' );

			// 为删除消息创建相应的查询。
			if ( $sender_deleted == 1 ){
				$query = 'DELETE from ' . $wpdb->prefix . 'pm WHERE `id` = "' . $msg_id . '"';
			}else{
				$query = 'UPDATE ' . $wpdb->prefix . 'pm SET `deleted` = "2" WHERE `id` = "' . $msg_id . '"';
			}

			if ( !$wpdb->query( $query ) ){
				$error = true;
			}
		}
        if ($error) {
            $status = '<p class="warningbox">'.__('信息删除错误，请重试。', 'salong').'</p>';
        } else {
            $status = '<p class="successbox">'.sprintf(__('%s 条信息已删除。', 'salong'), count($id)).'</p>';
        }
	}

	// 显示未被此用户删除的所有消息(删除状态!= 2)
	$msgs = $wpdb->get_results( 'SELECT `id`, `sender`, `subject`, `from_to`, `read`, `date` FROM ' . $wpdb->prefix . 'pm WHERE `recipient` = "' . $user_id . '" AND `deleted` != "2" ORDER BY `date` DESC' );
	?>
    <div class="wrap">
        <?php if(is_admin()){ ?>
        <h2>
            <?php _e( '收件箱', 'salong' ); ?>
        </h2>
        <?php } ?>
        <?php if ( !empty( $status ) ){
        echo '<div id="message" class="updated fade">'.$status.'</div>';
        }
        if ( empty( $msgs ) ){
            echo '<p class="infobox">', __( '收件箱中没有信息。', 'salong' ), '</p>';
        }else{
            $n = count( $msgs );
            $num_unread = 0;
            foreach ( $msgs as $msg ){
                if ( !( $msg->read ) ){
                    $num_unread++;
                }
            }
		echo '<p class="infobox">', sprintf( _n( '您有 %d 条站内信 （%d 条未读）。', '您有 %d 条站内信 （%d 条未读）。', $n, 'salong' ), $n, $num_unread ), '</p>';
		?>
        <form action="" method="get" class="inout_box">
            <?php wp_nonce_field( 'salong_bulk-action_inbox' ); ?>
            <input type="hidden" name="page" value="salong_inbox" />
            <!--前台添加 tab -->
            <?php if(!is_admin()){ ?>
            <input type="hidden" name="tab" value="message-inbox" />
            <?php } ?>

            <div class="tablenav">
                <select name="action">
                    <option value="-1" selected="selected"><?php _e( '批量操作', 'salong' ); ?></option>
                    <option value="delete"><?php _e( '删除', 'salong' ); ?></option>
                    <option value="mar"><?php _e( '标记为已读', 'salong' ); ?></option>
                </select> <input type="submit" class="button-secondary" value="<?php _e( '应用', 'salong' ); ?>" />
            </div>

            <table class="widefat fixed" cellspacing="0">
                <thead>
                    <tr>
                        <th class="manage-column check-column"><input type="checkbox" class="checkall" /></th>
                        <th class="manage-column" width="10%">
                            <?php _e( '发件人', 'salong' ); ?>
                        </th>
                        <th class="manage-column">
                            <?php _e( '主题', 'salong' ); ?>
                        </th>
                        <th class="manage-column" width="20%">
                            <?php _e( '日期', 'salong' ); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $msgs as $msg ){
                        $user = get_user_by('id',$msg->sender);
                        /*输出 from，to 中的 ID*/
                        $msg_title_data = json_decode($msg->from_to);
                    ?>
                    <tr>
                        <th class="check-column"><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $msg->id; ?>" />
                        </th>
                        <td class="name">
                            <a href="<?php echo get_author_posts_url($user->ID); ?>"<?php echo new_open_link(); ?>><?php echo $user->display_name; ?></a>
                        </td>
                        <td>
                            <?php if ( $msg->read ){
                            echo '<a href="', wp_nonce_url( "?$tab&page=salong_inbox&action=view&id=$msg->id", 'salong_view_inbox_msg_' . $msg->id ), '">', stripcslashes( $msg->subject ), '</a>';
                            }else{
                            echo '<a href="', wp_nonce_url( "?$tab&page=salong_inbox&action=view&id=$msg->id", 'salong_view_inbox_msg_' . $msg->id ), '"><b>', stripcslashes( $msg->subject ), '</b></a>';
                            }
                            ?>
                            <div class="row-actions">
                                <span>
                                    <a href="<?php echo wp_nonce_url( "?$tab&page=salong_inbox&action=view&id=$msg->id", 'salong_view_inbox_msg_' . $msg->id ); ?>"><?php _e( '查看', 'salong' ); ?></a>
                                </span>
                                <?php if ( !( $msg->read ) ){ ?>
                                <span>
                                    | <a href="<?php echo wp_nonce_url( "?$tab&page=salong_inbox&action=mar&id=$msg->id", 'salong_mar_inbox_msg_' . $msg->id ); ?>"><?php _e( '标记为已读', 'salong' ); ?></a>
                                </span>
                                <?php } ?>
                                <span class="delete">
                                    | <a class="delete" href="<?php echo wp_nonce_url( "?$tab&page=salong_inbox&action=delete&id=$msg->id", 'salong_delete_inbox_msg_' . $msg->id ); ?>"><?php _e( '删除', 'salong' ); ?></a>
                                </span>
                                <span class="reply">
                                    | <a class="reply" href="<?php echo wp_nonce_url( "?tab=message&page=salong_send&recipient=$msg->sender&id=$msg->id&subject=" .__('回复：','salong'). stripcslashes( $msg->subject ), 'salong_reply_inbox_msg_' . $msg->id ); ?>"><?php _e( '回复', 'salong' ); ?></a>
                                </span>
                            </div>
                        </td>
                        <td>
                            <?php echo $msg->date; ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="manage-column check-column"><input type="checkbox" class="checkall" /></th>
                        <th class="manage-column">
                            <?php _e( '发件人', 'salong' ); ?>
                        </th>
                        <th class="manage-column">
                            <?php _e( '主题', 'salong' ); ?>
                        </th>
                        <th class="manage-column">
                            <?php _e( '日期', 'salong' ); ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </form>
    <?php } ?>
    </div>
<!--自定义样式-->
<style type="text/css">
   .widefat .check-column{
       padding: 8px 12px 8px 3px;
   }
</style>
<?php } ?>
