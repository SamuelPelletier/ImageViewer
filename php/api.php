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
    return;
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
    $name = str_replace('&','§',$name);
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

    $folders = array_diff(scandir(__DIR__ .'/'.PATH_ALL), array(".", "..","index.php"));
    $path = array_filter($folders,function($elem){
        $pattern = "/^[0-9]*-[0-9]*$/"; 
        if(preg_match($pattern, $elem)) {
            return $elem;
        }
    });
    $lastFolderName = end($path);
    $path = __DIR__ .'/'.PATH_ALL.end($path);
    // Size max or no folder found
    if(count(scandir($path)) >= MAX_FOLDER_SIZE || $path == __DIR__ .'/'.PATH_ALL){
        if($path == __DIR__ .'/'.PATH_ALL){
            $min = 0;
            $max = MAX_FOLDER_SIZE - 1;
        }else{
            $minMax = explode('-',$path);
            $min = $minMax[0]+MAX_FOLDER_SIZE;
            $max = $minMax[1]+MAX_FOLDER_SIZE;
        }
        mkdir(__DIR__ .'/'.PATH_ALL.($min)."-".($max),0777, true);
        $path = __DIR__ .'/'.PATH_ALL.($min)."-".($max);
    }

    $url = $lastFolderName.'/'.$name.'/';
    $newPath = $path."/".$name.'/';
    
    if (!file_exists($newPath)) {
        $result = mkdir($newPath, 0777, true);
        if($result == false){
            return;
        }
        for($i = 1; $i <= $nb_image; $i++){
            $ext = $json['images']['pages'][$i-1]["t"] == "j" ? "jpg" : "png";
            copy(API_IMG.$media_id.'/'.$i.'.'.$ext, $newPath.str_pad($i, 3, "0", STR_PAD_LEFT).'.'.$ext);
        }
    }

    $idFolder = insertFolder($name,$nb_image,$url, $tags);
}