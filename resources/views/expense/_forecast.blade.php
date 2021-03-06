<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/6/2018
 * Time: 12:26 PM
 */
?>

<!------------------------------------ Forecast Table ------------------------>
<br>

<table class="table table-responsive border border-dark" style="margin-top: 10px;" >
    <thead>
    <tr>
        <th colspan="3"><h1>Upcoming Sales Forecast</h1></th>
    </tr>
    <tr>

        <th>Average Daily Sales</th>
        <th>Sales Forecast Adjustment</th>
        <th>Projected Sales </th>

    </tr>
    </thead>
    <tbody>
    <tr>
        <!-- This displays the 7 day average of the past week -->
        <td rowspan="3">{{"$" . (round((int)number_format((float)($cogs->twenty_eight_day_avg /100), 2, '.', '') / 50 )) * 50}}</td>

        <?php
        if (isset($_GET['subject']))
        {
            $fore_percent = $_GET['subject'];
            $forecast->growth($fore_percent);
            $forecast->date();
            $forecast->forecastCalculation();

        }
        else
        {
            $forecast->forecastCalculation();
            $forecast->getPercentage();
            $fore_percent = $forecast->growth_rate;
        }
        ?>
        <td><form name="form" action="" method="get">
                <input type="number" name="subject" id="subject" value="{{$fore_percent}}">
                <input class="btn btn-outline-info btn-sm" type="submit" name="forecast_button"
                       value="SCALE"/>
            </form>
        </td>
        <td><?php
            if(isset($_GET['subject'])){
                $fore_percent = $_GET['subject'];
                $forecast->growth($fore_percent);
                $forecast->date();
                $forecast->forecastCalculation();
                echo "$" . (int)number_format((float)$forecast->seven_day, 0, '.', '');
            }
            else{
                $forecast->getPercentage();
                $forecast->forecastCalculation();
                $fore_percent = $forecast->growth_rate;
                echo "$" . (int)number_format((float)$forecast->seven_day, 0, '.', '');
            }?>
        </td>
    </tr>
    </tbody>
</table>
