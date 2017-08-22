<?php
/*
Plugin Name: LeadsNearby Recently Updated Posts
Plugin URI: http://leadsnearby.com
Description: Displays recently updated blog posts using a shortcode.
Version: 1.0.0
Author: LeadsNearby
Author URI: http://leadsnearby.com
License: GPLv2
*/

define('UpdatedPosts_MAIN', plugin_dir_path( __FILE__ ));

function lnb_last_updated_posts() { 
 
// Query Arguments
$lastupdated_args = array(
	'orderby' => 'modified',
	'ignore_sticky_posts' => '1'
);
 
//Loop to display 20 recently updated posts
$lnb_last_updated_loop = new WP_Query( $lastupdated_args );
$counter = 1; ?>
<style>
.card {background: #fff;border-radius: 2px;display: block;height: auto;margin: 1rem;padding:1.5%;position: relative;width: 97%;}
.card-1 {box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);transition: all 0.3s cubic-bezier(.25,.8,.25,1);}
.card-1:hover {box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);}
.card h3 {margin-top:0;}
</style>
<? while( $lnb_last_updated_loop->have_posts() && $counter < 20 ) : $lnb_last_updated_loop->the_post();

$logo_url = Avada_Sanitize::get_url_with_correct_scheme( Avada()->settings->get( 'logo', 'url' ) );
$content = get_the_content( $lnb_last_updated_loop->post->ID );
$excerpt = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $content);
$trim = wp_trim_words( $excerpt, 40, '...' );    

$string .= '<div class="card card-1"><a href="' . get_permalink( $lnb_last_updated_loop->post->ID ) . '"><div class="fusion-one-third fusion-layout-column fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;">';
if(has_post_thumbnail()) {                    
    $image_src = wp_get_attachment_image_src( get_post_thumbnail_id(),'full' );
    $string .='<img itemprop="image" alt="'.get_post_field('post_excerpt', get_post_thumbnail_id(get_the_ID())).'" id="image-slide" src="' . $image_src[0]  . '" style="height:auto; width:100%; margin:0; display:block;" />';
} else {
	$string .='<img itemprop="image" src="' . $logo_url . '" style="width:100%; margin:0; display:block;" />';
}
$string .= '</div><div class="fusion-two-third fusion-layout-column fusion-column-last fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;"><h3 class="title-heading-left" data-fontsize="19" data-lineheight="29">' .get_the_title( $lnb_last_updated_loop->post->ID ) . '</h3><p>' .$trim. '</p><div style="font-size:10px;"><span>Last Updated:</span> '. get_the_modified_date() .'</div></div>';
$string .= '<div style="clear:both"></div></a></div>';
$counter++;
endwhile; 
return $string;
wp_reset_postdata(); 
} 
 
//add a shortcode
add_shortcode('last-updated-posts', 'lnb_last_updated_posts');

require_once( UpdatedPosts_MAIN . 'lib/updater/github-updater.php' );
new GitHubPluginUpdater( __FILE__, 'LeadsNearby', 'updated-posts' );


?>