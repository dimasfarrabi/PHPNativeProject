<?php
function LIST_QUOTE_QUALITY_POINT($Time,$link)
{
    $query = mssql_query("
        SELECT 
            A.Quote,B.Idx 
        FROM T_WO_MAPPING_WITH_EXPENSE AS A 
        INNER JOIN T_PROJECT AS B ON A.Quote = B.ProjectName
        WHERE 
            A.ClosedTime = '$Time' 
            AND B.QuoteCategory = 'Quote'
        GROUP BY 
            A.Quote,B.Idx
        ORDER BY 
        A.Quote;");
    return $query;
}
function LIST_QUALITY_POINT_PROJECT_BY_ID($Time,$ID,$link)
{
    $query = mssql_query("
    SELECT 
        A.ClosedTime,A.Division,B.Idx,B.ProjectName,CAST(C.SortOrder AS INT) AS [SortOrder],D.Division_ID,E.Actual,E.TargetMin,E.TargetMax,E.GoalAchievement
    FROM 
        T_WO_MAPPING_WITH_EXPENSE AS A 
    INNER JOIN T_PROJECT AS B ON A.Quote = B.ProjectName
    INNER JOIN T_QUALITY_POINT_DIVISION AS C ON A.ExpenseAllocation = C.Division
    INNER JOIN T_DIVISION AS D ON C.Division = D.DivisionName
    LEFT JOIN T_QUALITY_POINT_PER_PROJECT AS E ON (E.ClosingHalf = A.ClosedTime AND E.QPDivision_ID = D.Division_ID AND E.Project_ID = B.Idx)
    WHERE 
        B.Idx = '$ID' AND A.ClosedTime = '$Time'
    GROUP BY 
        A.ClosedTime,A.Division,B.Idx,B.ProjectName,CAST(C.SortOrder AS INT),D.Division_ID,E.Actual,E.TargetMin,E.TargetMax,E.GoalAchievement
    ORDER BY 
        RIGHT(A.ClosedTime,1),CAST(C.SortOrder AS INT);");
    return $query;
}
function GET_DATA_DIVISION_IN_QUALITY_POINT($Input,$link)
{
    // $query = mssql_query("SELECT * FROM T_QUALITY_POINT_DIVISION WHERE Is_Active = '1' AND Division = '$Input';",$link);
    $query = mssql_query("SELECT * FROM T_DIVISION WHERE Is_Aktif = '1' AND DivisionName = '$Input';",$link);
    return $query;
}
function CHECK_ROW_QUALITY_POINT($Closing,$ProjectID,$DivisionID,$Location,$link)
{
    $query = mssql_query("SELECT * FROM T_QUALITY_POINT_PER_PROJECT WHERE ClosingHalf = '$Closing' AND Project_ID = '$ProjectID' AND QPDivision_ID = '$DivisionID' AND Location = '$Location';",$link);
    return $query;
}
function INSERT_NEW_QUALITY_POINT($ValHalf,$ValProjectID,$ValDivisionID,$ValActual,$ValTargetMin,$ValTargetMax,$ValGoal,$ValLocation,$link)
{
    $query = mssql_query("INSERT INTO T_QUALITY_POINT_PER_PROJECT(ClosingHalf,Project_ID,QPDivision_ID,Actual,TargetMin,TargetMax,GoalAchievement,Location,Is_Manual) VALUES('$ValHalf','$ValProjectID','$ValDivisionID','$ValActual','$ValTargetMin','$ValTargetMax','$ValGoal','$ValLocation','1');",$link);
    if(!$query)
    {
        return "False";
    }
    else {
        return "True";
    }
}
function UPDATE_DATA_QUALITY_POINT($ID,$ValActual,$ValTargetMin,$ValTargetMax,$ValGoal,$ValLocation,$link)
{
    $query = mssql_query("UPDATE T_QUALITY_POINT_PER_PROJECT SET Actual = '$ValActual',TargetMin = '$ValTargetMin',TargetMax = '$ValTargetMax',GoalAchievement = '$ValGoal',Location = '$ValLocation',Is_Manual = '1' WHERE Idx = '$ID';",$link);
    if(!$query)
    {
        return "False";
    }
    else {
        return "True";
    }
}
function GET_LIST_QUALITY_POINT_DIVISION($link)
{
    $query = mssql_query("SELECT * FROM T_QUALITY_POINT_DIVISION WHERE Is_Active = '1' ORDER BY SortOrder",$link);
    return $query;
}
function GET_LIST_DIVISION_BY_PROJECT($Project,$ClosedTime,$link)
{
    $query = mssql_query("
    SELECT 
        A.ClosedTime,A.Division,B.Idx,B.ProjectName,C.Division_ID
    FROM 
        T_WO_MAPPING_WITH_EXPENSE AS A
    INNER JOIN T_PROJECT AS B ON A.Quote = B.ProjectName
    INNER JOIN T_DIVISION AS C ON A.Division = C.DivisionName
    WHERE 
        B.ProjectName = '$Project' AND A.ClosedTime = '$ClosedTime'
    GROUP BY 
        A.ClosedTime,A.Division,B.Idx,B.ProjectName,C.Division_ID;",$link);
    return $query;
}
function GET_LIST_CONST_QUALITY_VALUE($ClosingHalf,$link)
{
    $query = mssql_query("SELECT * FROM T_CONST_QUALITY_REPORT WHERE ClosingHalf = '$ClosingHalf';",$link);
    return $query;
}
function GET_LIST_PROJECT_REJECT_RATE($ClosingHalf,$link)
{
    $query = mssql_query("SELECT *,FORMAT(LastUpdated,'yyyy-MM-dd hh:mm:ss') AS [LastUpdated2] FROM T_PROJECT_REJECT_RATE WHERE ClosingHalf = '$ClosingHalf';",$link);
    return $query;
}
function GET_LIST_DIVISION_BY_PROJECT_PSM($Project,$ClosedTime)
{
    $PSMConn = ConnectionDB("10.200.0.253\SQLEXPRESS","[FMLX-MACH]","client","Internal13");
    $query = mssql_query("
    SELECT 
        A.ClosedTime,A.Division,B.Idx,B.ProjectName,C.Division_ID
    FROM 
        T_WO_MAPPING_WITH_EXPENSE AS A
    INNER JOIN T_PROJECT AS B ON A.Quote = B.ProjectName
    INNER JOIN T_DIVISION AS C ON A.Division = C.DivisionName
    WHERE 
        B.ProjectName = '$Project' AND A.ClosedTime = '$ClosedTime'
    GROUP BY 
        A.ClosedTime,A.Division,B.Idx,B.ProjectName,C.Division_ID;",$PSMConn);
    return $query;
    mssql_close($PSMConn);
}
function ConnectionDB($servername,$dbname,$dbuser,$dbpassword)
{
    $link=mssql_connect($servername,$dbuser,$dbpassword,TRUE) or die ("SQL ERROR :".mssql_get_last_message());
    if(!$link){die("Could not connect to MsSQL");}
	else
	{
		mssql_select_db("$dbname",$link) or die ("SQL ERROR :".mssql_get_last_message());
		return $link;
	}
}
// function GET_DATA_CONST_QUALITY_REPORT($Location,$ClosingHalf,$ProjectID,$CostAllocation,$link)
// {
//     $query = mssql_query("SELECT TOP 1 * FROM T_CONST_QUALITY_REPORT WHERE Location = '$Location' AND ClosingHalf = '$ClosingHalf' AND Project_ID = '$ProjectID' AND CostAllocation = '$CostAllocation' ORDER BY Idx DESC;",$link);
//     return $query;
// }
function GET_LIST_QUALITY_POINT($ClosingHalf,$link)
{
    $query = mssql_query("SELECT * FROM T_QUALITY_POINT_PER_PROJECT WHERE ClosingHalf = '$ClosingHalf';",$link);
    return $query;
}
function DETAILS_QUALITY_POINTS($Location,$ProjectID,$DivisionID,$ClosingHalf,$link)
{
    $query = mssql_query("
    SELECT A.Location,A.ProjectID,A.Division_ID,A.ClosingHalf,A.RejectRate,A.TotalQtyIn,A.TotalQtyOut,A.LastUpdated,B.Actual,B.TargetMin,B.TargetMax,B.GoalAchievement 
    FROM T_PROJECT_REJECT_RATE AS A 
    INNER JOIN T_QUALITY_POINT_PER_PROJECT AS B ON (A.Location = B.Location AND A.ProjectID = B.Project_ID AND A.Division_ID = B.QPDivision_ID AND A.ClosingHalf = B.ClosingHalf)
    WHERE A.Location = '$Location' AND A.ProjectID = '$ProjectID' AND A.Division_ID = '$DivisionID' AND A.ClosingHalf = '$ClosingHalf';",$link);
    return $query;
}
function GET_LIST_DIVISION($link)
{
    // $query = mssql_query("SELECT * FROM T_DIVISION WHERE Is_Aktif = '1' AND DivisionName != '-' ORDER BY DivisionName;",$link);
    $query = mssql_query("
    SELECT A.* FROM T_DIVISION AS A LEFT JOIN T_QUALITY_POINT_DIVISION AS B ON A.DivisionName = B.Division 
    WHERE B.Is_Active = '1' AND A.DivisionName != '-' AND A.Division_ID NOT IN ('20') ORDER BY A.DivisionName;",$link);
    return $query;
}
function GET_LIST_DIVISION_PSM()
{
    $PSMConn = ConnectionDB("10.200.0.253\SQLEXPRESS","[FMLX-MACH]","client","Internal13");
    $query = mssql_query("
    SELECT * FROM T_DIVISION WHERE Is_Aktif = '1' AND DivisionName != '-' ORDER BY DivisionName;",$PSMConn);
    return $query;
    mssql_close($PSMConn);
}
function GET_DETAIL_MODAL_QP_SELECTED($ClosingHalf,$Division,$Project,$link)
{
    $query = mssql_query("
    SELECT A.* 
    FROM T_QUALITY_POINT_PER_PROJECT AS A 
    LEFT JOIN T_DIVISION AS B ON A.QPDivision_ID = B.Division_ID
    LEFT JOIN T_PROJECT AS C ON A.Project_ID = C.Idx
    WHERE A.ClosingHalf = '$ClosingHalf' AND B.DivisionName = '$Division' AND C.ProjectName = '$Project';",$link);
    return $query;
}
function GET_TOTAL_COUNT_AVG_QA($Half,$ProjectID,$Location,$link)
{
    $query = mssql_query("
    SELECT 
        T1.Location,T1.ClosingHalf,T1.Project_ID,SUM(T1.GoalAchievement) AS [TotalGoal],T1.TotalDivision
        ,(SUM(T1.GoalAchievement) / T1.TotalDivision) AS [Result]
        ,T1.TotalDivision2 
        ,(SUM(T1.GoalAchievement) / T1.TotalDivision2) AS [Result2]
        FROM 
    (
        SELECT 
            --A.*,
            A.Location,A.ClosingHalf,A.Project_ID,A.QPDivision_ID,A.GoalAchievement,
            (SELECT 
                COUNT(A1.Idx) 
            FROM T_DIVISION AS A1 LEFT JOIN T_QUALITY_POINT_DIVISION AS B1 ON A1.DivisionName = B1.Division 
            WHERE 
                B1.Is_Active = '1' 
                AND A1.DivisionName != '-' 
                AND A1.Division_ID NOT IN ('20') 
                AND A1.DivisionName != 'QUALITY ASSURANCE') AS [TotalDivision] ,
            (
                SELECT 
                    --A.Location,A.ClosingHalf,A.Project_ID,A.QPDivision_ID,A.GoalAchievement
                    COUNT(X.Project_ID)
                FROM T_QUALITY_POINT_PER_PROJECT AS X 
                    LEFT JOIN T_DIVISION AS Y ON X.QPDivision_ID = Y.Division_ID
                    LEFT JOIN T_PROJECT AS Z ON X.Project_ID = Z.Idx
                WHERE 
                    X.ClosingHalf = A.ClosingHalf
                    AND Y.DivisionName != 'QUALITY ASSURANCE' 
                    AND Z.Idx = C.Idx
                    AND X.Location = A.Location
            ) AS [TotalDivision2] 
        FROM T_QUALITY_POINT_PER_PROJECT AS A 
            LEFT JOIN T_DIVISION AS B ON A.QPDivision_ID = B.Division_ID
            LEFT JOIN T_PROJECT AS C ON A.Project_ID = C.Idx
        WHERE 
            A.ClosingHalf = '$Half' 
            AND B.DivisionName != 'QUALITY ASSURANCE' 
            AND C.Idx = '$ProjectID'
            AND A.Location = '$Location'
    ) AS T1
    GROUP BY T1.Location,T1.ClosingHalf,T1.Project_ID,T1.TotalDivision,T1.TotalDivision2;",$link);
    return $query;
}
function CHECK_ROW_QUALITY_POINT_QA($Closing,$ProjectID,$Location,$link)
{
    $query = mssql_query("
    SELECT 
        A.* 
    FROM 
        T_QUALITY_POINT_PER_PROJECT AS A
        INNER JOIN T_DIVISION AS B ON A.QPDivision_ID = B.Division_ID
    WHERE 
        A.ClosingHalf = '$Closing' 
        AND A.Project_ID = '$ProjectID' 
        AND B.DivisionName = 'QUALITY ASSURANCE' 
        AND A.Location = '$Location';",$link);
    return $query;
}
function ADD_DATA_QA_TO_QUALITY_POINT($Location,$ClosingHalf,$ProjectID,$DivisionID,$Goal,$link)
{
    $query = mssql_query("
    INSERT INTO [FMLX-MACH].dbo.T_QUALITY_POINT_PER_PROJECT(Location,ClosingHalf,Project_ID,QPDivision_ID,Actual,TargetMin,TargetMax,GoalAchievement,Is_Manual) 
    VALUES('$Location','$ClosingHalf','$ProjectID','$DivisionID','0','0','0','$Goal','1');",$link);
}
function UPDATE_DATA_QA_TO_QUALITY_POINT($Location,$ClosingHalf,$ProjectID,$DivisionID,$Goal,$link)
{
    $query = mssql_query("
    UPDATE [FMLX-MACH].dbo.T_QUALITY_POINT_PER_PROJECT 
    SET GoalAchievement = $Goal
    WHERE Location = '$Location' 
    AND ClosingHalf = '$ClosingHalf' 
    AND Project_ID  = '$ProjectID' 
    AND QPDivision_ID = '$DivisionID';",$link);
}
function CHECK_DATA_QA($Location,$ClosingHalf,$ProjectID,$DivisionID,$link)
{
    $query = mssql_query("SELECT COUNT(Idx) AS [TOTAL] FROM [FMLX-MACH].dbo.T_QUALITY_POINT_PER_PROJECT WHERE Location = '$Location' AND ClosingHalf = '$ClosingHalf' AND Project_ID  = '$ProjectID' AND QPDivision_ID = '$DivisionID';",$link);
    $result = mssql_fetch_assoc($query);
    return $result['TOTAL'];
}
function DELETE_DATA_SELECTED($ID,$link)
{
    $query = mssql_query("DELETE FROM [FMLX-MACH].dbo.T_QUALITY_POINT_PER_PROJECT WHERE Idx = '$ID';",$link);
    if(!$query)
    {
        return "False";
    }
    else {
        return "True";
    }
}
function DELETE_DATA_QA($Location,$ClosingHalf,$ProjectID,$DivisionID,$link)
{
    $query = mssql_query("DELETE FROM [FMLX-MACH].dbo.T_QUALITY_POINT_PER_PROJECT
         WHERE Location = '$Location' AND ClosingHalf = '$ClosingHalf' AND Project_ID = '$ProjectID' AND QPDivision_ID = '$DivisionID';",$link);
}
?>