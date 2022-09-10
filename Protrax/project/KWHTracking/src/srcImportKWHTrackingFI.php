<?php 
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleKWHTracking.php");

if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
# data protrax user
$BolProdAcc = false;
$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
if(mssql_num_rows($QDataUserWebtrax) > 0)
{
    $RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
    $TypeUser = trim($RDataUserWebtrax['TypeUser']);
    $_SESSION['LoginMode'] = base64_encode($TypeUser);
    $AccessLogin = base64_decode($_SESSION['LoginMode']);   
}
else # kondisi tidak terdaftar di protrax user & akan di set sebagai employee dan hak akses ke bagian produksi saja
{
    $_SESSION['LoginMode'] = base64_encode("Employee");
    $AccessLogin = base64_decode($_SESSION['LoginMode']);
    $BolProdAcc = true;
}

if((trim($AccessLogin) != "Manager"))
{
    if($RDataUserWebtrax['MnAdmin'] != "1")  
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}
else
{
    if($RDataUserWebtrax['MnSecurity'] != "1")
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $DataName = $_FILES['InputFile']['name'];
	$FileSize = $_FILES['InputFile']['size'];
	$DataFile = $_FILES['InputFile']['tmp_name'];
	$ArrayCheckData = array();
	$BolCheck = TRUE;
    # pengecekan ukuran file
	if($FileSize > 0 && $FileSize <= 1000000)
    {
        $CSVMimes = array('text/x-comma-separated-values','text/comma-separated-values','application/octet-stream','application/vnd.ms-excel','application/x-csv','text/x-csv','text/csv','application/csv','application/excel','application/vnd.msexcel','text/plain');
		# check jenis file
        if(!empty($DataName) && in_array($_FILES['InputFile']['type'],$CSVMimes))
        {
            if(is_uploaded_file($DataFile))
            {
                $CSVFile = fopen($DataFile,'r');
  				fgetcsv($CSVFile);
				$Baris = 2;
                $BolCheck == TRUE;
                while(($row = fgetcsv($CSVFile)) !== FALSE)
                {
                    # pengecekan jumlah kolom file
                    if(count($row) == "2")
                    {
						# pengambilan data upload
						$TempDateLog = preg_replace("/[\xA0\xC2]/", "",$row[0]);
						$TempKWHLog = preg_replace("/[\xA0\xC2]/", "",$row[1]);                        
                        if(trim($TempDateLog) != "" && trim($TempKWHLog) != "")
                        {
                            $ArrFormatDate = explode("/",$TempDateLog);
                            $NewDate = $ArrFormatDate[1]."/".$ArrFormatDate[0]."/".$ArrFormatDate[2];
                            $TempDateLog = $NewDate;
                            # check data
                            $QCheck = CHECK_DATA_USAGE_BY_DATE("Slave 1",$TempDateLog,"FI",$linkHRISWebTrax);    
                            $RowCheck = mssql_num_rows($QCheck);
                            if($RowCheck == "0")
                            {
                                # insert data
                                INSERT_NEW_DATA_USAGE("Slave 1",$TempDateLog,$TempKWHLog,"FI",$linkHRISWebTrax);
                            }
                            else
                            {
                                # get data
                                $RCheck = mssql_fetch_assoc($QCheck);
                                $IDData = trim($RCheck['Idx']);
                                # update data
                                UPDATE_DATA_USAGE($IDData,$TempKWHLog,$linkHRISWebTrax);
                            }
                        }
                        else
                        {
                            $BolCheck = FALSE;
                            # set session
                            $_SESSION['ImportKWHTrackingFI'] = '<script language="javascript">$(document).ready(function() { alert("Baris ke '.$Baris.' tidak valid!");});</script>';  
                        }
                    }
                    else
					{
                        $BolCheck = FALSE;
						# set session
                        $_SESSION['ImportKWHTrackingFI'] = '<script language="javascript">$(document).ready(function() { alert("Jumlah kolom tidak sesuai!");});</script>';
					}
					$Baris = $Baris + 1;
                }
                fclose($CSVFile);
				# check boolean
                if($BolCheck == TRUE)
                {
                    $_SESSION['ImportKWHTrackingFI'] = '<script language="javascript">$(document).ready(function() {GoToGenerate2();});</script>';
                }
                # redirect
                ?>
                <script language="javascript">
                    // window.location.replace("https://protrax.formulatrix.com/home.php?link=3");
                    window.location.replace("http://localhost:8080/protrax/home.php?link=3");
                </script>
                <?php
            }
            else
			{
                # redirect
                $_SESSION['ImportKWHTrackingFI'] = '<script language="javascript">$(document).ready(function() { alert("File gagal diimport!");});</script>';
                ?>
                <script language="javascript">
                    // window.location.replace("https://protrax.formulatrix.com/home.php?link=3";
                    window.location.replace("http://localhost:8080/protrax/home.php?link=3");
                </script>
                <?php
			}
        }
        else
		{
			# redirect
            $_SESSION['ImportKWHTrackingFI'] = '<script language="javascript">$(document).ready(function() { alert("Jenis file tidak valid!");});</script>';
            ?>
            <script language="javascript">
                // window.location.replace("https://protrax.formulatrix.com/home.php?link=3");
                window.location.replace("http://localhost:8080/protrax/home.php?link=3");
            </script>
            <?php
		}
    }
	elseif($FileSize < 1)
	{
		# redirect
		$_SESSION['ImportKWHTrackingFI'] = '<script language="javascript">$(document).ready(function() { alert("File tidak ditemukan!");});</script>';
        ?>
        <script language="javascript">
            // window.location.replace("https://protrax.formulatrix.com/home.php?link=3");
            window.location.replace("http://localhost:8080/protrax/home.php?link=3");
        </script>
        <?php
	}
	else
	{
		# redirect
		$_SESSION['ImportKWHTrackingFI'] = '<script language="javascript">$(document).ready(function() { alert("Ukuran data terlalu besar!");});</script>';
        ?>
        <script language="javascript">
            // window.location.replace("https://protrax.formulatrix.com/home.php?link=3");
            window.location.replace("http://localhost:8080/protrax/home.php?link=3");
        </script>
        <?php
	}
}
else
{
    echo "";    
}
?>