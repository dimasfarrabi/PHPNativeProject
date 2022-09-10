<?php
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleKelolaGuest.php");
date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
 
session_start();
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValID = htmlspecialchars(trim($_POST['ValID']), ENT_QUOTES, "UTF-8");
    $ValNew = htmlspecialchars(trim($_POST['ValNew']), ENT_QUOTES, "UTF-8");
    $ValNew = md5($ValNew);
    $ValID = base64_decode(base64_decode($ValID));
    UPDATE_PASSWORD_GUEST($ValID,$ValNew,$linkHRISWebTrax);
	// echo $ValID." >>> ".$ValNew;
}
else
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();  
}
?>
