<?php
/*
	Plugin Name: BNC
	Plugin URI: https://github.com/bluenovaconsulting/wordpress
	Version: 0.1
	Author: <a href="http://www.BlueNovaConsulting.com">BlueNovaConsulting.com</a>
	Description: BNC Plugin for WordPress
*/

	require_once('bnc/bnchelper.php'); 

	// protect admin user from being deleted
	add_action('admin_notices', 'delete_user_response');
	add_action('delete_user', 'delete_user_filter');
	function delete_user_filter($id)
	{
		$user_info = get_userdata($id);
		if( 'bluenova' == $user_info->user_login )
		{
			wp_redirect(admin_url() . 'users.php?msg=support-admin');
			exit;
		}
	}
	function delete_user_response()
	{
		if( $_REQUEST['msg'] && 'support-admin' == $_REQUEST['msg'] )
		{
			echo '<div class="error"><p>The BlueNova user is required for support and cannot be deleted.</p></div>';
		}
	}
	
	// Replace Admin Dashboard Logo
	function custom_admin_logo()
	{
		echo "<style type='text/css'>#header-logo { background: transparent url(" . get_bloginfo('template_directory') . "/images/wordpress-admin-dashboard-logo.png) !important; }</style>";
	}
	add_action('admin_head', 'custom_admin_logo');
		
	// remove_extraneous_dashboard_widgets
	function remove_dashboard_widgets() 
	{
		global $wp_meta_boxes;
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
		// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	}	
	add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );	
		
	// create bnc dashboard widget
	function bnc_dashboard_widget()
	{
		global $current_user; 
		get_currentuserinfo();
		echo "<a href='http://www.bluenovaconsulting.com' target='_blank' title='BlueNovaConsulting.com'><img src='" . get_bloginfo('template_directory') . "/images/logo_250x50.png'/></a>";
		echo "<br /><span style='margin-left: 5px;font-weight: bold;'>If you need help you can email the developer " .  BNCHelper::get_helpdesk_email_link() . ".</span>";
		echo "<br /><span style='margin-left: 5px;font-weight: bold;'><a href='http://www.bluenovaconsulting.com' target='_blank' title='BlueNovaConsulting.com'>www.BlueNovaConsulting.com</a></span><br />"; 
		
		echo '<br/><strong>Your user information:</strong>';
		echo '<br />Username: ' . $current_user->user_login;
		echo '<br />User email: ' . $current_user->user_email;
		echo '<br />User first name: ' . $current_user->user_firstname;
		echo '<br />User last name: ' . $current_user->user_lastname;
		echo '<br />User display name: ' . $current_user->display_name;
		echo '<br />User ID: ' . $current_user->ID;
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
		echo '<br />User Role: ' . $user_role;
	}
	// add bnc dashboard widget
	function my_dashboard_setup_function() 
	{
		add_meta_box( 'bnc_dashboard_widget', 'BlueNova Support', 'bnc_dashboard_widget', 'dashboard', 'side', 'high' );
	}
	add_action( 'wp_dashboard_setup', 'my_dashboard_setup_function' );
		
	// function to display member content widget
	function member_content_dashboard_widget() 
	{
		global $current_user; 
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);

		if( 'administrator' == $user_role )
		{
			//define arguments for WP_Query()
			$qargs = array(
				'post_type'=>'page',
				'post_status'=>'private'
			);
			// perform the query
			$q = new WP_Query();
			$q->query($qargs);

			// setup the content with a list
			$widget_content = '<ul>';
			// execute the WP loop
			while ($q->have_posts()) : $q->the_post(); 
			$widget_content .= '<li><a href="'.get_permalink() .'" rel="bookmark">'. get_the_title() .'</a></li>';
			endwhile;
			$widget_content .= '</ul>';
			// return the content you want displayed
			echo $widget_content;
		}
	}
	// add member content dashboard widget
	function member_content_function() 
	{
		add_meta_box( 'member_content_dashboard_widget', 'Member Content', 'member_content_dashboard_widget', 'dashboard', 'side', 'high' );
	}
	add_action( 'wp_dashboard_setup', 'member_content_function' );
	
	// remove version info from head and feeds
	function complete_version_removal() {
		return '';
	}
	add_filter('the_generator', 'complete_version_removal');

