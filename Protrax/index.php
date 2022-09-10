<?php
session_start();
// if(session_is_registered(""))
// {
	header("location:home.php");
    exit();
// }/
// $warning = "";
// if(isset($_SESSION['StatusLogin']))
// {
//     $warning = $_SESSION['StatusLogin'];
//     unset($_SESSION['StatusLogin']);
// }

?><!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<title>PROTRAX</title>
<link rel="icon" href="images/favicon.ico" title="ProTrax">
<link rel="stylesheet" href="lib/bootstrap-5.0.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="style/custom.css?no=<?php echo base64_encode(date("mdyHis")); ?>">
<script src="lib/js/jquery-3.6.0.min.js"></script>
<script src="lib/js/login.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
</head>
<body class="body-content">
<section class="vh-100 gradient-custom">
    <div class="container py-5 h-50">
        <div class="row d-flex justify-content-center align-items-center h-50">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white card-form card-login">
                <div class="card-body p-5 text-center">
                    <div class="mb-md-5">
						<img id="img-logo-form" class="img-fluid" src="images/final logo white.png" alt="logo"/>
						<hr>
						<h3 class="fw-bold mb-2 text-uppercase">PROTRAX</h3>
						<p class="text-white-50 mb-4">*) Please login with your TIGA account!</p>
						<form>
							<div class="form-outline form-white margin-form mb-4 w-75">
								<input type="text" id="TxtUsername" class="form-control form-control-sm" placeholder="Username" autofocus/>
							</div>
							<div class="form-outline form-white margin-form mb-4 w-75">
								<input type="password" id="TxtPassword" class="form-control form-control-sm" autocomplete="off" placeholder="Password" />
							</div>
							<button class="btn btn-outline-light btn-lg px-5" id="BtnLogin" type="submit">LOGIN</button>
						</form>
                    </div>
                </div>
                </div>
            </div>      
        </div>
    </div>
</section>
<script src="lib/bootstrap-5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
