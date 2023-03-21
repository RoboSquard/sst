<?php
include "../vendor/autoload.php";

/** Create JSONDB Object */

use Jajo\JSONDB;

class GetRSS
{
    public function run()
    {
        $json_db = new JSONDB(__DIR__ . "/db/");

        $schedules = $json_db->select('*')
            ->from('scheduled_chats.json')
            ->where(['Is_Executed' => 1])
            ->get();
        $image_base_url = sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            dirname($_SERVER["REQUEST_URI"])
        );
        $base_url = "https://ssur.uk";
        $xml = '';
        $xml .= "<?xml version='1.0' encoding='UTF-8' ?>" . PHP_EOL;
        $xml .= "<rss version='2.0'>" . PHP_EOL;
        $xml .= "<channel>" . PHP_EOL;
        $xml .= "<title>Title</title>" . PHP_EOL;
        $xml .= "<link>" . $base_url . "</link>" . PHP_EOL;
        $xml .= "<description>Chat GPT Schedule</description>" . PHP_EOL;

        foreach ($schedules as $k => $row) {


            $given = new DateTime($row["created_at"]);
            $given->setTimezone(new DateTimeZone("UTC"));
            $publish_Date = $given->format("D, d M Y H:i:s e");
            $contents = json_decode($row["Searched_Text"]);
            foreach ($contents as $key => $content_variant)
            {
                $variant_number = $key+1;
                $images = json_decode($row["Searched_Image_Url"]);
                $image_variant = isset($images[$key]) ? $images[$key] : $images[$key-1];
                $image_path = $image_base_url . $image_variant;
                $description = str_replace("\n", " ", $content_variant);
                $description = '<a href="' . $base_url . '"><img src="' . $image_path . '" /></a>' . $description;
                $guid = md5($base_url . $row["Id"]);
                $title = count($contents) > 1 ? $row["Title"] ." #".$variant_number : $row["Title"];

                $xml .= "<item>" . PHP_EOL;
                $xml .= "<title>" .$title. "</title>" . PHP_EOL;
                $xml .= "<link>" . $base_url . "</link>" . PHP_EOL;
                $xml .= '<description>' . htmlentities($description) . '</description>' . PHP_EOL;
                $xml .= "<pubDate>" . $publish_Date . "</pubDate>" . PHP_EOL;
                $xml .= "<guid isPermaLink=\"false\">" . $guid . "</guid>" . PHP_EOL;
                $xml .= "</item>" . PHP_EOL;
            }


        }

        $xml .= '</channel>' . PHP_EOL;
        $xml .= '</rss>' . PHP_EOL;
        return $xml;
    }
}

?>