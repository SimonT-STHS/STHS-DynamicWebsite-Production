<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$Search = (boolean)False;
$HistoryOutput = (boolean)False;
$AllowProspectEdition =(boolean)False;
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorProspect;
}else{try{
	$DESCQuery = (boolean)FALSE;/* The SQL Query must be Descending Order and not Ascending*/
	$TeamEdit= (integer)0;
	$MaximumResult = (integer)0;
	$OrderByInput = (string)"";
	$ProspectNumber = (integer)0;
	$ProspectName = (string)"";	
	$ProspectYear = (integer)0;
	$ProspectOverallPick = (integer)0;
	$ProspectInformation = (string)"";
	$ProspectLink = (string)"";

	if(isset($_GET['DESC'])){$DESCQuery= TRUE;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
	
	$LeagueName = (string)"";

	$Playoff = (boolean)False;
	$PlayoffString = (string)"False";
	$Year = (integer)0;	
	if(isset($_GET['Playoff'])){$Playoff=True;$PlayoffString="True";}
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 

	If($Year > 0 AND file_exists($CareerStatDatabaseFile) == true){  /* CareerStat */
		$db = new SQLite3($CareerStatDatabaseFile);
		$CareerDBFormatV2CheckCheck = $db->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
		If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
			$HistoryOutput = True;
			
			$Query = "Select Name from LeagueGeneral WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
			$LeagueGeneral = $db->querySingle($Query,true);		
			
			//Confirm Valid Data Found
			$CareerDBFormatV2CheckCheck = $db->querySingle("Select Count(Name) As CountName from LeagueGeneral  WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'",true);
			If ($CareerDBFormatV2CheckCheck['CountName'] == 1){$LeagueName = $LeagueGeneral['Name'];}else{$Year = (integer)0;$HistoryOutput = (boolean)False;Goto RegularSeason;}
				
			If($MaximumResult == 0){$Title = $DynamicTitleLang['All'];}else{$Title = $DynamicTitleLang['Top'] . $MaximumResult . " ";}
			$Query = "SELECT 0 As TeamThemeID, Prospects.*, TeamProInfoHistory.Name As TeamName FROM Prospects LEFT JOIN TeamProInfoHistory ON Prospects.TeamNumber = TeamProInfoHistory.Number WHERE Prospects.Year = " . $Year . " And Prospects.Playoff = '" . $PlayoffString. "' AND TeamProInfoHistory.Year = " . $Year . " And TeamProInfoHistory.Playoff = '" . $PlayoffString. "'";
			if($Team > 0){
				$Query = $Query . " AND TeamNumber = " . $Team;
				$QueryTeam = "SELECT Name FROM TeamProInfoHistory WHERE Number = " . $Team;
				$TeamName = $db->querySingle($QueryTeam,true);	
				$Title = $Title . " " . $TeamName['Name'];		
			}
			$Query = $Query . " ORDER BY NAME";
			
			$Title = $Title  . $DynamicTitleLang['Prospects'] . " - " . $Year;
			If ($Playoff == True){$Title = $Title . $TopMenuLang['Playoff'];}
			
			/* Order by  */
			If ($DESCQuery == TRUE){
				$Query = $Query . " DESC";
			}else{
				$Query = $Query . " ASC";
			}
			
			If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
			$Prospects = $db->query($Query);
			
			echo "<title>" . $LeagueName . " - " . $Title . "</title>";
						
		}else{
			Goto RegularSeason;
		}
	}else{
		/* Regular Season */			
		RegularSeason:
		$db = new SQLite3($DatabaseFile);
		$Query = "Select Name from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);		
		$LeagueName = $LeagueGeneral['Name'];
		
		If ($CookieTeamNumber > 0 AND $CookieTeamNumber <= 102){
			$Query = "Select AllowProspectEditionFromWebsite from LeagueWebClient";
			$LeagueWebClient = $db->querySingle($Query,true);			
			If($LeagueWebClient['AllowProspectEditionFromWebsite'] == "True"){if(isset($_GET['Edit'])){$AllowProspectEdition = True;}}					
		}		
					
		If($MaximumResult == 0){$Title = $DynamicTitleLang['All'];}else{$Title = $DynamicTitleLang['Top'] . $MaximumResult . " ";}
		$Query = "SELECT Prospects.*, TeamProInfo.Name As TeamName, TeamProInfo.TeamThemeID FROM Prospects LEFT JOIN TeamProInfo ON Prospects.TeamNumber = TeamProInfo.Number";
		if($Team > 0){
			$Query = $Query . " WHERE TeamNumber = " . $Team;
			$QueryTeam = "SELECT Name FROM TeamProInfo WHERE Number = " . $Team;
			$TeamName = $db->querySingle($QueryTeam,true);	
			$Title = $Title . " " . $TeamName['Name'];		
		}
		$Query = $Query . " ORDER BY NAME";
		
		$Title = $Title . $DynamicTitleLang['Prospects'];	
		If($AllowProspectEdition == True){$Title = $Title . " - " . $ProspectsLang['Edit'];}
		
		/* Order by  */
		If ($DESCQuery == TRUE){
			$Query = $Query . " DESC";
		}else{
			$Query = $Query . " ASC";
		}
		
		If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
		$Prospects = $db->query($Query);
			
		echo "<title>" . $LeagueName . " - " . $Title . "</title>";
	}
} catch (Exception $e) {
STHSErrorProspect:
	$Prospects = Null;
	$LeagueName = $DatabaseNotFound;
	$LeagueWebClient = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	echo "<style>.STHSProspects_MainDiv{display:none}</style>";
}}?>
</head><body>
<?php include "Menu.php";?>
<script>
$(function() {
  $(".STHSPHPAllProspects_Table").tablesorter({
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
      columnSelector_breakpoints : [ '10em', '20em', '30em', '40em', '50em', '60em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 1000,	  
      filter_reset: '.tablesorter_Reset',	 
	  output_delivery: 'd',
	  output_saveFileName: 'STHSProspects.CSV'
    }
  });
  $('.download').click(function(){
      var $table = $('.STHSPHPAllProspects_Table'),
      wo = $table[0].config.widgetOptions;
      $table.trigger('outputTable');
      return false;
  });  
});

function UpdateProspect(Id) {
try {
	Year = document.getElementById("Year"+Id).value;
	OverallPick = document.getElementById("OverallPick"+Id).value;
	Information = document.getElementById("Information"+Id).value;
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
	PostData = "EditProspectNumber="+Id+"&Year="+Year+"&OverallPick="+OverallPick+"&Information="+Information+"&Hyperlink="+Hyperlink;
	xmlhttp.send(PostData);
}
catch(err) {
  document.getElementById("STHSDivInformationMessage").innerHTML="<?php echo $ScriptError;?>";
}
}
</script>
<div class="STHSProspects_MainDiv" style="width:99%;margin:auto;">
<div id="STHSDivInformationMessage" class="STHSDivInformationMessage"><br></div>
<?php echo "<h1>" . $Title . "</h1>";?>
<div id="ReQueryDiv" style="display:none;">
<?php
If($HistoryOutput == False){
	include "SearchProspects.php";
}else{
	include "SearchHistorySub.php";
	include "SearchHistoryProspects.php";
	$Team = -1; /* Reset $Team to -1 beofre Prospects Sub To Show Team Column */
}?>
</div>

<div class="tablesorter_ColumnSelectorWrapper">
	<button class="tablesorter_Output" id="ReQuery"><?php echo $SearchLang['ChangeSearch'];?></button>
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
	<button class="tablesorter_Output download" type="button">Output</button>
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPAllProspects_Table"><thead><tr>
<?php include "ProspectsSub.php";
echo "</tbody></table></div>\n";
if (isset($LeagueWebClient)){If ($LeagueWebClient['AllowProspectEditionFromWebsite'] == "True" And $AllowProspectEdition == False){echo "<br><h1 class=\"STHSCenter\"><a href=\"Prospects.php?Edit\"";If ($lang == "fr"){echo "?Lang=fr";} echo ">" . $ProspectsLang['ClicktoEdit'] . "</a></h1>";}}

include "Footer.php";?>
