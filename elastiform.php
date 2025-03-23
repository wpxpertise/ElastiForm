<?php
/**
 * Plugin Name: ElastiForm
 *
 * @author    elsdev
 * @copyright 2025- Elsdev
 * @license   GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: ElastiForm
 * Plugin URI: https://github.com/wpxpertise/ElastiForm
 * Description: It's a simple contact form with a drag-and-drop feature that allows you to quickly design and build forms. It's also free to collect leads and deliver them directly to Social site.
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      5.6
 * Author:           ElastiForm
 * Author URI:        https://profiles.wordpress.org/elsdev/
 * Text Domain:       elastiform
 * Domain Path: /languages/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined('ABSPATH') or die('Hey, what are you doing here? You silly human!');

define('ELASTIFORMVERSION', '1.0.0');
define('ELASTIFORMBASE_PATH', plugin_dir_path(__FILE__));
define('ELASTIFORMBASE_URL', plugin_dir_url(__FILE__));
define('ELASTIFORMPLUGIN_FILE', __FILE__);
define('ELASTIFORMPLUGIN_NAME', 'ElastiForm');

// Define the class and the function.
require_once __DIR__ . '/app/ElastiForm.php';

// Run the plugin.
ElastiForm();
