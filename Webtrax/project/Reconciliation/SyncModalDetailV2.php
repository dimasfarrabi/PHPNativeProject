<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleSyncReconV3.php"); 
date_default_timezone_set("Asia/Jakarta");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['Code']), ENT_QUOTES, "UTF-8"));
    $NewValCodeEnc = base64_encode($ValCodeDec);
    $ArrCodeDec = explode("*",$ValCodeDec);
    $Type = $ArrCodeDec[0];
    $Params = $ArrCodeDec[1];
    $Date1 = $ArrCodeDec[2];
    $Date2 = $ArrCodeDec[3];
    $Loc = $ArrCodeDec[4];

    if($Loc == 'PSL'){ $ValConn = $linkMACHWebTrax;} else { $ValConn = $PSMConn; }

    switch ($Type) {
        case 'Time Tracking':
            {
                $XData = DETAIL_XY_TIMETRACK($Date1,$Date2,$Params,$Loc,$ValConn);
                $ZData = DETAIL_Z_TIMETRACK($Date1,$Date2,$Params,$Loc,$linkMACHWebTrax);
                $ParamX = "Date";
                $ParamY = "Name";
                $ParamZ = "WO";
                $ParamA = "Activity";
                $ParamB = "Notes";
            }
        break;
        case 'Machine Tracking':
            {
                $XData = DETAIL_XY_MACHINETRACK($Date1,$Date2,$Params,$Loc,$ValConn);
                $ZData = DETAIL_Z_MACHINETRACK($Date1,$Date2,$Params,$Loc,$linkMACHWebTrax);
                $ParamX = "Date Created";
                $ParamY = "Part Number";
                $ParamZ = "WO Mapping ID";
                $ParamA = "Notes";
            }
        break;
        case 'Material Tracking':
            {
                $XData = DETAIL_XY_MATERIALTRACK($Date1,$Date2,$Params,$Loc,$ValConn);
                $ZData = DETAIL_Z_MATERIALTRACK($Date1,$Date2,$Params,$Loc,$linkMACHWebTrax);
                $ParamX = "Date Created";
                $ParamY = "Part Number";
                $ParamZ = "WO";
                $ParamA = "Notes";
            }
        break;
        case 'WO Mapping':
            {
                $XData = DETAIL_XY_WO($Date1,$Date2,$Params,$Loc,$ValConn);
                $ZData = DETAIL_Z_WO($Date1,$Date2,$Params,$Loc,$linkMACHWebTrax);
                $ParamX = "Date Created";
                $ParamY = "WO Child";
                $ParamZ = "Quote";
                $ParamA = "Notes";
            }
        break;
        case 'Barcode Status':
            {
                $XData = DETAIL_XY_BARCODE($Date1,$Date2,$Params,$Loc,$ValConn);
                $ZData = DETAIL_Z_BARCODE($Date1,$Date2,$Params,$Loc,$linkMACHWebTrax);
                $ParamX = "Date Created";
                $ParamY = "Code";
                $ParamZ = "WO";
                $ParamA = "Notes";
            }
        break;
        case 'Spindle Hour':
            {
                $XData = DETAIL_XY_SPINDLE($Date1,$Date2,$Params,$Loc,$ValConn);
                $ZData = DETAIL_Z_SPINDLE($Date1,$Date2,$Params,$Loc,$linkMACHWebTrax);
                $ParamX = "Date Created";
                $ParamY = "Operator";
                $ParamZ = "Shift";
                $ParamA = "Notes";
            }
        break;
        case 'Attendance':
            {
                $XData = DETAIL_XY_ATT($Date1,$Date2,$Params,$Loc,$ValConn);
                $ZData = DETAIL_Z_ATT($Date1,$Date2,$Params,$Loc,$linkMACHWebTrax);
                $ParamX = "Date Created";
                $ParamY = "NIK";
                $ParamZ = "Name";
                $ParamA = "Notes";
            }
        break;
        case 'Raw Material':
            {
                $XData = DETAIL_XY_RAW($Date1,$Date2,$Params,$Loc,$ValConn);
                $ZData = DETAIL_Z_RAW($Date1,$Date2,$Params,$Loc,$linkMACHWebTrax);
                $ParamX = "Date Created";
                $ParamY = "WO";
                $ParamZ = "AlmytaPartNo";
                $ParamA = "Notes";
            }
        break;
        case 'Inventory Out label':
            {
                $XData = DETAIL_XY_INV($Date1,$Date2,$Params,$Loc,$ValConn);
                $ZData = DETAIL_Z_INV($Date1,$Date2,$Params,$Loc,$linkMACHWebTrax);
                $ParamX = "Date Created";
                $ParamY = "PartNo";
                $ParamZ = "JobID";
                $ParamA = "Notes";
            }
        break;
    }
    $Arr1 = array();
    $Arr2 = array();
    while($RList = sqlsrv_fetch_array($XData))
    {
        $Idx = trim($RList['Idx']);
        $X = trim($RList['X']);
        $Y = trim($RList['Y']);
        $Z = trim($RList['Z']);
        $ArrayXY = array("Idx" => $Idx, "X" => $X, "Y" => $Y, "Z" => $Z,"A" => "Belum Terimport ke Webtrax");
        array_push($Arr1,$ArrayXY);
    }
    while($RList2 = sqlsrv_fetch_array($ZData))
    {
        $Idx = trim($RList2['Idx']);
        $X = trim($RList2['X']);
        $Y = trim($RList2['Y']);
        $Z = trim($RList2['Z']);
        $ArrayZ = array("Idx" => $Idx, "X" => $X, "Y" => $Y, "Z" => $Z, "A" => "Tidak Sesuai Dengan Tabel Asal");
        array_push($Arr2,$ArrayZ);
    }

    foreach($Arr1 as $ValArray1)
    {
        $aTmp1[] = $ValArray1['Idx'];
    }
    foreach($Arr2 as $ValArray2)
    {
        $aTmp2[] = $ValArray2['Idx'];
    }
    $new_array = array_diff($aTmp2,$aTmp1);  // KELEBIHAN di Z
    $new_array2 = array_diff($aTmp1,$aTmp2); // XY di Zv
    
    $Index = array();
?>
<style>
    .my-custom-scrollbar {
    height: 280px;
    overflow-y: scroll;
    padding: 0px 0px;
    }
    .table-wrapper-scroll-y {
    display: block;
    }
    .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }
</style>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom">Index</th>
                    <th class="text-center trowCustom"><?php echo $ParamX; ?></th>
                    <th class="text-center trowCustom"><?php echo $ParamY; ?></th>
                    <th class="text-center trowCustom"><?php echo $ParamZ; ?></th>
                    <th class="text-center trowCustom"><?php echo $ParamA; ?></th>
            </tr>
            <tbody>
            <?php
            foreach($new_array as $x=>$x_value)
                {
                $ValIdxZ = $x;
                array_push($Index,$Arr2[$x]);
                }
            foreach($new_array2 as $x=>$x_value)
                {
                $ValIdxZ = $x;
                array_push($Index,$Arr1[$x]);
                }
            // print_r($Index[0]);
            foreach($Index as $IndexLoop)
                {
                $Nomor = $IndexLoop['Idx'];
                $XValue = $IndexLoop['X'];
                $YValue = $IndexLoop['Y'];
                $ZValue = $IndexLoop['Z'];
                $AValue = $IndexLoop['A'];
            ?>
                <tr>
                    <td><?php echo $Nomor; ?></td>
                    <td><?php echo $XValue; ?></td>
                    <td><?php echo $YValue; ?></td>
                    <td><?php echo $ZValue; ?></td>
                    <td><?php echo $AValue; ?></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
    if($Params == '')
    {
    ?>
    <div class="table-responsive my-custom-scrollbar table-wrapper-scroll-y">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom">Index</th>
                    <th class="text-center trowCustom"><?php echo $ParamX; ?></th>
                    <th class="text-center trowCustom"><?php echo $ParamY; ?></th>
                    <th class="text-center trowCustom"><?php echo $ParamZ; ?></th>
                </tr>
            <tbody>
            <?php
            foreach($Arr1 as $Arr)
            {
                $Index = trim($Arr['Idx']);
                $Param1 = trim($Arr['X']);
                $Param2 = trim($Arr['Y']);
                $Param3 = trim($Arr['Z']);
            ?>
                <tr>
                    <td><?php echo $Index; ?></td>
                    <td><?php echo $Param1; ?></td>
                    <td><?php echo $Param2; ?></td>
                    <td><?php echo $Param3; ?></td>
                </tr>   
            <?php
            }
            ?>
            </tbody>
            <i>UNCATEGORIZED INDEX FOUND</i>
        </table>
    </div>
    <div><button class="button-warning" id="UnknownList">Download Uncategorized List</button></div>
    <?php
    }

}
?>
<script>
$(document).ready(function () {
    $("#UnknownList").click(function(){
        var Type = '<?php echo $Type; ?>';
        var Params = '<?php echo $Params; ?>';
        var Date1 = '<?php echo $Date1; ?>';
        var Date2 = '<?php echo $Date2; ?>';
        var Loc = '<?php echo $Loc; ?>';
        window.location.href = 'project/Reconciliation/DownloadUnnamedList.php?ty='+Type+'&&par='+Params+'&&ds='+Date1+'&&de='+Date2+'&&loc='+Loc;
    });
});
</script>