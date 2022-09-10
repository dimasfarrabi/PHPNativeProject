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

if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $ValID = htmlspecialchars(trim($_GET['key']), ENT_QUOTES, "UTF-8");
    $ValID = base64_decode(base64_decode($ValID));
    DELETE_DATA_GUEST($ValID,$linkHRISWebTrax);    
    ?>
	<script language="javascript">
	window.location.href = "https://webtrax.formulatrix.com/home.php?link=10";
	</script>
	<?php
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
