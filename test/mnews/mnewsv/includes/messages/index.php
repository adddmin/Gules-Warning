<?php
/*
Plugin Name: 萨龙网络站内信系统
Plugin URI: https://salongweb.com
Description: 允许WordPress博客的成员发送和接收私人信息
Version: 1.0
Author: 萨龙龙
Author URI: https://yfdxs.com
*/


include get_template_directory() . '/includes/messages/inc/inbox-page.php';
include get_template_directory() . '/includes/messages/inc/send-page.php';
include get_template_directory() . '/includes/messages/inc/outbox-page.php';

if ( is_admin() ){
	include get_template_directory() . '/includes/messages/inc/options.php';
}

add_action( 'admin_notices', 'salong_notify' );
add_action( 'admin_bar_menu', 'salong_adminbar', 300 );
add_action( 'wp_ajax_salong_get_users', 'salong_get_users' );

/**
 * 显示新站内信通知。
 */
function salong_notify(){
	global $wpdb, $current_user;

	// 获取未读消息的数量。
	$num_unread = (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'pm WHERE `recipient` = "' . $current_user->ID . '" AND `read` = 0 AND `deleted` != "2"' );

	if ( !$num_unread )
		return;

	printf(
		'<div id="message" class="error"><p><b>%s</b> <a href="%s">%s</a></p></div>',
		sprintf( _n( '你有 %d 条新信息！', '你有 %d 条新信息！', $num_unread, 'salong' ), $num_unread ),
		admin_url( 'admin.php?page=salong_inbox' ),
		__( '点击这里进入收件箱', 'salong' )
	);
}

/**
 * 在工具栏中显示未读消息的数量。
 */
function salong_adminbar(){
	global $wp_admin_bar,$wpdb, $current_user;

	// 获取未读消息的数量。
	$num_unread = (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'pm WHERE `recipient` = "' . $current_user->ID . '" AND `read` = 0 AND `deleted` != "2"' );

	if ( $num_unread && is_admin_bar_showing() ){
		$wp_admin_bar->add_menu( array(
			'id'    => 'rwpm',
			'title' => sprintf( _n( '您有 %d 条新信息！', '您有 %d 条新信息！', $num_unread, 'salong' ), $num_unread ),
			'href'  => admin_url( 'admin.php?page=salong_inbox' ),
			'meta'  => array( 'class' => "salong_newmessages" ),
		) );
	}
}

/**
 * Ajax回调函数获取用户列表。
 */
function salong_get_users(){
	$keyword = trim( strip_tags( $_POST['term'] ) );
	$values  = array();
	$args    = array(
        'search' => '*' . $keyword . '*',
        'fields' => 'all_with_meta'
    );
	$results_search_users = get_users( $args );
	$results_search_users = apply_filters( 'salong_recipients', $results_search_users );
	if ( !empty( $results_search_users ) ){
		foreach ( $results_search_users as $result ){
			$values[] = $result->display_name;
		}
	}
	die( json_encode( $values ) );
}
