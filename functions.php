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
