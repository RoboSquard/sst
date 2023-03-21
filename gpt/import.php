<?php
include "../vendor/autoload.php";


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

$case = [
    'blog_idea' => 'Blog Idea &amp; Outline',
    'blog_writing' => 'Blog Section Writing',
    'business_idea' => 'Business Ideas',
    'cover_letter' => 'Cover Letter',
    'social_ads' => 'Facebook, Twitter, Linkedin Ads',
    'google_ads' => 'Google Search Ads',
    'post_idea' => 'Post &amp; Caption Ideas',
    'product_des' => 'Product Description',
    'seo_meta' => 'SEO Meta Description',
    'seo_title' => 'SEO Meta Title',
    'video_des' => 'Video Description',
    'video_idea' => 'Video Idea',
];


/** Get Json File Content */
$path = __DIR__ ;

/** Create OpenAI Client */
$key = GPT_KEY;

$panelClass = new Panel();

$base_url = sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    dirname($_SERVER["REQUEST_URI"])
);


if ($_REQUEST['action'] === 'query'){
    $title = isset($_REQUEST['title']) && !empty($_REQUEST['title']) ? $_REQUEST['title'] : "";
    $content = isset($_REQUEST['content']) && !empty($_REQUEST['content']) ? $_REQUEST['content'] : "";
    $variant = "";
    $image = isset($_REQUEST['image']) && !empty($_REQUEST['image']) ? $_REQUEST['image'] : "";
    $summarise = isset($_REQUEST['summarize']) && !empty($_REQUEST['summarize']) ? $_REQUEST['summarize'] : 0;
    $panel_id = isset($_REQUEST['panel_id']) && !empty($_REQUEST['panel_id']) ? $_REQUEST['panel_id'] : '';

}else{
    $title = isset($_REQUEST['aiKeyword']) && !empty($_REQUEST['aiKeyword']) ? $_REQUEST['aiKeyword'] : "";
    $content = isset($_REQUEST['aiType']) && !empty($_REQUEST['aiType']) ? $case[$_REQUEST['aiType']] : "";
    $variant = isset($_REQUEST['aiVariant']) && !empty($_REQUEST['aiVariant']) ? $_REQUEST['aiVariant'] : 1;
    $image = isset($_REQUEST['aiImage']) && !empty($_REQUEST['aiImage']) ? $_REQUEST['aiImage'] : "";
    $summarise = isset($_REQUEST['aiSummarize']) && !empty($_REQUEST['aiSummarize']) ? $_REQUEST['aiSummarize'] : 0;
    $panel_id = isset($_REQUEST['panel_id']) && !empty($_REQUEST['panel_id']) ? $_REQUEST['panel_id'] : '';
}

if ($variant != '' && $variant != 0) {
    $Nv = (int)$variant;
} else {
    $Nv = 1;
}

if ($summarise != '' && $summarise != 0) {
    $prompt =  $title . "(" . $content . ") in " . $summarise . " paragraphs. ";
} else {
    $prompt =  $title . "(" . $content . ")";
}

$data = array(
    'model' => 'text-davinci-001',
    "prompt" => $prompt,
    "max_tokens" => 500,
    "n" => $Nv,
    "stop" => "",
    "temperature" => 0.5
);
$url = 'https://api.openai.com/v1/completions';


/** Make call to search content */
$searched_text_response = (new makeRequest())->run($data, $url, $key);


if(isset($searched_text_response['error']['message']) && !empty($searched_text_response['error']['message']))
{
    print_r($searched_text_response['error']['message']) ;
    exit();
}

$searched_text = [];
for ($i = 0; $i < $Nv; $i++) {
    array_push($searched_text, $searched_text_response['choices'][$i]['text']);
}
$gptSearchedText = $searched_text;
$searched_text = json_encode($searched_text);

$data = array(
    'prompt' => $image,
    'n' => $Nv,
    'size' => '1024x1024',
    'response_format' => 'url',
);
$url = 'https://api.openai.com/v1/images/generations';

/** Make call to search Image */
$searched_image_response = (new makeRequest())->run($data, $url, $key);

$searched_image = [];
for ($i = 0; $i < $Nv; $i++) {
    $imageName ="/assets/images/".md5(mt_rand(0, 31337) . time()).".jpg";
    $imagePath = $base_url.$imageName;
    file_put_contents($path.$imageName, file_get_contents($searched_image_response['data'][$i]['url']));

    array_push($searched_image, $imagePath);
}
    $gptSearchedImages = $searched_image;
    $searched_image = json_encode($searched_image);


    /** Update Rss */
    $panel = $panelClass->getPenel(['id' =>  $panel_id]);

    $panelName = str_replace(' ', '-', $panel['title']);
    $ip = str_replace('.', '', get_client_ip());
    $ip = str_replace(':', '', $ip);
    $fileName = $panelName . '_'. $ip . '.xml';
    $mainRssFilePath = '../rss_files/' . $fileName;
    $gptRssFilePath = 'rss/' . $fileName;

    if (!file_exists($mainRssFilePath)) {
        $mainDoc = new DOMDocument("1.0", "UTF-8");
        $mainRss = $mainDoc->createElement("rss"); 
        $mainRssNode = $mainDoc->appendChild($mainRss);
        $mainRssNode->setAttribute("version","2.0");

        $mainChannelNode = $mainDoc->importNode($mainDoc->createElement('channel'), true);
        $mainRssNode->appendChild($mainChannelNode);

    } else {
        $mainDoc = new DOMDocument();
        $mainDoc->load($mainRssFilePath);
        $mainChannelNode = $mainDoc->getElementsByTagName('channel');
        $mainChannelNode = $mainChannelNode[0];
    
    }

    if (!file_exists($gptRssFilePath)) {
        $gptDoc = new DOMDocument("1.0", "UTF-8");
        $gptRss = $gptDoc->createElement("rss"); 
        $gptRssNode = $gptDoc->appendChild($gptRss);
        $gptRssNode->setAttribute("version","2.0");

        $gptChannelNode = $gptDoc->createElement('channel');
        $gptChannelNode = $gptRssNode->appendChild($gptChannelNode);

    } else {
        $gptDoc = new DOMDocument();
        $gptDoc->load($gptRssFilePath);
        $gptChannelNode = $gptDoc->getElementsByTagName('channel');
        $gptChannelNode = $gptChannelNode[0];
    
    }

    foreach($gptSearchedText as $in => $description)
    {
        $image = $gptSearchedImages[$in];
        $description = '<img width="100%" src="' . $image . '" />' . $description;

        /** Main Rss Feed */
        $mainItem = $mainDoc->importNode($mainDoc->createElement('item'), true);
        $mainChannelNode->appendChild($mainItem);
        $mainItem->appendChild($mainDoc->importNode($mainDoc->createElement('title', $title), true));
        $mainImageNode = $mainItem->appendChild($mainDoc->importNode($mainDoc->createElement('image'), true));
        $mainImageNode->appendChild($mainDoc->importNode($mainDoc->createElement('url', $image), true));
        $mainImageNode->appendChild($mainDoc->importNode($mainDoc->createElement('link', $image), true));
        $mainImageNode->appendChild($mainDoc->importNode($mainDoc->createElement('title', $title), true));
        $mainItem->appendChild($mainDoc->importNode($mainDoc->createElement('description', $description), true));
        $mainItem->appendChild($mainDoc->importNode($mainDoc->createElement('link', $image), true));
        $mainItem->appendChild($mainDoc->importNode($mainDoc->createElement('pubDate', date(DATE_RFC2822, time())), true));
        $mainItem->appendChild($mainDoc->importNode($mainDoc->createElement('status', 'Pending'), true));
        $mainItem->appendChild($mainDoc->importNode($mainDoc->createElement('approve', 'no'), true));

        /** Gpt Rss Feed */
        $gptItem = $gptDoc->importNode($gptDoc->createElement('item'), true);
        $gptChannelNode->appendChild($gptItem);
        $gptItem->appendChild($gptDoc->importNode($gptDoc->createElement('title', $title), true));
        $gptImageNode = $gptItem->appendChild($gptDoc->importNode($gptDoc->createElement('image'), true));
        $gptImageNode->appendChild($gptDoc->importNode($gptDoc->createElement('url', $image), true));
        $gptImageNode->appendChild($gptDoc->importNode($gptDoc->createElement('link', $image), true));
        $gptImageNode->appendChild($gptDoc->importNode($gptDoc->createElement('title', $title), true));
        $gptItem->appendChild($gptDoc->importNode($gptDoc->createElement('description', $description), true));
        $gptItem->appendChild($gptDoc->importNode($gptDoc->createElement('link', $image), true));
        $gptItem->appendChild($gptDoc->importNode($gptDoc->createElement('pubDate', date(DATE_RFC2822, time())), true));
        $gptItem->appendChild($gptDoc->importNode($gptDoc->createElement('status', 'Pending'), true));
        $gptItem->appendChild($gptDoc->importNode($gptDoc->createElement('approve', 'no'), true));
        $gptItem->appendChild($gptDoc->importNode($gptDoc->createElement('merged', 'yes'), true));

    }

    $mainDoc->save($mainRssFilePath);
    $gptDoc->save($gptRssFilePath);

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
       


?>