<?php

//Insert Title To Each Page

function getTitle()
{
    global $pageTitle;
    if(isset($pageTitle))
        echo $pageTitle;
    else
        echo "Default";
}

//Redirection To Specefic URL

function redirectionHome($msg, $url=NULL, $seconds=3)
{
    if (!$url && isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
        $url = $_SERVER['HTTP_REFERER'];
    if (!$url)
        $url = 'index.php';
    echo $msg;
    echo "<div class='alert alert-info'>You Will Be Redirected After $seconds seconds</div>";
    header("refresh:$seconds;url=$url");
    exit();

}

// Check Items

function checkItem($item, $table, $value)
{
    global $con;
    $statement = $con->prepare("SELECT $item FROM $table WHERE $item=?");
    $statement->execute(array($value));
    return $statement;
}

// Count Number Of Items

function countItems($item, $table)
{
    global $con;
    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table"); //COUNT() Returns the Number of $item in The $table
    $stmt2->execute();
    return $stmt2->fetchColumn(); // fetchColumn Without Parametre Return The first Column's Value of a Result Set (In Our Exemple It Returned Value of Count())... 
}

// Function To Get Latest [items, Comments]...

function getLatest($item, $table, $order, $limit = 5)
{
    global $con;
    $stmt = $con->prepare("SELECT $item FROM $table ORDER BY $order DESC LIMIT $limit");
    $stmt->execute();
    return($stmt->fetchAll());
}