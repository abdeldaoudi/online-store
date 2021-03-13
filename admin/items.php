<?php
$pageTitle = "Items";
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
        $stmt = $con->prepare("SELECT items.*, categories.Name AS CatName, users.Username 
                                FROM items 
                                INNER JOIN categories ON items.CatID = categories.ID
                                INNER JOIN users ON items.UserID = users.UserID
                                ORDER BY ItemID DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
    ?>
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Category</td>
                        <td>Username</td>
                        <td>Adding Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                        foreach($rows as $row)
                        {
                            echo"<tr>";
                            echo '<td>' .$row["ItemID"]. '</td>';
                            echo '<td>' .$row["Name"]. '</td>';
                            echo '<td>' .$row["Description"]. '</td>';
                            echo '<td>' .$row["Price"]. '</td>';
                            echo '<td>' .$row["CatName"]. '</td>';
                            echo '<td>' .$row["Username"]. '</td>';
                            echo '<td>' .$row["AddDate"]. '</td>';
                            echo '<td>
                                <a href="items.php?do=edit&itemid='.$row["ItemID"].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                <a href="items.php?do=delete&itemid='.$row["ItemID"].'" class="btn btn-danger confirm"><i class="fas fa-times"></i> Delete</a>';
                                if($row['Approve'] == 0)
                                    echo '<a href="items.php?do=approve&itemid='.$row["ItemID"].'" class="btn btn-primary activate-btn"><i class="fas fa-check"></i>Approve</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
            <a href="?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> New Items</a>
        </div>
    <?php 
    }
    elseif ($do == 'add')
    {?>
        <h1 class="text-center">Add New Items</h1>
        <div class="container">
            <form action="?do=insert" method="POST">
                <!--  Start Field Name  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="name" placeholder="Name Of The Item" required = "required">
                    </div>
                </div> 
                <!--  End Field Name  -->

                <!--  Start Field Description  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="description" placeholder="Description Of The Item" required = "required">
                    </div>
                </div> 
                <!--  End Field Description  -->

                <!--  Start Field Price  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="price" placeholder="Price Of The Item" required = "required">
                    </div>
                </div> 
                <!--  End Field Price  -->

                <!--  Start Field CountryMade  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="country" placeholder="Country of Made">
                    </div>
                </div> 
                <!--  End Field CountryMade  -->

                <!--  Start Field Status  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Old</option>
                        </select>
                    </div>
                </div> 
                <!--  End Field status  -->

                <!--  Start Field Members  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="member">
                            <option value="0">...</option>
                            <?php
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $row = $stmt->fetchAll();
                                foreach($row as $r)
                                {
                                    echo  '<option value="'.$r['UserID'].'">'.$r['Username'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div> 
                <!--  End Field Members  -->

                <!--  Start Field Categories  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="category">
                            <option value="0">...</option>
                            <?php
                                $stmt = $con->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $row = $stmt->fetchAll();
                                foreach($row as $r)
                                {
                                    echo  '<option value="'.$r['ID'].'">'.$r['Name'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div> 
                <!--  End Field Members  -->

                <!--  Start Field Submit  -->
                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <input type="submit" class="btn btn-success" value="Add Item">
                    </div>
                </div> 
                <!--  End Field Submit  -->   
            </form>
        </div>
    <?php
    }
    elseif ($do == 'insert')
    {
        echo"<h1 class='text-center'>Insert Item</h1>";
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $category = $_POST['category'];

            $formErrors = array();
            if(empty($name))
                $formErrors[] = "<div class='alert alert-danger'> Name can't be <strong>Empty</strong></div>";
            if(empty($description))
                $formErrors[] = "<div class='alert alert-danger'> Description can't be <strong>Empty</strong></div>";
            if(empty($price))
                $formErrors[] = "<div class='alert alert-danger'> Price can't be <strong>Empty</strong></div>";
            if($status == '0')
                $formErrors[] = "<div class='alert alert-danger'>You Must Choose The <strong>Status</strong></div>";
            if($member == '0')
                $formErrors[] = "<div class='alert alert-danger'>You Must Choose a <strong>member</strong></div>";
            if($category == '0')
                $formErrors[] = "<div class='alert alert-danger'>You Must Choose The <strong>Category</strong></div>";

            //Update The DataBase With This infos

            if (empty($formErrors))
            {
                //Insert ItemInfos In DataBase
                $stmt = $con->prepare("INSERT INTO items(Name, Description, Price, CountryMade, Status, UserID, CatID, AddDate) 
                                    VALUES(:xname, :xdescription, :xprice, :xcountry, :xstatus,:xuserid, :xcatid, now())");
                $stmt->execute(array(
                    'xname'         => $name,
                    'xdescription'  => $description,
                    'xprice'        => $price,
                    'xcountry'      => $country,
                    'xstatus'       => $status,
                    'xuserid'       => $member,
                    'xcatid'        => $category                       
                ));
                redirectionHome("<div class='alert alert-success'>".$stmt->rowCount()." Item added </div>", "items.php");
            }
            else
                redirectionHome(implode('',$formErrors)); //Concatenate the array of Errors
        }
        else
            redirectionHome("<div class='alert alert-danger'>Sorry you can't browse this page directly</div>");
        echo '</div>';
    }
    elseif ($do == 'edit')
    {?>
        <h1 class="text-center">Edit Item</h1>
        <div class="container">
        <?php
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;
            $stmt = $con->prepare("SELECT * FROM items WHERE itemID=$itemid");
            $stmt->execute();
            $row = $stmt->fetch();
            if($stmt->rowCount() > 0)
            {?>
                <form action="?do=update" method="POST">
                    <input type="hidden" name="itemid" value="<?php echo $itemid;?>">
                    <!--  Start Field Name  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="name" placeholder="Name Of The Item" value="<?php echo $row['Name']?>" required = "required">
                        </div>
                    </div> 
                    <!--  End Field Name  -->

                    <!--  Start Field Description  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="description" placeholder="Description Of The Item" value="<?php echo $row['Description']?>" required = "required">
                        </div>
                    </div> 
                    <!--  End Field Description  -->

                    <!--  Start Field Price  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="price" placeholder="Price Of The Item" value="<?php echo $row['Price']?>" required = "required">
                        </div>
                    </div> 
                    <!--  End Field Price  -->

                    <!--  Start Field CountryMade  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="country" value="<?php echo $row['CountryMade']?>" placeholder="Country of Made">
                        </div>
                    </div> 
                    <!--  End Field CountryMade  -->

                    <!--  Start Field Status  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="status" value="1">
                                <option value="1" <?php if($row['Status'] == 1) echo "selected";?>>New</option>
                                <option value="2" <?php if($row['Status'] == 2) echo "selected";?>>Like New</option>
                                <option value="3" <?php if($row['Status'] == 3) echo "selected";?>>Used</option>
                                <option value="4" <?php if($row['Status'] == 4) echo "selected";?>>Old</option>
                            </select>
                        </div>
                    </div> 
                    <!--  End Field status  -->

                    <!--  Start Field Members  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="member">
                                <?php
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach($users as $r)
                                    {
                                        echo  '<option value="'.$r['UserID'].'"';
                                        if($row['UserID'] == $r['UserID'])
                                            echo "selected";
                                        echo '>'.$r['Username'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div> 
                    <!--  End Field Members  -->

                    <!--  Start Field Categories  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="category">
                                <?php
                                    $stmt = $con->prepare("SELECT * FROM categories");
                                    $stmt->execute();
                                    $cat = $stmt->fetchAll();
                                    foreach($cat as $r)
                                    {
                                        echo  '<option value="'.$r['ID'].'"';
                                        if($row['CatID'] == $r['ID'])
                                            echo "selected";
                                        echo '>'.$r['Name'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div> 
                    <!--  End Field Members  -->

                    <!--  Start Field Submit  -->
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <input type="submit" class="btn btn-success" value="Save Item">
                        </div>
                    </div> 
                    <!--  End Field Submit  -->  
                </form>
                <?php
                $stmt = $con->prepare("SELECT comments.*, users.Username
                                        FROM comments
                                        INNER JOIN users ON comments.UserID = users.UserID
                                        WHERE ItemID=? ");
                $stmt->execute(array($itemid));
                $rows = $stmt->fetchAll();
                if($stmt->rowCount() == 0)
                    echo '<h1 class="text-center">There Is No Comments</h1>';
                else{
                ?>
                <h1 class="text-center">Manage <?php echo $row['Name']?> Comments</h1>
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>Comment</td>
                        <td>Username</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                        foreach($rows as $row)
                        {
                            echo"<tr>";
                            echo '<td>' .$row["Comment"]. '</td>';
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
            <?php }
            }else
                redirectionHome("<div class='alert alert-danger'>There is no such Id</div>");
                ?>
            </div>
    <?php
    } elseif($do == "update"){
        echo"<h1 class='text-center'>Update Item</h1>";
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $itemid = $_POST['itemid'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $category = $_POST['category'];

            $formErrors = array();
            if(empty($name))
                $formErrors[] = "<div class='alert alert-danger'> Name can't be <strong>Empty</strong></div>";
            if(empty($description))
                $formErrors[] = "<div class='alert alert-danger'> Description can't be <strong>Empty</strong></div>";
            if(empty($price))
                $formErrors[] = "<div class='alert alert-danger'> Price can't be <strong>Empty</strong></div>";
            if($status == '0')
                $formErrors[] = "<div class='alert alert-danger'>You Must Choose The <strong>Status</strong></div>";
            if($member == '0')
                $formErrors[] = "<div class='alert alert-danger'>You Must Choose a <strong>member</strong></div>";
            if($category == '0')
                $formErrors[] = "<div class='alert alert-danger'>You Must Choose The <strong>Category</strong></div>";

            //Update The DataBase With This infos

            if (empty($formErrors))
            {
                //Insert ItemInfos In DataBase
                $stmt = $con->prepare("UPDATE items SET Name = ?, Description = ?, Price = ?, CountryMade = ?, Status = ?, UserID = ?, CatID = ?WHERE ItemID=?");
                $stmt->execute(array($name, $description, $price, $country, $status, $member, $category, $itemid));
                redirectionHome("<div class='alert alert-success'>".$stmt->rowCount()." Item Updated </div>", "items.php");
            }
            else
                redirectionHome(implode('',$formErrors)); //Concatenate the array of Errors
        }
        else
            redirectionHome("<div class='alert alert-danger'>Sorry you can't browse this page directly</div>");
        echo '</div>';
    }
    elseif($do == 'delete')
    {
        echo"<h1 class='text-center'>Delete Item</h1>";
        echo '<div class="container">';
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;
        $stmt = $con->prepare("DELETE FROM items WHERE ItemID=?");
        $stmt->execute(array($itemid));
        if ($stmt->rowCount() > 0)
            redirectionHome("<div class='alert alert-success'>".$stmt->rowCount()." Item Deleted </div>");
        else
            redirectionHome("<div class='alert alert-danger'>There Is No Item With This ID</div>");
        echo "</div>";
    }
    elseif($do == 'approve')
    {
        echo"<h1 class='text-center'>Approve Item</h1>";
        echo '<div class="container">';
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;
        $stmt = $con->prepare("UPDATE items SET Approve=1 WHERE ItemID=?");
        $stmt->execute(array($itemid));
        if($stmt->rowCount() > 0)
            redirectionHome("<div class='alert alert-success'>".$stmt->rowCount()." Item Approved </div>");
        else
            redirectionHome("<div class='alert alert-danger'>There Is No Item With This ID</div>");

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