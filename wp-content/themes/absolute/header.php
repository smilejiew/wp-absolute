<?php
/**
 * The Header for our theme.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).

	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
        */

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
    <div id="site-wrapper">
        <?php
        /* **********************************************
         * Site logo
         */
        ?>
        <div id="site-title" class="logo">
            <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><span><?php bloginfo( 'name' ); ?></span></a>
        </div>

        <?php
        /* **********************************************
         * Menu Navigation
         * Open and close main menu
         */
        ?>
        <ul id="main-nav">
            <li id="main-nav-open">
                <a href="#"><span>Open menu</span></a>
            </li>
            <li id="main-nav-back">
                <a href="#"><span>Back</span></a>
            </li>
        </ul>

        <?php
        /* **********************************************
         * Main menu panel
         * Page List
         */

        $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div';
        ?>
        <div id="main-panel">
            <div class="wrapper">
                <<?php echo $heading_tag; ?> class="logo">
                    <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><span><?php bloginfo( 'name' ); ?></span></a>
                </<?php echo $heading_tag; ?>>

                <?php
                /* From  wordpress default
                 * Our navigation menu. If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assiged to the primary position is the one used. If none is assigned, the menu with the lowest ID is used.
                 *
                 * User custom menu feature
                 * Modify in Admin page
                 * Go to Appearance > Menus
                 */
                    wp_nav_menu( array( 'menu_id' => 'main-menu', 'container_class' => 'menu', 'theme_location' => 'primary', 'depth' => 2 ) );
                ?>
                <div id="nav-social">
                    <ul>
                        <li><a href="http://www.facebook.com/" title="facebook" target="_blank" class="nav-fb"><span>facebook</span></a></li>
                        <li><a href="https://twitter.com/" title="twitter" target="_blank" class="nav-tw"><span>twitter</span></a></li>
                        <li><a href="https://plus.google.com/" title="google plus" target="_blank" class="nav-plus"><span>google plus</span></a></li>
                        <li><a href="<?php echo home_url( '/' ); ?>?feed=rss" target="_blank" title="rss feed" class="nav-rss"><span>feed</span></a></li>
                    </ul>
                </div>
            </div>
            <a href="#" class="close"><span>close</span></a>
        </div>