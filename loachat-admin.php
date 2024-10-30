<?php

global $g_LOACHAT__plugin_directory_url;
$g_LOACHAT__plugin_directory_url = get_bloginfo ('wpurl') . preg_replace ('#^.*[/\\\\](.*?)[/\\\\].*?$#', "/wp-content/plugins/$1", __FILE__);

global $g_LOACHAT__config_defaults;
$g_LOACHAT__config_defaults = array (
// ------- General Settings
   'loachat_chat_window_width'   => 478,
   'loachat_chat_window_height'  => 500,
   );

//===========================================================================
function LOACHAT__get_settings ()
{
   $loachat_settings = get_option ('Loa-Chat');

   return ($loachat_settings);
}
//===========================================================================

//===========================================================================
function LOACHAT__update_settings ()
{
   global   $g_LOACHAT__config_defaults;

   // Load current settings and overwrite them with whatever values are present on submitted form
   $loachat_settings = LOACHAT__get_settings();

   foreach ($g_LOACHAT__config_defaults as $k=>$v)
      {
      if (isset($_POST[$k]))
         $loachat_settings[$k] = stripslashes($_POST[$k]);
      // If not in POST - existing will be used.
      }

   update_option ('Loa-Chat', $loachat_settings);
}
//===========================================================================

//===========================================================================
function LOACHAT__reset_settings ()
{
   global   $g_LOACHAT__config_defaults;

   update_option ('Loa-Chat', $g_LOACHAT__config_defaults);
}
//===========================================================================

//===========================================================================
function LOACHAT__regenerate_api_key ()
{
   $loachat_settings = LOACHAT__get_settings();
   $loachat_settings['loachat_api_key'] = substr(md5(microtime()), 0, 16);
   update_option ('Loa-Chat', $loachat_settings);
}
//===========================================================================

//===========================================================================
function LOACHAT__render_admin_html()
{
   $loachat_options = LOACHAT__get_settings ();

?>
<div align="center" style="font-family: Georgia, 'Times New Roman', Times, serif;font-size:18px;margin:30px 0 30px;background-color:#b8d6fb;padding:14px;border:1px solid gray;">
   Law of Attraction Chat plugin<br />
   <div style="color:#A00;font-size:130%;margin-top:10px;">Settings table</div>
</div>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
   <table style="background-color:#555;" width="98%" border="1">
     <tr>
       <td style="background-color:#B5FFA8" width="30%"><div align="center" style="padding:5px 3px;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 18px;">Name</div></td>
       <td style="background-color:#B5FFA8" width="40%"><div align="center" style="padding:5px 3px;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 18px;">Value</div></td>
       <td style="background-color:#B5FFA8" width="30%"><div align="center" style="padding:5px 3px;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 18px;">Notes</div></td>
     </tr>
     <tr>
       <td style="background-color:white;"><div align="left" style="padding-left:5px;">Default dimensions of chat window:</div></td>
       <td style="background-color:#CCC;"><div align="center">(width) <input type="text" name="loachat_chat_window_width" value="<?php echo $loachat_options['loachat_chat_window_width']; ?>" size="5" /> x <input type="text" name="loachat_chat_window_height" value="<?php echo $loachat_options['loachat_chat_window_height']; ?>" size="5" /> (height)<br />(in pixels)</div></td>
       <td style="background-color:white;"><div align="left" style="padding:5px;font-size:85%;">In pixels. These settings apply to <b>[loachat]</b> marker. You may still create custom sized chat windows by using markers such as: <b>[loachat 550x740]</b> (width x height)</div></td>
     </tr>
     <tr>
         <td colspan="3"><div align="center" style="padding:10px 0;">
           <input type="submit" name="button_update_loachat_settings" value="Save Settings" />
         </div></td>
     </tr>
   </table>
</form>
<?php
}
//===========================================================================


?>