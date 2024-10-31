<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<script>

		jQuery(document).ready(function(){

			jQuery('#playlists_yt .remove').click(function(){
				var pl = jQuery(this).parent('form').parent('.playlist_yt');
				jQuery.post(ajaxurl, {action: 'remove_pl_yt', id: jQuery(this).attr('rel'), _ajax_nonce: '<?php echo esc_attr(wp_create_nonce( "remove_pl_yt" )); ?>' }, function(){
					jQuery(pl).remove();
				});
			});

		});

</script>

<h2>All Playlists Youtube</h2>
<a href="https://support.google.com/youtube/answer/57792" target="_blank">How to create a Youtube playlist?</a>
<form action="" method="post" id="form_new_pl_yt">
<?php wp_nonce_field( 'new_pl_yt' ) ?>
<b>Add a new playlist</b><br />
	<label>Name : </label><input type="text" name="name" /><br />
	<label>Playlist ID : </label><input type="text" name="playlist_id" /> You find it in the URL of the playlist: https://www.youtube.com/playlist?list=<strong style="color: #00fc00">PLw58WHQXYOB-2Nkw-CLEnzz1rW1rb8ZDJ</strong><br />
	<input type="hidden" name="template" value="1">
	<label>Title video size : </label><input type="text" name="text_size" />px<br />
	<label>Title video color : </label><input type="color" name="text_color" /><br />
	<input type="submit" value="Add" class="button button-primary" />
</form>

<div id="playlists_yt">
<?php

if(sizeof($playlists) > 0)
{
	foreach($playlists as $playlist)
	{
		echo '<div class="playlist_yt"><h3>'.esc_html($playlist->name).'</h3>';
		echo '<form action="" method="post">';
		wp_nonce_field( 'update_pl_yt_'.(int)$playlist->id );
		echo '<input type="hidden" name="id" value="'.(int)$playlist->id.'" />';
		echo '<label>Name : </label><input type="text" name="name" value="'.esc_html($playlist->name).'" /><br />';
		echo '<label>Playlist ID : </label>';
		echo '<input type="text" name="playlist_id" value="'.esc_attr($playlist->playlist_id).'" /><br />';
		echo '<input type="hidden" name="template" value="1">';
		echo '<label>Title video size : </label><input type="text" name="text_size" value="'.(int)$playlist->text_size.'" />px<br />
		<label>Title video color : </label><input type="color" name="text_color" value="'.esc_attr($playlist->text_color).'" /><br />';
		echo '<input type="image" src="'.esc_url(plugins_url( 'playlist-for-youtube/images/save.png')).'" title"Save" /> <img title="Remove this playlist" class="remove action" rel="'.(int)$playlist->id.'" src="'.esc_url(plugins_url( 'playlist-for-youtube/images/remove.png' )).'" />
	Shortcode : <input type="text" value="[playlist_yt id='.(int)$playlist->id.']" onClick="this.select();" />
	</form></div>';
	}
}
else
	echo 'No playlist found !';

?>

</div>

<div id="yt_pl_info">

	<h3>Need more options ? Look at <a href="https://www.info-d-74.com/en/produit/playlist-youtube-pro-plugin-wordpress/" target="_blank">Playlists Youtube Pro</a> <a href="https://www.facebook.com/infod74/" target="_blank"><img src="<?php echo esc_url(plugins_url( 'images/fb.png', dirname(__FILE__))) ?>" alt="" /></a></h3>

	<a href="http://www.info-d-74.com/produit/playlists-youtube-plugin-wordpress/" target="_blank"><img src="<?php echo esc_url(plugins_url( 'playlist-for-youtube/images/pro.jpg' )) ?>" /></a>

</div>