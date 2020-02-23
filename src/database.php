<?php
/**
 * @Author: Kevin Le
 * @Date:   27/09/19 09:12:41
 * @Email:  kevin.le2@student.curtin.edu.au
 *
 * @Last modified by:   Kevin Le - 19472960
 * @Last modified time: 01/10/2019 20:07:57
 *
 * @Project: CCSEP Assignment S2 2019
 * @Filename: database.php
 *
 * @Purpose: Provides connection to database, "assignment" using hard-coded credentials.
 *
 */

    //Database constants
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'student');
    define('DB_PASSWORD', 'CCSEP2019'); //NOTE: Hard-coded password.
    define('DB_DATABASE', 'assignment');

    $db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE); //Create connection

    if(mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error(); //Display error if connection failed. NOTE: Bad, user should not see errors such as this.
    }
?>
