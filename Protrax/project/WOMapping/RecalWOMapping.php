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
}<script src="project/WOMapping/lib/LibRecalculateWO.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
*/

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">WO Mapping : Recalculate WO Mapping</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <h6 class="card-header text-white bg-secondary">Recalculate Qty Quote</h6>
            <div class="card-body pt-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="FilHalf1" class="form-label fw-bold">Pilih Closed Time</label>
                            <select class="form-select form-select-sm" id="FilHalf1">
                                <?php
                                $data = LIST_HALF("With Open","TOP 2",$linkMACHWebTrax);
                                while($res=sqlsrv_fetch_array($data))
                                {
                                    $ValHalf = trim($res['ClosedTime']);
                                ?>
                                    <option><?php echo $ValHalf; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" style="margin-top:28px;">
                        <input class="form-check-input" type="checkbox" id="checkOpen">
                        <label style="cursor:pointer;" class="form-check-label" for="checkOpen">Include Open</label>
                    </div>
                    <div class="col-md-2" style="margin-top:28px;">
                        <button class="btn btn-sm btn-dark" id="BtnSelect1">Recalculate</button>
                    </div>
                </div>
                <div class="col-md-12 pt-3">
                    <h6>Log Aktivitas<img src="../images/ajax-loader1.gif" id="ImgLoading1" class="load_img"/></h6>
                    <div id="InfoLog">
                        <textarea id="TextInfoLog" class="form-control" style="height: 200px; background-color:#00008B; color: #FFFF00; font-size: 12px;" readonly></textarea>
                    </div>
                </div>
                <div id="recalQty"><i>Last Recalculate: </i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <h6 class="card-header text-white bg-secondary">Recalculate WO Closed Periodic</h6>
            <div class="card-body pt-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="FilHalf2" class="form-label fw-bold">Pilih Closed Time</label>
                            <select class="form-select form-select-sm" id="FilHalf2">
                                <?php
                                    $datax = LIST_HALF("none","",$linkMACHWebTrax);
                                    while($resx=sqlsrv_fetch_array($datax))
                                    {
                                        $ValHalf = trim($resx['ClosedTime']);
                                    ?>
                                        <option><?php echo $ValHalf; ?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="FilCategory" class="form-label fw-bold">Pilih Quote Category</label>
                        <select class="form-select form-select-sm" id="FilCategory">
                            <option>Quote</option>
                            <option>Unquote</option>
                        </select>
                    </div>
                    <div class="col-md-2" style="margin-top:28px;">
                        <button class="btn btn-sm btn-dark" id="BtnSelect2">Recalculate</button>
                    </div>
                </div>
                <div class="col-md-12 pt-3">
                    <h6>Log Aktivitas<img src="../images/ajax-loader1.gif" id="ImgLoading2" class="load_img"/></h6>
                    <div id="InfoLog2">
                        <textarea id="TextInfoLog2" class="form-control" style="height: 200px; background-color:#00008B; color: #FFFF00; font-size: 12px;" readonly></textarea>
                    </div>
                </div>
                <div id="recalPeriodic"><i>Last Recalculate: </i></div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () { 
    $("#ImgLoading1").hide();
    $("#ImgLoading2").hide();
    $("#BtnSelect1").click(function(){
        $("#TextInfoLog").val('');
        var ValHalf = $("#FilHalf1").val();
        var UsedOpen = 'off';
        if($('#checkOpen').is(':checked'))
        {
            UsedOpen = 'on';
        }
        var formdata = new FormData();
        formdata.append('ValHalf', ValHalf);
        formdata.append('UsedOpen', UsedOpen);
        $.ajax({
            url: 'project/WOMapping/src2/RecalculateQtyQuote.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnSelect1").attr('disabled', true);
                $("#ImgLoading1").show();
            },
            success: function (xaxa) {
                $("#ImgLoading1").hide();
                var Res = xaxa;
                const ArrRes = Res.split("*");
                for(let i = 0; i < ArrRes.length; i++){ 
                    var IDRes = ArrRes[i].split("#");
                    if(IDRes[1] == "N")
                    {
                        var TextLog = $("#TextInfoLog").val();
                        var NewTextLog = TextLog + "[" + IDRes[0] + "] Update Status :[ERROR] \n";
                        $("#TextInfoLog").val(NewTextLog);
                        alert('Error Found');
                        return false;
                    }
                    else
                    {
                        var TextLog = $("#TextInfoLog").val();
                        var NewTextLog = TextLog + "[" + IDRes[0] + "] Update Status :[SUCCESS] \n";
                        $("#TextInfoLog").val(NewTextLog);
                    }
                }
                $("#BtnSelect1").attr('disabled', false);
                $("#TextInfoLog").animate({scrollTop:$("#TextInfoLog")[0].scrollHeight - $("#TextInfoLog").height()},1500,
                function(){
                })
            },
            error: function () {
                $("#ImgLoading1").hide();
                alert("Request cannot proceed!");
                $("#BtnSelect1").attr('disabled', false);
            }
        });
    });
    $("#BtnSelect2").click(function(){
        var ValHalf = $("#FilHalf2").val();
        var ValCat = $("#FilCategory").val();
        var formdata = new FormData();
        formdata.append('ValHalf', ValHalf);
        formdata.append('ValCat', ValCat);
        $.ajax({
            url: 'project/WOMapping/src2/RecalculatePeriodicQuote.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ImgLoading2").show();
                $("#BtnSelect2").attr('disabled', true);
            },
            success: function (xaxa) {
                $("#ImgLoading2").hide();
                var Res = xaxa;
                const ArrRes = Res.split("*");
                for(let i = 0; i < ArrRes.length; i++){ 
                    var TextLog = $("#TextInfoLog2").val();
                    var NewTextLog = TextLog + ">" + ArrRes[i] + " \n";
                    $("#TextInfoLog2").val(NewTextLog);
                }
                $("#BtnSelect2").attr('disabled', false);
                $("#TextInfoLog2").animate({scrollTop:$("#TextInfoLog2")[0].scrollHeight - $("#TextInfoLog2").height()},1500,
                function(){
                })
            },
            error: function () {
                $("#ImgLoading2").hide();
                alert("Request cannot proceed!");
                $("#BtnSelect2").attr('disabled', false);
            }
        });
    });
});
</script>