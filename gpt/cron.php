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


/** Get Json File Content */
$path = __DIR__ ;

/** Create OpenAI Client */
$key = GPT_KEY;

/** Create JSONDB Object */
$model = new Model();
$records = $model->getAll('gpt_posts',  [ 'Is_Executed' => 0]);  
$records = array_slice($records, 0, QUERY_LIMIT);
$panelClass = new Panel();

$base_url = sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    dirname($_SERVER["REQUEST_URI"])
);
/**
 * Loop Scheduled Jobs and Process them
 * @var  $index
 * @var  $data
 */
if (count($records) > 0) {
    foreach ($records as $index => $data){

        $variant = $records[$index]['Variants'];
        $summarise = $records[$index]['Summarize'];
        $title = $records[$index]['Title'];
        $content = $records[$index]['Content'];

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
            'prompt' => $records[$index]['Image'],
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

        /** Update DB */
        $model->update("gpt_posts", 
        [ 'Searched_Text' => $searched_text, 'Searched_Image_Url' => $searched_image, "Is_Executed" => 1 ], 
        [ 'Id' => $records[$index]['Id']] );

            /** Update Rss */
            $panel = $panelClass->getPenel(['id' =>  $records[$index]['Panel_Id']]);

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

    $data['message'] = 'Event Executed';
    $pusher->trigger('my-channel', 'my-event', $data);

    echo '<div style="color:green"; margin:auto; text-align:center;>Data updated </div>';
} else {
    echo '<div style="color:red"; margin:auto; text-align:center;>Nothing to do</div>';
}

?>