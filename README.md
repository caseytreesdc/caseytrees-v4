# Local Development for [caseytrees.org](https://caseytrees.org/)

## 1. General Setup Mac for now
1. Download the ZIP Archive file from the link and don't unzip it!
1. Install [Local](https://localwp.com/pro/)
2. Install [VSCode](https://code.visualstudio.com/) or your favorite text editor. 
   1. [Sublime Text](https://www.sublimetext.com/)
   2. [Atom](https://atom.io/)
3. Get the backup zip file of the site including the sql from 
   1. TK for now, OR
   2. [wpengine](https://my.wpengine.com/)
4. Drop that zip file onto the Local application
5. Name the site to something friendly like 'caseytrees-v4', it will be the url and the site title in Local
6. Click on the site title to open it in the browser
7. Login in with your Casey Trees CMS Password
8. The repository AKA repo AKA where all the files are is in 
   1. Mac: `/Users/yourusername/Local Sites`
   2. Windows: `C:/Users/yourusername/Local Sites`

## 2. A New Theme

### 2.1 Navigate to [`Appearance > Themes`](https://caseytrees-v4.local/wp-admin/themes.php) in the CMS
1. Currently the theme is called [Casey Trees, by NMC](https://caseytrees-v4.local/wp-admin/themes.php?theme=nmc_caseytrees)
   1. The home page is rendered (kinda) through `wp-content/themes/nmc_caseytrees/templates/_layouts/base.twig`... READ MORE ... 
1. create a folder called something like 'ct_theme21' in `/Local Sites/caseytrees-v4/app/public/wp-content/themes`
1. create 'style.css' in `/Local Sites/caseytrees-v4/app/public/wp-content/themes/ct_theme21`. This the template directory. 
1. Setting up the <b>Template Header</b>
   1. style.css requires a header, like: (there are more or less options of what can go in there.)
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

### 2.2 Open [Visualize the Wordpress Template Hierarchy](https://wphierarchy.com/)
1. Create <b>`index.php`</b> in `wp-content/themes/ct_theme21`
   1. This will load if no other php file is available.
   2. Nothing else is needed on this file for now.
   3. Click Activate on our new theme in the [CMS Tab](https://caseytrees-v4.local/wp-admin/themes.php).
   4. Currently, `Pages > Home` is set to the front page. 
   5. Create a new page "Front Page"
   6. Notice that in the CMS Sidebar, we have Pages, Posts, Media, but no Resources or Trees. These are [Custom Post Types](https://wordpress.org/support/article/post-types/#custom-post-types) ... READ MORE ...

### 2.3 Create `front-page.php` in the theme directory (`/themes/ct_theme21`)
1. At `Settings > Reading` set `Your homepage displays` to the radio button for Static Page, and select the new page we created "Front Page" and Save Changes
2. The home page is now rendered by `front-page.php`.

### 2.4 The Header
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
    echo get_custom_logo();
?>
<?php 
     wp_nav_menu(
        array(
            'theme_location' => 'topOfAllPagesMenu',
        )
    )
?>
```
*** in the above code, the string given to 'theme_location without registration in functions.php seems to give us a menu of all our current pages and parent child relationships? 
'theme_location` is the only required argument needed see [wp_nav_menu() Function](https://developer.wordpress.org/reference/functions/wp_nav_menu/)

### 2.5 Blog Posts Archive Page 
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
### 2.6 Menu
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

### 2.7 Single Blog Post Page
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
### 2.8 Adding the "Resources" Custom Post Type
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

### 2.9 Adding the "Trees" Custom Post Type
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
### 2.10 But what about the other Pages?!
1. Make `front-page.php` look like this: 

```
<?php get_header(); ?>
<?php wp_nav_menu(); ?>
<?php wp_page_menu(); ?>
```
2. Create <b>`page.php`</b> and copy and paste everything from `single.php` into it. 
3. Create <b>`taxonomy.php`</b> and <b>`category.php`</b> to display the links for those. 

Now most if not all content in the Casey Trees SQL Database of caseytrees.org is being rendered through the theme, into the browser. 

## 4. Building the Home Page - [reference](https://www.dropbox.com/sh/h4dn995tvbfdsdz/AAA4_nXgVlZBnOibmS5l5iyXa?dl=0)
### 4.1 HTML Boilerplate and Stylesheet Enqueue
1. First we will enqueue the stylesheet, although it won't initialize yet, until we add HTML Boilerplate, and the function `wp_head()`
2. In `functions.php` add -
```
// /*
//     ===================================
//     Register Styles
//     ===================================
// */
function ct2021_register_styles() {
    
    $styleVersion = wp_get_theme()->get( 'Version' );
    
    wp_enqueue_style('ct2021-style', get_template_directory_uri() . '/style.css', array(), $styleVersion, 'all');
}

add_action('wp_enqueue_scripts', 'ct2021_register_styles');
```
1. Make `header.php` - 
Here's the rest of the Boilerplate
```
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
        )
    ) 
    ?>
    </header>
    <!-- end header MODULE -->
```
4. Create <b>`footer.php`</b> with the closing tags of the above,
```
    </body>
</html>
```
5. Add `<?php get_footer(); ?>` to the bottom of 
   1. `archive-resources.php`
   2. `archive-trees.php`
   3. `category.php`
   4. `front-page.php`
   5. `home.php`
   6. `page.php`
   7. `single.php`
   8. `taxonomy.php`
6. Right click on a page, and select "View Page Source" to confirm things are being rendered in the browser. 
### 2.2 Styling the Header
#### 2.2.1 The Logo
1. Add the following to `:root` in `style.css`
```
/*
Theme Name: Casey Trees 2021 v4
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

:root {
    /* colors */
    --black: #000000;
    --coffee: #2c1010;
    --grey: #373737;
    --silver: #e5e6e5;
    --paper: #ffffff;
    --cobalt: #3768b2;
    --forest: #395940;
    --moss: #7b9e81;
    --khaki: #f2e397;
    --yellow: #f0d13f;
    --orange: #e18323;
    --paarl: #a4532e;

    /* fonts */
    --cronos: cronos-pro, lato, zeitung-mono, sans-serif;
    --lato: lato, cronos-pro, zeitung-mono, sans-serif;
    --zeitung: zeitung-mono, cronos-pro, lato, sans-serif;
}

.header__container {
    /* the 1% margin prevents the  header nav skew from exceeding the window width */
    margin: 1% 1%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--paper);
}

.header__logo-anchor {
    border-radius: 2px;
}

.header__logo-anchor:hover,
.header__logo-anchor:focus {
    background: var(--moss);
}

.header__logo {
    width: 300px;
    height: auto;
    padding: 10px;
}

@media (max-width: 576px) {
    .header__container {
        display: flex;
        flex-direction: column;
    }

    .header-logo {
        width: 340px;
        margin: 10px;
    }
}
```
2. The Logo will still be huge because we need to replace the Wordpress Classes with our own. Add the following to <b>`functions.php`</b>
```
// /*
//     ===================================
//     Class Manipulation Filters
//     ===================================
// */
add_filter('get_custom_logo', 'ct2021_replace_logo_classes');

function ct2021_replace_logo_classes( $html ) {
	$html = str_replace( 'custom-logo-link', 'header__logo-anchor', $html);
	$html = str_replace( 'custom-logo', 'header__logo', $html);
	return $html;
}
```
#### 2.2.2 The Nav Menu
1. Currently, in `header.php` the call to `wp_nav_menu()` around <u>line 15</u>, with its array argument returns:
```
      <div class="menu-main-navigation-container">
        <ul id="menu-main-navigation" class="menu">
          <li
            id="menu-item-20082"
            class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-20080 current_page_item menu-item-20082"
          >
            <a href="http://caseytreesv4.local/" aria-current="page"
              >Front Page</a
            >
          </li>
          <li
            id="menu-item-20083"
            class="menu-item menu-item-type-post_type menu-item-object-page menu-item-20083"
          >
            <a href="http://caseytreesv4.local/the-leaflet/">The Leaflet</a>
          </li>
          <li
            id="menu-item-20084"
            class="menu-item menu-item-type-post_type_archive menu-item-object-resources menu-item-20084"
          >
            <a href="http://caseytreesv4.local/resources-list/"
              >All Resources</a
            >
          </li>
          <li
            id="menu-item-20086"
            class="menu-item menu-item-type-post_type_archive menu-item-object-trees menu-item-20086"
          >
            <a href="http://caseytreesv4.local/trees-list/">All Trees</a>
          </li>
        </ul>
      </div>
```
2. Add the following to the Class Manipulation Filters in `functions.php`
```
add_filter('wp_nav_menu', 'ct2021_replace_header_menu_div_wrapper_class');

function ct2021_replace_header_menu_div_wrapper_class( $html ) {
	$html = str_replace('menu-main-navigation-container', 'Nav__container', $html);
	return $html;
}
```
3. Also in `functions.php`, in `function ct_2021_register_styles()`, insert
```
wp_enqueue_style('adobe-fonts', 'https://use.typekit.net/bix5hve.css', array(), 'version');
```
4. We will use `wp_nav_menu()`'s available methods to style it. Add `items_wrap`, `before`, and `after` as such, 
```
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
```
5. The Nav Section in View Page Source should look like:
```
<div class="Nav__container">
        <ul class="Nav__ul">
          <li
            id="menu-item-20082"
            class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-20080 current_page_item menu-item-20082"
          >
            <div class="Nav__anchor-p-container">
              <p>
                <a href="http://caseytreesv4.local/" aria-current="page"
                  >Front Page</a
                >
              </p>
            </div>
            <div class="Nav__slash"></div>
          </li>
          <li
            id="menu-item-20083"
            class="menu-item menu-item-type-post_type menu-item-object-page menu-item-20083"
          >
            <div class="Nav__anchor-p-container">
              <p>
                <a href="http://caseytreesv4.local/the-leaflet/">The Leaflet</a>
              </p>
            </div>
            <div class="Nav__slash"></div>
          </li>
          <li
            id="menu-item-20084"
            class="menu-item menu-item-type-post_type_archive menu-item-object-resources menu-item-20084"
          >
            <div class="Nav__anchor-p-container">
              <p>
                <a href="http://caseytreesv4.local/resources-list/"
                  >All Resources</a
                >
              </p>
            </div>
            <div class="Nav__slash"></div>
          </li>
          <li
            id="menu-item-20086"
            class="menu-item menu-item-type-post_type_archive menu-item-object-trees menu-item-20086"
          >
            <div class="Nav__anchor-p-container">
              <p>
                <a href="http://caseytreesv4.local/trees-list/">All Trees</a>
              </p>
            </div>
            <div class="Nav__slash"></div>
          </li>
        </ul>
      </div>
```
6. Now we can add the appropriate Classes to our `style.css`
```

.Nav__container {
    transform: skew(-20deg);
}

.Nav__ul {
    list-style-type: none;
    padding: 10px;
    font-family: var(--zeitung);
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    margin: 0 30px;
}

header li {
    display: flex;
}

header li a {
    color: var(--coffee);
    padding: 3px 2vw;
    text-transform: uppercase;
}

header li a:hover,
header li a:focus {
    background: var(--cobalt);
    color: var(--paper);
}

header li p {
    margin: 3px 0;
}

.Nav__slash {
    width: 2px;
    height: 100%;
    background: var(--coffee);
    margin: 0 .5vw;
}

.Nav__ul>li:last-child .Nav__slash {
    display: none;
}
```
7. AND
```
@media (max-width: 767.98px) {
    .Nav__slash {
        display: none;
    }
}
```
## Checkpoint 1
Congratulations! We've dug deep into Worpdress, and are making amazing progress!
The code up to this point is available on this Github Branch [checkpoint-1](https://github.com/caseytreesdc/caseytrees-v4/tree/checkpoint-1)! Use it to check/compare/copy/etc.
