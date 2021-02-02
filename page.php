<?php get_header(); ?>
<div class="Breadcrumbs">
  <a class="Breadcrumbs__anchor" href="https://www.google.com/"
    ><p class="Breadcrumbs__text"><?php ct2021_get_breadcrumbs(); ?></p></a
  >
</div>
<?php 
    if( have_posts() ) {
        while( have_posts() ) {
            the_post();
            printf( __( 'The post type is: %s', 'textdomain' ), get_post_type( get_the_ID() ) );
            ?><h2>
                <a href="<?php the_permalink();?>"><?php the_title();?></a>
            </h2>
            <p><?php echo get_the_date(); ?> by <?php echo get_field('author');?> </p>       
            <?php 
                $image = get_field('image');
                $size = 'full'; // (thumbnail, medium, large, full or custom size)
                if( $image ) {
                    echo wp_get_attachment_image( $image, $size );
                }
            ?>
            <?php echo get_the_category_list(); ?>
            <?php the_content();?>
            <?php 

            $fields = get_fields();
            
            if( $fields ): ?>
                <ul>
                    <?php foreach( $fields as $name => $value ): ?>
                        <li><b><?php echo $name; ?></b> <?php echo $value; ?></li>
                    <?php endforeach; ?>
                </ul>

            <?php endif; ?>
            <?php
        }
    }
?>
<?php get_footer(); ?>