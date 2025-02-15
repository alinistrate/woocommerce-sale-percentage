<?php //phpcs:ignore

// phpcs:disable
/*
  Plugin Name: Sale Percentage
  Version: 1.0.4
  Text Domain: sale-percentage
  Description: Manages and displays sale percentage labels.
  Author: netzstrategen
  Author URI: https://netzstrategen.com
  License: GPL-2.0+
  License URI: http://www.gnu.org/licenses/gpl-2.0
*/
// phpcs:enable

namespace Netzstrategen\SalePercentage;

if (!defined('ABSPATH')) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
  exit;
}

/**
 * Loads PSR-4-style plugin classes.
 */
function classloader($class) {
  static $ns_offset;
  if (strpos($class, __NAMESPACE__ . '\\') === 0) {
    if ($ns_offset === NULL) {
      $ns_offset = strlen(__NAMESPACE__) + 1;
    }
    include __DIR__ . '/src/' . strtr(substr($class, $ns_offset), '\\', '/') . '.php';
  }
}

spl_autoload_register(__NAMESPACE__ . '\classloader');

register_activation_hook(__FILE__, __NAMESPACE__ . '\Schema::activate');
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\Schema::deactivate');
register_uninstall_hook(__FILE__, __NAMESPACE__ . '\Schema::uninstall');

add_action('plugins_loaded', __NAMESPACE__ . '\Plugin::loadTextdomain');
add_filter('woocommerce_get_settings_pages', __NAMESPACE__ . '\Settings::addSettings');
add_action('init', __NAMESPACE__ . '\Plugin::init', 20);
// Displays a notice if woocommerce is not installed and active.
add_action('admin_notices', __NAMESPACE__ . '\Plugin::admin_notices');

if (defined('WP_CLI') && WP_CLI) {
  \WP_CLI::add_command('sale-percentage', __NAMESPACE__ . '\CliCommand');
}
