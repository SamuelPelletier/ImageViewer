<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 17/11/2017
 * Time: 21:33
 */

const PATH = "../media/autre/";
const PATH_IMPORT = "../import/";
const PATH_ALL = "../media/all/";
const PATH_DATABASE_TAGS = "../database/tags.json";
const PATH_DATABASE_ADD_TAG = "../database/add_tag.json";
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
    createContent('/',PATH);
    
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
    echo "<h1>About</h1><div class='row text-center text-lg-left about'>";
    echo '<h2>Hello World !</h2>';
    echo '<div>'.TITLE.' , it\'s '.count(scandirByModifiedDate(PATH_ALL)).' images folders ! </div>'; 
    echo '<div id="list-tag"><h2>List of tags</h2><ul class="list" >';
    $tagNames = getAllTags();
    foreach($tagNames as $tagName => $data){
        echo '<li class="list-item" ><a class="list-item-link">'.$tagName.' ( '.count($data).' )</a></li>';
    }
    echo "</ul></div></div>";
}



//---------- DisplayImage -----------

function displayImages($path)
{
    createDisplay(PATH, $path);
}

function displayImagesAll($path)
{
    createDisplay(PATH_ALL,$path);
}

function displayImagesImport($path)
{
    createDisplay(PATH_IMPORT, $path);
}

// --------- Create ----------

function createContent($pageName, $pathConst){
    $page = check_page();
    $allFolders = scandirByModifiedDate($pathConst);
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    isset($parts['query']) ? parse_str($parts['query'], $path) : null;
    if( isset($path['search']) &&  $path['search'] != null){
        $searchFolders = search($pathConst,$allFolders,$path['search']);
    }else{
        $searchFolders = $allFolders;
    }

    $total = sizeof($searchFolders);
    $searchFolders = array_slice($searchFolders, ($page - 1) * PAGINATION);
    $size = sizeof($searchFolders) < PAGINATION ? sizeof($searchFolders) : PAGINATION;

    if($size > 0){
        echo '<script>createPagination(' . PAGINATION . ',' . $total. ',' . $page . ')</script>';
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

    $tagsOfName = getAllTagByFolder($name);
    $allTags = getAllTagsName();
    $pathTag = "'/php/add_tag.php?name=" . urlencode($name) ."&tags="."'";
    echo "<h1>".$name."</h1><div class=\"row text-center text-lg-left\">";
    echo '<div class="tag-container">
    <h4>Tags</h4>
    <div class="dropdown">
      <span class="add-tag">Add</span>
      <span class="modify-tag">Modify</span>
      <span class="valid-tag">Valid</span>
      <ul class="dropdown-menu">';
      foreach($allTags as $tag){
          if(in_array($tag,$tagsOfName) == true){
        echo "<li class='added'>".$tag."</li>";
          }else{
        echo "<li>".$tag."</li>";
          }
        }
      echo "</ul> </div><br>
    <div class='tag-area'>";
    foreach($tagsOfName as $tag){
        echo "<div class='tag'>".$tag."<span class='remove'>×</span></div>";
    }
     echo "</div></div>";

    $name = "'/php/download.php?name=" . urlencode($name) . "&path=".$pathConst."'";
    echo '<h2 id="download-title"><a class="download" onclick="window.open('.$name.')"></a></h2><div class="row text-center text-lg-left">';


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

function search($path, $allFolders, $name){
    $result = array();
    $name = trim(substr($name, 1,strlen($name)-2));
    if($name == ''){
        return $allFolders;
    }
    $partSearch = convertInWord($name);

    foreach($partSearch as $part){
        $temp = array();
        foreach($allFolders as $data){
            if (strpos(strtoupper($data), strtoupper($part)) !== false) {
                array_push($temp, $data);
            }
        }
        array_push($result, $temp);
    }

    if(count($result) == 1){
        $result = $result[0];
    }else if(count($result) == 2){
        $result = array_intersect($result[0], $result[1]);
    }else if(count($result) > 2){
        $result = call_user_func_array('array_intersect',$result);
    }

    $tagData = getDataOfTag($name);
    if(count($tagData) > 0 && count($result) > 0 ){
       $result = array_unique(array_merge($result, $tagData));
    }else if(count($result) > 0 && count($tagData) == 0){
        $result = $result;
    }else if(count($result) == 0){
        $result = $tagData;
    }
    return $result;
}

function getAllTags(){
    // Read JSON file
    $json = file_get_contents(PATH_DATABASE_TAGS);

    //Decode JSON
    $json_data = json_decode($json,true);
    return $json_data;
}

function convertInWord($search){
   return explode(" ", $search);
}

function getAllTagsName(){
    $data = getAllTags();
    $tags = array();
    foreach($data as $tag =>$dataTag){
        array_push($tags,$tag);
    }
    return $tags;
}

function getAllCompositeTagsName(){
    $data = getAllTags();
    $tags = array();
    foreach($data as $tag =>$dataTag){
        if(preg_match("/^[a-z]+\s/",$tag) == 1){
            array_push($tags,$tag);
        }
    }
    return $tags;
}

function getDataOfTag($search){
    $tags = convertInWord($search);
    $data = getAllTags();
    $result = array();
    foreach($tags as $tag){
        if(array_key_exists($tag,$data)){
            array_push($result,$data[$tag]);
        }
    }

    if(count($tags) > 1){
        $result = getDataOfCompositeTag($search, $result);
    }
    
    if(count($result) == 1){
        $result = $result[0];
    }else if( count($result) == 0){
    }else if(count($result) == 2){
        $result = array_intersect($result[0],$result[1]);
    }else{
        $result = call_user_func_array('array_intersect',$result);
    }
    return $result;
}

function getDataOfCompositeTag($search, $result){
    $tagsName = getAllCompositeTagsName();
    $data = getAllTags();
    foreach($tagsName as $tag){
        if(array_key_exists($tag,$data) && strpos($tag, $search) !== false) {
            array_push($result,$data[$tag]);
        }
    }
    return $result;
}

function getAllTagByFolder($name){
    $tagsData = getAllTags();
    $result = array();
    foreach($tagsData as $tagName => $data){
        if(in_array($name,$data) == true){
            array_push($result, $tagName);
        }
    }
    return $result;
}