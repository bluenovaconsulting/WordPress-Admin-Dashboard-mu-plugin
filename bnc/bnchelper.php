<?php
	class BNCHelper {    
		// returns the url of the plugin's root folder
		public function get_base_url()
		{
			$plugin_folder = WP_PLUGIN_URL . '/BNC/';    
			return $plugin_folder;
		}
		
		 // returns the html string needed for the support email address with proper subject
		public function get_helpdesk_email_link()
		{
			$this_website_is = get_bloginfo('blogname') . " (" . get_bloginfo('url') . ")";
			$link = "<a href='mailto:support@bluenovaconsulting.com?subject=Help with $this_website_is'>here</a>";
			return $link; 
		}
	}
?>
