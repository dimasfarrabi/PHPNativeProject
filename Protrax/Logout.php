<?php 
session_start();
unset($_SESSION['UIDProTrax']);
unset($_SESSION['LoginModeProTrax']);
unset($_SESSION['FullNameUserProTrax']);
unset($_SESSION['SecurityCamIDProTrax']);
session_unregister("UIDProTrax");
session_unregister("LoginModeProTrax");
session_unregister("FullNameUserProTrax");
session_unregister("SecurityCamIDProTrax");
session_unset();
session_destroy();
header("location:./");
exit();

?>
