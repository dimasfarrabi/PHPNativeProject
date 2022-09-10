<?php
session_start();
if(!isset($_SESSION['UIDWebtrax']))
{
	header("location:home.php");
    exit();
}
$warning = "";
if(isset($_SESSION['StatusLogin']))
{
    $warning = $_SESSION['StatusLogin'];
    unset($_SESSION['StatusLogin']);
}

?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<title>WEBTRAX</title>
<link href="../images/favicon.ico" rel="icon" title="WebTrax">
<link rel="stylesheet" href="../forindotracking/Bootstrap-3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/custom.css">
<link rel="stylesheet" href="style/custom.css?no=<?php echo base64_encode(date("mdyHis")); ?>">
<link rel="stylesheet" href="../libs/datatables_1.10.19/css/jquery.dataTables.min.css">
<script src="../forindotracking/js/jquery-1.12.0.min.js"></script>
<script src="lib/login.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<?php //<script src="../libs/datatables_1.10.19/js/jquery.dataTables.min.js"></script> ?>
</head>
<body>
<div class="bg-main">
    <nav class="navbar navbar-inverse navbar-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#"><img id="img-logo" src="../images/final logo white.png" alt="logo"/></a>
            </div>
        </div>
    </nav>
    <div class="container-fluid container-index">
        <div class="form-login">
		<legend class="legend-custom">WEBTRAX IS UNDER MAINTENANCE</legend>
        <!-- <div class="form-group">
            <input type="text" class="form-control" name="username" id="TxtUsername" placeholder="Username" autofocus/>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" id="TxtPassword" placeholder="Password" />
        </div>
        <div class="form-group">
            <button class="btn btn-md btn-dark btn-block" id="BtnLogin" type="submit">Login</button>
        </div> -->
        <div class="form-group infoLogin">*) We'll be back at 3:00 PM</div>
        <!-- <div class="form-group">
            <img src="../images/ajax-loader1.gif" id="LoadingLogin" class="load_img"/>
        </div>
        <div id="NotificationLog"></div>
		<?php echo $warning; ?></div> -->
    </div>
</div>




<script src="../forindotracking/Bootstrap-3.3.6/js/bootstrap.min.js"></script>
</body>
</html>
