<?php
/**
 * @Author: Kevin Le
 * @Date:   27/09/19 09:12:41
 * @Email:  kevin.le2@student.curtin.edu.au
 *
 * @Last modified by:   Kevin Le - 19472960
 * @Last modified time: 01/10/2019 20:07:44
 *
 * @Project: CCSEP Assignment S2 2019
 * @Filename: sell.php
 *
 * @Purpose: Page that checks if user logged in, then allows them to input data
 * to make a new item listing.
 *
 */

    require_once("database.php");
    session_start();
?>
<!DOCTYPE html>

<html>
    <?php if(isset($_SESSION['activelogin']) && $_SESSION['activelogin'] == true): //If user logged in, then allow them to list an item?>

    <head>
        <title>Ebuy - Sell Something</title>
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
            <div class="col" id="websiteName"><a href="/"><img style="width: 100px;height: 50px;" src="assets/img/logo.png" /></a></div>
            </div>
        </div>

        <div class="container" id="itemList" style="padding-top: 20px;width: 100%;">
            <h1 style="padding-bottom: 15px;">List an item</h1>

            <?php
                $name = $_SESSION['uname']; //Get name of person currently logged in
                echo "<p><strong>Listing item as:</strong> $name</p>"; //Listing as name of current user logged in.
            ?>

            <form method="post" class="shadow-none">
                <!--Item Listing Fields-->
                <!--Item Name--><div class="form-group"><input class="form-control inputSpaces" name="itemName" type="text" style="width: 50%;background-color: rgb(247,249,252);height: 40px;" placeholder="Name"></div>
                <!--Item Description--><div class="form-group"><input class="form-control inputSpaces" name="description" type="text" style="width: 50%;background-color: rgb(247,249,252);height: 40px;" placeholder="Description"></div>
                <!--Item Price--><div class="form-group"><input class="form-control inputSpaces" name="price" type="text" style="width: 200px;background-color: rgb(247,249,252);height: 70px;font-size: 30px;" placeholder="Price" required="" pattern="^\d*(\.\d{0,2})?$"></div>

                <!--Sell Button Clicked-->
                <div class="form-group"><button class="btn btn-success btn-block" value="RUN" name="submit" type="submit" style="width: 75px;">Sell!</button></div>
            </form>
        </div>

        <?php
            if(array_key_exists('submit', $_POST)) //If Sell Button Clicked
            {
                $uid = $_SESSION['uid']; //Get User ID of current person logged in

                $itemName = $_POST['itemName']; //Set item name to entered name
                //Set item description to entered description. Mysqli escape string allows for XSS as it escapes special characters and allows it to be stored in database
                $desc = mysqli_real_escape_string($db, $_POST['description']);
                $price = $_POST['price'];

                $sql = "INSERT INTO items (sellerid, itemname, price, description) VALUES ('$uid', '$itemName', '$price', '$desc')"; //Insert into database
                mysqli_query($db, $sql);

                header("Location:/"); //Go to home page after item listed
                exit();
            }
        ?>

        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    </body>
<?php else: //If user not logged in, redirect to login page.?>
    <script> window.location.replace("/access.php?page=login.php"); </script>
<?php endif; ?>
</html>
