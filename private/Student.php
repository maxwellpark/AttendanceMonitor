<?php

class Student
{

    // these are separate functions to increase unit testability 
    public function calculateOverallAttendance($result, $sid)
    {
        $overallAttendance = 0; 
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            // subtract sid since we only want attendance values 
            $overallAttendance += array_sum($row) - $sid; 
        }
        return $overallAttendance; 
    }

    public function calculateOverallPercentage($overallAttendance)
    {
        return round($overallAttendance / 18 * 100); 
    }

    public function displayTable($result) 
    {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        echo "<tr>";

        // counter starts at 1 so that it can be concatenated with the $row[] key
        for($i = 1; $i < count($row) - 1; $i++)
        {
            echo "<td>" . ($row['WEEK_' . $i] == 1 ? "&#10003" : "&#10007") . "</td>"; 
        }
        echo "</tr>";
        echo "</table>";
    }
}