<?php if(isset($CareerStatdb)){
	$CareerDBFormatV2CheckCheck = $CareerStatdb->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
	If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
		$Query = "SELECT MainTable.Year FROM (SELECT PlayerProStatCareer.Year FROM PlayerProStatCareer GROUP BY PlayerProStatCareer.Year UNION ALL SELECT PlayerFarmStatCareer.Year FROM PlayerFarmStatCareer GROUP BY PlayerFarmStatCareer.Year) AS MainTable GROUP BY MainTable.Year";
		$PlayerYear = $CareerStatdb->query($Query);	
		$Query = "SELECT MainTable.Year FROM (SELECT GoalerProStatCareer.Year FROM GoalerProStatCareer GROUP BY GoalerProStatCareer.Year UNION ALL SELECT GoalerFarmStatCareer.Year FROM GoalerFarmStatCareer GROUP BY GoalerFarmStatCareer.Year) AS MainTable GROUP BY MainTable.Year";
		$GoalieYear = $CareerStatdb->query($Query);		
		$Query = "SELECT MainTable.Year FROM (SELECT TeamProStatCareer.Year FROM TeamProStatCareer GROUP BY TeamProStatCareer.Year UNION ALL SELECT TeamFarmStatCareer.Year FROM TeamFarmStatCareer GROUP BY TeamFarmStatCareer.Year) AS MainTable GROUP BY MainTable.Year";
		$TeamYear = $CareerStatdb->query($Query);
		$Query = "SELECT MainTable.TeamName FROM (SELECT PlayerProStatCareer.TeamName FROM PlayerProStatCareer GROUP BY PlayerProStatCareer.TeamName UNION ALL SELECT PlayerFarmStatCareer.TeamName FROM PlayerFarmStatCareer GROUP BY PlayerFarmStatCareer.TeamName) AS MainTable GROUP BY MainTable.TeamName";
		$PlayerTeamName = $CareerStatdb->query($Query);
		$Query = "SELECT MainTable.TeamName FROM (SELECT GoalerProStatCareer.TeamName FROM GoalerProStatCareer GROUP BY GoalerProStatCareer.TeamName UNION ALL SELECT GoalerFarmStatCareer.TeamName FROM GoalerFarmStatCareer GROUP BY GoalerFarmStatCareer.TeamName) AS MainTable GROUP BY MainTable.TeamName";
		$GoalieTeamName = $CareerStatdb->query($Query);	

		$Query = "Select sql FROM sqlite_master WHERE Name = 'PlayerProStatCareer'";
		$ResultUpdateDB = $CareerStatdb->querySingle($Query,true);	
		If (strpos($ResultUpdateDB['sql'],'Rookie') == true) {$UpdateCareerStatDBV1 = true;}
	}
}?>