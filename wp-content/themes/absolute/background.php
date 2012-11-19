<?php
/**
 * Background
 */
?>

<?php
    rewind_posts();

    // Compatibility with versions of WordPress prior to 3.4.
    if ( function_exists( 'get_custom_header' ) ) {
        // We need to figure out what the minimum width should be for our featured image.
        // This result would be the suggested width if the theme were to implement flexible widths.
        $header_image_width = get_theme_support( 'custom-header', 'width' );
    } else {
        $header_image_width = HEADER_IMAGE_WIDTH;
    }

    // Check if this is a post or page, if it has a thumbnail.
    if ( is_singular() && current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail( $post->ID )) :
        // Houston, we have a new header image!
        $post_thumbnail_id = get_post_thumbnail_id( $post->ID );
        $custom_fields = get_post_custom($post_thumbnail_id);
        $attr = array();
        if($custom_fields['_custom_src']):
            $attr['data-href'] = $custom_fields['_custom_src'][0];
        endif;
        echo '<div class="custom-background">' . get_the_post_thumbnail( $post->ID, 'full', $attr ) . '</div>';
    elseif ( get_header_image() ) :
        // Compatibility with versions of WordPress prior to 3.4.
        if ( function_exists( 'get_custom_header' ) ) {
            $header_image_width  = get_custom_header()->width;
            $header_image_height = get_custom_header()->height;
        } else {
            $header_image_width  = HEADER_IMAGE_WIDTH;
            $header_image_height = HEADER_IMAGE_HEIGHT;
        }
?>
<div class="custom-background">
    <img src="<?php header_image(); ?>" width="<?php echo $header_image_width; ?>" height="<?php echo $header_image_height; ?>" alt="" />
</div>
<?php endif; ?>