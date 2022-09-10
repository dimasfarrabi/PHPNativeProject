<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleQualityPoint.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
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
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $arrQPType = array("TargetMin","Goal");
?>
<style>
.Points {
    font-size:11px;
}
.tableFixHead {
    overflow-y: auto;
    max-height: 900px;
}
.tableFixHead thead tr.first th {
    position: sticky;
    top: 0;
}
.tableFixHead thead tr.second th {
    position: sticky;
}
th {
    background: #eee;
    border-collapse: separate;
}
</style>
<script>
$(document).ready(function() { 
    $("thead tr.second th, thead tr.second td").css("top", 33)
});
</script>
<div class="col-md-12"><h5><strong>Summary Quality Points</strong> . Closed Time : <strong><?php echo $ValClosedTime; ?></h5></div>
<div class="col-md-12">
    <div style="table-responsive" class="tableFixHead">
        <table class="table table-bordered table-hover" id="ListSummary">
            <thead class="theadCustom">
                <tr class="first">
                    <th class="text-center" rowspan="2">Quote</th>
                    <?php
                        $arrExpense = array();
                        $QListReport = GET_DIVISION($ValClosedTime,$linkMACHWebTrax);
                        while($RListReport = sqlsrv_fetch_array($QListReport))
                        {
                            $ValDivision = $RListReport['Division'];
                            array_push($arrExpense,$ValDivision);
                            ?>
                            <th class="text-center Points" colspan="2"><?php echo $ValDivision; ?></th>
                            <?php
                        }
                    ?>
                </tr>
                <tr class="second">
                    <th class="Points">TargetMin(%)</th>
                    <th class="Points">Achv(%)</th>
                    <th class="Points">TargetMin(%)</th>
                    <th class="Points">Achv(%)</th>
                    <th class="Points">TargetMin(%)</th>
                    <th class="Points">Achv(%)</th>
                    <th class="Points">TargetMin(%)</th>
                    <th class="Points">Achv(%)</th>
                    <th class="Points">TargetMin(%)</th>
                    <th class="Points">Achv(%)</th>
                    <th class="Points" colspan="2">Achv(%)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = GET_SUMMARY_QP2($ValClosedTime,$linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($data))
                {
                    $Quote = trim($res['Quote']);
                    $TargetMachining = trim($res['TargetMachining']);
                    $ValQPMachining = trim($res['Machining']);
                    $TargetFabrication = trim($res['TargetFabrication']);
                    $ValQPFabrication = trim($res['Fabrication']);
                    $TargetInjection = trim($res['TargetInjection']);
                    $ValQPInjection = trim($res['Injection']);
                    $TargetAssembly = trim($res['TargetAssembly']);
                    $ValQPAssembly = trim($res['Assembly']);
                    $TargetElectronics = trim($res['TargetElectronics']);
                    $ValQPElectronics = trim($res['Electronics']);
                    $ValQPQa = trim($res['QA']);
                    if(trim($ValQPMachining) == ""){$ValQPMachining = "";} else {$ValQPMachining = number_format((float)$ValQPMachining, 2, '.', ',');};
                    if(trim($ValQPFabrication) == ""){$ValQPFabrication = "";} else {$ValQPFabrication = number_format((float)$ValQPFabrication, 2, '.', ',');};
                    if(trim($ValQPInjection) == ""){$ValQPInjection = "";} else {$ValQPInjection = number_format((float)$ValQPInjection, 2, '.', ',');};
                    if(trim($ValQPAssembly) == ""){$ValQPAssembly = "";} else {$ValQPAssembly = number_format((float)$ValQPAssembly, 2, '.', ',');};
                    if(trim($ValQPElectronics) == ""){$ValQPElectronics = "";} else {$ValQPElectronics = number_format((float)$ValQPElectronics, 2, '.', ',');};
                    if(trim($ValQPQa) == ""){$ValQPQa = "";} else {$ValQPQa = number_format((float)$ValQPQa, 2, '.', ',');};

                    if(trim($ValQPMachining) == ""){$ValQPMachining = "";} else {$ValQPMachining = number_format((float)$ValQPMachining, 2, '.', ',');};
                    if(trim($ValQPFabrication) == ""){$ValQPFabrication = "";} else {$ValQPFabrication = number_format((float)$ValQPFabrication, 2, '.', ',');};
                    if(trim($ValQPInjection) == ""){$ValQPInjection = "";} else {$ValQPInjection = number_format((float)$ValQPInjection, 2, '.', ',');};
                    if(trim($ValQPAssembly) == ""){$ValQPAssembly = "";} else {$ValQPAssembly = number_format((float)$ValQPAssembly, 2, '.', ',');};
                    if(trim($ValQPElectronics) == ""){$ValQPElectronics = "";} else {$ValQPElectronics = number_format((float)$ValQPElectronics, 2, '.', ',');};
                    $enc = $Quote."*".$ValClosedTime;
                ?>
                <tr data-row="<?php echo $enc; ?>" class="SummaryPointer">
                    <td class="text-left"><?php echo $Quote; ?></td>
                    <td class="text-right"><?php echo $TargetMachining; ?></td>
                    <td class="text-right"><?php echo $ValQPMachining; ?></td>
                    <td class="text-right"><?php echo $TargetFabrication; ?></td>
                    <td class="text-right"><?php echo $ValQPFabrication; ?></td>
                    <td class="text-right"><?php echo $TargetInjection; ?></td>
                    <td class="text-right"><?php echo $ValQPInjection; ?></td>
                    <td class="text-right"><?php echo $TargetAssembly; ?></td>
                    <td class="text-right"><?php echo $ValQPAssembly; ?></td>
                    <td class="text-right"><?php echo $TargetElectronics; ?></td>
                    <td class="text-right"><?php echo $ValQPElectronics; ?></td>
                    <td class="text-right"><?php echo $ValQPQa; ?></td>
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

else{}
?>