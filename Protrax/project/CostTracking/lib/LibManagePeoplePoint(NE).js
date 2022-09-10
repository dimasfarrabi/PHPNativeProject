$(document).ready(function () {
    $("#btnView").click(function(){
        var Half = $("#FilHalf").val();
        var Category = $("#FilterCustom").val();
        var Keywords = $("#FilterKeywords").val();
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
                BTN_DOWNLOAD(Half,Category,Keywords);
                BTN_IMPORT(Half,Category,Keywords);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#btnView").blur();
                $('#PointContent').html("");
                $("#ContentLoading").remove();
                $("#btnView").attr('disabled', false);
            }
        });
    });
});
function BTN_DOWNLOAD(par1,par2,par3)
{
    $("#BtnDownload").click(function(){
        window.location.href = 'project/costtracking/src/DownloadPeoplePoint.php?par1='+par1+'&&par2='+par2+'&&par3='+par3;
    });
}
function EDIT_POINT(ValCat,ValKey)
{
    $("#ModalEditPoint").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        // var DataTemp = act.data('dcode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/costtracking/FormEditPeoplePoint.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#FormEditPoint').html("");
            },
            success: function (xaxa) {
                $('#FormEditPoint').hide();
                $('#FormEditPoint').html(xaxa);
                $('#FormEditPoint').fadeIn('fast');
                $('#Discretion').change(function() {
                    var p=parseFloat($("#Points").val());
                    var a=parseFloat($("#Discretion").val());
                    var b=parseFloat($("#Exception").val());
                    var c=parseFloat($("#Proporsi").val());
                    if(a > 0)
                    {   
                        var o = ((a/100)*p);
                        var x = c + o;
                        
                    }
                    else
                    {
                        var x = c;
                    }
                    if(b > 0)
                    {
                        var x = b;
                    }
                    $("#Total").val(x);
                });
                $('#Exception').change(function() {
                    var p=parseFloat($("#Points").val());
                    var a=parseFloat($("#Discretion").val());
                    var b=parseFloat($("#Exception").val());
                    var c=parseFloat($("#Proporsi").val());
                    if(a > 0)
                    {   
                        var o = ((a/100)*p);
                        var x = c + o;
                        
                    }
                    else
                    {
                        var x = c;
                    }
                    if(b > 0)
                    {
                        var x = b;
                    }
                    $("#Total").val(x);
                });
                SAVE_POINT(ValCat,ValKey,DataCode);
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    });
}
function SAVE_POINT(Data1,Data2,Data3)
{
    $("#BtnSave").click(function(){
        var Discretion = $("#Discretion").val();
        var Exception = $("#Exception").val();
        var formdata = new FormData();
        formdata.append("Discretion", Discretion);
        formdata.append("Exception", Exception);
        formdata.append("Data1", Data1);
        formdata.append("Data2", Data2);
        formdata.append("Data3", Data3);
        $.ajax({
            url: 'project/costtracking/src/srcUpdatePoint.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#SimpanPoint').html("");
            },
            success: function (xaxa) {
                $('#SimpanPoint').hide();
                $('#SimpanPoint').html(xaxa);
                $('#SimpanPoint').fadeIn('fast');
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    });
}