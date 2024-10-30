<?php
/*
Plugin Name: Law of Attraction Chat
Plugin URI: http://www.loachat.com/widget
Version: 1.01
Author: Gleb Esman, http://www.loachat.com/
Author URI: http://www.loachat.com/
Description: Instantly create live chat room dedicated to The Law of Attraction subjects on any page of your site. Engage your visitors, let them connect with other Law of Attraction experts, practitioners and fans. To create chat room - just insert one of these markers: <code>[loachat]</code> or <code>[loachat 500x600]</code> inside the text of any article or page. Great website enhancement tool for self-help and self-improvement portals.
*/

require_once (dirname(__FILE__) . '/loachat-admin.php');

//---------------------------------------------------------------------------
// Insert hooks
register_activation_hook   (__FILE__,                    'LOACHAT__activated');
add_action                 ('init',                      'LOACHAT__init');
add_action                 ('admin_menu',                'LOACHAT__admin_menu',   11);
add_filter                 ('the_content',               'LOACHAT__the_content',  222);
//---------------------------------------------------------------------------

//===========================================================================
function LOACHAT__the_content ($content)
{
$loachat_code =<<<CCCC
<iframe frameborder="0" style="width: {FRAME_WIDTH}px; height: {FRAME_HEIGHT}px; border: 2px solid gray;" border="0" src="http://www.loachat.com/"></iframe>
CCCC;

   $loachat_settings = LOACHAT__get_settings();

   // Replace simple marker: [loachat]
   $loachat_new_code = str_replace ('{FRAME_WIDTH}',  $loachat_settings['loachat_chat_window_width'],  $loachat_code);
   $loachat_new_code = str_replace ('{FRAME_HEIGHT}', $loachat_settings['loachat_chat_window_height'], $loachat_new_code);
   $content = str_replace ('[loachat]', $loachat_new_code, $content);

   // Replace more complex markers: [loachat 600x700]
   if (preg_match ('@\[loachat\s+(\d+)x(\d+)@', $content, $matches))
      {
      $loachat_new_code = str_replace ('{FRAME_WIDTH}',   $matches[1],  $loachat_code);
      $loachat_new_code = str_replace ('{FRAME_HEIGHT}',  $matches[2],  $loachat_new_code);
      $content = preg_replace ('|\[loachat\s+.*?\]|', $loachat_new_code, $content);
      }

   return ($content);
}
//===========================================================================

//===========================================================================
function LOACHAT__init ()
{
   // Process user's cookie if necessary.
}
//===========================================================================

//===========================================================================
// Initial activation code here such as: DB tables creation, storing initial settings.

function LOACHAT__activated ()
{
   global   $g_LOACHAT__config_defaults;
   // Initial set/update default options

   $loachat_default_options = $g_LOACHAT__config_defaults;

   // This will overwrite default options with already existing options but leave new options (in case of upgrading to new version) untouched.
   $loachat_settings = LOACHAT__get_settings ();
   if (is_array ($loachat_settings))
      {
      foreach ($loachat_settings as $key=>$value)
         $loachat_default_options[$key] = $value;
      }

    update_option ('Loa-Chat', $loachat_default_options);
}
//===========================================================================

//===========================================================================
function LOACHAT__admin_menu ()
{
  add_options_page('Law of Attraction chat', 'Law of Attraction chat', 8, 'Law of Attraction chat', 'LOACHAT__options_page');
}

// Do admin panel business, assemble and output admin page HTML
function LOACHAT__options_page ()
{
   if (isset ($_POST['button_update_loachat_settings']))
      {
      LOACHAT__update_settings ();
echo <<<HHHH
<div align="center" style="background-color:#FFA;padding:5px;font-size:120%;border: 2px solid gray;margin:5px;">
Settings updated!
</div>
HHHH;
      }
   else if (isset($_POST['button_regenerate_api_key']))
      {
      LOACHAT__regenerate_api_key ();
echo <<<HHHH
<div align="center" style="background-color:#FFA;padding:5px;font-size:120%;border: 2px solid gray;margin:5px;">
API key Regenerated
</div>
HHHH;
      }
   else if (isset($_POST['button_reset_loachat_settings']))
      {
      LOACHAT__reset_settings ();
echo <<<HHHH
<div align="center" style="background-color:#FFA;padding:5px;font-size:120%;border: 2px solid gray;margin:5px;">
Settings reverted to all defaults
</div>
HHHH;
      }

   // Output full admin settings HTML
   LOACHAT__render_admin_html ();
}
//===========================================================================

?>