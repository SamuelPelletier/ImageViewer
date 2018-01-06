<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 17/11/2017
 * Time: 21:33
 */

const PATH = "../Photo/";
const PAGINATION = 22;

function home_page_image()
{
    $tabs = scandirByModifiedDate(PATH);
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    $page = 1;
    if (array_key_exists('query', $parts)) {
        parse_str($parts['query'], $query);
        $page = $query["page"];
    }
    echo '<script>createPagination('.PAGINATION.','.sizeof($tabs).','.$page.')</script>';
    //var_dump($page);
    $tabs = array_slice($tabs, ($page-1)*PAGINATION);
    $size = sizeof($tabs) < PAGINATION ? sizeof($tabs) : PAGINATION;
    for ($i = 0; $i < $size; $i++) {
            if ($i % 4 == 0) {
                echo "\n";
            }
            $firstImage = array_values(array_diff(scandir(PATH."/".$tabs[$i]), array(".","..")))[0];
            $lien = sizeof($tabs) - $i+($page-1)*22;
            echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="/'. $lien.'" class="d-block mb-4 h-100 img-cell">
                        <h5 class="img-name" title="'.$tabs[$i].'">' . $tabs[$i] . '</h5>
                        <img class="img-fluid img-thumbnail" src="'.PATH . $tabs[$i] ."/".$firstImage. '" alt="">
                    </a>
                </div>';
    }
}

function displayImages($path){
    $tabs = scandirByModifiedDate(PATH);
    $path = PATH .$tabs[sizeof($tabs) - $path];
    $tabs = array_diff(scandir($path),array(".",".."));
    for ($i = 2; $i < sizeof($tabs)+2; $i++) {
        $name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $tabs[$i]);
        echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="#" class="d-block mb-4 h-100 img-cell" onclick="viewer(\''.$path.'/'.$tabs[$i].'\')">
                        <h5 class="img-name">' . $name . '</h5>
                        <img class="img-fluid img-thumbnail" src="'.$path .'/' .$tabs[$i] . '" alt="">
                    </a>
                </div>';
    }
    echo '
    <div style="display:none;" id="list">'. json_encode($tabs).'</div>';
}

function scandirByModifiedDate($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}