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
const PAGINATION = 20;
const TITLE = "MyWebSite";


//---------- Checker -----------

function check_link()
{
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    parse_str($parts['query'], $path);
    $link = PATH;
    if ($path['safe'] == "false") {
        $link = PATH_NS;
    }
    return $link;
}

function check_page(){
    $page = 1;
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    if (array_key_exists('query', $parts)) {
        parse_str($parts['query'], $query);
        $page = $query["page"];
    }
    return $page;
}


//---------- HomePage -----------

function home_page()
{
    echo "<h1>Best</h1><div class=\"row text-center text-lg-left\">";
    $link = check_link();
    $page = check_page();
    $safe = "";
    if ($link == PATH_NS) {
        $safe = "&safe=false";
    }
    
    $allFolders = scandirByModifiedDate($link);
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    parse_str($parts['query'], $path);
    if( isset($path['search']) &&  $path['search'] != null){
        $searchFolders = searchByName($link,$allFolders,$path['search']);
    }else{
        $searchFolders = $allFolders;
    }
    echo '<script>createPagination(' . PAGINATION . ',' . sizeof($searchFolders) . ',' . $page . ')</script>';
    $searchFolders = array_slice($searchFolders, ($page - 1) * PAGINATION);
    $size = sizeof($searchFolders) < PAGINATION ? sizeof($searchFolders) : PAGINATION;
    for ($i = 0; $i < $size; $i++) {
        if ($i % 4 == 0) {
            echo "\n";
        }
        $firstImage = array_values(array_diff(scandir($link . "/" . $searchFolders[$i]), array(".", "..")))[0];
        $lien = sizeof($allFolders)-array_search($searchFolders[$i], $allFolders, true);
        echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="./?number=' . $lien . $safe . '" class="d-block mb-4 h-100 img-cell">
                        <h5 class="img-name" title="' . $searchFolders[$i] . '">' . $searchFolders[$i] . '</h5>
                        <img class="img-fluid img-thumbnail" src="' . $link . $searchFolders[$i] . "/" . $firstImage . '" alt="">
                    </a>
                </div>';
    }
    echo "</div>";
}

function home_page_all()
{
    echo "<h1>All</h1><div class=\"row text-center text-lg-left\">";
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    parse_str($parts['query'], $path);
    $allFolders = scandirByModifiedDate(PATH_ALL);
    if( isset($path['search']) &&  $path['search'] != null){
        $searchFolders = searchByName(PATH_ALL,$allFolders,$path['search']);
    }else{
        $searchFolders = $allFolders;
    }
    $page = check_page();
    echo '<script>createPagination(' . PAGINATION . ',' . sizeof($searchFolders) . ',' . $page . ')</script>';
    $searchFolders = array_slice($searchFolders, ($page - 1) * PAGINATION);
    $size = sizeof($searchFolders) < PAGINATION ? sizeof($searchFolders) : PAGINATION;
    for ($i = 0; $i < $size; $i++) {
        if ($i % 4 == 0) {
            echo "\n";
        }
        $firstImage = array_values(array_diff(scandir(PATH_ALL . "/" . $searchFolders[$i]), array(".", "..")))[0];
        $lien = sizeof($searchFolders) - $i;
        echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="/all/?number=' . $lien . '" class="d-block mb-4 h-100 img-cell">
                        <h5 class="img-name" title="' . $searchFolders[$i] . '">' . $searchFolders[$i] . '</h5>
                        <img class="img-fluid img-thumbnail" src="' . PATH_ALL . $searchFolders[$i] . "/" . $firstImage . '" alt="">
                    </a>
                </div>';
    }
    echo "</div>";
}

function home_page_import()
{
    echo "<h1>Import</h1><div class=\"row text-center text-lg-left\">";

    $parts = parse_url($_SERVER['HTTP_REFERER']);
    parse_str($parts['query'], $path);
    $allFolders = scandirByModifiedDate(PATH_IMPORT);
    if( isset($path['search']) &&  $path['search'] != null){
        $searchFolders = searchByName(PATH_IMPORT,$allFolders,$path['search']);
    }else{
        $searchFolders = $allFolders;
    }
    $page = check_page();

    echo '<script>createPagination(' . PAGINATION . ',' . sizeof($searchFolders) . ',' . $page . ')</script>';
    $searchFolders = array_slice($searchFolders, ($page - 1) * PAGINATION);
    $size = sizeof($searchFolders) < PAGINATION ? sizeof($searchFolders) : PAGINATION;
    for ($i = 0; $i < $size; $i++) {
        if ($i % 4 == 0) {
            echo "\n";
        }
        $firstImage = array_values(array_diff(scandir(PATH_IMPORT . "/" . $searchFolders[$i]), array(".", "..")))[0];
        $lien = sizeof($searchFolders) - $i;
        echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="/import/?number=' . $lien . '" class="d-block mb-4 h-100 img-cell">
                        <h5 class="img-name" title="' . $searchFolders[$i] . '">' . $searchFolders[$i] . '</h5>
                        <img class="img-fluid img-thumbnail" src="' . PATH_IMPORT . $searchFolders[$i] . "/" . $firstImage . '" alt="">
                    </a>
                </div>';
    }
    echo "</div>";
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
    $tabs = scandirByModifiedDate($link);
    $path = $link . $tabs[sizeof($tabs) - $path];
    $tabs = array_diff(scandir($path), array(".", ".."));
    $path_array = explode('/',$path);
    $name = end($path_array);

    echo "<h1>".$name."</h1><div class=\"row text-center text-lg-left\">";
    $name = "'/php/download.php?name=" . $name . "&path=".$link."'";
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

function displayImagesAll($path)
{
    $tabs = scandirByModifiedDate(PATH_ALL);
    $path = PATH_ALL . $tabs[sizeof($tabs) - $path];
    $tabs = array_diff(scandir($path), array(".", ".."));
    $path_array = explode('/',$path);
    $name = end($path_array);

    echo "<h1>".$name."</h1><div class=\"row text-center text-lg-left\">";
    $name = "'/php/download.php?name=" . $name . "&path=".PATH_ALL."'";
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

function displayImagesImport($path)
{
    $tabs = scandirByModifiedDate(PATH_IMPORT);
    $path = PATH_IMPORT . $tabs[sizeof($tabs) - $path];
    $tabs = array_diff(scandir($path), array(".", ".."));
    $path_array = explode('/',$path);
    $name = end($path_array);

    echo "<h1>".$name."</h1><div class=\"row text-center text-lg-left\">";
    $name = "'/php/download.php?name=" . $name . "&path=".PATH_IMPORT."'";
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
    foreach($allFolders as $key => $data){
        if(preg_match('/.*'.strtoupper($name).'.*/',strtoupper($data))){
            array_push($result, $data);
        }
    }
    return $result;

}