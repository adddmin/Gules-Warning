<?php
/*
推荐作者小工具
*/

class SlongUser extends WP_Widget {

/*  构造函数
/* ------------------------------------ */
	function __construct() {
		parent::__construct( false, __('Salong-推荐作者','salong'), array('description' => __('推荐作者小工具，请到主题选项中设置选择。','salong'), 'classname' => 'widget_salong_user') );;	
	}
	
/*  小工具
/* ------------------------------------ */
	public function widget($args, $instance) {
		extract( $args );
		$instance['title']?NULL:$instance['title']='';
		$title = apply_filters('widget_title',$instance['title']);
		$output = $before_widget."\n";
		if($title)
			$output .= $before_title.$title.$after_title;
		ob_start();
	
?>
<?php global $salong; $blog_id = get_current_blog_id(); ?>
<ul>
   <?php $blogusers=get_users( 'blog_id='.$blog_id.'&orderby=post_count&order&DESC&include='.$instance['author_id'].'');foreach ($blogusers as $user) { ?>
    <?php global $wp_query;$curauth_name = $user->display_name;$curauth_description = $user->user_description;$curauth_id=$user->ID; ?>
    <li>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>" title="<?php echo $curauth_name; ?><?php if($curauth_description){ echo '｜';}?><?php echo $curauth_description; ?>" class="author_name"<?php echo new_open_link(); ?>>
            <!--头像-->
            <?php echo salong_get_avatar($curauth_id,$curauth_name); ?>
            <!--名称-->
            <h4><?php echo $curauth_name.salong_add_v($curauth_id); ?></h4>
        </a>
        <!--文章-->
        <?php $args=array('author' => $curauth_id,'post_type' => array('post','gallery','video'),'post_status' => 'publish','posts_per_page' => 1,'ignore_sticky_posts' => 1);$my_query = null;$my_query = new WP_Query($args);if( $my_query->have_posts() ) {while ($my_query->have_posts()) : $my_query->the_post(); ?><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="new_post"><?php the_title(); ?></a><?php endwhile;}wp_reset_query(); ?>
        <?php if($salong['switch_follow_btn']){ echo salong_get_follow_unfollow_links($curauth_id,$allow=0); } ?>
    </li>
    <?php } ?>
</ul>
<?php
		$output .= ob_get_clean();
		$output .= $after_widget."\n";
		echo $output;
	}
	
/*  更新小工具
/* ------------------------------------ */
	public function update($new,$old) {
		$instance = $old;
		$instance['title'] = strip_tags($new['title']);
		$instance['author_id'] = strip_tags($new['author_id']);
		return $instance;
	}

/*  小工具表单
/* ------------------------------------ */
	public function form($instance) {
		// 默认设置
		$defaults = array(
			'title' 			=> __('推荐作者','salong'),
			'author_id' 		=> '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('标题：','salong'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
		</p>
        <p>
            <label style="width: 100%; display: inline-block;" for="<?php echo $this->get_field_id("author_id"); ?>"><?php _e('作者 ID(多个 ID 间以英文逗号隔开)：','salong'); ?></label>
            <input style="width:100%;" id="<?php echo $this->get_field_id("author_id"); ?>" name="<?php echo $this->get_field_name("author_id"); ?>" type="text" value="<?php echo esc_attr($instance["author_id"]); ?>" size='3' />
        </p>

<?php

}

}

/*  注册小工具
/* ------------------------------------ */
if ( ! function_exists( 'salong_register_widget_user' ) ) {

	function salong_register_widget_user() { 
		register_widget( 'SlongUser' );
	}
	
}
add_action( 'widgets_init', 'salong_register_widget_user' );
