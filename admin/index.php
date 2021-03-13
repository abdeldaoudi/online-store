<?php
    session_start();
    $noNavbar = '';
    $pageTitle = "Login";
    if(isset($_SESSION['username']))
    {
        header('Location: dashboard.php');
    }
    include "init.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        $hashedPass = sha1($pass);
        $stmt = $con->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1");
        $stmt->execute(array($user, $hashedPass));
        $count = $stmt->rowCount();
        $row = $stmt->fetch(); // Array Contain infos of user 
        //Check If $count > 0 This mean the Database Contain info About this user
        if($count > 0)
        {
            $_SESSION['username'] = $user;
            $_SESSION['id'] = $row['UserID'];
            header('Location: dashboard.php');
            exit();
        }
        else
            echo"<div class='alert alert-danger'>Password Or Username Incorret</div>";
    }
    
?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control" type="text" name="user" placeholder="username" autocomplete="off">
    <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password">
    <input class="btn btn-primary btn-block" type="submit" value="Login">
</form>


<?php include $tpl."footer.php"; ?>