<?php
include(__DIR__ ."/config.php");

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
            $number != null ? displayImages(ltrim($number,"/")) : home_page_all();
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
    $svg = '<g class="group"></g><svg class="eye" viewBox="0 0 100 100"><path style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:5;" d="m 12.5,50 c 0,0 11.494049,-22.5 37.5,-22.5 26.005951,0 37.5,22.5 37.5,22.5 0,0 -11.494049,22.5 -37.5,22.5 -26.005951,0 -37.5,-22.5 -37.5,-22.5 z" /><ellipse style="fill:none;stroke:#0e232e;stroke-width:5;" cx="49.999996" cy="50" rx="20.208101" ry="20.208103" /><path style="fill:#0e232e;stroke:#0e232e;stroke-width:2;" d="M 50 39.283203 A 10.717515 10.717515 0 0 0 46.544922 39.869141 A 5 5 0 0 1 47.111328 42.175781 A 5 5 0 0 1 42.111328 47.175781 A 5 5 0 0 1 39.837891 46.623047 A 10.717515 10.717515 0 0 0 39.283203 50 A 10.717515 10.717515 0 0 0 50 60.716797 A 10.717515 10.717515 0 0 0 60.716797 50 A 10.717515 10.717515 0 0 0 50 39.283203 z " /> <g class="hand"><path sodipodi:nodetypes="cccccccccccccc" inkscape:connector-curvature="0" id="path4662" d="m 42.67293,91.274624 -2.967966,-5.124005 -7.477198,-7.614578 -6.75,-7.75 -2.798356,-17.636982 -1.29214,-4.455653 -0.847004,-3.344865 2.625,-0.5 6.5,7.0625 5.140951,7.423954 2.609049,-1.736454 -0.125,-18.9375 0.4375,-18.875 5.063981,-1.212871 4.873519,-2.412129 3.625,-4.3125 6.596253,3.627945 7.153747,1.997055 2,16.375 7.1875,1.625 1.25,37.875 -3.875,9.4375 -2.0625,8.125 -0.1875,1.75 z" style="fill: #e7ddc4;fill-rule:evenodd;stroke:#0e232e;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" /> <path sodipodi:nodetypes="ccccccccccccccccccccccccccccssccccccssccccscccccccccccccccccccccccccccccccccccccccccc" style="fill:#0e232e;fill-opacity:1;stroke:#0e232e;stroke-width:2;stroke-opacity:1" id="path4620" d="m 74.421955,80.771652 c 4.101679,-3.522444 3.519968,-17.941442 2.41348,-35.016506 -0.222783,-3.428381 -0.413386,-6.388917 -0.460418,-8.433568 -0.151079,-3.097072 -2.765512,-5.496302 -5.864138,-5.381443 -0.914462,-0.02479 -1.812662,0.245109 -2.562003,0.76984 l -0.566859,-13.206071 c -0.275728,-3.179533 -3.060748,-5.543818 -6.242868,-5.299755 -1.175044,0.139507 -2.294805,0.577526 -3.25263,1.272337 -0.09384,-1.459873 -0.731534,-2.831531 -1.787213,-3.844239 -1.131558,-1.127088 -2.684492,-1.7270644 -4.279905,-1.6535442 -3.19536,0.1135942 -5.723377,2.7435202 -5.710665,5.9408742 v 0.673299 c -0.976198,-0.693217 -2.155456,-1.041595 -3.351644,-0.990147 h -0.03466 c -1.672122,9.1e-5 -3.270695,0.687495 -4.421001,1.90108 -1.173893,1.217969 -1.830404,2.84328 -1.831769,4.534868 -0.123768,8.455846 -0.386157,28.238959 -0.175751,29.352874 0.321315,2.399402 0.167394,4.838579 -0.452992,7.178556 -0.195554,0.190604 -0.789641,0.764888 -1.034702,0.764888 0,0.01485 -0.339125,0.01485 -1.128766,-0.769838 -0.848968,-3.523945 -2.082498,-6.94396 -3.678393,-10.198503 -4.329412,-6.767646 -8.341977,-5.876515 -10.342072,-4.740323 -3.445708,1.953064 -1.759985,4.928451 -0.856477,6.527536 0.541333,0.731428 0.869487,1.598508 0.948066,2.50507 -0.559433,3.36402 4.131383,22.337689 9.936112,26.580464 1.304518,0.955493 2.992716,2.052078 4.641309,3.126385 1.872192,1.145118 3.668882,2.409332 5.378968,3.784833 v 5.124005 h -3.571952 c -0.820264,0 -1.485219,0.664955 -1.485219,1.485219 v 11.000937 c -10e-4,1.98132 2.96941,1.98132 2.970438,0 v -9.518194 h 35.95467 v 9.518194 c -0.001,1.98132 2.969409,1.98132 2.970437,0 v -11.003414 c 0,-0.820263 -0.664956,-1.485218 -1.485219,-1.485218 h -3.933354 c -0.146046,-2.398628 -0.03713,-7.636499 3.297186,-10.500496 z m -31.749025,10.502972 v -5.50026 c 0,-1.381253 -1.274813,-2.349122 -6.728042,-5.896317 -1.601561,-1.042128 -3.240252,-2.109013 -4.507639,-3.034796 -4.740323,-3.465511 -9.141521,-21.374772 -8.757839,-23.694192 0.10154,-1.590399 -0.355481,-3.166332 -1.29214,-4.455653 -1.123816,-1.980295 -1.00995,-2.054553 -0.26239,-2.475365 2.40853,-1.371355 4.797258,1.289664 6.371589,3.755125 1.466556,3.115388 2.607959,6.373698 3.406101,9.723235 0.06225,0.240784 0.183962,0.462082 0.353978,0.643592 0.835193,1.106876 2.087873,1.822695 3.46551,1.980294 1.230929,-0.0856 2.375989,-0.661696 3.178369,-1.599085 1.616412,-1.564433 1.70305,-7.770172 1.316894,-9.799972 -0.131195,-1.237681 0.05693,-17.91421 0.215355,-28.833043 -0.0058,-0.940716 0.355629,-1.846581 1.007474,-2.524871 0.612216,-0.64249 1.464197,-1.001219 2.351597,-0.990146 v 0 c 0.778035,-0.05444 1.53916,0.244736 2.07188,0.814395 0.708048,0.688743 1.112478,1.631226 1.123815,2.618935 l 0.06931,31.588127 c 10e-4,1.979263 2.971464,1.979263 2.970437,0 l 0.05199,-37.692376 c -0.009,-1.590127 1.247608,-2.899394 2.836767,-2.955587 0.783804,0.0031 1.539014,0.294754 2.121388,0.819347 0.582647,0.565585 0.90598,1.3466 0.893607,2.158518 l 1.064406,26.199259 0.220307,12.037698 c 0.01319,0.779763 0.627238,1.416508 1.406008,1.457989 0.769473,0.01323 1.431303,-0.541989 1.552053,-1.302043 l 0.10397,-0.977768 -0.727757,-33.328308 c -0.05859,-0.65323 0.1806,-1.298037 0.651021,-1.755033 0.615726,-0.62438 1.429735,-1.014754 2.302089,-1.104012 1.774837,-0.272291 2.762507,1.589184 2.925881,2.631312 l 1.237682,28.664721 0.247537,7.705811 c 0.03574,1.979171 3.006177,1.927181 2.970437,-0.05199 l -0.552006,-17.055262 c 0.398533,-4.138812 1.703051,-4.138812 2.193173,-4.138812 1.471838,-0.08161 2.747587,1.008774 2.896176,2.475365 0.04703,2.109013 0.247537,5.099253 0.465369,8.562288 0.673299,10.396529 1.925834,29.726653 -1.383729,32.568369 -4.242775,3.641264 -4.502688,9.775217 -4.344265,12.755556 z" inkscape:connector-curvature="0" /> </g></svg>';

    echo "<h1>About</h1><div class='row text-center text-lg-left about'>";
    echo '<h2>Hello World !</h2>';
    echo '<div>'.TITLE.' , it\'s '.count(scandirByModifiedDate(PATH_ALL)).' images folders ! </div>'; 
    echo '<div><a>You can contact us at </a><span class="email">'.EMAIL.'</span></div>';
    echo '<div><a>You can join us on <a class="email" href="'.CHANNEL_LINK.'">'.CHANNEL_NAME.'</a></a></div>';
    echo '<a style="font-weight:bold;">Use the search field ↑ for research in the tag list</a>';
    echo '<div id="list-tag"><h2>List of tags</h2><ul class="list" >';
    $tagNames = getAllTags();
    foreach($tagNames as $tagName => $data){
        echo '<li class="list-item" ><a class="about-tag">'.$tagName.' </a>( '.count($data).' )<a class="hide-tag" onclick="addHidenTags('."'".$tagName."'".',$(this))">'.$svg.'</a><a class="show-tag" onclick="removeHidenTags('."'".$tagName."'".',$(this))">'.$svg.'</a></li>';
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
    $searchFolders = array_slice($searchFolders, ($page - 1) * PAGINATION, PAGINATION);
    $size = sizeof($searchFolders) < PAGINATION ? sizeof($searchFolders) : PAGINATION;
    if($size > 0){
        echo '<script>createPagination(' . PAGINATION . ',' . $total. ',' . $page . ')</script>';
        for ($i = 0; $i < $size; $i++) {
            $firstImage = array_values(array_diff(scandir($pathConst . "/" . $searchFolders[$i]), array(".", "..")))[0];
            $name = $searchFolders[$i];
            $tags = getAllTagByFolder($name);
            $cookieTags = json_decode ($_COOKIE["HidenTags"]);
            $blur = "";
            if(count(array_intersect($tags, $cookieTags)) != 0){
                $blur = "blur";
            }
            $link = sizeof($allFolders)-array_search($name, $allFolders, true);
            $title = str_replace('♯','#',str_replace('‰','%',str_replace('⸮','?',$name)));
            list($width, $height, $type, $attr) = getimagesize($pathConst . $name. "/" . $firstImage);
            $bigThumbnail = '';
            if($height > 2000){
                $bigThumbnail = 'big-thumbnail';
            }
            echo '
                <div class="col-lg-3 col-md-4 col-xs-6">
                        <a href="'.$pageName.'?number=' . $link . '" class="d-block mb-4 h-100 img-cell">
                            <h5 class="img-name" title="' . $title . '">' . $title . '</h5>
                            <img class="img-fluid img-thumbnail '.$blur.' '.$bigThumbnail.'" src="' . $pathConst . $name. "/" . $firstImage . '" alt="">
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
    $clearName = str_replace('♯','#',str_replace('‰','%',str_replace('⸮','?',$name)));
    echo "<h1>".$clearName."</h1><div class=\"row text-center text-lg-left\">";
    /*
    echo '<div class="tag-container">
    <h4>Tags</h4>
    <div class="dropdown">
      <span class="add-tag">Add</span>
      <span class="modify-tag">Modify</span>
      <span class="valid-tag">Valid</span>
      <span class="help-us">Help us by adding tags !</span>
      <ul class="dropdown-menu">';
    */
    echo '<div class="tag-container">
    <h4>Tags</h4>
    <div class="dropdown">
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

    $parts = parse_url($_SERVER['HTTP_REFERER']);
    parse_str($parts['query'], $url );
    
    $id = $pathConst == PATH_ALL ? $url["number"] : getId($name);
    echo '<div class="pulse-div-add"><button class="pulse" onclick="addFavorite('.$id.','."'".$clearName."'".')">Add as favorite</button></div>';
    echo '<div class="pulse-div-remove"><button class="pulse" onclick="removeFavorite('.$id.')">Remove as favorite</button></div>';

    $name = "'/php/download.php?name=" . urlencode($name) . "&path=".$pathConst."'";
    echo '<h2 id="download-title"><a class="download" onclick="window.open('.$name.')"></a></h2><div class="row text-center text-lg-left">';
    


    for ($i = 2; $i < sizeof($tabs) + 2; $i++) {
        $src = $path . '/' . $tabs[$i];
        $src = str_replace("'", "\'", $src);
        $src = "'" . $src . "'";
        echo '
            <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="#" class="d-block mb-4 h-100 img-cell" onclick="viewer(' . $src . ')">
                        <img class="img-fluid img-thumbnail" src="' . $path . '/' .  $tabs[$i] . '" alt="">
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
    if($dir == PATH_ALL){
        $cache = PATH_CACHE_ALL;
    }else if($dir == PATH){
        $cache = PATH_CACHE_BEST;
    }
    // (Date last modif + 1 day) - date now
    $diff_date = (filemtime($cache) + 86400) - time();
    $json = file_get_contents($cache);
    $files = json_decode($json,true);

    // Last modif after one day
    if($diff_date < 0 || count($files) == 0){
        $files = array();
        foreach (scandir($dir) as $key => $file) {
            if (in_array($file, $ignored)) continue;
            $files[$file] = filemtime($dir . '/' . $file);
        }
        arsort($files);
        $files = array_keys($files);
        file_put_contents($cache,json_encode($files));
    }

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
       $result = array_unique(array_merge($tagData,$result));
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
    return array_reverse($result);
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

function getId($name){
    $allFolders = scandirByModifiedDate(PATH_ALL);
    $folderId;
    foreach($allFolders as $id => $folder){
        if($name === $folder){
            $folderId = $id;
            break;
        }
    }
    return sizeof($allFolders) - $folderId;
}