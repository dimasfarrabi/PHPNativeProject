<?php 
session_start();
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleKWHTracking.php");

if(!session_is_registered("UIDWebTrax"))
{
    header("location:https://webtrax.formulatrix.com/");
    exit();
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
                            // echo "<br>".$TempDateLog." ".$TempKWHLog;
                            $ArrFormatDate = explode("/",$TempDateLog);
                            $NewDate = $ArrFormatDate[1]."/".$ArrFormatDate[0]."/".$ArrFormatDate[2];
                            $TempDateLog = $NewDate;
                            # check data
                            $QCheck = CHECK_DATA_USAGE_BY_DATE("Slave 1",$TempDateLog,"PSM",$linkHRISWebTrax);    
                            $RowCheck = mssql_num_rows($QCheck);
                            if($RowCheck == "0")
                            {
                                # insert data
                                INSERT_NEW_DATA_USAGE("Slave 1",$TempDateLog,$TempKWHLog,"PSM",$linkHRISWebTrax);
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
                            $_SESSION['ImportKWHTrackingPSM'] = '<script language="javascript">$(document).ready(function() { alert("Baris ke '.$Baris.' tidak valid!");});</script>';  
                        }
                    }
                    else
					{
                        $BolCheck = FALSE;
						# set session
                        $_SESSION['ImportKWHTrackingPSM'] = '<script language="javascript">$(document).ready(function() { alert("Jumlah kolom tidak sesuai!");});</script>';
					}
					$Baris = $Baris + 1;
                }
                fclose($CSVFile);
				# check boolean
                if($BolCheck == TRUE)
                {
                    // $_SESSION['ImportKWHTrackingPSM'] = '<script language="javascript">$(document).ready(function() { alert("Proses upload file sudah selesai!");});</script>';
                    $_SESSION['ImportKWHTrackingPSM'] = '<script language="javascript">$(document).ready(function() { GoToGenerate2();});</script>';
                }
                # redirect
                ?>
                 <script language="javascript">
                    window.location.replace("https://webtrax.formulatrix.com/home.php?link=11");
                 </script>
                <?php
            }
            else
			{
                # redirect
                $_SESSION['ImportKWHTrackingPSM'] = '<script language="javascript">$(document).ready(function() { alert("File gagal diimport!");});</script>';
                ?>
                <script language="javascript">
                    window.location.replace("https://webtrax.formulatrix.com/home.php?link=11";
                </script>
                <?php
			}
        }
        else
		{
			# redirect
            $_SESSION['ImportKWHTrackingPSM'] = '<script language="javascript">$(document).ready(function() { alert("Jenis file tidak valid!");});</script>';
            ?>
            <script language="javascript">
                window.location.replace("https://webtrax.formulatrix.com/home.php?link=11");
            </script>
            <?php
		}
    }
	elseif($FileSize < 1)
	{
		# redirect
		$_SESSION['ImportKWHTrackingPSM'] = '<script language="javascript">$(document).ready(function() { alert("File tidak ditemukan!");});</script>';
        ?>
        <script language="javascript">
            window.location.replace("https://webtrax.formulatrix.com/home.php?link=11");
        </script>
        <?php
	}
	else
	{
		# redirect
		$_SESSION['ImportKWHTrackingPSM'] = '<script language="javascript">$(document).ready(function() { alert("Ukuran data terlalu besar!");});</script>';
        ?>
        <script language="javascript">
            window.location.replace("https://webtrax.formulatrix.com/home.php?link=11");
        </script>
        <?php
	}
}
else
{
    echo "";    
}
?>