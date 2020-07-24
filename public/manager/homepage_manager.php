<?php
require_once "../../private/Manager.php";
require_once "../../private/Database.php";
require_once "../../private/Routing.php";
session_start();
error_reporting(1);

$routing = new Routing();
$routing->redirectUnauthorisedUser($_SESSION['privilege'], "manager");

$database = new Database();
$db = $database::getDatabase();

$manager = new Manager();
$manager_name = $_SESSION["name"];

$result = $db->query("SELECT * FROM attendance ORDER BY MOD_ID ASC");

$module_1_attendance = array();
$module_2_attendance = array();
$module_3_attendance = array();

// pushes attendance data onto separate module arrays
while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
{
	if ($row["MOD_ID"] == "MOD1") 
	{
        array_push($module_1_attendance, round((array_sum($row) - $row["sid"]) / 6 * 100));
	} 
	elseif ($row["MOD_ID"] == "MOD2") 
	{
        array_push($module_2_attendance, round((array_sum($row) - $row["sid"]) / 6 * 100));
	} elseif ($row["MOD_ID"] == "MOD3") 
	{
        array_push($module_3_attendance, round((array_sum($row) - $row["sid"]) / 6 * 100));
	} 
	else 
	{
        continue;
    }
}

$query = "SELECT DISTINCT sid FROM attendance";
$result = $db->query($query);

$sids = array();

// fetches SIDs to be displayed in the table
while ($row = $result->fetchColumn()) {
    array_push($sids, $row);
}

$notifications = $db->query("SELECT * FROM notifications");

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

<!-- sidebar navigation -->
<div class="sidebar">
	<a class="active" href="homepage_manager.php">
		<img src ="../images/anglia1.png" style="height: 70px; width: 90px">
	</a>
  	<a href="homepage_manager.php">
		<img src="../images/home-2-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px"><br>Home
	</a>
	<button class="dropdown-btn" href="all modules">
	<img src="../images/book-stack-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px;"><br>All Modules</button>
	  <div class="dropdown-container">
		<a href="module1_manager.php">Module 1</a>
		<a href="module2_manager.php">Module 2</a>
		<a href="module3_manager.php">Module 3</a>
	  </div>
	<a href="rooms_manager.php">
	<img src="../images/table-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px"><br>All Rooms</a>

		<button class="dropdown-btn" onClick="document.getElementById('modal-container').style.display = 'block'">
			<img src="../images/edit-property-xxl.png" style="height: 40px; width: 40px; padding-bottom: 7px;"><br>
			Register Attendance
		</button>

	<a id = "empty" href="homepage_manager.php" style="height: 75px; background-color: rgb(21, 28, 48); cursor:default">
	<a href="/AttendanceMonitor/private/logout.php">
	<img src="../images/shut.png" style="height: 38px; width: 38px; padding-bottom: 7px"><br>Log Out</a>
</div>

<script src="../js/dropdown.js"></script>

<!-- header -->
<div class= "top">
	<h1 style="height: 20px; font-size: large">ARU Companion App</h1>
</div>

<div class="content1">

	<!-- greeting message -->
	<?php echo "<h1 style='color: black; font-size: 2rem;'>Welcome, $manager_name. </h1>"; ?>

	<br/>
	<br/>

	<!-- notifications table -->
	<?php
    echo "<h3 style='text-decoration: underline;'>You have " . $notifications->rowCount() . " request(s) to record attendance:</h3>";
    echo "<table><tr><th>Student ID</th><th>Week No.</th><th>Module No.</th><th>Information</th></tr>";
    while ($row = $notifications->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>{$row['sid']}</td><td>{$row['week']}</td><td>{$row['module']}</td><td>{$row['info']}</td></tr>";
    }
    echo "</table>";
    ?>

	<!-- poor attendance table -->
	<div id="naughty-list">
	<h3 style="margin-top: 50px; text-decoration: underline;">
	The following students have been flagged for poor attendance (under 50%):</h3>
	<table>
	<tr>
			<th>Student ID</th>
			<th>Student Name</th>
			<th>Percentage</th>
	</tr>
	<?php
        $manager->flagPoorAttendance($db);
    ?>
	</table>
</div>

	<br/> 
	<br/>

	<!-- module buttons -->
	<button class="button" onClick="window.location.href='module1_manager.php'">MOD1</button>
	<div class="divider"></div>
	<button class="button" onClick="window.location.href='module2_manager.php'">MOD2</button>
	<div class="divider"></div>
	<button class="button" onClick="window.location.href='module3_manager.php'">MOD3</button>
	</div>

	<!-- overall attendance table -->
	<h3 style="padding-left: 125px; padding-top: 20px; text-decoration: underline;">Total general attendance: </h3>
	<table class="table" style="width:90%; margin-left: 125px">
		<tr>
			<th>Student ID</th>
			<th>MOD1</th>
			<th>MOD2</th>
			<th>MOD3</th>
		</tr>
		<?php $manager->printOverallTable($module_1_attendance, $module_2_attendance, $module_3_attendance, $sids) ?>
	</table>

	<!-- modal for recording attendance  -->
	<div id="modal-container" class="w3-modal">
			<div class="w3-modal-content">
			<header class="w3-container w3-indigo">
				<h1>Register Attendance</h1>
			</header>
			<form id="register-form" action="../../private/record_attendance.php" method="post">
			<label>Student ID</label>
			<select class="dropdown-select" name="sid">
			<?php
            //ncap?
            for ($i = 0; $i < count($sids); $i++) {
                echo "<option value='$sids[$i]'>$sids[$i]</option>";
            }
            ?>
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
			<label>Module</label>
			<select class="dropdown-select" name="module">
				<option value="1">Module 1</option>
				<option value="2">Module 2</option>
				<option value="3">Module 3</option>
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

	<script src="../js/modal.js"></script>

</div>
</body>
</html>
