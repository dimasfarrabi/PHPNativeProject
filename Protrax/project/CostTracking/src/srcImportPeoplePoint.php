<?php 
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModulePeoplePoint.php");
/*
if(!session_is_registered("UIDWebTrax"))
{
    header("location:https://webtrax.formulatrix.com/");
    exit();
}
*/


if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $DataName = $_FILES['InputFile']['name'];
	$FileSize = $_FILES['InputFile']['size'];
	$DataFile = $_FILES['InputFile']['tmp_name'];
    $BolCheck = TRUE;
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
                    if(count($row) == "7")
                    {
						# pengambilan data upload
						$TempIdx = preg_replace("/[\xA0\xC2]/", "",$row[0]);
						$TempName = preg_replace("/[\xA0\xC2]/", "",$row[1]);
						$TempDivision = preg_replace("/[\xA0\xC2]/", "",$row[2]);
						$TempPoints = preg_replace("/[\xA0\xC2]/", "",$row[3]);
						$TempDis = preg_replace("/[\xA0\xC2]/", "",$row[4]);
						$TempExc = preg_replace("/[\xA0\xC2]/", "",$row[5]);
						$TempTotal = preg_replace("/[\xA0\xC2]/", "",$row[6]);
                        if($TempDis == ""){ $TempDis = 0; }
                        if($TempExc == ""){ $TempExc = 0; }
                        // echo "Succes - ".$TempIdx;
                        $update = UPDATE_PEOPLE_POINT2($TempIdx,$TempDis,$TempExc,$linkMACHWebTrax);
                    }
                    else
					{
                        $BolCheck = FALSE;
						?>
                        <script language="javascript">
                            alert('Jumlah Baris Tidak Sesuai!!');
                            window.location.replace("http://localhost/protrax/home.php?link=32");
                        </script>
                        <?php
					}
					$Baris = $Baris + 1;
                }
                fclose($CSVFile);

				# check boolean
                if($BolCheck == TRUE)
                {
                    ?>
                    <script language="javascript">
                        alert('File Berhasil Di Import!');
                    </script>
                    <?php
                }
                # redirect
                ?>
                <script language="javascript">
                    // window.location.replace("https://webtrax.formulatrix.com/home.php?link=11");
                    window.location.replace("http://localhost/protrax/home.php?link=32");
                </script>
                <?php
            }
            else
			{
                ?>
                <script language="javascript">
                    alert('File Gagal Di Import!');
                    window.location.replace("http://localhost/protrax/home.php?link=32");
                </script>
                <?php
			}
        }
        else
		{
			    ?>
                <script language="javascript">
                    alert('Jenis File Tidak Sesuai!');
                    window.location.replace("http://localhost/protrax/home.php?link=32");
                </script>
                <?php
		}
    }
	elseif($FileSize < 1)
	{
		        ?>
                <script language="javascript">
                    alert('File Tidak Ditemukan!');
                    window.location.replace("http://localhost/protrax/home.php?link=32");
                </script>
                <?php
	}
	else
	{
		    ?>
                <script language="javascript">
                    alert('Ukuran File Terlalu Besar!');
                    window.location.replace("http://localhost/protrax/home.php?link=32");
                </script>
                <?php
	}
}    
?>