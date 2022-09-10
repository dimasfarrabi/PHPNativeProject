<?php
 
 
// require_once("src/Modules/ModuleLogin.php");
$uid = "sa";
$pwd = "12345";
$serverName = "localhost\SQLEXPRESS";
$connectionInfo = array( "UID"=>$uid,
"PWD"=>$pwd,
"Database"=>"FMLX-MACH");
$linkMACHWebTrax = sqlsrv_connect( $serverName, $connectionInfo);

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function INSERT_DATA2($ValDevice,$ValBit,$ValBatt,$link)
{
	$sql = "INSERT INTO [FMLX-WEBTRAX].[dbo].[T_IOT_SPINDLE_MONITORING]
           ([DateRecord]
            ,[MachineStatus]
            ,[DeviceID]
            ,[DeviceBattery])
            VALUES (CURRENT_TIMESTAMP, '$ValBit', '$ValDevice', '$ValBatt')";
	$query = mssql_query($sql,$link);
	return$query;
}
/*
function INSERT_DATA($ValMachineName,$ValDevice,$ValBatt,$link)
{
	$sql = "INSERT INTO [FMLX-WEBTRAX].[dbo].[T_IOT_SPINDLE_RUNTIME]
           ([DateCreate]
           ,[MachineName]
           ,[DeviceID]
           ,[FullStart]
           ,[Location]
           ,[DeviceBatt])
            VALUES (CURRENT_TIMESTAMP, '$ValMachineName', '$ValDevice', CURRENT_TIMESTAMP, 'PSL', '$ValBatt')";
	$query = mssql_query($sql,$link);
	return$query;
}
function UPDATE_DATA($ValMachineName,$ValDevice,$ValBatt,$link)
{
    $sql = "UPDATE TOP (1) [FMLX-WEBTRAX].[dbo].[T_IOT_SPINDLE_RUNTIME]
    SET [FullEnd] = CURRENT_TIMESTAMP,
    [RunMinute] = datediff(MINUTE,ISNULL(FullStart,0),ISNULL(CURRENT_TIMESTAMP,0)),
    [DeviceBatt] = '$ValBatt'
    WHERE MachineName = '$ValMachineName'
    AND DeviceID = '$ValDevice'
    AND FullEnd IS NULL
    AND FullStart IS NOT NULL
    AND FullStart = (SELECT MAX(FullStart) FROM 
    [FMLX-WEBTRAX].[dbo].[T_IOT_SPINDLE_RUNTIME]
    WHERE MachineName = '$ValMachineName'
    AND DeviceID = '$ValDevice')";
    $query = mssql_query($sql,$link);
	return$query;
}
function CHECK_DATA($ValMachineName,$ValDevice,$link)
{
    $sql = "SELECT TOP 1 * FROM [FMLX-WEBTRAX].[dbo].[T_IOT_SPINDLE_RUNTIME]
    WHERE MachineName = '$ValMachineName'
    AND DeviceID = '$ValDevice'
    AND FullStart IS NULL
    ORDER BY DateCreate DESC";
    $query = mssql_query($sql,$link);
	return$query;
}
function CHECK_DATA2($ValMachineName,$ValDevice,$link)
{
    $sql = "SELECT TOP 1 * FROM [FMLX-WEBTRAX].[dbo].[T_IOT_SPINDLE_RUNTIME]
    WHERE MachineName = '$ValMachineName'
    AND DeviceID = '$ValDevice'
    AND FullStart IS NOT NULL
    AND FullEnd IS NULL
    ORDER BY DateCreate DESC";
    $query = mssql_query($sql,$link);
	return$query;
}
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ValMachineName = test_input($_POST["Machine"]);
        $ValBit = test_input($_POST["Bit"]);
        $ValDevice = test_input($_POST["DevID"]);
        $ValBatt = test_input($_POST["Batt"]);
    $insertData = INSERT_DATA2($ValMachineName,$ValDevice,$ValBit,$ValBatt,$linkMACHWebTrax);
    /*
    if($ValBit == 1)
    {
        $RowsCheck = CHECK_DATA($ValMachineName,$ValDevice,$linkMACHWebTrax);
        $NumRows = mssql_num_rows($RowsCheck);
        if ($NumRows == 0)
        {
            echo "\n INSERT START TIME ".$ValMachineName;
            INSERT_DATA($ValMachineName,$ValDevice,$ValBatt,$linkMACHWebTrax);
        }
    }
    else
    {
        $RowsCheck = CHECK_DATA2($ValMachineName,$ValDevice,$linkMACHWebTrax);
        $NumRows = mssql_num_rows($RowsCheck);
        if ($NumRows == 1)
        {
            echo "\n UPDATE END TIME ".$ValMachineName;
            UPDATE_DATA($ValMachineName,$ValDevice,$ValBatt,$linkMACHWebTrax);
        }
    }
*/
}
else {
    echo "No data posted.";
}

