<?php
session_start();
if(session_is_registered("UIDWebTrax"))
{
	header("location:home.php");
    exit();
}
require("../../src/srcConnect.php");
require("../../src/srcProcessFunction.php");
require("../../src/srcFunction.php");
require("Modules/ModuleLogin.php");
date_default_timezone_set("Asia/Jakarta");
$TimeNow = date("Y-m-d H:i:s");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $User = htmlspecialchars(trim($_POST['User']), ENT_QUOTES, "UTF-8");
    $Pass = htmlspecialchars(trim($_POST['Pass']), ENT_QUOTES, "UTF-8");
    # check login 
    $StatusReguler = FALSE;
    $StatusSuperUser = FALSE;
    $StatusGuest = FALSE;
    $EncPass = md5($Pass);
    $QDataLoginReguler = LOGIN_REGULER_ID($User,$Pass,$linkHRISWebTrax);
    $RowDataLoginReguler = mssql_num_rows($QDataLoginReguler);
    $QDataLoginSuperUser = LOGIN_SUPERUSER_ID($User,$EncPass,$linkHRISWebTrax);
    $RowDataLoginSuperUser = mssql_num_rows($QDataLoginSuperUser);
    $QDataLoginGuest = LOGIN_GUEST_ID($User,$EncPass,$linkHRISWebTrax);
    $RowDataLoginGuest = mssql_num_rows($QDataLoginGuest);

    if($RowDataLoginReguler != "0")
    {
        $StatusReguler = TRUE;
    }
    if($RowDataLoginSuperUser != "0")
    {
        $StatusSuperUser = TRUE;
    }
    if($RowDataLoginGuest != "0")
    {
        $StatusGuest = TRUE;
    }

    if($StatusReguler == FALSE && $StatusSuperUser == FALSE && $StatusGuest == FALSE)
    {
        echo '<div class="form-group"><div class="alert alert-danger alert-dismissible text-center" role="alert">User not found!</div></div>';
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#BtnLogin').attr('disabled', false);
            });
        </script>
        <?php
    }
    else
    {
        if($StatusReguler == TRUE)
        {
            # reguler
            $RDataLoginReguler = mssql_fetch_assoc($QDataLoginReguler);
            $NIKOnlineID = $RDataLoginReguler['NIK'];
            $EmpCounterOnlineID = $RDataLoginReguler['EmployeeCounter'];
            $ArrNIKSorting = explode(".",$NIKOnlineID);
            $NIKSorting = (int)$ArrNIKSorting[1];
            # data employee
            $QDataEmployeeLogin = GET_DATA_EMPLOYEE_LOGIN($NIKSorting,$EmpCounterOnlineID,$linkHRISWebTrax);
            $RDataEmployeeLogin = mssql_fetch_assoc($QDataEmployeeLogin);
            $EmployeeCounter = $RDataEmployeeLogin['EmployeeCounter'];
            $EncEmployeeCounter = base64_encode($EmployeeCounter);
            $_SESSION['UIDWebTrax'] = base64_encode($EncEmployeeCounter);
            $_SESSION['LoginMode'] = base64_encode("Reguler");
        }
        if($StatusSuperUser == TRUE)
        {
            # superuser
            $RDataLoginSuperUser = mssql_fetch_assoc($QDataLoginSuperUser);
            $NIKOnlineID = $RDataLoginSuperUser['NIK'];
            $ArrNIKSorting = explode(".",$NIKOnlineID);
            $NIKSorting = (int)$ArrNIKSorting[1];
            # data employee
            $QDataEmployeeLogin = GET_DATA_EMPLOYEE_LOGIN($NIKSorting,"",$linkHRISWebTrax);
            $RDataEmployeeLogin = mssql_fetch_assoc($QDataEmployeeLogin);
            $EmployeeCounter = $RDataEmployeeLogin['EmployeeCounter'];
            $EncEmployeeCounter = base64_encode($EmployeeCounter);
            $_SESSION['UIDWebTrax'] = base64_encode($EncEmployeeCounter);
            $_SESSION['LoginMode'] = base64_encode("Administrator");
        }
        if($StatusGuest == TRUE)
        {
            # guest 
            $RDataLoginGuest = mssql_fetch_assoc($QDataLoginGuest);
            $IDGuest = $RDataLoginGuest['Idx'];
            # update last login guest
            UPDATE_LAST_LOGIN_GUEST($IDGuest,$TimeNow,$linkHRISWebTrax);
            $_SESSION['UIDWebTrax'] = base64_encode(base64_encode($IDGuest));
            $_SESSION['LoginMode'] = base64_encode("Guest");
        }
        $_SESSION['SecurityCamID'] = base64_encode(base64_encode(GET_SECURITY_CAM_ID($linkHRISWebTrax)));

        echo '<div class="form-group"><div class="alert alert-success alert-dismissible text-center" role="alert">Login Successful!</div></div>';
        # redirect
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#BtnLogin').attr('disabled', true);
                window.location.href = 'home.php';
            });
        </script>
        <?php
    }
}
else
{
    echo "Anda tidak mempunyai hak akses!";
}
?>
