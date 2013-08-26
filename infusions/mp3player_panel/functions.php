<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright ╘ 2002 - 2009 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: functions.php
| CVS Version: 2.1
| Author: Arda KЩlЩГdaПЩ (SoulSmasher)
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
//because some hosts don't have this option and we want to temporarily emulate if there isn't
/*if(!function_exists("iconv")) { 
function iconv($input,$output,$string) {
return $string;
	}
}*/

//this is to upload Turkish filenames properly, add your characters as you like
function validate_filename($string) {
	

	$string = utf8_decode($string);
	$string = strtr($string, utf8_decode(" бйнтшю"), "_AEIOU");
	$string = strtolower($string);
	$search = array("г", "щ", "п", "ж", "ч", "э", "Г", "Щ", "П", "Ж", "Ч", "Э");
	$replace = array("c", "i", "g", "o", "s", "u", "c", "i", "g", "o", "s", "u");
	$string = str_replace($search, $replace, $string);
    return $string;
	
}

function validate_filename_old($text) {
	$search = array("г", "щ", "п", "ж", "ч", "э", "Г", "Щ", "П", "Ж", "Ч", "Э", " ");
	$replace = array("c", "i", "g", "o", "s", "u", "c", "i", "g", "o", "s","u", "_");
	$text = urlencode(str_replace($search, $replace, $text));
	//$text=iconv($locale['charset'],"UTF-8",$text); //does not work because of ajax upload
	return $text;
}
?>