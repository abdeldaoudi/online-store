<?php
$pageTitle = "members";
session_start();
if (isset($_SESSION['username']))
{
    include "init.php";
    if (isset($_GET['do']))
        $do = $_GET['do'];
    else
        $do = "manage";
    if ($do == 'manage')
    {  //Manage Member Page 
        $query = "";
        if(isset($_GET['page']) && $_GET['page'] == "pending")
            $query = "AND RegStatus=0";
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
    ?>
        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Avatar</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registred Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                        foreach($rows as $row)
                        {
                            echo"<tr>";
                            echo '<td>' .$row["UserID"]. '</td>';
                            echo '<td> <img src=uploads/avatars/' .$row["Avatar"]. '></td>';
                            echo '<td>' .$row["Username"]. '</td>';
                            echo '<td>' .$row["Email"]. '</td>';
                            echo '<td>' .$row["FullName"]. '</td>';
                            echo '<td>' .$row["Date"]. '</td>';
                            echo '<td>
                                <a href="members.php?do=edit&userid='.$row["UserID"].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                <a href="members.php?do=delete&userid='.$row["UserID"].'" class="btn btn-danger confirm"><i class="fas fa-times"></i> Delete</a>';
                                if ($row['RegStatus'] == 0)
                                    echo '<a href="members.php?do=activate&userid='.$row["UserID"].'" class="btn btn-primary activate-btn"><i class="fas fa-check"></i> Activate</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
            <a href="?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
        </div>
    <?php 
    }
    elseif($do == 'add')
    {?>
        <h1 class="text-center">Add Member</h1>
        <div class="container">
            <form action="?do=insert" method="POST" enctype="multipart/form-data">
                <!--  Start Field Username  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="username" placeholder="Username To Login Shop" required = "required">
                    </div>
                </div> 
                <!--  End Field Username  -->

                <!--  Start Field Password  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="password" class="password form-control" name="password" autocomplete="new-password" placeholder="Password Must be Complexe" required = "required">
                        <i class="show-pass fa fa-eye fa-1x"></i>
                    </div>
                </div> 
                <!--  End Field Password  --> 

                <!--  Start Field Email  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" class="form-control" name="email" placeholder="Email Must Be Valid" required = "required">
                    </div>
                </div> 
                <!--  End Field Email  --> 

                <!--  Start Field Full name  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Full name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="fullname" placeholder="Full Name Appear In Your Profil Page" required = "required">
                    </div>
                </div> 
                <!--  End Field Full name  --> 

                <!--  Start Field Avatar  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">User Avatar</label>
                    <div class="col-sm-10 col-md-6">
                        <div class="custom-file">
                            <input type="file" name='avatar' class="custom-file-input" id="inputGroupFile02">
                            <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choose file</label>
                        </div>
                    </div>
                </div>
                <!--  End Field Avatar  -->

                <!--  Start Field Submit  -->
                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <input type="submit" class="btn btn-success" value="Add member">
                    </div>
                </div> 
                <!--  End Field Submit  -->   
            </form>
        </div>
        <?php 
    }
    elseif ($do == 'insert')
    {
        echo"<h1 class='text-center'>Insert Member</h1>";
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
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


            $name = $_POST['username'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];
            $pass = $_POST['password'];
            $hashpass = sha1($_POST['password']);

            $formErrors = array();
            if(empty($name))
                $formErrors[] = "<div class='alert alert-danger'> Username can't be <strong>Empty</strong></div>";
            if(empty($pass))
                $formErrors[] = "<div class='alert alert-danger'> Password can't be <strong>Empty</strong></div>";
            if(empty($email))
                $formErrors[] = "<div class='alert alert-danger'> Email can't be <strong>Empty</strong></div>";
            if(empty($fullname))
                $formErrors[] = "<div class='alert alert-danger'> Full name can't be <strong>Empty</strong></div>";
            if (!empty($avatarName) &&!in_array($ext, $avatarAllowedExtension))
                $formErrors[] = "<div class='alert alert-danger'> This Extension is Not <strong>Allowed</strong></div>";
            //if (empty($avatarName))
                //$formErrors[] = "<div class='alert alert-danger'> Avatar is <strong>Required</strong></div>";

            //Update The DataBase With This infos

            if (empty($formErrors))
            {
                if(empty($avatarName))
                    $av = "inko.png";
                else
                {
                    $av = rand(0, 100000) . '_' . $avatarName;
                    move_uploaded_file($avatarTmp, "uploads\avatars\\" . $av);
                }

                $stmt = checkItem("Username", "users", $name);
                if ($stmt->rowCount() == 0)
                {
                    //Insert UserInfos In DataBase
                    $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FullName, Avatar, RegStatus, Date) 
                                        VALUES(:xuser, :xpass, :xemail, :xname, :xavatar, 1, now())");
                    $stmt->execute(array(
                        'xuser'     => $name,
                        'xpass'     => $hashpass,
                        'xemail'    => $email,
                        'xname'     => $fullname,
                        'xavatar'   => $av
                    ));
                    redirectionHome("<div class='alert alert-success'>".$stmt->rowCount()." member added </div>", "members.php");
                }
                else
                    redirectionHome("<div class='alert alert-warning'> This Username Is Already Taken </div>");
            }
            else
                redirectionHome(implode('',$formErrors)); //Concatenate the array of Errors
        }
        else
            redirectionHome("<div class='alert alert-danger'>Sorry you can't browse this page directly</div>");
        echo '</div>';
    }
    elseif ($do == 'edit'){
        
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
        
        //Select All data Depend Id
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?");
        $stmt->execute(array($userid));
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        if($count > 0)
        { ?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form action="?do=update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="userid" value = "<?php echo $userid?>">
                    <!--  Start Field Username  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="username" value="<?php echo $row['Username']?>" required = "required">
                        </div>
                    </div> 
                    <!--  End Field Username  -->

                    <!--  Start Field Password  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password']?>">
                            <input type="password" class="form-control" name="newpassword" autocomplete="new-password" placeholder="Leave Blank If You Don't Want To Change Your Password">

                        </div>
                    </div> 
                    <!--  End Field Password  --> 

                    <!--  Start Field Email  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" class="form-control" name="email" value="<?php echo $row['Email']?>" required = "required">
                        </div>
                    </div> 
                    <!--  End Field Email  --> 

                    <!--  Start Field Full name  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Full name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="fullname" value="<?php echo $row['FullName']?>" required = "required">
                        </div>
                    </div> 
                    <!--  End Field Full name  -->

                    <!--  Start Field Avatar  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">User Avatar</label>
                        <div class="col-sm-10 col-md-6">
                            <div class="custom-file">
                                <input type="file" name='avatar' class="custom-file-input" id="inputGroupFile02" >
                                <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Leave Blank If You Don't Want To Change Your Avatar</label>
                            </div>
                        </div>
                    </div>
                    <!--  End Field Avatar  -->

                    <!--  Start Field Submit  -->
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <input type="submit" class="btn btn-success" value="save">
                        </div>
                    </div> 
                    <!--  End Field Submit  -->   
                </form>
            </div>
            <?php
        }
        else
            redirectionHome("<div class='alert alert-danger'>There is no such Id</div>");
    }
    elseif($do == "update")
    {
        echo"<h1 class='text-center'>Update Member</h1>";
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
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

            $id = $_POST['userid'];
            $name = $_POST['username'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : $pass = sha1($_POST['newpassword']);

            $formErrors = array();
            $stmt = $con->prepare("SELECT * FROM users WHERE Username=? AND UserID != ?");
            $stmt->execute(array($name, $id));
            if($stmt->rowCount() > 0)
                $formErrors[] = "<div class='alert alert-danger'> Username Is Already Taken</div>";
            if(empty($name))
                $formErrors[] = "<div class='alert alert-danger'> Username can't be <strong>Empty</strong></div>";
            if(empty($email))
                $formErrors[] = "<div class='alert alert-danger'> Email can't be <strong>Empty</strong></div>";
            if(empty($fullname))
                $formErrors[] = "<div class='alert alert-danger'> Full name can't be <strong>Empty</strong></div>";
            if (!empty($avatarName) &&!in_array($ext, $avatarAllowedExtension))
                $formErrors[] = "<div class='alert alert-danger'> This Extension is Not <strong>Allowed</strong></div>";
            //Update The DataBase With This infos

            if (empty($formErrors))
            {
                if(empty($avatarName))
                {
                    $stmt = $con->prepare("SELECT * From Users WHERE UserID = ?");
                    $stmt->execute(array($id));
                    $r = $stmt->fetch();
                    $av = $r['Avatar'];
                }
                else
                {
                    $av = rand(0, 100000) . '_' . $avatarName;
                    move_uploaded_file($avatarTmp, "uploads\avatars\\" . $av);
                }
                $stmt = $con->prepare("UPDATE users SET Username=?, Email=?, FullName=?, Password=?, Avatar=? WHERE UserID=?");
                $stmt->execute(array($name, $email, $fullname, $pass, $av, $id));
                /*if ($stmt->rowCount() != 0)
                    redirectionHome("<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated </div>","members.php");
                else
                    redirectionHome("<div class='alert alert-warning'>" . $stmt->rowCount() . " Record Updated </div>");
            */ }
            else
                redirectionHome(implode($formErrors, ""));

        }
        else
            redirectionHome("<div class='alert alert-danger'>Sorry you can't browse this page directly</div>");
        echo '</div>';
    }
    elseif($do == 'delete')
    {
        echo '<h1 class="text-center">Delete Member</h1>';
        echo '<div class="container">';
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID=?");
            $stmt->execute(array($userid));
            $row = $stmt->fetch();
            if ($stmt->rowCount() > 0 && $row['GroupID'] != 1)
            {
                $stmt = $con->prepare("DELETE FROM users WHERE UserID=?");
                $stmt->execute(array($userid));
                redirectionHome("<div class='alert alert-success'> Member Deleted </div>");
            }
            else
                redirectionHome("<div class='alert alert-danger'>There is no Member With This ID</div>");
        echo '</div>';
    }
    elseif($do == 'activate')
    {
        echo '<h1 class="text-center">Activate Member</h1>';
        echo '<div class="container">';
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
            $stmt = checkItem("UserID", "users", $userid);
            if ($stmt->rowCount() > 0)
            {
                $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID=?");
                $stmt->execute(array($userid));
                redirectionHome("<div class='alert alert-success'> Member Activated </div>");
            }
            else
                redirectionHome("<div class='alert alert-danger'>There is no Member With This ID</div>");
        echo '</div>';
    }
    else
        redirectionHome("<div class='alert alert-danger'>There is no Page With This Name</div>");
    include $tpl."footer.php";
}
else
{
    header('Localhost: index.php');
    exit();
} 

?>