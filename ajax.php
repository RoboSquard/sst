<?php
$action = (isset($_POST['action'])) ? $_POST['action'] : "";

if ($action == 'fb' || $action == 'facebook')
{

    $username = filter($_POST['username']);

    $u = GetUserIDFromUsername($username);

    if ($u['status'] === true)
    {

        echo 'https://graph.facebook.com/' . $u['img'] . '/picture?type=large';
    }
    else
    {

        echo "Username not found";
    }

    die();
}

if ($action == 'flickr')
{
    $username = ($_POST['username']);
    $src = getFlickrImage($username);
    if (!empty($src))
    {
        echo $src;
    }
    else
    {
        echo 'Username not found';
    }
    die();
}

if ($action == 'pinterest')
{
    $username = filter($_POST['username']);
    $src = (getPinterestImage($username));
    if (!empty($src))
    {
        echo  $src;
    }
    else
    {
        echo 'Username not found';
    }
    die();
}
//
//if ($action == 'twitter')
//{
//    include ('simple_html_dom.php');
//
//    $username = filter($_POST['username']);
//    $url = 'https://twitter.com/' . $username;
//
//    echo $html=  file_get_html($url);
//    $imgContent = $html->find('.ProfileAvatar-image',0);
//    // echo $imgContent;
//    $url = $imgContent->src;
//
//    if ($url != "")
//    {
//        echo $url;
//    }
//    else
//    {
//        echo "Username not found";
//    }
//
//    die();
//}

if ($action == 'youtube')
{

    $username = filter($_POST['username']);

    $key = 'AIzaSyBT_UUyknVETjlmWNHGmSL1M_jsYKArkRw';

    $url = "https://www.googleapis.com/youtube/v3/channels?part=snippet%2CcontentDetails%2Cstatistics&forUsername=$username&key=" . $key;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $channelOBJ = json_decode(curl_exec($ch));

    curl_close($ch);

    if (isset($channelOBJ->items[0]
        ->snippet
        ->thumbnails
        ->high
        ->url))
    {

        $thumbnail_url = $channelOBJ->items[0]
            ->snippet
            ->thumbnails
            ->high->url;

        echo $thumbnail_url;
    }
    else
    {

        echo "Username not found";
    }
}

function filter($data)
{
    $data = trim(htmlentities(strip_tags($data)));
    return $data;
}

function GetUserIDFromUsername($username)
{

    // For some reason, changing the user agent does expose the user's UID
    $options = array(
        'http' => array(
            'user_agent' => 'some_obscure_browser'
        )
    );

    $context = stream_context_create($options);

    $fbsite = @file_get_contents('https://www.facebook.com/' . $username, false, $context);

    // ID is exposed in some piece of JS code, so we'll just extract it
    $fbIDPattern = '/\"entity_id\":\"(\d+)\"/';

    if (!preg_match($fbIDPattern, $fbsite, $matches))
    {

        // throw new Exception('Unofficial API is broken or user not found');
        return array(
            'status' => false
        );
    }

    return array(
        'status' => true,
        'img' => $matches[1]
    );
}

/**
 * Returns page content of the given url
 *
 * @param  String $url
 * @return []     containing code and error(in case of error) or pageContent(in case of success)
 */
function getPageContent($url)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "cache-control: no-cache",
            "upgrade-insecure-requests: 1",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36",
            "viewport-width: 1366"
        ) ,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err)
    {
        return ['code' => 400, 'message' => $err];
    }
    else
    {
        return ['code' => 200, 'pageContent' => $response];
    }
}

/**
 * Get instagram image for given username
 * @param  String $username instagram username
 * @return null 
 */
function getInstagramImage($username)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.view-page-source.com/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "reference_id=1&uri=https://www.instagram.com/beautifullpllaces",
        CURLOPT_HTTPHEADER => array(
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
            "origin: https://www.view-page-source.com",
            "postman-token: 589f30a2-e61b-0211-6582-a5031fe3b906",
            "sec-fetch-user: ?1",
            "upgrade-insecure-requests: 1",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36"
        ) ,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err)
    {
        echo "cURL Error #:" . $err;
    }
    else
    {

        include (__DIR__ . '/simple_html_dom.php');
        $html = str_get_html($response);

        $htmlContent = $html->find('pre', 0);

        echo $htmlContent->outertext;
        
    }

}

/**
 * Get pinterest user profile_picture based on username
 * @param  String $username pinterest username
 * @return String           pinterest profile picture image url
 */
function getPinterestImage($username)
{
    $url = "https://www.pinterest.com/" . $username . "/";
    $pageContentResult = getPageContent($url);
    if ($pageContentResult['code'] == 200)
    {

        include (__DIR__ . '/simple_html_dom.php');
        $html = str_get_html($pageContentResult['pageContent']);
        $metaAll = $html->find('meta');
        foreach ($metaAll as $meta)
        {
            if ($meta->property == 'og:image')
            {
                $src = $meta->content;
                break;
            }
        }
    }
    return $src;
}

/**
 * Get Flickr user profile_picture based on username
 * @param  String $username Flickr username
 * @return String           Flickr profile picture image url
 */
function getFlickrImage($username)
{
    $url = 'https://www.flickr.com/photos/' . $username . '/';
    $pageContentResult = getPageContent($url);
    if ($pageContentResult['code'] == 200)
    {

        include (__DIR__ . '/simple_html_dom.php');
        $html = str_get_html($pageContentResult['pageContent']);
        $div = $html->find('.avatar', 0);
        if ($div)
        {
            $style = $div->style;

            if (strlen($style) > 0)
            {
                $src = substr($style, strpos($style, 'url(') + 4);
            }
        }
    }
    return $src;
}

