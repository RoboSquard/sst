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

if (function_exists($action)) {
    $action();
} else {
    header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
    die();
}

/* Add post to rss feed */
function create()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    // Get input data
    $image_url = filter_input(INPUT_POST, 'image_url');
    $title = filter_input(INPUT_POST, 'title');
    $description = filter_input(INPUT_POST, 'description');
    $link = filter_input(INPUT_POST, 'link');
    $notes = filter_input(INPUT_POST, 'notes');

    $description = "<a href=\"$link\"><img src=\"$image_url\"></a> $description";

    // Load xml document
    $xmlDoc = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');

    // Check if post with this title already exists
    try {
        foreach ($xmlDoc->channel->item as $item) {
            if (strtolower($item->title) === strtolower($title))
                throw new Exception("Post with this title already exists");
        }
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'type' => 'error', 'message' => $e->getMessage()]);
        return;
    }

    // Store post
    $newItem = $xmlDoc->channel->addNewItem();
    $newItem->addChild('title', $title);
    $newItem->addChild('description', $description);
    $newItem->addChild('link', $link);
    $newItem->addChild('guid', $link);
    $newItem->addChild('pubDate', date(DATE_RFC2822, time()));
    $newItem->addChild('approve', 'no');
    $dom = new DOMDocument("1.0");
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xmlDoc->asXML());
    $status = file_put_contents(ADMIN_FEED_FILE, $dom->saveXML());
    $status = ($status === false ? false : true);

    // Store notes in json file
    if ($notes) {
        if (!file_exists(NOTES_FILE)) {
            file_put_contents(NOTES_FILE, json_encode([]));
        }

        $notesArr = json_decode(file_get_contents(NOTES_FILE), true);
        array_unshift($notesArr, ['title' => $title, 'notes' => $notes]);

        file_put_contents(NOTES_FILE, json_encode($notesArr));
    }

    $updatedPost = [];
    if ($status) {
        $updatedPost = [
            'title' => $title,
            'description' => filter_input(INPUT_POST, 'description'),
            'image_url' => $image_url,
            'link' => $link,
            'notes' => $notes,
            'pubDate' => ((array) $newItem->pubDate[0])[0],
            'hasNotes' => ($notes ? true : false)
        ];
    }

    // return status
    echo json_encode(['status' => $status, 'data' => $updatedPost]);
    return;
}

/* Update post in rss feed */
function update()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    session_start();
    // Get input data
    $index = (int) filter_input(INPUT_GET, 'index');
    $panel_id = (int) $_SESSION['panel_id'];
    $current_title = filter_input(INPUT_POST, 'current_title');
    $image_url = filter_input(INPUT_POST, 'image_url');
    $title = filter_input(INPUT_POST, 'title');
    $new_description = filter_input(INPUT_POST, 'description');
    $link = filter_input(INPUT_POST, 'link');
    $notes = filter_input(INPUT_POST, 'notes');

    try {
        $moderation_id = (int) $_SESSION['moderation_id'];
        $record = getRecord("moderations", $moderation_id);
    
    
        $doc = new DOMDocument();
        $filePath = "../".$record['rss_path'];
        $panelName = str_replace(' ', '-', $record['panel_name']);
        $ip = str_replace('.', '', get_client_ip());
        $ip = str_replace(':', '', $ip);
        $fileName = $panelName . '_'. $ip . '.xml';
        $doc->load($filePath);
    
        $itemNode = $doc->getElementsByTagName('item')->item($index);
        $oldTitle =  $itemNode->getElementsByTagName('title')->item(0)->firstChild->nodeValue;

       
        $is_exists = false;

        foreach ($doc->getElementsByTagName('item') as $item) {
            $oldest = $item->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
            if (strtolower($oldest) === strtolower($title))
                $is_exists = true;
        }
        
        if (strtolower($oldTitle) !== strtolower($title) && $is_exists)
                throw new Exception("Post with this title already exists");

            $description = "<a href=\"$link\"><img src=\"$image_url\"></a> $new_description";

            $titleNode = $itemNode->getElementsByTagName('title')->item(0);
            $descriptionNode = $itemNode->getElementsByTagName('description')->item(0);
            $linkNode = $itemNode->getElementsByTagName('link')->item(0);
            $guidNode = $itemNode->getElementsByTagName('guid')->item(0);
            $pubDateNode = $itemNode->getElementsByTagName('pubDate')->item(0);

            $titleNode->replaceChild($doc->createTextNode($title), $titleNode->firstChild);
            $descriptionNode->replaceChild($doc->createTextNode($description), $descriptionNode->firstChild);
            $linkNode->replaceChild($doc->createTextNode($link), $linkNode->firstChild);

            if ($guidNode)
                $guidNode->replaceChild($doc->createTextNode($link), $guidNode->firstChild);
            else
                $itemNode->appendChild($doc->createElement('guid', $link));
            if ($pubDateNode)
                $pubDateNode->replaceChild($doc->createTextNode(date(DATE_RFC2822, time())), $pubDateNode->firstChild);
            else
                $itemNode->appendChild($doc->createElement('pubDate', date(DATE_RFC2822, time())));

            
        $doc->save($filePath);
    

        $status = true;
        
    } catch(Exception $e) {
        echo json_encode(['status' => false, 'type' => 'error', 'message' => $e->getMessage()]);
        return;
    }
    
    // Update or Store notes in json file
    $noteModel = new Note();
    $noteRec = $noteModel->getNote([
        'panel_id' => $panel_id,
        'post_title' => $title
    ]);

    if ($noteRec) {
        if ($notes) {
            $noteModel->updateData(['note' => $notes], ['id' => $noteRec['id']]);
        } else {
            $noteModel->deleteNote($noteRec['id']);
        }
    } else if ($notes) {
        $noteModel->storeData([
            'panel_id' => $panel_id,
            'post_title' => $title,
            'note' => $notes
        ]);
    }
    
    $updatedPost = [];
    if ($status) {
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

        $updatedPost = [
            'title' => $title,
            'description' => $new_description,
            'image_url' => $image_url,
            'link' => $link,
            'notes' => $notes,
            'pubDate' => date(DATE_RFC2822, time()),
            'hasNotes' => ($notes? true: false),
            'panel_id' => $panel_id
        ];
        
    }

    // return status and updated post
    echo json_encode(['status' => $status, 'data' => $updatedPost, 'panel_id' => $panel_id]);
    return;
}

/* Delete post from rss feed */
function delete()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    session_start();
    // Get input data
    $index = (int) filter_input(INPUT_GET, 'index');
    $panel_id = (int) $_SESSION['panel_id'];
    $moderation_id = (int) $_SESSION['moderation_id'];

    $record = getRecord("moderations", $moderation_id);


    $document = new DOMDocument();
    $filePath = "../".$record['rss_path'];
    $panelName = str_replace(' ', '-', $record['panel_name']);
    $ip = str_replace('.', '', get_client_ip());
    $ip = str_replace(':', '', $ip);
    $fileName = $panelName . '_'. $ip . '.xml';
    $document->load($filePath);
    $itemNodeOriginal = $document->getElementsByTagName('item')->item($index);
    $title =  $itemNodeOriginal->getElementsByTagName('title')->item(0)->firstChild->nodeValue;


    foreach ($document->getElementsByTagName('item') as $index => $itemNode)
        {
            $q = $itemNode->getElementsByTagName('title')->item(0)->firstChild->nodeValue;

            if ($q === $title) {

                $delDir = "../rss_files";
                if (!is_dir($delDir))
                mkdir($delDir, 0777, true);
                $filePathDel = $delDir . '/deleted_' . $fileName;

                if (!file_exists($filePathDel)) {
                    $doc = new DOMDocument("1.0", "UTF-8");
                    $rss = $doc->createElement("rss"); 
                    $rssNode = $doc->appendChild($rss);
                    $rssNode->setAttribute("version","2.0");

                    $channel = $doc->createElement("channel");
                    $channelNode = $rssNode->appendChild($channel);
    
                } else {
                    $doc = new DOMDocument();
                    $doc->load($filePathDel);
                    $channelNode = $doc->getElementsByTagName('channel');
                    $channelNode = $channelNode[0];
                
                }
                $importedItem = $doc->importNode($itemNode, true);
                $channelNode->appendChild($importedItem);
                
                
                date_default_timezone_set('Europe/London');
                $sTime = date("d-m-Y H:i:s");  

                $removedDate = $doc->importNode($doc->createElement("deleteAt", $sTime), true)  ;
                $softDeleted = $doc->importNode($doc->createElement("softDeleted", 1), true)  ;
                $hardDeleted = $doc->importNode($doc->createElement("hardDeleted", 0), true)  ;

                $importedItem->appendChild($removedDate);
                $importedItem->appendChild($softDeleted);
                $importedItem->appendChild($hardDeleted);
                $doc->save($filePathDel);

                $itemNodeOriginal->parentNode->removeChild($itemNodeOriginal);

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

                break;
            }
        }
    

    $document->save($filePath);
    $status = true;

    // return status
    echo json_encode(['status' => $status]);
    return;
}

// Import posts from Rss
function import_from_rss()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $urls = $_POST['urls_to_publish'];

    foreach ($urls as $url) {
        $url = filter_var($url, FILTER_VALIDATE_URL);
        if (!$url) {
            echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Invalid url!']);
            return;
        }
    }

    require 'src/inc/Feed.php';

    $xmlDoc = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');

    foreach ($urls as $url) {
        $rss = Feed::loadRss($url);
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
        }
    }

    $dom = new DOMDocument("1.0");
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xmlDoc->asXML());
    $status = file_put_contents(ADMIN_FEED_FILE, $dom->saveXML());
    $status = ($status === false ? false : true);

    // return status
    echo json_encode(['status' => $status]);
    return;
}

// Import Posts From CSV
function import_from_csv()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    if (empty($_FILES['csv_file']['tmp_name'])) {
        echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Please Select file to upload!']);
        return;
    }

    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

    if (!in_array($_FILES['csv_file']['type'], $csvMimes)) {
        echo json_encode(['status' => false, 'type' => 'error', 'message' => 'The file is not CSV!']);
        return;
    }

    $handle = fopen($_FILES['csv_file']['tmp_name'], 'r');

    if ($handle === false) {
        echo json_encode(['status' => false, 'type' => 'error', 'message' => 'Could not open file!']);
        return;
    }

    // Load xml document
    $xmlDoc = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');

    while ($record = fgetcsv($handle)) {
        $itemArr = [
            'title' => $record[0],
            'description' => isset($record[1]) ? $record[1] : "",
            'link' => isset($record[2]) ? $record[2] : "",
            'image_url' => isset($record[3]) ? $record[3] : ""
        ];

        $itemExists = false;
        foreach ($xmlDoc->channel->item as $item) {
            if (strtolower($itemArr['title']) === strtolower($item->title))
                $itemExists = true;
        }

        if ($itemExists)
            continue;

        $description = "<a href=\"{$itemArr['link']}\"><img src=\"{$itemArr['image_url']}\"></a> {$itemArr['description']}";

        $newItem = $xmlDoc->channel->addNewItem();
        $newItem->addChild('title', $itemArr['title'] ?? ' ');
        $newItem->addChild('description', $description ?? ' ');
        $newItem->addChild('link', $itemArr['link']);
        $newItem->addChild('guid', $itemArr['link']);
        $newItem->addChild('pubDate', date(DATE_RFC2822, time()));
        $newItem->addChild('approve', 'no');
    }

    fclose($handle);

    $dom = new DOMDocument("1.0");
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xmlDoc->asXML());
    $status = file_put_contents(ADMIN_FEED_FILE, $dom->saveXML());
    $status = ($status === false ? false : true);

    // return status
    echo json_encode(['status' => $status]);
    return;
}

/* Publish Post */
function publish()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    session_start();
    $index = (int) filter_input(INPUT_POST, 'index');
    $moderation_id = (int) $_SESSION['moderation_id'];
    $record = getRecord("moderations", $moderation_id);

    $filePath = "../".$record['rss_path'];
    $publishFilePath = "../".$record['published_rss_path'];

    // Update approval in admin feed
    $adminFeed = simplexml_load_file($filePath, 'my_node');
    if (!isset($adminFeed->channel->item[$index])) {
        // return status
        echo json_encode(['status' => false]);
        return;
    }
    $adminFeed->channel->item[$index]->approve = 'yes';
    if($adminFeed->channel->item[$index]->status){
        $adminFeed->channel->item[$index]->status = 'Approved';
    }else{
        $adminFeed->channel->item[$index]->addChild('status', 'Approved');
    }
    $adminFeed->asXML($filePath );

    // Publish post
    $publishedFeed = simplexml_load_file($publishFilePath, 'my_node');

    // Check if post with this title already exists
    try {
        foreach ($publishedFeed->channel->item as $item) {
            if (strtolower($item->title) === strtolower($adminFeed->channel->item[$index]->title))
                throw new Exception("Post with this title already published");
        }
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'type' => 'error', 'message' => $e->getMessage()]);
        return;
    }
    

    // Store post
    $newItem = $publishedFeed->channel->addNewItem();
    $newItem->addChild('title', $adminFeed->channel->item[$index]->title);
    $newItem->addChild('description', htmlspecialchars(html_entity_decode($adminFeed->channel->item[$index]->description)));
    $newItem->addChild('link', $adminFeed->channel->item[$index]->link);
    $newItem->addChild('guid', $adminFeed->channel->item[$index]->link);
    $newItem->addChild('pubDate', date(DATE_RFC2822, time()));
    $newItem->addChild('approve', 'yes');
    $newItem->addChild('status', 'Approved');
    $dom = new DOMDocument("1.0");
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($publishedFeed->asXML());
    $status = file_put_contents($publishFilePath, $dom->saveXML());
    $status = ($status === false ? false : true);

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

    $data['panel_id'] = $_SESSION['panel_id'];
    $pusher->trigger('my-channel', 'update-feed', $data);

    // return status
    echo json_encode(['status' => $status]);
    return;
}

/* Unapprove post */
function unapprove()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    session_start();
    $index = (int) filter_input(INPUT_POST, 'index');
    $moderation_id = (int) $_SESSION['moderation_id'];
    $record = getRecord("moderations", $moderation_id);

    $filePath = "../".$record['rss_path'];
    $publishFilePath = "../".$record['published_rss_path'];

    // Update approval in admin feed
    $adminFeed = simplexml_load_file($filePath, 'my_node');
    if (!isset($adminFeed->channel->item[$index])) {
        // return status
        echo json_encode(['status' => false]);
        return;
    }
    $adminFeed->channel->item[$index]->approve = 'no';
    if($adminFeed->channel->item[$index]->status){
        $adminFeed->channel->item[$index]->status = 'Pending';
    }else{
        $adminFeed->channel->item[$index]->addChild('status', 'Pending');
    }
    $adminFeed->asXML($filePath);

    // Delete post from publish feed
    $publishedFeed = simplexml_load_file($publishFilePath, 'my_node');
    $i = 0;
    foreach ($publishedFeed->channel->item as $item) {
        if (strtolower($item->title) === strtolower($adminFeed->channel->item[$index]->title)) {
            unset($publishedFeed->channel->item[$i]);
            $publishedFeed->asXML($publishFilePath);
            break;
        }
        ++$i;
    }

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

    $data['panel_id'] = $_SESSION['panel_id'];
    $pusher->trigger('my-channel', 'update-feed', $data);

    echo json_encode(['status' => true]);
}

/* check for updates if any new post added or posts edited */
function check_for_updates()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $posts = isset($_POST['posts']) ? $_POST['posts'] : [];
    $posts_count = count($posts);
    $queue_items = isset($_POST['queue_items']) ? $_POST['queue_items'] : [];
    $rss_urls = isset($_POST['rss_urls']) ? $_POST['rss_urls'] : [];

    session_start();
    $moderation_id = (int) $_SESSION['moderation_id'];
    $record = getRecord("moderations", $moderation_id);
    $feed = new DOMDocument();
    $filePath = "../".$record['rss_path'];
    $feed->load($filePath);
    $count = 0;
    $json = array();
    
    $feed_title = $feed->getElementsByTagName('channel')->item(0)->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
    $items = $feed->getElementsByTagName('channel')->item(0)->getElementsByTagName('item');
    
    $json['updatedPosts'] = array();
    $notesArr = [];
    foreach($items as $index => $item) {
    
       $title = $item->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
       $description = $item->getElementsByTagName('description')->item(0)->firstChild->nodeValue;
       
       $text = $item->getElementsByTagName('description')->item(0)->firstChild->nodeValue;
       $image = dc_get_image($text);

       $approve = $item->getElementsByTagName('approve')->item(0)->firstChild->nodeValue;
	$scheduled = $item->getElementsByTagName('approve')->item(0)->getAttribute('scheduled') === 'yes' ? true : false;
       
      $clear = trim(preg_replace('/ +/', ' ', preg_replace('[^A-Za-z0-9����������]', ' ', urldecode(html_entity_decode(strip_tags($text))))));
       
     
       $link = $item->getElementsByTagName('link')->item(0)->firstChild->nodeValue;  
       @$publishedDate = $item->getElementsByTagName('pubDate')->item(0)->firstChild->nodeValue;

       $notes = null;
	$hasNotes = false;
	foreach ($notesArr as $rec) {
		if ($rec['title'] === $title) {
			$notes = $rec['notes'];
			$hasNotes = true;
		}
	}
       
       $json['updatedPosts'][$count] = array("title"=>$title,"description"=>$clear,
       "link"=>$link,"pubDate"=>$publishedDate,"scheduled"=>$scheduled,"approve"=>$approve,"image_url"=>$image,
       "notes" => $notes, "hasNotes" => $hasNotes, 'index' => $index
    );
       $count++;
    }

    echo json_encode(['status' => true, 'data' => $json]);
}

/* check for new published */
function check_for_published_posts()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $posts = isset($_POST['posts']) && is_array($_POST['posts']) ? $_POST['posts'] : [];
    $posts_count = count($posts);

    $publishFeed = simplexml_load_file(PUBLISHED_FEED_FILE, 'my_node');
    $publishFeed_count = count($publishFeed->channel->item);

    $diff = $publishFeed_count - $posts_count;

    //Get Deleted posts
    if ($publishFeed_count < $posts_count) {
        $deleted_posts = [];
        for ($j = 0; $j < $posts_count; ++$j) {
            $postDeleted = true;
            for ($i = 0; $i < $publishFeed_count; ++$i) {
                if (strtolower($posts[$j]['title']) === strtolower($publishFeed->channel->item[$i]->title)) {
                    $postDeleted = false;
                    break;
                }
            }

            if ($postDeleted)
                $deleted_posts[] = $j;
        }

        $data = [
            'newPosts' => [],
            'deletedPosts' => $deleted_posts
        ];

        echo json_encode(['status' => true, 'data' => $data]);
        return;
    }

    // Get new posts
    $new_posts = [];
    if ($publishFeed_count > $posts_count) {
        for ($i = 0; $i < $diff; ++$i) {
            $link = "#";
            $likarr = ((array) $publishFeed->channel->item[$i]->link[0]);
            if (isset($likarr[0])) {
                $link = $likarr[0];
            }
            $new_posts[] = clear_item([
                'title' => ((array) $publishFeed->channel->item[$i]->title[0])[0],
                'description' => ((array) $publishFeed->channel->item[$i]->description[0])[0],
                'link' =>  $link, //((array) $publishFeed->channel->item[$i]->link[0])[0],
                'pubDate' => ((array) $publishFeed->channel->item[$i]->pubDate[0])[0],
            ]);
        }
    }

    $data = [
        'newPosts' => array_reverse($new_posts),
        'deletedPosts' => []
    ];

    echo json_encode(['status' => true, 'data' => $data]);
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

/* Get queue items from file */
function get_queue_items()
{
    if (!file_exists(QUEUE_FILE)) {
        file_put_contents(QUEUE_FILE, json_encode([]));
    }

    return json_decode(file_get_contents(QUEUE_FILE), true);
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
