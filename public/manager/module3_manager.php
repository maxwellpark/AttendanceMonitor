<?php
require_once "../../private/Manager.php"; 
require_once "../../private/Routing.php"; 
require_once "../../private/Database.php";
session_start();

$routing = new Routing();
$routing->redirectUnauthorisedUser($_SESSION['privilege'], "manager");

$database = new Database();
$db = $database::getDatabase();

$manager = new Manager(); 


?>
<!doctype html>
<html>

<head>
<meta charset="utf-8">
<title>Modules</title>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
</head>

<body>
	
<div class="sidebar">
	<a class="active" href="homepage_manager.php"><img src ="../images/anglia1.png" style="height: 70px; width: 90px"></a>
  	<a href="homepage_manager.php"><img src="../images/home-2-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px"><br>Home</a>
	<button class="dropdown-btn" href="all modules"><img src="../images/book-stack-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px;"><br>All Modules</button>
	  <div class="dropdown-container">
		<a href="module1_manager.php">Module 1</a>
		<a href="module2_manager.php">Module 2</a>
		<a href="module3_manager.php">Module 3</a>
	  </div>
	<a href="rooms_manager.php"><img src="../images/table-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px"><br>All Rooms</a>
  	
    <a id = "empty" href="homepage_manager.php" style="height: 192px; background-color: rgb(21, 28, 48); cursor:default">
	<a href="/AttendanceMonitor/private/logout.php"><img src="../images/shut.png" style="height: 38px; width: 38px; padding-bottom: 7px"><br>Log Out</a>
</div>

<script src="../js/dropdown.js"></script>

<div class= "top">
	<h1 style="height: 20px; font-size: large;"> Module</h1>
</div>
	
<div class="content1">
	<p id="total-attendance-for-module" style="font-size: larger;"> Total Attendance for Module 3: </p>
	<table class="table" style="width:100%">
		<caption style="padding-bottom: 10px; text-align: left; font-size: larger;">Weekly Attendance</caption>
		<tr>
			<th>Student ID</th>
			<th>Week 1</th>
			<th>Week 2</th>
			<th>Week 3</th>
			<th>Week 4</th>
			<th>Week 5</th>
			<th>Week 6</th>
			<th>Total</th>
		</tr>
		<?php 
		$manager->printModuleTable($db, "MOD3");
		?> 
	</table>
	

</div>
</body>
</html>