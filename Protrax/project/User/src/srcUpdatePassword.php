<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleUser.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
 
if(!session_is_registered("UIDProTrax"))
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
    $ValEncID = htmlspecialchars(trim($_POST['ValID']), ENT_QUOTES, "UTF-8");
    $ValID = base64_decode(base64_decode($ValEncID));
    $ValPassword = htmlspecialchars(trim($_POST['ValPassword']), ENT_QUOTES, "UTF-8");
    $ValEncPassword = md5($ValPassword);
    
    UPDATE_PASSWORD_USER_PROTRAX($ValID,$ValEncPassword,$linkHRISWebTrax);
    ?>
    <script>
        $(document).ready(function () {
            location.reload();
        });
    </script>
    <?php
}
else
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();  
}
?>
