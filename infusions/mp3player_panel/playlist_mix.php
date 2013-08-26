<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2009 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: playlist.php
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
require_once "../../maincore.php";
include INFUSIONS."mp3player_panel/infusion_db.php";

header ("Content-type: application/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>
<playlist version="1" xmlns="http://xspf.org/ns/0/">
	<trackList>';
$directory  = opendir(INFUSIONS."mp3player_panel/songs/");
while (false !== ($filename = readdir($directory))) {
$i=0;
	if($filename!='.' && $filename!='..' && $filename!='index.php') {
	$playlist[$i]['name']=$filename;
	$playlist[$i]['url']=$settings['siteurl']."infusions/mp3player_panel/songs/".$filename;
	}
$i++;
}
closedir($directory);
//			<image>".INFUSIONS."mp3player_panel/includes/button.png</image>

//remote mp3
$result=dbquery("SELECT remote_mp3_name, remote_mp3_url FROM ".DB_MP3PLAYERREMOTE." ORDER BY remote_mp3_id DESC");
if(dbrows($result)) {
while($data=dbarray($result)) {
$playlist[$i]['name']=$data['remote_mp3_name'];
$playlist[$i]['url']=$data['remote_mp3_url'];
}
}

array_multisort($playlist[0]);

for ($j=0;$j<$i;$j++) {
echo "<track>
			<title>".$playlist[$j]['name']."</title>
			<location>".$playlist[$j]['url']."</location>
	</track>";
}

echo "</trackList></playlist>";
?>