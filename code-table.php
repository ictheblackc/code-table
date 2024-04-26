<?php
/**
 * Plugin Name:     Code Table
 * Description:     Add code comparison table.
 * Version:         0.4
 */

if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'CT_VERSION', '0.4' );
define( 'CT_MINIMUM_WP_VERSION', '5.8' );
define( 'CT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( CT_PLUGIN_DIR . 'class-code-table-admin.php' );
new CodeTableAmin();

require_once( CT_PLUGIN_DIR . 'class-code-table.php' );
new CodeTable();
