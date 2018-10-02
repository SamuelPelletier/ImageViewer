<?php 
include(__DIR__ ."/service.php");

$result = scandirByModifiedDate(PATH_ALL);
$maxFolderSize = MAX_FOLDER_SIZE;
$count = 0;
$countDir = 0;


foreach ($result as $value) {
    $dirName = PATH_ALL.($count * $maxFolderSize)."-".($count * $maxFolderSize + $maxFolderSize - 1);
    if(!file_exists($dirName)){
        mkdir($dirName);
    }
    mkdir($dirName.'/'.$value);
    foreach (array_diff(scandir(PATH_ALL.$value), array(".", "..")) as $image) {
        copy(PATH_ALL.$value.'/'.$image,$dirName.'/'.$value.'/'.str_pad($image, 7, "0", STR_PAD_LEFT));
    }
    $countDir++;
    if($countDir == $maxFolderSize){
        $count++;
        $countDir=0;
    }
}

