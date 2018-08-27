<?php get_header(); ?>
<?php $post_type=$_GET['post_type'];
$located=get_template_part( 'content/search', $post_type ); 
if ( isset( $post_type ) && locate_template($located, $require_once) ) {
    get_template_part( 'content/search', $post_type );
    exit;
}
?>
<?php get_footer(); ?>