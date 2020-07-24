<?php 

class Lecturer
{
    public function printHomepageTable($db) 
    {
        $result = $db->query("SELECT * FROM attendance ORDER BY sid ASC");

        echo "<table>";
        echo "<tr>";
        echo "<th> Student ID </th>";
        echo "<th> Module 1 </th>";
        echo "<th> Module 2 </th>";
        echo "<th> Module 3 </th>";
        
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            echo "<tr>"; 

            // print SID and attendance for the first module
            echo "<td> {$row['sid']} </td>"; 
            echo "<td>" . round((array_sum($row) - $row['sid']) / 6 * 100) . "%</td>"; 

            // print attendance for the second and third modules
            for($i = 0; $i < 2; $i++)
            {
                $row = $result->fetch(PDO::FETCH_ASSOC); 
                echo "<td>" . round((array_sum($row) - $row['sid']) / 6 * 100) . "%</td>";
            }
            echo "</tr>"; 
        }
        echo "</table>"; 
    }    

    public function printModuleTable($db, $module_id) 
    {
        // query database to get attendance values 
        $result = $db->query("SELECT * FROM attendance WHERE MOD_ID = '$module_id'");
        $total = 0;

        // loop through the rows of the query result  
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            // print the markup for the table row
            echo "<tr>";
            echo "<td>{$row['sid']}</td>";

            // loop through the attendance columns  
            for($i = 1; $i <= 6; $i++)
            {
               // if student attended, print a tick else print a cross 
                echo "<td>" . ($row['WEEK_' . $i] == 1 ? "&#10003" : "&#10007") . "</td>"; 
            }

            // add each row total to the overall total value 
            $total += array_count_values($row)[1];
            echo "</tr>";
        }

        // close the table element 
        echo "</table>";
        return $total;  
    }

    public function moduleTotal($db, $module_id)
    {
        $result = $db->query("SELECT * FROM attendance WHERE MOD_ID = '$module_id'");
        $total = 0;
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            // subtract sid to stop it inflating attendance value 
            $total += array_sum($row) - $row['sid'];
        }
        return $total; 
    }

    public function printSIDsInForm($db)
    {
        $result = $db->query("SELECT DISTINCT sid FROM attendance");
        while($col = $result->fetchColumn())
        {
            echo "<option value='$col'>$col</option>";
        }
    }
}