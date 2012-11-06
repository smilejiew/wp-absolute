<?php

get_header();

if ( !is_home() ) {

    get_template_part( 'loop', 'index' );

}

get_template_part( 'background' );

//get_sidebar();
get_footer();
?>