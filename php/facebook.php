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
    $appId = '';
    $appSecret = '';
    $pageId = '';
    $userAccessToken = '';

    $fb = new Facebook\Facebook([
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => 'v3.0'
    ]);

    $longLivedToken = $fb->getOAuth2Client()->getLongLivedAccessToken($userAccessToken);

    $fb->setDefaultAccessToken($longLivedToken);

    $response = $fb->sendRequest('GET', $pageId, ['fields' => 'access_token'])
        ->getDecodedBody();

    $foreverPageAccessToken = $response['access_token'];

    $fb = new Facebook\Facebook([
        'app_id' => $appId,
        'app_secret' => $appSecret,
        'default_graph_version' => 'v3.0'
    ]);
    
    $data = scandirByModifiedDate(PATH_ALL);
    $one = $data[1];
    $url = 'openhentai.org/media/all/';
    if(!file_exists($url.$one.'/01.png')){
        $img = $url.$one.'/01.jpg';
    }else{
        $img = $url.$one.'/01.png';
    }
    dump($img);
    die;

    $fb->setDefaultAccessToken($foreverPageAccessToken);
    $fb->sendRequest('POST', "$pageId/feed", [
        'message' => 'New Hentai : '.$one,
        'link' => 'openhentai.org/all/?number='.(count($data)-1),
        'full_picture' => $img,
    ]);
}