<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $css;?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $css;?>all.min.css">
    <link rel="stylesheet" href="<?php echo $css;?>styles.css">
    <title><?php getTitle();?></title>
</head>
<body>
    <div class="upper-bar">
        <div class="container">
            <?php
                if (!isset($_SESSION['user']))
                { 
                    echo '<a href="login.php">';
                        echo '<span class="float-right"> Login/Signup </span>';
                    echo '</a>';
                } 
                ?>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">HomePage</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="navbar-nav ml-auto">   <!--  ml-auto to push items to the right (l:left, There is also mr-auto r:right) -->
                <?php 
                $catgs = getCat("Parent=0");
                    foreach($catgs as $cat)
                    {
                        echo'<li class="nav-item"><a class="nav-link" href="categories.php?catid='.$cat['ID'].'">';
                        echo $cat['Name'] . '</a></li>';
                    }
                    echo '<div style="margin-right:30px;"></div>'
                ?>   
            </ul>
            <?php
                if (isset($_SESSION['user']))
                {
                    $s = $con->prepare("SELECT * FROM users WHERE Username=?");
                    $s->execute(array($_SESSION['user']));
                    $u = $s->fetch();
                    ?>
                    <ul class="navbar-nav navbar-right">
                        <li class="nav-item dropdown">
                            <a class=" nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
                                <img class="rounded-circle" style="width:30px;" src="admin/uploads/avatars/<?php echo $u['Avatar']; ?>" alt="">
                                <?php echo $_SESSION['user']; ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="profile.php">My Profile</a>
                            <a class="dropdown-item" href="profile.php#my-items">My Items</a>
                            <a class="dropdown-item" href="newad.php">New Item</a>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                <?php 
                }
                ?>
        </div>  
        </div>
    </nav>
 
