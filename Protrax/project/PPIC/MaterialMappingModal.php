<?php
require_once("../../ConfigDB.php");
require_once("Modules/ModuleNewBCPartJob.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    $ArrCodeDec = explode("*",$ValCodeDec);
    $WO = $ArrCodeDec[0];
    $Machine = $ArrCodeDec[1];
    $MachineCode = $ArrCodeDec[2];
    $Location = $ArrCodeDec[3];
    echo "$WO >> $Machine";
?>
<div class="col-md-12">
    <form id="RadioFilter" style="margin-top:30px">
        <label class="radio-inline">
        <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Lock" checked>&nbsp;Lock Machine Mapping</label>
        <br></br><label class="radio-inline">
        <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter Change" value="Change">&nbsp;Change And Lock Machine Mapping</label>
    </form>
    <select class="form-select" id="SelectMachine" style="margin-top:10px" disabled="disabled">
    <?php
        if($Location == 'PSL')
        {
            $QListMach = MACHINE_LIST($linkMACHWebTrax);
        }
        else
        {
            $QListMach = MACHINE_LIST_PSM($linkMACHWebTrax);
        }
            while($RListMach = sqlsrv_fetch_array($QListMach))
            {
            $NamaMesin = trim($RListMach['NamaMesin']);
            $Kode = trim($RListMach['KodeMesin']);
            ?>
            <option value="<?php echo $Kode;?>"><?php echo $NamaMesin; ?></option>
            <?php
            }                
            ?>
    </select>
</div>
<div class="col-md-12" style="margin-top:10px">
    <button id="ButtonSave" type="button" class="btn btn-success btn-labeled block" style="width: 100%;" onclick="SaveMapping()">Save</button>
</div>
<div class="col-md-12" id="SaveData"></div>
<?php
}
?>
<script type="text/javascript">
   $("input[name='RadioFilter']").click(function() { 
      $("#SelectMachine").prop("disabled",true);
      if($(this).hasClass('Change')) {
      $("#SelectMachine").prop("disabled",false);
      }
   });
   function SaveMapping() {
        $('#ButtonSave').attr('disabled', true);
        var Fil1 = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var KodeMesin = $('#SelectMachine option:selected').val();
        var WorkOrder = '<?php echo $WO; ?>';
        var Location = '<?php echo $Location; ?>';
        if(Fil1 == 'Lock')
        {
            KodeMesin = '<?php echo $MachineCode; ?>';
        }
        var formdata = new FormData();
        formdata.append("Fil1", Fil1);
        formdata.append("KodeMesin", KodeMesin);
        formdata.append("WorkOrder", WorkOrder);
        formdata.append("Location", Location);
        $.ajax({
            url: 'project/ppic/MaterialMappingSaver.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#SaveData').html("");
                $('#SaveData').html("");
            },
            success: function(xaxa){
                $('#SaveData').hide();
                $('#SaveData').html(xaxa);
                $('#SaveData').fadeIn('fast');
            },
            error: function() {
                alert('Request cannot proceed!');
            }
        });
    }
</script>