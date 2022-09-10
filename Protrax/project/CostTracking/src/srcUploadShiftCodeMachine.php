<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleShiftCodeMachine.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
$DateNow = date("m/d/Y");
 
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $DataName = $_FILES['UploadFileShiftCode']['name'];
	$DataSize = $_FILES['UploadFileShiftCode']['size'];
	$DataFile = $_FILES['UploadFileShiftCode']['tmp_name'];
	$ArrayCheckData = array();
    $BolCheck = TRUE;
    # pengecekan ukuran file
    if($DataSize > 0 && $DataSize <= 1000000)
    {
        $CSVMimes = array('text/x-comma-separated-values','text/comma-separated-values','application/octet-stream','application/vnd.ms-excel','application/x-csv','text/x-csv','text/csv','application/csv','application/excel','application/vnd.msexcel','text/plain');
        # check jenis file
        if(!empty($DataName) && in_array($_FILES['UploadFileShiftCode']['type'],$CSVMimes))
        {
            if(is_uploaded_file($DataFile))
            {
                $CSVFile = fopen($DataFile,'r');
  				fgetcsv($CSVFile);
				$Baris = 2;
                while(($row = fgetcsv($CSVFile)) !== FALSE)
                {
                    # pengecekan jumlah kolom file
					if(count($row) == "4")
                    {
						# pengambilan data upload
						$TempLocation = preg_replace("/[\xA0\xC2]/", "",$row[0]);
						$TempMachine = preg_replace("/[\xA0\xC2]/", "",$row[1]);
                        $TempDate = preg_replace("/[\xA0\xC2]/", "",$row[2]);
                        $TempHour = preg_replace("/[\xA0\xC2]/", "",$row[3]);
                        # check valid inputan
                        if(trim($TempLocation) == "" || trim($TempMachine) == "" || trim($TempDate) == "" || trim($TempHour) == "")
                        {
							$BolCheck = FALSE;
                            # redirect
                            $_SESSION['InfoUploadShiftCodeMachine'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada yang kosong!"); });</script>';
                            header("location:../../../home.php?link=22");
                            exit();
                        }
                        # check valid lokasi
                        if(trim($TempLocation) != "SALATIGA" && trim($TempLocation) != "SEMARANG")
                        {
                            $BolCheck = FALSE;
                            # redirect
                            $_SESSION['InfoUploadShiftCodeMachine'] = '<script language="javascript">$(document).ready(function() { alert("Lokasi baris ke '.$Baris.' tidak terdaftar!"); });</script>';
                            header("location:../../../home.php?link=22");
                            exit();
                        }
                        # input ke array
                        $ArrayTemp = array(
                            "NoLoop" => $Baris,
                            "Location" => strtoupper(trim($TempLocation)),
                            "Machine" => trim($TempMachine),
                            "Date" => trim($TempDate),
                            "UtilizeHours" => (int)trim($TempHour),
                        );
                        array_push($ArrayCheckData,$ArrayTemp);
                    }
                    else
                    {
                        # redirect
                        $_SESSION['InfoUploadShiftCodeMachine'] = '<script language="javascript">$(document).ready(function() { alert("Jumlah kolom tidak sesuai dengan ketentuan seperti template!"); });</script>';
                        header("location:../../../home.php?link=22");
                        exit();
                    }
					$Baris = $Baris + 1;
                }
                fclose($CSVFile);
                # check boolean
                $BolCheck2 = TRUE;
                $TempRowNo = "";
                # semarang
                foreach ($ArrayCheckData as $CheckData)
                {
                    if($CheckData['Location'] == "SEMARANG")
                    {
                        if($BolCheck2 == TRUE)
                        {
                            # check row
                            $Check = CHECK_DATA_UPLOAD_PSM($CheckData['Machine'],$CheckData['Date']);
                            if(mssql_num_rows($Check) == "0")
                            {
                                # insert
                                $ResAdd = ADD_NEW_DATA_HOURS_PSM($CheckData['Machine'],$CheckData['Date'],$CheckData['UtilizeHours']);
                                if($ResAdd == "FALSE")
                                {
                                    $BolCheck2 = FALSE;
                                    $_SESSION['InfoUploadShiftCodeMachine'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$CheckData['NoLoop'].' gagal disimpan!"); });</script>';
                                    break;
                                }
                            }
                            else
                            {
                                $RCheck = mssql_fetch_assoc($Check);
                                $ResIdx = trim($RCheck['Idx']);
                                # update
                                $ResUpdate = UPDATE_DATA_HOURS_PSM($ResIdx,$CheckData['Machine'],$CheckData['Date'],$CheckData['UtilizeHours']);
                                if($ResUpdate == "FALSE")
                                {
                                    $BolCheck2 = FALSE;
                                    $_SESSION['InfoUploadShiftCodeMachine'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$CheckData['NoLoop'].' gagal diupdate!"); });</script>';
                                    break;
                                }
                            }
                        }
                    }
                }
                
                # salatiga
                foreach ($ArrayCheckData as $CheckData)
                {
                    if($CheckData['Location'] == "SALATIGA")
                    {
                        if($BolCheck2 == TRUE)
                        {                            
                            # check row
                            $Check = CHECK_DATA_UPLOAD($CheckData['Machine'],$CheckData['Date'],$linkMACHWebTrax);
                            if(mssql_num_rows($Check) == "0")
                            {
                                # insert data
                                $ResAdd = ADD_NEW_DATA_HOURS($CheckData['Machine'],$CheckData['Date'],$CheckData['UtilizeHours'],$linkMACHWebTrax);
                                if($ResAdd == "FALSE")
                                {
                                    $BolCheck2 = FALSE;
                                    $_SESSION['InfoUploadShiftCodeMachine'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$CheckData['NoLoop'].' gagal disimpan!"); });</script>';
                                    break;
                                }
                            }
                            else
                            {
                                $RCheck = mssql_fetch_assoc($Check);
                                $ResIdx = trim($RCheck['Idx']);
                                # update data
                                $ResUpdate = UPDATE_DATA_HOURS($ResIdx,$CheckData['Machine'],$CheckData['Date'],$CheckData['UtilizeHours'],$linkMACHWebTrax);
                                if($ResUpdate == "FALSE")
                                {
                                    $BolCheck2 = FALSE;
                                    $_SESSION['InfoUploadShiftCodeMachine'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$CheckData['NoLoop'].' gagal diupdate!"); });</script>';
                                    break;
                                }
                            }
                        }
                    }
                }

                if($BolCheck2 == TRUE)
                {
                    $_SESSION['InfoUploadShiftCodeMachine'] = '<script language="javascript">$(document).ready(function() { alert("Proses upload berhasil!"); });</script>';
                    header("location:../../../home.php?link=22");
                    exit();
                }
                else
                {
                    header("location:../../../home.php?link=22");
                    exit();
                }

            }
            else
            {
                # redirect
                $_SESSION['InfoUploadShiftCodeMachine'] = '<script language="javascript">$(document).ready(function() { alert("Jenis file tidak valid!"); });</script>';
                header("location:../../../home.php?link=22");
                exit();
            }
        }
        else
        {
            # redirect
            $_SESSION['InfoUploadShiftCodeMachine'] = '<script language="javascript">$(document).ready(function() { alert("File tidak ditemukan!"); });</script>';
            header("location:../../../home.php?link=22");
            exit();
        }
    }
    elseif($DataSize < 1)
	{
		# redirect
		$_SESSION['InfoUploadShiftCodeMachine'] = '<script language="javascript">$(document).ready(function() { alert("File tidak ditemukan!"); });</script>';
		header("location:../../../home.php?link=22");
		exit();
	}
	else
	{
		# redirect
		$_SESSION['InfoUploadShiftCodeMachine'] = '<script language="javascript">$(document).ready(function() { alert("File data terlalu besar!"); });</script>';
		header("location:../../../home.php?link=22");
		exit();
	}
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
