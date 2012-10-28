<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content. See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 */
?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
    <div class="detail-box">
        <div class="wrapper">
            <h1><?php _e( 'Not Found', 'twentyten' ); ?></h1>
            <h2>...</h2>
            <div class="wysiwyg"><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'twentyten' ); ?></div>
        </div>
    </div>
    <?php return; ?>
<?php endif; ?>

<?php
/* **********************************************
 * Start the Loop.
 */
get_template_part( 'loop', 'page' );
?>
