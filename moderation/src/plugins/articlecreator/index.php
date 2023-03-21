<?php
// Article Creator Script
// This script can grab articles from any keyword and rewrite them to unique articles
// Author: FullContentRSS.com
// Script URL: http://articlecreator.fullcontentrss.com
require_once(dirname(__FILE__).'/config.php');
?><!DOCTYPE html>
<html>
  <head>
    <title>FREE Unique Article Creator Online</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
	<meta name="robots" content="noindex,nofollow" />
	<meta name="viewport" content="width=device-width">
	<meta name="description" content="Multi Languages SEO Friendly Article Generator Online" />
	<meta name="keywords" content="content, unique article, articles, contents, unique content, keyword, seo article, google seo" />
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
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

		// tooltips
		$('a[rel=tooltip]').tooltip();
	});
	</script>
  </head>
  <body>
	<div class="container">
	<center>

	  <div align="center" style="margin-bottom:0px;"><a href="index.php" title="FREE Unique Article Creator Online"><img src="images/ArticleCreatorLogo.png"></a></div>
	  <div style="font-size:14px; color:grey;">Multi Languages SEO Friendly Article Generator Online</div><br /><br />
  	</center>


  <form method="get" action="go.php" id="form" class="form-horizontal"role="form">

		<div class="form-group">
			<label class="control-label col-sm-2" for="keyword">Keyword:</label>
			<div class="col-sm-10">
				<input type="text" class="form-control input-lg" id="keyword" name="keyword" title="KEYWORD" placeholder="Enter keyword e.g. smartphone, samsung galaxy, seo tool, etc"required />
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-2" for="lang">Language</label>
			<div class="col-sm-10">
			<select name="lang" id="lang" class="form-control" title="Language" data-content="Select the language!">
				<option value="en" selected="selected">English</option>
				<option value="fr">French</option>
				<option value="de">Germany</option>
				<option value="es">Spanish</option>
				<option value="nl">Netherland</option>
				<option value="cn">Chinese</option>
				<option value="jp">Japanese</option>
				<option value="br">Brazil</option>
				<option value="ru">Russia</option>
				<option value="se">Sweden</option>
				<option value="pl">Polish</option>
				<option value="kr">Korean</option>
				<option value="tr">Turkish</option>

			</select>
			</div>
		</div>

		
		<div id="spinner">
			<div class="form-group">
				<label class="control-label col-sm-2" for="rewrite">Rewrite articles:</label>
				<div class="col-sm-10">
				<select name="rewrite" id="rewrite" class="form-control" title="Rewrite articles" data-content="By default, links within the content are preserved. Change this field if you'd like links removed.">
					<option value="original">keep original</option>
					<option value="unique">make unique</option>
				</select>
			</div>
			</div>
		</div>
	
		<div class="form-group">
			<label class="control-label col-sm-2" for="numbers">Number of Articles</label>
			<div class="col-sm-10">
			<select name="numbers" id="numbers" class="form-control" title="Number of Articles">
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
			</select>
			</div>
		</div>


		<div id="showaccesskey" class="form-group" style="display:none">
				<label class="control-label col-sm-2" for="accesskey">Access Key</label>
				<div class="col-sm-10"><input type="text" id="accesskey" name="accesskey"  class="form-control" title="ACCESS KEY" />
				<br /><div style=font-size:80%;><font color="grey">Access key is required when you select "Make unique" or set the number of articles to more than three.</font></div>
				</div>
		</div>

		
			<script type="text/javascript">
				document.getElementById('rewrite').addEventListener('change', function () {
				var style = this.value == 'unique' ? 'block' : 'none';
				document.getElementById('showaccesskey').style.display = style;
				});
				document.getElementById('lang').addEventListener('change', function () {
				var style = this.value == 'en' ? 'block' : 'none';
				document.getElementById('spinner').style.display = style;
				});
				document.getElementById('numbers').addEventListener('change', function () {
				var style = this.value !== '3' ? 'block' : 'none';
				document.getElementById('showaccesskey').style.display = style;
				});
				document.getElementById('lang').addEventListener('change', function () {
				var style = this.value !== 'en' ? 'block' : 'none';
				document.getElementById('showaccesskey').style.display = style;
				});
			</script>
	

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" id="submit" name="submit" value="Submit" class="btn btn-primary" />
		</div>
	</div>
	</form>


<?php include ("footer.php"); ?>

</div>
  </body>
</html>