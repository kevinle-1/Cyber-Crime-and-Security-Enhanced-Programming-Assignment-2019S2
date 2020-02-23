<?php
/**
 * @Author: Kevin Le
 * @Date:   27/09/19 09:12:41
 * @Email:  kevin.le2@student.curtin.edu.au
 *
 * @Last modified by:   Kevin Le - 19472960
 * @Last modified time: 01/10/2019 20:07:39
 *
 * @Project: CCSEP Assignment S2 2019
 * @Filename: adminpanel.php
 *
 * @Purpose: Page for managing administrator level settings
 * and functionality. E.g. Enable/Disable users.
 *
 */

    require_once("database.php");
    session_start();
?>
<!DOCTYPE html>
<html>
<?php if((isset($_SESSION['activelogin']) && $_SESSION['activelogin'] == true)): //Check if user currently logged in ?>
<head>
    <title>Ebuy - Admin Panel</title>

    <meta charset="utf-8">

    <link rel="icon" type="image/png" sizes="200x200" href="assets/img/favicon.png">
    <link rel="icon" type="image/png" sizes="200x200" href="assets/img/favicon.png">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<?php
    if(isset($_GET['deleteuser'])) //If the administrator requests deletion of user
    {
        $uid = $_GET['deleteuser']; //Get ID of user to delete
        $sql = "DELETE FROM users WHERE uid = $uid"; //Database query to delete user
        mysqli_query($db, $sql); //Execute query.

        header('Location: /adminpanel.php'); //Return administrator back to adminpanel, remove the request URL parameter
        exit();
    }

    if(isset($_GET['deleteitem'])) //If the administrator requests deletion of user
    {
        $iid = $_GET['deleteitem']; //Get ID of item to delete
        $sql = "DELETE FROM items WHERE iid = $iid"; //Database query to delete item
        mysqli_query($db, $sql); //Execute query.

        header('Location: /adminpanel.php'); //Return administrator back to adminpanel, remove the request URL parameter
        exit();
    }

    if(isset($_GET['disableuser'])) //If the administrator requests disabling a users account
    {
        $uid = $_GET['disableuser']; //Get ID of user to disable
        $sql = "UPDATE users SET enabled = FALSE WHERE uid = $uid"; //Database query to update users status to disabled.
        mysqli_query($db, $sql); //Execute query.

        header('Location: /adminpanel.php'); //Return administrator back to adminpanel, remove the request URL parameter
        exit();
    }

    if(isset($_GET['enableuser'])) //If the administrator requests enabling a users account
    {
        $uid = $_GET['enableuser']; //Get ID of user to enable
        $sql = "UPDATE users SET enabled = TRUE WHERE uid = $uid"; //Database query to update users status to enabled.
        mysqli_query($db, $sql); //Execute query.

        header('Location: /adminpanel.php'); //Return administrator back to adminpanel, remove the request URL parameter
        exit();
    }
 ?>
<body style="padding: 60px;">
    <div class="container" id="headerBar">
        <div class="row">
            <div class="col" id="websiteName"><a href="/"><img style="width: 100px;height: 50px;" src="assets/img/logo.png" /></a></div>
            <div class="col text-right align-self-center" id="headerButtons">
                <form method="post" class="shadow-none">
                    <!--Sign Out Button-->
                    <div class="form-group"><button class="btn btn-danger text-light" value="RUN" name="submit" type="submit">Sign Out</button></div>

                    <?php
                        if(array_key_exists('submit', $_POST)) //If the user has clicked the "Sign Out" button.
                        {
                            $_SESSION = array(); //Clear session array of variables.
                            session_destroy(); //Destroy session

                            header("Location:/"); //Return user to home page
                            exit();
                        }
                    ?>
                </form>
        </div>
    </div>
    <div class="container" id="header" style="padding-top: 40px;width: 400;">
        <h1 style="padding-bottom: 10px;">Admin Panel</h1>
    </div>

    <!--Table of registered users-->
    <div class="container" id="userList" style="padding-top: 10px;">
        <h2 style="padding-bottom: 10px;">Registered Users</h2>
        <p>Note: Cannot delete users with items listed.</p>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <!--Table Headers-->
                        <th style="width: 50px;">UID</th>
                        <th style="width: 450px;">Username</th>
                        <th style="width: 100px;">Balance</th>
                        <th style="width: 100px;">User Access</th>
                        <th style="width: 1px;"></th>
                    </tr>
                </thead>
                <tbody
                    <?php
                        //Load table data

                        $sql = "SELECT uid, name, enabled, admin, balance FROM users"; //Select all users
                        $result = mysqli_query($db, $sql); //Execute Query

                        while($row = mysqli_fetch_array($result)) //For each row, fetch it in array format
                        {
                            echo "<tr>"; //Render row using fields from each user.
                            echo "<td style=\"width: 1px;\">$row[0]</td>"; //UID
                            echo "<td style=\"width: 1px;\">$row[1]</td>"; //Name
                            echo "<td style=\"width: 1px;\">$$row[4]</td>"; //Balance

                            if($row[3] != 1) //If user not admin
                            {
                                if($row[2] == 1) //If user is enabled show button to disable.
                                {
                                    echo "<td style=\"width: 210px;\"><a href=\"/adminpanel.php?disableuser=$row[0]\"><button class=\"btn btn-danger\" type=\"button\" style=\"width: 78px;\">Disable</button></a></td>";
                                }
                                else //User disabled, show button to enable
                                {
                                    echo "<td style=\"width: 210px;\"><a href=\"/adminpanel.php?enableuser=$row[0]\"><button class=\"btn btn-success\" type=\"button\" style=\"width: 78px;\">Enable</button></a></td>";
                                }

                                echo "<td><a href=\"/adminpanel.php?deleteuser=$row[0]\"><button class=\"btn btn-danger\" type=\"button\">Delete</button></a></td>";
                            }
                            else //Disable Delete and Disable button
                            {
                                echo "<td>ADMIN</td>";
                                echo "<td>ADMIN</td>";
                            }

                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!--Table of listed items-->
    <div class="container" id="saleList" style="padding-bottom: 10px;">
        <h2 style="padding-bottom: 10px;">Listed Items</h2>
        <p>Crossed out items have been sold.</p>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <!--Table Headers-->
                        <th style="width: 50px;">IID</th>
                        <th style="width: 500px;">Item</th>
                        <th style="width: 100px;">Listed by UID</th>
                        <th style="width: 100px;">Price</th>
                        <th style="width: 1px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //Load table data

                        $sql = "SELECT iid, sellerid, itemname, FORMAT(price, 2), sold FROM items"; //Select all items from table.
                        $result = mysqli_query($db, $sql); //Execute query

                        while($row = mysqli_fetch_array($result)) //For each row, fetch it in array format
                        {
                            echo "<tr>"; //For each row, obtain fields
                            echo "<td>$row[0]</td>";
                            if($row[4] == 1) //Item has been sold
                            {
                                echo "<td style=\"width: 210px;\"><strike>$row[2]</strike> <strong>SOLD</strong></td>"; //Show the items name as crossed out and text "SOLD"
                            }
                            else //Item has not been sold
                            {
                                echo "<td style=\"width: 210px;\">$row[2]</td>"; //Item name
                            }
                            echo "<td style=\"width: 210px;\">$row[1]</td>"; //Seller ID
                            echo "<td style=\"width: 210px;\">$$row[3]</td>"; //Item Price
                            echo "<td><a href=\"/adminpanel.php?deleteitem=$row[0]\"><button class=\"btn btn-danger\" type=\"button\">Delete<br></button></a></td>"; //Delete item button
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
<?php else: //If user not admin, redirect to login page.?>
    <script> window.location.replace("/access.php?page=login.php"); </script>
<?php endif; ?>
</html>
