<?php
/*
   Plugin Name: WP REST API JSON Filters
   Plugin URI: http://torbjornzetterlund.com/wp-android-rest-api-filters
   Version: 0.1
   Author: Torbjorn Zetterlund
   Description: Adds additional filter to the JSON response to limited the number of REST API calls from a mobile app
   Text Domain: wp-android-rest-api-filters
   License: GPLv3
  */

/**
  Add comments count to the JSON main response
 */

function my_rest_prepare_post_comments( $data, $post, $request ) {
    $args = array(
    'post_id' => $post->ID, // use post_id, not post_ID
        'count' => true, // return only the count
      'status' => 'approve'
  );
    $_data = $data->data;
    $comments = get_comments($args);
  $_data['comments_count'] = $comments;
  $data->data = $_data;
  return $data;
}

/**
 Get user profile picture from WP REST API 
 */

function my_rest_author_prepare_post( $data, $post, $request ) {
  $_data = $data->data;
    $avatar = get_avatar_url ( get_the_author_meta( 'ID' ), 32 );
    $_data['author_image_thumbnail_url'] = $avatar;
  $data->data = $_data;
  return $data;
}

/**
 * Get Image in REST API
 */

function my_rest_prepare_post( $data, $post, $request ) {
  $_data = $data->data;
  $thumbnail_id = get_post_thumbnail_id( $post->ID );
  $thumbnail = wp_get_attachment_image_src( $thumbnail_id );
    $big = wp_get_attachment_image_src( $thumbnail_id, 'full' );
  $_data['featured_image_thumbnail_url'] = $thumbnail[0];
  $_data['featured_image_big_url'] = $big[0];
  
  $data->data = $_data;
  return $data;
}

/*
Initialize the filters
*/

add_filter( 'rest_prepare_post', 'my_rest_prepare_post', 10, 3 );

add_filter( 'rest_prepare_post', 'my_rest_prepare_post_comments', 10, 3 );

add_filter( 'rest_prepare_post', 'my_rest_author_prepare_post', 10, 3 );
