<?php
/**
 * The loop that displays a page.
 *
 * The loop displays the posts and the post content. See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-page.php.
 */
?>
<!-- Content panel -->
<div id="content-panel">
    <div class="content-mask"><div class="wrapper">
        <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

            <h1 class="entry-title"><a href="" title=""><?php the_title(); ?></a></h1>

            <?php /* TODO: Check for contact us page */ ?>
            <?php if (0): ?>
                <!-- Contact US -->
                <form method="post" action="#" class="contact-us-form">
                    <p><input type="text" value="" name="" placeholder="YOUR NAME" /></p>
                    <p><input type="email" value="" name="" placeholder="YOUR E-MAIL" /></p>
                    <p><textarea name=""></textarea></p>
                    <p class="submit"><button type="submit">Submit</button></p>
                </form>
            <?php else: ?>
                <?php if ( has_post_thumbnail( $post->ID )): ?>
                    <a href="#" class="show-background"><?php echo get_the_post_thumbnail( $post->ID, 'thumbnail');?></a>
                <?php endif; ?>
                <div class="wysiwyg">
                    <?php the_content(); ?>
                </div>
            <?php endif; ?>

        <?php endwhile; // end of the loop. ?>
    </div></div>
</div>

<?php
/* Bullet
 * TODO: 1) List all the Bullet in this page
 * TODO: 2) Apply top and left position in style attribute
 */
?>
<a href="" class="bullet" style="top:200px;left:800px;">
  <span>bullet 1</span>
</a>
<a href="" class="bullet" style="top:350px;left:700px;">
  <span>bullet 1</span>
</a>