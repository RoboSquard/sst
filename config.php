<?php
$timezone = 'Europe/London';
date_default_timezone_set($timezone);

define("DEFAULT_TIMEZONE",$timezone);
define("GOOGLE_SCREEN_SHOT_API_KEY", "");
define('ROOT_DIR', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
define('CURL_UA', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36');
define('CURL_TIMEOUT', 30);
define('TMP_DIR', ROOT_DIR.'temp'.DIRECTORY_SEPARATOR);
define('DOCUMENT_ROOT', ROOT_DIR);
//$baseUrl = 'https://suite.social/d/';
$baseUrl = 'https://ssur.uk/dashboard/';
//$baseUrl = 'http://localhost/sss';

$moderationBaseUrl = 'https://ssur.uk/';
//$moderationBaseUrl = 'http://localhost/sss/';

// Define GPT API
define("GPT_KEY", "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");
define("QUERY_LIMIT", 2);

//Define Key pusher
define('PUSHER_APP_KEY','801ce541c11002abf395');
define('PUSHER_APP_SECRET','df0aef6e0e76eb4c8996');
define('PUSHER_APP_ID','1558509');

$networks = [
    'Amazon',
    'Answers',
    'Articles',
    'Behance',
    'Blogger',
    'Classifeds',
    //'Scripts',
    'DeviantArt',
    'Digg',
    'Dribble',
    'Ebay',
    'Etsy',
    'Eventbrite',
    'Facebook-Group',
    'Facebook-Hashtag',
    'Facebook-Marketplace',
    'Facebook-Page',
    'Facebook-Profile',
    'Flickr-Groups',
    'Flickr-Photos',
    'Flipboard',
    'Foursquare',
    'Instagram',
    'Linkedin-Group',
    'Linkedin-Jobs',
    'Linkedin-Profile',
    'Linkedin-Company',
    'Leads',
    'Livejournal',
    'Medium',
    'Meetup',
    'Mix',
    'Movies-Shows',
    'News',
    //'Periscope',
    'Pinterest-User',
    'Pinterest-Pin',
    'Producthunt',
    'Quora',
    'Quotes',
    'Reddit',
    'Reviews-Business',
    'Reviews-Products',
    'Reviews-Social',
    'Slideshare',
    'Snapchat',
    'Soundcloud',
    'Spotify',
    'Strategy',
    'Steam',
    'Tiktok',
    'Tumblr',
    'Twitch',
    'Twitter',
    'Vimeo',
    'Vk',
    'Wordpress',
    'Xing',
    'Youtube-Channel',
    'Youtube-Video'
];
$industries = [
    "General",
    "Actor",
    "Artist",
    "Blogger",
    "Chef",
    "Character",
    "Comedian",
    "Dancer",
    "Designer",
    "Director",
    "Entertainment",
    "Entrepreneur",
    "Event",
    "Gamer",
    "Influencer",
    "Movie",
    "Musician",
    "Other",
    "Photographer",
    "Politician",
    "Presenter",
    "Singer",
    "Sportsperson",
    "Sports Team",
    "Stylist",
    "Television",
    "TV Show",
    "YouTuber"
];

// Check if this is email script
if (isset($is_email_script) && $is_email_script) return;

$default_feeds = [
    'https://suite.social/search/search-result.php?q=influencer&site=Articles&rss; Test Feed 1',
    'https://suite.Test/search/search-result.php?q=influencer&site=Articles&rss; Test Feed 2',
];

$defaultShareImage = 'https://suite.social/d/src/img/default.jpg';
$advert = '<a href="https://suite.social/" target="_blank"><img style="max-width: 100%; margin-bottom: 0px" src="https://suite.social/dashboard/src/img/advert.png"></a>';
// SEARCH LIMIT
define('ADD_MORE_LIMIT', 10); // ADD MORE DYNAMIC SEARCH
define('ADD_MORE_LIMIT_ACTIVE', true); // TRUE IS ENABLED
// PANEL LIMIT
define('ADD_PANEL_LIMIT', 10); // ADD MORE PANELS
define('ADD_PANEL_LIMIT_ACTIVE', true); // TRUE IS ENABLED
// CURRENT PANEL SEARCH LIMIT
define('CURRENT_PANEL_SEARCH', 10); // ADD TO CURRENT PANEL
define('CURRENT_PANEL_SEARCH_ACTIVE', true); // TRUE IS ENABLED
// GROUP LIMIT
define('ADD_GROUP_LIMIT', 10); // GROUP LIMIT
define('ADD_GROUP_LIMIT_ACTIVE', true); // TRUE IS ENABLED
// NUMBER OF POST PER SEARCH LIMIT
define('KEYWORD_POST_LIMIT', 10); // POST LIMIT
define('THEME_COLOR', 'dark');
##### Sytem Panel ########
$sytem_default_panel = array(
    'social_default_stream' => array(
        'title'  => "What's New",
        'stream' => 'https://suite.social/rss/new.xml',
        'out' => 'intro,thumb,title,text,user,share',
        'twitterId' => 'suite.social',
        'color' => 'dark',
        'limit' => '10',
    ),
);
##### Default Panel Setting ######

$default_panels= array('default_panel_1' => array(
    'title'  => "Social Management",
    'stream' => 'https://suite.social/rss/manage.xml',
    'out' => 'intro,thumb,title,text,user,share',
    'twitterId' => 'suite.social',
    'color' => 'dark',
    'limit' => '30',
),
    'default_panel_2' => array(
        'title'  => "Social Marketing",
        'stream' => 'https://suite.social/rss/market.xml',
        'out' => 'intro,thumb,title,text,user,share',
        'twitterId' => 'suite.social',
        'color' => 'dark',
        'limit' => '30',
    ),
    'default_panel_3' => array(
        'title'  => "Social Messaging",
        'stream' => 'https://suite.social/rss/message.xml',
        'out' => 'intro,thumb,title,text,user,share',
        'twitterId' => 'suite.social',
        'color' => 'dark',
        'limit' => '30',
    ),
    'default_panel_4' => array(
        'title'  => "Social Monitoring",
        'stream' => 'https://suite.social/rss/monitor.xml',
        'out' => 'intro,thumb,title,text,user,share',
        'twitterId' => 'suite.social',
        'color' => 'dark',
        'limit' => '30',
    ),
    'default_panel_5' => array(
        'title'  => "Social Merchants",
        'stream' => 'https://suite.social/rss/merchant.xml',
        'out' => 'intro,thumb,title,text,user,share',
        'twitterId' => 'suite.social',
        'color' => 'dark',
        'limit' => '30',
    ),
    'default_panel_6' => array(
        'title'  => "Social Shopper",
        'stream' => 'https://suite.social/rss/shopper.xml',
        'out' => 'intro,thumb,title,text,user,share',
        'twitterId' => 'suite.social',
        'color' => 'dark',
        'limit' => '30',
    ),
    'default_panel_7' => array(
        'title'  => "Social Media Tips",
        'stream' => 'https://nealschaffer.com/feed/',
        'out' => 'intro,thumb,title,text,user,share',
        'twitterId' => 'suite.social',
        'color' => 'dark',
        'limit' => '30',
    )
);

###### End Default Panel #######
##### Dynamic panel https:/suite.social/d/?id=devjobs ########
$dynamic_panels =  array(   'freelancer'  => array(
    'title'  => "Social Media Jobs",
    'stream' => 'https://suite.social/rss/freelancer.xml',
    'out' => 'intro,thumb,title,text,user,share',
    'twitterId' => 'suite.social',
    'color' => 'dark',
    'limit' => '50',
),
    'promotion'  =>  array(
        'title'  => "Create Promotion",
        'stream' => 'https://suite.social/rss/promotion.xml',
        'out' => 'intro,thumb,title,text,user,share',
        'twitterId' => 'suite.social',
        'color' => 'dark',
        'limit' => '30',
    ),
);
######End Dynamic panel #####
function database_connect()
{
    if (isset($GLOBALS['db_overwrite'])) {
        $db = $GLOBALS['db_overwrite'];
    } else {
        $db = DOCUMENT_ROOT . 'db/data.db';
    }

    try{
        $dbh = new pdo('sqlite:'.$db,
            null,
            null,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        return $dbh;


    }catch(PDOException $ex){
        die(json_encode(array('outcome' => false, 'message' => $ex->getMessage())));
    }
}
function migration(){
    $commands = [
        'DROP TABLE IF EXISTS `users`',
        'DROP TABLE IF EXISTS `groups`',
        'DROP TABLE IF EXISTS `panels`',
        'DROP TABLE IF EXISTS `keywords`',
        'DROP TABLE IF EXISTS `default_keyword`',
        'DROP TABLE IF EXISTS `cache_storage`',
        'DROP TABLE IF EXISTS `notes`',
        'DROP TABLE IF EXISTS `rss_urls`',
        'DROP TABLE IF EXISTS `moderations`',
        'CREATE TABLE "users" (
            "id"	INTEGER NOT NULL UNIQUE,
            "ip_address"	TEXT NOT NULL,
            "timezone"	TEXT NOT NULL,
            PRIMARY KEY("id" AUTOINCREMENT)
            );',
        'CREATE TABLE "groups" (
            "id"	INTEGER NOT NULL UNIQUE,
            "user_id" INTEGER NOT NULL,  
            "name"	TEXT NOT NULL,
            "is_deleted" INTEGER NOT NULL,
            PRIMARY KEY("id" AUTOINCREMENT),
            FOREIGN KEY (user_id) 
            REFERENCES users (id) 
                ON DELETE CASCADE 
                ON UPDATE NO ACTION
            );',
        'CREATE TABLE "panels" (
        "id"	INTEGER NOT NULL UNIQUE,
        "user_id" INTEGER NOT NULL,  
        "title"	TEXT NOT NULL,
        "group_id"	INTEGER NOT NULL,  
        "color"	TEXT,
        "panel_type" INTEGER DEFAULT 1 NOT NULL,
        "is_deleted" INTEGER DEFAULT 0 NOT NULL,
        PRIMARY KEY("id" AUTOINCREMENT),
        FOREIGN KEY (user_id) 
        REFERENCES users (id) 
            ON DELETE CASCADE 
            ON UPDATE NO ACTION
        FOREIGN KEY (group_id) 
        REFERENCES groups (id) 
            ON DELETE CASCADE 
            ON UPDATE NO ACTION    
        );',
        'CREATE TABLE "keywords" (
        "id"	INTEGER NOT NULL UNIQUE,
        "panel_id" INTEGER NOT NULL,
        "page_type"	TEXT,
        "network_type"	TEXT,
        "keywork"	TEXT,
        "per_page_limit"	TEXT,
        "is_full_text_feed" INTEGER DEFAULT 0 NOT NULL,
        "is_scheduled" INTEGER DEFAULT 0 NOT NULL,
        PRIMARY KEY("id" AUTOINCREMENT),
        FOREIGN KEY (panel_id) 
        REFERENCES panels (id) 
            ON DELETE CASCADE 
            ON UPDATE NO ACTION
        );',
        'CREATE TABLE "default_keyword" (
            "id"	INTEGER NOT NULL UNIQUE,
            "user_id" INTEGER NOT NULL,  
            "group_id" INTEGER NOT NULL,  
            "panel_text_id" TEXT,
            "page_type"	TEXT,
            "network_type"	TEXT,
            "keywork"	TEXT,
            "per_page_limit"	TEXT,
            "is_full_text_feed" INTEGER DEFAULT 0 NOT NULL,
            PRIMARY KEY("id" AUTOINCREMENT),
            FOREIGN KEY (user_id) 
            REFERENCES users (id) 
                ON DELETE CASCADE 
                ON UPDATE NO ACTION  
        );',
        'CREATE TABLE "cache_storage" (
            "id"	INTEGER NOT NULL UNIQUE,
            "ip"	TEXT,
            "slug"	TEXT,
            "page"   TEXT,
            PRIMARY KEY("id" AUTOINCREMENT)
        );',
        'CREATE TABLE "notes" (
            "id"	INTEGER NOT NULL UNIQUE,
            "user_id" INTEGER NOT NULL,
            "panel_id"	INTEGER NOT NULL,
            "post_title" Text,
            "note"   TEXT,
            PRIMARY KEY("id" AUTOINCREMENT)
        );',
        'CREATE TABLE "rss_urls" (
            "id"	INTEGER NOT NULL UNIQUE,
            "panel_id"	INTEGER NOT NULL,
            "url" Text,
            PRIMARY KEY("id" AUTOINCREMENT)
        );',
        'CREATE TABLE "moderations" (
            "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            "user_id" INTEGER NOT NULL,
            "panel_id" INTEGER NOT NULL,
            "panel_name" TEXT NOT NULL,
            "status" TEXT NOT NULL,
            "is_deleted" INTEGER DEFAULT 0,
            "created_at" TEXT,
            "rss_path" TEXT
          );'

    ];
    $db = database_connect();
    foreach ($commands as $command) {
        $db->exec($command);
    }
    #### Table ####
    $stmt = $db->query("SELECT name
    FROM sqlite_master
    WHERE type = 'table'
    ORDER BY name");
    $tables = [];
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        $tables[] = $row['name'];
    }
    return $tables;
}
//dd(migration());
function setUserLoginSession()
{
    // session_destroy();
    // unset($_SESSION["login_user_id"]);
    if(isset($_SESSION["login_user_id"]))
        return true;
    $user = new Users();
    $ip = get_client_ip();
    $timezone = getTimeZoneFromIpAddress();
    $current_user = $user->getUser(['ip_address'=>$ip]);
    if(empty($current_user))
    {
        $current_user = $user->storeData(['ip_address'=>$ip,'timezone'=>$timezone]);
    }
    if (!empty($current_user)) {
        $_SESSION["login_user_id"] = $current_user['id'];
    }
}
setUserLoginSession();




class makeRequest{

    public function run($data, $url, $key)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.$key;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $response['error'] = curl_error($ch);
            curl_close($ch);
        }else{
            curl_close($ch);
            return json_decode($response, true);
        }
    }

}
