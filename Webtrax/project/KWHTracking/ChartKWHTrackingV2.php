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
    <div class="col-md-3">
      <div class="row">
        <div class="col-md-12"><h5 class="TitleGroup">Filter Harian</h5></div>
        <div class="col-md-12"><button type="button" id="BtnViewChartH" class="btn btn-md btn-dark">Grafik Data Harian</button></div>        
      </div>
    </div>
    <div class="col-md-3">
      <div class="row">
        <div class="col-md-12"><h5 class="TitleGroup">Filter Mingguan</h5></div>
        <div class="col-md-12"><button type="button" id="BtnViewChartM" class="btn btn-md btn-dark">Grafik Data Mingguan</button></div>        
      </div>
    </div>
    <div class="col-md-3">
      <div class="row">
        <div class="col-md-12"><h5 class="TitleGroup">Filter Bulanan</h5></div>
        <div class="col-md-12"><button type="button" id="BtnViewChartB" class="btn btn-md btn-dark">Grafik Data Bulanan</button></div>        
      </div>
    </div>
    <div class="col-md-3">
      <div class="row">
        <div class="col-md-12"><h5 class="TitleGroup">Filter Tahunan</h5></div>
        <div class="col-md-12"><button type="button" id="BtnViewChartT" class="btn btn-md btn-dark">Grafik Data Tahunan</button></div>        
      </div>
    </div>
  </div>
</div>
<div class="col-sm-12">&nbsp;</div>
<div class="col-sm-12">&nbsp;</div>
<div class="col-sm-12" id="ContentResultChart"></div>