<?php
/* $ip     = $_SERVER['REMOTE_ADDR']; // means we got user's IP address 

$ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
$ipInfo = json_decode($ipInfo);
$timezone = $ipInfo->timezone;

print_r($timezone);


if ($ipData['timezone']) {
  echo  $tz = new DateTimeZone( $ipData['timezone']);
    $now = new DateTime( 'now', $tz); // DateTime object corellated to user's timezone
} else {
   // we can't determine a timezone - do something else...
}

 */

ob_start(); 
session_start(); 

if(isset($_GET['id']))
{
$cookie_name = "id";

setcookie("id",$_GET['id']);
}

if(isset($_COOKIE['id'])) {
echo $_COOKIE['id'];
} 
else
{
echo 'http://feeds.feedburner.com/arsenalfcblog';	
}

?>