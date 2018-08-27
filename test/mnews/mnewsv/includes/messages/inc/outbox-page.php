<?php
/**
 * 收件箱页面
 */
function salong_outbox(){
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
    if (isset($_GET['action']) && 'view' == $_GET['action'] && !empty($_GET['id'])) {
        $id = $_GET['id'];

        check_admin_referer("salong_view_outbox_msg_$id");

        // 信息详情
        $msg = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'pm WHERE `id` = "' . $id . '" LIMIT 1');
        $user = get_user_by('id',$msg->recipient);
    ?>
    <div class="wrap view_box">
        <h2>
            <?php _e('浏览已发送信息', 'salong'); ?>
        </h2>

        <p>
            <a href="?<?php echo $tab; ?>page=salong_outbox">
                <?php _e('返回已发信息', 'salong'); ?>
            </a>
        </p>
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th class="manage-column" width="20%">
                        <?php _e('详情', 'salong'); ?>
                    </th>
                    <th class="manage-column">
                        <?php _e('信息', 'salong'); ?>
                    </th>
                    <th class="manage-column" width="15%">
                        <?php _e('动作', 'salong'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php printf(__('<b>收件人：</b>%s<br /><b>日期：</b>%s', 'salong'), $user->display_name, $msg->date); ?></td>
                    <td>
                        <?php printf(__('<p><b>主题：</b>%s</p><p><b>内容：</b>%s</p>', 'salong'), stripcslashes($msg->subject), nl2br(stripcslashes($msg->content))); ?>
                    </td>
                    <td>
                        <span class="delete">
                        <a class="delete"
                           href="<?php echo wp_nonce_url("?$tab&page=salong_outbox&action=delete&id=$msg->id", 'salong_delete_outbox_msg_' . $msg->id); ?>"><?php _e('删除', 'salong'); ?>
                        </a>
                    </span>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="manage-column" width="20%">
                        <?php _e('详情', 'salong'); ?>
                    </th>
                    <th class="manage-column">
                        <?php _e('信息', 'salong'); ?>
                    </th>
                    <th class="manage-column" width="15%">
                        <?php _e('动作', 'salong'); ?>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php return;
    }

    // 删除信息
    if (isset($_GET['action']) && 'delete' == $_GET['action'] && !empty($_GET['id'])) {
        $id = $_GET['id'];

        if (!is_array($id)) {
            check_admin_referer("salong_delete_outbox_msg_$id");
            $id = array($id);
        } else {
            check_admin_referer("salong_bulk-action_outbox");
        }
        $error = false;
        foreach ($id as $msg_id) {
            // 检查收件人是否删除了此消息。
            $recipient_deleted = $wpdb->get_var('SELECT `deleted` FROM ' . $wpdb->prefix . 'pm WHERE `id` = "' . $msg_id . '" LIMIT 1');
            // 为删除消息创建相应的查询。
            if ($recipient_deleted == 2) {
                $query = 'DELETE from ' . $wpdb->prefix . 'pm WHERE `id` = "' . $msg_id . '"';
            } else {
                $query = 'UPDATE ' . $wpdb->prefix . 'pm SET `deleted` = "1" WHERE `id` = "' . $msg_id . '"';
            }

            if (!$wpdb->query($query)) {
                $error = true;
            }
        }
        if ($error) {
            $status = '<p class="warningbox">'.__('信息删除错误，请重试。', 'salong').'</p>';
        } else {
            $status = '<p class="successbox">'.sprintf(__('%s 条信息已删除。', 'salong'), count($id)).'</p>';
        }
    }

    // 所有信息
    $msgs = $wpdb->get_results('SELECT `id`, `recipient`, `subject`, `date` FROM ' . $wpdb->prefix . 'pm WHERE `sender` = "' . $user_id . '" AND `deleted` != 1 ORDER BY `date` DESC');
    ?>
    <div class="wrap">
        <?php if(is_admin()){ ?>
        <h2>
            <?php _e('发件箱', 'salong'); ?>
        </h2>
        <?php } ?>
        <?php
        if (!empty($status)) {
            echo '<div id="message" class="updated fade">'.$status.'</div>';
        }
        if (empty($msgs)) {
            echo '<p class="infobox">', __( '发件箱中没有信息。', 'salong' ), '</p>';
        } else {
            $n = count($msgs);
            echo '<p class="infobox">', sprintf(_n('您写了 %d 条站内信。', '您写了 %d 条站内信。', $n, 'salong'), $n), '</p>';
        ?>
            <form action="" method="get" class="inout_box">
                <?php wp_nonce_field('salong_bulk-action_outbox'); ?>
                <input type="hidden" name="action" value="delete" />
                <input type="hidden" name="page" value="salong_outbox" />
                
                <!--前台添加 tab -->
                <?php if(!is_admin()){ ?>
                <input type="hidden" name="tab" value="message-outbox" />
                <?php } ?>

                <div class="tablenav">
                    <input type="submit" class="button-secondary" value="<?php _e('删除选择', 'salong'); ?>" />
                </div>

                <table class="widefat fixed" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="manage-column check-column"><input type="checkbox" class="checkall" /></th>
                            <th class="manage-column" width="10%">
                                <?php _e('收件人', 'salong'); ?>
                            </th>
                            <th class="manage-column">
                                <?php _e('主题', 'salong'); ?>
                            </th>
                            <th class="manage-column" width="20%">
                                <?php _e('日期', 'salong'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($msgs as $msg) {
                            $user = get_user_by('id',$msg->recipient);
                        ?>
                        <tr>
                            <th class="check-column"><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $msg->id; ?>" />
                            </th>
                            <td class="name">
                                <a href="<?php echo get_author_posts_url($user->ID); ?>"<?php echo new_open_link(); ?>><?php echo $user->display_name; ?></a>
                            </td>
                            <td>
                                <?php echo '<a href="', wp_nonce_url("?$tab&page=salong_outbox&action=view&id=$msg->id", 'salong_view_outbox_msg_' . $msg->id), '">', stripcslashes($msg->subject), '</a>'; ?>
                                <div class="row-actions">
                                    <span>
                                        <a href="<?php echo wp_nonce_url("?$tab&page=salong_outbox&action=view&id=$msg->id", 'salong_view_outbox_msg_' . $msg->id); ?>"><?php _e('查看', 'salong'); ?></a>
                                    </span>
                                    <span class="delete">
                                        | <a class="delete" href="<?php echo wp_nonce_url("?$tab&page=salong_outbox&action=delete&id=$msg->id", 'salong_delete_outbox_msg_' . $msg->id); ?>"><?php _e('删除', 'salong'); ?></a>
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
                                <?php _e('收件人', 'salong'); ?>
                            </th>
                            <th class="manage-column">
                                <?php _e('主题', 'salong'); ?>
                            </th>
                            <th class="manage-column">
                                <?php _e('日期', 'salong'); ?>
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
