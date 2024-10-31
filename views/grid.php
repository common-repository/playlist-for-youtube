<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<script>

	jQuery(document).ready(function(){

		jQuery('#playlist_youtube_grid_<?php echo (int)$playlist->id ?> .video .video_container').click(function(){

			//supprime la vidéo déjà en cours de lecture
			jQuery('#playlist_youtube_grid_<?php echo (int)$playlist->id ?> .video .video_container iframe').remove();

			//affiche le lecteur youtube
			jQuery(this).append('<iframe width="100%" height="100%" src="https://www.youtube.com/embed/'+jQuery(this).attr('rel')+'?autoplay=1&rel=0" frameborder="0" allowfullscreen></iframe>');

		});

	})
	

</script>
<div class="playlist_youtube_grid" id="playlist_youtube_grid_<?php echo (int)$playlist->id ?>">
<?php

foreach($data->items as $video)
{
	if($video->snippet->title != 'Private video' && $video->snippet->title != 'Deleted video')
	{
		echo '<div class="video">';
		if($playlist->show_title)
			echo '<h3 style="color: '.esc_attr($playlist->text_color).'; font-size: '.(int)$playlist->text_size.'px;">'.esc_html($video->snippet->title).'</h3>';
		echo '<div class="video_container" rel="'.esc_attr($video->snippet->resourceId->videoId).'">';
		if($video->snippet->thumbnails->standard)
			echo  '<img class="thumbnail" src="'.esc_url($video->snippet->thumbnails->standard->url).'" />';
		else
			echo  '<img class="thumbnail" src="'.esc_url($video->snippet->thumbnails->high->url).'" />';
		if($playlist->show_description == 1)
			echo '<span class="video_description" style="background-color: '.esc_attr($playlist->bg_color).'; color: '.esc_attr($playlist->desc_text_color).'">'.esc_html($video->snippet->description).'</span>';
		echo '<img class="play_video" src="'.esc_url(plugins_url( 'playlist-for-youtube/images/play.png')).'" />';
		echo '</div></div>';
	}
}

?>
</div>