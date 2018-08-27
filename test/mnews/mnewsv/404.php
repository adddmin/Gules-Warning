<?php get_header();
global $salong;
$img = $salong['404_img']['url'];
?>
<main class="container">
    <div class="wrapper">
        <article class="page404">
            <img src="<?php echo $img; ?>" alt="404">
            <div class="con">
                <h2>
                    <?php echo $salong['404_title']; ?>
                </h2>
                <p>
                    <?php echo $salong['404_desc']; ?>
                </p>
                <a href="<?php echo home_url(); ?>" class="button">
                    <?php _e('返回首页','salong'); ?>
                </a>
            </div>
        </article>
    </div>
</main>
<?php get_footer(); ?>
