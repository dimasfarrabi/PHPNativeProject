<?php

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $TotalLabor = htmlspecialchars(trim($_POST['TotalLabor']), ENT_QUOTES, "UTF-8");
    $TotalMachine = htmlspecialchars(trim($_POST['TotalMachine']), ENT_QUOTES, "UTF-8");
    $TotalMaterial = htmlspecialchars(trim($_POST['TotalMaterial']), ENT_QUOTES, "UTF-8");
    $TotalOTS = htmlspecialchars(trim($_POST['TotalOTS']), ENT_QUOTES, "UTF-8");
    $Category = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    // echo "$TotalLabor >> $TotalMachine >> $TotalMaterial >> $TotalOTS";
    $Totalan = $TotalLabor + $TotalMachine + $TotalMaterial + $TotalOTS;
    $TotalLabor2 = number_format((float)$TotalLabor,2,'.',',');
    $TotalMachine2 = number_format((float)$TotalMachine,2,'.',',');
    $TotalMaterial2 = number_format((float)$TotalMaterial,2,'.',',');
    $TotalOTS2 = number_format((float)$TotalOTS,2,'.',',');
    $Totalan = number_format((float)$Totalan,2,'.',',');
    if($Category == 'Quote')
    {
        $Title = "Cost Per Unit";
    }
    else
    {
        $Title = "Total Actual Cost";
    }
?>

<div class="col-md-6">
<div><h5><strong><?php echo $Title; ?></strong></h5></div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th class="theadCustom text-center"><strong>Items</strong></th>
                <th class="theadCustom text-center"><strong>Cost ($)</strong></th>
            </tr>
            <tr>
                <td>Labour Cost</td>
                <td class="text-right"><?php echo $TotalLabor2; ?></td>
            </tr>
            <tr>
                <td>Machine Cost</td>
                <td class="text-right"><?php echo $TotalMachine2; ?></td>
            </tr>
            <tr>
                <td>Material Cost</td>
                <td class="text-right"><?php echo $TotalMaterial2; ?></td>
            </tr>
            <tr>
                <td>OTS Cost</td>
                <td class="text-right"><?php echo $TotalOTS2; ?></td>
            </tr>
            <tr>
                <td class="text-center"><strong>TOTAL</strong></td>
                <td class="text-right"><strong><?php echo $Totalan; ?></strong></td>
            </tr>
        </table>
    </div>
</div>
<script type="text/javascript">
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);
		function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['Item', 'Cost per Unit',]
			,['Labor Cost ($)',<?php echo $TotalLabor; ?>]
            ,['Machine Cost ($)',<?php echo $TotalMachine; ?>]
            ,['Material Cost ($)',<?php echo $TotalMaterial; ?>]
            ,['OTS Cost ($)',<?php echo $TotalOTS; ?>]
		]);
		var options = {
			is3D: true,            
			chartArea: {top:0,height:"80%",width:"100%",bottom:0},
			isStacked:true
					,focusTarget: 'category'
		};
		var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
		chart.draw(data, options);
		}
	</script>
<div class="col-md-6" style="margin-top:20px;">
    <div id="piechart_3d"></div>
</div>
<?php
}
?>