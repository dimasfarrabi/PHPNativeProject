<?php
require_once("src/Modules/ModuleLogin.php");
require_once("Modules/ModuleMachinePlan.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
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
if($AccessLogin == "Reguler")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}
*/
$ArrList = array();
$QListCategory = MACHINE_LIST($linkMACHWebTrax);
    while($RListCategory = sqlsrv_fetch_array($QListCategory))
    {
        $TempArray = array(
            "MachineName" => trim($RListCategory['NamaMesin']),
            "Location" => "PSL",
            "MachineCode" => trim($RListCategory['KodeMesin'])
        );
        array_push($ArrList,$TempArray);
    }
$ArrList2 = array();
$QListPSM = MACHINE_LIST_PSM($linkMACHWebTrax);
    while($RListPSM = sqlsrv_fetch_array($QListPSM))
    {
        $TempArray = array(
            "MachineName" => trim($RListPSM['NamaMesin']),
            "Location" => "PSM",
            "MachineCode" => trim($RListCategory['KodeMesin'])
        );
        array_push($ArrList2,$TempArray);
    }
?>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=37">Production : Machining Material Supply</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="row" id="">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="ListCategory">
                        <thead class="theadCustom">
                            <tr>
                                <th class="text-center">PSL Machine Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        foreach($ArrList as $List)
                        {
                            $MachineName = $List['MachineName'];
                            $EncLocation = base64_encode(base64_encode($List['Location']));
                            echo '<tr data-id="'.$List['MachineCode'].'" data-log="'.$EncLocation.'" class="PointerList">';
                            echo '<td>'.$MachineName.'</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <span id="TempMachinePSM" class="InvisibleText"></span>
        <div class="row" id="">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="ListCategory">
                        <thead class="theadCustom">
                            <tr>
                                <th class="text-center">PSM Machine Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        foreach($ArrList2 as $List2)
                        {
                            $MachineName = $List2['MachineName'];
                            $EncLocation = base64_encode(base64_encode($List2['Location']));
                            echo '<tr data-id="'.$List['MachineCode'].'" data-log="'.$EncLocation.'" class="PointerList">';
                            echo '<td>'.$MachineName.'</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <span id="TempMachine" class="InvisibleText"></span>
    </div>
    <div class="col-md-9">
        <div class="row" id="QuoteList">

        </div>
        <span id="" class="InvisibleText"></span>
        <div class="row" id="MaterialSupplyContent">

        </div>
        <!-- <div class="row" id="ListBarcode">

        </div> -->
    </div>
</div>
<script>
$(document).ready(function () {
    var BolClickListCategory = "TRUE";
    if (BolClickListCategory == "TRUE") {
        $(".PointerList").click(function () {
            if (BolClickListCategory == "TRUE") {
                $("#ListCategory tr").removeClass('PointerListSelected');
                $(this).closest('.PointerList').addClass("PointerListSelected");
                var MachineName = $(this).text();
                var MachineCode = $(this).data('id');
                var Location = $(this).data('log');
                $("#TempMachine").text(MachineName);
                var formdata = new FormData();
                formdata.append('ValMachine', MachineName);
                formdata.append('MachineCode', MachineCode);
                formdata.append('ValLocation', Location);
                $.ajax({
                    url: 'project/wipsims/MachineScheduleQuote.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        BolClickListCategory = "FALSE";
                        $("html, body").animate({ scrollTop: $("#QuoteList").offset().top - 20 }, "fast");
                        $('#QuoteList').html("");
                        $("#ContentLoading").remove();
                        $("#QuoteList").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#QuoteList').html("");
                        $('#MaterialSupplyContent').hide();
                    },
                    success: function (xaxa) {
                        $('#QuoteList').html("");
                        $('#QuoteList').hide();
                        $('#QuoteList').html(xaxa);
                        $('#QuoteList').fadeIn('fast');
                        $("#ContentLoading").remove();
                        BolClickListCategory = "TRUE";
                        MATERIAL_SUPPLY_CONTENT(MachineName);
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $('#QuoteList').html("");
                        $("#ContentLoading").remove();
                        BolClickListCategory = "TRUE";
                    }
                });
            }
            else {
                return false;
            }
        });
    }
});
function MATERIAL_SUPPLY_CONTENT(MachineName)
{
    var BoolClick = "TRUE";
    $(".DataQuote").click(function () {
        if (BoolClick == "TRUE") {
            $("#TableQ tr").removeClass('PointerListSelected');
            $(this).closest('.DataQuote').addClass("PointerListSelected");
            var FloatData = $(this).data('float');
            var MachName = MachineName;
            var formdata = new FormData();
            formdata.append("ValFloat", FloatData);          
            formdata.append("ValMachine", MachName);          
            $.ajax({
                url: 'project/WIPSims/MachineScheduleContent.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BoolClick = "FALSE";
                    $("html, body").animate({ scrollTop: $("#MaterialSupplyContent").offset().top }, "fast");
                    $('#MaterialSupplyContent').html("");
                    $("#ContentLoading").remove();
                    $("#MaterialSupplyContent").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#MaterialSupplyContent').html("");
                },
                success: function (xaxa) {
                    $('#MaterialSupplyContent').html("");
                    $('#MaterialSupplyContent').hide();
                    $('#MaterialSupplyContent').html(xaxa);
                    $('#MaterialSupplyContent').fadeIn('fast');
                    $("#ContentLoading").remove();
                    BoolClick = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#ContentLoading").remove();
                    BoolClick = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
}
</script>
