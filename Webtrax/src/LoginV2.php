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
    
    $ApiVersion = GET_LOGIN_API_VERSION($linkHRISWebTrax);     // settingan API TIGA
    //$ApiVersion = '6.5.0.1';     

    $CheckUser = LOGIN_BY_TIGA($ApiVersion,$User,$Pass);
    $ResultUsername = trim($CheckUser['GetAuthorizationResult']['UserCredential']['Username']);
    if($ResultUsername != "")
    {
        $ValUsernameTIGA = $ResultUsername;
        $ValNameTIGA = trim($CheckUser['GetAuthorizationResult']['UserCredential']['Fullname']);
        # check sbg webtrax user
        $QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($ValUsernameTIGA,$linkHRISWebTrax);
        if(mssql_num_rows($QDataUserWebtrax) != "0")
        {
            # check jika superuser SIK
            $QDataCheckSuperUser = CHECK_AS_SUPERUSER_ADMIN($ValUsernameTIGA,$linkHRISWebTrax);
            if(mssql_num_rows($QDataCheckSuperUser) != "0")
            {
                $_SESSION['LoginMode'] = base64_encode("Administrator");
                $_SESSION['FullNameUser'] = base64_encode($ValNameTIGA);
                $_SESSION['UIDWebTrax'] = base64_encode(base64_encode($ValUsernameTIGA));
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
            else
            {
                $_SESSION['LoginMode'] = base64_encode("Guest");
                $_SESSION['FullNameUser'] = base64_encode($ValNameTIGA);
                $_SESSION['UIDWebTrax'] = base64_encode(base64_encode($ValUsernameTIGA));
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
        else # tidak terdaftar di webtrax tp terdaftar di TIGA
        {
            $_SESSION['LoginMode'] = base64_encode("Guest");
            $_SESSION['FullNameUser'] = base64_encode($ValNameTIGA);
            $_SESSION['UIDWebTrax'] = base64_encode(base64_encode($ValUsernameTIGA));
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
    else # tidak terdaftar di TIGA
    {
        $StatusSuperUser = FALSE;
        $StatusGuest = FALSE;
        $EncPass = md5($Pass);
        $QDataLoginGuest = LOGIN_GUEST_ID($User,$EncPass,$linkHRISWebTrax);
        $RowDataLoginGuest = mssql_num_rows($QDataLoginGuest);
        $QDataLoginSuperUser = LOGIN_SUPERUSER_ID_2($User,$EncPass,$linkHRISWebTrax);
        $RowDataLoginSuperUser = mssql_num_rows($QDataLoginSuperUser);
        if($RowDataLoginGuest != "0")
        {
            $StatusGuest = TRUE;
        }
        if($RowDataLoginSuperUser != "0")
        {
            $StatusSuperUser = TRUE;
        }
        if($StatusSuperUser == TRUE && $StatusGuest == TRUE)
        {
            $RDataLoginSuperUser = mssql_fetch_assoc($QDataLoginSuperUser);
            $_SESSION['FullNameUser'] = base64_encode(trim($RDataLoginSuperUser['FullName']));
            $_SESSION['UIDWebTrax'] = base64_encode(base64_encode(trim($RDataLoginSuperUser['su_username'])));
            $_SESSION['LoginMode'] = base64_encode("Administrator");
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
        elseif ($StatusSuperUser == TRUE && $StatusGuest == FALSE)
        {
            $RDataLoginSuperUser = mssql_fetch_assoc($QDataLoginSuperUser);
            $_SESSION['FullNameUser'] = base64_encode(trim($RDataLoginSuperUser['FullName']));            
            $_SESSION['UIDWebTrax'] = base64_encode(base64_encode(trim($RDataLoginSuperUser['su_username'])));
            $_SESSION['LoginMode'] = base64_encode("Administrator");
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
        elseif ($StatusSuperUser == FALSE && $StatusGuest == TRUE)
        {
            $RDataLoginGuest = mssql_fetch_assoc($QDataLoginGuest);
            $_SESSION['FullNameUser'] = base64_encode(trim($RDataLoginGuest['FullName']));
            $_SESSION['UIDWebTrax'] = base64_encode(base64_encode(trim($RDataLoginGuest['username'])));
            $_SESSION['LoginMode'] = base64_encode("Guest");
            $_SESSION['SecurityCamID'] = base64_encode(base64_encode(GET_SECURITY_CAM_ID($linkHRISWebTrax)));
            $IDGuest = $RDataLoginGuest['Idx'];
            UPDATE_LAST_LOGIN_GUEST($IDGuest,$TimeNow,$linkHRISWebTrax);
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
        else 
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
    }
}
else
{
    echo "Access denied!";
}
?>
