<?php
/**
 * @Author: Kevin Le
 * @Date:   27/09/19 09:12:41
 * @Email:  kevin.le2@student.curtin.edu.au
 *
 * @Last modified by:   Kevin Le - 19472960
 * @Last modified time: 01/10/2019 20:08:37
 *
 * @Project: CCSEP Assignment S2 2019
 * @Filename: user.php
 *
 * @Purpose: Page for user if logged in to add/ see balance and items currently listed/ sold.
 *
 */

    require_once("database.php");
    session_start();
?>
<!DOCTYPE html>

<html>
    <?php if(isset($_SESSION['activelogin']) && $_SESSION['activelogin'] == true): //If user logged in, display user page?>

    <head>
        <title>Ebuy - User Profile</title>
        <meta charset="utf-8">
        <link rel="icon" type="image/png" sizes="200x200" href="assets/img/favicon.png">
        <link rel="icon" type="image/png" sizes="200x200" href="assets/img/favicon.png">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/styles.css">
    </head>

    <body style="padding: 60px;">
        <div class="container" id="headerBar">
            <div class="row">
                <div class="col" id="websiteName">
                    <a href="/">
                    <img style="width: 100px;height: 50px;" src="assets/img/logo.png" /></a>
                </div>

                <div class="col text-right align-self-center" id="headerButtonRight">
                    <form method="post" class="shadow-none">
                        <div class="form-group">
                            <!--Sign Out Button -->
                            <button class="btn btn-danger text-light" value="RUN" name="submit" type="submit">Sign Out</button>
                        </div>

                        <?php
                            if(array_key_exists('submit', $_POST)) //If sign out clicked
                            {
                                $_SESSION = array(); //Clear session array
                                session_destroy(); //Destroy session

                                header("Location:/"); //Send user to home page
                                exit();
                            }
                        ?>
                    </form>
                </div>
            </div>
        </div>

    <div class="container" id="profile" style="padding-top: 40px;width: 400;">
        <div class="row">
            <div class="col" style="padding-right: 0px;width: 146px;"><img style="width: 200px;height: 200px;" src="assets/img/userImage.png"></div>
            <div class="col">
                <?php
                    $uid = $_SESSION['uid']; //Get currently logged in user ID
                    $uname = $_SESSION['uname']; //Get currently logged in user name

                    $uidsql = "SELECT FORMAT(balance, 2) FROM users WHERE uid = '$uid'"; //Retrieve balance
                    $uinfo = mysqli_query ($db, $uidsql);
                    $row = mysqli_fetch_array($uinfo);

                    echo "<h1>$uname</h1>"; //Display name
                    echo "<h3><strong>Balance:</strong> $$row[0]</h3>"; //Display Balance
                ?>
            </div>
            <div class="col"></div>
            <div class="col"></div>
        </div>
    </div>

    <div class="container" style="padding-top: 20px;">
        <form method="GET" action="/user.php">
            <div class="input-group" style="width: 256px;">
                <!--Add funds section-->
                <!--Amount to add--><input name="funds" class="border rounded form-control" style ="background-color: rgb(247,249,252);" type="text" required="" pattern="^(0|[1-9][0-9]*)$" placeholder="Funds to add">
                <div class="input-group-append">
                    <!--Add Button--><button class="btn btn-primary" type="submit">Add</button>
                </div>
            </div>
        </form>

        <?php
            if(isset($_GET["funds"])) //If page called to add funds
            {
                $uid = $_SESSION['uid']; //Get currently logged in user ID
                $funds = $_GET["funds"]; //Get funds to add

                $sql = "UPDATE users SET balance = balance + $funds WHERE uid = '$uid'"; //Add funds to users current balance
                mysqli_query($db, $sql);

                header("Location:/user.php"); //Refresh to show new balance and remove input param from request.
                exit();
            }
        ?>
    </div>

    <!--Items user has for sale page-->
    <div class="container" id="itemsForSale" style="padding-top: 40px;">
        <h3>Items for sale</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <!--Table headers-->
                        <th>Item Name</th>
                        <th style="width: 150px;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $uid = $_SESSION['uid']; //Get currently logged in user id

                        $sql = "SELECT items.itemname, FORMAT(items.price,2), sold FROM items WHERE sellerid = '$uid'"; //Get items user has for sale
                        $result = mysqli_query($db, $sql);

                        while($row = mysqli_fetch_array($result)) //For each row get the array
                        {
                            echo "<tr>"; //For each table row
                            if($row[2] == 1) //If item has been sold
                            {
                                echo "<td><strike>$row[0]</strike> <strong>SOLD</strong></td>"; //Cross out item name and display "SOLD"
                            }
                            else
                            {
                                echo "<td>$row[0]</td>"; //Display item name
                            }
                            echo "<td>$$row[1]</td>"; //Display item price
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    </body>
<?php else: ?> <!--If not logged in, redirect to login page.-->
    <script>window.location.replace("/access.php?page=login.php");</script>
<?php endif; ?>
</html>
