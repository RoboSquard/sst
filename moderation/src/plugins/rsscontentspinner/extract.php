<?php
define('_FF_FTR_MODE', 'simple');

// Don't process URL as feed
$_POST['html'] = '1';
// JSON output only
$_POST['format'] = 'json';
// Enable excerpts
$_POST['summary'] = '1';
// Don't produce result if extraction fails
$_POST['exc'] = '1';
// Enable XSS filtering (unless explicitly disabled)
if (isset($_POST['xss']) && $_POST['xss'] !== '0') {
	$_POST['xss'] = '1';
} elseif (isset($_GET['xss']) && $_GET['xss'] !== '0') {
	$_GET['xss'] = '1';
} else {
	$_POST['xss'] = '1';
}

require 'go.php';