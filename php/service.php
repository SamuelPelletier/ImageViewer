<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 17/11/2017
 * Time: 21:33
 */

const PATH = "../Photo/";

function home_page_image()
{
    $tabs = scandir(PATH);
    //var_dump($tabs);
    for ($i = 0; $i < 22; $i++) {
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