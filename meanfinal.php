<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mean calculator</title>
    <link rel="stylesheet" href="extra.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide">
</head>
<body>
    <br><br><br>
    <center>
        <p id="heading">eShadow Calculator</p>
        <div class="set">
        <form method="post">
            <label for="a1">Choose data type: </label>
            <select name="ch" id="a1">
                <option value="1">Raw data/ungrouped data</option>
                <option value="2">Discrete group data</option>
                <option value="3">Continous data</option>+
            </select> &nbsp;&nbsp;
            <label for="a2"> 
                <input type="number" name="size" min="2" id="a2" placeholder="no. of observations" required>
            </label><br><br>
            <input type="submit" value="Run" class="run"><br><br><br>
        </form>
        </div>
        <br><br><br>
    </center>
</body>
</html>
<!-- For Data Input -->
<?php
    if(isset($_POST["size"]))
    {
        $ch = $_POST["ch"];
        $size = $_POST["size"];
        if($ch == 1)    // raw data
        {
            echo "<center><form method='post'>";
            for($x = 1;$x <= $size;$x++)
            {
                echo "<label class='raw' for='raw$x'>Data-$x:
                    <input type='number' id='raw$x' name='rawdt$x' step='any' required>
                    </label><br>";
            }
            echo "<br><br><input type='submit' value='Data Entry' class='ok'><br><br></form></center>";
        }
        else if($ch == 2)   // discrete group data
        {
            echo "<center><form method='post'>
                  <table>
                         <tr>
                            <th>Observations</th>
                             <th>Frequency</th>
                         </tr>";
            for($x = 1;$x <= $size;$x++)
            {
                echo "<tr>
                          <td>
                             <input type='number' name='disOb$x' step='any' required>
                          </td>
                          <td>
                             <input type='number' name='disFq$x' step='any' required>
                         </td>
                     </tr>";
            }
            echo "</table><br><br><input type='submit' value='Data Entry' class='ok'><br><br>
            </form></center>";
        }
        else    // continuous group data
        {
            echo "<center>
                     <form method='post'>
                          <table>
                            <tr>
                               <th>Class</th>
                               <th>Frequency</th>
                            </tr>";
            for($x = 1;$x <= $size;$x++)
            {
                echo "     <tr>
                               <td>
                                   <table>
                                      <tr>
                                          <td><input type='number' name='contidtLP$x' step='any' style='width: 4.5em;' required></td>
                                          <td><b>-</b></td>
                                          <td><input type='number' name='contidtUP$x' step='any' style='width: 4.5em;' required></td>
                                      </tr>
                                   </table>
                              </td>
                              <td><input type='number' name='contifq$x' step='any' required></td>
                            </tr>";
            }
            echo "</table><br><br><input type='submit' value='Data Entry'class='ok'><br><br>
            </form></center>";
        }
    }
?>
<!-- Calculation & Display Output For Mean claculation-->
 <?php
    // for raw data
    if(isset($_POST["rawdt1"]))
    {
        $sum = 0;
        $count = 0;
        for($i = 1;$_POST["rawdt$i"] != "";$i++)
        {
            $sum += $_POST["rawdt$i"];
            $count++;
        }
        $_mean_ = ($sum/$count);
        echo "<center><div class='result'>OUTPUT:<br><br>mean value = ".$_mean_."<br>";
    }
    // for discrete group data
    if(isset($_POST["disOb1"]))
    {
        $total_fq = 0;
        $fq_multiply_observ = 0;
        $discrete_fqs = [0,]; // for storing all frequencies
        for($_ = 1;$_POST["disFq$_"] != "";$_++)
        {
            $discrete_fqs[$_] = $_POST["disFq$_"];
            $total_fq += $_POST["disFq$_"];
            $fq_multiply_observ += ($_POST["disFq$_"] * $_POST["disOb$_"]);
        }
        $_mean_ = $fq_multiply_observ/$total_fq;
        echo "<center><div class='result'>OUTPUT:<br><br> mean value = $_mean_ <br>";
    }
    //for continuous group data
    if(isset($_POST["contifq1"]))
    {
        $total_fq = 0;
        $countt = 0;
        $midpoints = [0,];    // to store all midpoints
        for($i = 1;$_POST["contifq$i"] != "";$i++)
        {
            // creating midpoint for each xi or observation for their interval
            $midpoints[$i] = ($_POST["contidtUP$i"] + $_POST["contidtLP$i"]) / 2;
            $total_fq += $_POST["contifq$i"];
            $countt += 1;
        } 
        // determining assume min (A)
        if($countt % 2 == 0) //if no. of midpoints is even
        {
            $index = $countt / 2;
            $A = $midpoints[$index];
        }
        else // if no. of midpoints is odd
        {
            $index = ($countt + 1) / 2;
            $A = $midpoints[$index];
        }
        $sum_0f_x_A = 0;
        $sum_of_f_d = 0;    // let x - A = d
        for($y = 1;$_POST["contifq$y"] != "";$y++)
        {
            $sum_0f_x_A = ($midpoints[$y] - $A);
            $sum_of_f_d += ($_POST["contifq$y"] * $sum_0f_x_A);
        }
        $_mean_ = $A + ($sum_of_f_d / $total_fq);
        echo "<center><div class='result'>OUTPUT:<br><br> mean value = $_mean_ <br>";
    }

?>
<!-- Calculation & Display Output For Median claculation-->
<?php
    // for raw data
    if(isset($_POST["rawdt1"]))
    {
        $raw_data = []; //empty array
        $arr_size = 1;
        for($j = 0;$_POST["rawdt$arr_size"] != "";$j++)
        {
            $raw_data[$j] = $_POST["rawdt$arr_size"];
            $arr_size++;
        }
        // bubble sort start
       for($k = 0;$k < $arr_size -1;$k++)   // n-1 pass
       {
            $is_sorted = true;
            for($r = 0;$r < $arr_size - 1 - $k;$r++)
            {
                if($raw_data[$r] > $raw_data[$r + 1])
                {
                    $temp = $raw_data[$r];
                    $raw_data[$r] = $raw_data[$r + 1];
                    $raw_data[$r + 1] = $temp;
                    $is_sorted = false;
                }
            }
            if($is_sorted)
            {
                break;
            }
       }
       /*----------this part is for debugging ------*/
    //    echo "AFTER SORTING:<br> ";
    //    for($j = 0;$j < $arr_size;$j++)
    //    {
    //       echo $raw_data[$j]."<br>";
    //    }

       $size = $arr_size - 1;
       if($size % 2 !== 0)      // if size of data is odd
       {
            $median = $raw_data[($size + 1)/2]; 
            echo "median value = $median <br>";
       }
       else      // if size of data is even then
       {
            $medx_value = $size / 2;
            $median1 = $raw_data[$medx_value];
            $medain2 = $raw_data[$medx_value + 1];
            $_median_ = ($median1 + $medain2) / 2;
            echo "median value = $_median_ <br>";
       }
    }
    // for discrete group data
    if(isset($_POST["disOb1"]))
    {
        $cumu_fq = [0];      // cumulative frequency
        $big_n = 0;             // total frequency
        for($i = 1;$_POST["disFq$i"] != "";$i++)
        {
            $big_n +=  $_POST["disFq$i"];
            $cumu_fq[$i] = $big_n;
        }
        $finding =  ($big_n + 1)/2;    // calculating (N + 1)/2 th
        $is_finding_present = -99;
        for($j = 1;$_POST["disFq$j"] != "";$j++)
        {
            if ($cumu_fq[$j] >= $finding)
            {
                $is_finding_present = $j;   // if found then we stores it's index
                $_median_ = $_POST["disOb$is_finding_present"];
                break;
            }
        }
    
        echo "median value = ".$_median_."<br>";
        
    }
    //for continuous group data
    if(isset($_POST["contifq1"]))
    {
        $total_fre = 0;
        $u_class_boundary = [];
        $cumulative_fq = [];
        // assuiming the difference in boundary is same for all data
        $subs_part = ($_POST["contidtLP2"] - $_POST["contidtUP1"])/2;
        for($i = 1;$_POST["contifq$i"] != "";$i++)
        {
            $total_fre += $_POST["contifq$i"];
            $u_class_boundary[$i] = ($_POST["contidtUP$i"] + $subs_part);
            $cumulative_fq[$i] = $total_fre;
        }
        $n_by_2 = $total_fre / 2;
        for($j = 1;$_POST["contifq$j"] != "";$j++)
        {
            if($cumulative_fq[$j] >= $n_by_2)
            {
                $index = $j;
                $fm = $_POST["contifq$j"];  //frequency of median class
                $previous_cumu_freq = $cumulative_fq[$index -  1];
                $lower_bound = ($_POST["contidtLP$index"] - $subs_part);
                $width_of_class = $u_class_boundary[$index] - $lower_bound;
                break;
            }
        }
        $part1 = ($total_fre/2) - $previous_cumu_freq;
        $part2 = $part1 * $width_of_class;
        $_median_ = $lower_bound + ($part2 / $fm);

        echo "median value = $_median_ <br>";
    }
?>
<!-- Claculation for MODE calculation -->
<?php
    /*--- For raw data ---*/
    if(isset($_POST["rawdt1"]))
    {
        $repeat = [];
        
        for($i = 1;$i <= $count;$i++)
        {   
            $temp = $raw_data[$i];
            $counting = 0;
            for($j =1;$j <= $count;$j++)
            {
                if($raw_data[$j] == $temp)
                {
                    $counting += 1;
                }
            }
            $repeat[$i] = $counting;
            // echo "<br>$raw_data[$i] = $repeat[$i] times<br>";
        }
        echo "mode value = ";
        $max = max($repeat);
        
        for($i = 1;$i <= $count;)
        {
            if($repeat[$i] == $max)
            {
                echo "$raw_data[$i]<pre style='display: inline'>  </pre>";
                $i = $i + $max;
            }
            else
            {
                $i++;
            }
        }
        echo "<br><div></center>";
    }
    // for discrete group data
    if(isset($_POST["disOb1"]))
    {
        $max = max($discrete_fqs);
        $indexx = -1;
        echo "mode value = ";
        for($i = 1;$_POST["disFq$i"] != "";$i++)
        {
            if($discrete_fqs[$i] == $max)
            {
                echo $_POST["disOb$i"]."<pre style='display: inline'>  </pre>";
            }
        }
        echo "<br><div></center>";
    }

    // for Continuous frequency data
        
?>

