<!DOCTYPE html>
<html lang="en">
<?php include '../php/service.php'; ?>
<script src="js/paginator.js"></script>
<link href="css/main.css" rel="stylesheet">
<div id="wrapper" class="toggled">

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
                home_page_image();
                ?>
            </div>


        </div>
    </div>

    <!-- Menu Toggle Script -->
    <script>
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
</div>
</html>