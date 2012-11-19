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
            <div class="wysiwyg"><?php _e( 'Apologies, but the page you requested could not be found.', 'twentyten' ); ?></div>
        </div>
    </div>
    <?php return; ?>
<?php endif; ?>

<?php
$page_id  = get_the_ID();
$parent   = get_ancestors($page_id, 'page');
$children = get_pages( array( 'child_of' => $page_id, 'parent' => $page_id, 'sort_column' => 'menu_order') );

// Check if the page is first/second level
if(count($parent) == 1 || (count($parent) == 0 && count($children) == 0) ):
?>
    <!-- Content panel -->
    <div id="content-panel">
        <div class="content-mask">
            <div class="wrapper">
                <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <?php if ( has_post_thumbnail( $post->ID )): ?>
                        <a href="#" class="show-background"><?php echo get_the_post_thumbnail( $post->ID, 'thumbnail');?></a>
                    <?php endif; ?>
                    <div class="wysiwyg">
                        <?php the_content(); ?>
                    </div>
                <?php endwhile; // end of the loop. ?>
            </div>
        </div>
    </div>
    <?php
    foreach( $children as $pg ):
        $custom_fields = get_post_custom($pg->ID);
        $top_position = $custom_fields['top'];
        $left_position = $custom_fields['left'];
    ?>
        <a href="<?php echo get_permalink( $pg->ID ) ?>" class="bullet" style="top:<?php echo $top_position[0];?>px;left:<?php echo $left_position[0];?>px;">
            <span>bullet</span>
        </a>
    <?php endforeach ?>
<?php elseif(count($parent) >= 2 ):?>
    <?php
    $sibling = count($parent) == 0 ? array() : get_pages( array(  'child_of' => $parent[0], 'parent' => $parent[0], 'sort_column' => 'menu_order') );

    if ( have_posts() ) while ( have_posts() ) : the_post();
    ?>
        <!-- Box content -->
        <div class="detail-box">
            <div class="wrapper">
                <h1><?php the_title(); ?></h1>
                <div class="wysiwyg detail"><?php the_content(); ?></div>
                <?php if(count($sibling) > 1):
                    $idx = 0;
                    foreach( $sibling as $pg ):
                        if($pg->ID == $page_id){
                            break;
                        }
                        $idx++;
                    endforeach;
                    $prev = $sibling[$idx - 1];
                    $next = $sibling[$idx + 1];
                ?>
                    <ul id="detail-nav">
                      <li id="detail-nav-next" <?php echo !$next ? 'class="invisible"' : '' ?>>
                          <a href="<?php echo $next ? get_permalink( $next->ID ) : '#' ?>"><span>next</span></a>
                      </li>
                      <li id="detail-nav-back" <?php echo !$prev ? 'class="invisible"' : '' ?>>
                          <a href="<?php echo $prev ? get_permalink( $prev->ID ) : '#' ?>"><span>prev</span></a>
                      </li>
                    </ul>
                <?php endif ?>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif;?>
