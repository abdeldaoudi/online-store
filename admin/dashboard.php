<?php
session_start();
$pageTitle = "dashboard";
if(isset($_SESSION['username']))
{
    include "init.php";
    
    // Start Dashboard Page
    ?>

<div class="container home-stats text-center">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <a href="members.php">
                    <div class="stat st-members">
                        <i class="fas fa-users"></i>
                        <div class="info">
                                Total Members
                                <span><?php echo countItems('UserID', 'users'); ?></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="members.php?page=pending">
                    <div class="stat st-pending">
                        <i class="fas fa-user-plus"></i>
                        <div class="info">    
                            Pending Members
                            <span><?php echo checkItem('RegStatus', 'users', 0)->rowCount(); ?></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="items.php?page=pending">
                    <div class="stat st-items">
                        <i class="fas fa-tag"></i>
                        <div class="info">    
                            Total Items
                            <span><?php echo countItems("ItemID", "items") ?></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="comments.php">
                    <div class="stat st-comments">
                        <i class="far fa-comment"></i>
                        <div class="info">
                            Total Comments
                            <span><?php echo countItems("ComID", "comments") ?></span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="container latest">
        <div class="row">
            <?php $latest = 6; ?>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-users"></i> Latest <?php echo $latest; ?> Registred Users
                        <span class="toggle-info float-right">
                            <i class="fas fa-minus"></i>
                        </span>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled latest-users">
                            <?php
                                $theLatest = getLatest("*", 'users', 'UserID', $latest);
                                foreach($theLatest as $us)
                                {
                                    echo '<li>'. $us['Username']; 
                                            echo '<a href="members.php?do=edit&userid='.$us['UserID'].'">';
                                                echo '<span class="btn btn-outline-primary float-right">';
                                                    echo '<i class="fa fa-edit"></i> Edit';
                                                echo '</span>';
                                            echo'</a>';
                                            if ($us['RegStatus'] == 0)
                                                echo '<a href="members.php?do=activate&userid='.$us["UserID"].'" class="btn btn-outline-success float-right"><i class="fas fa-check"></i> Activate</a>';
                                    echo'</li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                <?php $latestItems = 4;?>
                    <div class="card-header">
                        <i class="fas fa-tag"></i> Latest <?php echo $latestItems;?> Registred Items
                        <span class="toggle-info float-right">
                            <i class="fas fa-minus"></i>
                        </span>
                    </div>
                    <div class="card-body">
                    <ul class="list-unstyled latest-users">
                            <?php
                                $theLatest = getLatest("*", 'items', 'ItemID', $latestItems);
                                if (!empty($theLatest))
                                {
                                    foreach($theLatest as $it)
                                    {
                                        echo '<li>'. $it['Name']; 
                                                echo '<a href="items.php?do=edit&itemid='.$it['ItemID'].'">';
                                                    echo '<span class="btn btn-outline-primary float-right">';
                                                        echo '<i class="fa fa-edit"></i> Edit';
                                                    echo '</span>';
                                                echo'</a>';
                                                if ($it['Approve'] == 0)
                                                {
                                                    echo '<a href="items.php?do=approve&itemid='.$it["ItemID"].'" class="btn btn-outline-success float-right"><i class="fas fa-check"></i> Approve</a>';
                                                }
                                        echo'</li>';
                                    }
                                }
                                else
                                    echo "There is No Items";
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Latest Comments --> 
        <div class="row">
            <?php $latest = 6; ?>
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <i class="far fa-comment"></i> Latest <?php echo $latest; ?> Comments
                        <span class="toggle-info float-right">
                            <i class="fas fa-minus"></i>
                        </span>
                    </div>
                    <div class="card-body">
                        <?php
                            $stmt = $con->prepare("SELECT comments.*, users.Username
                                                    FROM comments
                                                    INNER JOIN users ON comments.UserID = users.UserID
                                                    ORDER BY ComID DESC
                                                    LIMIT $latest");
                            $stmt->execute();
                            $rows = $stmt->fetchAll(); 
                            foreach($rows as $r)
                            {
                                echo '<div class="comment-box">';
                                    echo '<span class="member-name">';
                                        echo'<a href="members.php?do=edit&userid='. $r['UserID'].'">'.$r['Username'].'</a>';
                                    echo '</span>';
                                    echo '<a href="comments.php?do=edit&comid='.$r['ComID'].'" class="edit-comment"><p class="member-comment">'.$r['Comment'].'</p></a>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Latest Comments -->
    </div>


    <?php
    // End Dashboard Page

    include $tpl."footer.php";
}
else
{
    header('Localhost: index.php');
    exit();
} 
?>
