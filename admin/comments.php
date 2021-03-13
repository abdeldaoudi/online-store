<?php
$pageTitle = "comments";
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
        $stmt = $con->prepare("SELECT comments.*, users.Username, items.Name AS ItemName
                                FROM comments
                                INNER JOIN users ON comments.UserID = users.UserID
                                INNER JOIN items ON comments.ItemID = Items.ItemID
                                ORDER BY ComID DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
    ?>
        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Comment</td>
                        <td>Item Name</td>
                        <td>Username</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                        foreach($rows as $row)
                        {
                            echo"<tr>";
                            echo '<td>' .$row["ComID"]. '</td>';
                            echo '<td>' .$row["Comment"]. '</td>';
                            echo '<td>' .$row["ItemName"]. '</td>';
                            echo '<td>' .$row["Username"]. '</td>';
                            echo '<td>' .$row["ComDate"]. '</td>';
                            echo '<td>
                                <a href="comments.php?do=edit&comid='.$row["ComID"].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                <a href="comments.php?do=delete&comid='.$row["ComID"].'" class="btn btn-danger confirm"><i class="fas fa-times"></i> Delete</a>';
                                if ($row['Status'] == 0)
                                    echo '<a href="comments.php?do=approve&comid='.$row["ComID"].'" class="btn btn-primary activate-btn"><i class="fas fa-check"></i> Approve</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
        </div>
    <?php 
    }
    elseif ($do == 'edit'){
        
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? $_GET['comid'] : 0;
        
        //Select All data Depend Id
        $stmt = $con->prepare("SELECT * FROM comments WHERE ComID = ?");
        $stmt->execute(array($comid));
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        if($count > 0)
        { ?>
            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <form action="?do=update" method="POST">
                    <input type="hidden" name="comid" value = "<?php echo $comid?>">
                    <!--  Start Field Comment  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-6">
                            <textarea class="form-control" name="comment" required = "required"><?php echo $row['Comment']?></textarea>
                        </div>
                    </div> 
                    <!--  End Field Comment  -->

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
            redirectionHome("<div class='alert alert-danger'>There is no Comment With This id</div>");
    }
    elseif($do == "update")
    {
        echo"<h1 class='text-center'>Update Comment</h1>";
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $id = $_POST['comid'];
            $comment = $_POST['comment'];

            //Update The DataBase With This infos
            $stmt = $con->prepare("UPDATE comments SET Comment=? WHERE ComID=?");
            $stmt->execute(array($comment , $id));
            // echo "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated </div>";
            if ($stmt->rowCount() != 0)
                redirectionHome("<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated </div>","comments.php");
            else
                redirectionHome("<div class='alert alert-warning'>" . $stmt->rowCount() . " Record Updated </div>");

        }
        else
            redirectionHome("<div class='alert alert-danger'>Sorry you can't browse this page directly</div>");
        echo '</div>';
    }
    elseif($do == 'delete')
    {
        echo '<h1 class="text-center">Delete Comment</h1>';
        echo '<div class="container">';
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? $_GET['comid'] : 0;
            $stmt = $con->prepare("SELECT * FROM comments WHERE ComID=?");
            $stmt->execute(array($comid));
            $row = $stmt->fetch();
            if ($stmt->rowCount() > 0)
            {
                $stmt = $con->prepare("DELETE FROM comments WHERE ComID=?");
                $stmt->execute(array($comid));
                redirectionHome("<div class='alert alert-success'> Comment Deleted </div>");
            }
            else
                redirectionHome("<div class='alert alert-danger'>There is no Comment With This ID</div>");
        echo '</div>';
    }
    elseif($do == 'approve')
    {
        echo '<h1 class="text-center">Approve Comment</h1>';
        echo '<div class="container">';
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? $_GET['comid'] : 0;
        $stmt = checkItem("ComID", "comments", $comid);
            if ($stmt->rowCount() > 0)
            {
                $stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE ComID=?");
                $stmt->execute(array($comid));
                redirectionHome("<div class='alert alert-success'> Comment Approved </div>");
            }
            else
                redirectionHome("<div class='alert alert-danger'>There is no Comment With This ID</div>");
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