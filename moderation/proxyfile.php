<?php
function ends_with($haystack, $needle)
{
    return substr($haystack, -strlen($needle)) === $needle;
}

if (!in_array(ini_get('allow_url_fopen'), [1, 'on', 'true'])) {
    die('PHP configuration change is required for image proxy: allow_url_fopen setting must be enabled!');
}

$url = isset($_GET['url']) ? $_GET['url'] : null;


if (!$url || substr($url, 0, 4) != 'http') {
    die('Please, provide correct URL');
}

$parsed = parse_url($url);

if ((!ends_with($parsed['host'], 'cdninstagram.com') && !ends_with($parsed['host'], 'fbcdn.net')) || !ends_with($parsed['path'], 'jpg')) {
    // die('Please, provide correct URL');
}

// instagram only has jpeg images for now..
header("Content-type: image/jpeg");
readfile($url);
