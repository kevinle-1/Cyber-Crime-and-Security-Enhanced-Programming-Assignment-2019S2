<?php
/**
 * @Author: Kevin Le
 * @Date:   27/09/19 09:12:41
 * @Email:  kevin.le2@student.curtin.edu.au
 *
 * @Last modified by:   Kevin Le - 19472960
 * @Last modified time: 01/10/2019 20:07:40
 *
 * @Project: CCSEP Assignment S2 2019
 * @Filename: access.php
 *
 * @Purpose: Page that is used to load either the login or register pages.
 * Depending on request in URL.
 *
 */

    require_once("database.php");

    if(isset($_GET['page'])) //If page specified
    {
        $accesspage = $_GET['page']; //Get page
        include "$accesspage"; //Import page to display. NOTE: PHP File Inclusion Vulnerability
    }
    else
    {
        echo "Error"; //No page specified. Display "Error"
    }
 ?>
