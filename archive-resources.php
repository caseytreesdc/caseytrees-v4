<?php get_header(); ?> 
<?php the_posts_pagination(); ?>
<?php 
    if( have_posts() ) {
        while( have_posts() ) {
            the_post();
            ?><h2>
                <a href="<?php the_permalink();?>"><?php the_title();?></a>
            </h2>
            <p><?php echo get_the_date(); ?> by <?php echo get_field('author');?> </p>       
            <?php 
                $image = get_field('image');
                $size = 'medium'; // (thumbnail, medium, large, full or custom size)
                if( $image ) {
                    echo wp_get_attachment_image( $image, $size );
                }
            ?>
            <?php echo get_the_category_list(); ?>
            <?php
        }
    }
?>
<?php the_posts_pagination(); ?>
