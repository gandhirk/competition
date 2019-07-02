<?php
/**
 * Template part for displaying submit entry page content
 */

get_header();

$getPageID = get_option('submit_entry_page_id');

if( !empty( $getPageID ) ) {
    $c_post = get_post($getPageID);
    $content = $c_post->post_content;
    $content = do_shortcode($content);
    if(function_exists('do_blocks')){
        $content = do_blocks($content);
    }

    echo $content;
}

get_sidebar();
get_footer();

?>