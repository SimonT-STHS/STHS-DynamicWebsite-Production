<?php
require_once "STHSSetting.php";
$Query = (string) "";
$Hint = (string) "";
$PlayerSearch = (boolean)FALSE;
$LoopCount = (integer)0;
if(isset($_GET['PlayerSearch'])){$PlayerSearch = filter_var($_GET['PlayerSearch'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
if(isset($_POST['PlayerSearch'])){$PlayerSearch = filter_var($_POST['PlayerSearch'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
If (file_exists($DatabaseFile) == true AND $PlayerSearch != ""){
	$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.ProTeamName, PlayerInfo.Retire as Retire, 'False' AS PosG FROM PlayerInfo WHERE Retire = 'False' UNION ALL SELECT 
	GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.ProTeamName, GoalerInfo.Retire as Retire, 'True' AS PosG FROM GoalerInfo WHERE Retire = 'False' ) AS MainTable WHERE Name LIKE '%" . $PlayerSearch . "%' ORDER BY Name";
	$db = new SQLite3($DatabaseFile);
	$DBReturn = $db->query($Query);
	if (empty($DBReturn) == false){while ($Row = $DBReturn ->fetchArray()) { 
		$LoopCount +=1; 
		if ($Row['PosG']== "True"){
			$Hint = $Hint . "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a><br>";
		}else{
			$Hint = $Hint . "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a><br>";
		}
		If ($LoopCount > 20){
			$Hint = $SearchLang['TooManyResult'];
			break;
		}
	}}
	if ($Hint=="") {
		echo $SearchLang['NoSuggestion'];
	}else{
		echo $Hint;
	}
}
