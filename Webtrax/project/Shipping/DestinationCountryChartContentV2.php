<?php
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleShippingChart.php");
/*
if(!session_is_registered("UIDWebTrax"))
{
  ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $InputYear = htmlspecialchars(trim($_POST['InputYear']), ENT_QUOTES, "UTF-8");
    // echo $InputYear;
    $arrTopCountry = array();
    $arrTopThree = array();
    $Data = GET_DATA_FREIGHT_SUBJECT_GROUP($InputYear,$linkMACHWebTrax);
    while($res=sqlsrv_fetch_array($Data))
    {
        $ValCountry = trim($res['Subject']);
        $ValFreight = trim($res['Freight']);
        $ValQty = trim($res['Qty']);
        array_push($arrTopCountry,$ValCountry);
        $TempArr = array("Country" => $ValCountry, "TotalFreight" => $ValFreight, "Qty" => $ValQty);
        array_push($arrTopThree,$TempArr);
    }
    $CountryException = $arrTopCountry[0]."*".$arrTopCountry[1]."*".$arrTopCountry[2]."*".$arrTopCountry[3]."*".$arrTopCountry[4];
    
    $Data2 = GET_DATA_FREIGHT_SUBJECT_OTHERS($InputYear,$CountryException,$linkMACHWebTrax);
    while($res2=sqlsrv_fetch_array($Data2))
    {
        $ValCountry = trim($res2['Subject']);
        $ValFreight = trim($res2['Freight']);
        $ValQty = trim($res2['Qty']);
        $TempArr = array("Country" => $ValCountry, "TotalFreight" => $ValFreight,"Qty" => $ValQty);
        array_push($arrTopThree,$TempArr);
    }
?>
    <script type="text/javascript">
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);
		function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['Country', 'Freight($)']
            <?php
            foreach($arrTopThree as $main)
            {
                $Country = trim($main['Country']);
                $TotalFreight = trim($main['TotalFreight']);
            ?>
			,['<?php echo $Country; ?>',<?php echo $TotalFreight; ?>]
            <?php
            }
            ?>
		]);
		var options = {
			is3D: true,            
			chartArea: {top:0,height:"100%",width:"100%",bottom:0},
			isStacked:true
					,focusTarget: 'category'
		};
		var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
		chart.draw(data, options);
		}
	</script>
<div class="col-md-6">
<h4>Top 5 Shipment Destination in <strong><?php echo $InputYear; ?></strong></h4>
    <div class="table-responsive">
        <table id="TableSumDestination" class="table table-responsive table-bordered table-hover">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">Country</th>
                    <th class="text-center">Freight ($)</th>
                    <th class="text-center">Qty Shipment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($arrTopThree as $main2)
                {
                    $Country = trim($main2['Country']);
                    $TotalFreight = trim($main2['TotalFreight']);
                    $TotalQty = trim($main2['Qty']);
                    $TotalFreight = number_format((float)$TotalFreight, 2, '.', ',');
                    $TotalQty = number_format((float)$TotalQty, 2, '.', ',');
                    $ValEnc = $Country."+".$InputYear."+".$CountryException;
                    $Enc = base64_encode($ValEnc);
                ?>
                <tr data-id="<?php echo $Enc; ?>" class="PointerList">
                    <td class="text-left"><?php echo $Country; ?></td>
                    <td class="text-right"><?php echo $TotalFreight; ?></td>
                    <td class="text-right"><?php echo $TotalQty; ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-6">
    <div id="piechart_3d" style="height: 300px;"></div>
</div>

<?php
}

?>