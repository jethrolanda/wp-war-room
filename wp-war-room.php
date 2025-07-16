<?php

/**
 * Plugin Name: WP War Room
 * Description: WP War Room
 * Version: 1.0
 * Author: Jethro Landa
 * Author URI: https://jethrolanda.com/
 * Text Domain: wp-war-room
 * Domain Path: /languages/
 * Requires at least: 5.7
 * Requires PHP: 7.2
 */

defined('ABSPATH') || exit;

// Path Constants ======================================================================================================

define('WPWR_PLUGIN_URL',             plugins_url() . '/wp-war-room/');
define('WPWR_PLUGIN_DIR',             plugin_dir_path(__FILE__));
define('WPWR_CSS_ROOT_URL',           WPWR_PLUGIN_URL . 'css/');
define('WPWR_JS_ROOT_URL',            WPWR_PLUGIN_URL . 'js/');
define('WPWR_JS_ROOT_DIR',            WPWR_PLUGIN_DIR . 'js/');
define('WPWR_TEMPLATES_ROOT_URL',     WPWR_PLUGIN_URL . 'templates/');
define('WPWR_TEMPLATES_ROOT_DIR',     WPWR_PLUGIN_DIR . 'templates/');
define('WPWR_BLOCKS_ROOT_URL',        WPWR_PLUGIN_URL . 'blocks/');
define('WPWR_BLOCKS_ROOT_DIR',        WPWR_PLUGIN_DIR . 'blocks/');

// Require autoloader
require_once 'inc/autoloader.php';

// Require settings
// require_once "settings/my-first-gutenberg-app.php";

// Run
require_once 'wp-war-room.plugin.php';
$GLOBALS['wpwr'] = new WP_War_Room();
