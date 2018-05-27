<?php
include(__DIR__ ."/service.php");
require_once __DIR__ . '/../vendor/autoload.php'; // change path as needed

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
    $appId = FACEBOOK_APP_ID;
    $appSecret = FACEBOOK_APP_SECRET;
    $pageId = FACEBOOK_PAGE_ID;
    $accessToken = FACEBOOK_ACCESS_TOKEN;

    $fb = new Facebook\Facebook([
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => 'v3.0'
    ]);
    
    $fb->setDefaultAccessToken($accessToken);

    $ignored = array('.', '..', '.svn', '.htaccess', 'index.php');
    $dir = PATH_ALL;
    $cache = PATH_CACHE_ALL;
    $json = file_get_contents($cache);
    $files = json_decode($json,true);
    $files = array();
    foreach (scandir($dir) as $key => $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }
    arsort($files);
    $files = array_keys($files);
    file_put_contents($cache,json_encode($files));

    foreach($files as $key => $file){
        if(empty(array_intersect(getAllTagByFolder($file),PRIVATE_TAGS))){
            break;
        }
    }

    $title = $files[$key];
    $file = PATH_ALL.$title.'/01.jpg';

    $image = imagecreatefromjpeg($file);

    /* Get original image size */
    list($w, $h) = getimagesize($file);

    /* Create array with width and height of down sized images */
    $size = array('sm'=>array('w'=>intval($w/4), 'h'=>intval($h/4)),
                   'md'=>array('w'=>intval($w/2), 'h'=>intval($h/2))
                  );                       

    /* Scale by 25% and apply Gaussian blur */
    $sm = imagecreatetruecolor($size['sm']['w'],$size['sm']['h']);
    imagecopyresampled($sm, $image, 0, 0, 0, 0, $size['sm']['w'], $size['sm']['h'], $w, $h);

    for ($x=1; $x <=40; $x++){
        imagefilter($sm, IMG_FILTER_GAUSSIAN_BLUR, 999);
    } 

    imagefilter($sm, IMG_FILTER_SMOOTH,99);
    imagefilter($sm, IMG_FILTER_BRIGHTNESS, 10);        

    /* Scale result by 200% and blur again */
    $md = imagecreatetruecolor($size['md']['w'], $size['md']['h']);
    imagecopyresampled($md, $sm, 0, 0, 0, 0, $size['md']['w'], $size['md']['h'], $size['sm']['w'], $size['sm']['h']);
    imagedestroy($sm);

        for ($x=1; $x <=25; $x++){
            imagefilter($md, IMG_FILTER_GAUSSIAN_BLUR, 999);
        } 

    imagefilter($md, IMG_FILTER_SMOOTH,99);
    imagefilter($md, IMG_FILTER_BRIGHTNESS, 10);        

    /* Scale result back to original size */
    imagecopyresampled($image, $md, 0, 0, 0, 0, $w, $h, $size['md']['w'], $size['md']['h']);

    imagejpeg($image,__DIR__.'/../facebook/'.$title.'.jpg');
    imagedestroy($image);

    $response = $fb->post(
        '/'.$pageId.'/photos',
        array (
          'url' => str_replace(' ','%20', URL_FACEBOOK.$title.'.jpg'),
          'caption' => 'New '.FACEBOOK_NEW.' : '.$title."\n".'Link : '.FACEBOOK_LINK. (count($files) - $key),
        ),
        $accessToken
      );
}