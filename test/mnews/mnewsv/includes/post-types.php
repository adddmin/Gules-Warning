<?php
global $salong;

if ($salong[ 'switch_topic_type']) {
    /******************************************************************
    自定义专题文章类型
    ******************************************************************/
    add_action( 'init', 'create_redvine_topic' );
    function create_redvine_topic() {
        $labels = array( 
            'name'                => __( '专题', 'salong' ),
            'singular_name'       => __( '专题', 'salong' ),
            'add_new'             => __( '添加专题', 'salong' ),
            'add_new_item'        => __( '添加专题', 'salong' ),
            'edit_item'           => __( '编辑专题', 'salong' ),
            'new_item'            => __( '新的专题', 'salong' ),
            'view_item'           => __( '查看专题', 'salong' ),
            'search_items'        => __( '搜索专题', 'salong' ),
            'not_found'           => __( '没有找到专题', 'salong' ),
            'not_found_in_trash'  => __( '在回收站没有找到专题', 'salong' ),
            'parent_item_colon'   => __( '父级专题', 'salong' ),
            'menu_name'           => __( '专题', 'salong' ),
        );

        $args = array( 
            'labels'              => $labels,
            'hierarchical'        => false,
            'supports'            => array('title','editor','author','thumbnail','custom-fields'),
            'taxonomies'          => array( 'tcat','ttag'),
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 4,
            'menu_icon'           => 'dashicons-flag',
            'show_in_nav_menus'   => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => false,
            'has_archive'         => true,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => true,
            'capability_type'     => 'post'
        );

        register_post_type( 'topic', $args );
        register_post_status(
            'topic_status',
            array(
                'label'                     => __('专题状态', 'salong'),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => ''
            )
        );
    }

    //创建分类法

    add_action( 'init', 'create_redvine_topic_taxonomy', 0 );
    function create_redvine_topic_taxonomy() {
        //添加分类分类法
        $labels = array(
            'name'              => __( '专题分类', 'salong' ),
            'singular_name'     => __( '专题分类', 'salong' ),
            'search_items'      => __( '搜索专题分类','salong' ),
            'all_items'         => __( '所有专题分类','salong' ),
            'parent_item'       => __( '父级专题分类','salong' ),
            'parent_item_colon' => __( '父级专题分类：','salong' ),
            'edit_item'         => __( '编辑专题分类','salong' ), 
            'update_item'       => __( '更新专题分类','salong' ),
            'add_new_item'      => __( '添加专题分类','salong' ),
            'new_item_name'     => __( '新的专题分类','salong' ),
            'menu_name'         => __( '专题分类','salong' ),
        );     

        //注册分类法
        register_taxonomy('tcat',array('topic'), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'tcat' )
        ));

        //添加标签分类法
        $labels = array(
            'name'              => __( '专题标签', 'salong' ),
            'singular_name'     => __( '专题标签', 'salong' ),
            'search_items'      => __( '搜索专题标签','salong' ),
            'all_items'         => __( '所有专题标签','salong' ),
            'parent_item'       => __( '父有专题标签','salong' ),
            'parent_item_colon' => __( '父级专题标签：','salong' ),
            'edit_item'         => __( '编辑专题标签','salong' ), 
            'update_item'       => __( '更新专题标签','salong' ),
            'add_new_item'      => __( '添加专题标签','salong' ),
            'new_item_name'     => __( '新的专题标签','salong' ),
            'menu_name'         => __( '专题标签','salong' ),
        );
        register_taxonomy('ttag',array('topic'), array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'ttag' ),
        ));
    }
}

if ($salong[ 'switch_download_type']) {
    /******************************************************************
    自定义下载文章类型
    ******************************************************************/
    add_action( 'init', 'create_redvine_download' );
    function create_redvine_download() {
        $labels = array( 
            'name'                => __( '下载', 'salong' ),
            'singular_name'       => __( '下载', 'salong' ),
            'add_new'             => __( '添加下载', 'salong' ),
            'add_new_item'        => __( '添加下载', 'salong' ),
            'edit_item'           => __( '编辑下载', 'salong' ),
            'new_item'            => __( '新的下载', 'salong' ),
            'view_item'           => __( '查看下载', 'salong' ),
            'search_items'        => __( '搜索下载', 'salong' ),
            'not_found'           => __( '没有找到下载', 'salong' ),
            'not_found_in_trash'  => __( '在回收站没有找到下载', 'salong' ),
            'parent_item_colon'   => __( '父级下载', 'salong' ),
            'menu_name'           => __( '下载', 'salong' ),
        );

        $args = array( 
            'labels'              => $labels,
            'hierarchical'        => false,
            'supports'            => array('title','editor','author','thumbnail','custom-fields','comments'),
            'taxonomies'          => array( 'dcat','dtag'),
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 4,
            'menu_icon'           => 'dashicons-download',
            'show_in_nav_menus'   => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => false,
            'has_archive'         => true,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => true,
            'capability_type'     => 'post'
        );

        register_post_type( 'download', $args );
        register_post_status(
            'download_status',
            array(
                'label'                     => __('下载状态', 'salong'),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => ''
            )
        );
    }


    //创建分类法

    add_action( 'init', 'create_redvine_download_taxonomy', 0 );
    function create_redvine_download_taxonomy() {
        //添加分类分类法
        $labels = array(
            'name'              => __( '下载分类', 'salong' ),
            'singular_name'     => __( '下载分类', 'salong' ),
            'search_items'      => __( '搜索下载分类','salong' ),
            'all_items'         => __( '所有下载分类','salong' ),
            'parent_item'       => __( '父级下载分类','salong' ),
            'parent_item_colon' => __( '父级下载分类：','salong' ),
            'edit_item'         => __( '编辑下载分类','salong' ), 
            'update_item'       => __( '更新下载分类','salong' ),
            'add_new_item'      => __( '添加下载分类','salong' ),
            'new_item_name'     => __( '新的下载分类','salong' ),
            'menu_name'         => __( '下载分类','salong' ),
        );     

        //注册分类法
        register_taxonomy('dcat',array('download'), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'dcat' )
        ));

        //添加标签分类法
        $labels = array(
            'name'              => __( '下载标签', 'salong' ),
            'singular_name'     => __( '下载标签', 'salong' ),
            'search_items'      => __( '搜索下载标签','salong' ),
            'all_items'         => __( '所有下载标签','salong' ),
            'parent_item'       => __( '父有下载标签','salong' ),
            'parent_item_colon' => __( '父级下载标签：','salong' ),
            'edit_item'         => __( '编辑下载标签','salong' ), 
            'update_item'       => __( '更新下载标签','salong' ),
            'add_new_item'      => __( '添加下载标签','salong' ),
            'new_item_name'     => __( '新的下载标签','salong' ),
            'menu_name'         => __( '下载标签','salong' ),
        );
        register_taxonomy('dtag',array('download'), array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'dtag' ),
        ));
    }
}

if ($salong[ 'switch_video_type']) {
    /******************************************************************
    自定义视频文章类型
    ******************************************************************/
    add_action( 'init', 'create_redvine_video' );
    function create_redvine_video() {
        $labels = array( 
            'name'                => __( '视频', 'salong' ),
            'singular_name'       => __( '视频', 'salong' ),
            'add_new'             => __( '添加视频', 'salong' ),
            'add_new_item'        => __( '添加视频', 'salong' ),
            'edit_item'           => __( '编辑视频', 'salong' ),
            'new_item'            => __( '新的视频', 'salong' ),
            'view_item'           => __( '查看视频', 'salong' ),
            'search_items'        => __( '搜索视频', 'salong' ),
            'not_found'           => __( '没有找到视频', 'salong' ),
            'not_found_in_trash'  => __( '在回收站没有找到视频', 'salong' ),
            'parent_item_colon'   => __( '父级视频', 'salong' ),
            'menu_name'           => __( '视频', 'salong' ),
        );

        $args = array( 
            'labels'              => $labels,
            'hierarchical'        => false,
            'supports'            => array('title','editor','author','thumbnail','custom-fields','comments'),
            'taxonomies'          => array( 'vcat','vtag'),
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 4,
            'menu_icon'           => 'dashicons-video-alt2',
            'show_in_nav_menus'   => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => false,
            'has_archive'         => true,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => true,
            'capability_type'     => 'post'
        );

        register_post_type( 'video', $args );
        register_post_status(
            'video_status',
            array(
                'label'                     => __('视频状态', 'salong'),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => ''
            )
        );
    }


    //创建分类法

    add_action( 'init', 'create_redvine_video_taxonomy', 0 );
    function create_redvine_video_taxonomy() {
        //添加分类分类法
        $labels = array(
            'name'              => __( '视频分类', 'salong' ),
            'singular_name'     => __( '视频分类', 'salong' ),
            'search_items'      => __( '搜索视频分类','salong' ),
            'all_items'         => __( '所有视频分类','salong' ),
            'parent_item'       => __( '父级视频分类','salong' ),
            'parent_item_colon' => __( '父级视频分类：','salong' ),
            'edit_item'         => __( '编辑视频分类','salong' ), 
            'update_item'       => __( '更新视频分类','salong' ),
            'add_new_item'      => __( '添加视频分类','salong' ),
            'new_item_name'     => __( '新的视频分类','salong' ),
            'menu_name'         => __( '视频分类','salong' ),
        );     

        //注册分类法
        register_taxonomy('vcat',array('video'), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'vcat' )
        ));

        //添加标签分类法
        $labels = array(
            'name'              => __( '视频标签', 'salong' ),
            'singular_name'     => __( '视频标签', 'salong' ),
            'search_items'      => __( '搜索视频标签','salong' ),
            'all_items'         => __( '所有视频标签','salong' ),
            'parent_item'       => __( '父有视频标签','salong' ),
            'parent_item_colon' => __( '父级视频标签：','salong' ),
            'edit_item'         => __( '编辑视频标签','salong' ), 
            'update_item'       => __( '更新视频标签','salong' ),
            'add_new_item'      => __( '添加视频标签','salong' ),
            'new_item_name'     => __( '新的视频标签','salong' ),
            'menu_name'         => __( '视频标签','salong' ),
        );
        register_taxonomy('vtag',array('video'), array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'vtag' ),
        ));
    }
}