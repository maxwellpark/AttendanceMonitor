<?php
require_once "../../private/Student.php";
require_once "../../private/Routing.php";
require_once "../../private/Database.php";
session_start(); 

$routing = new Routing();
$routing->redirectUnauthorisedUser($_SESSION['privilege'], "student"); 

$database = new Database();
$db = $database::getDatabase();

$student = new Student();

$query = "SELECT * FROM attendance WHERE sid = {$_SESSION['sid']} AND MOD_ID = 'MOD2'";
$result = $db->query($query); 

$overallAttendance = $student->calculateOverallAttendance($result, $_SESSION['sid']);
$percentage = round($overallAttendance / 6 * 100);

$query = "SELECT * FROM attendance WHERE sid = {$_SESSION['sid']} AND MOD_ID = 'MOD2'";
$result = $db->query($query); 

?>

<!doctype html>
<html>

<head>
<meta charset="utf-8">
<title>Module2</title>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
</head>

<body>
	
<div class="sidebar">
	<a class="active" href=""><img src ="../images/anglia1.png" style="height: 70px; width: 90px"></a>
  	<a href="homepage_student.php"><img src="../images/home-2-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px"><br>Home</a>
	<button class="dropdown-btn" href="all modules"><img src="../images/book-stack-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px;"><br>All Modules</button>
	  <div class="dropdown-container">
		<a href="module1_student.php">Module 1</a>
		<a href="module2_student.php">Module 2</a>
		<a href="module3_student.php">Module 3</a>
	  </div>

	<a id = "empty" href="empty" style="height: 175px; background-color: rgb(21, 28, 48); cursor:default">
	<a href="../../private/logout.php"><img src="../images/shut.png" style="height: 38px; width: 38px; padding-bottom: 7px;;"><br>Log Out</a>
</div>

<script src="../js/dropdown.js"></script>

<div class= "top">
	<h1 style="height: 20px; font-size: large;"> Module 2</h1>
</div>
	
<div class="content1">
	<?php echo "<p style='font-size: larger'> Total Attendance for Module 2: <b>$percentage%</b></p>";
	?>
	<table class="table1" style="width:100%">
		<caption style="padding-bottom: 10px; text-align: left; font-size: larger; text-decoration: underline;">Weekly Attendance for Module</caption>
		<tr>
			<th>Week 1</th>
			<th>Week 2</th>
			<th>Week 3</th>
			<th>Week 4</th>
			<th>Week 5</th>
			<th>Week 6</th>
		</tr>
		<tr>
			<?php 
			$student->displayTable($result);
			?>
		</tr>
	</table>
	

</div>
</body>
</html>