<?php
/*
Template Name: Contact us
*/
?>

<?php get_header(); ?>
<?php
$page_id  = get_the_ID();
$attachments = get_posts(array(
    'post_type' => 'attachment',
    'numberposts' => null,
    'post_status' => null,
    'post_parent' => $page_id
));

if ( have_posts() ) while ( have_posts() ) : the_post();
?>
    <!-- Content panel -->
    <div id="content-panel">
        <div class="content-mask">
            <div class="wrapper">
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <?php if ( has_post_thumbnail( $post->ID )): ?>
                    <a href="#" class="show-background"><?php echo get_the_post_thumbnail( $post->ID, 'thumbnail');?></a>
                <?php endif; ?>
                <?php if ($attachments): ?>
                <h2>Show room</h2>
                <ul class="image-list">
                    <?php foreach ($attachments as $attachment):
                            $document_title = apply_filters('the_title', $attachment->post_title);
                    ?>
                    <li><a href="<?php echo get_attachment_link($attachment->ID); ?>" title="<?php echo $document_title ?>">
                        <?php echo $document_title ?>
                    </a></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <div class="wysiwyg">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; // end of the loop. ?>
<?php
get_template_part( 'background' );
get_footer();
?>