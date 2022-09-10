<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModulePointAchievement.php");
date_default_timezone_set("Asia/Jakarta");
/*
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    // echo $ValClosedTime;
?>
<style>
    .SmallText{font-size:11px;}
    .tableFixHead2 {
        overflow-y: auto;
        max-height: 700px;
    }
    .tableFixHead2 thead th {
        position: sticky;
        top: 0;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th,
    td {
        padding: 8px 16px;
        border: 1px solid #ccc;
    }
    th {
        background: #eee;
    }
</style>
<div class="col-md-12"><h5><strong>Employee Points</strong></h5><i>*) Exclude Discreation & Exception</i>
<button id="BtnDownload" type="button" class="btn btn-sm btn-info btn-labeled block" style="float: right; margin-bottom:30px">Download</button>
</div>
<div class="col-md-4">
    <div class="table-responsive tableFixHead2">
        <table class="table table-bordered table-hover" id="TableLeft">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center SmallText">Employee Name</th>
                    <th class="text-center SmallText">Division</th>
                    <th class="text-center SmallText">Total Points (%)*</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = GET_TOTAL_EMP_POINT($ValClosedTime,"LEFT",$linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($data))
                {
                    $ValName = trim($res['FullName']);
                    $ValDiv = trim($res['Division']);
                    $Points = trim($res['Points']);
                    $TotalPoints = ($Points - (0.2*($Points)));
                    $TotalPoints = number_format((float)$TotalPoints,2,'.',',');
                ?>
                <tr>
                    <td class="text-left SmallText"><?php echo $ValName; ?></td>
                    <td class="text-left SmallText"><?php echo $ValDiv; ?></td>
                    <td class="text-right SmallText"><?php echo $TotalPoints; ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-4">
    <div class="table-responsive tableFixHead2">
        <table class="table table-bordered table-hover" id="TableCenter">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center SmallText">Employee Name</th>
                    <th class="text-center SmallText">Division</th>
                    <th class="text-center SmallText">Total Points (%)*</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = GET_TOTAL_EMP_POINT($ValClosedTime,"CENTER",$linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($data))
                {
                    $ValName = trim($res['FullName']);
                    $ValDiv = trim($res['Division']);
                    $Points = trim($res['Points']);
                    $TotalPoints = ($Points - (0.2*($Points)));
                    $TotalPoints = number_format((float)$TotalPoints,2,'.',',');
                ?>
                <tr>
                    <td class="text-left SmallText"><?php echo $ValName; ?></td>
                    <td class="text-left SmallText"><?php echo $ValDiv; ?></td>
                    <td class="text-right SmallText"><?php echo $TotalPoints; ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-4">
    <div class="table-responsive tableFixHead2">
        <table class="table table-bordered table-hover" id="TableRight">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center SmallText">Employee Name</th>
                    <th class="text-center SmallText">Division</th>
                    <th class="text-center SmallText">Total Points (%)*</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = GET_TOTAL_EMP_POINT($ValClosedTime,"RIGHT",$linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($data))
                {
                    $ValName = trim($res['FullName']);
                    $ValDiv = trim($res['Division']);
                    $Points = trim($res['Points']);
                    $TotalPoints = ($Points - (0.2*($Points)));
                    $TotalPoints = number_format((float)$TotalPoints,2,'.',',');
                ?>
                <tr>
                    <td class="text-left SmallText"><?php echo $ValName; ?></td>
                    <td class="text-left SmallText"><?php echo $ValDiv; ?></td>
                    <td class="text-right SmallText"><?php echo $TotalPoints; ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
}
?>