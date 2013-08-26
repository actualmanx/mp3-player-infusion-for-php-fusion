Note: This infusion is last updated on 2010, and I'm posting to GitHub as-is. Most components are out of date.
If you feel like using this feel free to make push request(s), I'll gladly consider them.

Advanced Mp3 Player Panel
Version 2.2

For PHP-Fusion 7.x
Author - Arda Kilicdagi (SoulSmasher)
Web - www.arda.pw

USED 3RD PARTY COMPONENTS
------------------------------------- 
JW FLV Media Player 4.5.203 (http://www.jeroenwijering.com/?item=JW_FLV_Player) with edited simple skin to show files correctly
JQuery Uploadify Plugin 1.62 (www.uploadify.com) (with some upload.php and javascript edits)
JQuery iColorPicker with many edits (http://www.supersite.me/website-building/jquery-free-color-picker/)

CURRENT TRANSLATIONS
---------------------
* English
* Turkish
* Danish (Thanks Helmuth!)
* German (Thanks gozoc!)

FEATURES
----------------
* Ajax File Upload : Within this feature you can upload mp3 files without needing to refresh the page, also you can multi upload during this process. If javascript is disabled, old standart upload form with submit button will be shown for mp3 files.
* These installed local mp3 files can be deleted from administration
* From admin interface, player's all colors, width, height, maximum filesize, player mode can be set. So you can alter it for both left and center side panels and colorize like any style you want
* Remote mp3 files can be added, deleted and edited.
* Mp3 player playlist is updated automatically, so you don't need to worry about generating/updating the playlist xml file
* You can set player mode which allows to show it inside panel or makes a button to open it seperately on a popup window
* Adjustable advanced visualization effect on playback
* Skin support
* advanced error controls are done on each process


INSTALLATION
----------------
* Upload everything in files directory to your root folder
* Chmod the folder /infusions/mp3player_panel/songs/ folder to 777
* Go to Admin panel/System administration/Infusions and infuse Mp3 Player
* Go to Admin panel/System administration/Panels and add mp3player_panel to anywhere you like
* Go to Admin panel/Infusions/Mp3 Player
* You can alter all settings, upload and delete local and remote mp3 files
* Enjoy!

UPDATING
----------------
-From 2.0
*Go to Admin panel/System administration/Infusions and infuse Mp3 Player. It'll update itself

- From 1.1 to 2.0
* Upload All new files
* click www.yoursite.com/infusions/mp3player_panel/update-11-20.php
* If it says this with green colors: "Update Done, now please delete this file", everything went well, now you can delete the file.

- From 1.0 to 2.0
* Upload All new files
* click www.yoursite.com/infusions/mp3player_panel/update-10-11.php
* If it says this with green colors: "Update Done, now please delete this file", everything went well, now you can delete the file.
* click www.yoursite.com/infusions/mp3player_panel/update-11-20.php
* If it says this with green colors: "Update Done, now please delete this file", everything went well, now you can delete the file.

ADDING SKINS
----------------
* Go to http://www.longtailvideo.com/addons/skins and download any skin that you like
* Extract the zipped skin archive and upload skin swf file to infusions/mp3player_panel/skins
* Select your new uploaded skin from mp3 player admin interface

Note: Visualization effect didn't work for me for remote mp3 files, may work for you though 

VERSION HISTORY
----------------
2.2 - a small bug in admin panel is fixed
2.1 - A tiny bug is fixed in playlist.php
	  German translation added  (Thanks gozoc! :))
	  JW FLV Media Player updated to 4.5.203 
2.0 - Visualisation and skinning support added
	  Some Bugfixes
	  Danish translation added (Thanks Helmuth:))
1.1 - Playlist Behaviour And Shuffle Mode Added
1.0 - Initial Release
