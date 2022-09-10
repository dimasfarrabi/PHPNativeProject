<?php
require_once("project/WOMapping/Modules/ModuleWOMapping.php");
require_once("project/CostTracking/Modules/ModuleCostTracking.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
/*
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
# data protrax user
$BolProdAcc = false;
$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
if(mssql_num_rows($QDataUserWebtrax) > 0)
{
    $RDataUserWebtrax = sqlsrv_fetch_array($QDataUserWebtrax);
    $TypeUser = trim($RDataUserWebtrax['TypeUser']);
    $_SESSION['LoginMode'] = base64_encode($TypeUser);
    $AccessLogin = base64_decode($_SESSION['LoginMode']);   
}
else # kondisi tidak terdaftar di protrax user & akan di set sebagai employee dan hak akses ke bagian produksi saja
{
    $_SESSION['LoginMode'] = base64_encode("Employee");
    $AccessLogin = base64_decode($_SESSION['LoginMode']);
    $BolProdAcc = true;
}

if((trim($AccessLogin) != "Manager"))
{
    if($RDataUserWebtrax['MnAdmin'] != "1" && $RDataUserWebtrax['MnWOMapping'] != "1")  
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}
else
{
    if($RDataUserWebtrax['MnAdmin'] != "1")
    {
        if($RDataUserWebtrax['MnWOMapping'] != "1")
        {
            ?>
            <script language="javascript">
                window.location.replace("https://protrax.formulatrix.com/");
            </script>
            <?php
            exit();
        }
    }
}<script src="project/WOMapping/lib/LibCreateWOMapping.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
*/

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">WO Mapping : Create WO Mapping</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h6 class="card-header text-white bg-secondary">Pembuatan WO Mapping</h6>
            <div class="card-body pt-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="FilQuote" class="form-label fw-bold">Pilih Quote</label>
                            <select class="form-select form-select-sm" id="FilQuote">
                                <?php
                                $Data = GET_QUOTE_NAME("OPEN",$linkMACHWebTrax);
                                while($res=sqlsrv_fetch_array($Data))
                                {
                                    $Quote = trim($res['Quote']);
                                    $QuoteCategory = trim($res['QuoteCategory']);
                                    $Combine = $Quote."*".$QuoteCategory;
                                    $ecode = base64_encode($Combine);
                                ?>
                                <option value="<?php echo $ecode; ?>"><?php echo $Quote; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div> 
                    <div class="col-md-2" style="margin-top:28px;">
                        <button class="btn btn-sm btn-dark" id="BtnSelect">Select</button>&nbsp;
                        <span aria-hidden="true" data-bs-toggle="modal" data-bs-target="#ModalNewQuote" title="Buat Quote Baru"><button class="btn btn-sm btn-info">....</button></span>
                    </div>
                </div>
                <div class="row" id="FormCreateWO"  style="margin-top:20px;">

                </div>
            </div>
        </div>
    </div>
    <?php
    /*
    <div class="col-md-4">
        <div class="card">
            <h6 class="card-header text-white bg-secondary">Pencarian Data</h6>
            <div class="card-body pt-2">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="FilType" class="form-label fw-bold">Pilih Keywords</label>
                            <select class="form-select form-select-sm" id="FilType">
                                <option>WO Child</option>
                                <option>WO Parent</option>
                                <option>Quote</option>
                                <option>ClosedTime</option>
                            </select>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="FilterKeywords" class="form-label fw-bold">Input Keywords</label>
                            <input type="text" class="form-control form-control-sm" id="FilterKeywords" placeholder="Keywords" value="">
                        </div>
                    </div> 
                    <div class="col-md-2" style="margin-top:28px;">
                        <button class="btn btn-sm btn-dark" id="BtnSearch">Search</button>
                    </div>
                    
                </div>
                <div class="row" id="DataSearch" style="margin-top:20px;">

                </div>
            </div>
        </div>
    </div>
    */
    ?>
</div>
<div class="modal fade" id="ModalNewQuote" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Quote Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div id="NewQuoteForm">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $("#ModalNewQuote").on('show.bs.modal', function (event) {
        $.ajax({
            url: 'project/WOMapping/FormBuatQuote.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#NewQuoteForm').html("");
                $('#NewQuoteForm').append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading1"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingImg"></span ></div></div>');
            },
            success: function (xaxa) {
                $('#NewQuoteForm').hide();
                $("#ContentLoading1").remove();
                $('#NewQuoteForm').html(xaxa);
                $('#NewQuoteForm').fadeIn('fast');
                document.getElementById("NewQuoteName").focus();
                SaveQuote();
            },
            error: function () {
                $("#ContentLoading1").remove();
                alert('Request cannot proceed!');
            }
        });
    });
    $("#BtnSelect").click(function(){
        var ValQuote = $("#FilQuote").val();
        var formdata = new FormData();
        formdata.append('ValQuote', ValQuote);
        $.ajax({
            url: 'project/WOMapping/CreateWOMappingContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnSelect").attr('disabled', true);
                $('#SubmitWO').hide();
                $('#FormCreateWO').html("");
                $("#FormCreateWO").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#FormCreateWO').html("");
            },
            success: function (xaxa) {
                $('#FormCreateWO').html("");
                $('#FormCreateWO').hide();
                $('#FormCreateWO').html(xaxa);
                $('#FormCreateWO').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnSelect").blur();
                $("#BtnSelect").attr('disabled', false);
                $("#LoadingCekWOP").hide();
                document.getElementById("FILWOP").focus();
                $('#FILWOP').change(function() {
                    var val = $("#FILWOP").val().toUpperCase();
                    var Category = $("#FilCategory").val();
                    var formdata = new FormData();
                    formdata.append("val", val);
                    formdata.append("ValQuote", ValQuote);
                    formdata.append("Category", Category);
                    $.ajax({
                        url: 'project/WOMapping/src2/srcCekWOParent.php',
                        dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        async: false,
                        data: formdata,
                        type: 'post',
                        beforeSend: function () {
                            $("#LoadingCekWOP").show();
                        },
                        success: function (xaxa) {
                            $("#LoadingCekWOP").hide();
                            var res = xaxa;
                            const myArray = res.split(":");
                            if(myArray[3] == "TRUE")
                            {
                                $("#QtyWOP").val(myArray[1]);
                                $("#FilProduct").val(myArray[2]);
                                $("#FilWOC").val(myArray[0]);
                            }
                            else
                            {
                                alert('WOP Belum Terdaftar');
                                $("#QtyWOP").val('');
                                $("#FilProduct").val('');
                                $("#FilWOC").val('');
                            }
                        },
                        error: function () {
                            $("#LoadingCekWOP").hide();
                            alert("Request cannot proceed!");
                        }
                    });
                });
                NEW_WOP_MODAL(ValQuote);
                INPUT_MANHOUR();
                BTN_SUBMIT(ValQuote);
                $(".checkExpense").click(function(){
                    var DataExpense = $(this).data("id");
                    $("#FormExpense tr[data-idrows='" + DataExpense + "']").find("td:eq(1)").text('');
                    $("#FormExpense tr[data-idrows='" + DataExpense + "']").find("td:eq(2)").text('');
                    $("#FormExpense tr[data-idrows='" + DataExpense + "']").find("td:eq(3)").text('');
                    $("#FormExpense tr[data-idrows='" + DataExpense + "']").find("td:eq(4)").text('');
                    $("#FormExpense tr[data-idrows='" + DataExpense + "']").find("td:eq(5)").text('');
                    $("#FormExpense tr[data-idrows='" + DataExpense + "']").find("td:eq(6)").text('');
                    $("#FormExpense tr[data-idrows='" + DataExpense + "']").find("td:eq(7)").text('');
                    $("#FormExpense tr[data-idrows='" + DataExpense + "']").find("td:eq(8)").text('');
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnSelect").blur();
                $('#FormCreateWO').html("");
                $("#ContentLoading").remove();
                $("#BtnSelect").attr('disabled', false);
            }
        });
    });
});
function SaveQuote()
{
    $("#BtnSaveQuote").click(function(){
        var QuoteName = $("#NewQuoteName").val().toUpperCase();
        var QuoteCat = $("#QuoteCat").val();
        var NamaPM = $("#NamaPM").val();
        var formdata = new FormData();
        if(QuoteName == "")
        {
            document.getElementById("NewQuoteName").focus();
            return false;
        }
        formdata.append("QuoteName", QuoteName);
        formdata.append("QuoteCat", QuoteCat);
        formdata.append("NamaPM", NamaPM);
        $.ajax({
            url: 'project/WOMapping/src/srcBuatQuoteBaru.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#SimpanQuote').html("");
                $('#SimpanQuote').before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#SimpanQuote').hide();
                $('#ContentLoading').remove();
                $('#SimpanQuote').html(xaxa);
                $('#SimpanQuote').fadeIn('fast');
            },
            error: function () {
                $('#ContentLoading').remove();
                alert('Request cannot proceed!');
            }
        });
    });
}
function NEW_WOP_MODAL(Data)
{
    $("#ModalNewWOP").on('show.bs.modal', function (event) {
        var NamaPM = $("#FilPM").val();
        var formdata = new FormData();
        formdata.append('Quote', Data);
        formdata.append('NamaPM', NamaPM);
        $.ajax({
            url: 'project/WOMapping/FormBuatWOP.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#NewWOPForm').html("");
                $('#NewWOPForm').append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading1"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingImg"></span ></div></div>');
            },
            success: function (xaxa) {
                $('#NewWOPForm').hide();
                $('#ContentLoading1').remove();
                $('#NewWOPForm').html(xaxa);
                $('#NewWOPForm').fadeIn('fast');
                SaveWOP();
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    });
}
function SaveWOP()
{
    $("#BtnSaveWOP").click(function(){
        var QuoteName = $("#QuoteName").val();
        var Category = $("#Category").val();
        var NamaPM = $("#NamaPM").val();
        var WOPBaru = $("#WOPBaru").val().toUpperCase();
        var ProductName = $("#ProductName").val();
        var QtyWOPBaru = $("#QtyWOPBaru").val();
        if(WOPBaru == "")
        {
            document.getElementById("WOPBaru").focus();
            return false;
        }
        if(ProductName == "")
        {
            document.getElementById("ProductName").focus();
            return false;
        }
        if(QtyWOPBaru == "")
        {
            document.getElementById("QtyWOPBaru").focus();
            return false;
        }
        var formdata = new FormData();
        formdata.append("QuoteName", QuoteName);
        formdata.append("Category", Category);
        formdata.append("NamaPM", NamaPM);
        formdata.append("WOPBaru", WOPBaru);
        formdata.append("ProductName", ProductName);
        formdata.append("QtyWOPBaru", QtyWOPBaru);
        $.ajax({
            url: 'project/WOMapping/src/srcBuatWOPBaru.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#SimpanWOP').html("");
            },
            success: function (xaxa) {
                $('#SimpanWOP').hide();
                $('#SimpanWOP').html(xaxa);
                $('#SimpanWOP').fadeIn('fast');
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    });
}
function INPUT_MANHOUR()
{
    $("#ManHourModal").on('show.bs.modal', function (event) {
        if($("#FILWOP").val() == "")
        {
            document.getElementById("FILWOP").focus();
            return false;
        }
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/WOMapping/src/srcInputManHour.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#InputManHour').html("");
                $('#InputManHour').append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading1"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingImg"></span ></div></div>');
            },
            success: function (xaxa) {
                $('#InputManHour').hide();
                $('#ContentLoading1').remove();
                $('#InputManHour').html(xaxa);
                $('#InputManHour').fadeIn('fast');
                $('#txtFilterTanggal1').datetimepicker({
                    lang:'en',
                    timepicker:false,
                    format:'m/d/Y',
                    formatDate:'m/d/Y',
                    theme:'dark'
                });
                var e = c = '0';
                $('#SumDay').change(function() {
                    
                    var a=$("#SumDay").val();
                    var b=$("#SumMan").val();
                    var d=$("#KonsManHour").val();
                    var c=a*b*8;
                    var e=c*d;
                    $("#LimMax").val(c);
                    $("#EstManHour").val(e);
                });
                $('#SumMan').change(function() {
                    var a=$("#SumDay").val();
                    var b=$("#SumMan").val();
                    var d=$("#KonsManHour").val();
                    var c=a*b*8;
                    var e=c*d;
                    $("#LimMax").val(c);
                    $("#EstManHour").val(e);
                    PASSING_MANHOUR(e,c);
                });
                PASSING_MANHOUR(e,c);
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    });
}
function PASSING_MANHOUR(ValE,ValC)
{
    $("#BtnInputManHour").click(function(){
        var Expense = $("#Expense").val();
        var MachHour = $("#MachHour").val();
        var MatCost = $("#MatCost").val();
        var EstDate = $("#txtFilterTanggal1").val();
        var NamaDM = $("#NamaDM").val();
        var EstHalf = $("#EstHalf").val();
        var Loc = $("#FilLokasi").val();
        if(MachHour == "")
        {
            MachHour = 0;
        }
        if(MatCost == "")
        {
            MatCost = 0;
        }
        if(Loc == "--Pilih Lokasi--")
        {
            $("#FilLokasi").focus();
            return false;
        }
        $("#ManHourModal").modal('hide');
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(1)").text(ValE).css('text-align','right');
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(2)").text(MachHour).css('text-align','right');
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(3)").text(MatCost).css('text-align','right');
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(4)").text(ValC).css('text-align','right');
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(5)").text(EstDate).css('text-align','center');
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(6)").text(EstHalf).css('text-align','center');
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(7)").text(NamaDM);
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(8)").text(Loc);
    });
}
function BTN_SUBMIT(Quote)
{
    $("#ModalProsesCreate").on('show.bs.modal', function (event) {
        var FilPM = $("#FilPM").val();
        var FilCOPM = $("#FilCOPM").val();
        var FilCategory = $("#FilCategory").val();
        var FilWOP = $("#FILWOP").val();
        var QtyWOP = $("#QtyWOP").val();
        var FilProduct = $("#FilProduct").val();
        var FilWOC = $("#FilWOC").val();
        var JenisWO = $("#JenisWO").val();
        var TipeOrder = $("#TipeOrder").val();
        if($("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(2)").text() === ''){ var Exp1 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(8)").text();
            Exp1 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(2)").text() === ''){ var Exp2 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(8)").text();
            Exp2 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(2)").text() === ''){ var Exp3 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(8)").text();
            Exp3 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(2)").text() === ''){ var Exp4 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(8)").text();
            Exp4 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(2)").text() === ''){ var Exp5 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(8)").text();
            Exp5 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(2)").text() === ''){ var Exp6 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(8)").text();
            Exp6 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(2)").text() === ''){ var Exp7 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(8)").text();
            Exp7 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='LASER BENDING']").find("td:eq(2)").text() === ''){ var Exp8 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='LASER BENDING']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='LASER BENDING']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='LASER BENDING']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='LASER BENDING']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='LASER BENDING']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='LASER BENDING']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='LASER BENDING']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='LASER BENDING']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='LASER BENDING']").find("td:eq(8)").text();
            Exp8 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='INJECTION MOLD ENGINEERING']").find("td:eq(2)").text() === ''){ var Exp9 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='INJECTION MOLD ENGINEERING']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='INJECTION MOLD ENGINEERING']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='INJECTION MOLD ENGINEERING']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='INJECTION MOLD ENGINEERING']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='INJECTION MOLD ENGINEERING']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='INJECTION MOLD ENGINEERING']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='INJECTION MOLD ENGINEERING']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='INJECTION MOLD ENGINEERING']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='INJECTION MOLD ENGINEERING']").find("td:eq(8)").text();
            Exp9 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='MAINTENANCE']").find("td:eq(2)").text() === ''){ var Exp10 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='MAINTENANCE']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='MAINTENANCE']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='MAINTENANCE']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='MAINTENANCE']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='MAINTENANCE']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='MAINTENANCE']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='MAINTENANCE']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='MAINTENANCE']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='MAINTENANCE']").find("td:eq(8)").text();
            Exp10 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='MECHANICAL ENGINEERING']").find("td:eq(2)").text() === ''){ var Exp11 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='MECHANICAL ENGINEERING']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='MECHANICAL ENGINEERING']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='MECHANICAL ENGINEERING']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='MECHANICAL ENGINEERING']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='MECHANICAL ENGINEERING']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='MECHANICAL ENGINEERING']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='MECHANICAL ENGINEERING']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='MECHANICAL ENGINEERING']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='MECHANICAL ENGINEERING']").find("td:eq(8)").text();
            Exp11 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='INFORMATION TECHNOLOGY']").find("td:eq(2)").text() === ''){ var Exp12 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='INFORMATION TECHNOLOGY']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='INFORMATION TECHNOLOGY']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='INFORMATION TECHNOLOGY']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='INFORMATION TECHNOLOGY']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='INFORMATION TECHNOLOGY']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='INFORMATION TECHNOLOGY']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='INFORMATION TECHNOLOGY']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='INFORMATION TECHNOLOGY']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='INFORMATION TECHNOLOGY']").find("td:eq(8)").text();
            Exp12 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='WAREHOUSE']").find("td:eq(2)").text() === ''){ var Exp13 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='WAREHOUSE']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='WAREHOUSE']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='WAREHOUSE']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='WAREHOUSE']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='WAREHOUSE']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='WAREHOUSE']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='WAREHOUSE']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='WAREHOUSE']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='WAREHOUSE']").find("td:eq(8)").text();
            Exp13 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        if($("#FormExpense tr[data-idrows='PROCESS ENGINEERING']").find("td:eq(2)").text() === ''){ var Exp14 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='PROCESS ENGINEERING']").find("td:eq(0)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='PROCESS ENGINEERING']").find("td:eq(1)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='PROCESS ENGINEERING']").find("td:eq(2)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='PROCESS ENGINEERING']").find("td:eq(3)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='PROCESS ENGINEERING']").find("td:eq(4)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='PROCESS ENGINEERING']").find("td:eq(5)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='PROCESS ENGINEERING']").find("td:eq(6)").text();
            var dm1 = $("#FormExpense tr[data-idrows='PROCESS ENGINEERING']").find("td:eq(7)").text();
            var loc1 = $("#FormExpense tr[data-idrows='PROCESS ENGINEERING']").find("td:eq(8)").text();
            Exp14 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1+'+'+loc1;
        }
        var All = Quote+'*'+FilPM+'*'+FilCOPM+'*'+FilCategory+'*'+FilWOP+'*'+QtyWOP+'*'+FilProduct+'*'+FilWOC+'*'+JenisWO+'*'+TipeOrder;
        var All2 = Exp1+'*'+Exp2+'*'+Exp3+'*'+Exp4+'*'+Exp5+'*'+Exp6+'*'+Exp7+'*'+Exp8+'*'+Exp9+'*'+Exp10+'*'+Exp11+'*'+Exp12+'*'+Exp13+'*'+Exp14;
        
        
        var formdata = new FormData();
        formdata.append('DataAtas', All);
        formdata.append('DataBawah', All2);
        $.ajax({
            url: 'project/WOMapping/src/srcInputWOMappingBaru.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnSubmitWO").attr('disabled', true);
                $('#SubmitWO').html("");
                $('#SubmitWO').html("");
            },
            success: function (xaxa) {
                $('#SubmitWO').html("");
                $('#SubmitWO').hide();
                $('#SubmitWO').html(xaxa);
                $('#SubmitWO').fadeIn('fast');
                $("#BtnSubmitWO").blur();
                $("#BtnSubmitWO").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnSubmitWO").blur();
                $('#SubmitWO').html("");
                $("#BtnSubmitWO").attr('disabled', false);
            }
        });
    });
}
</script>