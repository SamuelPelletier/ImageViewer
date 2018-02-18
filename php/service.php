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
    $tabs = scandirByModifiedDate($link);
    echo '<script>createPagination(' . PAGINATION . ',' . sizeof($tabs) . ',' . $page . ')</script>';
    $tabs = array_slice($tabs, ($page - 1) * PAGINATION);
    $size = sizeof($tabs) < PAGINATION ? sizeof($tabs) : PAGINATION;
    for ($i = 0; $i < $size; $i++) {
        if ($i % 4 == 0) {
            echo "\n";
        }
        $firstImage = array_values(array_diff(scandir($link . "/" . $tabs[$i]), array(".", "..")))[0];
        $lien = sizeof($tabs) - $i;
        echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="./?number=' . $lien . $safe . '" class="d-block mb-4 h-100 img-cell">
                        <h5 class="img-name" title="' . $tabs[$i] . '">' . $tabs[$i] . '</h5>
                        <img class="img-fluid img-thumbnail" src="' . $link . $tabs[$i] . "/" . $firstImage . '" alt="">
                    </a>
                </div>';
    }
    echo "</div>";
}

function home_page_all()
{
    echo "<h1>All</h1><div class=\"row text-center text-lg-left\">";
    $tabs = scandirByModifiedDate(PATH_ALL);
    $page = check_page();

    echo '<script>createPagination(' . PAGINATION . ',' . sizeof($tabs) . ',' . $page . ')</script>';
    $tabs = array_slice($tabs, ($page - 1) * PAGINATION);
    $size = sizeof($tabs) < PAGINATION ? sizeof($tabs) : PAGINATION;
    for ($i = 0; $i < $size; $i++) {
        if ($i % 4 == 0) {
            echo "\n";
        }
        $firstImage = array_values(array_diff(scandir(PATH_ALL . "/" . $tabs[$i]), array(".", "..")))[0];
        $lien = sizeof($tabs) - $i;
        echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="/all/?number=' . $lien . '" class="d-block mb-4 h-100 img-cell">
                        <h5 class="img-name" title="' . $tabs[$i] . '">' . $tabs[$i] . '</h5>
                        <img class="img-fluid img-thumbnail" src="' . PATH_ALL . $tabs[$i] . "/" . $firstImage . '" alt="">
                    </a>
                </div>';
    }
    echo "</div>";
}

function home_page_import()
{
    echo "<h1>Import</h1><div class=\"row text-center text-lg-left\">";

    $tabs = scandirByModifiedDate(PATH_IMPORT);
    $page = check_page();

    echo '<script>createPagination(' . PAGINATION . ',' . sizeof($tabs) . ',' . $page . ')</script>';
    $tabs = array_slice($tabs, ($page - 1) * PAGINATION);
    $size = sizeof($tabs) < PAGINATION ? sizeof($tabs) : PAGINATION;
    for ($i = 0; $i < $size; $i++) {
        if ($i % 4 == 0) {
            echo "\n";
        }
        $firstImage = array_values(array_diff(scandir(PATH_IMPORT . "/" . $tabs[$i]), array(".", "..")))[0];
        $lien = sizeof($tabs) - $i;
        echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="/import/?number=' . $lien . '" class="d-block mb-4 h-100 img-cell">
                        <h5 class="img-name" title="' . $tabs[$i] . '">' . $tabs[$i] . '</h5>
                        <img class="img-fluid img-thumbnail" src="' . PATH_IMPORT . $tabs[$i] . "/" . $firstImage . '" alt="">
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
    $name = "'" . $name . "'";
    echo '<h2><a class="download" onclick="downloadFile('.$name.')"></a></h2><div class="row text-center text-lg-left">';

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
    echo "<h2><a class='download' href='".PATH_ALL."/".$name."/' download='".$name."'></a></h2><div class=\"row text-center text-lg-left\">";

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
    echo "<h2><a class='download' href='".PATH_IMPORT."/".$name."/' download='".$name."'></a></h2><div class=\"row text-center text-lg-left\">";


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
        if($key >= ($page*PAGINATION-PAGINATION*10) && $key <= ($page*PAGINATION+PAGINATION*10)) {
            if (in_array($file, $ignored)) continue;
            $files[$file] = filemtime($dir . '/' . $file);
        }
        if($key > ($page*PAGINATION+PAGINATION*10)){
            break;
        }
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}