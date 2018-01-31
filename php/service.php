<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 17/11/2017
 * Time: 21:33
 */

const PATH = "../media/autre/";
const PATH_NS =  "../media/nosafe/";
const PATH_IMPORT = "../import/";
const PAGINATION = 20;
const TITLE = "MyWebSite";

function check_link(){
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    parse_str($parts['query'], $path);
    $link = PATH;
    if($path['safe'] == "false"){
        $link =  PATH_NS;
    }
    return $link;
}

function home_page()
{
    $link = check_link();
    $safe = "";
    if($link == PATH_NS){
        $safe = "&safe=false";
    }
    $tabs = scandirByModifiedDate($link);
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    $page = 1;
    if (array_key_exists('query', $parts)) {
        parse_str($parts['query'], $query);
        $page = $query["page"];
    }
    echo '<script>createPagination('.PAGINATION.','.sizeof($tabs).','.$page.')</script>';
    $tabs = array_slice($tabs, ($page-1)*PAGINATION);
    $size = sizeof($tabs) < PAGINATION ? sizeof($tabs) : PAGINATION;
    for ($i = 0; $i < $size; $i++) {
            if ($i % 4 == 0) {
                echo "\n";
            }
            $firstImage = array_values(array_diff(scandir($link."/".$tabs[$i]), array(".","..")))[0];
            $lien = sizeof($tabs)-$i;
            echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="./?number='.$lien.$safe.'" class="d-block mb-4 h-100 img-cell">
                        <h5 class="img-name" title="'.$tabs[$i].'">' . $tabs[$i] . '</h5>
                        <img class="img-fluid img-thumbnail" src="'.$link . $tabs[$i] ."/".$firstImage. '" alt="">
                    </a>
                </div>';
    }
}

function displayImages($path){
    $link = check_link();
    $tabs = scandirByModifiedDate($link);
    $path = $link .$tabs[sizeof($tabs) - $path];
    $tabs = array_diff(scandir($path),array(".",".."));
    for ($i = 2; $i < sizeof($tabs)+2; $i++) {
        $src = $path.'/'.$tabs[$i];
        $src = str_replace("'","\'", $src);
        $src = "'".$src."'";
        echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="#" class="d-block mb-4 h-100 img-cell" onclick="viewer('.$src.')">
                        <img class="img-fluid img-thumbnail" src="'.$path .'/' .$tabs[$i] . '" alt="">
                    </a>
                </div>';
    }
    echo '
    <div style="display:none;" id="list">'. json_encode($tabs).'</div>';
}

function displayImagesImport($path){
    $tabs = scandirByModifiedDate(PATH_IMPORT);
    $path = PATH_IMPORT .$tabs[sizeof($tabs) - $path];
    $tabs = array_diff(scandir($path),array(".",".."));
    for ($i = 2; $i < sizeof($tabs)+2; $i++) {
        echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="#" class="d-block mb-4 h-100 img-cell" onclick="viewer(\''.$path.'/'.$tabs[$i].'\')">
                        <img class="img-fluid img-thumbnail" src="'.$path .'/' .$tabs[$i] . '" alt="">
                    </a>
                </div>';
    }
    echo '
    <div style="display:none;" id="list">'. json_encode($tabs).'</div>';
}

function home_page_import(){
    $tabs = scandirByModifiedDate(PATH_IMPORT);
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    $page = 1;
    if (array_key_exists('query', $parts)) {
        parse_str($parts['query'], $query);
        $page = $query["page"];
    }
    echo '<script>createPagination('.PAGINATION.','.sizeof($tabs).','.$page.')</script>';
    $tabs = array_slice($tabs, ($page-1)*PAGINATION);
    $size = sizeof($tabs) < PAGINATION ? sizeof($tabs) : PAGINATION;
    for ($i = 0; $i < $size; $i++) {
        if ($i % 4 == 0) {
            echo "\n";
        }
        $firstImage = array_values(array_diff(scandir(PATH_IMPORT."/".$tabs[$i]), array(".","..")))[0];
        $lien = sizeof($tabs) - $i+($page-1)*22;
        echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="/import/?number='. $lien.'" class="d-block mb-4 h-100 img-cell">
                        <h5 class="img-name" title="'.$tabs[$i].'">' . $tabs[$i] . '</h5>
                        <img class="img-fluid img-thumbnail" src="'.PATH_IMPORT . $tabs[$i] ."/".$firstImage. '" alt="">
                    </a>
                </div>';
    }
}

function home_page_about(){
    echo '<div>hello world!</div>';
}

function home_page_upload(){
include '../php/upload.php';
    echo '<script src="https://use.fontawesome.com/aa95071b26.js" xmlns="http://www.w3.org/1999/html"></script>
<div id="body">
  <div class="out-wrap">
    <p class="head">File Upload</p>
    <div class="in-wrap">
       <label for="file" class="ui icon button">Open File</label>
          <p class="f-name"></p>
          <form action="../php/upload.php" method="post" enctype="multipart/form-data">
                 <input type="file"  accept="application/zip" requied name="file" id="file">
                 <input type="hidden" value="" name="name" id="name">
                 <button type="submit" class="upload btn"><i class="fa fa-cloud-upload"></i></button>
           </form>
  </div>
</div>
</div>';
}

function scandirByModifiedDate($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess','index.php');

    $files = array();
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}