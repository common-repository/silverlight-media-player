<?php
/*
Plugin Name: Silverlight Media Player for WordPress
Plugin URI: http://timheuer.com/silverlight-for-wordpress
Description: A plugin for WordPress to host a Silverlight media player for regular or IIS Smooth Streaming video playback.
Version: 1.1.1
Author: Tim Heuer
Author URI: http://timheuer.com/blog/
*/

//[sl-media: URI, WIDTH, HEIGHT, Smooth streaming (true|false)]
// FULL: [sl-media: MOVIE_URI, WIDTH, HEIGHT, SmoothStreaming (true|false), THUMB, MINVER]

define("SILVERLIGHT_MEDIA_START", "[sl-media:");
define("SILVERLIGHT_MEDIA_END", "]");
define("SILVERLIGHT_MEDIA_TARGET", "<div id=\"silverlightControlHost\"><object data=\"data:application/x-silverlight,\" type=\"application/x-silverlight\" width=\"###WIDTH###\" height=\"###HEIGHT###\"><param name=\"source\" value=\"/wp-content/plugins/silverlight-media-player/###PLAYER_TYPE###\"/><param name=\"background\" value=\"#00000000\" /><param name=\"minRuntimeVersion\" value=\"###MINVER###\" /><param name=\"autoupgrade\" value=\"true\" /><param name=\"enableHtmlAccess\" value=\"true\" />###PARAMHOLDER###<a href=\"http://go.microsoft.com/fwlink/?LinkID=149156\" style=\"text-decoration: none;\"><img src=\"http://storage.timheuer.com/sl4wp-ph.png\" alt=\"Install Microsoft Silverlight\" style=\"border-style: none; width:400px; height:200px\"/></a></object><iframe style=\"visibility:hidden;height:0;width:0;border:0px\" id=\"_sl_historyFrame\"></iframe></div>");
define("SILVERLIGHT_MEDIA_INITPARAMS", "<param name=\"initParams\" value=\"###INITPARAMS###\" />");

function silverlight_media_the_content($content)
{
	$found_pos = strpos($content, SILVERLIGHT_MEDIA_START);
    while ($found_pos !== false) {
        $embedded = substr($content, 0, $found_pos);
        $meta = explode(",", trim(substr($content, $found_pos+strlen(SILVERLIGHT_MEDIA_START), (strpos($content, SILVERLIGHT_MEDIA_END, $found_pos) - ($found_pos+strlen(SILVERLIGHT_MEDIA_START))))));

        $output = $embedded . SILVERLIGHT_MEDIA_TARGET;
        $paramHolder = SILVERLIGHT_MEDIA_INITPARAMS;
        $url = trim($meta[0]);
        $width = trim($meta[1]);
        $height = trim($meta[2]);
        $ss = trim($meta[3]);
        $issmooth = false;
        $thumburl = trim($meta[4]);
        $minver = trim($meta[5]);
        $init_params = "MediaUrl=###URL###,AutoPlay=false";
        
        //if(strpos($url, "http://") === false) $url = get_option('silverlight_standard_location') . $url;
        if(strlen($width) <= 0) $width = get_option('silverlight_media_standard_width');
        if(strlen($height) <= 0) $height = get_option('silverlight_media_standard_height');
        if(strlen($minver) <= 0) $minver = get_option('silverlight_media_standard_version');
        $output = str_replace("###WIDTH###", $width, $output);
        $output = str_replace("###HEIGHT###", $height, $output);
        $output = str_replace("###MINVER###", $minver, $output);
        
        // check if smooth streaming is opted
        if(strlen($ss) > 0) {
            if ($ss == "true") {
                $issmooth = true;
            }
        }
        
        // set the player based on the type
        if ($issmooth) {
            $output = str_replace("###PLAYER_TYPE###", "SmoothStreamingPlayer.xap", $output);
        }
        else {
            $output = str_replace("###PLAYER_TYPE###", "ProgressiveDownloadPlayer.xap", $output);
        }
        
        // set the params
        $full_params = str_replace("###URL###", $url, $init_params);
        
        // check to see if a thumbnail image was added
        if (strlen($thumburl) > 0) {
            $full_params .= ",ThumbnailUrl=" . $thumburl;
        }
        
        $paramHolder = str_replace("###INITPARAMS###", $full_params, $paramHolder);
        $output = str_replace("###PARAMHOLDER###", $paramHolder, $output);
        $end_pos = strpos($content, SILVERLIGHT_MEDIA_END, $found_pos)+1;
        $output .= "<br />" . substr($content, $end_pos);
        $content = $output;
        $found_pos = strpos($content, SILVERLIGHT_MEDIA_START, $end_pos);
    }
    return ($content);
}

function silverlight_media_wp_head()
{
	echo "<style type=\"text/css\">\n<!-- Silverlight Media Player for WordPress Plugin -->\n#silverlightControlHost{height:100%;}\n</style>";
}

add_action('wp_head', 'silverlight_media_wp_head');
add_filter('the_content', 'silverlight_media_the_content');

/* ADMIN */



function silverlight_media_option_page()
{
	$standard_width = 'silverlight_media_standard_width';
	$standard_height = 'silverlight_media_standard_height';
	$minver = 'silverlight_media_standard_version';

	$loc_val = get_option($standard_loc);
	$width_val = get_option($standard_width);
	$height_val = get_option($standard_height);
	$minver_val = get_option($minver);


  if ('insert' == $_POST['action'])
  {
          update_option($standard_loc, $_POST[$standard_loc]);
          update_option($standard_width, $_POST[$standard_width]);
          update_option($standard_height, $_POST[$standard_height]);
          update_option($minver, $_POST[$minver]);

	$loc_val = get_option($standard_loc);
	$width_val = get_option($standard_width);
	$height_val = get_option($standard_height);
	$minver_val = get_option($minver);
?>
<div class="updated"><p><strong><?php _e('Silverlight Media Player for WordPress default settings updated.', 'mt_trans_domain' ); ?></strong></p></div>
<?php
}
?>
<!-- Start Options -->
        <div class="wrap">
          <h2>Silverlight Media Player for WordPress Options</h2>
          <form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
          		<table border="0" cellpadding="0" cellspacing="15"">                    
                    <tr>
                    	<td width="200px"><strong><label>Standard Width</label>: </strong></p></td>
                        <td><input name="<?php echo $standard_width; ?>" value="<?php echo $width_val; ?>" type="text" /></td>
                    </tr>
                    
                    <tr>
                    	<td width="200px"><strong><label>Standard Height</label>: </strong></p></td>
                        <td><input name="<?php echo $standard_height; ?>" value="<?php echo $height_val; ?>" type="text" /></td>
                    </tr>

			<tr>
                    	<td width="200px"><strong><label>Minimum Version (must be at least 4.0.50401.0)</label>: </strong></p></td>
                        <td><input name="<?php echo $minver; ?>" value="<?php echo $minver_val; ?>" type="text" /></td>
                    </tr>
                    
                    <tr>
                    	<td colspan="2"><input name="action" value="insert" type="hidden" /></td>
                    </tr>
                </table>
                <p><div class="submit"><input type="submit" name="Update" value="Update Silverlight Plugin"  style="font-weight:bold;" /></div></p>
          </form>
        </div>

<?php
}

function silverlight_media_admin_menu()
{
	add_option("silverlight_media_standard_width","640");
	add_option("silverlight_media_standard_height","480");
  add_option("silverlight_media_standard_version","4.0.50524.0");
	add_options_page('Silverlight Media Player', 'Silverlight Media Player', 9, __FILE__, 'silverlight_media_option_page'); 
}

add_action('admin_menu', 'silverlight_media_admin_menu');

?>