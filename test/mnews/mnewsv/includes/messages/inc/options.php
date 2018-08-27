<?php
add_action( 'admin_menu', 'salong_add_menu' );

/**
 * Add Option page and PM Menu
 *
 * @return void
 */
function salong_add_menu()
{
	global $wpdb, $current_user;

	// Get number of unread messages
	$num_unread = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'pm WHERE `recipient` = "' . $current_user->ID . '" AND `read` = 0 AND `deleted` != "2"' );

	if ( empty( $num_unread ) )
		$num_unread = 0;

	// Add Private Messages Menu
	add_menu_page( __( '站内信', 'salong' ), __( '信息', 'salong' ) . "<span class='update-plugins count-$num_unread'><span class='plugin-count'>$num_unread</span></span>", 'read', 'salong_inbox', 'salong_inbox', 'dashicons-email-alt',25 );

	// Inbox page
	$inbox_page = add_submenu_page( 'salong_inbox', __( '收件箱', 'salong' ), __( '收件箱', 'salong' ), 'read', 'salong_inbox', 'salong_inbox' );
	add_action( "admin_print_styles-{$inbox_page}", 'salong_admin_print_styles_inbox' );

	// Outbox page
	$outbox_page = add_submenu_page( 'salong_inbox', __( '发件箱', 'salong' ), __( '发件箱', 'salong' ), 'read', 'salong_outbox', 'salong_outbox' );
	add_action( "admin_print_styles-{$outbox_page}", 'salong_admin_print_styles_outbox' );

	// Send page
	$send_page = add_submenu_page( 'salong_inbox', __( '发送站内信息', 'salong' ), __( '发送信息', 'salong' ), 'read', 'salong_send', 'salong_send' );
	add_action( "admin_print_styles-{$send_page}", 'salong_admin_print_styles_send' );
}

/**
 * Enqueue scripts and styles for inbox page
 *
 * @return void
 */
function salong_admin_print_styles_inbox(){
	do_action( 'salong_print_styles', 'inbox' );
}

/**
 * Enqueue scripts and styles for outbox page
 *
 * @return void
 */
function salong_admin_print_styles_outbox(){
	do_action( 'salong_print_styles', 'outbox' );
}

/**
 * Enqueue scripts and styles for send page
 *
 * @return void
 */
function salong_admin_print_styles_send(){
	wp_enqueue_script( 'salong_js', get_template_directory_uri() . '/includes/messages/js/script.js', array( 'jquery-ui-autocomplete' ) );
	do_action( 'salong_print_styles', 'send' );
}
