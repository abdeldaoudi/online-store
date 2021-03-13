<?php
    session_start();
    $pageTitle = "Create New Item";
    include "init.php";

    if(isset($_SESSION['user']))
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $itemImg = $_FILES['itemimg'];
            $itemName = $itemImg['name'];
            $itemSize = $itemImg['size'];
            $itemTmp = $itemImg['tmp_name'];
            $itemType = $itemImg['type'];

            // List Of Allowed File Types
            $itemAllowedExtension = array('jpeg', 'jpg', 'png', 'gif');
            $itemExtension = explode('.', $itemName);
            $ext = strtolower(end($itemExtension));

            $formErrors = array();
            $title = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
            $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($title))
                $formErrors[] = "<div class='alert alert-danger'> Title can't be <strong>Empty</strong></div>";
            if (empty($desc))
                $formErrors[] = "<div class='alert alert-danger'> Description can't be <strong>Empty</strong></div>";
            if (empty($price))
                $formErrors[] = "<div class='alert alert-danger'> Price can't be <strong>Empty</strong></div>";
            if ($status == "0")
                $formErrors[] = "<div class='alert alert-danger'> Status can't be <strong>Empty</strong></div>";
            if ($category == "0")
                $formErrors[] = "<div class='alert alert-danger'> Category can't be <strong>Empty</strong></div>";
            if (!empty($itemName) &&!in_array($ext, $itemAllowedExtension))
                $formErrors[] = "<div class='alert alert-danger'> This Extension is Not <strong>Allowed</strong></div>";
            if (empty($itemName))
                $formErrors[] = "<div class='alert alert-danger'> Item Image is <strong>Required</strong></div>";
            
            if (empty($formErrors))
            {
                $itemIm = rand(0, 100000) . '_' . $itemName;
                move_uploaded_file($itemTmp, "admin\uploads\items\\" . $itemIm);
                //Insert ItemInfos In DataBase
                $stmt = $con->prepare("INSERT INTO items(Name, Description, Price, CountryMade, Status, UserID, CatID, Image, AddDate) 
                                    VALUES(:xname, :xdescription, :xprice, :xcountry, :xstatus,:xuserid, :xcatid, :ximage, now())");
                $stmt->execute(array(
                    'xname'         => $title,
                    'xdescription'  => $desc,
                    'xprice'        => $price,
                    'xcountry'      => $country,
                    'xstatus'       => $status,
                    'xuserid'       => $_SESSION['uid'],
                    'xcatid'        => $category,
                    'ximage'        => $itemIm                   
                ));
                echo "<div class='alert alert-success'>".$stmt->rowCount()." Item added </div>";
            }
            else
                echo (implode('', $formErrors));
        }
?>

<h1 class="text-center">Create New Item </h1>
<div class="create-ad block">
    <div class="container">
        <div class="card border-secondary">
            <div class="card-header bg-primary text-white">
                Create New Item
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                            <!--  Start Field Name  -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10 col-md-9">
                                    <input
                                        type="text"
                                        class="form-control live-name"
                                        name="name"
                                        placeholder="Name Of The Item"
                                        required = "required">
                                </div>
                            </div> 
                            <!--  End Field Name  -->

                            <!--  Start Field Description  -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10 col-md-9">
                                    <input
                                        type="text"
                                        class="form-control live-desc"
                                        name="description"
                                        placeholder="Description Of The Item"
                                        required = "required">
                                </div>
                            </div> 
                            <!--  End Field Description  -->

                            <!--  Start Field Price  -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-10 col-md-9">
                                    <input
                                        type="text"
                                        class="form-control live-price"
                                        name="price"
                                        placeholder="Price Of The Item"
                                        required = "required">
                                </div>
                            </div> 
                            <!--  End Field Price  -->

                            <!--  Start Field CountryMade  -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Country</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" class="form-control" name="country" placeholder="Country of Made">
                                </div>
                            </div> 
                            <!--  End Field CountryMade  -->

                            <!--  Start Field Status  -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-10 col-md-9">
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
                            <!--  Start Field Categories  -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Category</label>
                                <div class="col-sm-10 col-md-9">
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
                            <!--  End Field Categories  -->

                            <!--  Start Field Avatar  -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Item Image</label>
                                <div class="col-sm-10 col-md-9">
                                    <div class="custom-file">
                                        <input type="file" name="itemimg" class="custom-file-input live-img" id="inputGroupFile02" >
                                        <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <!--  End Field Avatar  -->

                            <!--  Start Field Submit  -->
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <input type="submit" class="btn btn-success" value="Add Item">
                                </div>
                            </div> 
                            <!--  End Field Submit  -->   
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="card item-box live-preview">
                            <span class='price'>0</span>
                            <img src="avatar.png" class="card-img-top" alt="..." >
                            <div class="card-body">
                                <h5 class="card-title">Title</h5>
                                <p class="card-text">Description</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    }
    else
    {
        header("Location: login.php");
        exit();
    }
    include $tpl."footer.php";
?>