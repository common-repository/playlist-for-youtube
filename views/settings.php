<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="wrap">
<h2>Playlists Youtube settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'playlist-yt-settings-group' ); ?>
    <?php do_settings_sections( 'playlist-yt-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">API key</th>
            <td><input type="text" name="pl_yt_api_key" value="<?php echo esc_attr( get_option('pl_yt_api_key') ); ?>" />
            <p>Get it on Google developer console : <a href="https://console.developers.google.com/home" target="_blank">https://console.developers.google.com/home</a><br />You can look at this <a href="https://www.youtube.com/watch?v=Im69kzhpR3I" target="_blank">helping video</a></p></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>