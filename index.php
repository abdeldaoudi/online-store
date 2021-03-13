<?php
    session_start();
    $pageTitle = "HomePage";
    include "init.php";
?>
<div class="container">
    <div class="row row-cols-1 row-cols-lg-4 row-cols-md-3" style="margin-top: 50px;">
        <?php
            $stmt = $con->prepare("SELECT * FROM items WHERE Approve=1 ORDER BY ItemID DESC");
            $stmt->execute();
            $row = $stmt->fetchAll();
            foreach($row as $it)
            {
                //echo "<div class='col-sm-6 col-md-4 col-lg-3'>";
                echo '<div class="col mb-3">';
                    echo '<div class="card item-box">';
                        echo "<span class='price'>".$it['Price']."</span>";
                        echo '<img src="admin/uploads/items/'.$it['Image'].'" class="card-img-top" alt="..." style="height:250px;">';
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
</div>

<?php
    include $tpl."footer.php";
?>