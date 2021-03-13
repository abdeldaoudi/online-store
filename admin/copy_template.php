<?php
$pageTitle = "";
session_start();
if (isset($_SESSION['username']))
{
    include "init.php";
    if (isset($_GET['do']))
        $do = $_GET['do'];
    else
        $do = "manage";
    if ($do == 'manage')
    {
        echo "Welcome";
    }
    elseif ($do == 'add')
    {
    }
    elseif ($do == 'insert')
    {
    }
    elseif ($do == 'edit')
    {
    }
    elseif($do == "update")
    {
    }
    elseif($do == 'delete')
    {
    }
    elseif($do == 'activate')
    {
    }
    else
    {

    }
    include $tpl."footer.php";
}
else
{
    header('Localhost: index.php');
    exit();
} 

?>