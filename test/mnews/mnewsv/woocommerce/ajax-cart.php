<?php global $salong; ?>
<aside class="ajax_cart">
    <a class="cart_btn" rel="nofollow">
        <?php echo svg_cart().svg_close(); ?><span class="cart-contents">0</span>
    </a>
    <h4><?php _e('我的购物车','salong'); ?></h4>
    <div class="widget_shopping_cart_content">
        <?php wc_get_template( 'cart/mini-cart.php' ); ?>
    </div>
</aside>
