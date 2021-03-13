<?php
    include "connect.php";
    $tpl = "includes/templates/";
    $func = "includes/functions/";
    $css = "layout/css/";
    $js = "layout/js/";
    $language = "includes/languages/";

    //Include the Importantes Files
    include $language . "english.php";
    include $func . "function.php";
    include $tpl . "header.php" ;

    //Include The Navbar In All Files Except The One With $noNavbar Variable 

    if(!isset($noNavbar))
        include $tpl."navbar.php" ;
?>