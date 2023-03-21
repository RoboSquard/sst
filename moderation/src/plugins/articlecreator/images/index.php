<?php
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
    <title>Google News Full RSS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
	<meta name="robots" content="noindex, follow" />
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="screen" />
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-tooltip.js"></script>
	<script type="text/javascript" src="js/bootstrap-popover.js"></script>
	<script type="text/javascript" src="js/bootstrap-tab.js"></script>
	<script type="text/javascript">
	var baseUrl = 'http://'+window.location.host+window.location.pathname.replace(/(\/index\.php|\/)$/, '');
	$(document).ready(function() {
		// remove http scheme from urls before submitting
		$('#form').submit(function() {
			$('#url').val($('#url').val().replace(/^http:\/\//i, ''));
			return true;
		});
		// popovers
		$('#url').popover({offset: 10, placement: 'left', trigger: 'focus', html: true});
		$('#key').popover({offset: 10, placement: 'left', trigger: 'focus', html: true});
		$('#max').popover({offset: 10, placement: 'left', trigger: 'focus', html: true});
		$('#links').popover({offset: 10, placement: 'left', trigger: 'focus', html: true});
		$('#exc').popover({offset: 10, placement: 'left', trigger: 'focus', html: true});
		// tooltips
		$('a[rel=tooltip]').tooltip();
	});
	</script>
	<style>
	html, body { background-color: #eee; }
	body {
	margin: 0;
	line-height: 1.4em;
	font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
	background-color: #E0F7FC;
}
	label, input, select, textarea { font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; }
	li { color: #404040; }
	li.active a { font-weight: bold; color: #666 !important; }
	form .controls { margin-left: 220px !important; }
	label { width: 200px !important; }
	fieldset legend {
	padding-left: 220px;
	line-height: 20px !important;
	margin-bottom: 12px !important;
	margin-top: 24px;
}
	.form-actions { padding-left: 220px !important; }
	.popover-inner { width: 205px; }
	h1 {
	margin-bottom: 4px;
}
	.style1 {
	font-size: 24px
}
    </style>
  </head>
  <body>
	<div class="container" style="width: 800px; padding-bottom: 60px;">
	<center>
	  <h1 style="padding-top: 5px;">Google News Full RSS</h1>
	  <p style="margin-bottom: 25px;">Google News Full Feed Finder</p>
  </center>
    <form method="get" action="googlefullrss.php" id="form" class="form-horizontal">
	<fieldset>
		<div align="center"></div>
	<div align="center">
  <p class="style1">Enter Your Keyword</p>
  <div></div>
			</label>
	  
	<div>
	  <input type="text" id="keyword" name="keyword" style="width: 450px;" title="KEYWORD" data-content="Enter your keyword here. Example: seo, hosting, web+hosting, android+tablet." />
	</div>
</div>
	</fieldset>
    <fieldset>
	<legend></legend>
	<legend>Options</legend>
	<?php if (isset($options->api_keys) && !empty($options->api_keys)) { ?>
	<div class="control-group">
	<label class="control-label" for="key">Access key</label>
	<div class="controls">
	<input type="text" id="key" name="key" class="input-medium" <?php if ($options->key_required) echo 'required'; ?> title="Access Key" data-content="<?php echo ($options->key_required) ? 'An access key is required to generate a feed' : 'If you have an access key, enter it here.'; ?>" />
	</div>
	</div>
	<?php } ?>
	<div class="control-group">
	<label class="control-label" for="max">Max items</label>
	<div class="controls">
	<?php
	// echo '<select name="max" id="max" class="input-medium">'
	// for ($i = 1; $i <= $options->max_entries; $i++) {
	//	printf("<option value=\"%s\"%s>%s</option>\n", $i, ($i==$options->default_entries) ? ' selected="selected"' : '', $i);
	// } 
	// echo '</select>';
	if (!empty($options->api_keys)) {
		$msg = 'Limit: '.$options->max_entries.' (with key: '.$options->max_entries_with_key.')';
		$msg_more = 'If you need more items, change <tt>max_entries</tt> (and <tt>max_entries_with_key</tt>) in config.';
	} else {
		$msg = 'Limit: '.$options->max_entries;
		$msg_more = 'If you need more items, change <tt>max_entries</tt> in config.';
	}
	?>	
	<input type="text" name="max" id="max" class="input-mini" value="<?php echo $options->default_entries; ?>" title="Feed item limit" data-content="Set the maximum number of feed items we should process. The smaller the number, the faster the new feed is produced.<br /><br />If your URL refers to a standard web page, this will have no effect: you will only get 1 item.<br /><br /> <?php echo $msg_more; ?>" />
	<span class="help-inline" style="color: #888;"><?php echo $msg; ?></span>
	</div>
	</div>
	<div class="control-group">
	<label class="control-label" for="links">Links</label>
	<div class="controls">
	<select name="links" id="links" class="input-medium" title="Link handling" data-content="By default, links within the content are preserved. Change this field if you'd like links removed, or included as footnotes.">
		<option value="preserve" selected="selected">preserve</option>
		<option value="footnotes">add to footnotes</option>
		<option value="remove">remove</option>
	</select>
	</div>
	</div>
	<?php if ($options->exclude_items_on_fail == 'user') { ?>
	<div class="control-group">
	<label class="control-label" for="exc">If extraction fails</label>
	<div class="controls">
	<select name="exc" id="exc" title="Item handling when extraction fails" data-content="If extraction fails, we can remove the item from the feed or keep it in.<br /><br />Keeping the item will keep the title, URL and original description (if any) found in the feed. In addition, we insert a message before the original description notifying you that extraction failed.">
		<option value="" selected="selected">keep item in feed</option>
		<option value="1">remove item from feed</option>
	</select>
	</div>
	</div>
	<?php } ?>
	
	<div class="control-group">
	<label class="control-label" for="json">JSON output</label>
	<div class="controls">
	<input type="checkbox" name="format" value="json" id="json" style="margin-top: 7px;" />
	</div>
	</div>
	
	<div class="control-group" style="margin-top: -15px;">
	<label class="control-label" for="debug">Debug</label>
	<div class="controls">
	<input type="checkbox" name="debug" value="1" id="debug" style="margin-top: 7px;" />
	</div>
	</div>	
	
	</fieldset>
	<div class="form-actions">
		<input type="submit" id="sudbmit" name="submit" value="Create Feed" class="btn btn-primary" />
	</div>
	</form>
	
	

	<div id="license" class="tab-pane">
	<p><a href="http://en.wikipedia.org/wiki/Affero_General_Public_License" style="border-bottom: none;"><img src="images/agplv3.png" alt="AGPL logo" /></a></p>
	<p>Full Content RSS is licensed under the <a href="http://en.wikipedia.org/wiki/Affero_General_Public_License">AGPL version 3</a></p> 
	<p>The software components in this application are licensed as follows...</p>
	<ul>
		<li>PHP Readability: <a href="http://www.apache.org/licenses/LICENSE-2.0">Apache License v2</a></li>
		<li>SimplePie: <a href="http://en.wikipedia.org/wiki/BSD_license">BSD</a></li>
		<li>FeedWriter: <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html">GPL v2</a></li>
		<li>Humble HTTP Agent: <a href="http://en.wikipedia.org/wiki/Affero_General_Public_License">AGPL v3</a></li>
		<li>Zend: <a href="http://framework.zend.com/license/new-bsd">New BSD</a></li>
		<li>Rolling Curl: <a href="http://www.apache.org/licenses/LICENSE-2.0">Apache License v2</a></li>
		<li>html5lib: <a href="http://opensource.org/licenses/mit-license.php">MIT</a></li>
		<li>htmLawed: <a href="http://en.wikipedia.org/wiki/GNU_Lesser_General_Public_License">LGPL v3</a></li>
		<li>Text_LanguageDetect: <a href="http://en.wikipedia.org/wiki/BSD_license">BSD</a></li>		
	</ul>
	</div>
	

  </body>
</html>