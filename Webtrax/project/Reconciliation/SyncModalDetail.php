<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleSyncRecon.php"); 
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

    // echo $Type."<br>".$Params."<br>".$Date1."<br>".$Date2;

    switch ($Type) {
        case 'Time Tracking':
            {
                $QList = GET_PSL_TIME_TRACKING_DETAIL_ROW($Params,$Date1,$Date2,$linkMACHWebTrax);
                $QList2 = GET_PSM_TIME_TRACKING_DETAIL_ROW($Params,$Date1,$Date2,$PSMConn);
                $ParamX = "Date";
                $ParamY = "NIK";
                $ParamZ = "Activity";
                $ParamA = "Notes";
                ?>
                <br>
                <div><h5><strong>Division :<?php echo $Params." Date :".$Date1." - ".$Date2;?></strong></h5></div>
                <br>
                <?php
            }
        break;
        case 'Machine Tracking':
            {
                $QList = GET_PSL_MACHINE_TRACKING_DETAIL_ROW($Params,$Date1,$Date2,$linkMACHWebTrax);
                $QList2 = GET_PSM_MACHINE_TRACKING_DETAIL_ROW($Params,$Date1,$Date2,$PSMConn);
                $ParamX = "Date";
                $ParamY = "Part Number";
                $ParamZ = "WO Mapping ID";
                $ParamA = "Notes";
                ?>
                <br>
                <div><h5><strong>Machine Name :<?php echo $Params." Date :".$Date1." - ".$Date2;?></strong></h5></div>
                <br>
                <?php
            }
        break;
        case 'Material Tracking':
            {
                $QList = GET_PSL_MATERIAL_TRACKING_DETAIL_ROW($Params,$Date1,$Date2,$linkMACHWebTrax);
                $QList2 = GET_PSM_MATERIAL_TRACKING_DETAIL_ROW($Params,$Date1,$Date2,$PSMConn);
                $ParamX = "Date";
                $ParamY = "Part Number";
                $ParamZ = "WO Mapping ID";
                $ParamA = "Notes";
                ?>
                <br>
                <div><h5><strong>Category :<?php echo $Params." Date :".$Date1." - ".$Date2;?></strong></h5></div>
                <br>
                <?php
            }
        break;
        case 'WO Mapping':
            {
                $QList = GET_PSL_WO_TRACKING_DETAIL_ROW($Params,$Date1,$Date2,$linkMACHWebTrax);
                $QList2 = GET_PSM_WO_TRACKING_DETAIL_ROW($Params,$Date1,$Date2,$PSMConn);
                $ParamX = "Date";
                $ParamY = "WO Child";
                $ParamZ = "WO Quote";
                $ParamA = "Notes";
                ?>
                <br>
                <div><h5><strong>Expense Allocation :<?php echo $Params." Date :".$Date1." - ".$Date2;?></strong></h5></div>
                <br>
                <?php
            }
        break;
        case 'Barcode Status':
            {
                $QList = GET_PSL_BARCODE_DETAIL_ROW($Date1,$Date2,$linkMACHWebTrax);
                $QList2 = GET_PSM_BARCODE_DETAIL_ROW($Date1,$Date2,$PSMConn);
                $ParamX = "Date";
                $ParamY = "WO Child";
                $ParamZ = "WO Quote";
                $ParamA = "Notes";
                ?>
                <br>
                <div><h5><strong><?php echo " Date :".$Date1." - ".$Date2;?></strong></h5></div>
                <br>
                <?php
            }
        break;
    }

    $Arr1 = array();
    $Arr2 = array();
    while($RList = sqlsrv_fetch_array($QList))
    {
        $Idx = trim($RList['PSMonPSL']);
        $X = trim($RList['X']);
        $Y = trim($RList['Y']);
        $Z = trim($RList['Z']);
        $ArrayPSL = array("Idx" => $Idx, "X" => $X, "Y" => $Y, "Z" => $Z,"A" => "Tidak Sesuai Dengan PSM");
        array_push($Arr1,$ArrayPSL);
    }
    while($RList2 = sqlsrv_fetch_array($QList2))
    {
        $IdxPSM = trim($RList2['PSMRow']);
        $XPSM = trim($RList2['X']);
        $YPSM = trim($RList2['Y']);
        $ZPSM = trim($RList2['Z']);
        $ArrayPSM = array("Idx" => $IdxPSM, "X" => $XPSM, "Y" => $YPSM, "Z" => $ZPSM, "A" => "Belum Terimport ke PSL");
        array_push($Arr2,$ArrayPSM);
    }

    foreach($Arr1 as $ValArray1)
    {
        $aTmp1[] = $ValArray1['Idx'];
    }
    foreach($Arr2 as $ValArray2)
    {
        $aTmp2[] = $ValArray2['Idx'];
    }
    $new_array = array_diff($aTmp2,$aTmp1); // PSM di PSL
    $new_array2 = array_diff($aTmp1,$aTmp2); // KELEBIHAN IMPORT
    $Index = array();
    ?>
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
  
}
?>
                
                        