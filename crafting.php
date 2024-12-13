<?php

require_once('../includes/connection_test.php');

if(isset($_POST['craftItem'])){
    
    
    
    $craft_item = $_POST['craft'];
    
    $getname = "SELECT craftedItemName from craftedItemRecipe WHERE craftedItemRecipeID = $craft_item";
    $requestname = $con->query($getname);
    $itemname = $requestname->fetch_assoc();
    $craftedItemName = $itemname['craftedItemName'];
    
    $matTest = "SELECT C.craftedItemRecipeID, C.craftedItemName, C.materialName, C.requiredQuantity, C.recipeImg, M.materialName, M.quantity as MaterialHeld 
                FROM craftedItemRecipe C 
                JOIN MaterialsOwned M ON C.materialName = M.materialName
                JOIN Islander ON M.username = Islander.username 
                WHERE Islander.username = 'Benji' 
                AND C.craftedItemName = '$craftedItemName'";

    $requestmat = $con->query($matTest);
    $materials = mysqli_fetch_assoc($requestmat);

    $requiredQuantity = $materials['requiredQuantity'];
    $quantityHeld = $materials['MaterialHeld'];
    $materialName = $materials['materialName'];

    if($quantityHeld >= $requiredQuantity){

        $craft_complete = "UPDATE MaterialsOwned SET quantity = quantity - $requiredQuantity WHERE materialName = '$materialName' AND username = 'Benji'";
        $request_craft = $con->query($craft_complete);

        $pretest = "SELECT count($craft_item) AS count FROM craftedItemsOwned WHERE username = 'Benji' AND craftedItemRecipeID = '$craft_item'";
        $pre_test = $con->query($pretest);
        $count = mysqli_fetch_assoc($pre_test);

        if($count['count'] = 1){
            $sql = "UPDATE craftedItemsOwned SET quantity = quantity + 1 WHERE username = 'Benji' AND craftedItemRecipeID = '$craft_item'";
            $query = $con-> query($sql);
        }else{
        
            $sql = "INSERT INTO craftedItemsOwned VALUES ('$craft_item', 'Benji', 1)";
            $query = $con-> query($sql);
        }

        header("Location: crafting.php");
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
	<link rel="stylesheet" href="../styles/crafting.css">
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
        <h1>Crafting</h1>
        <div class="grid-container"></div>
        <br/>
        <center>
        <aside>
            <form action="crafting.php" method="post">
                <input type="text" placeholder=" Search for a craftable item name" name="search">
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
                 $sql = "SELECT * FROM craftedItemRecipe WHERE craftedItemName = '$search_value'";
            }
            else {
                $sql = "SELECT * FROM craftedItemRecipe";
            }

            $craftable_itmes_list = $con->query($sql);
            $count = 0;
            
            echo("<table><tr>");
            while($row=mysqli_fetch_assoc($craftable_itmes_list)) 
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
                   <img class="craft_img" 
                   src="<?php echo $row["recipeImg"]; ?>" alt="Craftable Item"> 
               </div>
               <div class="caption">
                   <p class="craft_name"><?php echo $row["craftedItemName"]; ?></p>
               </div>
               <table method="post">
                   <tr>
                       <td>
                         <form class = "" method="post">
                          <input type="hidden" name="craft" value="<?php echo $row["craftedItemRecipeID"]; ?>">
                          <input class="view" type="submit" name="craftItem" value="Craft">
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