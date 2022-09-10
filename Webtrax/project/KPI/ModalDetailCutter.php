<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleKPI.php"); 
date_default_timezone_set("Asia/Jakarta");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    $Codec = explode("*",$ValCodeDec);
    $ValMonth = $Codec[0];
    $Loc = $Codec[1];
    // echo $ValMonth;
?>
<div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TableDetailCutter">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center trowCustom">BC</th>
                        <th class="text-center trowCustom">DatePrint</th>
                        <th class="text-center trowCustom">DateCutting</th>
                        <th class="text-center trowCustom">Diff (Day)</th>
                        <th class="text-center trowCustom">PartNo</th>
                        <th class="text-center trowCustom">WO</th>
                        <th class="text-center trowCustom">Cutter</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                        $Data = GET_DETAILS($Loc,$ValMonth,$linkMACHWebTrax);
                        while($DataRes = sqlsrv_fetch_array($Data))
                        {
                            $ValDateCreate = trim($DataRes['DatePrint']);
                            $ValDateCut = trim($DataRes['DateCutting']);
                            $ValDiff = abs(trim($DataRes['Diff']));
                            $PartNo = trim($DataRes['AlmytaPartNo']);
                            $WO = trim($DataRes['WO']);
                            $Cutter = trim($DataRes['Cutter']);
                            $Idx = trim($DataRes['Idx']);
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $Idx; ?></td>
                                <td class="text-center"><?php echo $ValDateCreate; ?></td>
                                <td class="text-center"><?php echo $ValDateCut; ?></td>
                                <td class="text-center"><?php echo $ValDiff; ?></td>
                                <td class="text-center"><?php echo $PartNo; ?></td>
                                <td class="text-left"><?php echo $WO; ?></td>
                                <td class="text-left"><?php echo $Cutter; ?></td>
                            </tr>
                            <?php
                        }
                ?>
                </tbody>
            </table>
        </div>
</div>
<?php
}
?>