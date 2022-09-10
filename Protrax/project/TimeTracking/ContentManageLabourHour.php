<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleLabourHour.php");
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
if($RDataUserWebtrax['MnAdmin'] != "1")  
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $InputName = htmlspecialchars(trim($_POST['InputName']), ENT_QUOTES, "UTF-8");
    $InputWOC = htmlspecialchars(trim($_POST['InputWOC']), ENT_QUOTES, "UTF-8");
    $Location = htmlspecialchars(trim($_POST['Location']), ENT_QUOTES, "UTF-8");
    $ValToken = base64_encode(base64_encode("ID:".$InputName));
    $InputName = str_replace(" - PSM","",$InputName);
    $ArrListPMDM = array();
    $BolV1 = FALSE;
    $QListPMDM = GET_LIST_PM_DM_NAME($linkMACHWebTrax);
    while($RListPMDM = mssql_fetch_assoc($QListPMDM))
    {
        array_push($ArrListPMDM,array("FullName"=>trim($RListPMDM['FullName'])));
    }
    foreach($ArrListPMDM as $ListPMDM)
    {
        if($ListPMDM['FullName'] == $InputName)
        {
            $BolV1 = TRUE;
        }
    }
    if($BolV1 == TRUE)
    {
        $Location = "All";
    }

    ?>
<div class="col-md-6 fw-bold">Table Result</div>
<div class="col-md-6 text-end"><button class="btn btn-sm btn-dark" id="BtnAddData" data-sess="<?php echo $ValToken; ?>">Add Data</button></div>
<div class="col-md-12 pt-2">
    <div class="table-responsive">
        <table class="table table-border table-hover" id="TableViewData">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Employee</th>
                    <th class="text-center">WO ID</th>
                    <th class="text-center">WO Child</th>
                    <th class="text-center">Expense</th>
                    <th class="text-center">Closed Time</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">#</th>
                </tr>
            </thead>
            <tbody><?php
            $No = 1;
            $QData = VIEW_DATA_EMPLOYEE_FILTER($InputName,$InputWOC,$Location,$linkMACHWebTrax);
            while($RData = mssql_fetch_assoc($QData))
            {
                $IDRow = trim($RData['Idx']);
                $Employee = trim($RData['EmployeeName']);
                $Employee = str_replace(" - PSM","",$Employee);
                $WOC = trim($RData['WOChild']);
                $Expense = trim($RData['ExpenseAllocation']);
                $ClosedTime = trim($RData['ClosedTime']);
                $Total = trim($RData['SumEstimateTime']);
                $Total2 = sprintf('%.3f',floatval(trim($Total)));
                $WOMappingID = trim($RData['WOMappingID']);
                $Key = $Employee."#".$IDRow;
                $ValToken = base64_encode(base64_encode("TokenID:".$Key));
                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-start"><?php echo $Employee; ?></td>
                    <td class="text-center"><?php echo $WOMappingID; ?></td>
                    <td class="text-start"><?php echo $WOC; ?></td>
                    <td class="text-center"><?php echo $Expense; ?></td>
                    <td class="text-center"><?php echo $ClosedTime; ?></td>
                    <td class="text-end"><?php echo $Total2; ?></td>
                    <td class="text-center"><span class="PointerList UpdateLabourHour" data-datatoken="<?php echo $ValToken; ?>" title="Update Labour Hour"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>&nbsp;<span class="PointerList DeleteLabourHour" data-datatoken="<?php echo $ValToken; ?>" title="Delete Labour Hour"><i class="bi bi-trash-fill" aria-hidden="true"></i></span></td>
                </tr>
                <?php
                $No++;
            }
            ?></tbody>
        </table>
    </div>
</div>
<div id="TemporarySpace"></div>  
    <?php
}
else
{
    echo "";    
}
?>