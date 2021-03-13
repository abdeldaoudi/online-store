<?php
    session_start();
    $pageTitle = "Show Item";
    include "init.php";
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;
    $stmt = $con->prepare("SELECT items.* , categories.Name AS CatName , users.Username
                            FROM items 
                            INNER JOIN categories
                            ON items.CatID=categories.ID
                            INNER JOIN users
                            ON users.UserID=items.UserID
                            WHERE itemID=$itemid");
    $stmt->execute();
    $row = $stmt->fetch();
    if($stmt->rowCount() > 0)
    {
    
?>

<h1 class="text-center"><?php echo $row['Name']; ?> </h1>
<div class="container" style="margin-bottom:100px;">
        <div class="row">
            <div class="col-md-3">
                <img src="admin/uploads/items/<?php echo $row['Image']; ?> " class="card-img-top img-thumbnail" alt="...">
            </div>
            <div class="item-infos col-md-9">
                <h2><?php echo $row['Name']?></h2>
                <p><?php echo $row['Description']?></p>
                <ul class="list-unstyled">
                    <li><i class="far fa-calendar-alt"></i><span>&nbsp;Added Date: </span><?php echo $row['AddDate']?></li>
                    <li><i class="fas fa-money-bill-wave"></i><span>&nbsp;Price: </span><?php echo $row['Price']?></li>
                    <li><i class="far fa-building"></i><span>&nbsp;Made in: </span><?php echo $row['CountryMade']?></li>
                    <li><i class="fas fa-tags"></i><span>&nbsp;Category: </span><a href="categories.php?catid=<?php echo $row['CatID']; ?>"><?php echo $row['CatName']?></a></li>
                    <li><i class="fas fa-user"></i><span>&nbsp;Added By: </span><a href="#"><?php echo $row['Username']?></a></li>
                </ul>
            </div>
        </div>
        <hr>
        <?php
            if(isset($_SESSION['user']))
            { 
        ?>
        <div class="row">
            <div class="col-md-9 offset-md-3">
                <div class="add-comment">
                    <h3>Add Your Comment</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $itemid; ?>" method="POST">
                        <textarea class="form-control" rows="4" name="comment" id="" required></textarea>
                        <input class="btn btn-primary" type="submit" value="Add Comment">
                    </form>
                    <?php
                        if($_SERVER['REQUEST_METHOD'] == "POST")
                        {
                            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                            
                            if (!empty($comment))
                            {
                                $stmt = $con->prepare("INSERT INTO comments(Comment, ItemID, UserID, ComDate)
                                                        VALUES(:xcomment, :xitemid, :xuserid, now())");
                                $stmt->execute(array(
                                    'xcomment'  => $comment,
                                    'xitemid'   => $itemid,
                                    'xuserid'   => $_SESSION['uid']
                                ));
                                echo '<div class="alert alert-success">Comment added</div>';
                            }
                        } 
                    ?>
                </div>
            </div>
        </div>
        <?php
            }
            else
                echo '<div class="alert alert-warning"><a href="login.php">Login</a> Or <a href="login.php">Registred</a> To Add Comment</div>';
        ?>
        <hr>
        <?php
            $stmt = $con->prepare("SELECT comments.*, users.Username, users.Avatar
            FROM comments
            INNER JOIN users ON comments.UserID = users.UserID
            WHERE ItemID=? AND Status=1
            ORDER BY ComID DESC");
            $stmt->execute(array($itemid));
            $rows = $stmt->fetchAll();
        
        foreach($rows as $r)
        { ?>
            <div class="comment-box">
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <img class="img-responsive img-thumbnail rounded-circle" src="admin/uploads/avatars/<?php echo $r['Avatar'] ?>" alt=""><br>
                        <?php echo $r['Username'] ?>
                    </div>
                    <div class="col-sm-10">
                        <p class="lead"> <?php echo $r['Comment'] ?> </p>
                    </div>
                </div>
            </div>
            <hr>
       <?php
        }
            ?>
</div>

<?php
    }
    else
        echo "<div class='alert alert-danger'>There is No Item With This ID</div>";
    include $tpl."footer.php";
?>