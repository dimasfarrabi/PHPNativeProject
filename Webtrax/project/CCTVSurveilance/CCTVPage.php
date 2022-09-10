<?php
require_once("project/CCTVSurveilance/Modules/ModuleCCTV.php"); 
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
if($AccessLogin == "Reguler")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}


?><script src="project/cctvsurveilance/lib/libcctv.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=13">CCTV : CCTV Surveilance</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="ListCategory">
                <thead class="theadCustom">
                    <tr>
                        <td class="text-center">Category</td>
                    </tr>
                </thead>
                <tbody><?php 
                $QListCategory = GET_LIST_ACTIVE_CCTV_CATEGORY($linkHRISWebTrax);
                while($RListCategory = mssql_fetch_assoc($QListCategory))
                {
                    $ValCategory = trim($RListCategory['CategoryName']);
                    $ValCategoryID = base64_encode("ID".trim($RListCategory['Idx']));
                    echo '<tr data-id="'.$ValCategoryID.'" class="PointerListCategory">';
                    echo '<td>'.$ValCategory.'</td>';
                    echo '</tr>';
                }
                ?></tbody>
            </table>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row" id="ResultCategory"></div>
    </div>
</div>
