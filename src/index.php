<?php
/**
 * @Author: Kevin Le
 * @Date:   27/09/19 09:12:41
 * @Email:  kevin.le2@student.curtin.edu.au
 *
 * @Last modified by:   Kevin Le - 19472960
 * @Last modified time: 01/10/2019 20:07:36
 *
 * @Project: CCSEP Assignment S2 2019
 * @Filename: index.php
 *
 * @Purpose: Main home page of website, shows items for sale, search field,
 * login, profile management, admin panel link and sell button. Depending on
 * if user logged in and account type.
 *
 */

	require_once("database.php"); //Include database file.
	session_start(); //For managing user login sessions.
?>
<!DOCTYPE html>

<html>
	<head>
		<title>EBuy</title>
		<meta charset="utf-8">
		<!--Favicons-->
		<link rel="icon" type="image/png" sizes="200x200" href="assets/img/favicon.png">
		<link rel="icon" type="image/png" sizes="200x200" href="assets/img/favicon.png">

		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

		 <!--CSS-->
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/styles.css">
	</head>

	<body style="padding: 60px;">
		<div class="container" id="headerBar">
			<div class="row">
				<div class="col" id="websiteName"><a href="/"><img style="width: 100px;height: 50px;" src="assets/img/logo.png" /></a></div>
					<div class="col text-right align-self-center" id="headerButtons">
						<a class="btn btn-info text-light" type="button" style="width: 175px;" href="sell.php">Sell Something!</a> <!--ALL USERS: Button to initiate listing an item-->

						<?php
							//Decide what buttons to show on header bar.

							if((isset($_SESSION['activelogin']) && $_SESSION['activelogin'] == true) && $_SESSION['uadmin'] == 1) //User logged in is admin
							{
								echo "<a class=\"btn btn-dark text-light btnSpace\" role=\"button\" href=\"adminpanel.php\">Admin Panel</a>"; //Show link to admin panel
								echo "<a class=\"btn btn-dark text-light btnSpace\" role=\"button\" href=\"user.php\">Profile</a>"; //Show link to profile
							}
							else if(isset($_SESSION['activelogin']) && $_SESSION['activelogin'] == true) //User logged in is regular user
							{
								echo "<a class=\"btn btn-dark text-light btnSpace\" role=\"button\" href=\"user.php\">Profile</a>"; //Show link to profile
							}
							else //User not logged in
							{
								echo "<a class=\"btn btn-dark text-light btnSpace\" role=\"button\" href=\"/access.php?page=login.php\">Login</a></div>"; //Show link to login
							}
						?>
			</div>
		</div>

		<div class="container" id="searchSection" style="padding-top: 40px;width: 400;">
			<!--Search field-->
			<form method="GET" action="/">
				<div class="input-group" style="width: 100%;">
					<!--Input--><div class="input-group-prepend"><input class="border rounded form-control" type="text" style="width: 300px;background-color: rgb(247,249,252);" placeholder="Search by item/ seller name" name="search"></div>
					<!--Search Button--><div class="input-group-append"><button class="btn btn-success" type="submit" style="width: 70px;">Search</button></div>
				</div>
			</form>
			<?php
				if(isset($_GET["search"])) //Search field has entry
				{
					$name = $_GET["search"]; //Get entry
					echo "<p style=\"padding-top: 10px;\">Searching for: $name</p>"; //Show the users search input string. NOTE: Reflective XSS possible
				}
			 ?>
		</div>
		<div class="container" id="itemList" style="padding-top: 20px;width: 100%;">
			<h3 style="padding-bottom: 10px;">Items for sale</h3>
				<div class="row" style="width: 100%;">
				<div class="col">
					<div class="table-responsive" style="width: 100%;">
						<table class="table">
							<thead>
							    <tr>
							        <th>Details</th>
							        <th style="width: 175px;">Seller</th>
							        <th></th>
							    </tr>
							</thead>
							<tbody>
								<?php
									if(isset($_GET["search"])) //Search field has entry
									{
										$name = $_GET["search"]; //Get entry
										//Perform query to retrieve item names or seller names matching entry string.
										$sql = "SELECT items.itemname, FORMAT(items.price, 2), users.name, items.iid FROM items INNER JOIN users ON items.sellerid = users.uid WHERE items.sold = FALSE AND items.itemname LIKE '%$name%' OR users.name LIKE '%$name%'";
									}
									else //No entry
									{
										//Get all items
										$sql = "SELECT items.itemname, FORMAT(items.price, 2), users.name, items.iid FROM items INNER JOIN users ON items.sellerid = users.uid WHERE items.sold = FALSE";
									}

									$result = mysqli_query($db, $sql); //Execute query

									while($row = mysqli_fetch_array($result)) //For each row, fetch it in array format
									{
										echo "<tr>"; //For each table row
										echo "<td>";
										echo "<p>$row[0]</p><strong>$$row[1]</strong></td>"; //Show item name and price in first column
										echo "<td>";
										echo "<p>$row[2]</p>"; //Show seller name
										echo "</td>";
										//Show button to go to item information page.
										echo "<td style=\"margin: 12px;padding: 0px;width: 100px;padding-top: 25px;padding-left: 25px;\"><a href=\"/item.php?item=$row[3]\"<button class=\"btn btn-info text-light\" type=\"button\">Info</button></td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
