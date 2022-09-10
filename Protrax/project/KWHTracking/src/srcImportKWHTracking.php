<?php 
session_start();
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleKWHTracking.php");

if(!session_is_registered("UIDWebTrax"))
{
    header("location:http://localhost:8080/newsik/webtrax/");
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
                while(($row = fgetcsv($CSVFile)) !== FALSE)
                {
                    # pengecekan jumlah kolom file
                    if(count($row) == "12")
                    {
						# pengambilan data upload
						$TempSRNo1 = preg_replace("/[\xA0\xC2]/", "",$row[0]);
						$TempTime1 = preg_replace("/[\xA0\xC2]/", "",$row[1]);
						$TempDate1 = preg_replace("/[\xA0\xC2]/", "",$row[2]);
						$TempKWH1 = preg_replace("/[\xA0\xC2]/", "",$row[3]);
						$TempSRNo2 = preg_replace("/[\xA0\xC2]/", "",$row[4]);
						$TempTime2 = preg_replace("/[\xA0\xC2]/", "",$row[5]);
						$TempDate2 = preg_replace("/[\xA0\xC2]/", "",$row[6]);
						$TempKWH2 = preg_replace("/[\xA0\xC2]/", "",$row[7]);
						$TempSRNo3 = preg_replace("/[\xA0\xC2]/", "",$row[8]);
						$TempTime3 = preg_replace("/[\xA0\xC2]/", "",$row[9]);
						$TempDate3 = preg_replace("/[\xA0\xC2]/", "",$row[10]);
                        $TempKWH3 = preg_replace("/[\xA0\xC2]/", "",$row[11]);

                        if( trim($TempDate1) != "" && trim($TempKWH1) != "" && trim($TempDate2) != "" && trim($TempKWH2) != "" && trim($TempDate3) != "" && trim($TempKWH3) != "" )
                        {
                            # check nilai kolom
                            if(trim($TempSRNo1) == "" || trim($TempTime1) == ""  || trim($TempDate1) == ""  || trim($TempKWH1) == ""  || trim($TempSRNo2) == "" || trim($TempTime2) == "" || trim($TempDate2) == "" || trim($TempKWH2) == "" || trim($TempSRNo3) == "" || trim($TempTime3) == "" || trim($TempDate3) == "" || trim($TempKWH3) == "" )
                            {
                                $BolCheck = FALSE;
                                $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada yang kosong!");});</script>';
								break;
                            }

                            if($Baris > 2)
                            {
                                if(is_numeric($TempSRNo1) && is_numeric($TempSRNo2) && is_numeric($TempSRNo3))
                                {
                                    # check jam valid
                                    $Bits1 = explode(':', $TempTime1);
                                    // if ($Bits1[0] > 24 || ($Bits1[0] == 24 && $Bits1[1] > 0) || count($Bits1) > 3)
                                    if ($Bits1[0] > 24 || $Bits1[0] == 24 || $Bits1[1] > 59 || $Bits1[2] > 59 || count($Bits1) > 3)
                                    {
                                        $BolCheck = FALSE;
                                        $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada waktu yang tidak valid!");});</script>';
                                        break;
                                    }
                                    $Bits2 = explode(':', $TempTime2);
                                    // if ($Bits2[0] > 24 || ($Bits2[0] == 24 && $Bits2[1] > 0) || count($Bits2) > 3)
                                    if ($Bits2[0] > 24 || $Bits2[0] == 24 || $Bits2[1] > 59 || $Bits2[2] > 59 || count($Bits2) > 3)
                                    {
                                        $BolCheck = FALSE;
                                        $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada waktu yang tidak valid!");});</script>';
                                        break;
                                    }
                                    $Bits3 = explode(':', $TempTime3);
                                    // if ($Bits3[0] > 24 || ($Bits3[0] == 24 && $Bits3[1] > 0) || count($Bits3) > 3)
                                    if ($Bits3[0] > 24 || $Bits3[0] == 24 || $Bits3[1] > 59 || $Bits3[2] > 59 || count($Bits3) > 3)
                                    {
                                        $BolCheck = FALSE;
                                        $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada waktu yang tidak valid!");});</script>';
                                        break;
                                    }

                                    # check tgl valid
                                    if(strpos($TempDate1,'/') !== false)
                                    {
                                        $ArrayDate1 = explode("/",$TempDate1);
                                        $CountArrayDate1 = count($ArrayDate1);					
                                        // if(checkdate($ArrayDate1[0],$ArrayDate1[1],$ArrayDate1[2]) == false)
                                        if(checkdate($ArrayDate1[1],$ArrayDate1[0],$ArrayDate1[2]) == false)
                                        {
                                            $BolCheck = FALSE;
                                            $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada tanggal yang tidak valid!");});</script>';
                                            break;
                                        }
                                        if($CountArrayDate1 != "3")
                                        {
                                            $BolCheck = FALSE;
                                            $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada tanggal yang tidak valid!");});</script>';
                                            break;
                                        }
                                    }
                                    else
                                    {
                                        $BolCheck = FALSE;
                                        $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada tanggal yang tidak valid!");});</script>';
                                        break;
                                    }
                                    if(strpos($TempDate2,'/') !== false)
                                    {
                                        $ArrayDate2 = explode("/",$TempDate2);
                                        $CountArrayDate2 = count($ArrayDate2);					
                                        // if(checkdate($ArrayDate2[0],$ArrayDate2[1],$ArrayDate2[2]) == false)
                                        if(checkdate($ArrayDate2[1],$ArrayDate2[0],$ArrayDate2[2]) == false)
                                        {
                                            $BolCheck = FALSE;
                                            $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada tanggal yang tidak valid!");});</script>';
                                            break;
                                        }
                                        if($CountArrayDate2 !="3")
                                        {
                                            $BolCheck = FALSE;
                                            $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada tanggal yang tidak valid!");});</script>';
                                            break;
                                        }
                                    }
                                    else
                                    {
                                        $BolCheck = FALSE;
                                        $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada tanggal yang tidak valid!");});</script>';
                                        break;
                                    }
                                    if(strpos($TempDate3,'/') !== false)
                                    {
                                        $ArrayDate3 = explode("/",$TempDate3);
                                        $CountArrayDate3 = count($ArrayDate3);					
                                        // if(checkdate($ArrayDate3[0],$ArrayDate3[1],$ArrayDate3[2]) == false)
                                        if(checkdate($ArrayDate3[1],$ArrayDate3[0],$ArrayDate3[2]) == false)
                                        {
                                            $BolCheck = FALSE;
                                            $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada tanggal yang tidak valid!");});</script>';
                                            break;
                                        }
                                        if($CountArrayDate3 != "3")
                                        {
                                            $BolCheck = FALSE;
                                            $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada tanggal yang tidak valid!");});</script>';
                                            break;
                                        }
                                    }
                                    else
                                    {
                                        $BolCheck = FALSE;
                                        $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada tanggal yang tidak valid!");});</script>';
                                        break;
                                    }
                                    # check nilai kwh valid
                                    if(!is_numeric($TempKWH1))
                                    {
                                        $BolCheck = FALSE;
                                        $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada nilai KWH yang tidak valid!");});</script>';
                                        break;
                                    }
                                    if(!is_numeric($TempKWH2))
                                    {
                                        $BolCheck = FALSE;
                                        $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada nilai KWH yang tidak valid!");});</script>';
                                        break;
                                    }
                                    if(!is_numeric($TempKWH3))
                                    {
                                        $BolCheck = FALSE;
                                        $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Data baris ke '.$Baris.' masih ada nilai KWH yang tidak valid!");});</script>';
                                        break;
                                    }


                                    # input ke array
                                    $TempArrayResult = array(
                                        "SRNo1" => $TempSRNo1,
                                        "Time1" => $TempTime1,
                                        "Date1" => $TempDate1,
                                        "KWH1" => $TempKWH1,
                                        "SRNo2" => $TempSRNo2,
                                        "Time2" => $TempTime2,
                                        "Date2" => $TempDate2,
                                        "KWH2" => $TempKWH2,
                                        "SRNo3" => $TempSRNo3,
                                        "Time3" => $TempTime3,
                                        "Date3" => $TempDate3,
                                        "KWH3" => $TempKWH3
                                    );
                                    array_push($ArrayCheckData,$TempArrayResult);
                                }
                            }
                        }
                    }
                    else
					{
						# set session
                        $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Jumlah kolom tidak sesuai!");});</script>';
					}
					$Baris = $Baris + 1;
                }
                fclose($CSVFile);

				# check boolean
                if($BolCheck == TRUE)
                {
                    # simpan data
                    foreach($ArrayCheckData as $DataArray)
                    {
                        $Slave1 = "1";
                        $Time1 = $DataArray['Time1'];
                        $TempDate1 = $DataArray['Date1'];
                        $ArrTempDate1 = explode("/",$TempDate1);
                        $Date1 = $ArrTempDate1[1]."/".$ArrTempDate1[0]."/".$ArrTempDate1[2];
                        $KWH1 = $DataArray['KWH1'];

                        $Slave2 = "2";
                        $Time2 = $DataArray['Time2'];
                        $TempDate2 = $DataArray['Date2'];
                        $ArrTempDate2 = explode("/",$TempDate2);
                        $Date2 = $ArrTempDate2[1]."/".$ArrTempDate2[0]."/".$ArrTempDate2[2];                        
                        $KWH2 = $DataArray['KWH2'];

                        $Slave3 = "3";
                        $Time3 = $DataArray['Time3'];                       
                        $TempDate3 = $DataArray['Date3'];
                        $ArrTempDate3 = explode("/",$TempDate3);
                        $Date3 = $ArrTempDate3[1]."/".$ArrTempDate3[0]."/".$ArrTempDate3[2]; 
                        $KWH3 = $DataArray['KWH3'];
                        # bagian simpan
                        NEW_KWH_TRACKING($Slave1,$Time1,$Date1,$KWH1,$linkHRISWebTrax);
                        NEW_KWH_TRACKING($Slave2,$Time2,$Date2,$KWH2,$linkHRISWebTrax);
                        NEW_KWH_TRACKING($Slave3,$Time3,$Date3,$KWH3,$linkHRISWebTrax);
                    }
                    $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Proses upload file sudah selesai!");});</script>';
                }
                $ArrayCheckData = array();
                # redirect
                header("location:../../../home.php?link=3");
				exit();
            }
            else
			{
                # redirect
                $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("File gagal diimport!");});</script>';
                header("location:../../../home.php?link=3");
                exit();
			}
        }
        else
		{
			# redirect
            $_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Jenis file tidak valid!");});</script>';
            header("location:../../../home.php?link=3");
            exit();
		}
    }
	elseif($FileSize < 1)
	{
		# redirect
		$_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("File tidak ditemukan!");});</script>';
		header("location:../../../home.php?link=3");
		exit();
	}
	else
	{
		# redirect
		$_SESSION['ImportKWHTracking'] = '<script language="javascript">$(document).ready(function() { alert("Ukuran data terlalu besar!");});</script>';
		header("location:../../../home.php?link=3");
		exit();
	}
}
else
{
    echo "";    
}
?>