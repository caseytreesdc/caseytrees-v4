<?php get_header(); ?> 
<div class="Breadcrumbs">
  <a class="Breadcrumbs__anchor" href="https://www.google.com/"
    ><p class="Breadcrumbs__text"><?php ct2021_get_breadcrumbs(); ?></p></a
  >
</div>
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
<?php get_footer(); ?>
