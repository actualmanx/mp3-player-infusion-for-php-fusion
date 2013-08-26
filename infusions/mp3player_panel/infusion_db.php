<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion_db.php
| Version: 2.1
| Author: Nick Jones (Digitanium)
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

if (!defined("DB_MP3PLAYERPANEL")) {
define("DB_MP3PLAYERPANEL", DB_PREFIX."mp3player_panel");
}

if (!defined("DB_MP3PLAYERREMOTE")) {
define("DB_MP3PLAYERREMOTE", DB_PREFIX."mp3player_panel_remotemp3");
}


?>