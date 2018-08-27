<?php

define('custom_WORDPRESS_FOLDER',$_SERVER['DOCUMENT_ROOT']);
define('custom_THEME_FOLDER',str_replace("\\",'/',dirname(__FILE__)));
define('custom_THEME_PATH','/' . substr(custom_THEME_FOLDER,stripos(custom_THEME_FOLDER,'wp-content')));

add_action('admin_init','custom_meta_init');

function custom_meta_init()
{
    // review the function reference for parameter details
    // http://codex.wordpress.org/Function_Reference/wp_enqueue_script
    // http://codex.wordpress.org/Function_Reference/wp_enqueue_style

    //wp_enqueue_script('custom_meta_js', custom_THEME_PATH . '/custom/meta.js', array('jquery'));
    wp_enqueue_style('custom_meta_css', custom_THEME_PATH . '/shortcodes.css');

    // review the function reference for parameter details
    // http://codex.wordpress.org/Function_Reference/add_meta_box

    // add a meta box for each of the wordpress page types: posts and pages
    foreach (array('post','page','download','topic','video','product') as $type)
    {
        add_meta_box('custom_all_meta', __('自定义简码','salong'), 'custom_meta_setup', $type, 'side', 'high');
    }

    // add a callback function to save any data a user enters in
    add_action('save_post','custom_meta_save');
}

function custom_meta_setup()
{
    global $post;

    // using an underscore, prevents the meta variable
    // from showing up in the custom fields section
    $meta = get_post_meta($post->ID,'_custom_meta',TRUE);

    // instead of writing HTML here, lets do an include
    include(custom_THEME_FOLDER . '/meta.php');

    // create a custom nonce for submit verification later
    echo '<input type="hidden" name="custom_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

function custom_meta_save($post_id)
{
    // authentication checks

    // make sure data came from our meta box
    if (!wp_verify_nonce($_POST['custom_meta_noncename'],__FILE__)) return $post_id;

    // check user permissions
    if ($_POST['post_type'] == 'page')
    {
        if (!current_user_can('edit_page', $post_id)) return $post_id;
    }
    else
    {
        if (!current_user_can('edit_post', $post_id)) return $post_id;
    }

    // authentication passed, save data

    // var types
    // single: _custom_meta[var]
    // array: _custom_meta[var][]
    // grouped array: _custom_meta[var_group][0][var_1], _custom_meta[var_group][0][var_2]

    $current_data = get_post_meta($post_id, '_custom_meta', TRUE);

    $new_data = $_POST['_custom_meta'];

    custom_meta_clean($new_data);

    if ($current_data)
    {
        if (is_null($new_data)) delete_post_meta($post_id,'_custom_meta');
        else update_post_meta($post_id,'_custom_meta',$new_data);
    }
    elseif (!is_null($new_data))
    {
        add_post_meta($post_id,'_custom_meta',$new_data,TRUE);
    }

    return $post_id;
}

function custom_meta_clean(&$arr)
{
    if (is_array($arr))
    {
        foreach ($arr as $i => $v)
        {
            if (is_array($arr[$i]))
            {
                custom_meta_clean($arr[$i]);

                if (!count($arr[$i]))
                {
                    unset($arr[$i]);
                }
            }
            else
            {
                if (trim($arr[$i]) == '')
                {
                    unset($arr[$i]);
                }
            }
        }

        if (!count($arr))
        {
            $arr = NULL;
        }
    }
}

?>