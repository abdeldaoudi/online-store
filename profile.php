<?php
    session_start();
    $pageTitle = "Profile";
    include "init.php";

    
    if(isset($_SESSION['user']))
    {
        $stmt = $con->prepare("SELECT * FROM users WHERE Username=?");
        $stmt->execute(array($_SESSION['user']));
        $userInfo = $stmt->fetch();
?>

<h1 class="text-center">My Profile </h1>
<div class="information block">
    <div class="container">
        <div class="card border-secondary">
            <div class="card-header bg-primary text-white">
                My Infos
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item"> <i class="fas fa-user-lock"></i> Username : <?php echo $userInfo['Username'];?> </li>
                    <li class="list-group-item"> <i class="far fa-envelope"></i> Email : <?php echo $userInfo['Email'];?> </li>
                    <li class="list-group-item"> <i class="fas fa-user"></i> Full Name : <?php echo $userInfo['FullName'];?></li>
                    <li class="list-group-item"> <i class="fas fa-calendar-alt"></i> Registred Date : <?php echo $userInfo['Date'];?> </li>
                </ul>
                <a href="" class="btn btn-outline-info" style="margin-top:10px; font-weight:bold;">
                    <i class="fa fa-edit"></i> Edit Informations
                </a>
            </div>
        </div>
    </div>
</div>

<div class="myads block" id="my-items">
    <div class="container">
        <div class="card border-secondary">
            <div class="card-header bg-primary text-white">
                My Items
            </div>
            <div class="card-body">
                <?php
                    echo '<div class="row">';
                        if (empty(getItems('UserID', $userInfo['UserID'], "approve")))
                        {
                            echo "There is no ads to show. ";
                            echo "Create <a href='newad.php'> New Ad</a>";
                        }
                        else
                        {
                            //echo '<div class="row row-cols-1 row-cols-lg-4 row-cols-md-3">';
                            foreach(getItems('UserID', $userInfo['UserID'], "approve") as $it)
                            {
                                echo '<div class="col-sm-6 col-md-4 col-lg-3">';
                                    echo '<div class="card w-75 item-box">';
                                        if($it['Approve'] == 0)
                                            echo '<span class="approve-msg"> Waiting Approval</span>';
                                        echo "<span class='price'>".$it['Price']."</span>";
                                        echo '<img src="admin/uploads/items/'.$it['Image'].'" class="card-img-top" alt="...">';
                                        echo '<div class="card-body">';
                                            echo '<h5 class="card-title"><a href="items.php?itemid='.$it['ItemID'].'">'.$it['Name'].'</a></h5>';
                                            echo '<p class="card-text">'.$it['Description'].'</p>';
                                            echo '<div class="date">'.$it['AddDate'].'</div>';
                                        echo '</div>';
                                    echo '</div>';
                                echo "</div>";
                            }  
                        }
                    echo "</div>";  
                ?>
            </div>
        </div>
    </div>
</div>

<div class="comment block">
    <div class="container">
        <div class="card border-secondary">
            <div class="card-header bg-primary text-white">
                Latest Comments
            </div>
            <div class="card-body">
                test Comments
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