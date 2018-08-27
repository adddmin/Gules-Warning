<?php
/*
每日一句小工具
*/

class SlongWord extends WP_Widget {

/*  构造函数
/* ------------------------------------ */
	function __construct() {
		parent::__construct( false, __('Salong-每日一句','salong'), array('description' => __('每日一句小工具','salong'), 'classname' => 'widget_salong_word') );;	
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
<div class="oneword">
    <p><?php echo esc_attr($instance["word_text"]); ?></p>
    <span><?php echo esc_attr($instance["word_author"]); ?></span>
</div>


<?php
		$output .= ob_get_clean();
		$output .= $after_widget."\n";
		echo $output;
	}
	
/*  更新小工具
/* ------------------------------------ */
	public function update($new,$old) {
		$instance                     = $old;
		$instance['title']            = strip_tags($new['title']);
		$instance['word_text']      = strip_tags($new['word_text']);
		$instance['word_author']      = strip_tags($new['word_author']);
		return $instance;
	}

/*  小工具表单
/* ------------------------------------ */
	public function form($instance) {
		// 默认设置
		$defaults = array(
			'title'          => __('每日一句','salong'),
			'word_text'    => __('萨龙网络始建于2012年8月，是一家新生的优秀互联网综合服务提供商！','salong'),
			'word_author'    => __('萨龙龙','salong'),
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
?>

	<style>
	.widget .widget-inside .postform { width: 100%; }
	</style>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('标题：','salong'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('word_text'); ?>"><?php _e('内容：','salong'); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('word_text'); ?>" name="<?php echo $this->get_field_name('word_text'); ?>"><?php echo $instance["word_text"]; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('word_author'); ?>"><?php _e('作者：','salong'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('word_author'); ?>" name="<?php echo $this->get_field_name('word_author'); ?>" type="text" value="<?php echo esc_attr($instance["word_author"]); ?>" />
		</p>
<?php

}

}

/*  注册小工具
/* ------------------------------------ */
if ( ! function_exists( 'salong_register_widget_Word' ) ) {

	function salong_register_widget_Word() { 
		register_widget( 'SlongWord' );
	}
	
}
add_action( 'widgets_init', 'salong_register_widget_Word' );
