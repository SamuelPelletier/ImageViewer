<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 18/02/2018
 * Time: 18:58
 */
include "./service.php";
$file = PATH.$_GET["name"];

if (file_exists($file)) {
    $zipname = 'adcs.zip';
    $zip = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);
    if ($handle = opendir($file)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && !strstr($entry,'.php')) {
                $zip->addFile($entry);
            }
        }
        closedir($handle);
    }

    $zip->close();

    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename='adcs.zip'");
    header('Content-Length: ' . filesize($zipname));

}