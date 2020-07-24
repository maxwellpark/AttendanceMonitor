<?php
require_once "../../private/Manager.php";
require_once "../../private/Database.php";
require_once "../../private/Routing.php";
session_start();

$routing = new Routing();
$routing->redirectUnauthorisedUser($_SESSION['privilege'], "manager");

$database = new Database();
$db = $database::getDatabase();

$manager = new Manager();
$room_1_capacity = $manager->getRoomCapacity(1);
$room_2_capacity = $manager->getRoomCapacity(2);
$room_3_capacity = $manager->getRoomCapacity(3);

$query = "SELECT * FROM attendance";
$result = $db->query($query); 

$data = $result->fetchAll(PDO::FETCH_ASSOC);

// keys are pre-declared so they can be dynamically iterated through
$room_usage_array = array 
(
	"room_1" => array 
	(
		"WEEK_1" => 0,
		"WEEK_2" => 0,
		"WEEK_3" => 0, 
		"WEEK_4" => 0,
		"WEEK_5" => 0,
		"WEEK_6" => 0
	),
	"room_2" => array 
	(
		"WEEK_1" => 0,
		"WEEK_2" => 0,
		"WEEK_3" => 0, 
		"WEEK_4" => 0,
		"WEEK_5" => 0,
		"WEEK_6" => 0
	),
	"room_3" => array
	(
		"WEEK_1" => 0,
		"WEEK_2" => 0,
		"WEEK_3" => 0, 
		"WEEK_4" => 0,
		"WEEK_5" => 0,
		"WEEK_6" => 0
		)
	);

error_reporting(E_ALL ^ E_NOTICE);  
ini_set("precision", 2);
	
// populate room attendance array based on attendance values 
for($i = 0; $i < 36; $i++)
{
	// counter starts at 1 so it matches the week numbers from the database columns  
	for($j = 1; $j < 9; $j++)
	{
		if($data[$i]["MOD_ID"] == "MOD1" && $data[$i]["WEEK_" . $j] == 1)
		{
				$room_usage_array["room_1"]["WEEK_" . $j] += 1;  
		}
		elseif($data[$i]["MOD_ID"] == "MOD2" && $data[$i]["WEEK_" . $j] == 1)
		{
			$room_usage_array["room_2"]["WEEK_" . $j] += 1;
		}
		elseif($data[$i]["MOD_ID"] == "MOD3" && $data[$i]["WEEK_" . $j] == 1) 
		{
			$room_usage_array["room_3"]["WEEK_" . $j] += 1; 
		}
	}
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>All Rooms</title>
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
    <a id = "empty" href="homepage_manager.php" style="height: 75px; background-color: rgb(21, 28, 48); cursor:default">
    <a href="/AttendanceMonitor/private/logout.php"><img src="../images/shut.png" style="height: 38px; width: 38px; padding-bottom: 7px"><br>Log Out</a>
</div>
    
<script src="../js/dropdown.js"></script>

<div class= "top">
	<h1 style="height: 20px; font-size: large"> Room Information</h1>
</div>
	
<button type="button" class="collapsible">Room 123 Module 1</button>
    <div class="content">
      <p style="font-size: larger;">Room capacity: <?php echo $manager->getRoomCapacity(1); ?></p>
      <p style="font-size: larger;">Number of students in Module 1: 12</p>
      <table class="table" style="width:100%">
		<caption style="padding-bottom: 10px; text-align: left; font-size: larger; text-decoration: underline;">Usage by week:</caption>
		<tr>
			<td></td>
			<th>Week 1</th>
			<th>Week 2</th>
			<th>Week 3</th>
			<th>Week 4</th>
			<th>Week 5</th>
			<th>Week 6</th>
        </tr>
        <tr>
			<?php 
				$manager->printRoomTable($room_usage_array, $room_1_capacity);
			?> 
		</tr>
      </table>
	  <p style="font-size: larger;">Average usage: 
	  <?php echo (array_sum($room_usage_array["room_1"]) / ($room_1_capacity * 6)) * 100 . "%"; ?></p>
    </div>


<button type="button" class="collapsible">Room 456 Module 2</button>
    <div class="content">
      <p style="font-size: larger;">Room capacity: <?php echo $manager->getRoomCapacity(2); ?></p>
      <p style="font-size: larger;">Number of students in Module 2: 12</p>
      <table class="table" style="width:100%">
		<caption style="padding-bottom: 10px; text-align: left; font-size: larger; text-decoration: underline;">Usage by week:</caption>
		<tr>
			<td></td>
			<th>Week 1</th>
			<th>Week 2</th>
			<th>Week 3</th>
			<th>Week 4</th>
			<th>Week 5</th>
			<th>Week 6</th>
        </tr>
		<?php
			$manager->printRoomTable($room_usage_array, $room_2_capacity);
		?>
      </table>
	  <p style="font-size: larger;">Average usage: 
	  <?php echo (array_sum($room_usage_array["room_2"]) / ($room_2_capacity * 6)) * 100 . "%"; ?></p>
    </div>


<button type="button" class="collapsible" style="padding-top: inherit;">Room 789 Module 3</button>
    <div class="content">
      <p style="font-size: larger;">Room capacity: <?php echo $manager->getRoomCapacity(3); ?></p>
      <p style="font-size: larger;">Number of students in Module 3: 12</p>
      <table class="table" style="width:100%">
		<caption style="padding-bottom: 10px; text-align: left; font-size: larger; text-decoration: underline;">Usage by week</caption>
		<tr>
			<td></td>
			<th>Week 1</th>
			<th>Week 2</th>
			<th>Week 3</th>
			<th>Week 4</th>
			<th>Week 5</th>
			<th>Week 6</th>
        </tr>
		<?php 
			$manager->printRoomTable($room_usage_array, $room_3_capacity);
		?>
      </table>
	  <p style="font-size: larger;">Average usage: 
	  <?php echo (array_sum($room_usage_array["room_3"]) / ($room_3_capacity * 6)) * 100 . "%"; ?></p>
    </div>

<script src="../js/rooms.js"></script>

</body>
</html>