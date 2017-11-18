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
    $tabs = scandir(PATH);
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
        if ($tabs[$i] != "." && $tabs[$i] != "..") {
            if ($i % 4 == 0) {
                echo "\n";
            }
            echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="#" class="d-block mb-4 h-100 img-cell">
                        <h5 class="img-name">' . $tabs[$i] . '</h5>
                        <img class="img-fluid img-thumbnail" src="'.PATH . $tabs[$i] . '" alt="">
                    </a>
                </div>';
        }
    }
}