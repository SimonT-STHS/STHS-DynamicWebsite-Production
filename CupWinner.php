<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorCupWinner;
}else{try{
	$LeagueName = (string)"";
		
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, NumbersOfTeam from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);

		$CareerDBFormatV2CheckCheck = $CareerStatdb->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
		If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
			$Query = "Select MainTable.*,  TeamProInfoHistory.Name as ProTeam ,  TeamFarmInfoHistory.Name as FarmTeam FROM (SELECT Year,Playoff, PlayOffWinnerPro, PlayOffWinnerFarm  From LeagueGeneral Where Playoff = 'True' ORDER BY Year DESC) as MainTable LEFT JOIN TeamFarmInfoHistory ON (MainTable.PlayOffWinnerFarm = TeamFarmInfoHistory.Number) AND (MainTable.Year = TeamFarmInfoHistory.Year) AND (MainTable.Playoff = TeamFarmInfoHistory.Playoff) LEFT JOIN TeamProInfoHistory ON (MainTable.PlayOffWinnerPro = TeamProInfoHistory.Number) AND (MainTable.Year = TeamProInfoHistory.Year) AND (MainTable.Playoff = TeamProInfoHistory.Playoff)";
			$CupWinner = $CareerStatdb->query($Query);				
		}else{
			$CupWinner = Null;
		}
	}

	echo "<title>" . $LeagueName . " - " . $CupWinnerLang['StanleyCupWinner'] . "</title>";
} catch (Exception $e) {
STHSErrorCupWinner:	
	$LeagueName = $DatabaseNotFound;
	echo "<title>" . $DatabaseNotFound ."</title>";
}}?>
</head><body>
<?php include "Menu.php";?>

<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $CupWinnerLang['StanleyCupWinner'] . "</h1>"; ?>
<table class="STHSCupWinner_MainTable">
<thead><tr>
<th class="STHSCupWinner_Year"><?php echo $CupWinnerLang['Year'];?></th>
<th class="STHSCupWinner_Team"><?php echo $CupWinnerLang['ProTeam'];?></th>
<th class="STHSCupWinner_Team"><?php echo $CupWinnerLang['FarmTeam'];?></th>
</tr></thead><tbody>
<?php
if (empty($CupWinner) == false){while ($row = $CupWinner ->fetchArray()) {
	echo "<tr><td>" . $row['Year'] . "</td><td>";
	$Query = "Select TeamThemeID From TeamProInfo WHERE UniqueID = " . $row['PlayOffWinnerPro'];
	$TeamImage = $db->querySingle($Query,true);		
	If (isset($TeamImage['TeamThemeID']) == True){If ($TeamImage['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $TeamImage['TeamThemeID'] .".png\" alt=\"\" class=\"STHSCupWinner_Image\" /><br />";}}
	echo $row['ProTeam'] . "</td><td>";
	
	$Query = "Select TeamThemeID From TeamFarmInfo WHERE UniqueID = " . $row['PlayOffWinnerFarm'];
	$TeamImage = $db->querySingle($Query,true);		
	If (isset($TeamImage['TeamThemeID']) == True){If ($TeamImage['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $TeamImage['TeamThemeID'] .".png\" alt=\"\" class=\"STHSCupWinner_Image\" /><br />";}}
	echo $row['FarmTeam'] . "</td></tr>";
}}
?>
</tbody></table>

<br />
</div>

<?php include "Footer.php";?>
