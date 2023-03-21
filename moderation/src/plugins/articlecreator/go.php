<?php
// Article Creator Script
// This script can grab articles from any keyword and rewrite them to unique articles
// Author: FullContentRSS.com
// Script URL: http://articlecreator.fullcontentrss.com
require_once(dirname(__FILE__).'/config.php');
?><!DOCTYPE html>
<html>
  <head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
	<meta name="robots" content="noindex, nofollow" />
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-tooltip.js"></script>
	<script type="text/javascript" src="js/bootstrap-popover.js"></script>
	<script type="text/javascript" src="js/bootstrap-tab.js"></script>
  </head>
  <body>
	<div class="container">
		<center>
	  	<div align="center" style="margin-bottom:0px;"><a href="index.php" title="FREE Unique Article Creator Online"><img src="images/ArticleCreatorLogo.png"></a></div>
	 	<div style="font-size:14px; color:grey;">Multi Languages SEO Friendly Article Generator Online</div><br /><br />
  		</center>


  
<?php

error_reporting(0);

if ($_GET['keyword'] == '') {
	echo("<title>You don't have permission to access this page</title>");
	echo "<center><h1>You don't have permission to access this page!</h1></center><br><br><br>";
	include ('message.php');
        include ("footer.php");
        exit;
	}

//$baseurl = "http://articlecreator.fullcontentrss.com/";
$baseurl  = $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], "/")) ."/";

$lang = filter_var($_GET['lang'], FILTER_SANITIZE_SPECIAL_CHARS); 

if((strpos(trim($_GET['keyword']), ' ') == false) and $lang == 'en'){
 $keyword = '%22' .$_GET['keyword'] .'%22'; $keyword= filter_var($keyword, FILTER_SANITIZE_SPECIAL_CHARS);
} else { $keyword=$_GET['keyword']; }

$urlsource = $baseurl ."read.php?keyword=" .urlencode($keyword) ."&lang=" .$lang ."&links=remove";


	
include ('accesskey.php');
$getkey= filter_var($_GET['accesskey'], FILTER_SANITIZE_SPECIAL_CHARS); 
$numbers = filter_var($_GET['numbers'], FILTER_SANITIZE_SPECIAL_CHARS);

if (in_array($getkey, $accesskey) or (($_GET['rewrite'] == 'original') and ($numbers == 3) and ($lang == 'en'))) {

	$gettitle = filter_var($_GET['keyword'], FILTER_SANITIZE_SPECIAL_CHARS) ." :: Article Creator";
	echo("<title>$gettitle</title>");

	$prefix = mt_rand(100,1000);
	$myFile = 'foldertemp/' .$prefix ."_generatedfile.txt";
	$spintaxtfile = 'foldertemp/' .$prefix ."_generatedfile_SPINTAXT.txt";
	if(file_exists("$myFile")) unlink("$myFile");
	if(file_exists("$spintaxtfile")) unlink("$spintaxtfile");


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $urlsource);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 300);
	$returned = curl_exec($ch);
	curl_close($ch);	
	$feed = simplexml_load_string($returned);


	$count = 0;
	$maxitems = $numbers;
	foreach ($feed->channel->item as $item) {

		if ($count < $maxitems){

			$title = $item->title;
			$title = str_replace("<b>", "", $title);
			$subject = str_replace("</b>", "", $title);
			$link = $item->link;

			$body= $item->description;
			//$body = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $body);
			$body = strip_tags($body, '<p><li><b><img>');

			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
			

			if (($_GET['rewrite'] == 'unique') and ($lang == 'en')) {
				require_once('unik.class.php'); 
				require_once ('spinter.php');
				$data = $body;
				$unik=new unik;
				$spintaxbody=$unik->spin($data);
				$spinter = new Spinter();
				$newbody=$spinter->process($spintaxbody);

				$data = $subject;
				$unik=new unik;
				$spintaxsubject=$unik->spin($data);
				$spinter = new Spinter();
				$newsubject=$spinter->process($spintaxsubject);
				} else {
					$newbody = $body;
					$newsubject = $subject;
					}
				

			echo "<h3>" .$newsubject ."</h3>";
			echo $newbody;
			echo "<br />";
			$newbody = preg_replace ('/<[^>]*>/', ' ', $newbody);

   			 // ----- remove control characters -----
   			$newbody = str_replace("\r", '', $newbody);    // --- replace with empty space
    			$newbody = str_replace("\n", ' ', $newbody);   // --- replace with space
    			$newbody = str_replace("\t", ' ', $newbody);   // --- replace with space
   
    			// ----- remove multiple spaces -----
   			$newbody = trim(preg_replace('/ {2,}/', ' ', $newbody));

			$filecontent = $newsubject ." " ."\n" ."\n" .$newbody ."\n" ."\n" ."\n";
			$fh = fopen($myFile, 'a');
			fwrite($fh, "\xEF\xBB\xBF".$filecontent);

			if (($_GET['rewrite'] == 'unique') and ($lang == 'en')) {

			$spintaxbody = preg_replace ('/<[^>]*>/', ' ', $spintaxbody);

   			 // ----- remove control characters -----
   			$spintaxbody = str_replace("\r", '', $spintaxbody);    // --- replace with empty space
    			$spintaxbody = str_replace("\n", ' ', $spintaxbody);   // --- replace with space
    			$spintaxbody = str_replace("\t", ' ', $spintaxbody);   // --- replace with space
   
    			// ----- remove multiple spaces -----
   			$spintaxbody = trim(preg_replace('/ {2,}/', ' ', $spintaxbody));

   
			$filecontentspintax = $spintaxsubject ." " ."\n" ."\n" .$spintaxbody ."\n" ."\n" ."\n";
			$fsx = fopen($spintaxtfile, 'a');
			fwrite($fsx, "\xEF\xBB\xBF".$filecontentspintax);

			}
                        

		}
		$count++;
		if(is_resource($fh)) {
			fclose($fh);
		}		
	}


	echo "<br /><div align='center'><a href='$myFile' target='_blank'><div class='btn btn-primary'>Download TXT file </div></a> ";


	if (($_GET['rewrite'] == 'unique') and ($lang == 'en')) {	
		echo "<a href='$spintaxtfile' target='_blank'><div class='btn btn-primary'>SPINTAX format </div></a>";}
	
	echo "<a href='index.php'><div class='btn btn-secondary'>Generate new articles</div></a></div><br />";
	} else
	{
		$page_title = "Invalid Access Key!";
		echo("<title>$page_title</title>");
		echo "<center><h1>Invalid Access Key!</h1></center>";
	}
	
?>

<br /><?php include ("footer.php"); ?>



	</div>
  </body>
</html>