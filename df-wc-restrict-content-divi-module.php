<?php
/**
 * Plugin Name:     WC - Restrict Content Divi Module
 * Plugin URI:      https://www.diviframework.com
 * Description:     Divi Module for WooCommerce Memberships Content Restriction.
 * Author:          Divi Framework
 * Author URI:      https://www.diviframework.com
 * Text Domain:     df-wc-restrict-content-divi-module
 * Domain Path:     /languages
 * Version:         1.1.1
 *
 * @package
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

define('DF_RESTRICT_CONTENT_VERSION', '1.1.1');
define('DF_RESTRICT_CONTENT_DIR', __DIR__);
define('DF_RESTRICT_CONTENT_URL', plugins_url('/' . basename(__DIR__)));

require_once DF_RESTRICT_CONTENT_DIR . '/vendor/autoload.php';

$container = new \DF\DF_RESTRICT_CONTENT\Container;
$container['plugin_name'] = 'WC - Restrict Content Divi Module';
$container['plugin_version'] = DF_RESTRICT_CONTENT_VERSION;
$container['plugin_file'] = __FILE__;
$container['plugin_dir'] = DF_RESTRICT_CONTENT_DIR;
$container['plugin_url'] = DF_RESTRICT_CONTENT_URL;
$container['plugin_slug'] = 'df-wc-restrict-content-divi-module';

// activation hook.
register_activation_hook(__FILE__, array($container['activation'], 'install'));

$container->run();
