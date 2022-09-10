<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleEmployee.php");
date_default_timezone_set("Asia/Jakarta");

if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $EncValDetail = htmlspecialchars(trim($_POST['ValDetail']), ENT_QUOTES, "UTF-8");
    $ValDetail = base64_decode(base64_decode($EncValDetail));;
    $ArrCategory = explode("#",$ValDetail);
    $Group = $ArrCategory[0];
    $Category = $ArrCategory[1];
    if($Group != "ALL DEPARTMENT")
    {
        switch ($Category) {
            case 'A':
                {
                    $QData = GET_DATA_PTO_CLASS_A($Group,$linkMACHWebTrax);
                }
                break;
            case 'B':
                {
                    $QData = GET_DATA_PTO_CLASS_B($Group,$linkMACHWebTrax);
                }
                break;
            default:
                {
                    $QData = GET_DATA_PTO_CLASS_C($Group,$linkMACHWebTrax);
                }
                break;
        } 
    }
    else
    {
        switch ($Category) {
            case 'A':
                {
                    $QData = GET_DATA_PTO_CLASS_ALL_A($linkMACHWebTrax);
                }
                break;
            case 'B':
                {
                    $QData = GET_DATA_PTO_CLASS_ALL_B($linkMACHWebTrax);
                }
                break;
            default:
                {
                    $QData = GET_DATA_PTO_CLASS_ALL_C($linkMACHWebTrax);
                }
                break;
        } 
    }   
    ?>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-hover" id="TableDetail">
            <thead>
                <tr>
                    <th class="text-center" width="10">No</th>
                    <th class="text-center">Division</th>
                    <th class="text-center">Location</th>
                    <th class="text-center">FullName</th>
                    <th class="text-center">DayOff Balance</th>
                </tr>
            </thead>
            <tbody><?php 
            $No = 1;
            while($RData = mssql_fetch_assoc($QData))
            {
                $ValDivision = trim($RData['Division']);
                $ValLocation = trim($RData['Location']);
                if($ValLocation == "PSL"){$Location = "SALATIGA";}else{$Location = "SEMARANG";}
                $ValFullName = trim($RData['EmployeeName']);
                $ValDayOffBalance = trim($RData['PTOBalance']);
                $ValDayOffBalance = number_format((float)$ValDayOffBalance, 2, '.', ',');
                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-left"><?php echo $ValDivision; ?></td>
                    <td class="text-left"><?php echo $Location; ?></td>
                    <td class="text-left"><?php echo $ValFullName; ?></td>
                    <td class="text-center"><?php echo $ValDayOffBalance; ?></td>
                </tr>
                <?php
                $No++;
            }
            ?></tbody>
        </table>
    </div>
</div>
    <?php
?>
<?php
}
else
{
    echo "";    
}
?>