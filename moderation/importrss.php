<?php
$receiveddata = array(
    'links' => $_POST["links"],
    'rewrite' => $_POST["rewrite"],
    'sentences' => $_POST["sentences"],
    'newRSS' => $_POST["newRSS"],
    'imageapi' => $_POST["imageapi"],
);

echo json_encode($receiveddata);



require_once 'config.php';
require_once 'src/inc/my_node.php';
require 'src/inc/Feed.php';


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
/**get message summary from api */
function getmessagesummary($message, $sentencenum)
{

    $postRequest = array(
        'key' => '1a8816f5601d3fd6216a8f2c6406d2c6',
        'txt' => $message,

        'sentences' => $sentencenum

    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.meaningcloud.com/summarization-1.0');
    curl_setopt($ch, CURLOPT_POST, 1);
    //In real life you should use something like:
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postRequest));

    // Receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);
    //echo $server_output;
    $summaryjson = json_decode($server_output);

    curl_close($ch);

    $summary = '';


    if ($summaryjson->status->msg == "OK") {
        $summary = $summaryjson->summary;
    }

    return $summary;
}

/**gets keywords from paragraph */
function get_keyword($string)
{
    $array = explode(' ', $string);
    $yeyword = "";
    foreach ($array as $v) {
        if (strlen(trim($v)) > 5) {
            $yeyword = $v;
            break;
        }
    }
    return $yeyword;
}

/**shortens paragraph if too long  >1000 characters as required by api*/
function get_snippet($str, $wordCount = 10)
{
    return implode(
        '',
        array_slice(
            preg_split(
                '/([\s,\.;\?\!]+)/',
                $str,
                $wordCount * 2 + 1,
                PREG_SPLIT_DELIM_CAPTURE
            ),
            0,
            $wordCount * 2 - 1
        )
    );
}

/**saves last url to file */
function savelastrssurl($url)
{
    file_put_contents(RSS_LAST_FILE, $url);
}
/**gets last urls from file*/
function get_last_url()
{
    $file = file_get_contents(RSS_LAST_FILE);
    return trim($file);
}
/**gets next url from file */
function get_next_url()
{
    $next = "";
    $lasturl = trim(get_last_url());
    $currenturls = get_currentrss_urls();
    if (count($currenturls) > 0) {
        //echo "array not empty";
        $next = $currenturls[0];
        if (in_array($lasturl, $currenturls)) {
            // echo "found in array";
            $key = array_search($lasturl, $currenturls);
            $nextkey = $key + 1;
            if (array_key_exists($nextkey, $currenturls)) {
                //echo "key found";
                $next = $currenturls[$nextkey];
            }
        }
    }
    savelastrssurl($next);
    return $next;
}
/**gets current list of rss */
function get_currentrss_urls()
{
    $current = array();
    $filedata = file_get_contents(RSS_URLS_FILE);
    $filearray = explode("\n", $filedata);
    foreach ($filearray as $line) {
        if (!empty(trim($line))) {
            $current[] = trim($line);
        }
    }
    return $current;
}
/**reduces paragraph if too long */
function reducelengthiftoolong($string, $repl, $limit)
{
    if (strlen($string) > $limit) {
        return substr($string, 0, $limit) . $repl;
    } else {
        return $string;
    }
}

// gets images from pixbay
function get_pixabay($imagename)
{
    $api_key = '20774189-c9384e7e1437cface00025056';

    $type = 'photo';
    $endpoint = 'https://pixabay.com/api/';


    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $endpoint . "?key=" . $api_key . "&q=" . $imagename . "&image_type=" . $type . "&page=1");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    $funalresult = '';

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        $funalresult = '';
    } else {
        $resultobject = json_decode($result);
        if ($resultobject->totalHits > 0) {
            $ranindex = array_rand($resultobject->hits);
            $funalresult = $resultobject->hits[$ranindex]->previewURL;
        }
    }
    curl_close($ch);

    echo "<br/><br/> Photo from pixbay (" . $imagename . ") : " . $funalresult;
    return $funalresult;
}



//gets images from pexeles
function get_pexels($imagename)
{


    $postRequest = array(
        'action' => 'pexels',
        'q' => $imagename,
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, BASE_URL . 'src/images/api.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    //In real life you should use something like:
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postRequest));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $funalresult = '';

    $result = curl_exec($ch);

    //echo $result;
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        $funalresult = '';
    } else {

        $resultobject = json_decode($result);
        //echo $result;
        if (count($resultobject->imges) > 0) {
            //echo "jjjjj";
            $ranindex = array_rand($resultobject->imges);
            $funalresult = $resultobject->imges[$ranindex];
        }
    }
    curl_close($ch);

    echo "<br/><br/> Photo from pexels (" . $imagename . ") : " . $funalresult;
    return $funalresult;
}




//gets images from wallhaven
function get_wallhaven($imagename)
{


    $postRequest = array(
        'action' => 'wallhaven',
        'q' => $imagename,
        'page' => '1'
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, BASE_URL . 'src/images/api.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    //In real life you should use something like:
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postRequest));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $funalresult = '';

    $result = curl_exec($ch);

    //echo $result;
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        $funalresult = '';
    } else {

        $resultobject = json_decode($result);
        //echo $result;
        if (count($resultobject->data) > 0) {
            //echo "jjjjj";
            $ranindex = array_rand($resultobject->data);
            $funalresult = $resultobject->data[$ranindex]->thumbs->large;
        }
    }
    curl_close($ch);

    echo "<br/><br/> Photo from walhaven (" . $imagename . ") : " . $funalresult;
    return $funalresult;
}


//gets images from flickr
function get_flicker($imagename)
{


    $postRequest = array(
        'action' => 'flickr',
        'q' => $imagename,
        'page' => '1'
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, BASE_URL . 'src/images/api.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    //In real life you should use something like:
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postRequest));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $funalresult = '';

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        $funalresult = '';
    } else {

        $resultobject = json_decode($result);
        if (count($resultobject) > 0) {
            $ranindex = array_rand($resultobject);
            $funalresult = $resultobject[$ranindex];
        }
    }
    curl_close($ch);

    echo "<br/><br/> Photo from flickr (" . $imagename . ") : " . $funalresult;
    return $funalresult;
}

//gets images from unsplash
function get_unsplash($imagename)
{
    $api_key = 'jyDAm3w60HG2H0vKB--FhrMrsJ5C8ggi2LSoV5Y5Aww';
    $q = 'yellowflowers';
    if (!empty($imagename)) {
        $q = $imagename;
    }
    $type = 'photo';
    $endpoint = 'https://api.unsplash.com/search/photos/';


    $ch = curl_init();
    //echo $endpoint . "?key=" . $api_key . "&query=" . $q . "&client_id=" . $api_key . "&page=1&per_page=50";
    curl_setopt($ch, CURLOPT_URL, ($endpoint . "?key=" . $api_key . "&query=" . $q . "&client_id=" . $api_key . "&page=1&per_page=50"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    $funalresult = '';

    $result = curl_exec($ch);
    //echo $result;

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        $funalresult = '';
    } else {
        $resultobject = json_decode($result);
        if ($resultobject->total > 0) {
            $ranindex = array_rand($resultobject->results);
            $funalresult = $resultobject->results[$ranindex]->urls->thumb;
        }
    }


    echo "<br/><br/> Photo from unsplash (" . $imagename . ") : " . $funalresult;
    return $funalresult;

    curl_close($ch);
}
// selects the imagesource
function get_image($imagesource, $keyword)
{
    $resultimage = '';
    switch ($imagesource) {
        case "unsplash":
            $resultimage = get_unsplash($keyword);
            break;
        case "pixbay":
            $resultimage = get_pixabay($keyword);
            break;
        case "pexeles":
            $resultimage = get_pexels($keyword);
            break;
        case "walhaven":
            $resultimage = get_wallhaven($keyword);
            break;
        case "flickr":
            $resultimage = get_flicker($keyword);
            break;
        default:
            $resultimage = get_unsplash($keyword);
    }
    return $resultimage;
}


/******************************* 
 * 
 * START THE CRON JOB
 * 
/******************************** */

/**GET NEXT URL */

$removedurl = $receiveddata["newRSS"];
/** DISPLAY THE NEXT URL */
echo  "Queue url : <a href='" . ($removedurl) . "' target='_blank' >" . ($removedurl) . "</a>";
echo "<br /><br/>";



/**load current content on the Admin Feed file */

$xmlDoc = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');


/* create and display theurl for refined RSS output */

$url = BASE_URL . 'src/plugins/rsscontentspinner/go.php?url=' . urlencode($removedurl) . '&key=0&hash=19012e6cf0bf71db975de14a720685963ec78105&links=remove&rewrite=' . $receiveddata["rewrite"];
echo "Access-Url : <a href='" . $url . "' target='_blank' >" . $url . "</a>";

/** load content on from the refined RSS output link */
$rss = Feed::loadRss($url);


/**loop through the link adding content to the admin file content loaded */

foreach ($rss->item as $newItem) {
    $itemExists = false;
    /** filter for similar titles */
    foreach ($xmlDoc->channel->item as $item) {
        if (strtolower($newItem->title) === strtolower($item->title))
            $itemExists = true;
    }
    if ($itemExists)
        continue;

    //get description from xml tag
    $descriptionRaw = html_entity_decode($newItem->description, ENT_XML1);
    // get first image from description
    $firstimage = preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $descriptionRaw, $image);
    //get text descritption without html tags
    $textdescription = strip_tags($descriptionRaw);
    // truncate description to <1000 characters for the summary api to work
    $truncatedtext = get_snippet($textdescription, 800);
    //get summary from api
    $summarizedmessage = getmessagesummary($truncatedtext, $receiveddata["sentences"]);

    // if summary is too long reduce length to <200 characters


    // create data object 
    $mydata = array(
        'title' => htmlentities($newItem->title ?? ' ', ENT_XML1),
        'image' => ($image["src"] ?? "noimage"),
        'guid' => $newItem->guid ?? ' ',
        'link' => $newItem->link ?? ' ',
        'pubd' => $newItem->pubDate ?? ' ',
        //'desc' => reducelengthiftoolong(htmlentities($summarizedmessage, ENT_XML1), '...', 200),
        'desc' => htmlentities($summarizedmessage, ENT_XML1),
    );


    //check if item has image

    if ($mydata['image'] == 'noimage') {
        // if no image get keyword and get image from unsplash
        $keyword = get_keyword($summarizedmessage);
        $unsplashphoto = get_image($receiveddata["imageapi"], $keyword);
        if ($keyword !== '' && $unsplashphoto !== '') {
            $mydata["desc"] =  htmlentities('<img src="' . $unsplashphoto . '" />', ENT_XML1) . $mydata["desc"];
        }
    } else {
        $mydata["desc"] =  htmlentities('<img src="' . $mydata['image'] . '" />', ENT_XML1) . $mydata["desc"];
    }

    // add item to xml content loaded from adminfeed file
    $item = $xmlDoc->channel->addNewItem();
    $item->addChild('title', $mydata['title']);
    $item->addChild('description', $mydata['desc']);
    $item->addChild('link', $mydata['link']);
    $item->addChild('guid', $mydata['guid']);
    $item->addChild('pubDate', $mydata['pubd']);
    $item->addChild('approve', 'no');

    echo ("<br/><hr><br/>");
}

//create new xml document content
$dom = new DOMDocument("1.0");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xmlDoc->asXML());
// save to admin feed file
$status = file_put_contents(ADMIN_FEED_FILE, $dom->saveXML());
$status = ($status === false ? false : true);

//output the status when done
echo "<br/><br/>";
echo json_encode(['status' => $status]);

/******************************* 
 * CRON JOB DONE
/******************************* */
header("Location: " . BASE_URL);
