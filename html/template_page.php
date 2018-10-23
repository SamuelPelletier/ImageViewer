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
                <a href="/">All</a>
            </li>
            <li>
                <a href="/?number=<?php $folders = getAllFolder($total); echo $folders[array_rand($folders)]['id'] ?>">Random</a>
            </li>
            <li>
                <a href="/about">About</a>
            </li>
        </ul>
    </div>
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <a href="#menu-toggle" class="btn btn-secondary" id="menu-toggle">Menu</a>
            <input type="text" name="search" id="search" placeholder="Tags or name"><button class="searchButton" onclick="search()">Search</button>
            <section class="container-favorite">
                <div class="dropdown-favorite">
                    <select class="dropdown-favorite-select" onchange='chooseFavorite(this.value)'>
                        <option value="" disabled selected>Your favorite...</option>
                    </select>
                </div>
            </section>
            <?php
                check_routing();
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
            if(sessionStorage.getItem("menu_toggled") == "close"){
                sessionStorage.setItem("menu_toggled","open")
            }else{
                sessionStorage.setItem("menu_toggled","close")
            }
        });
    </script>
</div>
</html>