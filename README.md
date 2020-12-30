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
   2. Create a new page "Home 2021"
   3. Notice that in the CMS Sidebar, we have Pages, Posts, Media, but no Resources or Trees. These are [Custom Post Types](https://wordpress.org/support/article/post-types/#custom-post-types) ... READ MORE ...

## A New Home Page
### Create `front-page.php` in the theme directory (`/themes/ct_theme21`)
1. At `Settings > Reading` set `Your homepage displays` to the radio button for Static Page, and select the new page we created "Home 2021" and Save Changes
2. The home page is now rendered by `front-page.php`.

### The Header
1. In `front-page.php` put `<?php get_header(); ?>` to call on `header.php`. 
2. At [`Appearance > Themes`](https://caseytrees-v4.local/wp-admin/themes.php), select Customize and upload a new favicon if desired. 
   1. There is no option to upload a logo for the site, but we will add it now. 
3. Create `functions.php` - add
```
/*
===================================
Theme Supports
===================================
*/
function ct2021_theme_setup() {

    add_theme_support('custom-logo');

}

add_action('after_setup_theme', 'ct2021_theme_setup');

```

1. Create `header.php`
```
<?php 
    if ( function_exists( 'the_custom_logo' ) ) {
        the_custom_logo();
   }
?>
<?php 
wp_nav_menu(
    array(
        'theme_location' => 'primary'
    )
); 
?>
```
*** in the above code, the string given to 'theme_location without registration in functions.php seems to give us a menu of all our current pages and parent child relationships? 

#### Check out the browser and what it renders on [Front-page.php](http://caseytrees-v4.local/).
## Creating a simple menu with relevant Pages/Content
1. Create a new Page in the CMS called 'Blog Posts' or 'The Leaflet'
2. Under `Settings > Reading` set `Posts Page:` to the Page we created.
3. Create `home.php` in the template directory to render the the Blog Posts Index page with the following:
```
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
```
4. Navigate to `Appearance > Menus`, create a new menu, and to it add The Front Page and the Posts Page. Save Menu. 
5. Add the following to `functions.php` to register a menu to a location. We will add more to `$locations` eventually. 
```
/*
    ===================================
    Menu
    ===================================
*/
function ct2021_menus() {
    $locations = array(
        'primary' => 'Header Menu',
    );

    register_nav_menus($locations);
}

add_action('init', 'ct2021_menus');
```