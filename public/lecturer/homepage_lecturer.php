<?php
require_once "../../private/Lecturer.php"; 
require_once "../../private/Database.php";
require_once "../../private/Routing.php";
session_start();

$routing = new Routing();
$routing->redirectUnauthorisedUser($_SESSION['privilege'], "lecturer"); 

$database = new Database();
$db = $database::getDatabase(); 

$lecturer = new Lecturer();
$name = $_SESSION["name"];

?>
<!doctype html>
<html>

<head>
<meta charset="utf-8">
<title>Homepage_Lecturer</title>
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

<?php
// feedback message 
if(array_key_exists('message', $_GET)) 
    {
        echo "<h5 class='record-attendance-response'> {$_GET['message']} </h5>";
    } 
?>
<div class="sidebar">
	<a class="active" href=""><img src ="../images/anglia1.png" style="height: 70px; width: 90px"></a>
  	<a href="homepage_lecturer.php"><img src="../images/home-2-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px"><br>Home</a>
	<button class="dropdown-btn" href="all modules"><img src="../images/book-stack-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px;"><br>All Modules</button>
	  <div class="dropdown-container">
		<a href="module1_lecturer.php">Module 1</a>
		<a href="module2_lecturer.php">Module 2</a>
		<a href="module3_lecturer.php">Module 3</a>
	  </div>

	  	<button class="dropdown-btn" onClick="document.getElementById('modal-container').style.display = 'block'">
			<img src="../images/edit-property-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px;"><br>
			Register Attendance
		</button>

	<a id = "empty" href="homepage_lecturer.php" style="height: 75px; background-color: rgb(21, 28, 48); cursor:default">
	<a href="/AttendanceMonitor/private/logout.php"><img src="../images/shut.png" style="height: 38px; width: 38px; padding-bottom: 7px"><br>Log Out</a>
</div>

<script src="../js/dropdown.js"></script>

<div class= "top">
	<h1 style="height: 20px; font-size: large">ARU Companion App</h1>
</div>
	
<div class="content1">
	<?php echo "<h1 style='color: black; font-size: 2rem;'>Welcome, $name. </h1>"; ?>

	<caption style="padding-bottom: 10px; text-align: left; font-size: larger; text-decoration: underline;">Attendance by module: </caption>

	<!-- module buttons -->
	<div style="margin-top: 20px">
		<button class="button" onClick="window.location.href='module1_lecturer.php'">MOD1</button>
		<button class="button" onClick="window.location.href='module2_lecturer.php'">MOD2</button>
		<button class="button" onClick="window.location.href='module3_lecturer.php'">MOD3</button>
	</div>

	<!-- overall attendance table (across all modules) -->
	<p style="font-size: larger; padding-top: 100px; text-decoration: underline;">Total general attendance: </p>
	<?php 
	$lecturer->printHomepageTable($db);
	?>
	<!-- Modal  -->
	<div id="modal-container" class="w3-modal">
			<div class="w3-modal-content">
			<header class="w3-container w3-indigo">
				<h1>Register Attendance</h1>
			</header>	
			<form id="register-form" action="../../private/record_attendance.php" method="post">
			<label>Student ID</label>
			<select class="dropdown-select" name="sid">
			<?php
			$lecturer->printSIDsInForm($db); 
			?>
			</select>
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
			<label>Attended?</label>
			<select class="dropdown-select" name="attended">
				<option value="Attended">Yes</option>
				<option value="Unattended">No</option>
			</select>
			<input type="submit">
		</form>
		</div>
	</div> 
</div>

<script src="../js/modal.js"></script>

</body>
</html>
