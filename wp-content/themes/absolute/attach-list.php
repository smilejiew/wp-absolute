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
    'numberposts' => -1,
    'post_status' => null,
    'post_parent' => $page_id
));
$image_group = array();
foreach ($attachments as $attachment) {
    $custom_fields = get_post_custom($attachment->ID);
    $name          = isset($custom_fields['_custom_group'][0]) && $custom_fields['_custom_group'][0]
                   ? $custom_fields['_custom_group'][0] : 'Others';
    $color         = isset($custom_fields['_custom_group_color'][0]) && $custom_fields['_custom_group_color'][0]
                   ? $custom_fields['_custom_group_color'][0] : false;
    $image         = isset($custom_fields['_custom_group_image'][0]) && $custom_fields['_custom_group_image'][0]
                   ? $custom_fields['_custom_group_image'][0] : false;

    if ( !isset($image_group[$name]) ) {
        $image_group[$name] = array('image' => false,'color' => false, 'items' => array());
    }
    $image_group[$name]['image']   = $image ? $image : $image_group[$name]['image'];
    $image_group[$name]['color']   = $color ? $color : $image_group[$name]['color'];
    $image_group[$name]['items'][] = $attachment;
}

if ( have_posts() ) while ( have_posts() ) : the_post();
?>
    <!-- Content panel -->
    <div id="content-panel">
        <div class="content-mask">
            <div class="wrapper">
                <?php if ($image_group): ?>
                    <div class="showroom">
                        <h2>Show room</h2>
                        <?php foreach ($image_group as $key => $value):
                                if ($value['color'] || $value['image']) {
                                    $style = $value['color'] ? 'style="color:' . $value['color'] . '"' : '';
                                    $name  = $value['image'] ? '<img href="' . $value['image'] . '" alt="' . $key . '" title="' . $key . '">' : $key;
                                } else {
                                    $style = '';
                                    $name  = $key;
                                }
                            ?>
                            <h3 <?php echo $style ?>><?php echo $name ?></h3>
                            <ul class="image-list">
                                <?php foreach ($value['items'] as $attachment):
                                        $document_title = apply_filters('the_title', $attachment->post_title);
                                ?>
                                <li><a href="<?php echo get_attachment_link($attachment->ID); ?>" title="<?php echo $document_title ?>">
                                    <?php echo $document_title ?>
                                </a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <?php if ( has_post_thumbnail( $post->ID )): ?>
                    <a href="#" class="show-background"><?php echo get_the_post_thumbnail( $post->ID, 'thumbnail');?></a>
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