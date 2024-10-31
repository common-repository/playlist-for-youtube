<?php

/*
Plugin Name: Playlist for Youtube - Free version
Plugin URI: 
Version: 1.41
Description: Display playlists Youtube in page
Author: InfoD74
Author URI: https://www.info-d-74.com/en/shop/
Network: false
Text Domain: playlist-for-youtube
Domain Path: 
*/


register_activation_hook( __FILE__, 'playlist_yt_free_install' );
register_uninstall_hook(__FILE__, 'playlist_yt_free_desinstall');

function playlist_yt_free_install() {

	global $wpdb;

	$playlist_yt_table = $wpdb->prefix . "playlists_yt";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$sql = "
        CREATE TABLE `".$playlist_yt_table."` (
          id int(11) NOT NULL AUTO_INCREMENT,
          name varchar(50) NOT NULL,          
          playlist_id varchar(50) NOT NULL,
          show_title int(1) NOT NULL,
          show_description int(1) NOT NULL,
          template int(11) NOT NULL,
          text_size int(3) NOT NULL,
          text_color varchar(20) NOT NULL,
          desc_text_color varchar(20) NOT NULL,
          bg_color varchar(20) NOT NULL,
          PRIMARY KEY  (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
    ";

    dbDelta($sql);

}

function playlist_yt_free_desinstall() {

	global $wpdb;

	$wpdb->query("DROP TABLE ".$wpdb->prefix."playlists_yt");
}

add_action( 'admin_menu', 'register_playlist_yt_free_menu' );

function register_playlist_yt_free_menu() {

	add_menu_page('Playlists Youtube', 'Playlists Youtube free', 'edit_pages', 'playlists_yt_free', 'playlists_yt_free', plugins_url('images/icon.png', __FILE__), 32);
	add_submenu_page( 'playlists_yt_free', 'Settings for Playlist Youtube', 'Settings', 'manage_options', 'playlist-yt-settings', 'playlist_yt_free_settings' );
	//call register settings function
	add_action( 'admin_init', 'register_playlist_yt_free_settings' );

}

function register_playlist_yt_free_settings() {
	//register our settings
	register_setting( 'playlist-yt-settings-group', 'pl_yt_api_key' );
}

add_action('admin_print_styles', 'playlist_yt_free_css' );
function playlist_yt_free_css() {
    wp_enqueue_style( 'PlaylistYtFreeStylesheet', plugins_url('css/admin.css', __FILE__) );
}


function playlist_yt_free_settings(){

	include(plugin_dir_path( __FILE__ ) . 'views/settings.php');

}

function playlists_yt_free() {

	if (is_admin()) {

		global $wpdb;

		$playlist_yt_table = $wpdb->prefix . "playlists_yt";

		//check_admin_referer( 'new_pl_yt' );

		if(sizeof($_POST) > 0)
		{
			if(empty($_POST['name']) || empty($_POST['playlist_id']))
				echo '<h2>You must enter a name and the ID of the playlist !</h2>';
			else if(!is_numeric($_POST['id'])) //nouvelle playlist
			{
				check_admin_referer('new_pl_yt');
				
				$show_title = 1;
				$show_description = 1;
				$desc_text_color = "#ffffff";
				$bg_color = "rgba(0, 0, 0, 0.7)";

				$wpdb->query($wpdb->prepare( "INSERT INTO ".$wpdb->prefix . "playlists_yt (`name`, `playlist_id`, `template`, `text_size`, `text_color`, `desc_text_color`, `bg_color`, `show_title`, `show_description`) VALUES (%s, %s, %d, %d, %s, %s, %s, %d, %d)", stripslashes_deep($_POST['name']), $_POST['playlist_id'], $_POST['template'], $_POST['text_size'], $_POST['text_color'], $desc_text_color, $bg_color, $show_title, $show_description));
			}
			else //mise à jour d'une playlist
			{
				check_admin_referer( 'update_pl_yt_'.$_POST['id'] );
				$show_title = isset($_POST['show_title']) ? 1 : 0;
				$show_description = isset($_POST['show_desc']) ? 1 : 0;
				/*echo $wpdb->prepare( "UPDATE ".$wpdb->prefix . "playlists_yt SET `name` = %s, `playlist_id` = %s, `template` = %d, `text_size` = %d, `text_color` = %s WHERE id = %d",
				stripslashes_deep($_POST['name']), $_POST['playlist_id'], $_POST['template'], $_POST['text_size'], $_POST['text_color'], $_POST['id'] );*/
				$wpdb->query($wpdb->prepare( "UPDATE ".$wpdb->prefix . "playlists_yt SET `name` = %s, `playlist_id` = %s, `template` = %d, `text_size` = %d, `text_color` = %s WHERE id = %d",
				stripslashes_deep($_POST['name']), $_POST['playlist_id'], $_POST['template'], $_POST['text_size'], $_POST['text_color'], $_POST['id'] ));
			}
		}
			
		//on récupère toutes les cards
		$playlists = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "playlists_yt");
		include(plugin_dir_path( __FILE__ ) . 'views/playlists.php');
	}
}

//Ajax : suppression d'une playlist
add_action( 'wp_ajax_remove_pl_yt', 'remove_pl_yt_free_callback' );

function remove_pl_yt_free_callback() {

	check_ajax_referer( 'remove_pl_yt' );

	if (is_admin()) {

		global $wpdb;

		$playlist_yt_table = $wpdb->prefix . "playlists_yt";

		if(is_numeric($_POST['id']))
		{
			//supprime toutes les images
			$res = $wpdb->query($wpdb->prepare( 
				"DELETE FROM ".$wpdb->prefix . "playlists_yt
				 WHERE id=%d", $_POST['id']
			));			
		}
		wp_die();
	}
}

add_shortcode('playlist_yt', 'display_playlist_yt_free');
function display_playlist_yt_free($atts) {

	$api_key = get_option('pl_yt_api_key');

	if(empty($api_key))
		return '<strong>You need to set your API key in the <a href="'.admin_url( 'admin.php?page=playlist-yt-settings') .'">settings</a> to use Playlist Youtube</strong>';

	if(is_numeric($atts['id']))
	{

		global $wpdb;

		$playlist_yt_table = $wpdb->prefix . "playlists_yt";
		$query = "SELECT * FROM ".$wpdb->prefix . "playlists_yt WHERE id = %d";
		$playlist = $wpdb->get_row($wpdb->prepare( "SELECT * FROM ".$wpdb->prefix . "playlists_yt WHERE id = %d", $atts['id'] ));

		if($playlist)
		{
				//on inclut jquery
				wp_enqueue_script( 'jquery' );			

				$url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&key=".get_option('pl_yt_api_key')."&playlistId=".$playlist->playlist_id;
				$response = wp_remote_get( $url );
				$json = wp_remote_retrieve_body( $response );

				if(!empty($json))
				{
					$data = json_decode($json);

					if($data)
					{

						if(!isset($data->error))
						{
							wp_enqueue_style( 'playlist_youtube_grid_css', plugins_url( 'css/grid.css', __FILE__ ));
							$view = plugin_dir_path( __FILE__ ) . 'views/grid.php';
							
							ob_start();
							include( $view );
							$playlist_html = ob_get_clean();

							return $playlist_html;
						}
						else
						{
							$error_msg = "<strong>Error(s) Youtube API : </strong><br />";
							foreach($data->error->errors as $error)
							{
								$error_msg .= $error->message.'<br />';
							}

							return $error_msg;
						}

					}
					else
						return 'Error convert JSON data';
				}
				else
					return 'Error retrieve playlist from Youtube API';
		}
		else
			return "Error : playlist id ".$atts['id'].' not found !';

	}
	else
		return 'Wrong ID format !';

}

add_action( 'wp_enqueue_scripts', function() {	wp_enqueue_script( 'jquery' ); });	