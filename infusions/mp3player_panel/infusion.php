<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2009 Nick Jones
| http://www.php-fusion.co.uk/
+---------------------------------------------------------+
| Mp3 Player Panel 2.1
| Author: SoulSmasher © 2009
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

if (!defined("IN_FUSION")) { die("Access Denied"); }

if (file_exists(INFUSIONS."mp3player_panel/locale/".$settings['locale'].".php")) {
	include_once INFUSIONS."mp3player_panel/locale/".$settings['locale'].".php";
} else {
	include_once INFUSIONS."mp3player_panel/locale/English.php";
}

include INFUSIONS."mp3player_panel/infusion_db.php";

// Infusion Information
$inf_title = $locale['mp3player_00'];
$inf_version = "2.2";
$inf_developer = "Arda Kilicdagi (SoulSmasher)";
$inf_email = "soulsmasher@gmail.com";
$inf_weburl = "http://www.soulsmasher.net/";
$inf_description = $locale['mp3player_01'];

$inf_folder = "mp3player_panel"; // The folder in which the infusion resides.

// Delete any items not required here.
$inf_newtable[1] = DB_MP3PLAYERPANEL." (
	backcolor VARCHAR(6) NOT NULL DEFAULT '000000',
	frontcolor VARCHAR(6) NOT NULL DEFAULT 'ffffff',
	lightcolor VARCHAR(6) NOT NULL DEFAULT 'bbbbbb',
	maxfilesize INT(10) UNSIGNED NOT NULL DEFAULT '5242880',
	player_mode TINYINT(1) NOT NULL DEFAULT '1',
	player_shuffle TINYINT(1) NOT NULL DEFAULT '0',
	player_behaviour TINYINT(1) NOT NULL DEFAULT '0',
	width SMALLINT(4) NOT NULL DEFAULT '160',
	height SMALLINT(4) NOT NULL DEFAULT '200',
	visualization_enabled TINYINT(1) NOT NULL DEFAULT '1',
	visualization_height SMALLINT(4) NOT NULL DEFAULT '100',
	skin VARCHAR(50) NOT NULL DEFAULT 'simple-edited.swf'
) TYPE=MyISAM;";

$inf_newtable[2] = DB_MP3PLAYERREMOTE." (
	remote_mp3_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	remote_mp3_url VARCHAR(200) NOT NULL DEFAULT '',
	remote_mp3_name VARCHAR(200) NOT NULL DEFAULT '',
	PRIMARY KEY(remote_mp3_id)
) TYPE=MyISAM;";

$inf_insertdbrow[1] = DB_MP3PLAYERPANEL." (backcolor) VALUES ('000000')"; //defaults are added automatically / varolan standart deðerleri sql den zaten alýyor


$inf_adminpanel[1] = array(
	"title" => $locale['mp3player_00'],
	"image" => "../../infusions/mp3player_panel/mp3.png",
	"panel" => "mp3player_admin.php",
	"rights" => "MP"
);

$inf_droptable[1] = DB_MP3PLAYERPANEL;
$inf_droptable[2] = DB_MP3PLAYERREMOTE;
?>