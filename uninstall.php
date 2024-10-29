<?php
/**
 * Uninstall procedure for the plugin.
 */

if(!defined('WP_UNINSTALL_PLUGIN'))
	exit();

delete_option('alter_tag');

?>