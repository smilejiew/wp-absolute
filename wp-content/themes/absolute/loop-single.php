<?php
/**
 * The loop that displays a single post.
 *
 * The loop displays the posts and the post content. See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-single.php.
 *
 */
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="detail-box">
        <div class="wrapper">
            <h1><?php the_title(); ?></h1>
            <div class="wysiwyg"><?php the_content(); ?></div>
            <ul id="detail-nav">
              <li id="detail-nav-back">
                  <?php previous_post_link( '%link', '<span>back</span>' ); ?>
              </li>
              <li id="detail-nav-next">
                  <?php previous_post_link( '%link', '<span>next</span>' ); ?>
              </li>
            </ul>
        </div>
    </div>
<?php endwhile; // end of the loop. ?>
