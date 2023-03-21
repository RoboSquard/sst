<?php
header('Access-Control-Allow-Origin: *');

/**
 * Define Base URL Here ending with '/'
 */

define('BASE_URL', 'https://ssur.uk/moderation/');
//define('BASE_URL', 'http://localhost/sss/moderation/');

define('ORIGIN_URL', 'https://ssur.uk/');
//define('ORIGIN_URL', 'http://localhost/sss/');

define('REDIRECTION_URL', 'https://ssur.uk/moderation/redirect.php');

define('ADMIN_FEED_FILE', 'db/moderate.xml');

define('PUBLISHED_FEED_FILE', 'db/publish.xml');

define('NOTES_FILE', 'db/notes.json');

define('QUEUE_FILE', 'db/queue.json');

define('UPDATES_FILE', 'db/updates.json');

define('RSS_URLS_FILE', 'db/rss_urls.txt');

define('RSS_LAST_FILE', 'db/lastfile.txt');

define('LAST_KEY_WORD', 'db/lastkeyword.txt');

define('RSS_DEFAULT_VALUES', 'db/defaultvalues.txt');

define('RSS_AUTOMATIC_LIST', 'db/automaticposts.txt');

define('KEYWORDS_LIST', 'db/keywords.txt');

define('BLACK_LISTED_WORDS_LIST', 'db/blacklistwordsfile.txt');

define('DELETED_POSTS_FILE', 'db/deletedposts.txt');

define('MAXPOSTSPERIMPORT', 10);

define('PIXBAY_API_KEY', '20774189-c9384e7e1437cface00025056');

define('UNSPLASH_API_KEY', 'jyDAm3w60HG2H0vKB--FhrMrsJ5C8ggi2LSoV5Y5Aww');

define('SUMARY_API_KEY', '1a8816f5601d3fd6216a8f2c6406d2c6');

//define('FREEPIK_API_KEY', '00000000-0000-0000-0000-000000000000');

//Define Key pusher
define('PUSHER_APP_KEY','801ce541c11002abf395');
define('PUSHER_APP_SECRET','df0aef6e0e76eb4c8996');
define('PUSHER_APP_ID','1558509');

function database_connection_build()
{
    if (isset($GLOBALS['db_overwrite'])) {
        $db = $GLOBALS['db_overwrite'];
    } else {
        $db = '../db/data.db';
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

function getRecord($table,$id)
{
    $sql = "SELECT * FROM `$table` WHERE `id` = $id";
    $pdo = database_connection_build();
    $stmt = $pdo->query($sql);
    return $stmt->fetch();
}

function getWhere($table,$whereArray=[])
{
    $sql = "SELECT * FROM `$table`";
    if(!empty($whereArray)){
        $sql .= whereCondition($whereArray) ;
    }
    $pdo = database_connection_build();
    $stmt = $pdo->query($sql);
    return $stmt->fetch();
}

function whereCondition($whereArray = [], $likeQuery = false){
    $count_record = count($whereArray);
    $count=1;
    $whereQuery ='';
    foreach($whereArray as $col=>$val){
        if(is_numeric($val))
        $whereQuery  .= "`".$col."` = ".$val;
        else
        $whereQuery  .= "`".$col."` = '".$val."'";
        if($count == $count_record)
        break;
        $whereQuery  .= ' AND ';
        $count++;
    }
    if( $likeQuery )
    return $whereQuery ;
    return ' WHERE '. $whereQuery ;
}