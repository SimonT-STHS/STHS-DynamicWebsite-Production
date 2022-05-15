<?php 	
$HistoryYear = Null;
$HistoryTeam  = Null;
$HistoryFarm = (boolean)False;
if (isset($CareerStatDatabaseFile) == False){$CareerStatDatabaseFile="";}
If (file_exists($CareerStatDatabaseFile) == true){
	$Historydb = new SQLite3($CareerStatDatabaseFile);
	$CareerDBFormatV2CheckCheck = $Historydb->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
	If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
		$Query = "SELECT Year FROM LeagueGeneral GROUP BY Year";
		$HistoryYear = $Historydb->query($Query);	
		$Query = "SELECT Number, Name from TeamProInfoHistory GROUP BY Name";
		$HistoryTeam = $Historydb->query($Query);
		$CareerDBFormatV2CheckCheck = $Historydb->querySingle("Select Count(FarmEnable) AS CountFarmEnable FROM LeagueSimulation Where FarmEnable = 'True'",true);
		If ($CareerDBFormatV2CheckCheck['CountFarmEnable'] > 0){$HistoryFarm = True;}
	}
}?>