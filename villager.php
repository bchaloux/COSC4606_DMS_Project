<?php 

require_once('../includes/connection_test.php');

if(isset($_POST['removeVillager'])){
    $villager = $_POST['villager'];
    $sql = "DELETE FROM IslandVillager WHERE username = 'Benji' AND villagerName = '$villager'";
    $query = $con -> query($sql);
    header("Location: villager.php");
    exit();
}

if(isset($_POST['addVillager'])){
    $villager = $_POST['villager'];
    $sql = "INSERT INTO IslandVillager VALUES ('$villager', 'Benji')";
    $query = $con -> query($sql);
    header("Location: villager.php");
    exit();                                               
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    <title>ACNH</title>
	<link rel="shortcut icon" href="../images/acnh_project_logo.png">
	<link rel="stylesheet" href="../styles/villager.css">
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
                        <a class="current" href="../villager/villager.php">Villagers</a>
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
        <h1>Villagers</h1>
        <center>
        <div class = "encapsulate">
        <table>
            <td>
                <h2>My Villagers</h2>
                <div style= "width:600px; height:500px; overflow-y:auto;">
                    <section>
                        <?php
                            $sql = "SELECT * FROM VillagerMaster
                                             JOIN IslandVillager ON VillagerMaster.villagerName = IslandVillager.villagerName
                                             JOIN Islander ON IslandVillager.username = Islander.username
                                             WHERE Islander.username = 'Benji'";
                            $my_villagers = $con->query($sql);
                            $count = 0;
                            echo("<table><tr>");
                            while ($row = mysqli_fetch_assoc($my_villagers)) {
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
                                <img class="villager_img" src="<?php echo $row["villagerImg"]; ?>" alt="Villager">
                            </div>
                            <div class="caption">
                                <p class="villager_name"><?php echo $row["villagerName"]; ?></p>
                                <p class="personality"><?php echo $row["personalityType"]; ?></p>
                                <p class="species"><?php echo $row["species"]; ?></p>
                            </div>
                            <table class="card_actions">
                                <tr>
                                    <td>
                                        <form method="post">
                                        <input type="hidden" name="villager" value="<?php echo $row['villagerName']; ?>">
                                        <input class="view" type="submit" name="removeVillager" value="Remove">
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
                <h2>All Villagers</h2>
                <div style= "width:600px; height:500px; overflow-y:auto;">
                    <section>
                        <?php
                            $sql = "SELECT * FROM VillagerMaster EXCEPT (SELECT VillagerMaster.villagerName, personalityType, species, villagerImg FROM VillagerMaster JOIN IslandVillager ON VillagerMaster.villagerName = IslandVillager.villagerName where username = 'Benji')";
                            $all_villagers = $con->query($sql);
                            $count = 0;
                            echo("<table><tr>");
                            while ($row = mysqli_fetch_assoc($all_villagers)) {
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
                                <img class="villager_img" src="<?php echo $row["villagerImg"]; ?>" alt="Villager">
                            </div>
                            <div class="caption">
                                <p class="villager_name"><?php echo $row["villagerName"]; ?></p>
                                <p class="personality"><?php echo $row["personalityType"]; ?></p>
                                <p class="species"><?php echo $row["species"]; ?></p>
                            </div>
                            <table class="card_actions">
                                <tr>
                                    <td> 
                                        <form method="post">
                                            <input type="hidden" name="villager" value="<?php echo $row['villagerName']; ?>">
                                            <input class="view" type="submit" name="addVillager" value="Add">
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
        </table>
    </div>
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