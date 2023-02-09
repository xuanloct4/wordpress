<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.upwork.com/fl/rayhan1
 * @since             1.0.0
 * @package           AI_Writing_Assistant
 *
 * @wordpress-plugin
 * Plugin Name:       AI Content Writing Assistant (Content Writer, ChatGPT, Image Generator) All in One
 * Plugin URI:        https://myrecorp.com/
 * Description:       "AI Content Writing Assistant" is a WordPress plugin that uses AI to generate high-quality content for your website or blog. It can create articles, blog posts, and more, and even suggest topic ideas and help with keyword optimization. Save time and improve the quality of your content with this must-have plugin.
 * Version:           1.0.3
 * Author:            RAYHAN KABIR
 * Author URI:        https://www.upwork.com/fl/rayhan1
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ai-writing-assistant
 * Domain Path:       /languages
 */

use WpWritingAssistant\PluginActivator;
use WpWritingAssistant\PluginDeactivator;

if (!defined('WPINC')) {
    die;
}

// Define the version and name of the plugin
define( 'AIWA_VERSION', '1.0.3' );
define( 'AIWA_NAME', 'ai_writing_assistant' );

// Define the directory path and URL for the plugin
define( 'AIWA_DIR_PATH', dirname(__FILE__) . '/' );
define( 'AIWA_DIR_URL', plugin_dir_url(__FILE__) . '/' );

// Include the file containing the plugin class
require 'includes/class-WpWritingAssistant.php';

/**
 * Activation hook function that runs when the plugin is activated.
 *
 * @since 1.0.0
 */
function ai_writing_assistant_plugin_activator(){
    // Include the file containing the activation class
    require 'includes/plugin-activator.php';

    // Create an instance of the activation class and call its activator method
    $plugin = new PluginActivator;
    $plugin->activator();
}
// Register the activation hook
register_activation_hook( __FILE__, 'ai_writing_assistant_plugin_activator' );

/**
 * Deactivation hook function that runs when the plugin is deactivated.
 *
 * @since 1.0.0
 */
function ai_writing_assistant_plugin_deactivator(){
    // Include the file containing the deactivation class
    require 'includes/plugin-deactivator.php';

    // Create an instance of the deactivation class and call its deactivator method
    $plugin = new PluginDeactivator();
    $plugin->deactivator();
}
// Register the deactivation hook
register_deactivation_hook( __FILE__, 'ai_writing_assistant_plugin_deactivator' );

/**
 * Function that runs when the plugin is loaded.
 *
 * @since 1.0.0
 */
function ai_writing_assistant() {
    // Create an instance of the plugin class and call its run method
    $plugin = new WpWritingAssistant\WpWritingAssistant();
    $plugin->run();
}
// Run the plugin
ai_writing_assistant();

