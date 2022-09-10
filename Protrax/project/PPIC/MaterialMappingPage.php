<?php
// require_once("../webtrax/project/CostTracking/Modules/ModuleCostTracking.php");
require_once("project/PPIC/Modules/ModuleNewBCPartJob.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">PPIC : Machining Material Supply Mapping</li>
            </ol>
        </nav>
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
        <div class="row" id="MappingContent">

        </div>
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
                    url: 'project/PPIC/MaterialMappingContent.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        BolClickListCategory = "FALSE";
                        $('#MappingContent').html("");
                        $("#ContentLoading").remove();
                        $("#MappingContent").before('<div class="col-sm-9" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#MappingContent').html("");
                    },
                    success: function (xaxa) {
                        $('#MappingContent').html("");
                        $('#MappingContent').hide();
                        $('#MappingContent').html(xaxa);
                        $('#MappingContent').fadeIn('fast');
                        $("#ContentLoading").remove();
                        BolClickListCategory = "TRUE";
                        $('#TableDiRak').DataTable({
                            "iDisplayLength": 5,
                            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                            scrollCollapse: true,
                            autoWidth: true
                        });
                        MODAL_FORM();
                        MODAL_FORM2();
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $('#MappingContent').html("");
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
    function MODAL_FORM()
    {
        $("#MappingForm").on('show.bs.modal', function (event) {
            var act = $(event.relatedTarget);
            var DataCode = act.data('ecode');
            var formdata = new FormData();
            formdata.append("ValCode", DataCode);
            $.ajax({
                url: 'project/ppic/MaterialMappingModal.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $('#LoadingImg').show();
                    $('#FormContent').html("");
                },
                success: function (xaxa) {
                    $('#LoadingImg').hide();
                    $('#FormContent').hide();
                    $('#FormContent').html(xaxa);
                    $('#FormContent').fadeIn('fast');
                    
                },
                error: function () {
                    $('#LoadingImg').hide();
                    alert('Request cannot proceed!');
                }
            });
        });
    }
    function MODAL_FORM2()
    {
        $("#AddMapping").on('show.bs.modal', function (event) {
            var act = $(event.relatedTarget);
            var DataCode = act.data('ecode');
            var formdata = new FormData();
            formdata.append("ValCode", DataCode);
            $.ajax({
                url: 'project/ppic/MaterialMappingAddNew.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $('#loading').show();
                    $('#FormAddMapping').html("");
                    $('#WOList').hide();
                },
                success: function (xaxa) {
                    $('#loading').hide();
                    $('#FormAddMapping').hide();
                    $('#FormAddMapping').html(xaxa);
                    $('#FormAddMapping').fadeIn('fast');
                    getWOlist(DataCode);
                },
                error: function () {
                    $('#loading').hide();
                    alert('Request cannot proceed!');
                }
            });
        });
    }
    function getWOlist(DataCode)
    {
        $("#ShowWO").click(function(){
            var Quote = $('#selectQuote option:selected').val();
            var formdata = new FormData();
            formdata.append("ValQuote", Quote);
            formdata.append("DataCode", DataCode);
            $.ajax({
                url: 'project/ppic/WOList.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $('#loading').show();
                    $('#WOList').html("");
                },
                success: function (xaxa) {
                    $('#loading').hide();
                    $('#WOList').hide();
                    $('#WOList').html(xaxa);
                    $('#WOList').fadeIn('fast');
                    $('#ButtonSave').click(function() {
                        var sel = $('input[type=checkbox]:checked').map(function(_, el) {
                            return $(el).val();
                        }).get();
                        SAVE_NEW_MAPPING(sel,DataCode);
                    })
                },
                error: function () {
                    $('#loading').hide();
                    alert('Request cannot proceed!');
                }
            });
        });
    }
    function SAVE_NEW_MAPPING(Data,DataCode)
    {
        var formdata = new FormData();
        formdata.append("Data", Data);
        formdata.append("Machine", DataCode);
        $.ajax({
            url: 'project/ppic/MaterialMappingSaver2.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#saving').html("");
            },
            success: function (xaxa) {
                $('#loading').hide();
                $('#saving').hide();
                $('#saving').html(xaxa);
                $('#saving').fadeIn('fast');
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    }
});
</script>