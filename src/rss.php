<?php
ini_set('display_errors', '0');
error_reporting(E_ALL | E_STRICT);
$GLOBALS['db_overwrite'] = '../db/data.db';

require_once '../helper.php';
include_once('../model/model.php');
require_once '../model/users.php';
require_once '../config.php';
require_once '../model/Note.php';

header('Content-Type: application/json');
$feed = new DOMDocument();

$page_id = isset($_GET['id']) ? $_GET['id'] : '';
$limit = isset($_GET['limit']) ? $_GET['limit'] : 20;
$type = isset($_GET['type']) ? $_GET['type'] : 'rss';

if ($type == 'pinterest') {
    $source = isset($_GET['feed']) ? $_GET['feed'] : 'user';

    if ($source == 'board') {
        $feed_url = 'https://www.pinterest.com/' . $page_id . '.rss';
    } else {
        $feed_url = 'https://www.pinterest.com/' . $page_id . '/feed.rss';
    }
} else {
    if (strpos($page_id, 'localhost') > -1)
        $feed_url = 'http://' . $page_id;
    else
        $feed_url = 'https://' . $page_id;

}

$feed->load($feed_url);
$count = 0;
$json = array();

$feed_title = $feed->getElementsByTagName('channel')->item(0)->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
$items = $feed->getElementsByTagName('channel')->item(0)->getElementsByTagName('item');

$json['item'] = array();

foreach ($items as $item) {

    $title = $item->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
    $description = $item->getElementsByTagName('description')->item(0)->firstChild->nodeValue;

    $text = $item->getElementsByTagName('description')->item(0)->firstChild->nodeValue;
    $image = dc_get_image($text);

//   $clear = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($text))))));

    $clear = trim(preg_replace('/ +/', ' ', preg_replace('[^A-Za-z0-9����������]', ' ', urldecode(html_entity_decode(strip_tags($text))))));

    //  $clear = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9\p{L}\s\p{N}\'\.\ ]+/u', ' ', urldecode(html_entity_decode(strip_tags($text))))));


    @$standardimage = $item->getElementsByTagName('standardimage')->item(0)->firstChild->nodeValue;
    $link = $item->getElementsByTagName('link')->item(0)->firstChild->nodeValue;
    @$publishedDate = $item->getElementsByTagName('pubDate')->item(0)->firstChild->nodeValue;
    @$status = $item->getElementsByTagName('status')->item(0)->firstChild->nodeValue;

    // Retrieve post notes
    $notes = null;
    $hasNotes = false;
    $noteModel = new Note();
    $noteRec = $noteModel->getNote([
        'post_title' => $title
    ]);
    if ($noteRec) {
        $hasNotes = true;
        $notes = $noteRec['note'];
    }
    if (!$image) {
        $image = base64_decode($item->getElementsByTagName('image_data')->item(0)->firstChild->nodeValue);
    }

    // Get Type
    $type = $item->getElementsByTagName('type');
    if (count($type) > 0) {
        $type = $type->item(0)->firstChild->nodeValue;
    } else {
        $type = null;
    }

    // Get address if type is email
    $address = null;
    if ($type === 'email') {
        $address = $item->getElementsByTagName('address');
        if (count($address) > 0) {
            $address = $address->item(0)->firstChild->nodeValue;
        } else {
            $address = null;
        }
    }

    $json['item'][$count] = array(
        "title" => $title,
        "description" => $description,
        "link" => $link,
        "publishedDate" => $publishedDate,
        "text" => $clear,
        "feedTitle" => $feed_title,
        "image" => $image,
        "notes" => $notes,
        "hasNotes" => $hasNotes,
        'type' => $type,
        'address' => $address,
        'hash' => md5($title),
        'status' => $status

    );
    $count++;
}
if (isset($_GET['cache'])) {
    echo '</pre>', print_r($json);
    die();
}
echo json_encode($json);

function dc_get_image($html)
{
    $doc = new DOMDocument();
    @$doc->loadHTML($html);
    $xpath = new DOMXPath($doc);
    $src = $xpath->evaluate("string(//img/@src)"); # "/images/image.jpg"
    return $src;
}

?>