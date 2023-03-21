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
$model = new Model();

if($_REQUEST['for'] === 'select')
{
    /** Select Scheduler */
    $schedules = $model->getAll('gpt_posts', ['Id' => $_REQUEST['Id']]);
    $content = json_decode($schedules[0]["Searched_Text"]);
    $images = json_decode($schedules[0]["Searched_Image_Url"]);
    if($schedules[0]["Type"] === 'Writer'){
        $question_text = $schedules[0]["Title"];
    }else{
        $question_text = $schedules[0]["Content"];
    }
    $question_image =  $schedules[0]["Image"];

    $image_base_url = sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        dirname($_SERVER["REQUEST_URI"])
    );

    $html = '';
    foreach ($content as $index => $data){
        $count = $index + 1;
        $text = nl2br(trim($data));
        $show = $index === 0 ? "show" : "hide";
        $title = count($content) === 1 ? 'GPT Result' : 'GPT Result '. $count;
        $image = isset($images[$index]) ? $images[$index] : $images[$index-1];

        $html.= <<<EOD
<div class="accordion" id="accordionExample$index">

                                        <div class="card  bg-dark border">
                                        <div class="card-header border" id="heading-0">
                                          <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left text-white" type="button" data-toggle="collapse" data-target="#collapse-$index" aria-expanded="true" aria-controls="collapse-$index">
                                                    $title                                 </button>
                                          </h2>
                                        </div>

                                        <div id="collapse-$index" class="collapse $show border" aria-labelledby="heading-0" data-parent="#accordionExample$index">
                                          <div class="card-body">
                                             <p class="p-3 bg-gray" style="border-radius: 3px;display: flex; align-items: center">
                                <img src="gpt/assets/me.png" alt="me" style="border-radius: 10px; width: 20px; height: 20px">
                                <span class="ml-2" >$question_text</span>
                            </p>
                            <p class="p-3" style="border-radius: 3px;display: flex; align-items: center">
                                <img src="gpt/assets/gpt.webp" alt="me" style="border-radius: 10px; width: 20px; height: 20px">
                                <span class="ml-2" >$text</span>
                            </p>
                            <p class="p-3 bg-gray" style="border-radius: 3px;display: flex; align-items: flex-start">
                                <img src="gpt/assets/me.png" alt="me" style="border-radius: 10px; width: 20px; height: 20px">
                                <span class="ml-2" >$question_image</span>
                            </p>
                            <p class="p-3" style="border-radius: 3px;display: flex; align-items: start">
                                <img src="gpt/assets/gpt.webp" alt="me" style="border-radius: 10px; width: 20px; height: 20px">
                                <span><img class="ml-2" src="$image" style="width: 100%;"/></span>
                                          </div>
                                        </div>
                                      </div>
                                  </div>
EOD;

    }
    $record = [
        "Html" =>  $html
    ];
    echo json_encode($record);

}elseif ($_REQUEST['for'] === 'edit')
{
    /** Select Scheduler */
    $schedules = $model->getAll('gpt_posts', ['Id' => $_REQUEST['Id']]);
    echo json_encode($schedules);

} elseif ($_REQUEST['for'] === 'add')
{
    /** Get all data from request */
    $title = isset($_REQUEST['title']) && !empty($_REQUEST['title']) ? $_REQUEST['title'] : "";
    $content = isset($_REQUEST['content']) && !empty($_REQUEST['content']) ? $_REQUEST['content'] : "";
    $image = isset($_REQUEST['image']) && !empty($_REQUEST['image']) ? $_REQUEST['image'] : "";
    $summarize = isset($_REQUEST['summarize']) && !empty($_REQUEST['summarize']) ? $_REQUEST['summarize'] : 0;

    $panel_id = isset($_REQUEST['panel_id']) && !empty($_REQUEST['panel_id']) ? $_REQUEST['panel_id'] : '';
    $user_id = login_user();

    $date = date('Y-m-d H:i:s');

    /** Insert Query into JSON DB */
    $model->store('gpt_posts', [
        "Type" => "Query",
        "Title" => $title,
        "Content" => $content,
        "Summarize" => $summarize,
        "Image" => $image,
        "Variants" => "",
        "Case" => "",
        "Content_Slug" => "",
        "Searched_Text" => "",
        "Searched_Image_Url" => "",
        "Is_Executed" => 0,
        "Panel_Id" => $panel_id,
        "User_Id" => $user_id,
        "created_at" => $date,
        "updated_at" => $date
    ]);

}elseif ($_REQUEST['for'] === 'update')
{
    /** Get all data from request */
    $title = isset($_REQUEST['title']) && !empty($_REQUEST['title']) ? $_REQUEST['title'] : "";
    $content = isset($_REQUEST['content']) && !empty($_REQUEST['content']) ? $_REQUEST['content'] : "";
    $image = isset($_REQUEST['image']) && !empty($_REQUEST['image']) ? $_REQUEST['image'] : "";

    $date = date('Y-m-d H:i:s');

    /** Update JSON DB */
    $model->update("gpt_posts", [
        "Title" => $title,
        "Content" => $content,
        "Image" => $image,
        "updated_at" => $date,
        "Is_Executed" => 0
    ], [
        'Id' => $_REQUEST['Id']
    ] );

}elseif ($_REQUEST['for'] === 'delete')
{
    /** Update JSON DB */
    $model->delete("gpt_posts", [ 'Id' => $_REQUEST['Id'] ], false );

}elseif ($_REQUEST['for'] === 'rss')
{
    $image_base_url = sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        dirname($_SERVER["REQUEST_URI"]));
    echo $image_base_url.'/rss.xml';
}


?>