<?php
include(__DIR__ ."/service.php");

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
    // if not 1 GO free
    if(disk_free_space ( __DIR__ .'/'.PATH_ALL )/1000000000 < 1){
        return;
    }
    if(isset($_GET["id"])){
        $id = $_GET["id"];
    }else{
        $str = file_get_contents(__DIR__ .'/'.PATH_API_MEMORY);
        $data = json_decode($str, true);
        $id = $data['id'];
        $data['id']++;
        $data = json_encode($data, true);
        file_put_contents(__DIR__ .'/'.PATH_API_MEMORY,$data);
    }
    
    $data = CallAPI("GET",API_URL.$id);
    $json = json_decode($data, true);
    $name = $json['title']['english'];
    $name = str_replace('/','',$name);
    $name = str_replace('?','⸮',$name);
    $name = str_replace('%','‰',$name);
    $name = str_replace('#','♯',$name);
    $name = str_replace('#','♯',$name);
    $name = str_replace('"',"'",$name);
    $nb_image = $json['num_pages'];
    $media_id = $json['media_id'];
    $tags = array();
    foreach($json['tags'] as $tag){
        if($tag['type'] == 'tag'){
            array_push($tags,$tag['name']);
        }
    }

    $newPath = __DIR__ .'/'.PATH_ALL.$name.'/';

    if (!file_exists($newPath)) {
        $result = mkdir($newPath, 0777, true);
        if($result == false){
            return;
        }
        for($i = 1; $i <= $nb_image; $i++){
            $ext = $json['images']['pages'][$i-1]["t"] == "j" ? "jpg" : "png";
            copy(API_IMG.$media_id.'/'.$i.'.'.$ext, $newPath.str_pad($i, 2, "0", STR_PAD_LEFT).'.'.$ext);
        }
    }

    $str = file_get_contents(__DIR__ .'/'.PATH_DATABASE_TAGS_TEMP);
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
    file_put_contents(__DIR__ .'/'.PATH_DATABASE_TAGS_TEMP,$listTag);
}