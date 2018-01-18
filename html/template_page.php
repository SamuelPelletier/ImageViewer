<!DOCTYPE html>
<html lang="en">
<?php include '../php/service.php'; ?>
<link href="css/main.css" rel="stylesheet">
<div id="wrapper">

    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand">
                <a href="/">
                    <?php echo TITLE; ?>
                </a>
            </li>
            <li>
                <a href="/">Dashboard</a>
            </li>
            <li>
                <a href="./import">Import</a>
            </li>
            <li>
                <a href="./about">About</a>
            </li>
        </ul>
    </div>
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <a href="#menu-toggle" class="btn btn-secondary" id="menu-toggle">Menu</a>
                <?php
                $parts = parse_url($_SERVER['HTTP_REFERER']);
                //parse_str($parts['path'], $path);
		        parse_str($parts['query'], $path);
                //$path = array_keys($path)[0];
                if($parts['path'] == '/' && $path['number'] != null){
                    $tabs = scandirByModifiedDate(PATH);
                    $name = $tabs[sizeof($tabs) - $path['number']];
                    echo "<h1>".$name."</h1><div class=\"row text-center text-lg-left\">";
                    echo "<h2><a href='".PATH."/".$name."/' download='".$name."'>Download</a></h2><div class=\"row text-center text-lg-left\">";
                    displayImages(ltrim($path['number'],"/"));
                }else if($parts['path'] == '/import/' && $path['number'] != null){
                    $tabs = scandirByModifiedDate(PATH_IMPORT);
                    $name = $tabs[sizeof($tabs) - $path['number']];
                    echo "<h1>".$name."</h1><div class=\"row text-center text-lg-left\">";
                    echo "<h2><a href='".$path."'>Download</a></h2><div class=\"row text-center text-lg-left\">";
                    displayImagesImport(ltrim( $path['number'],"/"));
                }else if($parts['path'] == "/import" or $parts['path'] == "/import/"){
                    ?><h1>Import</h1><div class="row text-center text-lg-left"><?php
                    home_page_import();
                }else if($parts['path'] == "/about" or $parts['path'] == "/about/"){
                    ?><h1>About</h1><div class="row text-center text-lg-left"><?php
                    home_page_about();
                }else{
                    ?><h1>Simple Sidebar</h1><div class="row text-center text-lg-left"><?php
                        home_page();
                }
                ?>

            </div>


        </div>
    </div>

    <!-- Menu Toggle Script -->
    <script>
        if (sessionStorage.getItem("menu_toggled") == ("open" || "false") && $("#wrapper").hasClass('toggled') == false){
            $("#wrapper").addClass('toggled')
            sessionStorage.setItem("menu_toggled","open")
        }
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
            console.log(sessionStorage.getItem("menu_toggled"))
            if(sessionStorage.getItem("menu_toggled") == "close"){
                sessionStorage.setItem("menu_toggled","open")
            }else{
                sessionStorage.setItem("menu_toggled","close")
            }
        });
    </script>
</div>
</html>