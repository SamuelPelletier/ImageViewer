<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 17/11/2017
 * Time: 21:33
 */

function home_page_image(){
    $tabs = scandir("./Photo");
    //var_dump($tabs);
    for($i =0;$i<20; $i++) {
        if ($tabs[$i] != "." && $tabs[$i] != "..") {
            if($i % 5 == 0){
                echo "\n";
            }
            echo '<img style="width:100px; height:100px" src="./Photo/' . $tabs[$i] . '">';
        }
    }

}