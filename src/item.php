<?php
/**
 * @Author: Kevin Le
 * @Date:   27/09/19 09:12:41
 * @Email:  kevin.le2@student.curtin.edu.au
 *
 * @Last modified by:   Kevin Le - 19472960
 * @Last modified time: 01/10/2019 20:07:48
 *
 * @Project: CCSEP Assignment S2 2019
 * @Filename: item.php
 *
 * @Purpose: Page for showing details about an item listing. Recieves an item id
 * and uses that id to retrieve the listing from items table.
 *
 */

    require_once("database.php");
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>EBuy - Item Information</title>

    <meta charset="utf-8">
    <link rel="icon" type="image/png" sizes="200x200" href="assets/img/favicon.png">
    <link rel="icon" type="image/png" sizes="200x200" href="assets/img/favicon.png">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<?php
    //Logic for handling a purchase request.

    if(isset($_GET['item']) && isset($_GET['purchase']) && isset($_GET['seller'])) //If the user has clicked the buy button, loading the website with variables item id and seller id
    {
        $iid = $_GET['purchase']; //Get id of item to purchase
        $sellerid = $_GET['seller']; //Get id of seller
        $buyerid = $_SESSION['uid']; //Get UID of user currently logged in.

        //Get sellers balance
        $sellersql = "SELECT balance FROM users WHERE uid = $sellerid";
        $result0 = mysqli_query($db, $sellersql);
        $row0 = mysqli_fetch_array($result0);

        //Get buyers balance
        $buyersql = "SELECT balance FROM users WHERE uid = $buyerid";
        $result1 = mysqli_query($db, $buyersql);
        $row1 = mysqli_fetch_array($result1);

        //Get item price
        $itemsql = "SELECT price, sold FROM items WHERE iid = $iid";
        $result2 = mysqli_query($db, $itemsql);
        $row2 = mysqli_fetch_array($result2);

        //If the buyers balance is more than or equal to the item price, and the item hasnt already been purchased, and the buyer isnt the seller
        if($row1[0] >= $row2[0] && $row2[0] != 1 && $buyerid != $sellerid)
        {
            $buyerNewBal = $row1[0] - $row2[0]; //Calculate buyers new balance after deducting cost of the item purchased
            $sellerNewBal = $row0[0] + $row2[0]; //Calculate sellers new balance after adding price of item sold

            $updateSQL1 = "UPDATE users SET balance = $buyerNewBal WHERE uid = $buyerid"; //Statement to update buyers balance with deducted funds
            $updateSQL2 = "UPDATE users SET balance = $sellerNewBal WHERE uid = $sellerid"; //Statement to update sellers balance with recieved funds
            $updateSQL3 = "UPDATE items SET sold = TRUE WHERE iid = $iid"; //Statement to set item to sold

            mysqli_query($db, $updateSQL1); //Execute all statements
            mysqli_query($db, $updateSQL2);
            mysqli_query($db, $updateSQL3);

            header("Location:/"); //Return buyer to home page, item has been sold so no more listing.
            exit();
        }
        else //Error purchasing. Either insufficient funds, buyer trying to buy own item, or item already purchased.
        {
            echo "<script>alert('Error purchasing item. Likely insufficient funds.')</script>";
        }
    }
 ?>
<body style="padding: 60px;">
    <div class="container" id="headerBar">
        <div class="row">
            <div class="col" id="websiteLogo"><a href="/"><img style="width: 100px;height: 50px;" src="assets/img/logo.png" /></a></div>

        </div>
    </div>
    <div class="container" style="padding-top: 40px;padding-right: 0px;">
        <div class="row" style="padding-top: 20px;">
            <!--User avatar placeholder-->
            <div class="col"><img style="max-height: 250px;height: 250px;width: 400px;" src="https://via.placeholder.com/400x250"></div>
        </div>
        <div class="row">
            <div class="col">
                <?php
                    if(isset($_GET['item'])) //If item ID is set
                    {
                        $iid = $_GET['item']; //Get item id requested to be viewed NOTE: Blind SQL Injection possible

                        $sql = "SELECT items.itemname, items.price, items.description, users.name, users.uid FROM items INNER JOIN users ON items.sellerid = users.uid WHERE items.iid = $iid"; //Get item information
                        $result = mysqli_query($db, $sql);
                        $row = mysqli_fetch_array($result); //Return item row as array

                        echo "<h1 style=\"padding-top: 20px;padding-bottom: 0px;\"><strong>$row[0]</strong></h1>"; //Item name
                        echo "<p><strong>Seller: </strong>$row[3]</p>"; //Seller name
                        echo "<h2 style=\"padding-bottom: 10px;\">$$row[1]</h2>"; //Show item price

                        if(isset($_SESSION['activelogin']) && $_SESSION['activelogin'] == true) //If the user is currently logged in
                        {
                            echo "<a href=\"/item.php?item=$iid&purchase=$iid&seller=$row[4]\"><button class=\"btn btn-success\" type=\"button\" style=\"width: 100px;height: 50px;\">Buy</button></a>"; //Show a button to buy item
                        }
                        else //User not logged in
                        {
                            echo "<a href=\"/access.php?page=login.php\"><button class=\"btn btn-dark\" type=\"button\" style=\"width: 200px;height: 50px;\">Login to Buy</button></a>"; //Show button for user to login
                        }

                        echo "<p style=\"padding-top: 20px;\">$row[2]<br><br></p>"; //Show Item description NOTE: XSS possible here.
                    }
                    else //No item requested
                    {
                        echo "Error: No item selected."; //Print error
                    }
                ?>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
