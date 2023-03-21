<?php

//echo exec('whoami');


require_once 'config.php';
require_once 'src/inc/my_node.php';
//require 'src/inc/Feed.php';




/* Get urls from file */
function get_rss_urls2()
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
        'key' => SUMARY_API_KEY,
        'txt' => $message,

        'sentences' => $sentencenum,

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

    if (isset($summaryjson->status)) {
        if ($summaryjson->status->msg == "OK") {
            $summary = $summaryjson->summary;
        }
    }
    return $summary;
}

/**gets keywords from paragraph */
function get_keyword($string)
{
    $blacklistedwords = file_get_contents(BLACK_LISTED_WORDS_LIST);
    $blacklistedwordsarray = json_decode($blacklistedwords);
    $array = explode(' ', $string);
    $yeyword = "";
    foreach ($array as $v) {
        if (strlen(trim($v)) > 3 && !in_array($v, $blacklistedwordsarray)) {
            $yeyword = $v;
            break;
        }
    }

    sort_blacklist();
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

        function myfunction($v)
        {
            return ($v->newRSS);
        }

        $sortarray = array_map("myfunction", $currenturls);

        $next = $currenturls[0];
        if (in_array($lasturl, $sortarray)) {
            // echo "found in array";
            $key = array_search($lasturl, $sortarray);
            $nextkey = $key + 1;
            if (array_key_exists($nextkey, $currenturls)) {
                //echo "key found";
                $next = $currenturls[$nextkey];
            }
        }
    }
    if ($next != "") {
        savelastrssurl($next->newRSS);
    }
    return $next;
}
/**gets current list of rss */
function get_currentrss_urls()
{

    $filedata = file_get_contents(RSS_AUTOMATIC_LIST);

    return json_decode($filedata);
}
/**reduces paragraph if too long */
function reducelengthiftoolong($string, $repl, $limit)
{
    $exl = explode(' ', $string);
    if ($limit == "0" || $limit == 0) {
        return $string;
    }
    if (count($exl) <= (int)$limit) {
        return $string;
    }

    return implode(' ', (array_slice($exl, 0, (int)$limit))) . $repl;
}

// gets images from pixbay
function get_pixabay($imagename)
{
    $api_key = PIXBAY_API_KEY;

    $type = 'photo';
    $endpoint = 'https://pixabay.com/api/';


    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $endpoint . "?key=" . $api_key . "&q=" . $imagename . "&image_type=" . $type . "&page=1");
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
        if ($resultobject->totalHits > 0) {

            $ranindex = array_rand($resultobject->hits);

            $funalresult = $resultobject->hits[$ranindex]->largeImageURL;

            // echo json_encode($resultobject->hits[$ranindex], JSON_PRETTY_PRINT);
        }
    }
    curl_close($ch);

    // echo "<br/><br/> Photo from pixbay (" . $imagename . ") : " . $funalresult;
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

    echo $result;
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        $funalresult = '';
    } else {

        $resultobject = json_decode($result);
        //echo $resultobject;
        if (count($resultobject->imges) > 0) {
            //echo "jjjjj";
            $ranindex = array_rand($resultobject->imges);
            $funalresult = $resultobject->imges[$ranindex];
        }
    }
    curl_close($ch);

    //echo "<br/><br/> Photo from pexels (" . $imagename . ") : " . $funalresult;
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
            $funalresult = $resultobject->data[$ranindex]->path; //->large;
        }
    }
    curl_close($ch);

    //echo "<br/><br/> Photo from walhaven (" . $imagename . ") : " . $funalresult;
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
    //echo $result;
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

    //echo "<br/><br/> Photo from flickr (" . $imagename . ") : " . $funalresult;
    return $funalresult;
}

//gets images from unsplash
function get_unsplash($imagename)
{
    $api_key = UNSPLASH_API_KEY;
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
            $funalresult = $resultobject->results[$ranindex]->urls->full;
        }
    }


    //echo "<br/><br/> Photo from unsplash (" . $imagename . ") : " . $funalresult;
    return $funalresult;

    curl_close($ch);
}

// selects the imagesource
function get_image($imagesource, $keyword)
{
    ///echo $imagesource;
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
        case "freepik":
            $resultimage = get_freepik($keyword);
            break;
        case "instagram":
            $resultimage = get_instagram($keyword);
            break;

        default:
            $resultimage = get_unsplash($keyword);
    }
    return $resultimage;
}


function runnewcron()
{
    /******************************* 
     * 
     * START THE CRON JOB
     * 
    /******************************** */
    /**get default values */

    $receiveddata = json_decode(file_get_contents(RSS_DEFAULT_VALUES));

    /**GET NEXT URL */
    $nexturl = get_next_url();
    if ($nexturl != "") {

        $receiveddata->links = $nexturl->links;
        $receiveddata->rewrite = $nexturl->rewrite;
        $receiveddata->sentences = $nexturl->sentences;
        $receiveddata->truncate = $nexturl->truncate;
        $receiveddata->imageapi = $nexturl->imageapi;
        $receiveddata->hashtags = $nexturl->hashtags;


        $removedurl = $nexturl->newRSS;
        /** DISPLAY THE NEXT URL */
        $queueurl = "Queue url : <a href='" . ($removedurl) . "' target='_blank' >" . ($removedurl) . "</a>";
        //echo "<br /><br/>";



        /**load current content on the Admin Feed file */

        $xmlDoc = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');


        /* create and display theurl for refined RSS output */

        $url = BASE_URL . 'src/plugins/rsscontentspinner/go.php?url=' . urlencode($removedurl) . '&key=0&hash=19012e6cf0bf71db975de14a720685963ec78105&links=remove&rewrite=' . $receiveddata->rewrite;
        $accessurl = $url; //"Access-Url : <a href='" . $url . "' target='_blank' >" . $url . "</a>";
        //require 'src/inc/Feed.php';
        /** load content on from the refined RSS output link */
        // $rss = Feed::loadRss($url);
        $rss = simplexml_load_file($url);
        $namespaces = $rss->getNamespaces(true);

        /**loop through the link adding content to the admin file content loaded */
        $counter = 0;
        $deleted_posts = json_decode(file_get_contents(DELETED_POSTS_FILE));
        $lowerdeleted = array();
        foreach ($deleted_posts as $deleted) {
            $lowerdeleted[] = strtolower($deleted);
        }

        foreach ($rss->channel->item as $newItem) {

            /** filter for similar titles **/
            $itemExists = false;
            foreach ($xmlDoc->channel->item as $item) {
                if (
                    in_array(strtolower($newItem->title), $lowerdeleted) ||
                    strtolower($newItem->title) === strtolower($item->title) ||
                    trim($newItem->description) === "" ||
                    strtolower($newItem->description) === strtolower($item->description)
                ) {
                    // if (in_array(strtolower($newItem->title), $lowerdeleted) || strtolower($newItem->title) === strtolower($item->title) || strtolower($newItem->description) === strtolower($item->description)) {
                    $itemExists = true;
                    //echo "exists";
                } else {
                    // echo "never";
                }
            }

            if ($itemExists || $counter >= MAXPOSTSPERIMPORT)
                continue;
            // echo json_encode($newItem);
            // echo "<hr>";
            //get description from xml tag
            $descriptionRaw = html_entity_decode($newItem->description, ENT_XML1);
            //echo $descriptionRaw;
            // get first image from description
            $firstimage = preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $descriptionRaw, $image);
            //get text descritption without html tags
            $textdescription = strip_tags($descriptionRaw);
            // truncate description to <1000 characters for the summary api to work
            $truncatedtext = get_snippet($textdescription, 800);
            //get summary from api
            $summarizedmessage = getmessagesummary($truncatedtext, $receiveddata->sentences);
            if (trim($summarizedmessage) == "")
                continue;

            if (isset($newItem->{'media:content'})) {
                //echo json_encode($newItem->{'media:content'}) . "\n";
            }
            // if summary is too long reduce length to <200 characters

            $dsc = htmlentities($summarizedmessage, ENT_XML1);
            if ($receiveddata->truncate != "0") {
                $dsc = reducelengthiftoolong(htmlentities($summarizedmessage, ENT_XML1), '...', ((int)$receiveddata->truncate));
            }

            // create data object 
            $mydata = array(
                'title' => isset($newItem->title) ? htmlentities($newItem->title, ENT_XML1) : "",
                'image' => isset($image["src"]) ? $image["src"] : "noimage",
                'guid' => isset($newItem->guid) ? $newItem->guid : '',
                'link' => isset($newItem->link) ? $newItem->link : '',
                'pubd' => isset($newItem->pubDate) ? $newItem->pubDate : '',
                'desc' =>  $dsc,


            );
            /*
                // if ($newItem->link)
                if (isset($namespaces['media'])) {
                    $media_content = $newItem->children($namespaces['media']);
                    $imageAlt = '';
                    foreach ($media_content as $i) {
                        $imageAlt = (string)$i->attributes()->url;
                    }
                    if ($mydata['image'] == 'noimage' &&  $imageAlt != '') {
                        $mydata['image'] = $imageAlt;
                    }
                }
                */
            $instahash = "";
            $imgind = array_rand($receiveddata->imageapi);
            $imagesourceselect = $receiveddata->imageapi[$imgind];
            if ($imagesourceselect == "instagram") {
                $instahash = get_keyword($newItem->title);
            }


            if ($mydata['image'] == 'noimage') {
                // if no image get keyword and get image from unsplash
                if ($imagesourceselect == "instagram") {
                    $keyword = $instahash;
                } else {
                    $keyword = get_keyword($summarizedmessage);
                }
                $unsplashphoto = get_image($imagesourceselect, $keyword);
                if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {

                    foreach ($receiveddata->imageapi as $imageapi) {
                        if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {
                            $unsplashphoto = get_image($imageapi, $keyword);
                        }
                    }
                }

                if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {

                    $wordsarray = explode(' ', $summarizedmessage);

                    foreach ($wordsarray as $keyword) {
                        foreach ($receiveddata->imageapi as $imageapi) {
                            if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {
                                $unsplashphoto = get_image($imageapi, $keyword);
                            }
                        }
                    }
                }



                if ($keyword != '' && $unsplashphoto != '' && $unsplashphoto != BASE_URL . 'proxyfile.php?url=') {
                    $mydata["desc"] =  htmlentities('<img crossorigin="anonymous" src="' . $unsplashphoto . '" />', ENT_XML1) . $mydata["desc"];
                }
            } else {
                $mydata["desc"] =  htmlentities('<img  crossorigin="anonymous" src="' . $mydata['image'] . '" />', ENT_XML1) . $mydata["desc"];
            }


            $item = $xmlDoc->channel->addNewItem();
            $item->addChild('title', $mydata['title']);
            $item->addChild('description', $mydata['desc'] . "&lt;br&gt;&lt;br&gt;" . $receiveddata->hashtags . '' . $instahash);
            $item->addChild('link', htmlspecialchars($mydata['link']));
            $item->addChild('guid', $mydata['guid']);
            $item->addChild('pubDate', $mydata['pubd']);
            $item->addChild('approve', 'no');

            //echo json_encode($mydata);
            $counter++;
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
        //echo "<br/><br/>";
        // echo json_encode(['status' => $status, 'accessurl' => $accessurl, 'queueurl' => $queueurl]);
        echo "<br>($counter posts imported) -  $accessurl<br>";

        /******************************* 
         * CRON JOB DONE
            /******************************* */
    } else {
        echo ("NO URL IN QUEUE");
    }
}
//runnewcron();
function runcron_with_values($nexturl)
{
    /******************************* 
     * 
     * START THE CRON JOB
     * 
    /******************************** */
    /**get default values */

    $receiveddata = json_decode(file_get_contents(RSS_DEFAULT_VALUES));

    /**GET NEXT URL */
    //$nexturl = get_next_url();
    if (!empty($nexturl)) {

        $receiveddata->links = $nexturl["links"];
        $receiveddata->rewrite = $nexturl["rewrite"];
        $receiveddata->sentences = $nexturl["sentences"];
        $receiveddata->imageapi = $nexturl["imageapi"];
        $receiveddata->truncate = $nexturl["truncate"];
        $receiveddata->hashtags = $nexturl["hashtags"];
    }
    $removedurl = $nexturl["newRSS"];
    /** DISPLAY THE NEXT URL */
    $queueurl =  "Queue url : <a href='" . ($removedurl) . "' target='_blank' >" . ($removedurl) . "</a>";
    //echo "<br /><br/>";



    /**load current content on the Admin Feed file */

    $xmlDoc = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');


    /* create and display theurl for refined RSS output */

    $url = BASE_URL . 'src/plugins/rsscontentspinner/go.php?url=' . urlencode($removedurl) . '&key=0&hash=19012e6cf0bf71db975de14a720685963ec78105&links=remove&rewrite=' . $receiveddata->rewrite;
    $accessurl = "Access-Url : <a href='" . $url . "' target='_blank' >" . $url . "</a>";
    //require 'src/inc/Feed.php';
    /** load content on from the refined RSS output link */
    //$rss = Feed::loadRss($url);
    $rss = simplexml_load_file($url);
    $namespaces = $rss->getNamespaces(true);

    $counter = 0;
    $deleted_posts = json_decode(file_get_contents(DELETED_POSTS_FILE));
    $lowerdeleted = array();
    foreach ($deleted_posts as $deleted) {
        $lowerdeleted[] = strtolower($deleted);
    }
    /**loop through the link adding content to the admin file content loaded */
    foreach ($rss->channel->item as $newItem) {
        /** filter for similar titles **/
        $itemExists = false;
        foreach ($xmlDoc->channel->item as $item) {
            if (
                in_array(strtolower($newItem->title), $lowerdeleted) ||
                strtolower($newItem->title) === strtolower($item->title) ||
                trim($newItem->description) === "" ||
                strtolower($newItem->description) === strtolower($item->description)
            ) {
                $itemExists = true;
                //echo "exists";
            } else {
                //	echo "never";
            }
        }

        if ($itemExists || $counter >= MAXPOSTSPERIMPORT)
            continue;

        //get description from xml tag
        $descriptionRaw = html_entity_decode($newItem->description, ENT_XML1);
        //echo $descriptionRaw;
        // get first image from description
        $firstimage = preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $descriptionRaw, $image);
        //get text descritption without html tags
        $textdescription = strip_tags($descriptionRaw);
        // truncate description to <1000 characters for the summary api to work
        $truncatedtext = get_snippet($textdescription, 800);
        //get summary from api
        $summarizedmessage = getmessagesummary($truncatedtext, $receiveddata->sentences);
        if (trim($summarizedmessage) == "")
            continue;

        $dsc = htmlentities($summarizedmessage, ENT_XML1);
        if ($receiveddata->truncate != "0") {
            $dsc = reducelengthiftoolong(htmlentities($summarizedmessage, ENT_XML1), '...', ((int)$receiveddata->truncate));
        }

        // create data object 
        $mydata = array(
            'title' => isset($newItem->title) ? htmlentities($newItem->title, ENT_XML1) : "",
            'image' => isset($image["src"]) ? $image["src"] : "noimage",
            'guid' => isset($newItem->guid) ? $newItem->guid : '',
            'link' => isset($newItem->link) ? $newItem->link : '',
            'pubd' => isset($newItem->pubDate) ? $newItem->pubDate : '',
            'desc' =>  $dsc,


        );
        /*

        if (isset($namespaces['media'])) {
            $media_content = $newItem->children($namespaces['media']);
            $imageAlt = '';
            foreach ($media_content as $i) {
                $imageAlt = (string)$i->attributes()->url;
            }
            if ($mydata['image'] == 'noimage' &&  $imageAlt != '') {
                $mydata['image'] = $imageAlt;
            }
        }
        */


        $instahash = "";
        $imgind = array_rand($receiveddata->imageapi);
        $imagesourceselect = $receiveddata->imageapi[$imgind];
        if ($imagesourceselect == "instagram") {
            $instahash = get_keyword($newItem->title);
        }



        if ($mydata['image'] == 'noimage') {
            // if no image get keyword and get image from unsplash
            if ($imagesourceselect == "instagram") {
                $keyword = $instahash;
            } else {
                $keyword = get_keyword($summarizedmessage);
            }
            $unsplashphoto = get_image($imagesourceselect, $keyword);

            if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {

                foreach ($receiveddata->imageapi as $imageapi) {
                    if ($unsplashphoto == '') {
                        $unsplashphoto = get_image($imageapi, $keyword);
                    }
                }
            }
            if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {

                $wordsarray = explode(' ', $summarizedmessage);

                foreach ($wordsarray as $keyword) {
                    foreach ($receiveddata->imageapi as $imageapi) {
                        if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {
                            $unsplashphoto = get_image($imageapi, $keyword);
                        }
                    }
                }
            }


            if ($keyword != '' && $unsplashphoto != '' && $unsplashphoto != BASE_URL . 'proxyfile.php?url=') {
                $mydata["desc"] =  htmlentities('<img crossorigin="anonymous" src="' . $unsplashphoto . '" />', ENT_XML1) . $mydata["desc"];
            }
        } else {
            $mydata["desc"] =  htmlentities('<img crossorigin="anonymous" src="' . $mydata['image'] . '" />', ENT_XML1) . $mydata["desc"];
        }


        $item = $xmlDoc->channel->addNewItem();
        $item->addChild('title', $mydata['title']);
        $item->addChild('description', $mydata['desc'] . "&lt;br&gt;&lt;br&gt;" . $receiveddata->hashtags . '' . $instahash);
        $item->addChild('link', htmlspecialchars($mydata['link']));
        $item->addChild('guid', $mydata['guid']);
        $item->addChild('pubDate', $mydata['pubd']);
        $item->addChild('approve', 'no');

        //echo json_encode($mydata);

        $counter++;
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
    // echo "<br/><br/>";
    echo json_encode(['status' => $status, 'accessurl' => $accessurl, 'queueurl' => $queueurl]);

    /******************************* 
     * CRON JOB DONE
    /******************************* */
}
function sort_blacklist()
{
    $blist = file_get_contents(BLACK_LISTED_WORDS_LIST);
    //$str = str_replace("\r\n", "", $blist);

    $barray = json_decode($blist); //preg_split("/\r\n|\n|\r/",  $blist);

    sort($barray);
    file_put_contents(BLACK_LISTED_WORDS_LIST, json_encode($barray, JSON_PRETTY_PRINT));
}
function get_freepik($imagename)
{

    $api_key = ""; //FREEPIK_API_KEY;

    $endpoint = 'https://api.freepik.com/v1/resources?locale=en-GB&page=1&limit=10&order=latest&term=' . $imagename;

    $funalresult = "";
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept-Language: en-GB',
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Freepik-API-Key: ' . $api_key
        ),
    ));

    $response = curl_exec($curl);


    //echo $response;

    if (curl_errno($curl)) {
        echo 'Error:' . curl_error($curl);
        $funalresult = '';
    } else {

        $resultobject = json_decode($response);
        if (isset($resultobject->data)) {
            if (count($resultobject->data) > 0) {

                $ranindex = array_rand($resultobject->data);

                $funalresult = $resultobject->data[$ranindex]->image->source->url;

                // echo json_encode($resultobject->hits[$ranindex], JSON_PRETTY_PRINT);
            }
        }
    }

    curl_close($curl);
    // echo "<br/><br/> Photo from pixbay (" . $imagename . ") : " . $funalresult;
    return $funalresult;
}
function get_instagram($imagename)
{

    $q = 'yellowflowers';
    if (!empty($imagename)) {
        $q = $imagename;
    }
    $type = 'photo';
    $endpoint =  BASE_URL . 'src/plugins/instagram/?action=display&bridge=InstagramBridge&context=Hashtag&h=' . $imagename . '&media_type=picture&direct_links=on&format=Json';
    //$endpoint =  BASE_URL . 'src/plugins/instagram/?action=display&bridge=InstagramBridge&context=Hashtag&h=' . $imagename . '&media_type=picture&format=Json';


    $ch = curl_init();


    //echo $endpoint . "?key=" . $api_key . "&query=" . $q . "&client_id=" . $api_key . "&page=1&per_page=50";
    //curl_setopt($ch, CURLOPT_URL, ($endpoint . "?key=" . $api_key . "&query=" . $q . "&client_id=" . $api_key . "&page=1&per_page=50"));
    curl_setopt($ch, CURLOPT_URL, ($endpoint));
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
        if (count($resultobject->items) > 0) {
            $ranindex = array_rand($resultobject->items);
            if (isset($resultobject->items[$ranindex]->attachments[0])) {
                $funalresultd = $resultobject->items[$ranindex]->attachments[0]->url;

                $funalresult =  BASE_URL . "proxyfile.php?url=" . urlencode($funalresultd);
            }
        }
    }


    //echo "<br/><br/> Photo from unsplash (" . $imagename . ") : " . $funalresult;
    return $funalresult;

    curl_close($ch);
}

//echo (get_instagram("shoes"));
//get_pixabay("lion");
//echo get_unsplash("lion");
//echo get_pexels("lion");
//echo get_wallhaven("lion");
//echo get_flicker("lion");
//echo get_freepik("lion");
