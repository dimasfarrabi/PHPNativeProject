<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleQualityPoint.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    // $ValProjectName = htmlspecialchars(trim($_POST['ValProjectName']), ENT_QUOTES, "UTF-8");
    // $ValProjectIDEnc = htmlspecialchars(trim($_POST['ValProjectID']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    // $ValProjectID = str_replace("ID","",base64_decode($ValProjectIDEnc));
?>

<div class="col-md-12"><h5><strong>Summary Quality Points</strong> . Closed Time : <strong><?php echo $ValClosedTime; ?></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom" width="20">No</th>
                    <th class="text-center trowCustom" width = "350">Quote</th>
                    <?php
                    $QListReport = GET_DIVISION($ValClosedTime,$linkMACHWebTrax);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValDivision = $RListReport['Division'];
                    ?>
                    <th class="text-center trowCustom"  width = "80"><strong><?php echo $ValDivision; ?><strong></th>
                    <?php
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $No = 1;
                $QListReport = GET_SUMMARY_QP($ValClosedTime,$linkMACHWebTrax);
                while($RListReport = sqlsrv_fetch_array($QListReport))
                {
                $ValQuoteName = $RListReport['Quote'];
                $ValQPMachining = $RListReport['Machining'];
                $ValQPFabrication = $RListReport['Fabrication'];
                $ValQPInjection = $RListReport['Injection'];
                $ValQPAssembly = $RListReport['Assembly'];
                $ValQPElectronics = $RListReport['Electronics'];
                $ValQPQa = $RListReport['QA'];
                if(trim($ValQPMachining) == ""){$ValQPMachining = "";} else {$ValQPMachining = number_format((float)$ValQPMachining, 2, '.', ',');};
                if(trim($ValQPFabrication) == ""){$ValQPFabrication = "";} else {$ValQPFabrication = number_format((float)$ValQPFabrication, 2, '.', ',');};
                if(trim($ValQPInjection) == ""){$ValQPInjection = "";} else {$ValQPInjection = number_format((float)$ValQPInjection, 2, '.', ',');};
                if(trim($ValQPAssembly) == ""){$ValQPAssembly = "";} else {$ValQPAssembly = number_format((float)$ValQPAssembly, 2, '.', ',');};
                if(trim($ValQPElectronics) == ""){$ValQPElectronics = "";} else {$ValQPElectronics = number_format((float)$ValQPElectronics, 2, '.', ',');};
                if(trim($ValQPQa) == ""){$ValQPQa = "";} else {$ValQPQa = number_format((float)$ValQPQa, 2, '.', ',');};
                
                ?>
                <tr>
                    <td><?php echo $No; ?></td>
                    <td><?php echo $ValQuoteName; ?></td>
                    <td class="text-right"><?php echo $ValQPMachining; ?></td>
                    <td class="text-right"><?php echo $ValQPFabrication; ?></td>
                    <td class="text-right"><?php echo $ValQPInjection; ?></td>
                    <td class="text-right"><?php echo $ValQPAssembly; ?></td>
                    <td class="text-right"><?php echo $ValQPElectronics; ?></td>
                    <td class="text-right"><?php echo $ValQPQa; ?></td>
                </tr>
                <?php
                
                $No++;
                
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