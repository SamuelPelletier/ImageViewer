<!DOCTYPE html>
<html lang="en">
<?php include '../php/service.php'; ?>
<link href="css/main.css" rel="stylesheet">
<div id="wrapper">

    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand">
                <a href="#">
                    Start Bootstrap
                </a>
            </li>
            <li>
                <a href="#">Dashboard</a>
            </li>
            <li>
                <a href="#">Shortcuts</a>
            </li>
            <li>
                <a href="#">Overview</a>
            </li>
            <li>
                <a href="#">Events</a>
            </li>
            <li>
                <a href="#">About</a>
            </li>
            <li>
                <a href="#">Services</a>
            </li>
            <li>
                <a href="#">Contact</a>
            </li>
        </ul>
    </div>
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <h1>Simple Sidebar</h1>
            <a href="#menu-toggle" class="btn btn-secondary" id="menu-toggle">Menu</a>

            <div class="row text-center text-lg-left">
                <?php
                $parts = parse_url($_SERVER['HTTP_REFERER']);
                parse_str($parts['path'], $path);
                $path = array_keys($path)[0];
                if(preg_match('/^\/[0-9]+$/', $path)){
                    displayImages(ltrim($path,"/"));
                }else{
                    home_page_image();
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