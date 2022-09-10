<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleInOut.php");
/*
date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
 
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
$FullName = "LOCAL - DIMAS FARRABI";
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValCode = htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8");
    $Delete = DELETE_TEMP($ValCode,$linkMACHWebTrax);
    if($Delete == "TRUE"){
        ?>
        <script language="javascript">
            setTimeout(myFunction, 300);
            function myFunction()
            {
                var XX = '<?php echo $ValCode; ?>';
                $("#ScanInInfo tr[data-erows='" + XX + "']").remove();
                $('#DeleteTemp').modal('hide');
            }
        </script>
        <?php
    }
    else{
        echo '<div class="alert alert-danger fw-bold" id="DangerBar2" role="alert">Delete Failed</div>';
    }
}
?>
