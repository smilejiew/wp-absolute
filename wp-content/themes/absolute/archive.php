<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header();

if ( !is_home() ) {
    /* Run the loop to output the page.
     * If you want to overload this in a child theme then include a file
     * called loop-page.php and that will be used instead.
     */
    get_template_part( 'loop', 'index' );
}
get_template_part( 'background' );

get_sidebar();
get_footer();
?>
