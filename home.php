<?php get_header(); ?> 
<?php 
    if( have_posts() ) {
        while( have_posts() ) {
            the_post();
            ?><h2>
                <a href="<?php the_permalink();?>"><?php the_title();?></a>
            </h2>
            
            <?php 
                $image = get_field('image');
                $size = 'medium'; // (thumbnail, medium, large, full or custom size)
                if( $image ) {
                    echo wp_get_attachment_image( $image, $size );
                }
            ?>
            <p>
                <?php $post_id = get_the_ID(); ?>
                <?php echo get_post_type($post_id); ?>
                <?php the_date(); ?>
            </p>
            
            <?php
        }
    }
?>
<?php the_posts_pagination(); ?>