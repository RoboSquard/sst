<?php
// RSS Content Spinner Script
// This script convert rss feed into full rss with spun content 
// Author: FullContentRSS.com
// Script URL: http://spinner.fullcontentrss.com
error_reporting(0);
require_once(dirname(__FILE__).'/config.php');
// check for custom index.php (custom_index.php)
if (!defined('_FF_FTR_INDEX')) {
	define('_FF_FTR_INDEX', true);
	if (file_exists(dirname(__FILE__).'/custom_index.php')) {
		include(dirname(__FILE__).'/custom_index.php');
		exit;
	}
}
?><!DOCTYPE html>
<html>
  <head>
    <title>RSS Feed Content Spinner</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
	<meta name="robots" content="noindex,nofollow" />
	<meta name="viewport" content="width=device-width">
	<meta name="description" content="Convert any RSS feeds into full rss with article rewriter or content spinner feature" />
	<meta name="keywords" content="content, rss feed, autoblog, article, full rss, article rewriter, content spinner" />
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-tooltip.js"></script>
	<script type="text/javascript" src="js/bootstrap-popover.js"></script>
	<script type="text/javascript" src="js/bootstrap-tab.js"></script>
	</head>
  <body>
	<div class="container">
	<h1 style="padding-top: 5px; padding-left:100px;">RSS Feed Content Spinner </h1>
    <form method="get" action="go.php" id="form" class="form-horizontal">
		<legend>Full RSS Feed Generator with article rewriter</legend>

	
	<fieldset>	
		<div class="control-group">
			<label class="control-label" for="url">Source Feed</label>
			<div class="controls"><input type="text" id="url" name="url" style="width: 450px;" title="Source Feed URL" /><br />
			<div style="font-size:small; color:#888">Enter valid RSS feed here, e.g. http://feeds.foxnews.com/foxnews/latest</div></div>
		</div>

	<?php if (isset($options->api_keys) && !empty($options->api_keys)) { ?>
	<div class="control-group">
	<label class="control-label" for="key">Access key</label>
	<div class="controls">
	<input type="text" id="key" name="key" class="input-medium" <?php if ($options->key_required) echo 'required'; ?> title="Access Key" />
	</div>
	</div>
	<?php } ?>

	<div class="control-group">
	<label class="control-label" for="links">Links</label>
	<div class="controls">
	<select name="links" id="links" class="input-medium" title="Link handling">
		<option value="preserve" selected="selected">preserve</option>
		<option value="footnotes">add to footnotes</option>
		<option value="remove">remove</option>
	</select>
	</div>
	</div>
	<div class="control-group">
	<label class="control-label" for="rewrite">Rewrite RSS</label>
	<div class="controls">
	<select name="rewrite" id="rewrite" title="Rewrite rss title & content">
		<option value="1" selected="selected">Rewrite title & content</option>
		<option value="2">Rewrite content</option>
		<option value="3">Rewrite title</option>
		<option value="">Don't rewrite</option>
	</select>
	</div>
	</div>
	

	</fieldset>
	<div class="form-actions">
		<input type="submit" id="submit" name="submit" value="Create Feed" class="btn btn-primary" />
	</div>
	</form>


	<br /><br /><br />
	
	<?php include ("footer.php"); ?></div>
	
  </body>
</html>