<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleSyncReconV2.php"); 
date_default_timezone_set("Asia/Jakarta");
$ValDateAwal = $ValDateAkhir = 0;

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
    $ReportType = htmlspecialchars(trim($_POST['ReportType']), ENT_QUOTES, "UTF-8");
    $FilterType = htmlspecialchars(trim($_POST['FilterType']), ENT_QUOTES, "UTF-8");
    $ValDate = htmlspecialchars(trim($_POST['Date']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ClosedTime']), ENT_QUOTES, "UTF-8");

    // echo "<br>".$ReportType."<br>".$FilterType."<br>".$ValDate."<br>".$ValClosedTime."<br>";

    switch ($FilterType) {
        case 'Daily':
            {
                $ValDateAwal = $ValDate;
                $ValDateAkhir = $ValDate;
                ?>
                <br>
                <div><h5><strong>Webtrax Synchronize Reconcile(<?php echo $ValDate;?>)</strong></h5></div>
                <br>
                <?php
            }
        break;
        case 'Weekly':
            {
                $ArrWeek = explode("/",$ValDate);
                $Day = $ArrWeek[0];
                $Month = $ArrWeek[1];
                $Year = $ArrWeek[2];

                $date = new DateTime($ValDate);
                $week = $date->format("W");
                $week_array = getStartAndEndDate($week,$Year);
                $ArrRangeWeek2 = explode("#",$week_array);
                $ValDateAwal = $ArrRangeWeek2[0];
                $ValDateAkhir = $ArrRangeWeek2[1];
                ?>
                <br>
                <div><h5><strong>Webtrax Synchronize Reconcile(<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?>)
                <br>Week : <?php echo $week;?></strong></h5></div>
                <br>
                <?php
            }
        break;
        case 'Monthly':
            {
                
                $ArrMonth = explode("/",$ValDate);
                $Month = $ArrMonth[0];
                $Year = $ArrMonth[2];
                $ValDateAwal = $Month ."/01/". $Year;
                $ValDateAkhir = date("m/t/Y", strtotime($ValDateAwal));
                ?>
                <br>
                <div><h5><strong>Webtrax Synchronize Reconcile(<?php echo $Month."/".$Year;?>)</strong></h5></div>
                <br>
                <?php
            }
        break;
        case 'ClosedTime':
            {
                $ArrHalf = explode("-",$ValClosedTime);
                $Year = $ArrHalf[0];
                $Half = $ArrHalf[1];
                
                if($Half == "H1"){ $ValDateAwal="1/01/".$Year; $ValDateAkhir="6/30/".$Year;}
                elseif($Half == "H2"){ $ValDateAwal="7/01/".$Year; $ValDateAkhir="12/31/".$Year;}
                ?>
                <br>
                <div><h5><strong>Webtrax Synchronize Reconcile(<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?>)</strong></h5></div>
                <br>
                <?php
            }
        break;
        default:
        {
            
            ?>
            <br>
            <div><h5><strong>Employee Webtrax Synchronize</strong></h5></div>
            <br>
            <?php
        }
        break;
    }
    switch ($ReportType) {
        case 'Time Tracking':
            {
                $XData = GET_TIMETRACK_PSL_DATA($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $XDataSum = GET_TIMETRACK_PSL_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $YData = GET_TIMETRACK_PSM_DATA($ValDateAwal,$ValDateAkhir,$PSMConn);
                $YDataSum = GET_TIMETRACK_PSM_SUM($ValDateAwal,$ValDateAkhir,$PSMConn);

                $ZData1 = GET_TIMETRACK_WEBTRAX_DATA_PSL($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZData2 = GET_TIMETRACK_WEBTRAX_DATA_PSM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZDataSum = GET_TIMETRACK_WEBTRAX_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $ValHead = "Division";
                $ValStab = "Stabilize<br>(hours)";
            }
        break;
        case 'Machine Tracking':
            {
                $XData = GET_MACHINE_TRACK_PSL_DATA($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $XDataSum = GET_MACHINE_TRACK_PSL_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $YData = GET_MACHINE_TRACK_PSM_DATA($ValDateAwal,$ValDateAkhir,$PSMConn);
                $YDataSum = GET_MACHINE_TRACK_PSM_SUM($ValDateAwal,$ValDateAkhir,$PSMConn);

                $ZData1 = GET_TMACHINE_TRACK_WEBTRAX_DATA_PSL($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZData2 = GET_TMACHINE_TRACK_WEBTRAX_DATA_PSM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZDataSum = GET_TMACHINE_TRACK_WEBTRAX_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $ValHead = "Machine Name";
                $ValStab = "Stabilize<br>(hours)";
            }
        break;
        case 'Material Tracking':
            {
                $XData = GET_MATERIAL_TRACK_PSL_DATA($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $XDataSum = GET_MATERIAL_TRACK_PSL_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $YData = GET_MATERIAL_TRACK_PSM_DATA($ValDateAwal,$ValDateAkhir,$PSMConn);
                $YDataSum = GET_MATERIAL_TRACK_PSM_SUM($ValDateAwal,$ValDateAkhir,$PSMConn);

                $ZData1 = GET_MATERIAL_TRACK_WEBTRAX_PSL($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZData2 = GET_MATERIAL_TRACK_WEBTRAX_PSM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZDataSum = GET_MATERIAL_WEBTRAX_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $ValHead = "Category";
                $ValStab = "Total<br>Cost ($)";
            }
        break;
        case 'WO Mapping':
            {
                $XData = GET_WO_PSL_DATA($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $XDataSum = GET_WO_PSL_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $YData = GET_WO_PSM_DATA($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $YDataSum = GET_WO_PSM_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $ZData1 = GET_WO_WEBTRAX_PSL($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZData2 = GET_WO_WEBTRAX_PSM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZDataSum = GET_WO_WEBTRAX_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $ValHead = "Expense Allocation";
                $ValStab = "Total<br>Cost ($)";
            }
        break;
        case 'Barcode Status':
            {
                $XData = GET_BARCODE_PSL_DATA($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $XDataSum = GET_BARCODE_PSL_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $YData = GET_BARCODE_PSM_DATA($ValDateAwal,$ValDateAkhir,$PSMConn);
                $YDataSum = GET_BARCODE_PSM_SUM($ValDateAwal,$ValDateAkhir,$PSMConn);

                $ZData1 = GET_BARCODE_WEBTRAX_PSL($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZData2 = GET_BARCODE_WEBTRAX_PSM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZDataSum = GET_BARCODE_WEBTRAX_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $ValHead = "Expense Allocation";
                $ValStab = "Total Qty";
            }
        break;
        case 'Spindle Hour':
            {
                $XData = GET_SPINDLE_PSL_DATA($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $XDataSum = GET_SPINDLE_PSL_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $YData = GET_SPINDLE_PSM_DATA($ValDateAwal,$ValDateAkhir,$PSMConn);
                $YDataSum = GET_SPINDLE_PSM_SUM($ValDateAwal,$ValDateAkhir,$PSMConn);

                $ZData1 = GET_SPINDLE_WEBTRAX_PSL($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZData2 = GET_SPINDLE_WEBTRAX_PSM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZDataSum = GET_SPINDLE_WEBTRAX_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $ValHead = "Machine Name";
                $ValStab = "Total Hour<br>(hours)";
            }
        break;
        case 'Attendance':
            {
                $XData = GET_ATT_PSL_DATA($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $XDataSum = GET_ATT_PSL_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $YData = GET_ATT_PSM_DATA($ValDateAwal,$ValDateAkhir,$PSMConn);
                $YDataSum = GET_ATT_PSM_SUM($ValDateAwal,$ValDateAkhir,$PSMConn);

                $ZData1 = GET_ATT_WEBTRAX_PSL($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZData2 = GET_ATT_WEBTRAX_PSM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZDataSum = GET_ATT_WEBTRAX_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $ValHead = "Params";
                $ValStab = "Total Hour<br>(hours)";
            }
        break;
        case 'Raw Material':
            {
                $XData = GET_RAW_PSL_DATA($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $XDataSum = GET_RAW_PSL_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $YData = GET_RAW_PSM_DATA($ValDateAwal,$ValDateAkhir,$PSMConn);
                $YDataSum = GET_RAW_PSM_SUM($ValDateAwal,$ValDateAkhir,$PSMConn);

                $ZData1 = GET_RAW_WEBTRAX_PSL($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZData2 = GET_RAW_WEBTRAX_PSM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZDataSum = GET_RAW_WEBTRAX_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $ValHead = "Expense Allocation";
                $ValStab = "Qty Out";
            }
        break;
        case 'Inventory Out label':
            {
                $XData = GET_INVENTORY_PSL_DATA($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $XDataSum = GET_INVENTORY_PSL_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $YData = GET_INVENTORY_PSM_DATA($ValDateAwal,$ValDateAkhir,$PSMConn);
                $YDataSum = GET_INVENTORY_PSM_SUM($ValDateAwal,$ValDateAkhir,$PSMConn);

                $ZData1 = GET_INVENTORY_WEBTRAX_PSL($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZData2 = GET_INVENTORY_WEBTRAX_PSM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $ZDataSum = GET_INVENTORY_WEBTRAX_SUM($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);

                $ValHead = "Category";
                $ValStab = "Qty Out";
            }
        break;
    }

    $Arr1 = array();
    $Arr2 = array();
    $Arr3 = array();
    $Arr4 = array();

    while($XDataVal = sqlsrv_fetch_array($XData))
    {
        $ValParam = trim($XDataVal['Params']);
        $ValNumX = trim($XDataVal['XRow']);
        $ValCal = trim($XDataVal['CAL']);
        $ArrayPSL = array("Params" => $ValParam, "RowX" => $ValNumX, "CAL" => $ValCal);
        array_push($Arr1,$ArrayPSL);
    }
    while($YDataVal = sqlsrv_fetch_array($YData))
    {
        $ValParam2 = trim($YDataVal['Params']);
        $ValNumY = trim($YDataVal['YRow']);
        $ValCal = trim($YDataVal['CAL']);
        $ArrayPSM = array("Params" => $ValParam2, "RowY" => $ValNumY, "CAL" => $ValCal);
        array_push($Arr2,$ArrayPSM);
    }
    while($ZDataVal1 = sqlsrv_fetch_array($ZData1))
    {
        $ValParam3 = trim($ZDataVal1['Params']);
        $ValNumZ1 = trim($ZDataVal1['ZRow1']);
        $ValCal = trim($ZDataVal1['CAL']);
        $ArrayZ1 = array("Params" => $ValParam3, "RowZ1" => $ValNumZ1, "CAL" => $ValCal);
        array_push($Arr3,$ArrayZ1);
    }
    while($ZDataVal2 = sqlsrv_fetch_array($ZData2))
    {
        $ValParam4 = trim($ZDataVal2['Params']);
        $ValNumZ2 = trim($ZDataVal2['ZRow2']);
        $ValCal = trim($ZDataVal2['CAL']);
        $ArrayZ2 = array("Params" => $ValParam4, "RowZ2" => $ValNumZ2, "CAL" => $ValCal);
        array_push($Arr4,$ArrayZ2);
    }
//-----------------------------------------------------------------------------
    while($ValXDataSum = sqlsrv_fetch_array($XDataSum))
    {
        $ValCount = trim($ValXDataSum['COUNT']);
        $ValSum = trim($ValXDataSum['SUM']);
    }
    while($ValYDataSum = sqlsrv_fetch_array($YDataSum))
    {
        $ValCount2 = trim($ValYDataSum['COUNT']);
        $ValSum2 = trim($ValYDataSum['SUM']);
    }
    while($ValZDataSum = sqlsrv_fetch_array($ZDataSum))
    {
        $ValCount3 = trim($ValZDataSum['COUNT']);
        $ValSum3 = trim($ValZDataSum['SUM']);
    }
    $ValDiffCount = (($ValCount + $ValCount2) - $ValCount3);
    $ValDiffSum = (($ValSum + $ValSum2) - $ValSum3);
    $ValSum = number_format((float)$ValSum,2,'.',',');
    $ValSum2 = number_format((float)$ValSum2,2,'.',',');
    $ValSum3 = number_format((float)$ValSum3,2,'.',',');
    $ValDiffCount = number_format((float)$ValDiffCount,2,'.',',');
    $ValDiffSum = number_format((float)$ValDiffSum,2,'.',',');
?>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="">
        <thead class="theadCustom">
            <tr>
                <th class="text-center trowCustom" colspan="2">PSL</th>
                <th class="text-center trowCustom" colspan="2">PSM</th>
                <th class="text-center trowCustom" colspan="2">Webtrax</th>
                <th class="text-center trowCustom" colspan="2">Difference</th>
            </tr>
            <tr>
                <th class="text-center trowCustom">Data Count</th>
                <th class="text-center trowCustom"><?php echo $ValStab; ?></th>
                <th class="text-center trowCustom">Data Count</th>
                <th class="text-center trowCustom"><?php echo $ValStab; ?></th>
                <th class="text-center trowCustom">Data Count</th>
                <th class="text-center trowCustom"><?php echo $ValStab; ?></th>
                <th class="text-center trowCustom">Data Count</th>
                <th class="text-center trowCustom"><?php echo $ValStab; ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center"><?php echo $ValCount; ?></td>
                <td class="text-center"><?php echo $ValSum; ?></td>
                <td class="text-center"><?php echo $ValCount2; ?></td>
                <td class="text-center"><?php echo $ValSum2; ?></td>
                <td class="text-center"><?php echo $ValCount3; ?></td>
                <td class="text-center"><?php echo $ValSum3; ?></td>
                <td class="text-center"><?php echo $ValDiffCount; ?></td>
                <td class="text-center"><?php echo $ValDiffSum; ?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover" id="">
        <thead class="theadCustom">
            <tr>
                <th class="text-center trowCustom" width = "40" rowspan="2">No</th>
                <th class="text-center trowCustom" width = "300" rowspan="2"><?php echo $ValHead; ?></th>
                <th class="text-center trowCustom" colspan = "2">PSL</th>
                <th class="text-center trowCustom" colspan = "2">Webtrax</th>
                <th class="text-center trowCustom" colspan = "2">Difference</th>
                <th class="text-center trowCustom" rowspan="2">#</th>
            </tr>
            <tr>
                <th class="text-center trowCustom">Data Count</th>
                <th class="text-center trowCustom"><?php echo $ValStab; ?></th>
                <th class="text-center trowCustom">Data Count</th>
                <th class="text-center trowCustom"><?php echo $ValStab; ?></th>
                <th class="text-center trowCustom">Data Count</th>
                <th class="text-center trowCustom"><?php echo $ValStab; ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $TotalRowPSL = $TotalZRowPSL = $TotalDiff = $TotalCALdiff = $TotalZCALdiff = 0;
        foreach($Arr1 as $ValArr1)
        {
            $ValParamPSL = $ValArr1['Params'];
            $ValRowPSL = $ValArr1['RowX'];
            $ValCALPSL = $ValArr1['CAL'];
            foreach($Arr3 as $ValArr3)
            {
                $ZParamsPSL = $ValArr3['Params'];
                $ZRowPSL = $ValArr3['RowZ1'];
                $ZCALPSL = $ValArr3['CAL'];
                $ValDiff = ($ValRowPSL - $ZRowPSL);
                $ValCALDiff = @($ValCALPSL - $ZCALPSL);
                if($ZParamsPSL == $ValParamPSL)
                {
                    $TotalRowPSL = $TotalRowPSL + $ValRowPSL;
                    $TotalZRowPSL = $TotalZRowPSL + $ZRowPSL;
                    $TotalCAL1 = @($TotalCAL1 + $ValCALPSL);
                    $TotalCAL2 = @($TotalCAL2 + $ZCALPSL);
                    $ValCALPSL = number_format((float)$ValCALPSL,2,'.',',');
                    $ZCALPSL = number_format((float)$ZCALPSL,2,'.',',');
                    $ValCALDiff = number_format((float)$ValCALDiff,2,'.',',');
                    $RowEnc = base64_encode($ReportType."*".$ValParamPSL."*".$ValDateAwal."*".$ValDateAkhir."*PSL");
                    $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#SyncDetail" title="Detail"></span>'
                    
        ?>
            <tr>
                <td class="text-center"><?php echo $no; ?></td>
                <td class="text-left"><?php echo $ValParamPSL; ?></td>
                <td class="text-center"><?php echo $ValRowPSL; ?></td>
                <td class="text-right"><?php echo $ValCALPSL; ?></td>
                <td class="text-center"><?php echo $ZRowPSL; ?></td>
                <td class="text-right"><?php echo $ZCALPSL; ?></td>
                <td class="text-center"><?php echo $ValDiff; ?></td>
                <td class="text-right"><?php echo $ValCALDiff; ?></td>
                <td class="text-center"><?php echo $ValOptForm; ?></td>
            </tr>
        <?php
                $no++;
                
                }
            }
        }
        $TotalDiff = ($TotalRowPSL - $TotalZRowPSL);
        $TotalCALDiff = ($TotalCAL1 - $TotalCAL2);
        $TotalCAL1 = number_format((float)$TotalCAL1,2,'.',',');
        $TotalCAL2 = number_format((float)$TotalCAL2,2,'.',',');
        $TotalCALDiff = number_format((float)$TotalCALDiff,2,'.',',');
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-center theadCustom" colspan="2">TOTAL</th>
                <td class="text-center"><?php echo $TotalRowPSL; ?></td>
                <td class="text-right"><?php echo $TotalCAL1; ?></td>
                <td class="text-center"><?php echo $TotalZRowPSL; ?></td>
                <td class="text-right"><?php echo $TotalCAL2; ?></td>
                <td class="text-center"><?php echo $TotalDiff; ?></td>
                <td class="text-right"><?php echo $TotalCALDiff; ?></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="">
        <thead class="theadCustom">
            <tr>
                <th class="text-center trowCustom" width = "40" rowspan="2">No</th>
                <th class="text-center trowCustom" width = "300" rowspan="2"><?php echo $ValHead; ?></th>
                <th class="text-center trowCustom" colspan = "2">PSM</th>
                <th class="text-center trowCustom" colspan = "2">Webtrax</th>
                <th class="text-center trowCustom" colspan = "2">Difference</th>
                <th class="text-center trowCustom" rowspan="2">#</th>
            </tr>
            <tr>
                <th class="text-center trowCustom">Data Count</th>
                <th class="text-center trowCustom"><?php echo $ValStab; ?></th>
                <th class="text-center trowCustom">Data Count</th>
                <th class="text-center trowCustom"><?php echo $ValStab; ?></th>
                <th class="text-center trowCustom">Data Count</th>
                <th class="text-center trowCustom"><?php echo $ValStab; ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $TotalRowPSL = $TotalZRowPSL = $TotalDiff = $TotalCalX = $TotalCalZ = 0;
        foreach($Arr2 as $ValArr2)
        {
            $ValParamPSL = $ValArr2['Params'];
            $ValRowPSL = $ValArr2['RowY'];
            $Cal = $ValArr2['CAL'];
            foreach($Arr4 as $ValArr4)
            {
                $ZParamsPSL = $ValArr4['Params'];
                $ZRowPSL = $ValArr4['RowZ2'];
                $Cal2 = $ValArr4['CAL'];
                $ValDiffs = @($ValRowPSL - $ZRowPSL);
                $CalDiff = @($Cal - $Cal2);
                if($ZParamsPSL == $ValParamPSL)
                {
                    $TotalRowPSL = $TotalRowPSL + $ValRowPSL;
                    $TotalZRowPSL = $TotalZRowPSL + $ZRowPSL;
                    $TotalCalX = @($TotalCalX + $Cal);
                    $TotalCalZ = $TotalCalZ + $Cal2;
                    $TotalDiff = ($TotalRowPSL - $TotalZRowPSL);
                    $CaldiffTot = ($TotalCalX - $TotalCalZ);
                    $Cal = number_format((float)$Cal,2,'.',',');
                    $Cal2 = number_format((float)$Cal2,2,'.',',');
                    $CalDiff = number_format((float)$CalDiff,2,'.',',');
                    $RowEnc = base64_encode($ReportType."*".$ValParamPSL."*".$ValDateAwal."*".$ValDateAkhir."*PSM");
                    $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#SyncDetail" title="Detail"></span>'
                    
        ?>
            <tr>
                <td class="text-center"><?php echo $no; ?></td>
                <td class="text-left"><?php echo $ValParamPSL; ?></td>
                <td class="text-center"><?php echo $ValRowPSL; ?></td>
                <td class="text-right"><?php echo $Cal; ?></td>
                <td class="text-center"><?php echo $ZRowPSL; ?></td>
                <td class="text-right"><?php echo $Cal2; ?></td>
                <td class="text-center"><?php echo $ValDiffs; ?></td>
                <td class="text-right"><?php echo $CalDiff; ?></td>
                <td class="text-center"><?php echo $ValOptForm; ?></td>
            </tr>
        <?php
                $no++;
                
                
                }
            }
        }
        
        $TotalCalX = number_format((float)$TotalCalX,2,'.',',');
        $TotalCalZ = number_format((float)$TotalCalZ,2,'.',',');
        $CaldiffTot = number_format((float)$CaldiffTot,2,'.',',');
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-center theadCustom" colspan="2">TOTAL</th>
                <td class="text-center"><?php echo $TotalRowPSL; ?></td>
                <td class="text-right"><?php echo $TotalCalX; ?></td>
                <td class="text-center"><?php echo $TotalZRowPSL; ?></td>
                <td class="text-right"><?php echo $TotalCalZ; ?></td>
                <td class="text-center"><?php echo $TotalDiff; ?></td>
                <td class="text-right"><?php echo $CaldiffTot; ?></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="modal fade" id="SyncDetail" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Difference/Anomaly Details</strong></h5><span></span></div>
                        <div class="col-xs-6 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="ContentDetails"></div>
                        <div class="text-center"><img src="../images/ajax-loader1.gif" id="LoadingImg" class="load_img" style="height:10px;"/></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">&nbsp;Close&nbsp;</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
<?php

}
?>
<script>
$(document).ready(function () {
    $("#SyncDetail").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var formdata = new FormData();
        formdata.append("Code", DataCode);
        $.ajax({
            url: 'project/reconciliation/SyncModalDetailV2.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#ContentDetails').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#ContentDetails').hide();
                $('#ContentDetails').html(xaxa);
                $('#ContentDetails').fadeIn('fast');
                
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
});
</script>