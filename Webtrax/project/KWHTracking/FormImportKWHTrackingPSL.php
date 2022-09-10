<?php
session_start();
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
$Yesterday = date("m/d/Y",strtotime("-1 day"));

require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleKWHTracking.php"); 

?><div class="col-sm-12"><h4 class="TitleGroup">Import Data Electricy Usage</h4></div>
<div class="col-sm-12">&nbsp;</div>
<div class="col-sm-12">
    <div class="row">    
        <div class="col-md-3">
            <div class="row">
                <div class="col-sm-12">[<span class="DownloadTemplate" id="DownloadTemplate">Download Template</span>]</div>
                <form method="post" action="project/kwhtracking/src/srcimportkwhtrackingpsl.php" id="FormImportKWHTracking" enctype="multipart/form-data">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="InputFile">File input</label>
                        <input type="file" id="InputFile" name="InputFile" accept=".csv">
                        <p class="help-block"><i>Format file .csv, format date <?php echo date("d/m/Y"); ?>.</i></p>
                    </div>
                </div>
                <div class="col-sm-5">
                    <button type="submit" id="BtnSubmit" class="btn btn-md btn-dark">Import Data</button>
                </div>
                </form>
                <?php /*<div class="col-sm-7">
                    <button id="BtnMove" class="btn btn-md btn-dark">Go to Generate Page</button>
                </div>*/ ?>
                <div class="col-sm-7">&nbsp;</div>
                <div class="col-sm-12">&nbsp;</div>
                <div class="col-sm-12"><i>*)Please generate after import data.</i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="InputDate">Date</label>
                        <div class="controls">
                            <div class="input-group"><input id="InputDate" name="InputDate" type="text" class="date-picker form-control" value="<?php echo $Yesterday; ?>" readonly /><label for="InputDate" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="InputUsage">Usage</label>
                        <input type="text" class="form-control form-control-custom" id="InputUsage" name="InputUsage" required>
                    </div>                        
                </div>
                <div class="col-sm-5">
                    <button id="BtnAdd" class="btn btn-md btn-dark">Add Data</button>
                </div>
                <div class="col-sm-7">&nbsp;</div>
            </div>
            <div class="row" id="ResultMsg"></div>
        </div>
        <div class="col-md-6" id="TableTopData">
            <strong>Table Top 10 Result</strong>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="TableData">
                    <thead class="theadCustom">    
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Usage</th>
                        </tr>
                    </thead>
                    <tbody><?php 
                    $QData = GET_LIST_TOP10_KWH_ADDED("PSL",$linkHRISWebTrax);
                    $No = 1;
                    while($RData = mssql_fetch_assoc($QData))
                    {
                        $ValDate = date("m/d/Y",strtotime($RData['DateLog']));
                        $ValUsage = trim($RData['KWH']);
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $No; ?></td>
                            <td class="text-center"><?php echo $ValDate; ?></td>
                            <td class="text-center"><?php echo $ValUsage; ?></td>
                        </tr>
                        <?php
                        $No++;
                    }
                    ?></tbody>
                </table>
            </div>
		</div>
    </div>
</div>



