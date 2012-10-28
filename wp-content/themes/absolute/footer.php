<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=site-wrapper div and all content
 * after. Calls sidebar-footer.php for bottom widgets.
 *
 */
?>

    </div><!-- #site-wrapper -->
    <?php
    /* A sidebar in the footer? Yep. You can can customize
     * your footer with four columns of widgets.
     */
    get_sidebar( 'footer' );

    /* Always have wp_footer() just before the closing </body>
     * tag of your theme, or you will break many plugins, which
     * generally use this hook to reference JavaScript files.
     */

    wp_footer();
    ?>
</body>
</html>
