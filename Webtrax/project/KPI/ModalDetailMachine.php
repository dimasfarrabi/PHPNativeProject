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
?>
<div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TableMachKPI">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center trowCustom">Barcode</th>
                        <th class="text-center trowCustom">Start Machining By Operator</th>
                        <th class="text-center trowCustom">Machining Check Out</th>
                        <th class="text-center trowCustom">Diff (Day)</th>
                        <th class="text-center trowCustom">PartNo</th>
                        <th class="text-center trowCustom">WO</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                        $Data = GET_DETAILS_MACHINING($Loc,$ValMonth,$linkMACHWebTrax);
                        while($DataRes = sqlsrv_fetch_array($Data))
                        {
                            $Code = trim($DataRes['Barcode_ID']);
                            $DateIn = trim($DataRes['MachineStart']);
                            $DateOut = trim($DataRes['MachineEnd']);
                            $ValDiff = abs(trim($DataRes['Diff']));
                            $PartNo = trim($DataRes['PartNo']);
                            $WO = trim($DataRes['WO']);
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $Code; ?></td>
                                <td class="text-center"><?php echo $DateIn; ?></td>
                                <td class="text-center"><?php echo $DateOut; ?></td>
                                <td class="text-center"><?php echo $ValDiff; ?></td>
                                <td class="text-center"><?php echo $PartNo; ?></td>
                                <td class="text-left"><?php echo $WO; ?></td>
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