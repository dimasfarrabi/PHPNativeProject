<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleShiftCodeMachine.php");
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
if((trim($AccessLogin) != "Manager"))
{
    if($RDataUserWebtrax['MnAdmin'] != "1" && $RDataUserWebtrax['MnReport'] != "1")  
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}
else
{
    if($RDataUserWebtrax['MnAdmin'] != "1")
    {
        if($RDataUserWebtrax['MnCostTracking'] != "1")
        {
            ?>
            <script language="javascript">
                window.location.replace("https://protrax.formulatrix.com/");
            </script>
            <?php
            exit();
        }
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $StartDate = htmlspecialchars(trim($_POST['SD']), ENT_QUOTES, "UTF-8");
    $EndDate = htmlspecialchars(trim($_POST['ED']), ENT_QUOTES, "UTF-8");
    $Machine = htmlspecialchars(trim($_POST['Machine']), ENT_QUOTES, "UTF-8");
    $Location = htmlspecialchars(trim($_POST['Location']), ENT_QUOTES, "UTF-8");
    $BolChecked = htmlspecialchars(trim($_POST['BolChecked']), ENT_QUOTES, "UTF-8");
    if(strtotime($StartDate) > strtotime($EndDate))
    {
        echo '<div class="col-md-12 fw-bold"><h5>Error filter date!</h5></div>';
        exit();
    }    
    if($BolChecked == "TRUE") # dgn filter tgl
    {
        if($Location == "SEMARANG")
        {
            $QData = LOAD_SHIFTCODE_MACHINE_LIST_BY_MACHINE_PSM($StartDate,$EndDate,$Machine);
        }
        else
        {
            $QData = LOAD_SHIFTCODE_MACHINE_LIST_BY_MACHINE($StartDate,$EndDate,$Machine,$linkMACHWebTrax);
        }
        ?>
<div class="col-12"><h5>Hasil Pencarian Tgl <?php echo $StartDate." - ".$EndDate; ?></h5></div>
        <?php
    }
    else    # tanpa filter tgl
    {
        if($Location == "SEMARANG")
        {
            $QData = LOAD_SHIFTCODE_MACHINE_LIST_BY_MACHINE_PSM_NO_DATE($Machine);
        }
        else
        {
            $QData = LOAD_SHIFTCODE_MACHINE_LIST_BY_MACHINE_NO_DATE($Machine,$linkMACHWebTrax);
        }
        ?>
<div class="col-12"><h5>Hasil Pencarian Mesin : <?php echo $Machine; ?></h5></div>
        <?php
    }
    ?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-hover table-bordered" id="TableShiftCode">
            <thead class="table-secondary">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Location</th>
                    <th class="text-center">Machine</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Utilize Hours</th>
                    <th class="text-center">#</th>
                </tr>
            </thead>
            <tbody><?php 
            $No = 1;
            while($RData = mssql_fetch_assoc($QData)) 
            {
                $DataToken = base64_encode(base64_encode(trim($RData['Idx'])."DataToken"));
                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-center"><?php echo $Location; ?></td>
                    <td class="text-start"><?php echo trim($RData['Machine']); ?></td>
                    <td class="text-center"><?php echo date("m/d/Y",strtotime($RData['Date'])); ?></td>
                    <td class="text-center"><?php echo (int)trim($RData['UtilizeHours']); ?></td>
                    <td class="text-center"><span class="PointerList DeleteRow" data-token="<?php echo $DataToken; ?>" data-location="<?php echo $Location; ?>">Delete</span></td>
                </tr>
                <?php
                $No++; 
            }
            ?></tbody>
        </table>
    </div>
</div>

    <?php
}
else
{
    echo "";    
}
?>