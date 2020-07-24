<?php
require_once "../../private/Student.php";
require_once "../../private/database.php";
require_once "../../private/Routing.php";
session_start();

$routing = new Routing();
$routing->redirectUnauthorisedUser($_SESSION["privilege"], "student"); 

$database = new Database();
$db = $database::getDatabase(); 

$student = new Student(); 

$query = "SELECT * FROM attendance WHERE sid = {$_SESSION['sid']}";
$result = $db->query($query); 

$overallAttendance = $student->calculateOverallAttendance($result, $_SESSION["sid"]);
$percentage = $student->calculateOverallPercentage($overallAttendance);  
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Homepage</title>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<style>
#modal-container {
	width: 100vw;
	height: 100vh;
	margin: 750px;
}
.modal-content {
	width: 50vw;
	height: 50vh;
}
</style>
</head>
<body>
<div class= "top">
	<h1 style="height: 20px; font-size: large">ARU Companion App</h1>
</div>
	<div class="sidebar">
		<a class="active" href="homepage_student.php"><img src="../images/anglia1.png" style="height: 70px; width: 90px"></a>
		<a href=""><img src="../images/home-2-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px"><br>Home</a>
		<button class="dropdown-btn" href="all modules"><img src="../images/book-stack-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px;"><br>All Modules</button>
		<div class="dropdown-container">
			<a href="module1_student.php">Module 1</a>
			<a href="module2_student.php">Module 2</a>
			<a href="module3_student.php">Module 3</a>
		</div> 

		<!-- button for displaying modal window -->
		<button class="dropdown-btn" onClick="document.getElementById('modal-container').style.display = 'block'">
			<img src="../images/edit-property-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px;"><br>
			Register Attendance
		</button>




		<a id = "empty" href="homepage_student.php" style="height: 175px; background-color: rgb(21, 28, 48); cursor:default">
		<a href="/AttendanceMonitor/private/logout.php"><img src="../images/shut.png" style="height: 38px; width: 38px; padding-bottom: 7px"><br>Log Out</a>
	</div>

<div class="content1"> 

	<?php echo "<h1 style='color: black; font-size: 2rem;'>Welcome, {$_SESSION['name']} </h1>"; ?>

	<?php echo "<p style='font-size: larger; padding-bottom: 100px;'>You have attended $overallAttendance out of 18 lectures this term ($percentage%).</p>"; ?> 
	
	<p style="font-size: larger; padding-bottom: 40px;">Student attendance by module: </p>
	
	<div id="button-container">
	<button class="button" onClick="window.location.href='module1_student.php'">MOD1<br></button>
	<div class="divider"></div>
	<button class="button" onClick="window.location.href='module2_student.php'">MOD2<br></button>
	<div class="divider"></div>
	<button class="button" onClick="window.location.href='module3_student.php'">MOD3<br></button>
	</div> 
	<!-- modal window for recording attendance -->
	<div id="modal-container" class="w3-modal">
			<div class="w3-modal-content">
			<header class="w3-container w3-indigo">
				<h1>Check In</h1>
			</header>	
			<section class="w3-container">
			<form id="register-form" action="../../private/notify_manager.php" method="post">
			<label>Module</label>
			<select class="dropdown-select" name="module">
				<option value="1">Module 1</option>
				<option value="2">Module 2</option>
				<option value="3">Module 3</option>
			</select>
			<label>Week</label>
			<select class="dropdown-select" name="week">
				<option value="1">Week 1</option>
				<option value="2">Week 2</option>
				<option value="3">Week 3</option>
				<option value="4">Week 4</option>
				<option value="5">Week 5</option>
				<option value="6">Week 6</option>
			</select>	
			<label>Message</label>
			<input class="message-box" type="text" name="info">
			<input type="submit">
		</form>
		</section>
		</div>
	</div> 
</div>
<script src="../js/dropdown.js"></script>
<script src="../js/modal.js"></script>
</body>
</html>
	