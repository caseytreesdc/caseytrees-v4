<?php 
    if ( function_exists( 'the_custom_logo' ) ) {
        the_custom_logo();
   }
?>
<?php 
     wp_nav_menu(
        array(
            'theme_location' => 'topOfAllPagesMenu',
        )
    )
?>