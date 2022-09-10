<?php
require_once("../../../src/Modules/ModuleLogin.php");
require("../../../../src/srcProcessFunction.php");
require_once("../Modules/ModuleTarget.php");
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
    $DataID = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $DataID = str_replace("ID","",base64_decode(base64_decode($DataID)));
    # delete data
    DELETE_QTY_TARGET($DataID,$linkMACHWebTrax);
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
