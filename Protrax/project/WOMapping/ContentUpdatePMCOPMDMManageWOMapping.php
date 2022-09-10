<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWOMapping.php");
require_once("../../Project/Report/Modules/ModuleReport.php");
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
    $ValWOMapping_ID = htmlspecialchars(trim($_POST['ValWOMapping_ID']), ENT_QUOTES, "UTF-8");
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValDataTemp = base64_encode(base64_encode(trim($ValWOMapping_ID)."#".$ValLocation));
    # data detail wo
    if($ValLocation == "PSM")
    {
        $QDataDetailWO = GET_DETAIL_WO_SELECTED_BY_ID_PSM($ValWOMapping_ID);
        $RDataDetailWO = mssql_fetch_assoc($QDataDetailWO);
        # list user pm/dm/co pm
        $ArrListUser = array();
        $QListUser = GET_LIST_USER_WORKHOUR_PSM();
        $NoLoop = 0;
        $ValCOPM = 0;
        while($RListUser = mssql_fetch_assoc($QListUser))
        {
            if($NoLoop == 0)
            {
                array_push($ArrListUser,array("FN" => "","Title" => "PM"));
                array_push($ArrListUser,array("FN" => "","Title" => "CO PM"));
                array_push($ArrListUser,array("FN" => "","Title" => "DM"));
                array_push($ArrListUser,array("FN" => trim($RListUser['FullName']),"Title" => trim($RListUser['Role'])));
            }
            else
            {
                array_push($ArrListUser,array("FN" => trim($RListUser['FullName']),"Title" => trim($RListUser['Role'])));
                if(trim($RListUser['Role']) == "CO PM")
                {
                    $ValCOPM = $ValCOPM + 1;
                }
            }
            $NoLoop++;
        }
        $BlockCOPM = " disabled";
        if($ValCOPM > 0)
        {
            $BlockCOPM = "";
        }
    }
    if($ValLocation == "PSL")
    {
        $QDataDetailWO = GET_DETAIL_WO_SELECTED_BY_ID($ValWOMapping_ID,$linkMACHWebTrax);
        $RDataDetailWO = mssql_fetch_assoc($QDataDetailWO);
        # list user pm/dm/co pm
        $ArrListUser = array();
        $QListUser = GET_LIST_USER_WORKHOUR($linkMACHWebTrax);
        $NoLoop = 0;
        $ValCOPM = 0;
        while($RListUser = mssql_fetch_assoc($QListUser))
        {
            if($NoLoop == 0)
            {
                array_push($ArrListUser,array("FN" => "","Title" => "PM"));
                array_push($ArrListUser,array("FN" => "","Title" => "CO PM"));
                array_push($ArrListUser,array("FN" => "","Title" => "DM"));
                array_push($ArrListUser,array("FN" => trim($RListUser['FullName']),"Title" => trim($RListUser['Role'])));
            }
            else
            {
                array_push($ArrListUser,array("FN" => trim($RListUser['FullName']),"Title" => trim($RListUser['Role'])));
                if(trim($RListUser['Role']) == "CO PM")
                {
                    $ValCOPM = $ValCOPM + 1;
                }
            }
            $NoLoop++;
        }
        $BlockCOPM = " disabled";
        if($ValCOPM > 0)
        {
            $BlockCOPM = "";
        }
    }

    ?>
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldLocation2" class="form-label fw-bold">Location</label>
            <input type="text" class="form-control form-control-sm" id="FieldLocation2" value="<?php echo $ValLocation; ?>" readonly>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldWOID2" class="form-label fw-bold">WOMapping_ID</label>
            <input type="text" class="form-control form-control-sm" id="FieldWOID2" value="<?php echo $ValWOMapping_ID; ?>" readonly>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldClosedTime2" class="form-label fw-bold">Closed Time</label>
            <input type="text" class="form-control form-control-sm" id="FieldClosedTime2" value="<?php echo $ValClosedTime; ?>" readonly>
        </div>
    </div>
    <div class="col-md-6">&nbsp;</div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="FieldOldPM" class="form-label fw-bold">Old PM</label>
                            <input type="text" class="form-control form-control-sm" id="FieldOldPM" value="<?php echo trim($RDataDetailWO['PM']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="FieldNewPM" class="form-label fw-bold">New PM</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm" id="FieldNewPM"><?php 
                                    foreach($ArrListUser as $ListUser)
                                    {
                                        if(trim($ListUser['Title']) == "PM")
                                        {
                                            if(trim($ListUser['FN']) == trim($RDataDetailWO['PM']))
                                            {
                                                echo '<option selected>'.trim($ListUser['FN']).'</option>';
                                            }
                                            else
                                            {
                                                echo '<option>'.trim($ListUser['FN']).'</option>';
                                            }
                                        }
                                    }
                                ?></select>
                            </div>            
                        </div>
                    </div>
                    <div class="col-md-12 pt-2">
                        <button class="btn btn-sm btn-success" id="BtnUpdatePM" data-temp="<?php echo $ValDataTemp; ?>">Update PM</button>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="FieldOldCOPM" class="form-label fw-bold">Old CO PM</label>
                            <input type="text" class="form-control form-control-sm" id="FieldOldCOPM" value="<?php echo trim($RDataDetailWO['CO_PM']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="FieldNewCOPM" class="form-label fw-bold">New CO PM</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm" id="FieldNewCOPM"<?php echo $BlockCOPM; ?>><?php 
                                    foreach($ArrListUser as $ListUser)
                                    {
                                        if(trim($ListUser['Title']) == "CO PM")
                                        {
                                            if(trim($ListUser['FN']) == trim($RDataDetailWO['CO_PM']))
                                            {
                                                echo '<option selected>'.trim($ListUser['FN']).'</option>';
                                            }
                                            else
                                            {
                                                echo '<option>'.trim($ListUser['FN']).'</option>';
                                            }
                                        }
                                    }
                                ?></select>
                            </div>            
                        </div>
                    </div>
                    <div class="col-md-12 pt-2">
                        <button class="btn btn-sm btn-warning" id="BtnUpdateCOPM" data-temp="<?php echo $ValDataTemp; ?>"<?php echo $BlockCOPM; ?>>Update CO PM</button>                        
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="FieldOldDM" class="form-label fw-bold">Old DM</label>
                            <input type="text" class="form-control form-control-sm" id="FieldOldDM" value="<?php echo trim($RDataDetailWO['DM']); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="FieldNewDM" class="form-label fw-bold">New DM</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm" id="FieldNewDM"><?php 
                                    foreach($ArrListUser as $ListUser)
                                    {
                                        if(trim($ListUser['Title']) == "DM")
                                        {
                                            if(trim($ListUser['FN']) == trim($RDataDetailWO['DM']))
                                            {
                                                echo '<option selected>'.trim($ListUser['FN']).'</option>';
                                            }
                                            else
                                            {
                                                echo '<option>'.trim($ListUser['FN']).'</option>';
                                            }
                                        }
                                    }
                                ?></select>
                            </div>            
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-md-12 pt-2">
                        <button class="btn btn-sm btn-info" id="BtnUpdateDM" data-temp="<?php echo $ValDataTemp; ?>">Update DM</button>
                    </div>
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-4 pt-2" id="InfoUpdatePMDM"></div>
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