<?php

/*
 * Plugin Name: Search Slug Plugin
 * Description: Plugin which allows you to search post/pages/post_types by slug inside /wp-admin area
 * Version: 0.1.0
 * Author: KV
 */

if ( ! defined('ABSPATH')) {
    exit;
}

add_filter('posts_search', 'search_slug', 10, 2);

function search_slug($search, $wp_query) {
    global $wpdb;
    $result = $search;

    if  (!$wp_query->is_admin) {
        return $result;
    }

    $slug = 'slug:';
    $s = $wp_query->query_vars['s'];

    if (!empty($s) && strripos($wp_query->query_vars['s'], $slug) !== false) {
        $s = str_ireplace($slug, '',$s);

        if ($s !== '') {
            $like  = '%' . $wpdb->esc_like( $s ) . '%';
            $search = $wpdb->prepare( "{$wpdb->posts}.post_title LIKE %s", $like );
            $result = " AND ({$search}) ";
        }
    }

    return $result;
}

