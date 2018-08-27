<?php
function truethemes_widgets_init() {
    register_sidebar( );
    add_action( 'widgets_init', 'truethemes_widgets_init' );
}
global $salong;
if(isset($salong['sidebars'])){
    $dynamic_sidebar = $salong['sidebars'];
    if(!empty($dynamic_sidebar))
    {
        foreach($dynamic_sidebar as $key=>$sidebar)
        {
            if ( function_exists('register_sidebar') && ($sidebar <> ''))
            register_sidebar(
                array(
                    'name'          => str_replace("_"," ",$sidebar),
                    'id'            =>'m-'.($key+1),
                    'description'   => sprintf(__('这个边栏显示在%s边栏','salong'),$sidebar),
                    'before_title'  =>'<div class="sidebar_title"><h3>',
                    'after_title'   =>'</h3></div>',
                    'before_widget' => '<section   id="%1$s" class="sidebar_widget %2$s">',
                    'after_widget'  => '</section>',
                )
            );
        }
    }
}
