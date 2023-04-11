<?php

/*
 * Plugin Name: Search Slug Plugin
 * Description: Plugin which allows you to search post/pages/post_types by slug inside /wp-admin area
 * Version: 0.1.0
 * Author: KV
 */

namespace SearchSlug;

if ( ! defined('ABSPATH')) {
    exit;
}


if ( ! class_exists('SearchSlug')) {

    class SearchSlug
    {
        public function __construct() {
            // Do nothing.
        }

        /**
         * @return void
         */
        public function initialize()
        {
            add_filter('posts_search', [$this, 'search_slug'], 10, 2);
        }

        /**
         * @param $search
         * @param $wp_query
         *
         * @return mixed|string
         */
        public function search_slug($search, $wp_query) {
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
    }

    function SearchSlug() {
        global $searchSlug;

        if ( ! isset( $searchSlug ) ) {
            $searchSlug = new SearchSlug();
            $searchSlug->initialize();
        }
        return $searchSlug;
    }

    SearchSlug();
}
