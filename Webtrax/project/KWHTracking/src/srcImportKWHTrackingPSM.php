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
                    if(count($row) == "4")
                    {
						# pengambilan data upload
						$TempSlave = preg_replace("/[\xA0\xC2]/", "",$row[0]);
						$TempTimeLog = preg_replace("/[\xA0\xC2]/", "",$row[1]);
						$TempDateLog = preg_replace("/[\xA0\xC2]/", "",$row[2]);
						$TempKWHLog = preg_replace("/[\xA0\xC2]/", "",$row[3]);
                        $TempSlave = strtoupper($TempSlave);
                        $TempDateLog = date("m/d/Y",strtotime($TempDateLog));
                        if($TempKWHLog == "")
                        {
                            $TempKWHLog = 0;
                        }

                        // echo "Slave : ".$TempSlave.". TimeLog : ".$TempTimeLog.". DateLog : ".$TempDateLog.". KWHLog : ".$TempKWHLog.".<br>";
                        if(trim($TempSlave) != "" && trim($TempTimeLog) != "" && trim($TempDateLog) != "" && trim($TempKWHLog) != "")
                        {
                            # hapus data
                            DELETE_IMPORT_KWH_TRACKING_PSM($TempDateLog,$TempSlave,$linkHRISWebTrax);
                            // # simpan data
                            IMPORT_KWH_TRACKING_PSM($TempSlave,$TempTimeLog,$TempDateLog,$TempKWHLog,$linkHRISWebTrax);
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
                    $_SESSION['ImportKWHTrackingPSM'] = '<script language="javascript">$(document).ready(function() { alert("Proses upload file sudah selesai!");});</script>';
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