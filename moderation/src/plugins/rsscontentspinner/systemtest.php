<?php
/*
FULL RSS COMPATIBILITY TEST 
*/

$app_name = 'Full RSS';

$php_ok = (function_exists('version_compare') && version_compare(phpversion(), '5.2.0', '>='));
$pcre_ok = extension_loaded('pcre');
$zlib_ok = extension_loaded('zlib');
$mbstring_ok = extension_loaded('mbstring');
$iconv_ok = extension_loaded('iconv');
$tidy_ok = function_exists('tidy_parse_string');
$curl_ok = function_exists('curl_exec');
$parallel_ok = ((extension_loaded('http') && class_exists('HttpRequestPool')) || ($curl_ok && function_exists('curl_multi_init')));
$allow_url_fopen_ok = (bool)ini_get('allow_url_fopen');
$filter_ok = extension_loaded('filter');

if (extension_loaded('xmlreader')) {
	$xml_ok = true;
} elseif (extension_loaded('xml')) {
	$parser_check = xml_parser_create();
	xml_parse_into_struct($parser_check, '<foo>&amp;</foo>', $values);
	xml_parser_free($parser_check);
	$xml_ok = isset($values[0]['value']);
} else {
	$xml_ok = false;
}

header('Content-type: text/html; charset=UTF-8');

?><!DOCTYPE html>

<html lang="en">
<head>
<title><?php echo $app_name; ?>: System Requirements Test</title>

<style type="text/css">
body {
	font:14px/1.4em "Lucida Grande", Verdana, Arial, Helvetica, Clean, Sans, sans-serif;
	letter-spacing:0px;
	color:#333;
	margin:0;
	padding:0;
	background:#fff;
}

div#site {
	width:550px;
	margin:20px auto 0 auto;
}

a {
	color:#000;
	text-decoration:underline;
	padding:0 1px;
}

a:hover {
	color:#fff;
	background-color:#333;
	text-decoration:none;
	padding:0 1px;
}

p {
	margin:0;
	padding:5px 0;
}

em {
	font-style:normal;
	background-color:#ffc;
	padding: 0.1em 0;
}

.success {
  background-color: lightgreen;
}

.highlight {
  background-color: #ffc;
}

ul, ol {
	margin:10px 0 10px 20px;
	padding:0 0 0 15px;
}

ul li, ol li {
	margin:0 0 7px 0;
	padding:0 0 0 3px;
}

h2 {
	font-size:18px;
	padding:0;
	margin:30px 0 20px 0;
}

h3 {
	font-size:16px;
	padding:0;
	margin:20px 0 5px 0;
}

h4 {
	font-size:14px;
	padding:0;
	margin:15px 0 5px 0;
}

code {
	font-size:1.1em;
	color:#000;
}

em strong {
    text-transform: uppercase;
}

table#chart {
	border-collapse:collapse;
}

table#chart th {
	background-color:#eee;
	padding:2px 3px;
	border:1px solid #fff;
}

table#chart td {
	text-align:center;
	padding:2px 3px;
	border:1px solid #eee;
}

table#chart tr.enabled td {
	/* Leave this alone */
}

table#chart tr.disabled td, 
table#chart tr.disabled td a {
	color:#999;
	font-style:italic;
}

table#chart tr.disabled td a {
	text-decoration:underline;
}

div.chunk {
	margin:20px 0 0 0;
	padding:0 0 10px 0;
	border-bottom:1px solid #ccc;
}

.footnote,
.footnote a {
	font:10px/12px verdana, sans-serif;
	color:#aaa;
}

.footnote em {
	background-color:transparent;
	font-style:italic;
}
</style>

</head>

<body>

<div id="site">
	<div id="content">

		<div class="chunk">
			<h2 style="text-align:center;"><?php echo $app_name; ?>: Compatibility Test</h2>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" id="chart">
				<thead>
					<tr>
						<th>Test</th>
						<th>Should Be</th>
						<th>What You Have</th>
					</tr>
				</thead>
				<tbody>
					<tr class="<?php echo ($php_ok) ? 'enabled' : 'disabled'; ?>">
						<td>PHP</td>
						<td>5.2.0 or higher</td>
						<td><?php echo phpversion(); ?></td>
					</tr>
					<tr class="<?php echo ($xml_ok) ? 'enabled, and sane' : 'disabled, or broken'; ?>">
						<td><a href="http://php.net/xml">XML</a></td>
						<td>Enabled</td>
						<td><?php echo ($xml_ok) ? 'Enabled, and sane' : 'Disabled, or broken'; ?></td>
					</tr>
					<tr class="<?php echo ($pcre_ok) ? 'enabled' : 'disabled'; ?>">
						<td><a href="http://php.net/pcre">PCRE</a></td>
						<td>Enabled</td>
						<td><?php echo ($pcre_ok) ? 'Enabled' : 'Disabled'; ?></td>
					</tr>
					<tr class="<?php echo ($zlib_ok) ? 'enabled' : 'disabled'; ?>">
						<td><a href="http://php.net/zlib">Zlib</a></td>
						<td>Enabled</td>
						<td><?php echo ($zlib_ok) ? 'Enabled' : 'Disabled'; ?></td>
					</tr>
					<tr class="<?php echo ($mbstring_ok) ? 'enabled' : 'disabled'; ?>">
						<td><a href="http://php.net/mbstring">mbstring</a></td>
						<td>Enabled</td>
						<td><?php echo ($mbstring_ok) ? 'Enabled' : 'Disabled'; ?></td>
					</tr>
					<tr class="<?php echo ($iconv_ok) ? 'enabled' : 'disabled'; ?>">
						<td><a href="http://php.net/iconv">iconv</a></td>
						<td>Enabled</td>
						<td><?php echo ($iconv_ok) ? 'Enabled' : 'Disabled'; ?></td>
					</tr>
					<tr class="<?php echo ($filter_ok) ? 'enabled' : 'disabled'; ?>">
						<td><a href="http://uk.php.net/manual/en/book.filter.php">Data filtering</a></td>
						<td>Enabled</td>
						<td><?php echo ($filter_ok) ? 'Enabled' : 'Disabled'; ?></td>
					</tr>					
					<tr class="<?php echo ($tidy_ok) ? 'enabled' : 'disabled'; ?>">
						<td><a href="http://php.net/tidy">Tidy</a></td>
						<td>Enabled</td>
						<td><?php echo ($tidy_ok) ? 'Enabled' : 'Disabled'; ?></td>
					</tr>
					<tr class="<?php echo ($curl_ok) ? 'enabled' : 'disabled'; ?>">
						<td><a href="http://php.net/curl">cURL</a></td>
						<td>Enabled</td>
						<td><?php echo (extension_loaded('curl')) ? 'Enabled' : 'Disabled'; ?></td>
					</tr>
					<tr class="<?php echo ($parallel_ok) ? 'enabled' : 'disabled'; ?>">
						<td>Parallel URL fetching</td>
						<td>Enabled</td>
						<td><?php echo ($parallel_ok) ? 'Enabled' : 'Disabled'; ?></td>
					</tr>
					<tr class="<?php echo ($allow_url_fopen_ok) ? 'enabled' : 'disabled'; ?>">
						<td><a href="http://www.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen">allow_url_fopen</a></td>
						<td>Enabled</td>
						<td><?php echo ($allow_url_fopen_ok) ? 'Enabled' : 'Disabled'; ?></td>
					</tr>						
				</tbody>
			</table>
		</div>

</div>

</body>
</html>