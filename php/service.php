<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 17/11/2017
 * Time: 21:33
 */

const PATH = "../media/autre/";
const PATH_NS = "../media/nosafe/";
const PATH_IMPORT = "../import/";
const PATH_ALL = "../media/all/";
const PATH_DATABASE_TAGS = "../database/tags.json";
const PAGINATION = 20;
const TITLE = "MyWebSite";


//---------- Checker -----------

function check_routing(){
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    isset($parts['query']) ? parse_str($parts['query'], $path) : null;
    $number = isset($path['number']) ? $path['number'] : null;

    switch ($parts['path']){
        case "/import":
        case "/import/":
            $number != null ? displayImagesImport(ltrim($number,"/")) : home_page_import();
            break;
        case "/all":
        case "/all/":
            $number != null ? displayImagesAll(ltrim($number,"/")) : home_page_all();
            break;
        case "/about" :
        case "/about/":
            home_page_about();
            break;
        case "/upload":
        case"/upload/":
            home_page_upload();
            break;
        default:
            $number != null ? displayImages(ltrim($number,"/")) : home_page();
    }
}

function check_link()
{
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    isset($parts['query']) ? parse_str($parts['query'], $path) : null;
    $link = PATH;
    if (isset($path['safe']) && $path['safe'] == "false") {
        $link = PATH_NS;
    }
    return $link;
}

function check_page(){
    $page = 1;
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    if (array_key_exists('query', $parts)) {
        parse_str($parts['query'], $query);
        $page = isset($query["page"]) ? $query["page"] : 1;
    }
    return $page;
}


//---------- HomePage -----------

function home_page()
{
    echo "<h1>Best</h1><div class=\"row text-center text-lg-left\">";
    $link = check_link();
    createContent('/',$link);
    
}

function home_page_all()
{
    echo "<h1>All</h1><div class=\"row text-center text-lg-left\">";
    createContent('/all/',PATH_ALL);
}

function home_page_import()
{
    echo "<h1>Import</h1><div class=\"row text-center text-lg-left\">";
    createContent('/import/',PATH_IMPORT);
}

function home_page_upload()
{
    echo "<h1>Upload</h1><div class=\"row text-center text-lg-left\">";
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
</div>
</div>';
}

function home_page_about()
{
    echo "<h1>About</h1><div class=\"row text-center text-lg-left\">";
    echo '<div>hello world!</div>';
    echo "</div>";
}



//---------- DisplayImage -----------

function displayImages($path)
{
    $link = check_link();
    createDisplay($link, $path);
}

function displayImagesAll($path)
{
    createDisplay(PATH_ALL,$path);
}

function displayImagesImport($path)
{
    createDisplay(PATH_IMPORT, $path);
}

// --------- Other ----------


function scandirByModifiedDate($dir)
{
    $page = check_page();
    $ignored = array('.', '..', '.svn', '.htaccess', 'index.php');
    $page--;
    $files = array();
    foreach (scandir($dir) as $key => $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}

function searchByName($path, $allFolders, $name){
    $result = array();
    $name = substr($name, 1,strlen($name)-2);
    if($name == ''){
        return $allFolders;
    }
    foreach($allFolders as $data){
        if (strpos(strtoupper($data), strtoupper($name)) !== false) {
            array_push($result, $data);
        }
    }
    $tagData = getDataOfTag($name);
    foreach($tagData as $value){
        if(!in_array($value, $result)){
            array_push($result, $value);
        }
    }
    return $result;
}

function createContent($pageName, $pathConst){
    $page = check_page();
    $safe = "";
    if ($pathConst == PATH_NS) {
        $safe = "&safe=false";
    }
    $allFolders = scandirByModifiedDate($pathConst);
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    isset($parts['query']) ? parse_str($parts['query'], $path) : null;
    if( isset($path['search']) &&  $path['search'] != null){
        $searchFolders = searchByName($pathConst,$allFolders,$path['search']);
    }else{
        $searchFolders = $allFolders;
    }

    if(sizeof($searchFolders) > 0){
        echo '<script>createPagination(' . PAGINATION . ',' . sizeof($searchFolders) . ',' . $page . ')</script>';
        $searchFolders = array_slice($searchFolders, ($page - 1) * PAGINATION);
        $size = sizeof($searchFolders) < PAGINATION ? sizeof($searchFolders) : PAGINATION;
        for ($i = 0; $i < $size; $i++) {
            if ($i % 4 == 0) {
                echo "\n";
            }
            $firstImage = array_values(array_diff(scandir($pathConst . "/" . $searchFolders[$i]), array(".", "..")))[0];
            $lien = sizeof($allFolders)-array_search($searchFolders[$i], $allFolders, true);
            echo '
                <div class="col-lg-3 col-md-4 col-xs-6">
                        <a href="'.$pageName.'?number=' . $lien . '" class="d-block mb-4 h-100 img-cell">
                            <h5 class="img-name" title="' . $searchFolders[$i] . '">' . $searchFolders[$i] . '</h5>
                            <img class="img-fluid img-thumbnail" src="' . $pathConst . $searchFolders[$i]. "/" . $firstImage . '" alt="">
                        </a>
                    </div>';
        }
    }else{
        echo '<div class="notfound">
            <div class="face"><span class="eyes">:</span><span class="mouth">(</span></div>
            <p class="message">Oops, we have no result ...</p>
        </div>';
    }
    echo "</div>";
}

function createDisplay($pathConst, $path){
    $tabs = scandirByModifiedDate($pathConst);
    $path = $pathConst . $tabs[sizeof($tabs) - $path];
    $tabs = array_diff(scandir($path), array(".", ".."));
    $path_array = explode('/',$path);
    $name = end($path_array);

    echo "<h1>".$name."</h1><div class=\"row text-center text-lg-left\">";
    $name = "'/php/download.php?name=" . urlencode($name) . "&path=".$pathConst."'";
    echo '<h2><a class="download" onclick="window.open('.$name.')"></a></h2><div class="row text-center text-lg-left">';


    for ($i = 2; $i < sizeof($tabs) + 2; $i++) {
        $src = $path . '/' . $tabs[$i];
        $src = str_replace("'", "\'", $src);
        $src = "'" . $src . "'";
        echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="#" class="d-block mb-4 h-100 img-cell" onclick="viewer(' . $src . ')">
                        <img class="img-fluid img-thumbnail" src="' . $path . '/' . $tabs[$i] . '" alt="">
                    </a>
                </div>';
    }
    echo "</div>";
    echo '<div style="display:none;" id="list">' . json_encode($tabs) . '</div>';
}

function getAllTags(){
    // Read JSON file
    $json = file_get_contents(PATH_DATABASE_TAGS);

    //Decode JSON
    $json_data = json_decode($json,true);
    return $json_data;
}

function getAllTagsName(){
    $data = getAllTags();
    $tags = array();
    foreach($data as $tag =>$dataTag){
        array_push($tags,$tags);
    }
    return $tags;
}

function getDataOfTag($tag){
    $data = getAllTags();
    if(array_key_exists($tag,$data)){
        return $data[$tag];
    }
    return null;
}