<?php global $salong;
if(get_option('default_comments_page') == 'oldest'){
    $next = '#comments a.next';
}else{
    $next = '#comments a.prev';
}
?>
<script type="text/javascript">var ias=$.ias({container:".ajaxposts,ol.commentlist",item:".ajaxpost,li.depth-1",pagination:".navigation",next:".navigation a.next,<?php echo $next; ?>"});ias.extension(new IASSpinnerExtension());ias.extension(new IASTriggerExtension({text:'<?php echo $salong['loadmore_text']; ?>',offset:<?php echo $salong['loadmore_count'];?>,}));ias.extension(new IASNoneLeftExtension({text:'<?php echo $salong['loadmore_end']; ?>',}));<?php if ($salong['switch_lazyload']) { ?>ias.on('rendered',function(items){$(".container img,.content img,#content img").lazyload({effect:"fadeIn",failure_limit:10});})<?php } ?></script>