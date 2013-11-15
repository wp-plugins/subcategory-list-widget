<?php
/**
 * @package Flynsarmy Subcategory List Widget
 * @version 1.0.0
 *
 * Plugin Name: Flynsarmy Subcategory List Widget
 * Description: Adds various category widgets
 * Author: Flynsarmy
 * Version: 0.1
 * Author URI: http://www.flynsarmy.com
 */

/**
 * Protection
 *
 * This string of code will prevent hacks from accessing the file directly.
 */
defined('ABSPATH') or die("Cannot access pages directly.");

require __DIR__.'/subcat-widget.php';

add_action('widgets_init', create_function('', 'return register_widget("FlynCW_SubcatWidget");'));
