<?php
include(__DIR__ ."/service.php");
include(__DIR__."/database.php");

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

    $all = getAllFolder($total,true);
    foreach($all as $one){
        if(in_array(PRIVATE_TAGS,$one['tags'])){
            $url = $one['url'];
            $key = $one['id'];
            break;
        }
    }

    $file = $url.'/001.jpg';

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

    for ($x=1; $x <=50; $x++){
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

    imagejpeg($image,__DIR__.'/facebook.jpg');
    imagedestroy($image);
	
    $response = $fb->post(
        '/'.$pageId.'/photos',
        array (
          'url' => URL_FACEBOOK_IMG,
          'caption' => 'New '.FACEBOOK_NEW.' : '.$title."\n".'Link : '.FACEBOOK_LINK. $key,
        ),
        $accessToken
    );
}