<?php
    session_start(); 
    include "init.php";
?>

<div class="container">
    <?php if(isset($_GET['catid'])){ ?>
        <h1 class="text-center">
            show category
        </h1>
        <div class="row row-cols-1 row-cols-lg-4 row-cols-md-3">
            <?php
                foreach(getItems('CatID', $_GET['catid']) as $it)
                {
                    //echo "<div class='col-sm-6 col-md-4 col-lg-3'>";
                    echo '<div class="col mb-3">';
                        echo '<div class="card item-box">';
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
            ?>
        </div>
    <?php
        } else
            redirectionHome("<div class='alert alert-danger'>There is No id</div>"); 
    ?>
</div>
    
<?php include $tpl."footer.php"; ?>