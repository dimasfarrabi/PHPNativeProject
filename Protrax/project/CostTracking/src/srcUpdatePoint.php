<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModulePeoplePoint.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $Discretion = htmlspecialchars(trim($_POST['Discretion']), ENT_QUOTES, "UTF-8");
    $Exception = htmlspecialchars(trim($_POST['Exception']), ENT_QUOTES, "UTF-8");
    $Data1 = htmlspecialchars(trim($_POST['Data1']), ENT_QUOTES, "UTF-8");
    $Data2 = htmlspecialchars(trim($_POST['Data2']), ENT_QUOTES, "UTF-8");
    $Data3 = base64_decode(htmlspecialchars(trim($_POST['Data3']), ENT_QUOTES, "UTF-8"));
    $arr = explode("*",$Data3);
    $Nama = $arr[0];
    $Divisi = $arr[1];
    $Half = $arr[2];
    // echo "$Discretion >> $Exception >> $Data1 >> $Data2 >> $Data3";
    $Update = UPDATE_PEOPLE_POINT($Nama,$Divisi,$Half,$Discretion,$Exception,$linkMACHWebTrax);
    if($Update == 'TRUE')
    {
        ?>
        <script>
        alert('Update Success');
        $('#ModalEditPoint').modal('hide');
        var Half = '<?php echo $Half; ?>';
        var Category = '<?php echo $Data1; ?>';
        var Keywords = '<?php echo $Data2; ?>';
        var formdata = new FormData();
        formdata.append('Half', Half);
        formdata.append('Category', Category);
        formdata.append('Keywords', Keywords);
        $.ajax({
            url: 'project/costtracking/ManagePeoplePointContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#btnView").attr('disabled', true);
                $('#PointContent').html("");
                $("#PointContent").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#PointContent').html("");
                $('#ListReport').html("");
                $('#ListOTSTop').html("");
            },
            success: function (xaxa) {
                $('#PointContent').html("");
                $('#PointContent').hide();
                $('#PointContent').html(xaxa);
                $('#PointContent').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#btnView").blur();
                $("#btnView").attr('disabled', false);
                $('#TablePeoplePoint').DataTable( {
                    "iDisplayLength": 25,
                    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
                });
                EDIT_POINT(Category,Keywords);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#btnView").blur();
                $('#PointContent').html("");
                $("#ContentLoading").remove();
                $("#btnView").attr('disabled', false);
            }
        });
        </script>
        <?php
    }
    else
    {
        ?>
        <script>
            alert('Failed to Update');
        </script>
        <?php
    }
}
?>