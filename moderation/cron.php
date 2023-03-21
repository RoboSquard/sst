<?php
// ini_set('display_errors', '1');
// error_reporting(E_ALL);
require '../vendor/autoload.php';
require_once 'config.php';
require_once 'src/inc/my_node.php';

require_once '../helper.php';
include_once('../model/model.php');
require_once '../model/panel.php';
require_once '../model/group.php';
require_once '../model/panelsetting.php';
require_once '../model/defaultpanelsetting.php';
require_once '../model/users.php';
include_once ('../config.php');  
require_once '../full_text_feed.php';  
require_once '../model/Feed.php';
require_once '../model/Note.php';

$action = $_REQUEST['action'];
$action = "public_$action";

if (function_exists($action)) {
    $action();
} else {
    header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
    die();
}

/* Get queue items */
function public_get_items()
{
    echo json_encode(['status' => true, 'data' => get_queue_items()]);
}

/* Get single item */
function public_get_item()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $index = (int) filter_input(INPUT_POST, 'index');
    $items = get_queue_items();

    echo json_encode(['status' => true, 'data' => clear_item($items[$index])]);
}

/* Add post to queue */
function public_add_item()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    session_start();
    // Get input data
    $index = (int) filter_input(INPUT_POST, 'index');
    $panel_id = (int) $_SESSION['panel_id'];
    $moderation_id = (int) $_SESSION['moderation_id'];

    $record = getRecord("moderations", $moderation_id);


    $document = new DOMDocument();
    $filePath = "../".$record['rss_path'];
    $document ->load($filePath);
    $itemNodeOriginal = $document->getElementsByTagName('item')->item($index);
    $title =  $itemNodeOriginal->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
    $description =  $itemNodeOriginal->getElementsByTagName('description')->item(0)->firstChild->nodeValue;
    $link =  $itemNodeOriginal->getElementsByTagName('link')->item(0)->firstChild->nodeValue;
    $approve =  'yes';



    $item = add_queue_item([
        'title' => $title,
        'description' => $description,
        'link' => $link,
        'approve' => $approve,
        'updated' => 0,
        'user' => login_user(),
        'panelId' => $panel_id
    ]);

    $itemNodeOriginal->getElementsByTagName('approve')->item(0)->firstChild->nodeValue = 'yes';
    $itemNodeOriginal->getElementsByTagName('approve')->item(0)->setAttribute("scheduled","yes");
    if($itemNodeOriginal->getElementsByTagName('status')->item(0)){
        $itemNodeOriginal->getElementsByTagName('status')->item(0)->firstChild->nodeValue = 'Approved';
    }else{
        $status = $document->importNode($document->createElement('status', 'Approved'), true);
        $itemNodeOriginal->appendChild($status);
    }
    $document->save($filePath);

    $options = array(
        'cluster' => 'ap2',
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        PUSHER_APP_KEY,
        PUSHER_APP_SECRET,
        PUSHER_APP_ID,
        $options
    );

    $data['panel_id'] = $panel_id;
    $pusher->trigger('my-channel', 'update-feed', $data);
    
    // return status
    echo json_encode(['status' => ($item === false ? false : true), 'data' => $item, 'items' => get_queue_items()]);
    return;
}

/* Update post in queue */
function public_update_item()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    // Get input data
    $index = (int) filter_input(INPUT_POST, 'index');
    $image_url = filter_input(INPUT_POST, 'image_url');
    $title = filter_input(INPUT_POST, 'title');
    $description = filter_input(INPUT_POST, 'description');
    $link = filter_input(INPUT_POST, 'link');
    $publish_date = filter_input(INPUT_POST, 'publish_date');

    $description = "<a href=\"$link\"><img src=\"$image_url\"></a> $description";
    $description = htmlentities($description, ENT_QUOTES, 'UTF-8');

    $item = update_queue_item(compact('index', 'title', 'description', 'link', 'publish_date'));

    echo json_encode(['status' => (bool) $item, 'data' => get_queue_items()]);
}

/* Delete post from queue */
function public_delete_item()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $index = (int) filter_input(INPUT_POST, 'index');

    $items = get_queue_items();
    unset($items[$index]);
    $items = array_values($items);
    $status = save_queue_items($items);

    echo json_encode(['status' => true, 'data' => $items]);
}

/* Publish post from  queue */
function public_publish_item()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $index = (int) filter_input(INPUT_POST, 'index');
    $status = null;

    try {
        $status = publish_queue_item($index);
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'message' => $e->getMessage()]);
        return;
    }

    echo json_encode(['status' => $status, 'data' => get_queue_items()]);
}

/* Update item postion in queue */
function public_update_item_position()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $index = (int) filter_input(INPUT_POST, 'index');
    $new_index = (int) filter_input(INPUT_POST, 'new_index');

    $items = get_queue_items();
    $updatedItems = [];
    $start_date = DateTime::createFromFormat('Y-m-d', $items[0]['publishDate']);

    $replaced_item = $items[$new_index];
    $replacing_item = $items[$index];
    $replacing_item['publishDate'] = $replaced_item['publishDate'];
    $replaced_item['updated'] = 0;
    $replacing_item['updated'] = 0;

    foreach ($items as $i => $item) {
        if ($i === $index) {
            continue;
        } else if ($i === $new_index) {
            if ($index > $new_index) {
                $replaced_item['publishDate'] = DateTime::createFromFormat('Y-m-d', $replacing_item['publishDate'])
                    ->modify('+1 day')
                    ->format('Y-m-d');
                $updatedItems[] = $replacing_item;
                $updatedItems[] = $replaced_item;
            } else {
                $replaced_item['publishDate'] = DateTime::createFromFormat('Y-m-d', $replacing_item['publishDate'])
                    ->modify('-1 day')
                    ->format('Y-m-d');
                $updatedItems[] = $replaced_item;
                $updatedItems[] = $replacing_item;
            }
        } else {
            if ($index > $new_index) {
                if ($i < $new_index) {
                    $updatedItems[] = $item;
                } else {
                    $updatedDate = DateTime::createFromFormat('Y-m-d', $updatedItems[count($updatedItems) - 1]['publishDate']);
                    $currentDate = DateTime::createFromFormat('Y-m-d', $items[$i]['publishDate']);
                    if ($updatedDate->getTimestamp() === $currentDate->getTimestamp()) {
                        $updatedDate->modify('+1 day');
                        $items[$i]['publishDate'] = $updatedDate->format('Y-m-d');
                    }
                    $updatedItems[] = $items[$i];
                }
            } else {
                if ($i > $new_index) {
                    $updatedItems[] = $item;
                } else {
                    $currentDate = DateTime::createFromFormat('Y-m-d', $items[$i]['publishDate']);
                    if (count($updatedItems) > 0) {
                        $updatedDate = DateTime::createFromFormat('Y-m-d', $updatedItems[count($updatedItems) - 1]['publishDate']);
                        if ($updatedDate->getTimestamp() === $currentDate->getTimestamp()) {
                            $updatedDate->modify('+1 day');
                            $items[$i]['publishDate'] = $updatedDate->format('Y-m-d');
                        }
        
                        $updatedItems[] = $items[$i];
                    } else {
                        $items[$i]['publishDate'] = $start_date->format('Y-m-d');
                        $updatedItems[] = $items[$i];
                    }
                }
            }
        }
    }

    if (save_queue_items($updatedItems) === false) {
        echo json_encode(['status' => false]);
        return;
    }

    echo json_encode(['status' => true, 'data' => get_queue_items()]);
}

/* Publish items through cron */
function public_cron_publish()
{
    $items = get_queue_items();
    $today = time();

    $output = "";

    foreach ($items as $index => $item) {
        if ($item['published'] === true)
            continue;

        $item_publish_date = DateTime::createFromFormat('Y-m-d', $item['publishDate']);
        if ($item_publish_date->getTimestamp() <= $today) {
            try {
                publish_queue_item($index);
                $output .= "<li style=\"margin-bottom:16px;\">{$item['title']} <sup><i><b>({$item_publish_date->format('M j')})</b></i></sup></li>";
            } catch (Exception $e) {
                continue;
            }
        }
    }
    echo "<hr><h3>******* START PUBLISHING POSTS ***********</h3>";
    if (empty($output)) {
        echo "<h2>No posts were published.</h2>";
    } else {
        $output = "<h2>The following posts were published:</h2><ul>" . $output . "</ul>";
        echo $output;
    }

    exit();
    // Import Posts from rss feeds
    $urls = get_rss_urls();

    require 'src/inc/Feed.php';

    $xmlDoc = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');
    $output = "";

    foreach ($urls as $url) {
        $rss = Feed::loadRss($url);
        $postCount = 0;
        foreach ($rss->item as $newItem) {
            $itemExists = false;
            foreach ($xmlDoc->channel->item as $item) {
                if (strtolower($newItem->title) === strtolower($item->title))
                    $itemExists = true;
            }

            if ($itemExists)
                continue;

            $description = "<a href=\"{$newItem->link}\"><img src=\"\"></a> {$newItem->description}";

            // Store new item
            $item = $xmlDoc->channel->addNewItem();
            $item->addChild('title', htmlentities($newItem->title ?? ' ', ENT_XML1));
            $item->addChild('description', htmlentities($newItem->description ?? ' ', ENT_XML1));
            $item->addChild('link', ' ');
            $item->link = $newItem->link ?? ' ';
            $item->addChild('guid', ' ');
            $item->guid = $newItem->link ?? ' ';
            $item->addChild('pubDate', $newItem->pubDate ?? ' ');
            $item->addChild('approve', 'no');
            ++$postCount;
        }
        $output .= "<li style=\"margin-bottom:16px;\">($postCount) <sub>($url)</sub></li>";
    }

    $dom = new DOMDocument("1.0");
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xmlDoc->asXML());
    $status = file_put_contents(ADMIN_FEED_FILE, $dom->saveXML());

    if (empty($output)) {
        echo "<h2>No posts were imported.</h2>";
    } else {
        $output = "<h2>Posts from the following rss feeds were imported:</h2><ul>" . $output . "</ul>";
        echo $output;
    }

    echo "<h3>******* END IMPORT RSS/CSV (OLD CRON JOB) ***********</h3><hr>";
    echo "<h3>******* START POST CREATION ***********</h3>";
    require 'articlescronjob.php';
    private_create();
    echo "<h3>******* END POST CREATION ***********</h3><hr>";

    echo "<h3>******* START AUTOMATIC POSTS (NEW CRON JOB) ***********</h3>";
    //require 'importschedule.php';
    runnewcron();
    echo "<h3>******* END AUTOMATIC POSTS (NEW CRON JOB) ***********</h3>";
}
function public_automatic_import_new_rss()
{

    $imageapis = array();
    foreach ($_POST['imageapi'] as $selectedOption) {
        $imageapis[] = $selectedOption;
    }


    $defaultvalues = array(
        'links' => $_POST["links"],
        'rewrite' => $_POST["rewrite"],
        'sentences' => $_POST["sentences"],
        'truncate' => $_POST["truncate"],
        'imageapi' => $imageapis, // $_POST["imageapi"],
        'newRSS' => $_POST["newRSS"],
        'hashtags' => $_POST["hashtags"],

    );
    require 'importschedule.php';

    runcron_with_values($defaultvalues);
}
/* Schedule Rss */
function public_schedule_rss()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $urls = $_POST['urls'];
    /*  $defaultvalues = array(
        'links' => $_POST["links"],
        'rewrite' => $_POST["rewrite"],
        'sentences' => $_POST["sentences"],
        'imageapi' => $_POST["imageapi"],
    );

    file_put_contents(RSS_DEFAULT_VALUES, json_encode($defaultvalues));
    */
    foreach ($urls as $url) {
        $url = filter_var($url, FILTER_VALIDATE_URL);
        if (!$url) {
            echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Invalid url!']);
            return;
        }
    }

    foreach ($urls as $url) {
        $url = filter_var($url, FILTER_VALIDATE_URL);
        if (!$url) {
            echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Invalid url!']);
            return;
        }
    }

    add_rss_urls($urls);

    echo json_encode(['status' => true, 'data' => get_rss_urls()]);
}

/* Schedule Rss */
function public_automatic_schedule_rss()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $imageapis = array();
    foreach ($_POST['imageapi'] as $selectedOption) {
        $imageapis[] = $selectedOption;
    }

    //$urls = $_POST['urls'];
    $defaultvalues = array(
        'links' => $_POST["links"],
        'rewrite' => $_POST["rewrite"],
        'sentences' => $_POST["sentences"],
        'truncate' => $_POST["truncate"],
        'imageapi' => $_POST['imageapi'], //$imageapis,
        'newRSS' => $_POST["newRSS"],
        'hashtags' => $_POST["hashtags"],
    );
    $curdata = array();
    $currentdata = file_get_contents(RSS_AUTOMATIC_LIST);
    if ($currentdata != "") {
        $curdata = json_decode($currentdata);
    }
    function myfunction($v)
    {
        return ($v->newRSS);
    }

    $sortarray = array_map("myfunction", $curdata);
    if (!in_array($_POST["newRSS"], $sortarray)) {
        $curdata[] = $defaultvalues;
    }

    //file_put_contents(RSS_DEFAULT_VALUES, json_encode($defaultvalues));

    // foreach ($urls as $url) {
    $url = filter_var($_POST["newRSS"], FILTER_VALIDATE_URL);
    if (!$url) {
        echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Invalid url!']);
        return;
    }
    // }

    //foreach ($urls as $url) {
    $url = filter_var($_POST["newRSS"], FILTER_VALIDATE_URL);
    if (!$url) {
        echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Invalid url!']);
        return;
    }
    //}

    // add_rss_urls($urls);
    file_put_contents(RSS_AUTOMATIC_LIST, json_encode($curdata));

    echo json_encode(['status' => true, 'data' => get_rss_urls(), 'newdata' => json_encode($curdata)]);
}

/* Schedule Keywords */
function public_automatic_schedule_keywords()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $imageapis = array();
    foreach ($_POST['imageapi'] as $selectedOption) {
        $imageapis[] = $selectedOption;
    }

    //$urls = $_POST['urls'];
    $defaultvalues = array(
        'keyword' => $_POST["keyword"],
        'rewrite' => $_POST["rewrite"],
        'truncate' => $_POST["truncate"],
        'sentences' => $_POST["sentences"],
        'numbers' => $_POST["numbers"],
        'imageapi' => $imageapis, // $_POST["imageapi"],
        'language' => $_POST["lang"],
        'hashtags' => $_POST["hashtags"],

    );
    $curdata = array();
    $currentdata = file_get_contents(KEYWORDS_LIST);
    if ($currentdata != "") {
        $curdata = json_decode($currentdata);
    }
    function myfunction2($v)
    {
        return ($v->keyword);
    }

    $sortarray = array_map("myfunction2", $curdata);
    if (!in_array($_POST["keyword"], $sortarray)) {
        $curdata[] = $defaultvalues;
    }

    //file_put_contents(RSS_DEFAULT_VALUES, json_encode($defaultvalues));

    // foreach ($urls as $url) {

    // }


    // add_rss_urls($urls);
    file_put_contents(KEYWORDS_LIST, json_encode($curdata));

    echo json_encode(['status' => true, 'data' => get_rss_urls(), 'keywords' => json_encode($curdata)]);
}
/* Get Rss urls */
function public_get_rss_urls()
{
    echo json_encode(['status' => true, 'data' => get_rss_urls(), 'newdata' => file_get_contents(RSS_AUTOMATIC_LIST), 'keywords' => file_get_contents(KEYWORDS_LIST)]);
}


/* Delete Rss url */
function public_delete_rss_url()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    if (!$url) {
        echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Invalid url!']);
        return;
    }

    delete_rss_url($url);

    echo json_encode(['status' => true, 'data' => get_rss_urls()]);
}

/* Delete Rss url */
function public_delete_rss_url_auto()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    if (!$url) {
        echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Invalid url!']);
        return;
    }

    //delete_rss_url($url);
    $curdata = array();
    $currentdata = file_get_contents(RSS_AUTOMATIC_LIST);
    if ($currentdata != "") {
        $curdata = json_decode($currentdata);
    }
    $newcur = array();
    foreach ($curdata as $lnk) {
        if ($lnk->newRSS != $url) {
            $newcur[] = $lnk;
        }
    }
    file_put_contents(RSS_AUTOMATIC_LIST, json_encode($newcur));


    echo json_encode(['status' => true, 'data' => get_rss_urls(), 'newdata' => json_encode($newcur)]);
}
/* Delete keyword */
function public_delete_keyword()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $url = $_POST['url']; //filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);


    //delete_rss_url($url);
    $curdata = array();
    $currentdata = file_get_contents(KEYWORDS_LIST);
    if ($currentdata != "") {
        $curdata = json_decode($currentdata);
    }
    $newcur = array();
    foreach ($curdata as $lnk) {
        if ($lnk->keyword != $url) {
            $newcur[] = $lnk;
        }
    }
    file_put_contents(KEYWORDS_LIST, json_encode($newcur));


    echo json_encode(['status' => true, 'data' => get_rss_urls(), 'keywords' => json_encode($newcur)]);
}
/* Get queue items from file */
function get_queue_items()
{
    if (!file_exists(QUEUE_FILE)) {
        file_put_contents(QUEUE_FILE, json_encode([]));
    }

    $items = json_decode(file_get_contents(QUEUE_FILE), true);

    $items = array_filter($items, function ($var) {
        return (isset($var['user']) && $var['user'] == login_user());
    });

    usort($items, function($a, $b) { 
        return strtotime($a['publishDate']) - strtotime($b['publishDate']); 
      });
      

    return $items;
}

/* Save queue items to file */
function save_queue_items($items)
{
    return (bool) file_put_contents(QUEUE_FILE, json_encode($items));
}

/* Add queue item */
function add_queue_item($item)
{
    $timezone = 'Europe/London';
    date_default_timezone_set($timezone);

    $items = get_queue_items();

    $itemPublishDate = null;
    if (count($items) === 0) {
        $itemPublishDate = new DateTime();
    } else {
    
       $all_items = array_filter($items, function ($var) {
            return ($var['updated'] == 0);
        });
    
        usort($all_items, function($a, $b) { 
            return strtotime($a['publishDate']) - strtotime($b['publishDate']); 
          });

          if(count($all_items) > 0){
            $date = isset($all_items[count($all_items) - 1]) ? $all_items[count($all_items) - 1]['publishDate'] : $all_items[count($all_items)]['publishDate'];
            $date_now = date("Y-m-d"); // this format is string comparable
            $prev_date = date('Y-m-d', strtotime($date .' -1 day'));
            if($date < $date_now && $date != $prev_date ){
              $itemPublishDate = new DateTime();
          }else{
              $itemPublishDate = DateTime::createFromFormat('Y-m-d', $date);
              $itemPublishDate->modify('+1 day');
          }
          }else{
            $itemPublishDate = new DateTime();
          }
         
    }

    $item['publishDate'] = $itemPublishDate->format('Y-m-d');
    $item['published'] = false;
    $items[count($items)] = $item;

    if (save_queue_items($items) === false)
        return false;

    $item['id'] = count($items) - 1;
    return $item;
}

/* Update queue item */
function update_queue_item($item)
{
    extract($item);

    $items = get_queue_items();
    $items[$index]['title'] = $title;
    $items[$index]['description'] = $description;
    $items[$index]['link'] = $link;
    $items[$index]['publishDate'] = $publish_date;
    $items[$index]['updated'] = 1;

    if (save_queue_items($items) === false)
        return false;

    return $items[$index];
}

/* Publish queue item */
function publish_queue_item($index)
{
    $items = get_queue_items();

    $current = $items[$index];

    if(isset($current['panelId']) && !empty($current['panelId'])){
        $panel_id = $current['panelId'];
        $record = getWhere('moderations', ['panel_id' => $panel_id]);
        
        $publishFilePath = "../".$record['published_rss_path'];
    }else{
        $items[$index]['published'] = true;
        save_queue_items($items);
        return true;
    }
    
   $publishedFeed = simplexml_load_file($publishFilePath, 'my_node');

    // Check if post with this title already exists
    foreach ($publishedFeed->channel->item as $item) {
        if (strtolower($item->title) === strtolower($items[$index]['title']))
            throw new Exception("Post with this title already published");
    }

    // Store post
    $newItem = $publishedFeed->channel->addNewItem();
    $newItem->addChild('title', htmlentities($items[$index]['title'], ENT_XML1) );
    $newItem->addChild('description', htmlentities($items[$index]['description'], ENT_XML1) );
    $newItem->addChild('link', htmlentities($items[$index]['link'], ENT_XML1) );
    $newItem->addChild('guid', htmlentities($items[$index]['link'], ENT_XML1));
    $newItem->addChild('pubDate', date(DATE_RFC2822, time()));
    $newItem->addChild('approve', 'yes');
    $dom = new DOMDocument("1.0");
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($publishedFeed->asXML());
    $status = file_put_contents($publishFilePath, $dom->saveXML());
    $status = ($status === false ? false : true);

    if ($status) {
        $items[$index]['published'] = true;
        save_queue_items($items);
    }

    return $status;
    
}

/* Get post data from admin feed */
function get_post($index = null, $approve = false)
{
    $adminFeed = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');

    if (!isset($adminFeed->channel->item[$index])) {
        return false;
    }

    if ($approve) {
        $adminFeed->channel->item[$index]->approve = 'yes';
        $adminFeed->channel->item[$index]->approve->addAttribute('scheduled', 'yes');
        $adminFeed->asXML(ADMIN_FEED_FILE);
    }

    return (array) $adminFeed->channel->item[$index];
}

/* Extract image url from description */
function clear_item($item)
{
    $item['description'] = html_entity_decode($item['description'], ENT_QUOTES, 'UTF-8');
    $item['image_url'] = dc_get_image($item['description']);
    $item['description'] = trim(preg_replace('/ +/', ' ', preg_replace('[^A-Za-z0-9����������]', ' ', urldecode(html_entity_decode(strip_tags($item['description']))))));
    return $item;
}

function dc_get_image($html)
{
    $doc = new DOMDocument();

    @$doc->loadHTML($html);

    $xpath = new DOMXPath($doc);

    $src = $xpath->evaluate("string(//img/@src)"); # "/images/image.jpg"

    return $src;
}

/* Get urls from file */
function get_rss_urls()
{
    $urls = [];
    $file = fopen(RSS_URLS_FILE, 'a+');
    fseek($file, 0);
    while (!feof($file)) {
        $url = trim(fgets($file));
        $url = str_replace("\n", "", $url);
        if (empty($url)) continue;
        $urls[] = $url;
    }
    fclose($file);

    return $urls;
}

/* Add urls to file */
function add_rss_urls($urls)
{
    $file = fopen(RSS_URLS_FILE, 'a+');

    foreach ($urls as $url) {
        fseek($file, 0);
        $exists = false;
        while (!feof($file)) {
            $line = trim(fgets($file));
            $line = str_replace("\n", "", $line);
            if ($line === $url)
                $exists = true;
        }
        if (!$exists)
            fwrite($file, $url . "\r\n");
    }

    fclose($file);
}

/* delete url from file */
function delete_rss_url($url)
{
    $urls = get_rss_urls();
    foreach ($urls as $i => $u) {
        if ($u === $url) {
            unset($urls[$i]);
            break;
        }
    }

    $file = fopen(RSS_URLS_FILE, 'w');

    foreach ($urls as $url) {
        fwrite($file, $url . "\r\n");
    }

    fclose($file);
}
