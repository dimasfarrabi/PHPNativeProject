<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModulePeoplePoint.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
/*
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
    if($RDataUserWebtrax['MnAdmin'] != "1" && $RDataUserWebtrax['MnCostTracking'] != "1")  
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
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $Half = htmlspecialchars(trim($_POST['Half']), ENT_QUOTES, "UTF-8");
    $Category = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    $Keywords = htmlspecialchars(trim($_POST['Keywords']), ENT_QUOTES, "UTF-8");
?>
<div class="col-sm-12">
    <button class="btn btn-sm btn-info" id="BtnDownload">Download Template</button> 
</div>
<div class="col-md-12">
    <i><br>Isikan nilai pada kolom Discretionary (E) dan kolom Exception (F)</i>
    <form method="post" action="project/CostTracking/src/srcImportPeoplePoint.php" id="FormImportPeoplePoint" enctype="multipart/form-data">
        <input class="form-control form-control-sm" type="file" id="InputFile" name="InputFile" accept=".csv" style="width:30%;">
        <button class="btn btn-sm btn-success" id="BtnImport" style="margin-top:10px;">Import</button>
    </form>
</div>
<!-- <div class="col-sm-3">
    <form class="form-inline" method="post" action="project/CostTracking/src/srcImportPeoplePoint.php" id="FormImportPeoplePoint" enctype="multipart/form-data">
        <div class="form-group row">
            <input class="form-control form-control-sm" type="file" id="InputFile" name="InputFile" accept=".csv">
            <button class="btn btn-sm btn-success" id="BtnImport" style="margin-top:10px;">Import</button>
        </div>
    </form>
</div> -->
<div class="col-md-12 mt-2">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-responsive table-hover display" id="TablePeoplePoint">
                    <thead>
                        <tr>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Division</th>
                            <th class="text-center">Points (%)</th>
                            <th class="text-center">Discretion (%)</th>
                            <th class="text-center">Exception (%)</th>
                            <th class="text-center">Total Points (%)</th>
                            <th class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $data = GET_TOTAL_EMP_POINT($Half,$Category,$Keywords,$linkMACHWebTrax);
                        while($res=sqlsrv_fetch_array($data))
                        {
                            $ValName = trim($res['FullName']);
                            $ValDiv = trim($res['Division']);
                            $Points = trim($res['Points']);
                            $Discretion = trim($res['Discretion']);
                            $Exception = trim($res['Exception']);
                            $TotalPoints = trim($res['TotalPoints2']);
                            if(trim($Discretion) == ""){$Discretion = "";} else {$Discretion = number_format((float)$Discretion, 2, '.', ',');} 
                            if(trim($Exception) == ""){$Exception = "";} else {$Exception = number_format((float)$Exception, 2, '.', ',');} 
                            $TotalPoints = number_format((float)$TotalPoints,2,'.',',');
                            $Points = number_format((float)$Points,2,'.',',');
                            $RowEnc = base64_encode($ValName."*".$ValDiv."*".$Half);
                            $ValOptForm = '<span class="bi bi-pencil-fill PointerList UpdateRow" aria-hidden="true" data-bs-toggle="modal" data-ecode="'.$RowEnc.'" data-bs-target="#ModalEditPoint" title="Update Point"></span>';
                        ?>
                        <tr>
                            <td class="text-left"><?php echo $ValName; ?></td>
                            <td class="text-left"><?php echo $ValDiv; ?></td>
                            <td class="text-center"><?php echo $Points; ?></td>
                            <td class="text-center"><?php echo $Discretion; ?></td>
                            <td class="text-center"><?php echo $Exception; ?></td>
                            <td class="text-center"><?php echo $TotalPoints; ?></td>
                            <td class="text-center"><?php echo $ValOptForm; ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalEditPoint" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Points</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div id="FormEditPoint"></div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<?php
}

?>