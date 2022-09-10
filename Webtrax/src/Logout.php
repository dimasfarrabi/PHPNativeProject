<?php 
session_start();
unset($_SESSION['UIDWebTrax']);
unset($_SESSION['LoginMode']);
unset($_SESSION['SecurityCamID']);
session_unregister("UIDWebTrax");
session_unregister("LoginMode");
session_unregister("SecurityCamID");
session_unset();
session_destroy();
header("location:https://webtrax.formulatrix.com/");
exit();

?>
