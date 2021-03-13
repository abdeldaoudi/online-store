<?php 

    if (isset($_GET['do']))
        $do = $_GET['do'];
    else
        $do = "manage";
    if ($do == 'manage')
    {
        echo "Welcome To manage Category Page<br>";
        echo "<a href='page.php?do=add'>Add New Category + </a>";
    }
    elseif ($do == 'add')
        echo "Welcome To add Category Page";
    elseif ($do == 'insert')
        echo "Welcome To insert Category Page";
    else
        echo "There is no Page With This Name";



?>