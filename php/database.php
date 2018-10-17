<?php

const PROPERTY = "folder.id,folder.name,folder.date_add,folder.count_pages,folder.url";

function connexion(){
    $result = false;
    try {
        $conn = new PDO("mysql:host=".DATABASE_SERVER_NAME.";dbname=".DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result = $conn;
        //echo "Connected successfully"; 
    }catch(PDOException $e){
        //var_dump($e->getMessage());
        //echo "Connection failed: " . $e->getMessage();
    }
    return $result;
}


function getAllFolder(&$total,$byDate = false,$page = -1){
    $conn = connexion();
    if($conn == false){
        return array();
    }

    $limit = '';
    if($page != -1){
        $limit = ' limit '.(($page-1)*PAGINATION).','.PAGINATION;
    }

    $sql =  'SELECT '.PROPERTY.' FROM folder where count_pages <> 0';
    if($byDate == true){
        $sql .= ' order by date_add desc,id desc';
    }
    $sql .= $limit;
    $folders = array();
    foreach  ($conn->query($sql) as $row) {
        $row = convertRow($row);
        $tags = array();
        $sqlTags =  'SELECT tag.name FROM folder_tag join tag on folder_tag.id_tag = tag.id where id_folder = '.$row['id'];
        foreach  ($conn->query($sqlTags) as $rowTags) {
            array_push($tags,$rowTags['name']);
        }
        $row['tags'] = $tags;
        array_push($folders,$row);
    }

    $sql = "SELECT count(id) AS max_folder from folder;";
    foreach  ($conn->query($sql) as $row) {
        $total = $row['max_folder'];
        break;
    }

    return $folders;
}

function getAllTags($allParam = false){
    $conn = connexion();
    if($conn == false){
        return array();
    }
    $sql =  'SELECT * FROM tag order by name';
    $tags = array();
    foreach  ($conn->query($sql) as $row) {
        if($allParam){
            $tag = array();
            $tag['id'] = $row['id'];
            $tag['name'] = $row['name'];
        }else{
            $tag = $row['name'];
        }
        array_push($tags,$tag);
    }
    return $tags;
}

function getFolderById($id){
    $conn = connexion();
    if($conn == false){
        return NULL;
    }
    $sql =  'SELECT '.PROPERTY.' FROM folder where id = '.$id;
    $folders = array();
    foreach  ($conn->query($sql) as $row) {
        $row = convertRow($row);
        $tags = array();
        $sqlTags =  'SELECT tag.name FROM folder_tag join tag on folder_tag.id_tag = tag.id where id_folder = '.$row['id'];
        foreach  ($conn->query($sqlTags) as $rowTags) {
            array_push($tags,$rowTags['name']);
        }
        $row['tags'] = $tags;
        array_push($folders,$row);
    }
    return $folders[0];
}

function getByTags($tagsName){
    $conn = connexion();
    if($conn == false){
        return array();
    }

    $sql =  'SELECT '.PROPERTY.' FROM folder_tag join tag on id_tag = tag.id join folder on folder.id = id_folder where tag.name in '.convertArrayInString($tagsName);
    $folders = array();
    foreach  ($conn->query($sql) as $row) {
        $row = convertRow($row);
        $tags = array();
        $sqlTags =  'SELECT tag.name FROM folder_tag join tag on folder_tag.id_tag = tag.id where id_folder = '.$row['id'];
        foreach  ($conn->query($sqlTags) as $rowTags) {
            array_push($tags,$rowTags['name']);
        }
        $row['tags'] = $tags;
        array_push($folders,$row);
    }
    return $folders;
}

function convertRow($row){
    $result = array();
    $result['id'] = $row["id"];
    $result['name'] = $row["name"];
    $result['date_add'] = $row["date_add"];
    $result['count_pages'] = $row["count_pages"];
    $result['url'] = $row["url"];

    return $result;

}

function insertFolder($name,$countPages,$url,$tags){
    $name = str_replace("'","\'",$name);
    $url = str_replace("'","\'",$url);

    $conn = connexion();
    if($conn == false){
        return NULL;
    }

    $sqlTest = "select id from folder where name like '".$name."'";
    $first  = null;
    foreach($conn->query($sqlTest) as $row){
        $first = $row;
        break;
    }

    if($first != null){
        return $first['id'];
    }

    $sql = "insert into folder (name, date_add, count_pages, url) values ('".$name."',NOW(),".$countPages.",'".$url."')";
    $conn->query($sql);

    $sqlVerif = "select id from folder where name like '".$name."'";
    $first = null;
    foreach($conn->query($sqlVerif) as $row){
        $first = $row;
        break;
    }

    foreach($tags as $tag){
        $idTag = insertTag($tag);
        addTagToFolder($first['id'],$idTag);
    }

    return $first['id'];
}

function insertTag($tagName){
    $tagName =str_replace("'","\'",$tagName);
    $conn = connexion();
    if($conn == false){
        return NULL;
    }

    $sqlTest = "select id from tag where name like '".$tagName."'";
    $first = null;
    foreach($conn->query($sqlTest) as $row){
        $first = $row;
        break;
    }

    if($first != null){
        return $first['id'];
    }

    $sql = "insert into tag (name) values ('".$tagName."')";
    $conn->query($sql);

    $sqlVerif = "select id from tag where name like '".$tagName."'";
    $first = null;
    foreach($conn->query($sqlVerif) as $row){
        $first = $row;
        break;
    }
    return $first['id'];    
}

function addTagToFolder($idFolder, $idTag){
    $conn = connexion();
    if($conn == false){
        return NULL;
    }

    $sql = "insert into folder_tag values (".$idFolder.",".$idTag.")";
    $conn->query($sql);
}

function convertArrayInString($array){
    if(!is_array($array)){
        $array = array($array);
    }
    $array = array_map(function($elem){
        $elem = "'".$elem."'";
        return $elem;
    },$array);
    return '('.implode(',',$array).')';
}

function tagExist($tag){
    $conn = connexion();
    if($conn == false){
        return false;
    }

    $sql = "select id from tag where name like '".$tag."'";
    foreach($conn->query($sql) as $row){
        return true;
        break;

    }
    return false;
}

function search($string, &$total, $page = -1){
    $conn = connexion();
    if($conn == false){
        return array();
    }

    $limit = '';
    if($page != -1){
        $limit = ' limit '.(($page-1)*PAGINATION).','.PAGINATION;
    }

    $string = trim(substr($string, 1,strlen($string)-2));
    $arrayString = explode(" ",$string);
    $newArrayString = $arrayString;
    for($i = 0; $i < count($arrayString)-1; $i++){
        if(tagExist($arrayString[$i]." ".$arrayString[$i+1])){
            array_push($newArrayString,$arrayString[$i]." ".$arrayString[$i+1]);
        }
    }

    $whereTags = convertArrayInString($newArrayString);

    $sql =  'SELECT distinct '.PROPERTY.' FROM folder left join folder_tag on folder.id = id_folder left join tag on id_tag = tag.id where folder.count_pages and (folder.name like \'%'.$string.'%\' or tag.name in '.$whereTags.')'.' order by date_add desc,folder.id desc'.$limit;

    $folders = array();
    foreach  ($conn->query($sql) as $row) {
        $row = convertRow($row);
        $tags = array();
        $sqlTags =  'SELECT tag.name FROM folder_tag join tag on folder_tag.id_tag = tag.id where id_folder = '.$row['id'];
        foreach  ($conn->query($sqlTags) as $rowTags) {
            array_push($tags,$rowTags['name']);
        }
        $row['tags'] = $tags;
        array_push($folders,$row);
    }

    $sql = 'SELECT distinct count(folder.id) as "max_folder" FROM folder left join folder_tag on folder.id = id_folder left join tag on id_tag = tag.id where folder.count_pages <> 0 and (folder.name like \'%'.$string.'%\' or tag.name in '.$whereTags.')'.' order by date_add desc,folder.id desc';
    foreach  ($conn->query($sql) as $row) {
        $total = $row['max_folder'];
        break;
    }
    return $folders;
}

