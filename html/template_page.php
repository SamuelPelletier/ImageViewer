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
                <a href="/">Best</a>
            </li>
            <li>
                <a href="/all">All</a>
            </li>
            <li>
                <a href="/import">Import</a>
            </li>
            <li>
                <a href="/upload">Upload</a>
            </li>
            <li>
                <a href="/about">About</a>
            </li>
        </ul>
    </div>
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <a href="#menu-toggle" class="btn btn-secondary" id="menu-toggle">Menu</a>
                <?php
                $parts = parse_url($_SERVER['HTTP_REFERER']);
		        parse_str($parts['query'], $path);
		        $number = $path['number'];

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
                        $number != null ? displayImages(ltrim($number,"/")) : home_page();
                }
                ?>
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