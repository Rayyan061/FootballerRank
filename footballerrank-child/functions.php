<?php
if (!defined('ABSPATH')) { exit; }

function footballerrank_child_assets(): void {
    $version = wp_get_theme()->get('Version');
    wp_enqueue_style(
        'footballerrank-child',
        get_stylesheet_directory_uri() . '/assets/css/footballerrank.css',
        [],
        $version
    );
    wp_enqueue_script(
        'footballerrank-child',
        get_stylesheet_directory_uri() . '/assets/js/footballerrank.js',
        [],
        $version,
        true
    );

    if (is_singular('post')) {
        $single_css_path = get_stylesheet_directory() . '/assets/css/single-post.css';
        $single_js_path  = get_stylesheet_directory() . '/assets/js/single-post.js';
        wp_enqueue_style(
            'footballerrank-single-post',
            get_stylesheet_directory_uri() . '/assets/css/single-post.css',
            ['footballerrank-child'],
            file_exists($single_css_path) ? (string) filemtime($single_css_path) : $version
        );
        wp_enqueue_script(
            'footballerrank-single-post',
            get_stylesheet_directory_uri() . '/assets/js/single-post.js',
            [],
            file_exists($single_js_path) ? (string) filemtime($single_js_path) : $version,
            true
        );
    }

    if (is_author()) {
        $author_css_path = get_stylesheet_directory() . '/assets/css/author-page.css';
        wp_enqueue_style(
            'footballerrank-author-page',
            get_stylesheet_directory_uri() . '/assets/css/author-page.css',
            ['footballerrank-child'],
            file_exists($author_css_path) ? (string) filemtime($author_css_path) : $version
        );
    }
}
add_action('wp_enqueue_scripts', 'footballerrank_child_assets', 20);

function footballerrank_child_setup(): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('footballerrank-card', 720, 900, true);
}
add_action('after_setup_theme', 'footballerrank_child_setup');

function footballerrank_register_patterns(): void {
    if (!function_exists('register_block_pattern_category')) { return; }
    register_block_pattern_category('footballerrank', ['label' => __('FootballerRank', 'footballerrank-child')]);
}
add_action('init', 'footballerrank_register_patterns');
