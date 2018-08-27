<?php
//添加主题教程到外观
add_action('admin_menu', 'theme_tutorial');
function theme_tutorial() {
	add_submenu_page('themes.php', __('主题文档'), __('主题文档'), 'manage_options', 'theme_tutorial', 'theme_tutorial_options' );
}
function theme_tutorial_options() {
    echo '<div class="wrap"><div class="opwrap"><iframe src="https://salongweb.com/docs/mnews" width="100%" height="800" frameborder="0"></iframe></div></div>';
};