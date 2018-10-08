<?php 
include(__DIR__ ."/service.php");

$result = array_diff(scandir(PATH_ALL), array(".", "..","index.php"));
$result = array_filter($result,function($elem){
    $pattern = "/^[0-9]*-[0-9]*$/"; 
    if(!preg_match($pattern, $elem)) {
        return $elem;
    }
});
$maxFolderSize = MAX_FOLDER_SIZE;

foreach ($result as $value) {
    $folders = array_diff(scandir(PATH_ALL), array(".", "..","index.php"));
    $lastFolder = array_filter($folders,function($elem){
        $pattern = "/^[0-9]*-[0-9]*$/"; 
        if(preg_match($pattern, $elem)) {
            return $elem;
        }
    });
    $lastFolderName = end($lastFolder);
    $lastFolder = PATH_ALL.end($lastFolder);
    if(count(scandir($lastFolder)) >= MAX_FOLDER_SIZE || $lastFolder == PATH_ALL){
        // Error no folder found
        if($lastFolder == PATH_ALL){
            $min = 0;
            $max = MAX_FOLDER_SIZE - 1;
        }else{
            $minMax = explode('-',$lastFolder);
            $min = $minMax[0]+MAX_FOLDER_SIZE;
            $max = $minMax[1]+MAX_FOLDER_SIZE;
        }
        mkdir(PATH_ALL.($min)."-".($max),0777, true);
        $lastFolder = PATH_ALL.($min)."-".($max);
    }
    $success = mkdir($lastFolder.'/'.$value,0777, true);
    if($success){
        $count = 0;
        foreach (array_diff(scandir(PATH_ALL.$value), array(".", "..")) as $image) {
            $count++;
            if(copy(PATH_ALL.$value.'/'.$image,$lastFolder.'/'.$value.'/'.str_pad($image, 7, "0", STR_PAD_LEFT))){
                unlink(PATH_ALL.$value.'/'.$image);
            }
        }
        $idFolder = insertFolder($value,$count,$lastFolderName.'/'.$value, getAllTagByFolder($value));
        rmdir(PATH_ALL.$value);
    }
}

