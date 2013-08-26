<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2009 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: mp3player_admin.php
| CVS Version: 2.1
| Author: Arda Kýlýçdaðý (SoulSmasher)
| Web: http://www.soulsmasher.net, www.soulsmasher.com
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

require "../../maincore.php";

if (!checkrights("MP") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../../index.php"); }

if (file_exists(INFUSIONS."mp3player_panel/locale/".$settings['locale'].".php")) {
	include_once INFUSIONS."mp3player_panel/locale/".$settings['locale'].".php";
} else {
	include_once INFUSIONS."mp3player_panel/locale/English.php";
}
require_once THEMES."templates/admin_header.php";

include INFUSIONS."mp3player_panel/infusion_db.php";
include INFUSIONS."mp3player_panel/functions.php";



$mp3settings=dbarray(dbquery("SELECT * FROM ".DB_MP3PLAYERPANEL));

add_to_head('<link rel="stylesheet" href="'.INFUSIONS.'mp3player_panel/includes/uploadify_162/uploadify.css" type="text/css" /><script type="text/javascript" src="'.INFUSIONS.'mp3player_panel/includes/uploadify_162/jquery.uploadify.js"></script>
<script type="text/javascript" src="'.INFUSIONS.'mp3player_panel/includes/iColorPicker-noLink.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$(\'#hide\').hide();
$(\'#hide2\').hide();
$(\'#fileupload\').fileUpload({
\'uploader\': \''.INFUSIONS.'mp3player_panel/includes/uploadify_162/uploader.swf\',
\'script\': \''.INFUSIONS.'mp3player_panel/upload.php\',
\'folder\': \''.INFUSIONS.'mp3player_panel/songs\',
\'multi\': true,
\'auto\': true,
\'sizeLimit\': \''.$mp3settings['maxfilesize'].'\',
\'fileDesc\': \'MP3\',
\'fileExt\': \'*.mp3\',
\'buttonText\': \''.$locale['mp3player_23'].'\',
\'cancelImg\': \''.INFUSIONS.'mp3player_panel/includes/uploadify_162/cancel.png\'
});
});
</script>
<script type="text/javascript">
$(document).ready(function(){
$(\'#backcolor\').attr(\'readonly\',\'readonly\');
$(\'#frontcolor\').attr(\'readonly\',\'readonly\');
$(\'#lightcolor\').attr(\'readonly\',\'readonly\');
});
</script>'); 

//error messages
if (isset($_GET['error'])) {
$error=$_GET['error'];
opentable($locale['mp3player_02']);
if ($error=="filewrite") {
echo $locale['mp3player_03'];
} else if ($error=="fileistoobig") {//
echo sprintf($locale['mp3player_04'],floor($mp3settings['maxfilesize']/1048576));
} else if ($error=="fileisnotmp3") { //
echo $locale['mp3player_05'];
} else if ($error=="fileisempty") { //
echo $locale['mp3player_06'];
} else if ($error=="couldntdelete") {
echo $locale['mp3player_07'];
} else if ($error=="settingsnotsaved") {
echo $locale['mp3player_08'];
} else if ($error=="remotecouldntadd") {
echo $locale['mp3player_09'];
} else if ($error=="remotecouldntedit") {
echo $locale['mp3player_10'];
} else if ($error=="remotecouldntdelete") {
echo $locale['mp3player_11'];
} else {
redirect(FUSION_SELF.$aidlink);
}
closetable();
}

//messages after successful processes
if (isset($_GET['process'])) {
opentable($locale['mp3player_60']);
$process=$_GET['process'];
if ($process=="deleted") {
echo $locale['mp3player_61'];
} else if ($process=="uploaded") {
echo $locale['mp3player_62'];
} else if ($process=="settingssaved") {
echo $locale['mp3player_63'];
} else if ($process=="remoteadded") {
echo $locale['mp3player_64'];
} else if ($process=="remoteedited") {
echo $locale['mp3player_65'];
} else if ($process=="remotedeleted") {
echo $locale['mp3player_66'];
} else {
redirect(FUSION_SELF.$aidlink);
}
closetable();
}


$remote_mp3_address="http://"; //first value for empty textarea
$remoteedit=false; //default as false
$remoteformaction="remotemp3add";//default remote form action

//if mp3 is uploaded via old method, (if javascript is closed old html form appears)
if(isset($_FILES['file'])) {
	if(!empty($_FILES['file']['name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
		if ($_FILES['file']['type'] == "audio/mpeg" || strtolower(substr($_FILES['file']['name'],-4))==".mp3") {
			if ($_FILES['file']['size'] > $settings['maxfilesize']) {
				$filename = validate_filename_old($_FILES['file']['name']);
				$newfile =  INFUSIONS."mp3player_panel/songs/".$filename;
				if(move_uploaded_file($_FILES['file']['tmp_name'],$newfile)) {
					chmod($newfile,0644);
					redirect(FUSION_SELF.$aidlink."&amp;process=uploaded");
				} else {
				redirect(FUSION_SELF.$aidlink."&amp;error=filewrite");
				}
			} else {
			redirect(FUSION_SELF.$aidlink."&amp;error=fileistoobig");
			}
		} else {
		redirect(FUSION_SELF.$aidlink."&amp;error=fileisnotmp3");
		}
	} else {
	redirect(FUSION_SELF.$aidlink."&amp;error=fileisempty");
	}
}


//remote actions
if(isset($_GET['action'])) {
$action=$_GET['action'];

//local mp3 delete
if ($action=="localmp3delete" && isset($_GET['name']) && strtolower(substr(urldecode($_GET['name']),-4))==".mp3") {
if (unlink(INFUSIONS."mp3player_panel/songs/".urldecode($_GET['name']))) {
redirect(FUSION_SELF.$aidlink."&amp;process=deleted");
} else {
redirect(FUSION_SELF.$aidlink."&amp;error=couldntdelete");
	}
}

//remote mp3 edit
if ($action=="remotemp3edit" && isset($_GET['id']) && isnum($_GET['id'])) {
$remoteedit=true;
$result=dbquery("SELECT * FROM ".DB_MP3PLAYERREMOTE." WHERE remote_mp3_id='".$_GET['id']."'");
if (dbrows($result)) {
$remotedata=dbarray($result);
$remote_mp3_address=$remotedata['remote_mp3_url']; //if it's edited, it'll replace the code
$remoteformaction="remotemp3edit";//remote form action changed
	}
}

if ($action=="remotemp3delete" && isset($_GET['id']) && isnum($_GET['id'])) {
$result=dbquery("DELETE FROM ".DB_MP3PLAYERREMOTE." WHERE remote_mp3_id='".$_GET['id']."'");
redirect(FUSION_SELF.$aidlink."&amp;".($result?"process=remotedeleted":"error=remotecouldntdelete"));
}

}//action son

//remote mp3 add
if(isset($_POST['mp3add'])) {
$remote_mp3_url=stripinput($_POST['remote_mp3_url']);
$remote_mp3_name=!empty($_POST['remote_mp3_name'])?stripinput($_POST['remote_mp3_name']):"mp3".time();
$result=dbquery("INSERT INTO ".DB_MP3PLAYERREMOTE." (remote_mp3_url, remote_mp3_name) VALUES ('".$remote_mp3_url."', '".$remote_mp3_name."')");
redirect(FUSION_SELF.$aidlink."&amp;".($result?"process=remoteadded":"error=remotecouldntadd"));
}

//remote mp3 edit
if(isset($_POST['mp3edit'])) {
$remote_mp3_id=isnum($_POST['remote_mp3_id'])?$_POST['remote_mp3_id']:"";
$remote_mp3_url=stripinput($_POST['remote_mp3_url']);
$remote_mp3_name=!empty($_POST['remote_mp3_name'])?stripinput($_POST['remote_mp3_name']):"mp3".time();
$result=dbquery("UPDATE ".DB_MP3PLAYERREMOTE." SET remote_mp3_url='".$remote_mp3_url."', remote_mp3_name='".$remote_mp3_name."' WHERE remote_mp3_id='".$remote_mp3_id."'");
redirect(FUSION_SELF.$aidlink."&amp;".($result?"process=remoteedited":"error=remotecouldntedit"));
}

//settings
if (isset($_POST['settings'])) {
$width=isnum($_POST['width'])?$_POST['width']:$mp3settings['width'];
$height=isnum($_POST['height'])?$_POST['height']:$mp3settings['height'];
$maxfilesize=isnum($_POST['maxfilesize'])?$_POST['maxfilesize']:$mp3settings['maxfilesize'];
$backcolor=preg_match("/^[0-9A-F]{6}$/i", $_POST['backcolor'])?$_POST['backcolor']:$mp3settings['backcolor']; //for extra security - ekstra güvenlik için
$frontcolor=preg_match("/^[0-9A-F]{6}$/i", $_POST['frontcolor'])?$_POST['frontcolor']:$mp3settings['frontcolor']; //for extra security - ekstra güvenlik için
$lightcolor=preg_match("/^[0-9A-F]{6}$/i", $_POST['lightcolor'])?$_POST['lightcolor']:$mp3settings['lightcolor']; //for extra security - ekstra güvenlik için
$player_mode=isnum($_POST['player_mode'])?$_POST['player_mode']:1;
$player_shuffle=isnum($_POST['player_shuffle'])?$_POST['player_shuffle']:0;
$player_behaviour=isnum($_POST['player_behaviour'])?$_POST['player_behaviour']:0;
$visualization_enabled=isnum($_POST['visualization_enabled'])?$_POST['visualization_enabled']:$mp3settings['visualization_enabled'];
$visualization_height=isnum($_POST['visualization_height'])?$_POST['visualization_height']:$mp3settings['visualization_height'];
$skin=stripinput($_POST['skin']);
$result=dbquery("UPDATE ".DB_MP3PLAYERPANEL." SET backcolor='".$backcolor."', frontcolor='".$frontcolor."', lightcolor='".$lightcolor."', width='".$width."', height='".$height."', maxfilesize='".$maxfilesize."', player_mode='".$player_mode."', player_shuffle='".$player_shuffle."', player_behaviour='".$player_behaviour."', visualization_enabled='".$visualization_enabled."', visualization_height='".$visualization_height."', skin='".$skin."'");
redirect(FUSION_SELF.$aidlink."&amp;".($result?"process=settingssaved":"error=settingsnotsaved"));
}


echo '<div id="hide" style="color: red; font-size: 1.4em">'.$locale['mp3player_21'].'</div>';

//file upload with/without jquery
echo '<form action="'.FUSION_SELF.$aidlink.'&amp;islem=upload" method="post" enctype="multipart/form-data">';
echo '<div style="float: right; width: 200px;"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="6410325">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
<br />'.$locale['mp3player_999'].'</div>';
echo $locale['mp3player_22'].'<br /><input type="file" name="file" id="fileupload" size="40" class="button" /><br />
<div id="hide2"><input type="submit" name="mp3upload" value="'.$locale['mp3player_23'].'" class="button" /></div>'; //if javascript is off, we need old style upload
echo '</form>
<hr />
<form method="post" action="'.FUSION_SELF.$aidlink.'&amp;islem=ayarlar">
  <div><label for="backcolor">'.$locale['mp3player_30'].':</label><input type="text" id="backcolor" name="backcolor" class="button iColorPicker" style="background: #'.$mp3settings['backcolor'].'; color: #cccccc; font-weight: bold;" value="'.$mp3settings['backcolor'].'" /></div>
  <div class="form-item"><label for="frontcolor">'.$locale['mp3player_31'].':</label><input type="text" id="frontcolor" name="frontcolor" class="button iColorPicker" style="background: #'.$mp3settings['frontcolor'].'; color: #cccccc; font-weight: bold;" value="'.$mp3settings['frontcolor'].'" /></div>
  <div class="form-item"><label for="lightcolor">'.$locale['mp3player_32'].':</label><input type="text" id="lightcolor" name="lightcolor" class="button iColorPicker" style="background: #'.$mp3settings['lightcolor'].'; color: #cccccc; font-weight: bold;" value="'.$mp3settings['lightcolor'].'" /></div>
  <div class="form-item">'.$locale['mp3player_33'].': <input type="text" name="width" class="textbox" value="'.$mp3settings['width'].'" style="width: 70px;" /></div>
  <div class="form-item">'.$locale['mp3player_34'].': <input type="text" name="height" class="textbox" value="'.$mp3settings['height'].'" style="width: 70px;" /></div>
  <div class="form-item">'.$locale['mp3player_35'].': <input type="text" name="maxfilesize" class="textbox" value="'.$mp3settings['maxfilesize'].'" style="width: 70px;" /></div>
  <div class="form-item">'.$locale['mp3player_36'].': <select name="player_mode" class="textbox">\n';
	echo "<option value='1'".($mp3settings['player_mode'] == 1 ? " selected='selected'" : "").">".$locale['mp3player_37']."</option>\n";
	echo "<option value='0'".($mp3settings['player_mode'] == 0 ? " selected='selected'" : "").">".$locale['mp3player_38']."</option>\n";
	echo "</select></div>\n";
  
	echo '<div class="form-item">'.$locale['mp3player_39'].': <select name="player_shuffle" class="textbox">\n';
	echo "<option value='1'".($mp3settings['player_shuffle'] == 1 ? " selected='selected'" : "").">".$locale['mp3player_40']."</option>\n";
	echo "<option value='0'".($mp3settings['player_shuffle'] == 0 ? " selected='selected'" : "").">".$locale['mp3player_41']."</option>\n";
	echo "</select></div>\n";
  
	echo '<div class="form-item">'.$locale['mp3player_42'].': <select name="player_behaviour" class="textbox">\n';
	echo "<option value='0'".($mp3settings['player_behaviour'] == 0 ? " selected='selected'" : "").">".$locale['mp3player_43']."</option>\n";
	echo "<option value='1'".($mp3settings['player_behaviour'] == 1 ? " selected='selected'" : "").">".$locale['mp3player_44']."</option>\n";
	echo "<option value='2'".($mp3settings['player_behaviour'] == 2 ? " selected='selected'" : "").">".$locale['mp3player_45']."</option>\n";
	echo "</select></div>\n";
  
	echo '<div class="form-item">'.$locale['mp3player_100'].': <select name="visualization_enabled" class="textbox">\n';
	echo "<option value='1'".($mp3settings['visualization_enabled'] == 1 ? " selected='selected'" : "").">".$locale['mp3player_40']."</option>\n";
	echo "<option value='0'".($mp3settings['visualization_enabled'] == 0 ? " selected='selected'" : "").">".$locale['mp3player_41']."</option>\n";
	echo "</select></div>\n";
	
	echo '<div class="form-item">'.$locale['mp3player_101'].': <input type="text" name="visualization_height" class="textbox" value="'.$mp3settings['visualization_height'].'" style="width: 70px;" /></div>';
  
  echo '<div class="form-item">'.$locale['mp3player_102'].': <select name="skin" class="textbox">\n';
	$directory  = opendir(INFUSIONS."mp3player_panel/skins/");
while (false !== ($filename = readdir($directory))) {
    if($filename!='.' && $filename!='..' && $filename!='index.php' && strtolower(substr($filename,-4))==".swf") {
    echo "<option value='$filename'".($mp3settings['skin'] == $filename ? " selected='selected'" : "").">".$filename."</option>";
	}
   }
closedir($directory);
  echo '</select><br /> (<a href="http://www.longtailvideo.com/addons/skins" target="_blank">'.$locale['mp3player_103'].'</a>)</div><br />';
  echo '<div class="form-item"><input type="submit" name="settings" value="'.$locale['mp3player_24'].'" class="button" /></div>
</form>';

echo "<div style='height: 25px;'>&nbsp;</div>";

//show local mp3 files
opentable($locale['mp3player_50']);
echo "<table cellpadding='0' cellspacing='0' width='80%'>\n";
echo "<tr>\n<td width='80%' class='tbl'>".$locale['mp3player_51']."</td>\n<td width='20%'>".$locale['mp3player_52']."</td></tr>\n";
$directory  = opendir(INFUSIONS."mp3player_panel/songs/");
$i=0;
while (false !== ($filename = readdir($directory))) {
    if(strtolower(substr($filename,-4))==".mp3") {
    echo "<tr><td width='80%' class='tbl".(($i%2)?2:1)."'>".$filename."</td><td width='20%' class='tbl".(($i%2)?2:1)."'><a href='".FUSION_SELF.$aidlink."&amp;action=localmp3delete&amp;name=".urlencode($filename)."'>".$locale['mp3player_53']."</a></td></tr>\n";
	$i++;
	}
   }
closedir($directory);
echo "</table>\n";
closetable();

echo "<div style='height: 25px;'>&nbsp;</div>";

//remote mp3 add/edit form
opentable($locale['mp3player_70']);
echo "<form method='post' action='".FUSION_SELF.$aidlink."&amp;action=".$remoteformaction."'>";
echo $locale['mp3player_73'].": <input type='text' name='remote_mp3_name' value='".($remoteedit?$remotedata['remote_mp3_name']:"")."' class='textbox' style='width: 200px;' /><br />";
echo $locale['mp3player_74'].": <input type='text' name='remote_mp3_url' value='".$remote_mp3_address."' class='textbox' style='width: 200px;' /><br />";
if ($remoteedit) { echo "<input type='hidden' name='remote_mp3_id' value='".$remotedata['remote_mp3_id']."'>"; }
echo "<input type='submit' name='".($remoteedit?"mp3edit":"mp3add")."' value='".(!$remoteedit?$locale['mp3player_71']:$locale['mp3player_72'])."' class='textbox' />
</form>";
closetable();

//current remote mp3 files
opentable($locale['mp3player_80']);
$result=dbquery("SELECT * FROM ".DB_MP3PLAYERREMOTE." ORDER BY remote_mp3_id DESC");
if (!dbrows($result)) {
echo $locale['mp3player_85'];
} else {
echo "<table cellpadding='0' cellspacing='0' width='80%'>\n";
echo "<tr>\n<td width='70%' class='tbl'>".$locale['mp3player_81']."</td>\n<td width='30%'>".$locale['mp3player_82']."</td></tr>\n";
while($data=dbarray($result)) {
echo "<tr>\n<td width='70%' class='tbl'><a href='".$data['remote_mp3_url']."' target='_blank'>".$data['remote_mp3_name']."</a></td>\n<td width='30%'><a href='".FUSION_SELF.$aidlink."&amp;action=remotemp3edit&amp;id=".$data['remote_mp3_id']."'>".$locale['mp3player_83']."</a> ".THEME_BULLET." <a href='".FUSION_SELF.$aidlink."&amp;action=remotemp3delete&amp;id=".$data['remote_mp3_id']."'>".$locale['mp3player_84']."</a></td></tr>\n";
}
echo "</table>";
}
closetable();

require_once THEMES."templates/footer.php";
?>