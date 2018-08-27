<?php
global $salong;
//筛选////////////////////////////////////////////////////

//准备一个筛选数据的数组。
//需要多次用到，用一个函数来获取。
function salong_get_sift_array(){
    global $salong;
    //价格
    $sift_price = explode(PHP_EOL,$salong['sift_price']);
    foreach( $sift_price as $prices ) {
      $price = explode('=', $prices );
      $price_arr[ $price[0] ] = $price[1]; 
    }
    
    //排序
    $sift_paixu = explode(PHP_EOL,$salong['sift_paixu']);
    foreach( $sift_paixu as $paixus ) {
      $paixu = explode('=', $paixus );
      $paixu_arr[ $paixu[0] ] = $paixu[1]; 
    }
    
    $sift_array = array(
        //价格
        'price'=> $price_arr,
        //排序
        'paixu'=> $paixu_arr,
    );
    return $sift_array;
}

//筛选参数
//筛选页面的url类似为 http://www.salong.com/xxx?stage=love&year=2016&price=china
function salong_add_query_vars($public_query_vars) {
  $public_query_vars[] = 'price';
  $public_query_vars[] = 'paixu';
  return $public_query_vars;
}
add_action('query_vars', 'salong_add_query_vars');


//文章筛选代码
//通过pre_get_posts钩子筛选
add_action('pre_get_posts','salong_sift_posts_per_page');
function salong_sift_posts_per_page($query){
  //is_category()即为分类页面有效，自行更换。
  //$query->is_main_query()使得仅对默认的页面主查询有效
  //!is_admin()避免影响后台文章列表
  if(is_category() && $query->is_main_query() && !is_admin()){
    $sift_array = salong_get_sift_array(); //获取筛选数组
    //从筛选数组中获取筛选的有效值值
    /*例如类型的值
    * $stage_keys = array( 'love', 'literary', 'action', 'war', 'other');
    */
    $price_keys = array_keys( $sift_array['price'] ); //价格
    $paixu_keys = array_keys( $sift_array['paixu'] ); //排序
    $relation = 0; //用于计数筛选项目数
    //从url中获取要筛选的参数,放入数组中
    $sift_vars = array();
    $sift_vars['price'] = get_query_var('price');
    $sift_vars['paixu'] = get_query_var('paixu');
    $meta_query = array(
      'relation' => 'OR',
    );
    //判断类型是否合法,即是否存在于我们的配置数组中
    //判断价格是否合法
    if( in_array( $sift_vars['price'], $price_keys ) ){
      $meta_query[] = array(
        'key'     =>'price',
        'value'   => $sift_vars['price'],
        'compare' =>'LIKE',
      );
      $relation++;
    }
    //判断排序是否合法
    if( in_array( $sift_vars['paixu'], $paixu_keys ) ){
      $meta_query[] = array(
        'key'     =>'paixu',
        'value'   => $sift_vars['paixu'],
        'compare' =>'LIKE',
      );
      $relation++;
    }
    if($relation){
      //若大于两个筛选
      if($relation==2){
        $meta_query['relation'] = 'AND'; //多项筛选同时满足
      }
      $query->set('meta_query',$meta_query);
    }
  }
}


//筛选////////////////////////////////////////////////////end
