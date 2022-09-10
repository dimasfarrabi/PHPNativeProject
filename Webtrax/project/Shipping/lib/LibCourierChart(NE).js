$(document).ready(function(){
    VIEW_ALL();
    $("#BtnCourierChart").click(function(){
        var InputYear = $("#InputSeason option:selected").val().trim();
        var formdata = new FormData();
        if(InputYear == 'ALL')
        {
            VIEW_ALL();
        }
        else
        {
            formdata.append("InputYear", InputYear);
            $.ajax({
                url: 'project/Shipping/CourierChartContentV2.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'POST',
                beforeSend: function () {
                    $('#BtnCourierChart').attr('disabled', true);
                    $('#ChartContentAll').hide();
                    $('#ChartContent').html("");
                    $("#ChartContent").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                },
                success: function (xaxa) {
                    $("#ContentLoading").remove();
                    $('#ChartContentAll').hide();
                    $('#ChartContent').html(xaxa);
                    $('#ChartContent').fadeIn('fast');
                    $('#BtnCourierChart').attr('disabled', false);
                },
                error: function () {
                    alert('Request cannot proceed!');
                    $('#BtnCourierChart').attr('disabled', false);
                    $('#ChartContentAll').hide();
                }
            });
        }
    });
});
function VIEW_ALL()
{
    $.ajax({
        url: 'project/Shipping/CourierChartContentAll.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('#ChartContentAll').html("");
            $('#ChartContent').hide();
            $("#ChartContentAll").append('<div class="col-sm-12" id="ContentLoading2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
        },
        success: function (xaxa) {
            $("#ContentLoading2").remove();
            $('#ChartContent').hide();
            $('#ChartContentAll').html(xaxa);
            $('#ChartContentAll').fadeIn('fast');
        },
        error: function () {
            alert('Request cannot proceed!');
            $("#ContentLoading2").remove();
            $('#ChartContent').hide();
        }
    });
}