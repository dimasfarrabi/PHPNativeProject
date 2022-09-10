<?php
session_start();
if(session_is_registered("UIDProTrax"))
{
	header("location:home.php");
    exit();
}
require("../ConfigDB.php");
require("Modules/ModuleLogin.php");
date_default_timezone_set("Asia/Jakarta");
$TimeNow = date("Y-m-d H:i:s");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $User = htmlspecialchars(trim($_POST['User']), ENT_QUOTES, "UTF-8");
    $Pass = htmlspecialchars(trim($_POST['Pass']), ENT_QUOTES, "UTF-8");    
    # config var    
    $ValUsernameTIGA = "";
    $ValNameTIGA = "";
    $EncPass = md5($Pass);
    # check API TIGA 
    $ApiVersion = GET_LOGIN_API_VERSION($linkHRISWebTrax);
    # check login
    $BolTiga = False;
    $CheckUser = LOGIN_BY_TIGA($ApiVersion,$User,$Pass);
    $ResultUsername = trim($CheckUser['GetAuthorizationResult']['UserCredential']['Username']);
    if($ResultUsername != "") # jika terdaftar di TIGA
    {
        $ValUsernameTIGA = $ResultUsername;
        $ValNameTIGA = trim($CheckUser['GetAuthorizationResult']['UserCredential']['Fullname']);
		$BolTiga = True;
    }
	if($BolTiga == True)
	{
		# check sbg protrax user
		$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($User,$linkHRISWebTrax);
		if(mssql_num_rows($QDataUserWebtrax) > 0) # terdaftar di protrax user
		{
			$RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
			$_SESSION['FullNameUserProTrax'] = base64_encode(trim($RDataUserWebtrax['FullName']));
			$_SESSION['UIDProTrax'] = base64_encode(base64_encode(trim($RDataUserWebtrax['username'])));
			$_SESSION['SecurityCamIDProTrax'] = base64_encode(base64_encode(GET_SECURITY_CAM_ID($linkHRISWebTrax)));
			$IDGuest = $RDataUserWebtrax['Idx'];
			UPDATE_LAST_LOGIN_GUEST($IDGuest,$TimeNow,$linkHRISWebTrax);
			echo '<br><div class="alert alert-success fw-bold" id="InfoLogin" role="alert">Login Successful!</div>';
			# redirect
			?>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#TxtUsername").attr("disabled",true);
					$("#TxtPassword").attr("disabled",true);
					$("#BtnLogin").attr("disabled", true);
					window.location.href = 'home.php';
				});
			</script>
			<?php
		}
		else # tidak terdaftar di protrax user
		{
			if($ValUsernameTIGA != "")
			{
				$_SESSION['FullNameUserProTrax'] = base64_encode($ValNameTIGA);
				$_SESSION['UIDProTrax'] = base64_encode(base64_encode($ValUsernameTIGA));
				$_SESSION['SecurityCamIDProTrax'] = base64_encode(base64_encode(GET_SECURITY_CAM_ID($linkHRISWebTrax)));
				echo '<br><div class="alert alert-success fw-bold" id="InfoLogin" role="alert">Login Successful!</div>';
				# redirect
				?>
				<script type="text/javascript">
					$(document).ready(function(){
						$("#TxtUsername").attr("disabled",true);
						$("#TxtPassword").attr("disabled",true);
						$("#BtnLogin").attr("disabled", true);
						window.location.href = 'home.php';
					});
				</script>
				<?php
			}
			else
			{
				echo '<br><div class="alert alert-danger fw-bold" id="InfoLogin" role="alert">User not found!</div>';
				?>
				<script type="text/javascript">
					$(document).ready(function(){
						$("#TxtUsername").attr("disabled",false);
						$("#TxtPassword").attr("disabled",false);
						$("#BtnLogin").attr("disabled", false);
					});
				</script>
				<?php
			}
		}
	}
	else
	{
		# check sbg protrax user
		$QDataUserWebtrax = LOGIN_GUEST_ID($User,$EncPass,$linkHRISWebTrax);
		if(mssql_num_rows($QDataUserWebtrax) > 0) # terdaftar di protrax user
		{
			$RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
			$_SESSION['FullNameUserProTrax'] = base64_encode(trim($RDataUserWebtrax['FullName']));
			$_SESSION['UIDProTrax'] = base64_encode(base64_encode(trim($RDataUserWebtrax['username'])));
			$_SESSION['SecurityCamIDProTrax'] = base64_encode(base64_encode(GET_SECURITY_CAM_ID($linkHRISWebTrax)));
			$IDGuest = $RDataUserWebtrax['Idx'];
			UPDATE_LAST_LOGIN_GUEST($IDGuest,$TimeNow,$linkHRISWebTrax);
			echo '<br><div class="alert alert-success fw-bold" id="InfoLogin" role="alert">Login Successful!</div>';
			# redirect
			?>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#TxtUsername").attr("disabled",true);
					$("#TxtPassword").attr("disabled",true);
					$("#BtnLogin").attr("disabled", true);
					window.location.href = 'home.php';
				});
			</script>
			<?php
		}
		else # tidak terdaftar di protrax user
		{
			echo '<br><div class="alert alert-danger fw-bold" id="InfoLogin" role="alert">User not found!</div>';
			?>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#TxtUsername").attr("disabled",false);
					$("#TxtPassword").attr("disabled",false);
					$("#BtnLogin").attr("disabled", false);
					$("#TxtPassword").val("");
					$("#TxtPassword").focus();
				});
			</script>
			<?php
		}
	}
}
else
{
    echo "Access denied!";
}
?>
