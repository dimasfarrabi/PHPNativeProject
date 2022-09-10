$(document).ready(function () {
    $('#txtFilterTanggal1').datetimepicker({
        lang:'en',
        timepicker:false,
        format:'m/d/Y',
        formatDate:'m/d/Y',
        theme:'dark'
    });
    $('#txtFilterTanggal2').datetimepicker({
        lang:'en',
        timepicker:false,
        format:'m/d/Y',
        formatDate:'m/d/Y',
        theme:'dark'
    });
    $('#TableTimetrack').removeAttr('width').DataTable({
        "iDisplayLength": 5,
        "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        scrollY:        "430px",
        scrollX:        true,
        scrollCollapse: true,
        "autoWidth": true
    });
    $("#TableTimetrack tbody").css("font-size", "11px");
    $("#BtnViewData").click(function(){
        var StartDate = $("#txtFilterTanggal1").val();
        var EndDate = $("#txtFilterTanggal2").val();
        var Season = $("#FilterSeason").val();
        var Category = $("#FilterCustom").val();
        var Keywords = $("#FilterKeywords").val().trim();
        var formdata = new FormData();
        formdata.append('ValStartDate', StartDate);
        formdata.append('ValEndDate', EndDate);
        formdata.append('ValSeason', Season);
        formdata.append('ValCategory', Category);
        formdata.append('ValKeywords', Keywords);
        $.ajax({
            url: 'project/Report/DownloadTimeTrackingAjax.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewData").attr('disabled', true);
                $('#ContentResult').html("");
                $("#ContentResult").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResult').html("");
            },
            success: function (xaxa) {
                $('#ContentResult').html("");
                $('#ContentResult').hide();
                $('#ContentResult').html(xaxa);
                $('#ContentResult').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewData").blur();
                $("#BtnViewData").attr('disabled', false);                
                $('#TableTimetrack').removeAttr('width').DataTable( {
                    "iDisplayLength": 5,
                    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    scrollY:        "430px",
                    scrollX:        true,
                    scrollCollapse: true,
                    autoWidth: true
                });
                DOWNLOAD_TIMETRACK();
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewData").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#BtnViewData").attr('disabled', false);
            }
        });
    });
});
function DOWNLOAD_TIMETRACK()
{
    $("#BtnDownload").click(function(){
        var DataStart = $(this).data("start");
        var DataEnd = $(this).data("end");
        var DataSeason = $(this).data("season");
        var DataCategory = $(this).data("category");
        var DataKeywords = $(this).data("keywords");
        window.location.href = 'project/Report/src/DownloadResultTimeTracking.php?ds='+DataStart+'&&de='+DataEnd+'&&sea='+DataSeason+'&&cat='+DataCategory+'&&key='+DataKeywords;
    });
}