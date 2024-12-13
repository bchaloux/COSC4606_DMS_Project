<?php

require_once('../includes/connection_test.php');

if(isset($_POST['purchaseItem'])){
    $item = $_POST['purchase'];
    $get_bells = "SELECT bells FROM Islander WHERE username = 'Benji'";
    $check = $con->query($get_bells);
    $bell_count = mysqli_fetch_assoc($check);
    $bell_balance = $bell_count['bells'];
    
    $get_price = "SELECT sellPrice FROM Inventory WHERE inventoryItemID = $item";
    $run_price = $con->query($get_price);
    $sell_price = mysqli_fetch_assoc($run_price);
    $sell_price = $sell_price['sellPrice'];

    if($bell_balance > $sell_price){
        $update_bells = "UPDATE Islander SET bells = bells - $sell_price WHERE username = 'Benji'";
        $run_update = $con->query($update_bells);
        $sql_store_visit = "INSERT INTO storeVisits (storeName, visitDate, username) VALUES ('Nook''s Cranny', current_date, 'Benji')";
        $query = $con -> query($sql_store_visit);
        $select_store_visit = "SELECT MAX(storeVisitsID) AS storeVisitsID FROM storeVisits";
        $storevisitGet = $con -> query($select_store_visit);
        $storevisitGet = mysqli_fetch_assoc($storevisitGet);
        $storevisit = $storevisitGet['storeVisitsID'];
        $sql_purchased_items = "INSERT INTO PurchasedItems (quantity, storeVisitsID, inventoryItemID) VALUES (1, $storevisit, $item)";
        $query = $con -> query($sql_purchased_items);
        header("Location: shop.php");
        exit();      
    }
}                                   

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    <title>ACNH</title>
	<link rel="shortcut icon" href="../images/acnh_project_logo.png">
	<link rel="stylesheet" href="../styles/shop.css">
	<link rel="stylesheet" href="../styles/normalize.css">
</head>
<body>
   <header> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
         <aside class="leftnav">
            <nav>
                <ul>
                    <li>
                        <a href="shop.php">Shop</a>
                    </li>
                </ul>
            </nav>                   
        </aside> 
        <aside class="rightnav">
            <nav>
                <ul>
                    <li>
                        <a class="current" href="../index.html"><i class="fa fa-fw fa-home"></i>Home</a>
                    </li>
                    <li>
                        <a href="../login/login.html"><i class="fa fa-fw fa-user"></i>Login</a>
                    </li>
                </ul>
            </nav>
        </aside> 
        <a class="projectlogo" href="../index.html"><img src="../images/acnh_project_logo.png" alt="ACNH Logo"></a> 
    </header>
    <main>
    <!--Nav Bar for all the different pages-->
    <div>
        <aside class="navbar">
            <nav>
                <ul>
                    <li>
                        <a class="current" href="../villager/villager.php"></i>Villagers</a>
                    </li>
                    <li>
                        <a class="current" href="../crafting/crafting.php"></i>Craft</a>
                    </li>
                    <li>
                        <a class="current" href="../shop/shop.php"></i>Shop</a>
                    </li>
                    <li>
                        <a class="current" href="../trade/trade.php"></i>Trade</a>
                    </li>
                    <li>
                        <a class="current" href="../inventory/inventory.php"></i>Inventory</a>
                    </li>
                </ul>
                </nav>
            </aside>
    </div>
    <h1>Shop</h1>
        <div class="grid-container"></div>
        <br/>
        <center>
            <?php 
                $b = "SELECT bells FROM Islander WHERE username = 'Benji'"; 
                $rb = $con -> query($b);
                $result = mysqli_fetch_assoc($rb); 
                $bell_amount = $result['bells']; 
            ?>
        <aside>
            <h2>Bells: <?php echo $bell_amount ?></h2>
            <form class="" action="shop.php" method="post">

                <input type="text" placeholder=" Search for a product name" name="search">

                <!-- Search button -->
                <button class="searchbtn" type="submit" name="search_button"><i class="fa fa-fw fa-search"></i></button>
            </form>
        </aside>
        </center>
        <br/>
        <center>
        <section>
            <?php
            if (isset($_POST['search_button']))
            {
                 $search_value = htmlentities(mysqli_real_escape_string($con,$_POST['search']));
                 $sql = "SELECT * FROM Inventory WHERE itemName = '$search_value'";
            }

            else {
                $date = "SELECT (WEEKDAY(current_date)) AS day_of_the_week";
                $date_result = $con->query($date);
                $row = $date_result->fetch_assoc();
                $date_value = $row['day_of_the_week'];
                $sql = "SELECT * FROM Inventory ORDER BY RAND($date_value) LIMIT 12";
            }

            $product_list = $con->query($sql);
            $count = 0;
            
            echo("<table><tr>");
            while($row=mysqli_fetch_assoc($product_list)) 
            {
                if(($count % 4) == 0)
                {
                    echo("</tr><tr>\n"); 
                    $count++;

                }
                else {
                    $count++;
                }
                echo("<td class=\"spacer\"></td>");
                echo("<td>");
            ?>   
            
            <div class="card">
               <div class="image">
                   <img class="shop_img" 
                   src="<?php echo $row["itemImg"]; ?>" alt="Item"> 
               </div>
               <div class="caption">
                   <p class="shop_name"><?php echo $row["itemName"]; ?></p>
                   <p class="price"><b>$<?php echo $row["sellPrice"]; ?></b></p>
               </div>
               <table class="card_actions">
                   <tr>
                       <td>
                         <form method="post">
                            <input type="hidden" name="purchase" value="<?php echo $row['inventoryItemID'] ?>">
                            <input class="view" type="submit" name="purchaseItem" value="Purchase">
                         </form>
                       </td>
                   </tr>
               </table>
            </div>
            <br/>
            <?php 
                echo("</td>");
                
            } 
            echo("</tr></table>");
            ?>
        </section>
        </center>
    </main>
</body>
<footer>
   <center>
    <p>
        <em>Built by Benjamin and Stephanie &copy;</em>
    </p>
    </center>
</footer>
</html>