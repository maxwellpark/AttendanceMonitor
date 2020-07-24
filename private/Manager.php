<?php

class Manager
{
    // room capacities
    private $room1 = 12;
    private $room2 = 15;
    private $room3 = 18;

    public function getRoomCapacity($num)
    {
        return $this->{'room' . $num};
    }

    public function printRoomTable($usage, $capacity)
    {
        echo "<tr>";
        echo "<th>Used seats</th>";
        for ($i = 1; $i < 7; $i++) 
        {
            echo "<td>{$usage['room_3']['WEEK_' . $i]}</td>";
        }
        echo "</tr>";
        echo "<tr>";
        echo "<th>Empty seats</th>";
        for ($i = 1; $i < 7; $i++) 
        {
            echo "<td>";
            echo $capacity - $usage['room_3']['WEEK_' . $i];
            echo "</td>";
        }
        echo "</tr>";
    }

    public function displayNotifications($result)
    {
        echo "<h3>You have the following notifications:</h3>";

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
        {
            echo "<p>{$result['sid']} is requesting Week {$result['week']}, Module {$result['module']} to be recorded.</p><br/>";
            
            if (!empty($row['info'])) 
            {
                echo "<p>Message: {$row['info']}</p>";
            }
        }
    }

    public function printModuleTable($db, $module_id)
    {
        $result = $db->query("SELECT * FROM attendance WHERE MOD_ID = '$module_id'");

        $totals = array("WEEK_1" => 0, "WEEK_2" => 0, "WEEK_3" => 0, "WEEK_4" => 0, "WEEK_5" => 0, "WEEK_6" => 0);

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
        {
            echo "<tr>";
            echo "<td>{$row['sid']}</td>";

            // print weekly attendance and add to total
            for ($i = 1; $i < count($row) - 1; $i++) 
            {
                if ($row['WEEK_' . $i] == 1) 
                {
                    echo "<td>&#10003</td>";
                    $totals['WEEK_' . $i]++;
                } 
                else 
                {
                    echo "<td>&#10007</td>";
                }
            }
            // print student total
            echo "<td>"  . round((array_sum($row) - $row['sid']) / 6 * 100) . "%</td>";
            echo "</tr>";
        }

        echo "<th>Total" . "</th>";

        // print week total
        for ($i = 1; $i < count($totals) + 1; $i++) 
        {
            echo "<td>" . round($totals['WEEK_' . $i] / 12 * 100) . "%</td>";
        }
        echo "</table>";

        $group_total = round(array_sum($totals) / 72 * 100);

        // this script adds the total to an element higher up the document
        echo "<script type='text/javascript'>" . "document.getElementById('total-attendance-for-module').innerText += `$group_total%`;" . "</script>";
    }

    public function printOverallTable($module_1_attendance, $module_2_attendance, $module_3_attendance, $sids)
    {
        for ($i = 0; $i < count($sids); $i++) {
            echo "<tr>";
            echo "<td>" . $sids[$i];
            echo "<td>" . $module_1_attendance[$i] . "%</td>";
            echo "<td>" . $module_2_attendance[$i] . "%</td>";
            echo "<td>" . $module_3_attendance[$i] . "%</td>";
            echo "</tr>";
        }
    }

    public function flagPoorAttendance($db)
    {
        $result = $db->query("SELECT * FROM attendance LEFT JOIN students ON attendance.sid = students.sid");
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        
        $naughty = array();
        
        // each iteration increments the counter by 3 (to cover 3 modules)
        for ($i = 0; $i < count($all) - 2; $i += 3) 
        {
            $sid = $all[$i]["sid"];
            $name = $all[$i]["name"];
            $overall = array_sum($all[$i]) + array_sum($all[$i+1])	+ array_sum($all[$i+2]) - $sid * 3;
        
            // if under 9 out of 18 lectures missed (< 50%)
            if ($overall < 9) 
            {
                // converts to percentage
                $percentage = round(($overall / 18) * 100);
                array_push($naughty, array($sid, $name, $percentage));
            }
        }
        
        for ($i = 0; $i < count($naughty); $i++) 
        {
            echo "<tr>";
            for ($j = 0; $j < 3; $j++) 
            {
                // percentage values are saved as the last element in the array (index 2)
                // print percent sign if loop is at the last index, else just print the value
                echo ($j === 2) ? "<td>" . $naughty[$i][$j] . "%</td>" :
                "<td>" . $naughty[$i][$j] . "</td>";
            }
            echo "</tr>";
        }
    }

    // prints SIDs to a dropdown list (generalisable to more students)
    public function printSIDsInForm($db)
    {
        $result = $db->query("SELECT DISTINCT sid FROM attendance");
        while ($row = $result->fetchColumn()) 
        {
            echo "<option value='$row'>$row</option>";
        }
    }
}
