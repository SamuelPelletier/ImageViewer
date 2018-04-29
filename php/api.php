<?php
include "./service.php";

launch();

function CallAPI($method, $url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

function launch(){
    if(isset($_GET["id"])){
        $id = $_GET["id"];
    }else{
        return;
    }

    $data = CallAPI("GET",API_URL.$id);
    $json = json_decode($data, true);
    $name = $json['title']['english'];
    $nb_image = $json['num_pages'];
    $media_id = $json['media_id'];
    $ext = $json['images']['pages'][0]["t"] == "j" ? "jpg" : "png";
    $tags = array();
    foreach($json['tags'] as $tag){
        if($tag['type'] == 'tag'){
            array_push($tags,$tag['name']);
        }
    }

    $newPath = PATH_ALL.$name.'/';

    if (!file_exists($newPath)) {
        $result = mkdir($newPath, 0777, true);
        if($result == false){
            return;
        }
        for($i = 1; $i <= $nb_image; $i++){
            copy(API_IMG.$media_id.'/'.$i.'.'.$ext, $newPath.str_pad($i, 2, "0", STR_PAD_LEFT).'.'.$ext);
        }
    }

    $str = file_get_contents(PATH_DATABASE_TAGS);
    $listTag = json_decode($str, true);
    foreach($tags as $tag){
        if(!isset($listTag[$tag])){
            $listTag[$tag] = array();
        }
        if(array_search($name, $listTag[$tag]) === false){
            array_push($listTag[$tag],$name);
        }
    }
    
    $listTag = json_encode($listTag);
    file_put_contents(PATH_DATABASE_TAGS,$listTag);
}