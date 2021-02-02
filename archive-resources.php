<?php get_header(); ?> 
<div class="Breadcrumbs">
  <a class="Breadcrumbs__anchor" href="https://www.google.com/"
    ><p class="Breadcrumbs__text"><?php ct2021_get_breadcrumbs(); ?></p></a
  >
</div>
<div class="Archive__home">
    <div class="Archive__posts-list">
        <?php 
            if( have_posts() ) {
                while( have_posts() ) {
                    the_post();?>
                    <div class="Archive__post">
                        <h3>
                            <a class="Archive__post-title" href="<?php the_permalink();?>"><?php the_title();?></a>
                        </h3>
                        <p class="Archive__author-date"><?php echo get_the_date(); ?></p>
                        <p class="Archive__meta">Categories: <?php  echo get_the_term_list($resource_id, 'resources-categories');?></p>
                        <p class="Archive__meta">Tags: <?php echo get_the_term_list($resource_id, 'resources-tags', '', ' ');?>
                        <div class="Archive__meta"><?php the_excerpt(); ?></div>
                        <hr>
                    </div>
                    <?php
                }
            }
        ?>
        <?php the_posts_pagination( array(
            'mid_size'  => 2,
            'prev_text' => __( 'NEWER POSTS', 'textdomain' ),
            'next_text' => __( 'OLDER POSTS', 'textdomain' ),
        ) ); ?>
    </div>
</div>
<?php get_footer(); ?>
