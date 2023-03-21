<?php
include "../vendor/autoload.php";


require_once '../helper.php';
include_once '../model/model.php';
require_once '../model/panel.php';
require_once '../model/group.php';
require_once '../model/panelsetting.php';
require_once '../model/defaultpanelsetting.php';
require_once '../model/users.php';
include_once '../config.php';
require_once '../full_text_feed.php';
require_once '../model/Feed.php';
require_once '../model/Note.php';


$model = new Model();

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

if ($_REQUEST['for'] === 'list') {

    $data = $model->getAll('gpt_posts', ['Panel_Id' => $_REQUEST['panel_id']]);
    echo json_encode(["data" => $data]);
    exit;


} else if ($_REQUEST['for'] === 'add') {

    /** Get all data from request */
    $title = isset($_REQUEST['aiKeyword']) && !empty($_REQUEST['aiKeyword']) ? $_REQUEST['aiKeyword'] : "";
    $content = isset($_REQUEST['aiType']) && !empty($_REQUEST['aiType']) ? $case[$_REQUEST['aiType']] : "";
    $slug = isset($_REQUEST['aiType']) && !empty($_REQUEST['aiType']) ? $_REQUEST['aiType'] : "";
    $variants = isset($_REQUEST['aiVariant']) && !empty($_REQUEST['aiVariant']) ? $_REQUEST['aiVariant'] : 1;
    $image = isset($_REQUEST['aiImage']) && !empty($_REQUEST['aiImage']) ? $_REQUEST['aiImage'] : "";
    $summarize = isset($_REQUEST['aiSummarize']) && !empty($_REQUEST['aiSummarize']) ? $_REQUEST['aiSummarize'] : 0;

    $panel_id = isset($_REQUEST['panel_id']) && !empty($_REQUEST['panel_id']) ? $_REQUEST['panel_id'] : '';
    $user_id = login_user();


    $date = date('Y-m-d H:i:s');

    $use_case = "Generate " . $content . " " . $variants . " variants for keyword " . $title . " in " . $summarize . " paragraphs.";

    /** Insert Query into JSON DB */
    $model->store('gpt_posts', [
        "Type" => "Writer",
        "Title" => $title,
        "Content" => $content,
        "Summarize" => $summarize,
        "Image" => $image,
        "Variants" => $variants,
        "Case" => $use_case,
        "Content_Slug" => $slug,
        "Searched_Text" => "",
        "Searched_Image_Url" => "",
        "Is_Executed" => 0,
        "Panel_Id" => $panel_id,
        "User_Id" => $user_id,
        "created_at" => $date,
        "updated_at" => $date
    ]);

} elseif ($_REQUEST['for'] === 'update') {
    /** Get all data from request */
    $title = isset($_REQUEST['aiKeyword']) && !empty($_REQUEST['aiKeyword']) ? $_REQUEST['aiKeyword'] : "";
    $content = isset($_REQUEST['aiType']) && !empty($_REQUEST['aiType']) ? $case[$_REQUEST['aiType']] : "";
    $slug = isset($_REQUEST['aiType']) && !empty($_REQUEST['aiType']) ? $_REQUEST['aiType'] : "";
    $variants = isset($_REQUEST['aiVariant']) && !empty($_REQUEST['aiVariant']) ? $_REQUEST['aiVariant'] : 1;
    $image = isset($_REQUEST['aiImage']) && !empty($_REQUEST['aiImage']) ? $_REQUEST['aiImage'] : "";
    $summarize = isset($_REQUEST['aiSummarize']) && !empty($_REQUEST['aiSummarize']) ? $_REQUEST['aiSummarize'] : 1;

    $date = date('Y-m-d H:i:s');

    $use_case = "Generate " . $content . " " . $variants . " variants for keyword " . $title . " in " . $summarize . " paragraphs.";

    $date = date('Y-m-d H:i:s');

    $model->update("gpt_posts", [
        "Title" => $title,
        "Content" => $content,
        "Summarize" => $summarize,
        "Image" => $image,
        "Variants" => $variants,
        "Case" => $use_case,
        "Content_Slug" => $slug,
        "updated_at" => $date,
        "Is_Executed" => 0
    ], [
        'Id' => $_REQUEST['Id']
    ]);

}


?>