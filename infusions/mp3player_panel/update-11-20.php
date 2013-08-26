<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2009 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: update-11-20.php
| CVS Version: 2.0
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
if (iADMIN) {
//advanced mp3 player panel 1.1->2.0 update sql
include INFUSIONS."mp3player_panel/infusion_db.php";
$result1=dbquery("ALTER TABLE ".DB_MP3PLAYERPANEL." ADD visualization_enabled TINYINT(1) NOT NULL DEFAULT '1'");
$result2=dbquery("ALTER TABLE ".DB_MP3PLAYERPANEL." ADD visualization_height SMALLINT(4) NOT NULL DEFAULT '100'");
$result3=dbquery("ALTER TABLE ".DB_MP3PLAYERPANEL." ADD skin VARCHAR(50) NOT NULL DEFAULT 'simple-edited.swf'");
$result4=dbquery("UPDATE ".DB_MP3PLAYERPANEL." SET visualization_enabled='1', visualization_height='100', skin='simple-edited.swf'");
$result5=dbquery("UPDATE ".DB_INFUSIONS." SET inf_version='2.0' WHERE inf_folder='mp3player_panel'");
echo ($result1&&$result2&&$result3&&$result4&&$result5)?"<font color='green'>Update Done, now please delete this file</font>":"<font color='red'>Update not done, </font>";
}
exit;
?>