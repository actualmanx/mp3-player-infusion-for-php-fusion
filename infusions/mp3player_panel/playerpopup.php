<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2009 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: playerpopup.php
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
if (file_exists(INFUSIONS."mp3player_panel/locale/".$settings['locale'].".php")) {
	include_once INFUSIONS."mp3player_panel/locale/".$settings['locale'].".php";
} else {
	include_once INFUSIONS."mp3player_panel/locale/English.php";
}

include_once INFUSIONS."mp3player_panel/infusion_db.php";

$mp3settings=dbarray(dbquery("SELECT * FROM ".DB_MP3PLAYERPANEL));

echo "<html><head><style type='text/css'>
body {
margin-top: 0px;
margin-left: 0px;
}
</style>
</head><body>";
echo "<center>";
echo "<script type='text/javascript' src='".INFUSIONS."mp3player_panel/includes/swfobject.js'></script>

  <div id='mediaspace'>Mp3 Player</div>

  <script type='text/javascript'>
  var s1 = new SWFObject('".INFUSIONS."mp3player_panel/includes/player.swf','ply','".$mp3settings['width']."','".($mp3settings['visualization_enabled']?($mp3settings['height']+$mp3settings['visualization_height']):$mp3settings['height'])."','9','#');
  s1.addParam('allowfullscreen','false');
  s1.addParam('allowscriptaccess','sameDomain');
  s1.addParam('wmode','opaque');
  s1.addParam('flashvars','file=".INFUSIONS."mp3player_panel/playlist.php&playlist=bottom&backcolor=".$mp3settings['backcolor']."&frontcolor=".$mp3settings['frontcolor']."&lightcolor=".$mp3settings['lightcolor']."&playlistsize=".($mp3settings['height']-20).($mp3settings['player_shuffle']?"&shuffle=true":"").($mp3settings['player_behaviour']==0?"":($mp3settings['player_behaviour']==1?"&repeat=list":"&repeat=always")).($mp3settings['visualization_enabled']?"&plugins=revolt-1":"")."&skin=".INFUSIONS."mp3player_panel/skins/".$mp3settings['skin']."');
  s1.write('mediaspace');
</script>";
echo "</center>";
echo "</body></html>";

?>