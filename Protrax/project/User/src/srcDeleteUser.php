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
if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $Valkey = htmlspecialchars(trim($_GET['key']), ENT_QUOTES, "UTF-8");
    $ValID = base64_decode(base64_decode(trim($Valkey)));
    # delete data
    DELETE_PROTRAX_USER($ValID,$linkHRISWebTrax);
    ?>
	<script language="javascript">
	window.location.href = "https://protrax.formulatrix.com/home.php?link=10";
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
