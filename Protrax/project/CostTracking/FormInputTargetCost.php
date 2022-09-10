<?php
require_once("project/CostTracking/Modules/ModuleCostTracking.php");
require_once("project/CostTracking/Modules/ModuleTarget.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");

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
    $RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
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
    if($RDataUserWebtrax['MnAdmin'] != "1")  
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
    if($RDataUserWebtrax['MnCostTracking'] != "1")
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}


?>
<script src="project/CostTracking/lib/LibManageTargetCost.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cost Tracking : Manage Target Cost</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12"><h5><strong>Form New Target Cost</strong></h5></div>
            <div class="col-md-12 mb-2">
                <label for="InputSeasonF" class="form-label fw-bold">Filter Season</label>
                <select class="form-select" id="InputSeasonF">
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
            <div class="col-md-12 mb-2">
                <label for="InputSeasonTF" class="form-label fw-bold">Target Season Closed</label>
                <select class="form-select" id="InputSeasonTF">
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
            <div class="col-md-12 mb-2">
                <label for="InputQuoteCategoryF" class="form-label fw-bold">Quote Category</label>
                <select class="form-select" id="InputQuoteCategoryF">
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
                <div class="col-md-12 mb-2">
                    <label for="InputQuoteF" class="form-label fw-bold">Quote</label>
                    <select class="form-select" id="InputQuoteF" disabled></select>
                </div>
            </div>
            <div id="ResFormExpense">
                <div class="col-md-12 mb-2">
                    <label for="InputExpenseF" class="form-label fw-bold">Expense</label>
                    <select class="form-select" id="InputExpenseF" disabled><?php 
                    $QListExpense = GET_LIST_EXPENSE_ALLOCATION($linkMACHWebTrax);
                    while($RListExpense = mssql_fetch_assoc($QListExpense))
                    {
                        ?>
                        <option><?php echo trim($RListExpense['ExpenseOption']); ?></option>
                        <?php
                    }
                    ?></select>
                </div>
                <div class="col-md-12 mb-2">
                    <label for="InputTypeF" class="form-label fw-bold">Type</label>
                    <select class="form-select" id="InputTypeF" disabled>
                        <option>PEOPLE</option>
                        <option>MACHINE</option>
                        <option>MATERIAL</option>
                    </select>
                </div>
                <div class="col-md-12 mb-2">
                    <label for="InputTargetCostF" class="form-label fw-bold">Target Cost</label>
                    <input type="text" class="form-control" id="InputTargetCostF" placeholder="0.00" disabled>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="InputLocationF" class="form-label fw-bold">Location</label>
                    <select class="form-select" id="InputLocationF" disabled>
                        <option>PSL</option>
                        <option>PSM</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-dark btn-labeled" id="BtnNewTarget" disabled>Add New</button>
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
            <div class="col-sm-12 fw-bold"><h5><strong>Pencarian Data</strong></h5></div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="InputClosedTime" class="form-label fw-bold mt-2">Season</label>
                    </div>
                    <div class="col-sm-8 mb-2">
                        <select class="form-select" id="InputClosedTime"><?php 
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
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-5">
                        <label for="InputQuoteCategory" class="form-label fw-bold mt-2">QuoteCategory</label>
                    </div>
                    <div class="col-sm-7 mb-2">
                        <select class="form-select" id="InputQuoteCategory"><?php 
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
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-3">
                        <label for="InputExpense" class="form-label fw-bold mt-2">Expense</label>
                    </div>
                    <div class="col-sm-9 mb-2">
                        <select class="form-select" id="InputExpense"><option>All Expense</option><?php
                        $QListExpense2 = GET_LIST_EXPENSE_ALLOCATION($linkMACHWebTrax);
                        while($RListExpense2 = mssql_fetch_assoc($QListExpense2))
                        {
                            ?>
                            <option><?php echo trim($RListExpense2['ExpenseOption']); ?></option>
                            <?php
                        }              
                        ?></select>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-12">
                        <button class="btn btn-dark btn-labeled" id="BtnViewData">View Data</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12"><hr></div>
        </div>
        <div id="ContentView">
            <div><strong>Season</strong> : <?php echo $TempClosedTime; ?>.<strong>Quote Category</strong> : <?php echo $TempQuoteCategory; ?>. <strong>Expense</strong> : All Expense.</div>
            <div class="table-responsive">
                <table class="table table-hover" id="TableData">
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
                            <td class="text-center"><span class="PointerList EditTarget" data-datatoken="<?php echo $ValToken; ?>" title="Edit Target"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>&nbsp;<span class="PointerList DeleteTarget" data-datatoken="<?php echo $ValToken; ?>" title="Delete Target"><i class="bi bi-trash-fill" aria-hidden="true"></i></span></td>
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