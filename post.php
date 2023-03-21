<?php
error_reporting('1');	
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'helper.php';
include_once('model/model.php');
require_once 'model/panel.php';
require_once 'model/group.php';
require_once 'model/panelsetting.php';
require_once 'model/defaultpanelsetting.php';
require_once 'model/users.php';
require_once 'config.php';  
require_once 'full_text_feed.php';  
require_once 'model/Feed.php';
require_once 'model/Note.php';

$action = $_REQUEST['action'];

if (function_exists($action)) {
    $action();
} else {
    header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
    die();
}

/* Add post to feed */
function create()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    // Get input data
    $panel_id = (int) filter_input(INPUT_POST, 'panel_id');
    $image_url = filter_input(INPUT_POST, 'image_url');
    $title = filter_input(INPUT_POST, 'title');
    $description = filter_input(INPUT_POST, 'description');
    $link = filter_input(INPUT_POST, 'link');
    $notes = filter_input(INPUT_POST, 'notes');

    try {
        $feed = new Feed();
        $feed->createOrLoadPanelFeed($panel_id);
        $feed->addItem(compact('title', 'description', 'image_url', 'link'));
        $status = $feed->save();
        
    } catch(Exception $e) {
        echo json_encode(['status' => false, 'type' => 'error', 'message' => $e->getMessage()]);
        return;
    }
    
    // Store notes in database
    if ($notes) {
        $noteModel = new Note();
        $noteModel->storeData([
            'panel_id' => $panel_id,
            'post_title' => $title,
            'note' => $notes
        ]);
    }

    $createdPost = [];
    if ($status) {
        $createdPost = [
            'title' => $title,
            'description' => $description,
            'image_url' => $image_url,
            'link' => $link,
            'notes' => $notes,
            'pubDate' => date(DATE_RFC2822, time()),
            'hasNotes' => ($notes? true: false),
            'panel_id' => $panel_id
        ];
    }

    // return status
    echo json_encode(['status' => $status, 'data' => $createdPost]);
    return;
}

/* Update post in feed */
function update()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }
    // Get input data
    $panel_id = (int) filter_input(INPUT_POST, 'panel_id');
    $current_title = filter_input(INPUT_POST, 'current_title');
    $image_url = filter_input(INPUT_POST, 'image_url');
    $title = filter_input(INPUT_POST, 'title');
    $description = filter_input(INPUT_POST, 'description');
    $link = filter_input(INPUT_POST, 'link');
    $notes = filter_input(INPUT_POST, 'notes');

    try {
        $feed = new Feed();
        $feed->createOrLoadPanelFeed($panel_id);
        $feed->updateItem($current_title, compact('title', 'description', 'image_url', 'link'));
        $status = $feed->save();
        
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
        $updatedPost = [
            'title' => $title,
            'description' => $description,
            'image_url' => $image_url,
            'link' => $link,
            'notes' => $notes,
            'pubDate' => date(DATE_RFC2822, time()),
            'hasNotes' => ($notes? true: false),
            'panel_id' => $panel_id
        ];
    }

    // return status and updated post
    echo json_encode(['status' => $status, 'data' => $updatedPost]);
    return;
}

/* Delete post from rss feed */
function delete()
{
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $panel_id = (int) filter_input(INPUT_POST, 'panel_id');
    $title = filter_input(INPUT_POST, 'title');

    $feed = new Feed();
    $feed->createOrLoadPanelFeed($panel_id);
    $feed->saveDeletedItem($title);
    $feed->deleteItem($title);
    $status = $feed->save();

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

    require 'include/Feed.php';

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
            $item->addChild('title', htmlentities($newItem->title?? ' ', ENT_XML1));
            $item->addChild('description', htmlentities($newItem->description?? ' ', ENT_XML1));
            $item->addChild('link', ' ');
            $item->link = $newItem->link?? ' ';
            $item->addChild('guid', ' ');
            $item->guid = $newItem->link?? ' ';
            $item->addChild('pubDate', $newItem->pubDate?? ' ');
            $item->addChild('approve', 'no');
        }
    }

    $dom = new DOMDocument("1.0");
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xmlDoc->asXML());
    $status = file_put_contents(ADMIN_FEED_FILE, $dom->saveXML());
    $status = ($status === false? false: true);

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
            'description' => $record[1],
            'link' => $record[2],
            'image_url' => $record[3]
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
        $newItem->addChild('title', $itemArr['title']?? ' ');
        $newItem->addChild('description', $description?? ' ');
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
    $status = ($status === false? false: true);

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

    $index = (int) filter_input(INPUT_POST, 'index');

    // Update approval in admin feed
    $adminFeed = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');
    if (!isset($adminFeed->channel->item[$index])) {
        // return status
        echo json_encode(['status' => false]);
        return;
    }
    $adminFeed->channel->item[$index]->approve = 'yes';
    $adminFeed->asXML(ADMIN_FEED_FILE);

    // Publish post
    $publishedFeed = simplexml_load_file(PUBLISHED_FEED_FILE, 'my_node');

    // Check if post with this title already exists
    try {
        foreach ($publishedFeed->channel->item as $item) {
            if (strtolower($item->title) === strtolower($adminFeed->channel->item[$index]->title))
                throw new Exception("Post with this title already published");
        }
    } catch(Exception $e) {
        echo json_encode(['status' => false, 'type' => 'error', 'message' => $e->getMessage()]);
        return;
    }

    // Store post
    $newItem = $publishedFeed->channel->addNewItem();
    $newItem->addChild('title', $adminFeed->channel->item[$index]->title);
    $newItem->addChild('description', $adminFeed->channel->item[$index]->description);
    $newItem->addChild('link', $adminFeed->channel->item[$index]->link);
    $newItem->addChild('guid', $adminFeed->channel->item[$index]->link);
    $newItem->addChild('pubDate', date(DATE_RFC2822, time()));
    $newItem->addChild('approve', 'yes');
    $dom = new DOMDocument("1.0");
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($publishedFeed->asXML());
    $status = file_put_contents(PUBLISHED_FEED_FILE, $dom->saveXML());
    $status = ($status === false? false: true);


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

    $index = (int) filter_input(INPUT_POST, 'index');

    // Update approval in admin feed
    $adminFeed = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');
    if (!isset($adminFeed->channel->item[$index])) {
        // return status
        echo json_encode(['status' => false]);
        return;
    }
    $adminFeed->channel->item[$index]->approve = 'no';
    $adminFeed->asXML(ADMIN_FEED_FILE);

    // Delete post from publish feed
    $publishedFeed = simplexml_load_file(PUBLISHED_FEED_FILE, 'my_node');
    $i = 0;
    foreach ($publishedFeed->channel->item as $item) {
        if (strtolower($item->title) === strtolower($adminFeed->channel->item[$index]->title)) {
            unset($publishedFeed->channel->item[$i]);
            $publishedFeed->asXML(PUBLISHED_FEED_FILE);
            break;
        }
        ++$i;
    }

    echo json_encode(['status' => true]);
}

/* check for updates if any new post added or posts edited */
function check_for_updates()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $posts = isset($_POST['posts'])? $_POST['posts']: [];
    $posts_count = count($posts);
    $queue_items = isset($_POST['queue_items'])? $_POST['queue_items']: [];
    $rss_urls = isset($_POST['rss_urls'])? $_POST['rss_urls']: [];

    $adminFeed = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');
    $adminFeed_count = count($adminFeed->channel->item);

    $diff = $adminFeed_count - $posts_count;

    // Get post notes Array
    $notesArr = json_decode(file_get_contents(NOTES_FILE), true);

    //Get Deleted posts
    if ($adminFeed_count < $posts_count) {
        $deleted_posts = [];
        for ($j = 0; $j < $posts_count; ++$j) {
            $postDeleted = true;
            for ($i = 0; $i < $adminFeed_count; ++$i) {
                // Retrieve post notes
                $notes = null;
                $hasNotes = false;
                foreach ($notesArr as $rec) {
                    if ($rec['title'] === ((array) $adminFeed->channel->item[$i]->title[0])[0]) {
                        $notes = $rec['notes'];
                        $hasNotes = true;
                    }
                }
                $feedPost = clear_item([
                    'title' => ((array) $adminFeed->channel->item[$i]->title[0])[0],
                    'description' => ((array) $adminFeed->channel->item[$i]->description[0])[0],
                    'link' => ((array) $adminFeed->channel->item[$i]->link[0])[0],
                    'pubDate' => ((array) $adminFeed->channel->item[$i]->pubDate[0])[0],
                    'approve' => ((array) $adminFeed->channel->item[$i]->approve[0])[0],
                    'notes' => $notes,
                    'hasNotes' => $hasNotes
                ]);
        
                $posts[$j]['hasNotes'] = (bool) $posts[$j]['hasNotes'];
        
                if ($posts[$j] == $feedPost) {
                    $postDeleted = false;
                    break;
                }
            }

            if ($postDeleted)
                $deleted_posts[] = $j;
        }

        $data = [
            'newPosts' => [],
            'updatedPosts' => [],
            'deletedPosts' => $deleted_posts,
            'updatedRssUrls' => []
        ];
    
        echo json_encode(['status' => true, 'data' => $data]);
        return;
    }

    // Get new posts
    $new_posts = [];
    if ($adminFeed_count > $posts_count) {
        for ($i = 0; $i < $diff; ++$i) {
            // Retrieve post notes
            $notes = null;
            $hasNotes = false;
            foreach ($notesArr as $rec) {
                if ($rec['title'] === ((array) $adminFeed->channel->item[$i]->title[0])[0]) {
                    $notes = $rec['notes'];
                    $hasNotes = true;
                }
            }
            $new_posts[] = clear_item([
                'title' => ((array) $adminFeed->channel->item[$i]->title[0])[0],
                'description' => ((array) $adminFeed->channel->item[$i]->description[0])[0],
                'link' => ((array) $adminFeed->channel->item[$i]->link[0])[0],
                'pubDate' => ((array) $adminFeed->channel->item[$i]->pubDate[0])[0],
                'approve' => ((array) $adminFeed->channel->item[$i]->approve[0])[0],
                'notes' => $notes,
                'hasNotes' => $hasNotes,
                'scheduled' => (string) $adminFeed->channel->item[$i]->approve->attributes()[0] === 'yes'? true: false
            ]);
        }
    }

    // Get updated posts
    $updated_posts = [];
    for ($i = $diff, $j = 0; $i < $adminFeed_count; ++$i, ++$j) {
        // Retrieve post notes
        $notes = null;
        $hasNotes = false;
        foreach ($notesArr as $rec) {
            if ($rec['title'] === ((array) $adminFeed->channel->item[$i]->title[0])[0]) {
                $notes = $rec['notes'];
                $hasNotes = true;
            }
        }
        $feedPost = clear_item([
            'title' => ((array) $adminFeed->channel->item[$i]->title[0])[0],
            'description' => ((array) $adminFeed->channel->item[$i]->description[0])[0],
            'link' => ((array) $adminFeed->channel->item[$i]->link[0])[0],
            'pubDate' => ((array) $adminFeed->channel->item[$i]->pubDate[0])[0],
            'approve' => ((array) $adminFeed->channel->item[$i]->approve[0])[0],
            'notes' => $notes,
            'hasNotes' => $hasNotes
        ]);

        $posts[$j]['hasNotes'] = (bool) $posts[$j]['hasNotes'];

        if ($posts[$j] != $feedPost) {
            $feedPost['index'] = $i;
            $feedPost['scheduled'] = (string) $adminFeed->channel->item[$i]->approve->attributes()[0] === 'yes'? true: false;
            $updated_posts[] = $feedPost;
        }
    }

    // Check if queue items have changed
    $updated_queue_items = [];
    $file_queue_items = get_queue_items();
    if (count($file_queue_items) !== count($queue_items)) {
        $updated_queue_items = $file_queue_items;
    } else {
        foreach ($file_queue_items as $i => $item) {
            $queue_items[$i]['published'] = $queue_items[$i]['published'] === 'true'? true: false;
            if ($item['published'] !== $queue_items[$i]['published']) {
                $updated_queue_items = $file_queue_items;
                break;
            }
        }
    }

    // Check if new rss urls added or deleted
    $updated_rss_urls = [];
    $urls = get_rss_urls();
    if (count($rss_urls) !== count($urls))
        $updated_rss_urls = $urls;
    else
        $updated_rss_urls = null;

    $data = [
        'newPosts' => array_reverse($new_posts),
        'updatedPosts' => $updated_posts,
        'updatedQueueItems' => $updated_queue_items,
        'updatedRssUrls' => $updated_rss_urls,
    ];

    echo json_encode(['status' => true, 'data' => $data]);
}

/* check for new published */
function check_for_published_posts()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $posts = isset($_POST['posts']) && is_array($_POST['posts'])? $_POST['posts']: [];
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
            $new_posts[] = clear_item([
                'title' => ((array) $publishFeed->channel->item[$i]->title[0])[0],
                'description' => ((array) $publishFeed->channel->item[$i]->description[0])[0],
                'link' => ((array) $publishFeed->channel->item[$i]->link[0])[0],
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