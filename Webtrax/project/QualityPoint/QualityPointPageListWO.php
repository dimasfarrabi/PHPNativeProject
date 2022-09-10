<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleQualityPoint.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
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
?>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="ListProject">
        <thead class="theadCustom">
            <tr>
                <th class="text-center">Quote</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $QListProject = LIST_QUOTE_QUALITY_POINT($ValClosedTime,$linkMACHWebTrax);
        while($RListProject = sqlsrv_fetch_array($QListProject))
        {
            $ValName = $RListProject['Quote'];
            $ValIdx = $RListProject['Idx'];
            $ValIdxEnc = base64_encode("ID".$ValIdx);
            echo '<tr data-row="'.$ValIdxEnc.'" class="PointerListProject">';
            echo '<td>'.$ValName.'</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<?php
}
else
{
    echo "";    
}
?>