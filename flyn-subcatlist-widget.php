<?php
/*
Plugin Name: Flynsarmy Subcategory List Widget
Plugin URI: http://www.flynsarmy.com
Description: Adds a customisable widget to display subcategory lists
Version: 1.1
Author: Flyn San
Author URI: http://www.flynsarmy.com
*/

/**
 * Protection
 *
 * This string of code will prevent hacks from accessing the file directly.
 */
defined('ABSPATH') or die("Cannot access pages directly.");

require __DIR__.'/subcat-widget.php';

add_action('widgets_init', create_function('', 'return register_widget("FlynCW_SubcatWidget");'));
