<?php


require_once 'config.php';
require_once 'src/plugins/articlecreator/config.php';
require_once 'importschedule.php';

/**saves last keyword to file */
function savelastkeyword($url)
{
    file_put_contents(LAST_KEY_WORD, $url);
}
/**gets last urls from file*/
function get_last_keyword()
{
    $file = file_get_contents(LAST_KEY_WORD);
    return trim($file);
}
/**gets current list of keywords*/
function get_current_keywords()
{

    $filedata = file_get_contents(KEYWORDS_LIST);

    return json_decode($filedata);
}
/**gets next url from file */
function get_next_keyword()
{
    $next = "";
    $lasturl = trim(get_last_keyword());
    $currenturls = get_current_keywords();
    if (count($currenturls) > 0) {
        //echo "array not empty";

        function myfunctionz($v)
        {
            return ($v->keyword);
        }

        $sortarray = array_map("myfunctionz", $currenturls);

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
        savelastkeyword($next->keyword);
    }
    return $next;
}
function public_create()
{


    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die();
    }

    $imageapis = array();
    foreach ($_POST['imageapi'] as $selectedOption) {
        $imageapis[] = $selectedOption;
    }


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

    //echo json_encode($defaultvalues);


    $lang = filter_var($defaultvalues['language'], FILTER_SANITIZE_SPECIAL_CHARS);




    $urlsource = BASE_URL . "src/plugins/articlecreator/read.php?keyword=" . urlencode($defaultvalues["keyword"]) . "&lang=" . $defaultvalues['language'] . "&links=remove";



    $numbers = filter_var($defaultvalues["numbers"], FILTER_SANITIZE_SPECIAL_CHARS);


    $gettitle = filter_var($defaultvalues["keyword"], FILTER_SANITIZE_SPECIAL_CHARS) . " :: Article Creator";
    //echo ("<title>$gettitle</title>");

    $prefix = mt_rand(100, 1000);
    $myFile = 'foldertemp/' . $prefix . "_generatedfile.txt";
    $spintaxtfile = 'foldertemp/' . $prefix . "_generatedfile_SPINTAXT.txt";
    if (file_exists("$myFile")) unlink("$myFile");
    if (file_exists("$spintaxtfile")) unlink("$spintaxtfile");


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlsource);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    $returned = curl_exec($ch);
    curl_close($ch);
    //echo ($returned);
    $feed = simplexml_load_string($returned);


    $count = 0;
    $maxitems = $numbers;
    //echo $numbers;

    /**load current content on the Admin Feed file */

    $xmlDoc = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');

    $deleted_posts = json_decode(file_get_contents(DELETED_POSTS_FILE));
    $lowerdeleted = array();
    foreach ($deleted_posts as $deleted) {
        $lowerdeleted[] = strtolower($deleted);
    }


    foreach ($feed->channel->item as $newItem) {

        if ($count < $maxitems) {

            /** filter for similar titles **/
            $itemExists = false;
            foreach ($xmlDoc->channel->item as $item) {
                if (
                    in_array(strtolower($newItem->title), $lowerdeleted) ||
                    strtolower($newItem->title) === strtolower($item->title) ||
                    strtolower($newItem->description) === strtolower($item->description) ||
                    trim($newItem->description) === ""
                ) {

                    // if (strtolower($newItem->title) === strtolower($item->title) || strtolower($newItem->description) === strtolower($item->description)) {
                    $itemExists = true;
                    //echo "exists";
                } else {
                    //	echo "never";
                }
            }

            if ($itemExists)
                continue;


            /*** new article content */
            $title = $newItem->title;
            //$title = str_replace("<b>", "", $title);
            $subject = str_replace("</b>", "", $title);
            $link = $newItem->link;

            $body = $newItem->description;
            //$body = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $body);
            $body = strip_tags($body, '<p><li><b><img>');

            //$headers = "MIME-Version: 1.0" . "\r\n";
            //$headers .= "Content-type:text/html;charset=utf-8" . "\r\n";


            if (($defaultvalues['rewrite'] == 'unique') and ($lang == 'en')) {
                require_once('src/plugins/articlecreator/unik.class.php');
                require_once('src/plugins/articlecreator/spinter.php');
                $data = $body;
                $unik = new unik;
                $spintaxbody = $unik->spin($data);
                $spinter = new Spinter();
                $newbody = $spinter->process($spintaxbody);

                $data = $subject;
                $unik = new unik;
                $spintaxsubject = $unik->spin($data);
                $spinter = new Spinter();
                $newsubject = $spinter->process($spintaxsubject);
            } else {
                $newbody = $body;
                $newsubject = $subject;
            }

            /** new content ends */

            $descriptionRaw = html_entity_decode($newbody, ENT_XML1);
            //echo $descriptionRaw;
            // get first image from description
            $firstimage = preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $descriptionRaw, $image);
            //get text descritption without html tags
            $textdescription = strip_tags($descriptionRaw);
            // truncate description to <1000 characters for the summary api to work
            $truncatedtext = get_snippet($textdescription, 800);
            //get summary from api
            $summarizedmessage = getmessagesummary($truncatedtext, $defaultvalues["sentences"]);

            if (trim($summarizedmessage) == "")
                continue;

            if (isset($newItem->{'media:content'})) {
                // echo json_encode($newItem->{'media:content'}) . "\n";
            }
            // if summary is too long reduce length to <200 characters

            $dsc = htmlentities($summarizedmessage, ENT_XML1);
            if ($defaultvalues["truncate"] != "0") {
                $dsc = reducelengthiftoolong(htmlentities($summarizedmessage, ENT_XML1), '...', ((int)$defaultvalues["truncate"]));
            }

            // create data object 
            $mydata = array(
                'title' => isset($newsubject) ? htmlentities($newsubject, ENT_XML1) : "",
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
            $imgind = array_rand($defaultvalues["imageapi"]);
            $imagesourceselect = $defaultvalues["imageapi"][$imgind];

            if ($imagesourceselect == "instagram") {
                $instahash = get_keyword($mydata['title']);
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

                    foreach ($defaultvalues["imageapi"] as $imageapi) {
                        if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {
                            $unsplashphoto = get_image($imageapi, $keyword);
                        }
                    }
                }

                if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {

                    $wordsarray = explode(' ', $summarizedmessage);

                    foreach ($wordsarray as $keyword) {
                        foreach ($defaultvalues["imageapi"] as $imageapi) {
                            if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {
                                $unsplashphoto = get_image($imageapi, $keyword);
                            }
                        }
                    }
                }



                if ($keyword != '' && $unsplashphoto != '' &&  $unsplashphoto != BASE_URL . 'proxyfile.php?url=') {
                    $mydata["desc"] =  htmlentities('<img crossorigin="anonymous" src="' . $unsplashphoto . '" />', ENT_XML1) . $mydata["desc"];
                }
            } else {
                $mydata["desc"] =  htmlentities('<img crossorigin="anonymous" src="' . $mydata['image'] . '" />', ENT_XML1) . $mydata["desc"];
            }


            $item = $xmlDoc->channel->addNewItem();
            $item->addChild('title', $mydata['title']);
			$item->addChild('description', $mydata['desc'] . "&lt;br&gt;&lt;br&gt;" . $defaultvalues['hashtags'] . '' . $instahash);
            $item->addChild('link', htmlspecialchars($mydata['link']));
            $item->addChild('guid', $mydata['guid']);
            $item->addChild('pubDate', $mydata['pubd']);
            $item->addChild('approve', 'no');



            $count++;
        }
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

    echo json_encode(['status' => $status, 'accessurl' => BASE_URL . "src/plugins/articlecreator/go.php?keyword=" . $_POST["keyword"] . "&lang=" . $_POST["lang"] . "&rewrite=" . $_POST["rewrite"] . "&numbers=3&accesskey=&submit=Submit"]);

    // echo "($count posts imported)-" . BASE_URL . "src/plugins/articlecreator/go.php?keyword=" . $_POST["keyword"] . "&lang=" . $_POST["lang"] . "&rewrite=" . $_POST["rewrite"] . "&numbers=3&accesskey=&submit=Submit";
}
function private_create()
{

    /**GET NEXT URL */
    $nexturl = get_next_keyword();
    if ($nexturl != "") {

        $defaultvalues = (array) $nexturl;




        //echo json_encode($defaultvalues);


        $lang = filter_var($defaultvalues['language'], FILTER_SANITIZE_SPECIAL_CHARS);




        $urlsource = BASE_URL . "src/plugins/articlecreator/read.php?keyword=" . urlencode($defaultvalues["keyword"]) . "&lang=" . $defaultvalues['language'] . "&links=remove";



        $numbers = filter_var($defaultvalues["numbers"], FILTER_SANITIZE_SPECIAL_CHARS);


        $gettitle = filter_var($defaultvalues["keyword"], FILTER_SANITIZE_SPECIAL_CHARS) . " :: Article Creator";
        //echo ("<title>$gettitle</title>");

        $prefix = mt_rand(100, 1000);
        $myFile = 'foldertemp/' . $prefix . "_generatedfile.txt";
        $spintaxtfile = 'foldertemp/' . $prefix . "_generatedfile_SPINTAXT.txt";
        if (file_exists("$myFile")) unlink("$myFile");
        if (file_exists("$spintaxtfile")) unlink("$spintaxtfile");


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlsource);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $returned = curl_exec($ch);
        curl_close($ch);
        //echo ($returned);
        $feed = simplexml_load_string($returned);


        $count = 0;
        $maxitems = (int)$numbers;
        //echo $numbers;

        /**load current content on the Admin Feed file */

        $xmlDoc = simplexml_load_file(ADMIN_FEED_FILE, 'my_node');

        //echo count($feed->channel->item);
        $deleted_posts = json_decode(file_get_contents(DELETED_POSTS_FILE));
        $lowerdeleted = array();
        foreach ($deleted_posts as $deleted) {
            $lowerdeleted[] = strtolower($deleted);
        }

        foreach ($feed->channel->item as $newItem) {

            if ($count < $maxitems) {

                /** filter for similar titles **/
                $itemExists = false;
                foreach ($xmlDoc->channel->item as $item) {
                    if (
                        in_array(strtolower($newItem->title), $lowerdeleted) ||
                        strtolower($newItem->title) === strtolower($item->title) ||
                        strtolower($newItem->description) === strtolower($item->description) ||
                        trim($newItem->description) === ""
                    ) {

                        //if (strtolower($newItem->title) === strtolower($item->title) || strtolower($newItem->description) === strtolower($item->description)) {
                        $itemExists = true;
                        //echo "exists";
                    } else {
                        //	echo "never";
                    }
                }

                if ($itemExists)
                    continue;


                /*** new article content */
                $title = $newItem->title;
                //$title = str_replace("<b>", "", $title);
                $subject = str_replace("</b>", "", $title);
                $link = $newItem->link;

                $body = $newItem->description;
                //$body = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $body);
                $body = strip_tags($body, '<p><li><b><img>');

                //$headers = "MIME-Version: 1.0" . "\r\n";
                //$headers .= "Content-type:text/html;charset=utf-8" . "\r\n";


                if (($defaultvalues['rewrite'] == 'unique') and ($lang == 'en')) {
                    require_once('src/plugins/articlecreator/unik.class.php');
                    require_once('src/plugins/articlecreator/spinter.php');
                    $data = $body;
                    $unik = new unik;
                    $spintaxbody = $unik->spin($data);
                    $spinter = new Spinter();
                    $newbody = $spinter->process($spintaxbody);

                    $data = $subject;
                    $unik = new unik;
                    $spintaxsubject = $unik->spin($data);
                    $spinter = new Spinter();
                    $newsubject = $spinter->process($spintaxsubject);
                } else {
                    $newbody = $body;
                    $newsubject = $subject;
                }

                /** new content ends */

                $descriptionRaw = html_entity_decode($newbody, ENT_XML1);
                //echo $descriptionRaw;
                // get first image from description
                $firstimage = preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $descriptionRaw, $image);
                //get text descritption without html tags
                $textdescription = strip_tags($descriptionRaw);
                // truncate description to <1000 characters for the summary api to work
                $truncatedtext = get_snippet($textdescription, 800);
                //get summary from api
                $summarizedmessage = getmessagesummary($truncatedtext, $defaultvalues["sentences"]);

                if (trim($summarizedmessage) == "")
                    continue;

                if (isset($newItem->{'media:content'})) {
                    // echo json_encode($newItem->{'media:content'}) . "\n";
                }
                // if summary is too long reduce length to <200 characters

                $dsc = htmlentities($summarizedmessage, ENT_XML1);
                if ($defaultvalues["truncate"] != "0") {
                    $dsc = reducelengthiftoolong(htmlentities($summarizedmessage, ENT_XML1), '...', ((int)$defaultvalues["truncate"]));
                }

                // create data object 
                $mydata = array(
                    'title' => isset($newsubject) ? htmlentities($newsubject, ENT_XML1) : "",
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
                $imgind = array_rand($defaultvalues["imageapi"]);
                $imagesourceselect = $defaultvalues["imageapi"][$imgind];
                if ($imagesourceselect == "instagram") {
                    $instahash = get_keyword($mydata['title']);
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

                        foreach ($defaultvalues["imageapi"] as $imageapi) {
                            if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {
                                $unsplashphoto = get_image($imageapi, $keyword);
                            }
                        }
                    }

                    if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {

                        $wordsarray = explode(' ', $summarizedmessage);

                        foreach ($wordsarray as $keyword) {
                            foreach ($defaultvalues["imageapi"] as $imageapi) {
                                if ($unsplashphoto == '' || $unsplashphoto == BASE_URL . 'proxyfile.php?url=') {
                                    $unsplashphoto = get_image($imageapi, $keyword);
                                }
                            }
                        }
                    }


                    // echo "*****************" . $unsplashphoto . "************";

                    if ($keyword != '' && $unsplashphoto != '' && $unsplashphoto != BASE_URL . 'proxyfile.php?url=') {
                        $mydata["desc"] =  htmlentities('<img crossorigin="anonymous" src="' . $unsplashphoto . '" />', ENT_XML1) . $mydata["desc"];
                    }
                } else {
                    //echo "***************** Image exists ************";
                    $mydata["desc"] =  htmlentities('<img crossorigin="anonymous" src="' . $mydata['image'] . '" />', ENT_XML1) . $mydata["desc"];
                }


                $item = $xmlDoc->channel->addNewItem();
                $item->addChild('title', $mydata['title']);
				$item->addChild('description', $mydata['desc'] . "&lt;br&gt;&lt;br&gt;" . $defaultvalues['hashtags'] . '' . $instahash);
                $item->addChild('link', htmlspecialchars($mydata['link']));
                $item->addChild('guid', $mydata['guid']);
                $item->addChild('pubDate', $mydata['pubd']);
                $item->addChild('approve', 'no');



                $count++;
            }
        }

        //create new xml document content
        $dom = new DOMDocument("1.0");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlDoc->asXML());
        // save to admin feed file
        $status = file_put_contents(ADMIN_FEED_FILE, $dom->saveXML());
        $status = ($status === false ? false : true);

        $accessurl = BASE_URL . "src/plugins/articlecreator/go.php?keyword=" . $defaultvalues["keyword"] . "&lang=" . $defaultvalues["language"] . "&rewrite=" . $defaultvalues["rewrite"] . "&numbers=3&accesskey=&submit=Submit";
        echo "<br>Keyword : " . $defaultvalues["keyword"] . "($count posts created) -  $accessurl<br>";
    } else {
        echo ("NO URL IN QUEUE");
    }
}
