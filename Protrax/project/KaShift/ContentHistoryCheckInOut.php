<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../ConfigDB2.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCheckInOutStatus.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");

if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
# data protrax user
$BolProdAcc = false;
$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
if(mssql_num_rows($QDataUserWebtrax) > 0)
{
    $RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
    $TypeUser = trim($RDataUserWebtrax['TypeUser']);
    $_SESSION['LoginMode'] = base64_encode($TypeUser);
    $AccessLogin = base64_decode($_SESSION['LoginMode']);   
}
else # kondisi tidak terdaftar di protrax user & akan di set sebagai employee dan hak akses ke bagian produksi saja
{
    $_SESSION['LoginMode'] = base64_encode("Employee");
    $AccessLogin = base64_decode($_SESSION['LoginMode']);
    $BolProdAcc = true;
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $AreaInput = htmlspecialchars(trim($_POST['Area']), ENT_QUOTES, "UTF-8");
    ?>
<div class="col-md-12">
    <div class="card">
        <h6 class="card-header text-white bg-secondary">Top 20 Log History</h6>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table table-responsive">
                        <table class="table table-bordered" id="TableLogHistory">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">NIK</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">TglCheck</th>
                                    <th class="text-center">StatusCheck</th>
                                    <th class="text-center">BarcodeID</th>
                                    <th class="text-center">Location</th>
                                    <th class="text-center">Company</th>
                                </tr>
                            </thead>
                            <tbody><?php 
                            $No = 1;
                            $QLog = GET_LOG_HISTORY_CHECKINOUT_BARCODE($AreaInput,$LinkPSL);
                            while($RLog = mssql_fetch_assoc($QLog))
                            {
                                $ValCompany = "PSL";
                                if(trim($RLog['Company']) != "")
                                {
                                    $ValCompany = trim($RLog['Company']);
                                }
                                $ValNIK = trim($RLog['NIK2']);
                                if($ValNIK == ''){
                                    $ValNIK = trim($RLog['NIK3']);
                                }
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $No; ?></td>
                                    <td class="text-center"><?php echo  $ValNIK; ?></td>
                                    <td class="text-start"><?php echo trim($RLog['EmployeeName']); ?></td>
                                    <td class="text-center"><?php echo date('m/d/Y H:i:s',strtotime(trim($RLog['DateCheck2']))); ?></td>
                                    <td class="text-center"><?php echo trim($RLog['StatusCheck']); ?></td>
                                    <td class="text-center"><?php echo trim($RLog['Barcode_ID']); ?></td>
                                    <td class="text-center"><?php echo trim($RLog['Location']); ?></td>
                                    <td class="text-center"><?php echo $ValCompany; ?></td>
                                </tr>
                                <?php
                                $No++;
                            }
                            ?></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php
}
else
{
    echo "";    
}
?>