$(document).ready(function () 
{
    var i = 0;
    function LoopSecond() {
        i++;
        if (i <= 15) {
            setTimeout(LoopSecond, 1000);
        }
        else
        {
            i = 1;
            LOAD_MONITOR();
            setTimeout(LoopSecond, 1000);
        }
    }
    LoopSecond();
});
function LOAD_MONITOR()
{
    var formdata = new FormData();
    formdata.append("Active", "10");
    $.ajax({
        url: 'project/Report/MonitoringMachineContent.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        success: function (xaxa) {
            $("#ContentResult").html(xaxa);
			$("#ContentResult").fadeIn();
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#ContentResult").html("");
        }
    });
}