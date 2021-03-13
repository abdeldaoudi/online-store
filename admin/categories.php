<?php
$pageTitle = "Categories";
session_start();
if (isset($_SESSION['username']))
{
    include "init.php";
    $do = isset($_GET['do']) ? $_GET['do'] : "manage";
    if ($do == 'manage')
    {
        $sort = "ASC";
        $sort_array = array('ASC', 'DESC');
        if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array))
            $sort = $_GET['sort'];
        $stmt = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
        $stmt->execute();
        $catgs = $stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage Categories</h1>
        <div class="container category-container">
            <div class="card category">
                <div class="card-header">
                    <i class="fa fa-edit"></i> Manage Categories
                    <div class="ordering float-right">
                        <i class="fa fa-sort"></i> Ordering:
                        [<a class="<?php if ($sort == 'DESC') echo "active";?>" href="?sort=DESC"> Desc</a> |
                        <a class="<?php if ($sort == 'ASC') echo "active";?>" href="?sort=ASC">Asc </a>]
                    </div>
                </div>
                <div class="card-body">
                    <?php
                        foreach($catgs as $cat)
                        {
                            echo '<div class="cat">';
                                echo '<div class="buttons-hidden">';
                                    echo '<a href="?do=edit&catid='.$cat['ID'].'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                                    echo '<a href="?do=delete&catid='.$cat['ID'].'" class="btn btn-sm btn-danger confirm"><i class="fas fa-times"></i> Delete</a>';
                                echo '</div>';
                                echo '<h3>' . $cat['Name'] . "</h3>";
                                echo "<div class='full-view' style='display:none'>";
                                    echo "<p>"; if ($cat['Description'] == '') echo 'There is no Description'; else echo $cat['Description'];echo "</p>";
                                    if ($cat['Visibility'] == 1) echo'<span class="visibility"><i class="fas fa-eye"></i> Hidden</span>';
                                    if ($cat['Allow_Comment'] == 1) echo'<span class="comment"><i class="far fa-comment"></i> Comment Disabled</span>';
                                    if ($cat['Allow_Ads'] == 1) echo'<span class="ads"><i class="fas fa-times"></i> Ads Disabled</span>';
                                echo '</div>';
                            echo '</div>';
                            echo '<hr>';
                        }
                    ?>
                </div>
            </div>
            <a class="btn btn-info" href="?do=add"><i class="fa fa-plus"></i> New Category</a>
        </div>
        <?php
    }
    elseif ($do == 'add')
    {?>
        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <form action="?do=insert" method="POST">
                <!--  Start Field Name  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="name" placeholder="Name Of The Category" required = "required">
                    </div>
                </div> 
                <!--  End Field Name  -->

                <!--  Start Field Description  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="description" placeholder="Describe The Category">
                    </div>
                </div> 
                <!--  End Field Description  --> 

                <!--  Start Field ordering  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="ordering" placeholder="Number To Arrange Category">
                    </div>
                </div> 
                <!--  End Field ordering  -->

                <!-- Start Category Type -->

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Category Type</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="parent">
                            <option value="0">None</option>
                            <?php 
                                $stmt = $con->prepare("SELECT * FROM categories WHERE Parent=0");
                                $stmt->execute();
                                $rows = $stmt->fetchAll();
                                foreach($rows as $r)
                                {
                                    echo '<option value="'.$r['ID'].'">'.$r['Name'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div> 

                <!-- End Category Type -->

                <!--  Start Field Visibility  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                            <label for="vis-yes">Yes</label>
                            <input class="radio-space" id="vis-no" type="radio" name="visibility" value="1">
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div> 
                <!--  End Field Visibility  --> 

                <!--  Start Field Commenting  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="com-yes" type="radio" name="comment" value="0" checked>
                            <label for="com-yes">Yes</label>
                            <input class="radio-space" id="com-no" type="radio" name="comment" value="1">
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div> 
                <!--  End Field Commenting  --> 

                <!--  Start Field Ads  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" checked>
                            <label for="ads-yes">Yes</label>
                            <input class="radio-space" id="ads-no" type="radio" name="ads" value="1">
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div> 
                <!--  End Field Ads  --> 
                <!--  Start Field Submit  -->
                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <input type="submit" class="btn btn-success" value="Add Category">
                    </div>
                </div> 
                <!--  End Field Submit  -->   
            </form>
        </div>
    <?php
    }
    elseif ($do == 'insert')
    {
        echo"<h1 class='text-center'>Insert Category</h1>";
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $ordering = $_POST['ordering'];
            $visible = $_POST['visibility'];
            $comment = $_POST['comment'];
            $ads = $_POST['ads'];
            $parent = $_POST['parent'];

            if(empty($ordering))
                $ordering = 1;
            if(empty($name))
                redirectionHome("<div class='alert alert-danger'> Username can't be <strong>Empty</strong></div>");
            else
            {
                //Update The DataBase With This infos
                $stmt = checkItem("Name", "categories", $name);
                if ($stmt->rowCount() == 0)
                {
                    //Insert UserInfos In DataBase
                    $stmt = $con->prepare("INSERT INTO categories(Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads, Parent) 
                                        VALUES(:xname, :xdesc, :xorder, :xvisible, :xcom, :xads, :xparent)");
                    $stmt->execute(array(
                        'xname'     => $name,
                        'xdesc'     => $desc,
                        'xorder'    => $ordering,
                        'xvisible'  => $visible,
                        'xcom'      => $comment,
                        'xads'      => $ads,
                        'xparent'   => $parent
                    ));
                    redirectionHome("<div class='alert alert-success'>".$stmt->rowCount()." category added </div>", "categories.php");
                }
                else
                    redirectionHome("<div class='alert alert-warning'> Sorry This Category Is Already Exist</div>");
            }
        }
        else
            redirectionHome("<div class='alert alert-danger'>Sorry you can't browse this page directly</div>");
        echo '</div>';
    }
    elseif ($do == 'edit')
    {
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? $_GET['catid'] : 0;
        
        //Select All data Depend Id
        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
        $stmt->execute(array($catid));
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        if($count > 0)
        { 
            ?>
                <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form action="?do=update" method="POST">
                        <input type="hidden" name="catid" value="<?php echo $catid;?>">
                        <!--  Start Field Name  -->
                        <div class="form-group row">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" class="form-control" name="name" value="<?php echo $row['Name']; ?>" placeholder="Name Of The Category" required = "required">
                            </div>
                        </div> 
                        <!--  End Field Name  -->

                        <!--  Start Field Description  -->
                        <div class="form-group row">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" class="form-control" name="description" value="<?php echo $row['Description']; ?>" placeholder="Describe The Category">
                            </div>
                        </div> 
                        <!--  End Field Description  --> 

                        <!--  Start Field ordering  -->
                        <div class="form-group row">
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" class="form-control" name="ordering" value="<?php echo $row['Ordering']; ?>" placeholder="Number To Arrange Category">
                            </div>
                        </div> 
                        <!--  End Field ordering  --> 

                        <!--  Start Field Visibility  -->
                        <div class="form-group row">
                            <label class="col-sm-2 control-label">Visible</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($row['Visibility'] == "0") echo 'checked';?> >
                                    <label for="vis-yes">Yes</label>
                                    <input class="radio-space" id="vis-no" type="radio" name="visibility" value="1" <?php if($row['Visibility'] == "1") echo 'checked';?> >
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div> 
                        <!--  End Field Visibility  --> 

                        <!--  Start Field Commenting  -->
                        <div class="form-group row">
                            <label class="col-sm-2 control-label">Allow Commenting</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="com-yes" type="radio" name="comment" value="0" <?php if($row['Allow_Comment'] == "0") echo 'checked';?> >
                                    <label for="com-yes">Yes</label>
                                    <input class="radio-space" id="com-no" type="radio" name="comment" value="1" <?php if($row['Allow_Comment'] == "1") echo 'checked';?> >
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div> 
                        <!--  End Field Commenting  --> 

                        <!--  Start Field Ads  -->
                        <div class="form-group row">
                            <label class="col-sm-2 control-label">Allow Ads</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" <?php if($row['Allow_Ads'] == "0") echo 'checked';?> >
                                    <label for="ads-yes">Yes</label>
                                    <input class="radio-space" id="ads-no" type="radio" name="ads" value="1" <?php if($row['Allow_Ads'] == "1") echo 'checked';?> >
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div> 
                        <!--  End Field Ads  --> 
                        <!--  Start Field Submit  -->
                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <input type="submit" class="btn btn-success" value="save Category">
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
        echo "<div class='container'>";
        if($_SERVER['REQUEST_METHOD'] =='POST')
        {
            echo "<h1 class='text-center'> Update Category </h1>";
            $name = $_POST['name'];
            $description = $_POST['description'];
            $ordering = $_POST['ordering'];
            $visibility = $_POST['visibility'];
            $comment = $_POST['comment'];
            $ads = $_POST['ads'];
            $catid = $_POST['catid'];

            $stmt = $con->prepare("SELECT Name FROM categories WHERE Name=? AND ID!=?");
            $stmt->execute(array($name, $catid));
            if($stmt->rowCount() == 0)
            {
                $stmt = $con->prepare("UPDATE categories SET Name=?, Description=?, Ordering=?, Visibility=?, Allow_Comment=?, Allow_Ads=? WHERE ID=?");
                $stmt->execute(array($name, $description, $ordering, $visibility, $comment, $ads, $catid));
                redirectionHome("<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated </div>","categories.php");
            }
            else
                redirectionHome("<div class='alert alert-warning'>Sorry This Name is Already Exist</div>");
        }
        else
            redirectionHome("<div class='alert alert-danger'>Sorry you can't browse this page directly</div>");
        echo "</div>";
    }
    elseif ($do == 'delete')
    {
        echo '<h1 class="text-center">Delete Category</h1>';
        echo "<div class='container'>";
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? $_GET['catid'] : 0;
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID=?");
            $stmt->execute(array($catid));
            if ($stmt->rowCount() > 0)
            {
                $stmt = $con->prepare("DELETE FROM categories WHERE ID=?");
                $stmt->execute(array($catid));
                redirectionHome("<div class='alert alert-success'> Category Deleted </div>");
            }
            else
                redirectionHome("<div class='alert alert-danger'> There is No Categoriy With This ID </div>"); 
        echo "</div>";
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