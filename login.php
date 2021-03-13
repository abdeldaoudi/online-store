<?php 
    $pageTitle = 'login';
    $noNavBar = "";
    session_start();
    if(isset($_SESSION['user']))
        header('Location: index.php');

    include "init.php"; 

    if ($_SERVER['REQUEST_METHOD'] == "POST")
    {
        if (isset($_POST['login']))
        {
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedPass = sha1($pass);
            $stmt = $con->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password = ?");
            $stmt->execute(array($user, $hashedPass));
            $count = $stmt->rowCount();
            $row = $stmt->fetch(); // Array Contain infos of user 
            //Check If $count > 0 This mean the Database Contain info About this user
            if($count > 0)
            {
                $_SESSION['user'] = $user;
                $_SESSION['uid'] = $row['UserID'];
                header('Location: index.php');
                exit();
            }
            else
                echo "<div class='alert alert-danger'>Password Or Username Incorret</div>";
        }
        else
        {
            $formErrors = array();
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['fullname'])
                && isset($_POST['password']) && isset($_POST['confirm-password']))
            {
                $avatar = $_FILES['avatar'];
                $avatarName = $avatar['name'];
                $avatarSize = $avatar['size'];
                $avatarTmp = $avatar['tmp_name'];
                $avatarType = $avatar['type'];

                // List Of Allowed File Types
                $avatarAllowedExtension = array('jpeg', 'jpg', 'png', 'gif');
                $avatarExtension = explode('.', $avatarName);
                $ext = strtolower(end($avatarExtension));

                $user = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
                $pass = sha1($_POST['password']);
                $confPass = sha1($_POST['confirm-password']);

                if(empty($user))
                    $formErrors[] = "<div class='alert alert-danger'> Username can't be <strong>Empty</strong></div>";
                if(empty($_POST['password']))
                    $formErrors[] = "<div class='alert alert-danger'> Password can't be <strong>Empty</strong></div>";
                if($pass !== $confPass)
                    $formErrors[] = "<div class='alert alert-danger'> Passwords Does not <strong>Match</strong></div>";
                if(empty($email))
                    $formErrors[] = "<div class='alert alert-danger'> Email can't be <strong>Empty</strong></div>";
                if (filter_var($email, FILTER_VALIDATE_EMAIL) != true)
                    $formErrors[] = "<div class='alert alert-danger'> You Must Enter A <strong>Valid Email</strong></div>";
                if(empty($fullname))
                    $formErrors[] = "<div class='alert alert-danger'> Full name can't be <strong>Empty</strong></div>";
                if (!empty($avatarName) &&!in_array($ext, $avatarAllowedExtension))
                    $formErrors[] = "<div class='alert alert-danger'> This Extension is Not <strong>Allowed</strong></div>";
                
                if (empty($formErrors))
                {
                    if(empty($avatarName))
                    $av = "inko.png";
                    else
                    {
                        $av = rand(0, 100000) . '_' . $avatarName;
                        move_uploaded_file($avatarTmp, "admin\uploads\avatars\\" . $av);
                    }
                    $stmt = checkItem("Username", "users", $user);
                    if ($stmt->rowCount() == 0)
                    {
                        //Insert UserInfos In DataBase
                        $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FullName, Avatar, Date) 
                                            VALUES(:xuser, :xpass, :xemail, :xname, :ximage, now())");
                        $stmt->execute(array(
                            'xuser'  => $user,
                            'xpass'  => $pass,
                            'xemail' => $email,
                            'xname'  => $fullname,
                            'ximage' => $av
                        ));
                        echo "<div class='alert alert-success'>Congrats You Are Registred Now</div>";
                    }
                    else
                        echo "<div class='alert alert-danger'> Username Is Already Taken </div>";
                }
                else
                    echo implode('',$formErrors); //Concatenate the array of Errors        
            }

        }
    }
    
?>

<div class="container login-page">

    <h1 class="text-center ">
        <span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span>
    </h1>

    <!-- Start Login Form  -->

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" class="login">
        <div class="form-group row">
            <div class="col-12">
                <input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off" required="required">
            </div> 
        </div> 
        <div class="form-group row">
            <div class="col-12">
                <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="new-password" required="required">
            </div> 
        </div> 
        <input type="submit" class="btn btn-primary btn-block" name="login" value="Login">
        
    </form>

    <!-- End Login Form  -->

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" class="signup" >
        <div class="form-group row">
            <div class="col-12">
                <input type="text" class="form-control" name="username" pattern=".{4,}" title="Username Must Have More Than 4 Characters" placeholder="Username" autocomplete="off" required="required" >
            </div> 
        </div>
        <div class="form-group row">
            <div class="col-12">
                <div class="custom-file">
                    <input type="file" name='avatar' class="custom-file-input" id="inputGroupFile02">
                    <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">User Avatar</label>
                </div>
            </div>
        </div> 
        <div class="form-group row">
            <div class="col-12">
                <input type="text" class="form-control" name="email" placeholder="A valid Email" autocomplete="off" required="required">
            </div> 
        </div> 
        <div class="form-group row">
            <div class="col-12">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name" autocomplete="off"required="required">
            </div> 
        </div> 
        <div class="form-group row">
            <div class="col-12">
                <input type="password" class="form-control" name="password" minlength="6" placeholder="Password" autocomplete="new-password" required="required" >
            </div> 
        </div> 
        <div class="form-group row">
            <div class="col-12">
                <input type="password" class="form-control" name="confirm-password" minlength="6" placeholder="Confirm Password" autocomplete="new-password" required="required">
            </div> 
        </div>
        <input type="submit" class="btn btn-success btn-block" name="signup" value="Signup">
    </form>
</div>

<?php include $tpl."footer.php"; ?>