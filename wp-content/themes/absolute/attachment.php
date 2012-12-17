<?php
/**
 * The template for displaying attachments.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

<?php 
if ( have_posts() ):
    while ( have_posts() ) : the_post();
        if ( wp_attachment_is_image() ):
            $custom_fields = get_post_custom($post->ID);
            $attr = array();
            if($custom_fields['_custom_src']):
                $attr['data-href'] = $custom_fields['_custom_src'][0];
            endif;
            echo '<div class="custom-bg">' . wp_get_attachment_image( $post->ID, 'full', false, $attr ) . '</div>';
        else:
            get_template_part( 'background' );
        endif;
    endwhile;
endif;
?>
<?php get_footer(); ?>
