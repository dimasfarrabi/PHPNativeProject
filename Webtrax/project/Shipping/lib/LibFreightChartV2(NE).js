$(document).ready(function(){
    VIEW_ALL();
    $("#BtnView").click(function(){
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
                url: 'project/Shipping/FreightQtyWeightChartContentV2.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'POST',
                beforeSend: function () {
                    $('#BtnView').attr('disabled', true);
                    $('#FreightAll').hide();
                    $('#FreightChart').html("");
                    $("#FreightChart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                },
                success: function (xaxa) {
                    $("#ContentLoading").remove();
                    $('#FreightAll').hide();
                    $('#FreightChart').html(xaxa);
                    $('#FreightChart').fadeIn('fast');
                    $('#BtnView').attr('disabled', false);
                    $("#TableFreightContent").DataTable({
                        "pagingType": "full"
                    });
                    MODAL_ALL_DETAILS();
                },
                error: function () {
                    alert('Request cannot proceed!');
                    $('#BtnView').attr('disabled', false);
                    $('#FreightAll').hide();
                }
            });
        }
    });
});
function VIEW_ALL()
{
    $.ajax({
        url: 'project/Shipping/FreightQtyWeightChartContentAll.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('#FreightAll').html("");
            $('#FreightChart').hide();
            $("#FreightAll").append('<div class="col-sm-12" id="ContentLoading2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
        },
        success: function (xaxa) {
            $("#ContentLoading2").remove();
            $('#FreightChart').hide();
            $('#FreightAll').html(xaxa);
            $('#FreightAll').fadeIn('fast');
            $("#AllTable").DataTable({
                "pagingType": "full",
                order: [[0, 'desc']]
            });
            MODAL_ALL_DETAILS();
        },
        error: function () {
            alert('Request cannot proceed!');
            $("#ContentLoading2").remove();
            $('#FreightChart').hide();
        }
    });
}
function MODAL_ALL_DETAILS()
{
    $("#DetailAll").on('show.bs.modal', function (event) {
    var act = $(event.relatedTarget);
    var DataCode = act.data('ecode');
    var formdata = new FormData();
    formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/Shipping/FreightDetailALL.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#DetailContent').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#DetailContent').hide();
                $('#DetailContent').html(xaxa);
                $('#DetailContent').fadeIn('fast');
                $("#DetailALL").DataTable({
            });
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
}