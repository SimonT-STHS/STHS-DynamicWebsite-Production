<?php include "Header.php";
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$InformationMessage = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorPlayerInfo;
}else{try{
	$Type = (integer)0; /* 0 = All / 1 = Pro / 2 = Farm */
	$TypeQuery = "Number > 0";
	$TeamQuery = "Team >= 0";
	$LeagueName = (string)"";
	$PlayerNumber = (integer)0;
	$PlayerName = (string)"";	
	$PlayerDraftYear = (integer)0;
	$PlayerDraftOverallPick = (integer)0;
	$PlayerNHLID = (integer)0;
	$PlayerJersey = (integer)0;
	$PlayerLink = (string)"";
	
	if(isset($_GET['Type'])){$Type = filter_var($_GET['Type'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);}

	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "Select AllowPlayerEditionFromWebsite from LeagueWebClient";
	$LeagueWebClient = $db->querySingle($Query,true);
	
	If ($LeagueWebClient['AllowPlayerEditionFromWebsite'] == "True"){
					
		/* Team or All */
		If ($Team >= 0){
			if($Team > 0){
				$QueryTeam = "SELECT Name FROM TeamProInfo WHERE Number = " . $Team;
				$TeamName = $db->querySingle($QueryTeam,true);	
				$Title = $Title . $TeamName['Name'];
			}else{
				$Title = $DynamicTitleLang['Unassigned'];
			}
			$TeamQuery = "Team = " . $Team;
		}else{
			$TeamQuery = "Team >= 0"; /* Default Place Order Where everything will return */
		}
		
		/* Pro Only or Farm  */
		if($Type == 1){
			$TypeQuery = "Status1 >= 2";
			$Title = $Title . $DynamicTitleLang['Pro'];
		}elseif($Type == 2){
			$TypeQuery = "Status1 <= 1";
			$Title = $Title . $DynamicTitleLang['Farm'];
		}else{
			$TypeQuery = "Number > 0"; /* Default Place Order Where everything will return */
		} 
			
		/* Main Query with correct Variable */
		$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.ProTeamName, PlayerInfo.TeamThemeID, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.URLLink, PlayerInfo.NHLID, PlayerInfo.DraftYear, PlayerInfo.DraftOverallPick, PlayerInfo.Jersey, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG, PlayerInfo.Retire as Retire FROM PlayerInfo WHERE " . $TeamQuery . " AND Retire = \"False\" AND " . $TypeQuery . " UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.ProTeamName, GoalerInfo.TeamThemeID, GoalerInfo.Age, GoalerInfo.AgeDate, GoalerInfo.URLLink, GoalerInfo.NHLID, GoalerInfo.DraftYear, GoalerInfo.DraftOverallPick, GoalerInfo.Jersey, 'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG, GoalerInfo.Retire as Retire FROM GoalerInfo WHERE " . $TeamQuery . " AND Retire = \"False\" AND " . $TypeQuery . ") AS MainTable ORDER BY MainTable.Name ASC";
		
		/* Ran Query */	
		$PlayerInfo = $db->query($Query);
		
		
	}else{
		echo "<style>#EditPlayerInfoMainDiv, .STHSPHPAllPlayerInformation_Table {display:none}</style>\n";
		$InformationMessage = $ThisPageNotAvailable;
	}
	$Title = $Title . $DynamicTitleLang['PlayersInformation'] . " - " . $PlayersLang['Edit'];
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";		
} catch (Exception $e) {
STHSErrorPlayerInfo:
	$LeagueName = $DatabaseNotFound;
	$PlayerInfo = Null;
	$FreeAgentYear = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	echo "<style>.STHSEditPlayerInfo_MainDiv{display:none}</style>";
}}?>
</head><body>
<?php include "Menu.php";?>
<script>
$(function() {
  $(".STHSPHPAllPlayerInformation_Table").tablesorter({
    showProcessing: true,
    widgets: ['columnSelector', 'stickyHeaders', 'filter', 'output'],
    widgetOptions : {
	  stickyHeaders_zIndex : 110,		
      columnSelector_container : $('#tablesorter_ColumnSelector'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 1000,	  
      filter_reset: '.tablesorter_Reset',	 
    }
  });  
});
function UpdatePlayer(Id) {
try {
	DraftYear = document.getElementById("DraftYear"+Id).value;
	DraftOverallPick = document.getElementById("DraftOverallPick"+Id).value;
	NHLID = document.getElementById("NHLID"+Id).value;
	Jersey = document.getElementById("Jersey"+Id).value;
	Hyperlink = document.getElementById("Hyperlink"+Id).value;
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
		  document.getElementById("STHSDivInformationMessage").innerHTML=this.responseText;
		  document.getElementById("STHSDivInformationMessage").scrollIntoView(true);
		}
	}
	xmlhttp.open("POST","APIBackEnd.php<?php If ($lang == "fr"){echo "?Lang=fr";}?> ",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	PostData = "EditPlayerNumber="+Id+"&DraftYear="+DraftYear+"&DraftOverallPick="+DraftOverallPick+"&NHLID="+NHLID+"&Jersey="+Jersey+"&Hyperlink="+Hyperlink;
	xmlhttp.send(PostData);
}
catch(err) {
  document.getElementById("STHSDivInformationMessage").innerHTML="<?php echo $ScriptError;?>";
}
}

</script>
<div id="STHSDivInformationMessage" class="STHSDivInformationMessage"><br></div>
<div class="STHSEditPlayerInfo_MainDiv" id="EditPlayerInfoMainDiv" style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>"; ?>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>


<table class="tablesorter STHSPHPAllPlayerInformation_Table"><thead><tr>
<th data-priority="critical" title="Player Name" class="STHSW140Min"><?php echo $PlayersLang['PlayerName'];?></th>
<?php if($Team >= 0){echo "<th class=\"columnSelector-false STHSW140Min\" data-priority=\"6\" title=\"Team Name\">" . $PlayersLang['TeamName'] . "</th>";}else{echo "<th data-priority=\"2\" title=\"Team Name\" class=\"STHSW140Min\">" . $PlayersLang['TeamName'] ."</th>";}?>
<th data-priority="2" title="Position" class="STHSW45">POS</th>
<th data-priority="5" title="Age" class=" STHSW25"><?php echo $PlayersLang['Age'];?></th>
<th data-priority="5" title="Birthday" class="STHSW45"><?php echo $PlayersLang['Birthday'];?></th>
<th data-priority="4" title="Draft Year" class="STHSW55"><?php echo $PlayersLang['DraftYear'];?></th>
<th data-priority="4" title="Overall Pick" class="STHSW55"><?php echo $PlayersLang['DraftOverallPick'];?></th>
<th data-priority="4" title="Jersey #" class="STHSW55"><?php echo $PlayersLang['Jersey'];?></th>
<th data-priority="3" title="NHLID" class="STHSW55"><?php echo $PlayersLang['NHLID'];?></th>
<th data-priority="3" title="Hyperlink" class="STHSW140Min"><?php echo $PlayersLang['Link'];?></th>
<th data-priority="2" title="Edit" class="STHSW55"><?php echo $PlayersLang['Edit'];?></th>
</tr></thead><tbody>

<?php 
if (empty($PlayerInfo) == false){while ($Row = $PlayerInfo ->fetchArray()) { 
	echo "<tr><td>";
	if ($Row['PosG']== "True"){echo "<a href=\"GoalieReport.php?Goalie=";}else{echo "<a href=\"PlayerReport.php?Player=";}
	echo $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	If ($Row['TeamThemeID'] > 0){echo "<td><img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPGoaliesRosterTeamImage\">" . $Row['TeamName'] . "</td>";}else{echo "<td>" . $Row['TeamName'] . "</td>";}	
	echo "<td>" .$Position = (string)"";
	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}
	if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}
	if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}
	if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
	if ($Row['PosG']== "True"){if ($Position == ""){$Position = "G";}}
	echo $Position . "</td>";	
	echo "<td>" . $Row['Age'] . "</td>";
	echo "<td>" . $Row['AgeDate'] . "</td>";
	if ($Row['PosG']== "True"){$Row['Number'] = $Row['Number'] + 10000;}
	echo "<td class=\"STHSCenter\"><input type=\"number\" min=\"0\" max=\"99\" id=\"DraftYear" . $Row['Number'] . "\" name=\"DraftYear\" value=\"";If(isset($Row['DraftYear'])){Echo $Row['DraftYear'];}echo "\"></td>";
	echo "<td class=\"STHSCenter\"><input type=\"number\" min=\"0\" max=\"1000\" id=\"DraftOverallPick" . $Row['Number'] . "\" name=\"DraftOverallPick\" value=\"";If(isset($Row['DraftOverallPick'])){Echo $Row['DraftOverallPick'];}echo "\"></td>";
	echo "<td class=\"STHSCenter\"><input type=\"number\" min=\"0\" max=\"99\" id=\"Jersey" . $Row['Number'] . "\" name=\"Jersey\" value=\"";If(isset($Row['Jersey'])){Echo $Row['Jersey'];}echo "\"></td>";
	echo "<td class=\"STHSCenter\"><input type=\"number\" min=\"0\" max=\"999999999\" id=\"NHLID" . $Row['Number'] . "\" name=\"NHLID\" value=\"";If(isset($Row['NHLID'])){Echo $Row['NHLID'];}echo "\"></td>";
	echo "<td class=\"STHSCenter\"><input type=\"url\" id=\"Hyperlink" . $Row['Number'] . "\"  name=\"Hyperlink\" value=\"";If(isset($Row['URLLink'])){Echo $Row['URLLink'];}echo "\" size=\"60\"></td>";
	echo "<td class=\"STHSCenter\"><input type=\"submit\" class=\"SubmitButtonSmall\" value=\"" . $PlayersLang['Edit'] . "\" onclick=\"UpdatePlayer('" . $Row['Number'] . "');\"></td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table></div>
<br>

<?php include "Footer.php";?>