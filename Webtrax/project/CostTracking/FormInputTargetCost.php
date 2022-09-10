<?php  
require_once("Modules/ModuleCostTracking.php"); 
require_once("Modules/ModuleTarget.php"); 
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
if($AccessLogin == "Reguler")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}


?>
<script src="project/costtracking/lib/libmanagetargetcost.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=25">Manage Target Cost</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12"><h5><strong>Form New Target Cost</strong></h5></div>
            <div class="col-md-12">
                <div>
                    <div class="form-group">
                        <label for="InputSeasonF">Filter Season</label>
                        <select class="form-control" id="InputSeasonF">
                            <option>-- Choose Season --</option><option>OPEN</option>
                            <?php 
                            $QListClosedTimeF = GET_LIST_CLOSEDTIME_NOT_OPEN("",$linkMACHWebTrax);
                            while($RListClosedTimeF = mssql_fetch_assoc($QListClosedTimeF))
                            {
                                $ClosedTime = $RListClosedTimeF['ClosedTime'];
                                ?>
                                <option><?php echo $ClosedTime; ?></option>
                                <?php
                            }                
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="InputSeasonTF">Target Season Closed</label>
                        <select class="form-control" id="InputSeasonTF">
                            <option>-- Choose Season --</option>
                            <?php 
                            $QListClosedTimeTF = GET_LIST_CLOSEDTIME_NOT_OPEN("",$linkMACHWebTrax);
                            while($RListClosedTimeTF = mssql_fetch_assoc($QListClosedTimeTF))
                            {
                                $ClosedTime = $RListClosedTimeTF['ClosedTime'];
                                ?>
                                <option><?php echo $ClosedTime; ?></option>
                                <?php
                            }                
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="InputQuoteCategoryF">Quote Category</label>
                        <select class="form-control" id="InputQuoteCategoryF">
                            <option>-- Choose Category --</option>
                            <?php 
                            $QListQuoteCategoryF = GET_LIST_QUOTE_CATEGORY("PSL",$linkMACHWebTrax);
                            while($RListQuoteCategoryF = mssql_fetch_assoc($QListQuoteCategoryF))
                            {
                                $QuoteCategory = $RListQuoteCategoryF['QuoteCategory'];
                                ?>
                                <option><?php echo $QuoteCategory; ?></option>
                                <?php
                            }               
                            ?>
                        </select>
                    </div>
                    <div id="ResFormQuote">
                    <div class="form-group">
                        <label for="InputQuoteF">Quote</label>
                        <select class="form-control" id="InputQuoteF" disabled></select>
                    </div>
                    </div>
                    <div id="ResFormExpense">
                    <div class="form-group">
                        <label for="InputExpenseF">Expense</label>
                        <select class="form-control" id="InputExpenseF" disabled><?php 
                        $QListExpense = GET_LIST_EXPENSE_ALLOCATION($linkMACHWebTrax);
                        while($RListExpense = mssql_fetch_assoc($QListExpense))
                        {
                            ?>
                            <option><?php echo trim($RListExpense['ExpenseOption']); ?></option>
                            <?php
                        }
                        ?></select>
                    </div>
                    <div class="form-group">
                        <label for="InputTypeF">Type</label>
                        <select class="form-control" id="InputTypeF" disabled>
                            <option>PEOPLE</option>
                            <option>MACHINE</option>
                            <option>MATERIAL</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="InputTargetCostF">Target Cost</label>
                        <input type="text" class="form-control" id="InputTargetCostF" placeholder="0.00" disabled>
                    </div>
                    <div class="form-group">
                        <label for="InputLocationF">Location</label>
                        <select class="form-control" id="InputLocationF" disabled>
                            <option>PSL</option>
                            <option>PSM</option>
                        </select>
                    </div>
                    <button class="btn btn-dark btn-labeled" id="BtnNewTarget" disabled>Add New</button>        
                    </div>
                </div>
            </div>
            <div class="col-md-12">&nbsp;</div>
            <div class="col-md-12"><div id="InfoNewTarget"></div></div>
            <div class="col-md-12"><hr></div>
        </div>
        <div class="row" id="ResultMsg"></div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="InputClosedTime">Season</label>
                        <select class="form-control" id="InputClosedTime"><?php 
                        $NoLoopClosedTime = 1;
                        $TempClosedTime = "";
                        $QListClosedTime = GET_LIST_CLOSEDTIME_NOT_OPEN("",$linkMACHWebTrax);
                        while($RListClosedTime = mssql_fetch_assoc($QListClosedTime))
                        {
                            $ClosedTime = $RListClosedTime['ClosedTime'];
                            if($NoLoopClosedTime == 1)
                            {
                                $TempClosedTime = $ClosedTime;
                            }
                            ?>
                            <option><?php echo $ClosedTime; ?></option>
                            <?php
                            $NoLoopClosedTime++;
                        }                
                        ?></select>
                    </div>
                    <div class="form-group">
                        <label for="InputQuoteCategory">QuoteCategory</label>
                        <select class="form-control" id="InputQuoteCategory"><?php 
                        $NoLoopQuoteCategory = 1;
                        $TempQuoteCategory = "";
                        $QListQuoteCategory = GET_LIST_QUOTE_CATEGORY("PSL",$linkMACHWebTrax);
                        while($RListQuoteCategory = mssql_fetch_assoc($QListQuoteCategory))
                        {
                            $QuoteCategory = $RListQuoteCategory['QuoteCategory'];
                            if($NoLoopQuoteCategory == 1)
                            {
                                $TempQuoteCategory = $QuoteCategory;
                            }
                            ?>
                            <option><?php echo $QuoteCategory; ?></option>
                            <?php
                            $NoLoopQuoteCategory++;
                        }                
                        ?></select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-dark btn-labeled" id="BtnViewData">View Data</button> 
                    </div>           
                </div>
            </div>
            <div class="col-sm-12"><hr></div>
        </div>
        <div id="ContentView">
            <span><strong>Season</strong> : <?php echo $TempClosedTime; ?>.<strong>Quote Category</strong> : <?php echo $TempQuoteCategory; ?>.</span>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="TableData">
                    <thead class="theadCustom">    
                        <tr>
                            <th class="text-center" width="10">No</th>
                            <th class="text-center">Quote</th>
                            <th class="text-center" width="100">Season</th>
                            <th class="text-center">Expense</th>
                            <th class="text-center" width="100">CostType</th>
                            <th class="text-center" width="100">TargetCost</th>
                            <th class="text-center" width="50">Location</th>
                            <th class="text-center" width="50">#</th>
                        </tr>
                    </thead>
                    <tbody><?php 
                    $NoListDataTarget = 1;
                    $QListDataTargetCost = GET_LIST_TARGET_COST($TempClosedTime,$TempQuoteCategory,$linkMACHWebTrax);
                    while ($RListDataTargetCost = mssql_fetch_assoc($QListDataTargetCost))
                    {
                        $ValQuote = trim($RListDataTargetCost['Quote']);
                        $ValHalf = trim($RListDataTargetCost['Half']);
                        $ValExpense = trim($RListDataTargetCost['ExpenseAllocation']);
                        $ValCostType = trim($RListDataTargetCost['CostType']);
                        $ValTargetCost = trim($RListDataTargetCost['TargetCost']);
                        $ValTargetCost = number_format((float)$ValTargetCost, 2, '.', ',');
                        $ValLocation = trim($RListDataTargetCost['Location']);
                        $ValID = trim($RListDataTargetCost['Idx']);
                        $ValToken = base64_encode(base64_encode("ID".$ValID));
                        ?>    
                        <tr>
                            <td class="text-center"><?php echo $NoListDataTarget; ?></td>
                            <td class="text-left"><?php echo $ValQuote; ?></td>
                            <td class="text-center"><?php echo $ValHalf; ?></td>
                            <td class="text-left"><?php echo $ValExpense; ?></td>
                            <td class="text-left"><?php echo $ValCostType; ?></td>
                            <td class="text-right"><?php echo $ValTargetCost;?></td>
                            <td class="text-center"><?php echo $ValLocation; ?></td>
                            <td class="text-center"><span class="PointerList EditTarget" data-datatoken="<?php echo $ValToken; ?>" title="Edit Target"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></span>&nbsp;<span class="PointerList DeleteTarget" data-datatoken="<?php echo $ValToken; ?>" title="Delete Target"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span></td>
                        </tr>
                        <?php
                        $NoListDataTarget++;
                    }
                    ?></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<span id="Temporary"></span>