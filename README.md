# Local Development for caseytrees.org

## General Setup Mac for now
1. Install [Local](https://localwp.com/pro/)
2. Get the backup zip file of the site including the sql from [wpengine](https://my.wpengine.com/)
   1. why is my backup not updated?
3. Name/Rename the file to something friendly like 'caseytrees-v4', it will be the url and the site title in Local
   1. Drop that zip file onto the Local application
4. Click on the site title to open it in the browser
5. The repo is in /Users/username/Local Sites
6. Login in with your Casey Trees CMS Password

## A New Theme

### Navigate to [`Appearance > Themes`](https://caseytrees-v4.local/wp-admin/themes.php) in the CMS
1. Currently the theme is called [Casey Trees, by NMC](https://caseytrees-v4.local/wp-admin/themes.php?theme=nmc_caseytrees)
   1. The home page is rendered (kinda) through `wp-content/themes/nmc_caseytrees/templates/_layouts/base.twig`... READ MORE ... 
1. create a folder called something like 'ct_theme21' in `/Local Sites/caseytrees-v4/app/public/wp-content/themes`
1. create 'style.css' in `/Local Sites/caseytrees-v4/app/public/wp-content/themes/ct_theme21`
1. Setting up the <b>Template Header</b>
   1. style.css requires a header, like: (there are more.less options of what can go in there.)
```
/*
Theme Name: Casey Trees 2021
Author: Casey Trees Digital Developer
Author URI: https://caseytrees.org
Description: Casey Trees 2021 theme
Requires at least: 5.3
Tested up to: 5.6
Requires PHP: 5.6
Version: 1.1
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: Casey Trees 2021
Tags: featured-images twig timber accessible classic-editor advanced-custom-fields

(C) 2021 Casey Trees

*/
``` 

### Open [Visualize the Wordpress Template Hierarchy](https://wphierarchy.com/)
1. Create <b>`index.php`</b> in `wp-content/themes/ct_theme21`
   1. This will load if no other php file is available.
   1. Nothing else is need on this file for now.
   1. Click Activate on our new theme in the [CMS Tab](https://caseytrees-v4.local/wp-admin/themes.php).
   1. Currently, `Pages > Home` is set to the front page. 
   2. Create a new page "Front Page"
   3. Notice that in the CMS Sidebar, we have Pages, Posts, Media, but no Resources or Trees. These are [Custom Post Types](https://wordpress.org/support/article/post-types/#custom-post-types) ... READ MORE ...

### Create `front-page.php` in the theme directory (`/themes/ct_theme21`)
1. At `Settings > Reading` set `Your homepage displays` to the radio button for Static Page, and select the new page we created "Front Page" and Save Changes
2. The home page is now rendered by `front-page.php`.

### The Header
1. In `front-page.php` put `<?php get_header(); ?>` to call on `header.php`. 
2. At [`Appearance > Themes`](https://caseytrees-v4.local/wp-admin/themes.php), select Customize and upload a new favicon if desired. 
   1. There is no option to upload a logo for the site, but we will add it now. 
3. Create `functions.php` - add
```
/*
<?php
===================================
Theme Supports
===================================
*/
function ct2021_theme_setup() {

    add_theme_support('custom-logo');

}

add_action('after_setup_theme', 'ct2021_theme_setup');

```

4. Create <b>`header.php`</b> with
```
<?php 
    if ( function_exists( 'the_custom_logo' ) ) {
        the_custom_logo();
   }
?>
<?php 
wp_nav_menu(
    array(
        'theme_location' => 'topOfAllPagesMenu'
    )
); 
?>
```
*** in the above code, the string given to 'theme_location without registration in functions.php seems to give us a menu of all our current pages and parent child relationships? 
'theme_location` is the only required argument needed see [wp_nav_menu() Function](https://developer.wordpress.org/reference/functions/wp_nav_menu/)

### Blog Posts Archive Page 
1. Create a new Page in the CMS called 'Blog Posts' or 'The Leaflet'
2. Under `Settings > Reading` set `Posts Page:` to the Page we created.
3. Create <b>`home.php`</b> in the template directory to render the the Blog Posts Index page with the following:
```
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

```
### Menu
1. Navigate to `Appearance > Menus`, create a new menu, and to it add The Front Page and the Posts Page. Save Menu. 
1. Add the following to `functions.php` to register a menu to a location. We will add more to `$locations` eventually. 
```
/*
    ===================================
    Menu
    ===================================
*/
function ct2021_menus() {
    $locations = array(
        'topOfAllPagesMenu' => 'top of all pages',
    );

    register_nav_menus($locations);
}

add_action('init', 'ct2021_menus');
```
#### Check out the browser and what it renders on [Front-page.php](http://caseytrees-v4.local/).

### Single Blog Post Page
1. Create <b>`single.php`</b> with 
```
<?php get_header(); ?>

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
```
### Adding the "Resources" Custom Post Type
1. As of now, trees and resources are not visible in the CMS, although they are in our database. 
2. Add this to `functions.php`. 
```
// /*
//     ===================================
//     Register Resources Custom Post Type
//     ===================================
// */

function ct2021_resources_init() {
	$labels = array(
		'name'               => _x( 'Resources', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Resource', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Resources', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Resource', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => __( 'Add New', 'resource', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Resource', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Resource', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Resource', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Resource', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Resources', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Resources', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Resources:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No resources found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No resources found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => 'resources-list', 'with_front' => false),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-media-document',
		'supports'           => array('title', 'editor')
	);

	/******** Icon reference for $args['menu_position'] --> https://developer.wordpress.org/resource/dashicons/ *********/

	register_post_type( 'resources', $args );
}
add_action( 'init', 'ct2021_resources_init' );

register_taxonomy(
	'resources-categories',
	'resources',
	array(
		'label' => __( 'Resources Categories' ),
		'rewrite' => array( 'slug' => 'resources-categories' ),
		'hierarchical' => true,
		'show_in_nav_menus'  => false,
	)
);

register_taxonomy(
	'resources-tags',
	array('resources', 'page', 'post', 'trees'),
	array(
		'label' => __( 'Resources Tags' ),
		'rewrite' => array( 'slug' => 'resources-tags' ),
		'hierarchical' => true,
		'show_in_nav_menus'  => false,
	)
);
```
3. The resources are now visible in the CMS on the left. 
4. Navigate to `Appearance > Menus`
5. On the left here under Add Menu items, in the Resources dropdown, select view all and add All Resources to the Menu. 
6. Save Menu
7. Refresh the page to see the new link. If we click on it, there is nothing to open it. 
8. Create <b>`archive-resources.php`</b> with 
```
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
            <?php $resource_id = get_the_ID();?>
            <p>Categories: <?php  echo get_the_term_list($resource_id, 'resources-categories');?></p>
            <p>Tags: <?php echo get_the_term_list($resource_id, 'resources-tags', '', ' ');?>
            <?php the_excerpt(); ?>

            <?php
        }
    }
?>
<?php the_posts_pagination(); ?>

```
9.  Click on the link, if there is a 404, go to `Settings > Permalinks`, change the Common Settings to `Plain`. 
10. Save Changes
11. Change it back to whatever we want, as of now its `Month and Name` or `http://caseytreesv4.local/2020/12/sample-post/`.
12. Save Changes
13. Refresh the 404 page or visit the All Resources Archive from the home page. 

### Adding the "Trees" Custom Post Type
1. in the CMS sidebar, there are no trees. 
1. Add the following to `functions.php`
```
// /*
//     ===================================
//     Register Trees Custom Post Type - NMC Code verbatim
//      except we changed 'has_archive' to true to create a trees archive page. 
//     ===================================
// */

add_action( 'init', 'trees_init' );
function trees_init() {
	$labels = array(
		'name'               => _x( 'Trees', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Tree', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Trees', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Tree', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'tree', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Tree', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Tree', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Tree', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Tree', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Trees', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Trees', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Trees:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No trees found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No trees found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => 'trees-list', 'with_front' => true),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-palmtree',
		'supports'           => array('title', 'editor')
	);

	/******** Icon reference for $args['menu_position'] --> https://developer.wordpress.org/resource/dashicons/ *********/

	register_post_type( 'trees', $args );
}
```
3. Try to View one of the individual trees from the CMS. If a 404, either switch to an old theme and back or try the earlier solution from `Settings > Permalinks`
4. Navigate to `Appearance > Menus`
5. On the left here under Add Menu items, in the Trees dropdown, select view all and add All Trees to the Menu. 
6. Save Menu
7. Refresh the page to see the new link. If we click on it, there is nothing to open it. 
8. Create <b>`archive-tree.php`</b> with 
```
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

```
### But what about the other Pages?!
Make `front-page.php` look like this: 

```
<?php get_header(); ?>
<?php wp_nav_menu(); ?>
<?php wp_page_menu(); ?>
```
Now most if not all content in the Casey Trees SQL Database associate with caseytrees.org CMS is displaying through the theme. 
