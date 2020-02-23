<!DOCTYPE html>
<!--
 * @Author: Kevin Le
 * @Date:   27/09/19 09:12:41
 * @Email:  kevin.le2@student.curtin.edu.au
 *
 * @Last modified by:   Kevin Le - 19472960
 * @Last modified time: 01/10/2019 20:07:55
 *
 * @Project: CCSEP Assignment S2 2019
 * @Filename: login.php
 *
 * @Purpose: Page for recieving user input for logging in. Takes email and passwords
 * and checks for match in database.
 *
-->

<html>
    <head>
        <title>Ebuy - Login</title>
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
                <form method="post" class="shadow-none" style="height: 350px;margin: 40px;width: 400px;">
                    <a href="/"><img style="width: 100px;height: 50px;" src="assets/img/logo.png" /></a>

                    <h2 class="text-left text-dark" style="padding-bottom: 20px;padding-top: 15px;">Sign in</h2>

                    <!--Email field--><div class="form-group"><input class="border rounded form-control" type="input" name="email" placeholder="Email" style="width: 350px;height: 45px;"></div>
                    <!--Password field--><div class="form-group"><input class="border rounded form-control" type="password" name="password" placeholder="Password" style="width: 350px;height: 45px;"></div>

                    <div class="form-group">
                        <!--Sign in button--><button class="btn btn-success btn-block" value="RUN" name="submit" type="submit" style="width: 70%;">Sign in</button>
                        <!--Register button--><a class="btn btn-secondary btn-block" role="button" href="/access.php?page=register.php" style="padding: auto;padding-bottom: 6px;width: 70%;">Register</a>
                    </div>

                    <?php
                        if(array_key_exists('submit', $_POST)) //If "Sign in" clicked.
                        {
                            $email = $_POST['email']; //Get entered email NOTE: SQL Injection possible
                            $pwd = $_POST['password']; //Get entered password

                            $hashedpwdentry = md5($pwd); //Hash password using MD5. NOTE: Broken hash function

                            $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$hashedpwdentry'"; //Find match for email and password combination
                            $result = mysqli_query ($db, $sql);
                            $rowcount = mysqli_num_rows($result); //Get number of matches

                            if($rowcount == 1) //If a match is found, allow login.
                            {
                                $uidsql = "SELECT uid, name, admin, enabled FROM users WHERE email = '$email' AND password = '$hashedpwdentry'"; //Get user information

                                $uinfo = mysqli_query ($db, $uidsql);
                                $row = mysqli_fetch_array($uinfo); //Get user row as array

                                if($row[3] == 1) //If account isn't disabled
                                {
                                    session_start(); //Start session

                                    //Store variables in session
                                    $_SESSION['activelogin'] = true;
                                    $_SESSION['uid'] = $row[0]; //User id
                                    $_SESSION['uname'] = $row[1]; //User name
                                    $_SESSION['uadmin'] = $row[2]; //If user admin

                                    header("Location:/"); //Go to home page
                                    exit();
                                }
                                else //Account is disabled
                                {
                                    echo "Your account has been disabled.";
                                }
                            }
                            else //Error logging in. Show output
                            {
                                //NOTE: VERY BAD PRACTICE. Show minimum information. Too verbose. Should just display "Incorrect Email or Password"

                                //Check if entered email is correct
                                $sql2 = "SELECT * FROM users WHERE email ='$email'";
                                $uninfo2 = mysqli_query ($db, $sql2);
                                $rowcount2 = mysqli_num_rows($uninfo2);

                                //Check if entered password is correct
                                $sql3 = "SELECT * FROM users WHERE password ='$hashedpwdentry'";
                                $uninfo3 = mysqli_query ($db, $sql3);
                                $rowcount3 = mysqli_num_rows($uninfo3);

                                if($rowcount2 == 0 && $rowcount3 == 0) //Email and Password does not exist
                                {
                                    echo "Incorrect Email and Password";
                                }
                                else if($rowcount2 == 0 && $rowcount3 != 0) //Email exists but password doesn't.
                                {
                                    echo "Incorrect Email";
                                }
                                else if($rowcount3 == 0 && $rowcount2 != 0) //Password exists but email doesn't
                                {
                                    echo "Incorrect Password";
                                }
                                else
                                {
                                    echo "Error";
                                }
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
