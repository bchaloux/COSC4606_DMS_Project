<?php

require_once('../includes/connection_test.php');

if(isset($_POST['tradeItem'])){
    $item = $_POST['trade'];

    $datehour = "SELECT MOD(HOUR(current_time), 12) AS hour, WEEKDAY(current_date) AS 'date'";
    $datehour_result = $con->query($datehour);
    $row = $datehour_result->fetch_assoc();
    $date_value = $row['date'];
    $hour_value = $row['hour'];

    $sql = "SELECT inventoryItemID FROM Inventory ORDER BY RAND($date_value+$hour_value) LIMIT 1";
    $trade = $con->query($sql);
    $receiveable_item = mysqli_fetch_assoc($trade);
    
    $receiving = $receiveable_item['inventoryItemID'];

    $start_trade = "INSERT INTO TradeTransaction (transDate, villagerGiven, islanderItemGiven, villagerName, username) VALUES (current_date, $receiving, $item, 'Ace', 'Benji')";
    $run_trade = $con -> query($start_trade);

    $pretest = "SELECT count(*) AS count FROM craftedItemsOwned WHERE username = 'Benji' AND craftedItemRecipeID = '$item'";
    $pre_test = $con->query($pretest);
    $count = mysqli_fetch_assoc($pre_test);

    if($count['count'] = 1){

        $sql = "DELETE FROM craftedItemsOwned WHERE craftedItemRecipeID = '$item'";
        $query = $con-> query($sql);

    }else{
    
        $sql = "UPDATE craftedItemsOwned SET quantity = quantity - 1 WHERE username = 'Benji' AND craftedItemRecipeID = '$item";
        $query = $con-> query($sql);

    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    <title>ACNH</title>
	<link rel="shortcut icon" href="../images/acnh_project_logo.png">
	<link rel="stylesheet" href="../styles/trade.css">
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
        <h1>Trading</h1>
        <center>
        <div class="encapsulate">
        <table>
            <td>
                <h2>Inventory Items</h2>
                <div style= "width:600px; height:500px; overflow-y:auto;">
                    <section>
                        <?php
                            if (isset($_POST['search_button'])) {
                                $search_value = htmlentities(mysqli_real_escape_string($con, $_POST['search']));
                                $sql = "SELECT * FROM (SELECT C.craftedItemRecipeID, C.craftedItemName, C.sellPrice, C.recipeImg, O.username FROM craftedItemRecipe C 
                                        JOIN craftedItemsOwned O ON C.craftedItemRecipeID = O.craftedItemRecipeID 
                                        JOIN Islander ON O.username = Islander.username WHERE O.username = 'Benji') WHERE itemName = '$search_value'";
                            } else {
                                $sql = "SELECT C.craftedItemRecipeID, C.craftedItemName, C.sellPrice, C.recipeImg, O.username FROM craftedItemRecipe C 
                                        JOIN craftedItemsOwned O ON C.craftedItemRecipeID = O.craftedItemRecipeID 
                                        JOIN Islander ON O.username = Islander.username WHERE O.username = 'Benji'";
                            }

                            $product_list = $con->query($sql);
                            $count = 0;

                            echo("<table><tr>");
                            while ($row = mysqli_fetch_assoc($product_list)) {
                                if (($count % 3) == 0) {
                                    echo("</tr><tr>\n");
                                    $count++;
                                } else {
                                    $count++;
                                }
                            echo("<td class=\"spacer\"></td>");
                            echo("<td>");
                        ?>                
                        <div class="card">
                            <div class="image">
                                <img class="trade_img" src="<?php echo $row["recipeImg"]; ?>" alt="Item">
                            </div>
                            <div class="caption">
                                <p class="item_name"><?php echo $row["craftedItemName"]; ?></p>
                            </div>
                            <table class="card_actions">
                                <tr>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="trade" value="<?php echo $row['craftedItemRecipeID'] ?>">
                                            <input class="view" type="submit" name="tradeItem" value="Trade">
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
                </div>
            </td>
            <td>
                <div class="arrows">
                    <a class="projectlogo">
                        <img src="../images/arrows.png" alt="arrows">
                    </a>
                </div>
            </td>
            <td>
                <div class="card">
                    <?php
                        $datehour = "SELECT MOD(HOUR(current_time), 12) AS hour, WEEKDAY(current_date) AS 'date'";
                        $datehour_result = $con->query($datehour);
                        $row = $datehour_result->fetch_assoc();
                        $date_value = $row['date'];
                        $hour_value = $row['hour'];
                        $sql = "SELECT * FROM Inventory ORDER BY RAND($date_value+$hour_value) LIMIT 1";
                        $prod = $con->query($sql);
                        $product = mysqli_fetch_assoc($prod);
                    ?>
                    <div class="image">
                        <img class="trade_timg" src="<?php echo $product["itemImg"]; ?>" alt="Jewelery">
                    </div>
                    <div class="caption">
                        <p class="item_name"><?php echo $product["itemName"]; ?></p>
                    </div>
                </div>
            </td>
        </table>
    </center>
    </div>
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