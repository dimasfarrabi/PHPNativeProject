<?php

require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");

function getStartAndEndDate($week, $year) {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $Start = $dto->format('m/d/Y');
    $dto->modify('+6 days');
    $End = $dto->format('m/d/Y');
    return $Start."#".$End;
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $aFloat = base64_decode(htmlspecialchars(trim($_POST['aFloat']), ENT_QUOTES, "UTF-8"));
    // echo $aFloat;
    $arr = explode("*",$aFloat);
    $DeviceID = $arr[0];
    $ValDateAwal = $arr[1];
    $ValDateAkhir = $arr[2];
?>

<button id="BtnDownload" type="button" class="btn btn-sm btn-info btn-labeled block" style="float: right;">Download</button>
<div class="col-md-12"><h5>Detail Device ID : <?php echo $DeviceID; ?></h5></div>
<div class="col-md-12">
  <div class="table-responsive">
      <table class="table table-bordered table-hover" id="TableDetail">
          <thead class="theadCustom">
              <tr>
                  <th width = "20">No</th>
                  <th>Date</th>
                  <th>Machine Name</th>
                  <th>Start Time</th>
                  <th>End Time</th>
                  <th>Duration (Minute)</th>
                  <th>Device Battery (%)</th>
              </tr>
          </thead>
          <tbody>
          <?php
          $no=1;
              $Data = GET_SPINDLE_IOT($ValDateAwal,$ValDateAkhir,$DeviceID,$linkMACHWebTrax);
              while($Datares=sqlsrv_fetch_array($Data))
              {
                  $Date = trim($Datares['DateRecord']);
                  $ID = trim($Datares['DeviceID']);
                  $FullStart = trim($Datares['FullStart']);
                  $FullEnd = trim($Datares['FullEnd']);
                  $RunMinute = trim($Datares['RunMinute']);
                  $DeviceBatt = trim($Datares['DeviceBatt']);
                  $Machine = trim($Datares['Machine']);
                  
              ?>
              <tr>
                  <td class="text-center" width="30"><?php echo $no; ?></td>
                  <td class="text-center" width="30"><?php echo $Date; ?></td>
                  <td class="text-left" width="30"><?php echo $Machine; ?></td>
                  <td class="text-center" width="30"><?php echo $FullStart; ?></td>
                  <td class="text-center" width="30"><?php echo $FullEnd; ?></td>
                  <td class="text-right" width="30"><?php echo $RunMinute; ?></td>
                  <td class="text-right" width="30"><?php echo $DeviceBatt; ?></td>
              </tr>
              <?php
              $no++;
              }
          ?>
          </tbody>
      </table>
  </div>
</div>
<script>
    google.charts.load('current',{packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
    // Set Data
    var data = google.visualization.arrayToDataTable([
    ['Jam', 'Status']
    <?php
    $Data = GET_IOT_DATA_CHART($DeviceID,$ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
    while($res=sqlsrv_fetch_array($Data))
    {
        $ValJam = trim($res['Time']);
        $ValStatus = trim($res['MachineStatus']);
        ?>
        ,['<?php echo $ValJam; ?>',<?php echo $ValStatus; ?>]
        <?php
    }
    
    ?>
    ]);
    // Set Options
    var options = {
    title: 'Machine Status: <?php echo $Machine; ?>',
    hAxis: {
            title: data.getColumnLabel(0), textStyle: { fontSize: 12 }, slantedTextAngle: '75'
            },
    vAxis: {title: 'Status'},
    chartArea: {top:50,height:"80%",width:"90%",bottom:100},
    legend: 'none'
    };
    // Draw
    var chart = new google.visualization.LineChart(document.getElementById('myChart'));
    chart.draw(data, options);
    }
</script>
<div class="col-md-12">
  <div id="myChart" style="width:100%; height:500px;"></div>
</div>
<?php

}
?>