<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
  </head>
  <body>
    <!-- begin header MODULE -->
    <header class="header__container">
    <?php echo get_custom_logo(); ?>
    <?php 
        wp_nav_menu(
            array(
                'theme_location' => 'topOfAllPagesMenu',
                'items_wrap' => '<ul class="Nav__ul">%3$s</ul>', //what is %3$s
                'before' => '<div class="Nav__anchor-p-container"><p>',
                'after' => '</p></div><div class="Nav__slash"></div>',
            )
        )
    ?>
    </header>
    <!-- end header MODULE -->