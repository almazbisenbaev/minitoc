<?php
/**
 * Plugin Name: Mini Table of Contents
 * Plugin URI: http://example.com/minitoc-plugin
 * Description: Add a Table of Contents to your post using a shortcode
 * Version: 1.0
 * Author: Almaz Bissenbayev
 * Author URI: https://almazbisenbaev.github.io
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load plugin classes
require_once plugin_dir_path(__FILE__) . 'includes/class-mini-toc.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mini-toc-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mini-toc-shortcode.php';

// Initialize the plugin
function mini_toc_init() {
    Mini_TOC::get_instance();
    Mini_TOC_Settings::get_instance();
    Mini_TOC_Shortcode::get_instance();
}
add_action('plugins_loaded', 'mini_toc_init');
