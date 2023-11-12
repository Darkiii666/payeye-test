<?php
/*
Plugin Name: PayEye Payments
Plugin URI: https://payeye.com/
Description: Just test plugin
Version: 0.1.0
Requires at least: 5.8
Requires PHP: 8.0
Author: PayEye
Author URI: https://payeye.com/
License: GPLv2 or later
Text Domain: payeye
*/

use Payeye\Woocommerce\Helpers\Plugin;

if (!defined('PAYEYE_PLUGIN_DIR')) {
    define('PAYEYE_PLUGIN_DIR', __DIR__);
}

if (!defined('PAYEYE_PLUGIN_URI')) {
    define('PAYEYE_PLUGIN_URI', plugin_dir_url(__FILE__));
}

if (!defined('PAYEYE_PLUGIN_FILE')) {
    define('PAYEYE_PLUGIN_FILE', __FILE__);
}

require_once PAYEYE_PLUGIN_DIR . "/vendor/autoload.php";

new Plugin();