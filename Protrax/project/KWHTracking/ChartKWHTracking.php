<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleKWHTracking.php"); 
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y"); 
session_start();
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}



?>
<div class="col-sm-12"><h4 class="TitleGroup">Lihat Grafik KWH Tracking</h4></div>
<div class="col-sm-12">
  <div class="row">
    <div class="col-md-4">
      <div class="row">
        <div class="col-md-12"><h5 class="TitleGroup">Filter Harian</h5></div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label for="txtFilterTanggal1C">Tanggal Awal</label>
              <div class="controls">
                  <div class="input-group"><input id="txtFilterTanggal1C" name="txtFilterTanggal1C" type="text" class="date-picker form-control" value="<?php echo $DateNow; ?>" readonly /><label for="txtFilterTanggal1C" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                  </div>
              </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label for="txtFilterTanggal2C">Tanggal Akhir</label>
              <div class="controls">
                  <div class="input-group"><input id="txtFilterTanggal2C" name="txtFilterTanggal2C" type="text" class="date-picker form-control" value="<?php echo $DateNow; ?>" readonly /><label for="txtFilterTanggal2C" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                  </div>
              </div>
          </div>
        </div>
        <div class="col-md-12"><button type="button" id="BtnViewChart1" class="btn btn-md btn-dark">Grafik Data Harian</button></div>

        <?php /*
        //yg digunakan sekarang
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label for="txtFilterTanggal1C">Tanggal</label>
              <div class="controls">
                  <div class="input-group"><input id="txtFilterTanggal1C" name="txtFilterTanggal1C" type="text" class="date-picker form-control" value="<?php echo $DateNow; ?>" readonly /><label for="txtFilterTanggal1C" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                  </div>
              </div>
          </div>
        </div>
        <div class="col-md-12"><button type="button" id="BtnViewChart1" class="btn btn-md btn-dark">Grafik Data Harian</button></div>
        */ ?>


      </div>
    </div>
    <div class="col-md-4">
      <div class="row">
        <div class="col-md-12"><h5 class="TitleGroup">Filter Bulanan</h5></div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="MonthTrack">Bulan</label>
            <select id="MonthTrack" class="form-control form-custom-2"><?php
            $QlistMonthYear = GET_LIST_YEAR_MONTH_KWH_TRACKING($linkHRISWebTrax);
            while($RlistMonthYear = mssql_fetch_assoc($QlistMonthYear))
            {
              $ArrTime = $RlistMonthYear['MonthYear'];
              $TimeList = explode("#",$ArrTime);
              $MonthNameID = date("F",mktime(0, 0, 0, $TimeList[1]));
              $OptTime = substr($MonthNameID,0,3)." ".$TimeList[0];
              echo '<option value="'.$ArrTime.'">'.$OptTime.'</option>';
            }
            ?></select>
          </div>
        </div>
        <div class="col-md-12"><button type="button" id="BtnViewChart2" class="btn btn-md btn-dark">Grafik Data Bulanan</button></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="row">
        <div class="col-md-12"><h5 class="TitleGroup">Filter Tahunan</h5></div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="YearTrack">Tahun</label>
            <select id="YearTrack" class="form-control form-custom-2"><?php
            $QListYear = GET_LIST_YEAR_KWH_TRACKING($linkHRISWebTrax);
            while($RListYear = mssql_fetch_assoc($QListYear))
            {
            $DataYearOpt = $RListYear['Years'];
            echo '<option>'.$DataYearOpt.'</option>';
            }
            ?></select>
          </div>
        </div>
        <div class="col-md-12"><button type="button" id="BtnViewChart3" class="btn btn-md btn-dark">Grafik Data Tahunan</button></div>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-12">&nbsp;</div>
<div class="col-sm-12">&nbsp;</div>
<div class="col-sm-12" id="ContentResultChart"></div>