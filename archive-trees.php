<?php get_header(); ?> 
<?php the_posts_pagination(); ?>
<?php 
    if( have_posts() ) {
        while( have_posts() ) {
            the_post();
            ?><h2>
                <a href="<?php the_permalink();?>"><?php the_title();?></a>
            </h2>
            <?php the_excerpt(); ?>
            <?php 

            $fields = get_fields();
            
            if( $fields ): ?>
                <ul>
                    <?php foreach( $fields as $name => $value ): ?>
                        <li style="display: inline-block;"><b><?php echo $name; ?></b> = <?php echo $value; ?>|</li>
                    <?php endforeach; ?>
                </ul>

            <?php endif; ?>
            <?php
        }
    }
?>
<?php the_posts_pagination(); ?>
