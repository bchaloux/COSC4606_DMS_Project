<?php

require_once('../includes/connection_test.php');

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    <title>ACNH</title>
	<link rel="shortcut icon" href="../images/acnh_project_logo.png">
	<link rel="stylesheet" href="../styles/inventory.css">
	<link rel="stylesheet" href="../styles/normalize.css">
</head>
<body>
   <header> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
         <aside class="leftnav">
            <nav>
                <ul>
                    <li>
                        <a href="../shop/shop.php">Shop</a>
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
        <aside>
            <nav class="navbar">
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
        <h1>Inventory</h1>
        <div class="grid-container"></div>
        <br/>
        <center>
            <aside>
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
                 $sql = "SELECT * FROM (SELECT I.inventoryItemID, I.itemName, I.sellPrice, I.itemImg, S.username, SUM(P.quantity) AS total_quantity FROM Inventory I 
                         JOIN PurchasedItems P ON I.inventoryItemID = P.inventoryItemID 
                         JOIN storeVisits S ON P.storeVisitsID = S.storeVisitsID 
                         JOIN Islander ON S.username = Islander.username WHERE S.username = 'Benji'
                         GROUP BY I.inventoryItemID, I.itemName, I.sellPrice, I.itemImg, S.username
                         UNION 
                         SELECT C.craftedItemRecipeID, C.craftedItemName, C.sellPrice, C.recipeImg, O.username, SUM(O.quantity) as total_quantity FROM craftedItemRecipe C 
                         JOIN craftedItemsOwned O ON C.craftedItemRecipeID = O.craftedItemRecipeID 
                         JOIN Islander ON O.username = Islander.username WHERE O.username = 'Benji'
                         GROUP BY C.craftedItemRecipeID, C.craftedItemName, C.sellPrice, C.recipeImg, O.username
                         UNION
                         SELECT T.villagerGiven, I.itemName, I.sellPrice, I.itemImg, T.username, COUNT(T.villagerGiven) AS total_quantity FROM TradeTransaction T 
                         JOIN Inventory I ON T.villagerGiven = I.inventoryItemID WHERE T.username = 'Benji';) WHERE itemName = '$search_value'";
            }



            else {
                $sql = "SELECT I.inventoryItemID, I.itemName, I.sellPrice, I.itemImg, S.username, SUM(P.quantity) AS total_quantity FROM Inventory I 
                JOIN PurchasedItems P ON I.inventoryItemID = P.inventoryItemID 
                JOIN storeVisits S ON P.storeVisitsID = S.storeVisitsID 
                JOIN Islander ON S.username = Islander.username WHERE S.username = 'Benji'
                GROUP BY I.inventoryItemID, I.itemName, I.sellPrice, I.itemImg, S.username 
                UNION 
                SELECT C.craftedItemRecipeID, C.craftedItemName, C.sellPrice, C.recipeImg, O.username, SUM(O.quantity) as total_quantity FROM craftedItemRecipe C 
                JOIN craftedItemsOwned O ON C.craftedItemRecipeID = O.craftedItemRecipeID 
                JOIN Islander ON O.username = Islander.username WHERE O.username = 'Benji'
                GROUP BY C.craftedItemRecipeID, C.craftedItemName, C.sellPrice, C.recipeImg, O.username
                UNION
                SELECT T.villagerGiven, I.itemName, I.sellPrice, I.itemImg, T.username, COUNT(T.villagerGiven) AS total_quantity FROM TradeTransaction T 
                JOIN Inventory I ON T.villagerGiven = I.inventoryItemID WHERE T.username = 'Benji'
                GROUP BY T.villagerGiven, I.itemName, I.sellPrice, I.itemImg, T.username";
            }

            $item_list = $con->query($sql);
            $count = 0;
            
            echo("<table><tr>");
            while($row=mysqli_fetch_assoc($item_list)) 
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
                   <img class="item_img" 
                   src="<?php echo $row["itemImg"]; ?>" alt="Item"> 
               </div>
               <div class="caption">
                   <p class="item_name"><?php echo $row["itemName"]; ?></p>
                   <p class="quantity">Quantity: <?php echo $row["total_quantity"]; ?></p>
                   <p class="price">Sell Price: <?php echo $row["sellPrice"]; ?></p>
               </div>
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