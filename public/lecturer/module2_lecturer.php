<?php
require_once "../../private/Lecturer.php"; 
require_once "../../private/Routing.php";
require_once "../../private/Database.php";
session_start();
// error_reporting(E_ALL ^ E_NOTICE);

$routing = new Routing();
$routing->redirectUnauthorisedUser($_SESSION['privilege'], "lecturer"); 

$database = new Database();
$db = $database::getDatabase(); 

$lecturer = new Lecturer(); 

$module_id = "MOD2";
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
	<a class="active" href=""><img src ="../images/anglia1.png" style="height: 70px; width: 90px"></a>
  	<a href="homepage_lecturer.php"><img src="../images/home-2-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px"><br>Home</a>
	<button class="dropdown-btn" href="all modules"><img src="../images/book-stack-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px;"><br>All Modules</button>
	  <div class="dropdown-container">
	  	<a href="module1_lecturer.php">Module 1</a>
		<a href="module2_lecturer.php">Module 2</a>
		<a href="module3_lecturer.php">Module 3</a>
	  </div>
	
    <a id = "empty" href="homepage_lecturer.php" style="height: 289px; background-color: rgb(21, 28, 48); cursor:default">
	<a href="/AttendanceMonitor/private/logout.php"><img src="../images/shut.png" style="height: 38px; width: 38px; padding-bottom: 7px"><br>Log Out</a>
</div>

<script src="../js/dropdown.js"></script>

<div class= "top">
	<h1 style="height: 20px; font-size: large;"> Module</h1>
</div>
	
<div class="content1">
	<p style="font-size: larger;"> Total Attendance for Module 2: <?php echo round($lecturer->moduleTotal($db, $module_id) / 72 * 100) . "%"; ?> </p>
	<table>
		<tr> 
			<th>Student ID</th>
			<th>Week 1</th>
			<th>Week 2</th>
			<th>Week 3</th>
			<th>Week 4</th>
			<th>Week 5</th>
			<th>Week 6</th>
		</tr>
	<?php
	// $query = "SELECT * FROM attendance WHERE MOD_ID = 'MOD2'";
	// $result = $db->query($query);
	$lecturer->printModuleTable($db, $module_id);;
	?>
</div>
</body>
</html>