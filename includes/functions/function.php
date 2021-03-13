<?php


// Function To Get Categories From Database

function getCat($where = NULL)
{
    global $con;

    if($where == NULL)
        $sql = "";
    else
        $sql = "WHERE ". $where;
    $stmt = $con->prepare("SELECT * FROM categories $sql ORDER BY ID ASC");
    $stmt->execute();
    return($stmt->fetchAll());
}

// Function To Get Items From Database

function getItems($where, $value, $approve = NULL)
{
    global $con;
    $sql = ($approve == NULL) ? "AND Approve=1": "";
    $stmt = $con->prepare("SELECT * FROM items WHERE $where=? $sql ORDER BY ItemID dESC");
    $stmt->execute(array($value));
    return($stmt->fetchAll());
}



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
    //exit();
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

//Function To Check user's Status

function  CheckUserStatus($user)
{
    global $con;
    $stmt = $con->prepare("SELECT Username, RegStatus FROM users WHERE Username = ? AND RegStatus = 0");
    $stmt->execute(array($user));
    return $stmt->rowCount();
}
