<?php
/*
Plugin Name: WP Crossfire
Plugin URI: http://jumping-duck.com/wordpress.
Description: Automatically cross-post new posts to other sites on the network.
Version: 0.1
Author: Eric Mann
Author URI: http://www.eamann.com
License: GPLv3+
*/

/*  Copyright 2011  Eric Mann  (email : eric@eamann.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
load_plugin_textdomain( 'wp-crossfire', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/' );


if ( version_compare(PHP_VERSION, '5.2', '<') ) {
    deactivate_plugins(__FILE__);
    wp_die( __('WP Crossfire requires PHP 5.2 or higher, it has now disabled itself.', 'wp-crossfire') );
}

// Define plugin-wide constants
define( 'WPCROSSFIRE_URL', plugin_dir_url(__FILE__) );
define( 'WPCROSSFIRE_PATH', plugin_dir_path(__FILE__) );
define( 'WPCROSSFIRE_BASENAME', plugin_basename(__FILE__) );

define( 'WPCROSSFIRE_VERSION', '0.1' );

require_once('includes/class-admin.php');