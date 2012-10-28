<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
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
    <?php $themeuri = get_template_directory_uri(); ?>
    <!-- Styling -->
    <link href="<?php echo $themeuri; ?>/reset.css" rel="stylesheet" media="all" type="text/css" />
    <link href="<?php bloginfo( 'stylesheet_url' ); ?>" rel="stylesheet" media="all" type="text/css" />
    <!--[if lte IE 8]>
      <link href="<?php echo $themeuri; ?>/ie8.css" rel="stylesheet" media="all" type="text/css" />
    <![endif]-->
    <link href="<?php echo $themeuri; ?>/wysiwyg.css" rel="stylesheet" media="all" type="text/css" />

    <!-- Script Styling -->
    <link href="<?php echo $themeuri; ?>/script/scrollbar/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css" />
    <!-- Script -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
    <script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo $themeuri; ?>/script/jquery-1.8.2.min.js"%3E%3C/script%3E'))</script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <script>!window.jQuery.ui && document.write(unescape('%3Cscript src="<?php echo $themeuri; ?>/script/jquery-ui-1.9.1.custom.min.js"%3E%3C/script%3E'))</script>
    <script src="<?php echo $themeuri; ?>/script/scrollbar/jquery.mousewheel.min.js"></script>
    <script src="<?php echo $themeuri; ?>/script/scrollbar/jquery.mCustomScrollbar.js"></script>
    <script src="<?php echo $themeuri; ?>/script/script.js"></script>

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

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
                /* Main menu ****************************
                 *
                 * TODO: 1) Only 1 level menu (level 1).
                 * TODO: 2) Id in the <li id="page-item-$id">.
                 * TODO: 3) Active menu class can be "active" or "current_page_item" (Default from wordpress). Active if itself or submenu is active.
                 * TODO: 4) No need Home menu.
                 *
                 * Expectated output:
                 *  <ul class="menu">
                 *      <li id="page-item-1" class="current_page_item">
                 *          <a href="/menu1">Menu 1</a>
                 *      </li>
                 *      <li id="page-item-2">
                 *          <a href="/menu2">Menu 2</a>
                 *      </li>
                 *  </ul>
                 */

                /* From  wordpress default
                 * Our navigation menu. If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assiged to the primary position is the one used. If none is assigned, the menu with the lowest ID is used.
                 */
                wp_nav_menu( array( 'container_class' => 'menu', 'theme_location' => 'primary', 'depth' => 1 ) );
                ?>
            </div>
            <a href="#" class="close"><span>close</span></a>
        </div>

        <?php
        /* **********************************************
         * Sub menu panel
         * Page List
         *
         */
        ?>
        <div id="sub-panel">
            <div class="wrapper">
                <?php
                /* Sub menu ****************************
                 *
                 * TODO: 1) List all the level 2 menu and listed only 1 level.
                 * TODO: 2) Id in the <ul id="sub-page-item-$parentid">.
                 * TODO: 3) Active menu class can be "active" or "current_page_item" (Default from wordpress). Active if itself or submenu is active.
                 *
                 * Expectated output:
                 *  <ul class="menu" id="sub-page-item-1">
                 *      <li class="current_page_item">
                 *          <a href="/menu1/submenu1-1">submenu1-1</a>
                 *      </li>
                 *      <li>
                 *          <a href="/menu1/submenu1-2">submenu1-2</a>
                 *      </li>
                 *  </ul>
                 *  <ul class="menu" id="sub-page-item-2">
                 *      <li class="current_page_item">
                 *          <a href="/menu2/submenu2-1">submenu2-1</a>
                 *      </li>
                 *      <li>
                 *          <a href="/menu2/submenu2-2">submenu2-2</a>
                 *      </li>
                 *  </ul>
                 */
                ?>
                <ul class="menu">
                    <li>
                        <a href="/">Menu 1</a>
                    </li>
                    <li>
                        <a href="/">Menu 2</a>
                    </li>
                </ul>
            </div>
            <a href="#" class="close"><span>close</span></a>
        </div>