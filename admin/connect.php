<?php

    $dsn = "mysql:host=localhost;dbname=shop"; //data source name
    $user = "root";
    $pass = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    try
    {
        $con = new PDO($dsn, $user, $pass, $option); //start a new connection with pdo
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Failed" . $e->getMessage();
    }

?>