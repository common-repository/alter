<?php
/*
Plugin Name: Alter
Plugin URI: https://alter.com
Description: Automatically increase visitor engagement and pageviews with AI-powered content recommendations. No humans required.
Author: Alter
Version: 1.0
*/

add_action('admin_menu', 'alter_config_page');
add_action('wp_head', 'alter_js');

register_deactivation_hook(__FILE__, 'alter_deactivate');

function alter_config_page()
{
	if(function_exists('add_submenu_page'))
		add_submenu_page('plugins.php', __('Alter Configuration'), __('Alter Configuration'), 'manage_options', 'alter-config', 'alter_conf');
}

function alter_js()
{
	$tag = get_option('alter_tag');
	
	if(!empty($tag))
		echo $tag;
}

function alter_conf()
{
	$success = '';
	$error = '';
	$tag = get_option('alter_tag');
	
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$_POST['tag'] = stripslashes($_POST['tag']);
		
		if(empty($_POST['tag']) && !empty($tag))
		{
			if(update_option('alter_tag', $_POST['tag']))
				$success = '<div id="message" class="updated notice is-dismissible"><p><strong>Snippet uninstalled.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice</span></button></div>';
		}
		elseif(!empty($_POST['tag']) && $_POST['tag'] == $tag)
		{
			$success = '<div id="message" class="updated notice is-dismissible"><p><strong>Snippet already installed.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice</span></button></div>';
		}
		elseif(!empty($_POST['tag']) && strpos($_POST['tag'], '.alter.com') !== false)
		{
			if(update_option('alter_tag', $_POST['tag']))
				$success = '<div id="message" class="updated notice is-dismissible"><p><strong>Snippet installed. Now sit back, relax, and watch your website\'s engagement rate grow automatically!</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice</span></button></div>';
		}
		else
		{
			$error = '<div class="error"><p><strong>ERROR</strong>: The snippet you entered is incorrect. Please visit your <a href="https://app.alter.com" target="_blank">Alter control panel</a> to find the correct snippet and paste it here.</p></div>';
		}
		
		$tag = get_option('alter_tag');
	}
	
	echo '<div class="wrap"><h2>Alter Configuration</h2>'.$success.$error.'<p>If you don\'t already have an Alter account, you can <a href="https://alter.com" target="_blank">sign up for free</a> to make use of this plugin. Then copy your website\'s code snippet found in your <a href="https://app.alter.com" target="_blank">Alter control panel</a> and paste it here.</p><form method="post" action=""><h4>Code Snippet</h4><input id="tag" name="tag" type="text" size="100" value="'.htmlentities($tag).'" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" /><p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Install Snippet"></p></form></div>';	
}

function alter_deactivate()
{
	delete_option('alter_tag');
}

?>
