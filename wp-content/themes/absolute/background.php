<?php
/**
 * Background
 */
?>

<?php
    rewind_posts();

    if ( is_singular() && current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail( $post->ID )) :
        // Houston, we have a new header image!
        $post_thumbnail_id = get_post_thumbnail_id( $post->ID );
        $custom_fields = get_post_custom($post_thumbnail_id);
        $attr = array();
        if($custom_fields['_custom_src']):
            $attr['data-href'] = $custom_fields['_custom_src'][0];
        endif;
        $bg = get_the_post_thumbnail( $post->ID, 'full', $attr );
        if ($bg):
            echo '<div class="custom-bg">' . $bg . '</div>';
        endif;
    endif;

    /* Image rotation */
    $args = array(
                'post_type' =>'attachment',
                'post_status' => 'any',
                'posts_per_page' => 10,
                'meta_key' => '_custom_banner_order',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_query' => array( array( 'key' => '_custom_banner', 'value' => '1', 'compare' => '=' ) )
            );
    $images = new WP_Query( $args );
    if($images->post_count):
        ?><div id="image-rotation-wrp" class="theme-default"><?php
        if($images->post_count > 1):
            echo '<div id="slider" class="nivoSlider">';
        endif;
        while ( $images->have_posts() ) : $images->the_post();
            $custom_fields = get_post_custom($images->post->ID);
            $attr = array(title=>"", alt=>"");
            if($custom_fields['_custom_src']):
                $href = $attr['data-href'] = $custom_fields['_custom_src'][0];
            endif;
            echo ($href ? "<a href='$href'>" : '')
                . wp_get_attachment_image( $images->post->ID, 'large', 0, $attr )
                . ($href ? "</a>" : '');
        endwhile;
        if($images->post_count > 1):
            echo '</div>';
        endif;
        ?></div><?php
    elseif ( function_exists( 'get_custom_header' ) && get_header_image() ) :
        ?><div id="image-rotation-wrp">
            <img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
        </div><?php
    endif;
    wp_reset_query();
    wp_reset_postdata();
?>