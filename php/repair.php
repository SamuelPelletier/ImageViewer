<?php 
include(__DIR__ ."/config.php");
include(__DIR__ ."/service.php");

return;
$json = file_get_contents(PATH_DATABASE_TAGS);

//Decode JSON
$json_data = json_decode($json,true);
$tabs = scandirByModifiedDate(PATH_ALL);

foreach($json_data as $key => $tag){
    for($i = 0; $i < count($tag); $i++){
        if(!in_array($tag[$i],$tabs)){
           array_splice($tag,$i,$i);
           $i = 0;
        }
    }
    die;
    $json_data[$key] = $tag;
}
die;
$json_data = json_encode($json_data);
$result = file_put_contents(PATH_DATABASE_TAGS,$json_data);
