<!DOCTYPE html>
<!--
 * @Author: Kevin Le
 * @Date:   27/09/19 09:12:41
 * @Email:  kevin.le2@student.curtin.edu.au
 *
 * @Last modified by:   Kevin Le - 19472960
 * @Last modified time: 01/10/2019 20:07:51
 *
 * @Project: CCSEP Assignment S2 2019
 * @Filename: register.php
 *
 * @Purpose: Page for recieving user input to create a new website. Uses input to create
 * a new user account.
 *
-->

<html>
    <head>
        <title>Ebuy - Register</title>
        <meta charset="utf-8">
        <link rel="icon" type="image/png" sizes="200x200" href="assets/img/favicon.png">
        <link rel="icon" type="image/png" sizes="200x200" href="assets/img/favicon.png">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/form.css">
        <link rel="stylesheet" href="assets/css/styles.css">
    </head>

    <body>
        <div class="bg-white login-clean">
            <div class="container" style="width: 1000px;">
                <form class="shadow-none" method="post" style="height: 350px;margin: 40px;width: 400px;"><a href="/">
                    <img style="width: 100px;height: 50px;" src="assets/img/logo.png" /></a>

                    <h2 class="text-left text-dark" style="padding-bottom: 20px;padding-top: 15px;">Register</h2>

                    <!--User Registration Fields-->
                    <!--Name--><div class="form-group"><input class="border rounded form-control" name="name" placeholder="Name" style="width: 350px;height: 45px;" /></div>
                    <!--Email--><div class="form-group"><input class="border rounded form-control" type="email" name="email" placeholder="Email" style="width: 350px;height: 45px;"></div>
                    <!--Password--><div class="form-group"><input class="border rounded form-control" type="password" name="password1" placeholder="Password" style="width: 350px;height: 45px;"></div>
                    <!--Password (Repeated)--><div class="form-group"><input class="border rounded form-control" type="password" name="password2" placeholder="Password (Repeat)" style="width: 350px;height: 45px;"></div>

                    <div class="form-group"><button class="btn btn-success btn-block" value="RUN" name="submit" type="submit" style="width: 70%;">Register</button></div>

                    <?php
                        if(array_key_exists('submit', $_POST)) //Register button clicked
                        {
                            $name = $_POST['name']; //Get entered name
                            $email = $_POST['email']; //Get entered email
                            $pwd1 = $_POST['password1']; //Get entered password1
                            $pwd2 = $_POST['password2']; //Get entered password2

                            if($pwd1 == $pwd2) //Check if passwords match.
                            {
                                $hashedpwd = md5($pwd1); //Hash password to store. MD5

                                $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedpwd')"; //Add details to database
                                mysqli_query($db, $sql);

                                echo "Hello $name! Please sign in <a = href=\"/access.php?page=login.php\">here.</a>"; //Display welcome message and link to login page.
                            }
                            else //Passwords don't match.
                            {
                                echo "Passwords don't match, please try again.";
                            }
                        }
                    ?>
                </form>
            </div>
        </div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
