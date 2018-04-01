<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 18/02/2018
 * Time: 18:58
 */
include "./service.php";

if(isset($_GET["tags"]) && isset($_GET["name"])){
	$name = $_GET["name"];
	$tags = explode(",",$_GET["tags"]);
	$str = file_get_contents(PATH_DATABASE_ADD_TAG);
	$json = json_decode($str, true);
	$data = array();

	foreach($json as $key => $value){
		if($key == $name){
				$data = $value;
				break;
		}
	}

	if(count($data) > 0){
		foreach($tags as $tag){
			array_push($data,$tag);
		}
		$tags = array_values(array_unique($data,SORT_STRING));
	}
	
	$json[$name] = $tags;
	$json = json_encode($json);
	file_put_contents(PATH_DATABASE_ADD_TAG,$json);
	echo "<script>window.close();</script>";
}else{
	header('Location: ../html/error404.html');
}



