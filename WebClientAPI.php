<?php
function load_apis($exempt=array()){
	if(!in_array("api", $exempt)){load_api();}
	if(!in_array("dbresults", $exempt)){load_api_dbresults();}
	if(!in_array("fields", $exempt)){load_api_fields();}
	if(!in_array("html", $exempt)){load_api_html();}
	if(!in_array("jquery", $exempt)){load_api_jquery();}
	if(!in_array("js", $exempt)){load_api_js();}
	if(!in_array("layout", $exempt)){load_api_layout();}
	if(!in_array("pageinfo", $exempt)){load_api_pageinfo();}
	if(!in_array("sql", $exempt)){load_api_sql();}
}

function load_api(){
	function api_sqlite_connect($filename="STHS.db"){
		$db = new SQLite3($filename);
		return $db;
	}
	// Helper function to take strings and turn them
	// into CSS Classes or IDs removing spaces and symbols.
	function api_MakeCSSClass($var){
		$ret = preg_replace("/[^a-zA-Z0-9\s]/", "", $var);
		$ret = str_replace(" ", "" , $ret);
		$ret = strtolower($ret);
		return $ret;
	}
	function api_pre_r($arr){
		echo "<pre>"; print_r($arr); echo "</pre>";
	}
	function api_alpha_testing(){
		?><div class="instructions">Web Client currently in ALPHA testing mode. Use at own risk. Please report bugs and errors to :<br /><a href=http://sths.simont.info/Forum/viewtopic.php?f=4&t=12732>http://sths.simont.info/Forum/viewtopic.php?f=4&t=12732</a></div><?php
	}
}

function load_api_dbresults(){
	function api_dbresult_roster_editor_fields($db,$teamid){
		$rs = $db->query(api_sql_roster_editor_fields($teamid));
		return $rs->fetchArray();
	}
	function api_dbresult_line_editor_fields($db){
		$rs = $db->query(api_sql_line_editor_fields());
		$row = $rs->fetchArray();
		foreach($row AS $id=>$r){
			if(is_numeric($id)){unset($row[$id]);}
		}
		return $row;
	}
	function api_dbresult_teamname($db,$teamid,$league){
		$sql = "SELECT t.Name AS FullTeamName FROM Team". $league ."Info AS t WHERE Number = " . $teamid;
		return $db->querySingle($sql,true);
	}
}

function load_api_fields(){
	// Return all the fields needed for the roster editor.
	function api_fields_roster_editor_setup(){
		return  array(	"MaximumPlayerPerTeam","MinimumPlayerPerTeam","isWaivers","BlockSenttoFarmAfterTradeDeadline","isAfterTradeDeadline","ProTeamEliminatedCannotSendPlayerstoFarm","isEliminated","ForceCorrect10LinesupbeforeSaving",
						"ProMinC","ProMinLW","ProMinRW","ProMinD","ProMinForward","ProGoalerInGame","ProPlayerInGame","ProPlayerLimit", 
						"FarmMinC","FarmMinLW","FarmMinRW","FarmMinD","FarmMinForward","FarmGoalerInGame","FarmPlayerInGame","FarmPlayerLimit","MaxFarmOv","MaxFarmOvGoaler","GamesLeft","FullFarmEnable");
	}
	// Return all the fields needed for the line editor.
	function api_fields_line_editor_setup(){
		return  array(	"BlockPlayerFromPlayingLines12","BlockPlayerFromPlayingLines123","BlockPlayerFromPlayingLines12inPPPK",
						"ProForceGameStrategiesTo","ProForceGameStrategiesAt5","FarmForceGameStrategiesTo","FarmForceGameStrategiesAt5",
						"PullGoalerMinGoal","PullGoalerMinGoalEnforce","PullGoalerMinPct","PullGoalerRemoveGoaliesSecond","PullGoalerMax");
	}
	function api_fields_input_values($row){
		$value = $row["Name"] ."|";
		$value .= $row["Number"] ."|";
		$value .= $row["PositionNumber"]."|";
		$value .= $row["PositionString"] ."|";
		$value .= $row["Status1"] . "|";
		$value .= $row["Overall"] . "|";
		$value .= strtolower($row["ForceWaiver"]) . "|";
		$value .= api_MakeCSSClass($row["Name"]);

		return $value;
	}
}

function load_api_html(){
	// Create a dropdown with all teams
	function api_html_form_teamid($db,$teamid,$farm=false){
		$proLeague = (isset($_REQUEST["League"]) && $_REQUEST["League"] == "Farm") ? false : true;
		?>
		<form name="frmTeams">
			<select id=sltTeams onchange="javascript:var s = document.getElementById('sltTeams').value.split('|');window.location.replace('?TeamID='+s[0]+'&League='+s[1]);">
				<option>---Select a Team---</option>
				<?php
					$RS = $db->query("SELECT Name, Number FROM TeamProInfo ORDER BY Name;");
					while($row = $RS->fetchArray()){
						$s = ($row["Number"] == $teamid && $proLeague) ? " selected " : "";
						?><option<?= $s ?> value=<?=$row["Number"]?>|Pro><?=$row["Name"]?></option><?php
					}
					// Display the farm team listing if flagged.
					if($farm){
						?><option>----------------<?php
						$RS = $db->query("SELECT Name, Number FROM TeamFarmInfo ORDER BY Name;");
						while($row = $RS->fetchArray()){
							$s = ($row["Number"] == $teamid && !$proLeague) ? " selected " : "";
							?><option<?= $s ?> value=<?=$row["Number"]?>|Farm><?=$row["Name"]?></option><?php
						}
					}
				?>
			</select>
		</form>
		<?php
	}
	function api_html_checkboxes_positionlist($elementName,$byName="true",$display="inline"){
		?>
		<div class="positionlist">
			<label><input onchange="update_position_list('<?= $elementName; ?>',<?= $byName; ?>,'<?= $display; ?>');" type="checkbox" id="posC" name="position" class="position" checked>C</label>
			<label><input onchange="update_position_list('<?= $elementName; ?>',<?= $byName; ?>,'<?= $display; ?>');" type="checkbox" id="posLW" name="position" class="position" checked>LW</label>
			<label><input onchange="update_position_list('<?= $elementName; ?>',<?= $byName; ?>,'<?= $display; ?>');" type="checkbox" id="posRW" name="position" class="position" checked>RW</label>
			<label><input onchange="update_position_list('<?= $elementName; ?>',<?= $byName; ?>,'<?= $display; ?>');" type="checkbox" id="posD" name="position" class="position" checked>D</label>
			<label><input onchange="update_position_list('<?= $elementName; ?>',<?= $byName; ?>,'<?= $display; ?>');" type="checkbox" id="posG" name="position" class="position" checked>G</label>
		</div>
		<?php
	}
}

function load_api_jquery(){
	function api_jquery_call_jquery(){
		?>
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script> <!-- Load in JQuery -->
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script><!-- Load in JQuery UI -->
		<script src="js/jquery.ui.touch-punch.min.js"></script><!-- Load in JQuery Needed for mobile devices -->
		<script src="js/jquery.labs.js"></script><!-- Load in JQuery from Labs -->
		<?php
	}
	function api_jquery_roster_editor_draggable($jsfunction){?>
		<script>
		$(function() {
		    $("#sortProDress, #sortProScratch, #sortFarmDress, #sortFarmScratch").sortable({
	        	items: ".playerrow",
	         	items: "li:not(.injury)",
	        	forcePlaceholderSize: true,
	        	connectWith: ".connectedSortable",
	        	update: function(event, ui) {<?= $jsfunction ?>}
		    }).disableSelection();
		    
		    $(".playerrow").disableSelection();
		    $('#sortable').draggable();
		});
		</script><?php
	}
}

function load_api_js(){
	function api_js_function_roster_validator($db,$teamid){
		$jsRow = api_dbresult_roster_editor_fields($db,$teamid);
		$f = "";
		foreach(array_keys($jsRow) AS $k){
			if(!is_numeric($k))$f .= (!is_numeric($jsRow[$k])) ? strtolower($jsRow[$k]) . "," : $jsRow[$k] .",";
		}
		return "roster_validator(". rtrim($f,",") .");";
	}
	function api_js_function_line_validator($db){
		$jsRow = api_dbresult_line_editor_fields($db);
		$f = "";
		foreach(array_keys($jsRow) AS $k){
			if(!is_numeric($k))$f .= (!is_numeric($jsRow[$k])) ? strtolower($jsRow[$k]) . "," : $jsRow[$k] .",";
		}
		$ret = "line_validator(". rtrim($f,",") .");";
		return $ret;
	}
}

function load_api_layout(){
	function api_layout_header($id=false,$db=false,$teamid,$league=false,$headcode=""){?>
		<!DOCTYPE html>
			<html>
			<head>
				<meta name="author" content="Shawn Arsenault" />
				<meta name="description" content="Tools for the STHS Simulator" />
				<meta name="keywords" content="STHS, Fantasy, Hockey, Simulator" />
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta name="viewport" content="width=device-width" />
				<meta http-equiv="cache-control" content="max-age=0" />
				<meta http-equiv="cache-control" content="no-cache" />
				<meta http-equiv="expires" content="0" />
				<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
				<title>STHS WebEditor - 
				<?php
					if($id == "rostereditor" && $teamid > 0){  
						$row = ($teamid > 0) ? api_dbresult_teamname($db,$teamid,"Pro") : array();
						$teamname = (!empty($row)) ? $row["FullTeamName"] . " - " : "";
						echo $teamname . "Roster Editor";
					}elseif($id == "lineeditor" && $teamid > 0 && $league){
						$row = ($teamid > 0) ? api_dbresult_teamname($db,$teamid,$league) : array();
						$teamname = (!empty($row)) ? $row["FullTeamName"] . " - " : "";
						echo $teamname . "Line Editor";
					}
				?>
				</title>
				<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
				<link rel="stylesheet" href="css/required.css">
				<link rel="stylesheet" href="css/labs.css">
					<?php 
					// Using the $id paramater, check if there is a css file with that name to use for this page only. 
					// If the $id.css exists, load it in.
					foreach(array("css","js") AS $filetype){
						$file = $filetype . "/". $id ."." . $filetype;
						if(file_exists($file)):
							if($filetype == "css"){
							?>
								<link rel="stylesheet" href="<?= $file ?>"><?php
							}else{
								?><script src="<?= $file ?>"></script><?php
							}
						endif;
					}?>

				<script src="js/scripts_labs.js"></script><!-- Load in the scripts needed from labs -->
				
				<?php
					// Check for $id for rostereditor page. 
					// If we are on the roster editor page, the body tage needs an onload function to validate the rosters at default.
					// If so and a team is selected, create the onload attribute with the js_function_roster_validator to placein the body tag. 
					if($id == "rostereditor" && $teamid > 0){  
						api_jquery_call_jquery();
						$jsfunction = api_js_function_roster_validator($db,$teamid);
						$onload = " onLoad=\"". $jsfunction ."\"";
						// Add the jquery for draggable columns.
						api_jquery_roster_editor_draggable($jsfunction);
					}
				?>
				<?php
					if($id == "lineeditor" && $teamid > 0 && $league){
						api_jquery_call_jquery();
						$jsfunction = api_js_function_line_validator($db);
						$onload = " onLoad=\"". $jsfunction ."\"";
						
						echo api_script_team_array($db,$teamid); 
					}
				if($headcode != ""){echo $headcode;}
				?>

			</head>
		<?php
		// Start the Body, add an onload function if set above.
		?><body<?= (isset($onload)) ? $onload : "";?>><?php
	}

	function api_layout_footer(){
		?></body></html><?php
	}

	function api_script_team_array($db,$teamid){
		$pos = array(0=>"C",1=>"LW",2=>"RW",3=>"D",4=>"G",5=>"F");
		$position = array();
		foreach(array(3=>"Pro",1=>"Farm") AS $status=>$league){
			$isPro = ($status == 3) ? true: false;
			$SQL = api_sql_players_base("Player",$isPro);
			$SQL .= "WHERE Team = " . $teamid  . " AND Status1 = ". $status ." ";
			$SQL .= "UNION ";
			$SQL .= api_sql_players_base("Goaler",$isPro);
			$SQL .= "WHERE Team = " . $teamid  . " AND Status1 = ". $status ." ";
			$SQL .= "ORDER BY Overall DESC, PositionNumber DESC ";
			$oRS = $db->query($SQL);	
			while($row = $oRS->fetchArray()){
				foreach($pos AS $id=>$p){
					if($id != 4){
						// Check to see if the Position exists in the $row variable. This counter checks for PosF for all the forwards.
						if(array_key_exists("Pos" . $p, $row) && $row["Pos" . $p] == "True" && $id < 4){$position[$league][$id][] = "\"" . $row["Name"] . "\"";}
						if($row["PosC"] == "True" && $id ==5 || $row["PosLW"] == "True" && $id ==5 || $row["PosRW"] == "True" && $id ==5){$position[$league][$id][] = "\"" . $row["Name"] . "\"";}
					}else{
						if($row["Position"] == "FalseFalseFalseFalse"){$position[$league][4][] = "\"" . $row["Name"] ."\"";}		
					}

				}
			}
		}
		

		$j = "<script>";
		$j .= "function make_position_list(){\n";
		$j .= "var pos = [];\n";
		$j .= "pos[0] = [];\n";
		$j .= "pos[1] = [];\n";

		foreach(array(0=>"Pro",1=>"Farm") AS $status=>$league){
			foreach($pos AS $id=>$p){
				$string = (!empty($position[$league][$id])) ? implode(",",$position[$league][$id]) : "";
				$j .= "pos[". $status ."][". $id ."] = [" . $string ."];\n";
			}
		}
		$j .= "return pos;\n";
		$j .= "}\n\n";
		$j .= "</script>";
		return $j;	
	}
}

function load_api_pageinfo(){
	function api_pageinfo_editor_roster($db,$teamid,$showDropdown=true,$showHeader=true){
		$id = "rostereditor";?>
		<div id="<?= $id ?>">
			<div class="pagewrapper pagewrapper<?= $id ?>"><?php 
				api_alpha_testing();
				// $db = sqlite DB
				// $teamid is a teamid to use that teams roster.
				// $showDropdown is a flag if you want to toggle between teams.
				// $showHeader is a flag is you want to show the H1 Tag.
				if($showHeader){
					$row = ($teamid > 0) ? api_dbresult_teamname($db,$teamid,"Pro") : array();
					$teamname = (!empty($row)) ? $row["FullTeamName"] . " - " : "";
					?>
					<h1><?= $teamname ?>Roster Editor</h1><?php
				}
				
				$confirmbanner = "";
				$sql = "";
				$execute = false;
				// If the Save Lines button has been clicked.
				if(isset($_POST["sbtRoster"])){
					// Create an array to organize the information
					// $arrSort[$table][$playerid][$status]
					// 		$table = Player or Goalie
					// 		$playerid = Player Number from PlayerInfo Table
					// 		$status = selected status for that game. 
					$arrSort = array();
					// Loop through the txtRoster array. txtRoster[$nextgame][] = Divider = LINE|LineType, Player = FirstName LastName| Number | PositionNumber | PositionString
					// Explode at the pipe | 
					// If the count of the explode is 2 then its a different line
					// Section.  Switch the value of what the status should be
					// $_POST["txtRoster"][$game][$status]
					// $game = int 1-10
					// $status = int 0-3
					foreach($_POST["txtRoster"] AS $statuses=>$status){
						foreach($status AS $s){
							$explodeValue = explode("|",$s);
							if(count($explodeValue) == 2){
								if($explodeValue[1] == "ProDress")$playerStatus = 3;
								elseif($explodeValue[1] == "ProScratch") $playerStatus = 2;
								elseif($explodeValue[1] == "FarmDress") $playerStatus = 1;
								else $playerStatus = 0;
							}else{
								if($explodeValue[4] != $playerStatus){
									// Check to see if the updated player status = what is already in the DB. 
									// If there is a change, add to the arrSort array.
									$table = ($explodeValue[2] == 16) ? "Goaler" : "Player";
									$arrSort[$table][$explodeValue[1]]["Status". $statuses] = $playerStatus;
								}	
							}// End if count($explodeValue)
						} // End foreach $status
					} // End foreach $_POST["txtRoster"]
					// If there is something in the arrSort variable, then:
					// Loop through the arrSort variable to make 1 individual line of SQL
					// Per player to update the Status values in the DB.
					if(count($arrSort) > 0){
						foreach($arrSort AS $table=>$player){
							foreach($player AS $number=>$statuses){
								if($table == "Goaler")$number -= 10000;
								$sql .= "UPDATE " . $table . "Info ";
								$sql .= "SET ";
								foreach($statuses AS $status=>$s){
									for($x=1;$x<=10;$x++){
										$sql .= "Status". $x ." = " . $s . ", ";
									}
								}
								$sql .= "WebClientModify = 'True' ";
								$sql .= "WHERE Number = " . $number . ";";
							} // End foreach $player
						}// End foreach $arrSort
						//Update the database and save the lines.
						$db->exec("pragma journal_mode=memory;");
						$db->exec($sql);
						$confirmbannertext = "Roster has been saved."; 
					}else{
						$confirmbannertext = "No changes have been made to your roster."; 
					}
					
					$confirmbanner = "<div class=\"confirm\">". $confirmbannertext ."</div>";  
				} // End if isset($_POST["sbtRoster"])
				
				// Get the team selection form from the html API if needed
				if($showDropdown){
					api_html_form_teamid($db,$teamid);
				} // End if there is a showDropdown flag

				// If there is a team ID to use
				if($teamid > 0){?>
					
						<?php 
						echo $confirmbanner;
						$status = array();
						// Use the player_base SQL API to get the base information
						// loop for players and goalies
						// Add add your own order and query
						$sql = "";
						foreach(array("Player","Goaler") AS $type){
							$sql .= api_sql_players_base($type);
							$sql .= "WHERE Team = ". $teamid ." ";
							$sql .= "UNION ";
						}// End foreach array(Player,Goalie)

						$sql = rtrim($sql,"UNION ") . " ";
						$sql .= "ORDER BY Name ASC, Overall DESC";
						$oRS = $db->query($sql);

						// Loop through queries result and add values to new array to display players and goalies
						while($row = $oRS->fetchArray()){
							// Loop s for each status on each player
							for($s=1;$s<=1;$s++){
								// If the player is a goalie, add 10000 to the PLayer Number
								// This takes into account ID numbers can be duplicated in the PlayerInfo and GoalerInfo tables
								if($row["PositionString"] == "G"){$row["Number"]+=10000;}
								// Do not allow players without contracts to be dressed.
								if($row["Contract"] == 0){
									$row["Injury"] = "No Contract";
									// Make them Pro Scratched or Farm Scratched.
									$row["Status".$s] = ($row["Status".$s] == 3) ? 2 : 0;
								}
								$status[$s][$row["Status".$s]][$row["Number"]]["Number"] = $row["Number"];
								$status[$s][$row["Status".$s]][$row["Number"]]["Name"] = $row["Name"];
								$status[$s][$row["Status".$s]][$row["Number"]]["Injury"] = $row["Injury"];
								$status[$s][$row["Status".$s]][$row["Number"]]["PositionString"] = $row["PositionString"];
								$status[$s][$row["Status".$s]][$row["Number"]]["PositionNumber"] = $row["PositionNumber"];
								$status[$s][$row["Status".$s]][$row["Number"]]["Status1"] = $row["Status".$s];
								$status[$s][$row["Status".$s]][$row["Number"]]["Overall"] = $row["Overall"];
								$status[$s][$row["Status".$s]][$row["Number"]]["ForceWaiver"] = $row["ForceWaiver"];
							} // End for loop for statuses
						} // End while loop for players in result.
						
						// Create a next 10 games array to see the games both Pro and Farm will play.
						$nextgames = api_get_nextgames($db,$teamid);
						
						// start the form to submit the roster.?>
						<form name="frmRosterEditor" method="POST" id="frmRoster">
							<?php 
								foreach(api_dbresult_roster_editor_fields($db,$teamid) AS $k=>$f){
									if(!is_numeric($k)){
										?><input type="hidden" id="<?= $k ?>" value="<?=strtolower($f); ?>"><?php 
										echo "\n";
									}
								}
							?>


							<?php  
							// This accordion id is a JQuery accordion. If this ID changes then the JQuery has to be changed as well.
							?>
							<div id="accordionfuture">
								<?php 
								// Loop through the next games variable to get the lines for the next 10 games.
								foreach($nextgames AS $nextgame=>$games){?>
									<?php  //$accordionhead = ($games["Pro"]["Day"] != "") ? $nextgame . ". Pro Day " . $games["Pro"]["Day"] ." - " . $games["Pro"]["AtVs"] . " " . $games["Pro"]["Opponent"] ." | Farm: Day " . $games["Farm"]["Day"] . " - " . $games["Farm"]["AtVs"] . " " . $games["Farm"]["Opponent"] : "Currently No Schedule"; ?>
									<?php  $accordionhead = api_make_nextgame($games,"Pro") . " | " . api_make_nextgame($games,"Farm"); ?>
									<h3 class="withsave"><?= $accordionhead?>

									<div class="Save">
									<!--<input type="button" id="change" value="Copy Roster 1 to other days." >-->

									<input id="saveroster" type="submit" name="sbtRoster" value="Save Rosters">

									</div>

									<span id="linevalidate<?=$nextgame;?>"></span></h3>
									<div>
										<div id="rostererror<?= $nextgame ?>" class="rostererror"></div>
										<?php api_html_checkboxes_positionlist("rosterline1","false","list-item"); ?>
										<div class="columnwrapper"><?php 
											for($x=3;$x>=0;$x--){
												if($x == 3){
													$type = "Pro Dress";	
												}elseif($x == 2){
													$type = "Pro Scratch";
												}elseif($x == 1){
													$type = "Farm Dress";
												}else{
													$type = "Farm Scratch";
												}
												$columnid = str_replace(" ","",$type);
												$colcount = 0;
												
												// the id in the ol will be one of #sortProDress, #sortProScratch, #sortFarmDress, #sortFarmScratch.
												// These id's are in the JQuery call to make the columns sortable via drag and drop. If the IDs change
												// the calls will have to change in the JQuery.
												?>
												<div class="col4">
													<ol id="sort<?= str_replace(" ","",$columnid)?>" class="sort<?= str_replace(" ","",$columnid) . $nextgame; ?> connectedSortable ui-sortable">
														<h4 class="columnheader"><?= $type?></h4>
														<input class="rosterline<?=$nextgame; ?>" type="hidden" name="txtRoster[<?=$nextgame; ?>][]" value="LINE|<?= $columnid; ?>">
														<?php  	
															// Checks to see if there are players in the current category.
															// example, if there is at least 1 player in the ProScratch category, loop through and display
															if(array_key_exists($x, $status[$nextgame])){
																foreach($status[$nextgame][$x] AS $sid=>$s){
																	// Checks to see if a player is injured. if so, it will add an injury class
																	// to the <li> which will not allow him to be part of the JQuery drag and drop
																	// therefore unmovable. 
																	$inj = ($s["Injury"] != "") ? " injury": "";
																	
																	// playerrow class is the class JQuery is looking for to allow the drag and drop process
																	// if an <li> field has this, it can potentially be moved up and down the column.
																	?>
																	<li id="line<?=$nextgame . "_" . api_MakeCSSClass($s["Name"])?>" class="playerrow <?= $columnid . $inj; ?>">
																		<div class="rowinfo">
																			<?php 
																			// Use a hidden field in the form to get the info to save to the SQLite DB.
																			// The value of the hidden field is a string separated by pipes (|) to parse
																			// on submit "fieldName|fieldNumber|positionNumber(1-16)|positionString(C,LW)"
																			$value = api_fields_input_values($s);
																			?>
																			<input class="rosterline<?=$nextgame; ?> <?= "input".$columnid . $nextgame?>" id="g<?=$nextgame;?>t<?=$columnid;?><?= $colcount++;?>" type="hidden" name="txtRoster[<?=$nextgame; ?>][]" value="<?= $value; ?>">
																			<div class="rowname"><?= $s["Name"]?></div><div class="rowinfoline"><?= $s["PositionString"]?> - <?= $s["Overall"]?>OV</div>
																		</div>
																	</li>
																<?php }
															}?>
													</ol>
												</div><?php
											}?>
										</div><!-- End .columnwrapper-->
									</div><!-- End classless/id-less div for the accordion--><?php 
								break;
								} // End foreach $nextgames?>
							</div><!-- End #accordion-->
						</form> <!-- End frmRostereditor --><?php 
				}// End if there is a teamid as a parameter?>
			</div>
		</div><!-- End #rostereditor->$id --><?php
	}
	function api_pageinfo_editor_lines($db,$teamid=0,$league=false,$showDropdown=true,$showHeader=true,$useServerURIInTabLink=false){
		// $db = sqlite DB
		// $teamid is a teamid to use that teams roster.
		// $league is "Pro" or "Farm" based on selection.
		// $showDropdown is a flag if you want to toggle between teams.
		// $showHeader is a flag if you want to show the H1 Tag

		// Set the page id
		$id = "lineeditor";
		
		// Check to see if there is a team selected.
		if($teamid > 0){
			// Set the status value if the league is Pro or Farm
			$status = ($league == "Pro") ? 3: 1;
			// Select all the players and goalies if they are dressed.
			$sql = "SELECT Number, Name FROM PlayerInfo WHERE Team = " . $teamid . " AND Status1 = " . $status . " ";
			$sql .= "UNION ";
			$sql .= "SELECT Number, Name FROM GoalerInfo WHERE Team = " . $teamid . " AND Status1 = " . $status . " ";

			// Get the recordset of all the players
			$oRS = $db->query($sql);
			// Make an array of available players to use.
			// This makes comparing from a roster change easier.
			// i.e. Database could show a player in a position in the lines table, but if that
			// player was scratched, or moved between farm and pro, there has to be a way to
			// show he isn't there and show blank on the line. 
			$availableplayers = array();
			while($row = $oRS->fetchArray()){
				$availableplayers[api_MakeCSSClass($row["Name"])]["id"] = $row["Number"];
				$availableplayers[api_MakeCSSClass($row["Name"])]["Name"] = $row["Name"];
			}

			// Check to see if Custom OT lines are turned on 
			$sql = "SELECT " . $league . "CustomOTLines AS CustomLines FROM LeagueGeneral;";
			$oRS = $db->query($sql);
			$row = $oRS->fetchArray();
			$customOTlines = ($row["CustomLines"] == "True") ? true: false;
		}// end if $teamid

		
		
		// If the updatelines submit button is clicked 
		if(isset($_POST["sbtUpdateLines"])){
			$fminfo = "";
			$dbfields = api_get_fields($db,$customOTlines,$league);
			$fmfields = array_merge($_POST["txtLine"],$_POST["txtStrategies"]);

			$sql = "SELECT " . implode(" || ',' || ", $dbfields) . " AS LineValues FROM Team". $league ."Lines WHERE TeamNumber = " . $teamid . " AND Day = 1;";
			$oRS = $db->query($sql);
			$row = $oRS->fetchArray();
			$dbinfo = $row["LineValues"];
			foreach($dbfields AS $f){
				$fminfo .= $fmfields[$f] . ",";
			}
			$fminfo = rtrim($fminfo,",");

			if(trim($fminfo) != trim($dbinfo)){
				$arrDB = explode(",",$dbinfo);
				$arrFM = explode(",",$fminfo);
				$dbfields = array_values($dbfields);
				
				// Need 2 running query strings: one for the regular lines table
				// And one for the numberonly table.
				// For now this will update all 10 game slots for lines.
				$sql   = "UPDATE Team". $league ."Lines SET ";
				$sqlno = "UPDATE Team". $league ."LinesNumberOnly SET ";

				foreach($dbfields AS $i=>$f){
					if($arrDB[$i] != $arrFM[$i]){
						if(is_numeric($arrFM[$i])){
							$val    = api_sqlite_escape($arrFM[$i]);
							$valno  = api_sqlite_escape($arrFM[$i]);
						}else{
							$val    = "'" . api_sqlite_escape($arrFM[$i]) . "'";
							$valno  = $availableplayers[api_MakeCSSClass($arrFM[$i])]["id"];
						}
						$sql   .= $f . " = " . $val . ", ";
						$sqlno .= $f . " = " . $valno . ", ";
					}
				}
				
				$sql = rtrim($sql,", ");
				$sqlno .= " WebClientModify = 'True' ";

				$sql .= " WHERE TeamNumber = " . $teamid . ";";
				$sqlno .= " WHERE TeamNumber = " . $teamid . ";";
				$db->exec("pragma journal_mode=memory;");
				$db->exec($sql);
				$db->exec($sqlno);	
				$bannertext = "Lines have been saved.";
			}else{
				$bannertext = "No changes have been made.";
			}
			?><div class="confirm"><?= $bannertext?></div><?php
		}// end isset $_POST[sbtUpdateLines]

		// Get the team selection form from the html API if needed ?>
		<div id="<?= $id ?>">
			<div class="pagewrapper pagewrapper<?= $id ?>"><?php 
				api_alpha_testing();
				if($showHeader){
					$row = ($teamid > 0) ? api_dbresult_teamname($db,$teamid,$league) : array();
					$teamname = (!empty($row)) ? $row["FullTeamName"] . " - " : "";
					?>
					<h1><?= $teamname ?>Line Editor</h1><?php
				}
				if($showDropdown){
					api_html_form_teamid($db,$teamid,true);
				} // End if there is a showDropdown flag

				// If there is a team selected
				if($teamid > 0 && $league){
					// Create a next 10 games array to see the games both Pro and Farm will play.
					$nextgames = api_get_nextgames($db,$teamid);
					?><h3 class="withsave"><?= api_make_nextgame($nextgames[1],$league);?></h3>

					<?php
					
					// Error block for updating feedback to the user.
					?><div id="errors"></div><?php 
					if($league == "Pro"){
						// Set Pro variables
						$status1 = 3;
						$isPro = true;
					}else{
						// Set Farm variables
						$status1 = 1;
						$isPro = false;
					}
					// get the fields needed for the ChangePlayer function onClick
					$dbfields = api_dbresult_line_editor_fields($db);
					$cpfields = "";
					foreach($dbfields AS $f){$cpfields .= strtolower($f) .",";}
					$cpfields = rtrim($cpfields,",");
					
					// Get all the players and goalies of the team who are dressed
					$sql = api_sql_players_base("Player",$isPro);
					$sql .= "WHERE Team = " . $teamid . " AND Status1 = " . $status1 . " ";
					$sql .= "UNION ";
					$sql .= api_sql_players_base("Goaler",$isPro);
					$sql .= "WHERE Team = " . $teamid . " AND Status1 = " . $status1 . " ";
					$sql .= "ORDER BY Name ASC, Overall DESC ";
					?>
					
					<div class="playerlist">
						<?php api_html_checkboxes_positionlist("sltPlayerList","true","list-item"); ?>
						<form name="frmPlayerList">
							<ul class="playerselect">
							<?php 	// Loop through the players and add to the select list.
							$oRS = $db->query($sql);
							$first = true;
							while($row = $oRS->fetchArray()){
								//if its the first item in the loop, select the item as default.
								if($first){$s = " checked";$first = false;}else{$s = "";}
								// Separate Name and number with a pipe '|' to split in the javascript.
								$values = api_fields_input_values($row);
								?>
								<li id="line1_<?= api_MakeCSSClass($row["Name"])?>" class="option">
									<input name="sltPlayerList" type="radio" id="a<?= api_MakeCSSClass($row["Name"]); ?>" <?= $s;?> value="<?= $values; ?>">
									<label for="a<?= api_MakeCSSClass($row["Name"]); ?>"><?= $row["Name"];?> - <?= $row["PositionString"];?> <span class="smalllist">(<?= $row["Overall"]; ?>OV)</span></label>
								</li><?php 
							}?>
							<li class="option">
								<input name="sltPlayerList" type="radio" id="aRemove" value="">
								<label for="aRemove">Remove Player/Goalie</label>
							</li>
							</ul>
						</form><!-- end frmPlayerList-->
					</div><!-- end playerlist-->
					<?php 
					// Select all the lines and players in them for the next game.
					$sql = "SELECT l.* FROM Team". $league ."Lines AS l LEFT JOIN Team". $league ."Info AS t ON t.Number = l.TeamNumber ";
					$sql .= "WHERE t.Number = '" . $teamid . "' AND Day = 1 ";
					$oRS = $db->query($sql);
					$row = $oRS->fetchArray();

					// Fill in arrays needed. 
					//		tabs = line pages, 
					//		blocks =  section per page, 
					//		positions = different position combination for the blocks, 
					//		strategy = strategy slider info.  

					$tabs = api_get_line_arrays("tabs");
					$blocks = api_get_line_arrays("blocks");
					$positions = api_get_line_arrays("positions");
					$strategy = api_get_line_arrays("strategy");
					$count = 0;
					?>
					
					<?php  // Start the tabs for pages of lines.?>
					<div class="linetabs">
						<div id="tabs">
							<ul class="positiontabs">
								<?php  // loop through the tab names creating clickable tabs. ?>
								<?php  
								$tablink = ($useServerURIInTabLink) ? $_SERVER["REQUEST_URI"] . "#tabs-" : "#tabs-";
								foreach($tabs AS $i=>$t){
									$displaytab = false;
									if($i != "OT"){$displaytab = true;
									}elseif($i == "OT" && $customOTlines){$displaytab = true;
									}else{$displaytab = false;
									}
									if($displaytab){?>
										<li class="tabitem"><a href="<?= $tablink . ++$count?>"><?= $t?></a></li><?php 
									}
								}?>	
							</ul>
							<?php $count = 0;?>
							<form id="submissionform" name="frmEditLines" method="POST" onload="checkCompleteLines();">
								<?php
									$buttontext = (api_has_saved_lines($db,$teamid,$league)) ? "Re-Save Lines" : "Save Lines";
								?>
								<div class="SaveButton">
									<input id="autolines" onClick="javascript:auto_lines('<?= $league ?>',<?=$cpfields?>);" type="button" name="btnAutoLines" value="Auto Lines">
									<input id="linesubmit" type="submit" value="<?= $buttontext?>" name="sbtUpdateLines" form="submissionform" />

								</div><?php 
								// Loop through the tabs info making the lines pages.
								foreach($tabs AS $i=>$t){
									$displaytab = false;
									if($i != "OT"){$displaytab = true;
									}elseif($i == "OT" && $customOTlines){$displaytab = true;
									}else{$displaytab = false;
									}
									if($displaytab){
										?><div id="tabs-<?= ++$count ?>" class="tabcontainer"><?php 
											if($i == "Forward" || $i == "Defense" || $i == "PP" || $i == "PK4" || $i == "4VS4" || $i == "PK3"){	
												// Each of the if'ed statements above have the same kind of info. 
												// display the blocks based on which tabbed page you are on.
												api_make_blocks($row,$blocks,$positions,$strategy,$i,$availableplayers,$cpfields,$league);
											}elseif($i == "Others"){?>
												<?php // Start with the goalies. ?>
												<div class="linesection id<?= api_MakeCSSClass($i)?> goalies">
													<?php 
														foreach(array(1=>"Starting Goalie",2=>"Backup Goalie",3=>"Third String") AS $gid=>$g){?>
															<h4><?= $g?></h4>
															<div class="blockcontainer">
																<?php  $row["Goaler" . $gid] = (isset($availableplayers[api_MakeCSSClass($row["Goaler".$gid])])) ? $row["Goaler".$gid]: "";?>
																<div class="positionline"><?= "<input class=\"textname\" id=\"Goaler". $gid ."\" onclick=\"ChangePlayer('Goaler". $gid ."','". $league ."',".$cpfields.");\"  readonly type=\"text\" name=\"txtLine[Goaler". $gid ."]\" value=\"". $row["Goaler".$gid] ."\">";?></div>
															</div><?php 
														}
													?>
												</div><!-- end linesection <?= api_MakeCSSClass($i)?> goalies-->
												<?php 
												// Get the other page fields needed for the blocks.
												$field = api_get_line_arrays("otherfield");

												// Make the extra forwards and extra defense.
												foreach($field["start"] AS $fsid=>$fs){?>
													<div class="linesection id<?= api_MakeCSSClass($i)?> extra <?= $fs?>">
														<h4>Extra <?= $fs?></h4>
														<div class="blockcontainer">
															<?php foreach($field["end"] AS $feid=>$fe){
																$usefield = "Extra" .$fsid . $fe;
																if(array_key_exists($usefield, $row)){?>
																	<div class="positionline">
																		<div class="positionlabel"><?= $fe?></div>
																		<div class="positionname">
																			<?php  $row[$usefield] = (isset($availableplayers[api_MakeCSSClass($row[$usefield])])) ? $row[$usefield] : "";?>
																			<input id="<?= $usefield ?>" onclick="ChangePlayer('<?= $usefield ?>','<?= $league ?>',<?=$cpfields?>);" class="textname" readonly type="text" name="txtLine[<?= $usefield ?>]" value="<?= $row[$usefield] ?>">
																		</div>
																	</div><?php 
																}
															}?>
														</div><!--end blockcontainer -->
													</div><!-- end linesection <?= api_MakeCSSClass($i)?> extra <?= $fs?>--><?php 
												}?>
												<?php // Make the penalty shots order?>
												<div class="linesection id<?= api_MakeCSSClass($i)?> penaltyshots">
													<h4>Penalty Shots</h4>
													<div class="blockcontainer">								
														<div class="penaltyshot">
															<?php  for($x=1;$x<6;$x++){?>
															<div class="positionline">
																<div class="positionlabel"><?= $x ?>.</div>
																<div class="positionname">
																	<?php  $row["PenaltyShots" . $x] = (isset($availableplayers[api_MakeCSSClass($row["PenaltyShots" . $x])])) ? $row["PenaltyShots" . $x] : "";?>
																	<input id="PenaltyShots<?= $x ?>" onclick="ChangePlayer('PenaltyShots<?= $x ?>','<?= $league ?>',<?=$cpfields?>);" class="textname" readonly type="text" name="txtLine[PenaltyShots<?= $x ?>]" value="<?= $row["PenaltyShots" . $x] ?>">
																</div>	
															</div>
															<?php }?>
														</div>
													</div><!-- end blockcontainer-->
												</div><!-- end linesection <?= api_MakeCSSClass($i)?> penaltyshots-->
												<?php
											}else if($i == "OT"){ 
												foreach(array(10=>"Forward",5=>"Defense") AS $i=>$p){
												?><div class="linesection idot ot<?= $p?>">
													<h4><?= $p?></h4>
													<div class="blockcontainer">
														<?php
														for($x=1;$x<=$i;$x++){
															?>
															<div class="positionline">
																<div class="positionlabel"><?= $x?>.</div>
																<div class="positionname">
																	<input class="textname" id="OT<?= $p.$x;?>" onclick="ChangePlayer('OT<?= $p.$x;?>','<?= $league ?>',<?=$cpfields?>);"  readonly type="text" name="txtLine[OT<?=$p.$x;?>]" value="<?= $row["OT". $p.$x]; ?>">
																</div>
															</div><?php
														}
														?>
													</div>
												<?php

												?></div><?php	
												}
											}else if($i == "Strategy"){?>
													<h4>Team Wide Strategy</h4>
													<div class="strategieswrapper linesection">
														<?php
															$text = "";
															 for($x=1;$x<=5;$x++){
															 	if($x == 1 || $x == 2){$text = "If winning by ";}
															 	else if($x == 4 || $x == 5){
															 		$text = "If losing by";}
															 	else{$text = "If the score is equal  ";
															 	}

															 	?><div class="strategywrapper teamstrategy teamstrategy<?= $x; ?>">
																	<div class="strategyamount">
																		<?= $text ?> 
																		<?php if($x != 3){?><?php api_make_strategies($row,"Strategy". $x,"GoalDiff","Int-10",$cpfields);?><?php } ?>
																		then strategy is 
																	</div>
																	<div class="strategystrategies">
																		<div class="">
																			<div class="strategy">
																				<?php foreach($strategy AS $sid=>$strat){?>
																					<div class="strats">
																						<div class="stratlabel"><?= $sid?> : </div>
																						<div class="stratvalue">
																							<?php api_make_strategies($row,"Strategy" .$x,$sid,"Strat",$cpfields);?>
																						</div>
																					</div>
																				<?php }?>
																			</div><!-- end strategy-->
																		</div><!-- end strategywrapper-->
																	</div>
																</div><?php
															 }
														?>
														<div class="strategywrapper PullGoalerMinGoal">
															<div class="strategyamount">Goalie Minimum # of Goals before Remove from Game</div>
															<div class="strategystrategies"><?php api_make_strategies($row,"PullGoaler","MinGoal","Int-10",$cpfields);?></div>
														</div>
														<div class="strategywrapper PullGoalerMinPct">
															<div class="strategyamount">Goalie Save PCT Under before Remove from Game</div>
															<div class="strategystrategies"><?php api_make_strategies($row,"PullGoaler","MinPct","Int-100",$cpfields);?></div>
														</div>
														<div class="strategywrapper RemoveGoalieSecond">
															<div class="strategyamount">When to remove the goalies from goal if trailing by 1 in the third period (in seconds)</div>
															<div class="strategystrategies"><?php api_make_strategies($row,"Remove","GoaliesSecond","Int-180",$cpfields);?></div>
														</div>
													</div>
												<?php
											}else{
												// Make the Offsensive and Defensive Lines.
												$types = array("Off"=>"Offensive Line","Def"=>"Defensive Line");
												foreach($types AS $tid=>$t){?>
													<div class="linesection id<?= api_MakeCSSClass($i)?> penaltyshots">
														<h4><?= $t?></h4>
														<div class="blockcontainer"><?php 
															$fordef = array("Forward", "Defense");
															foreach($fordef AS $fd){
																foreach($positions[$fd] AS $pid=>$pos){
																	$usefield = "LastMin" . $tid . $fd . $pid;
																	if(array_key_exists($usefield, $row)){
																		?>
																		<div class="positionline">
																			<div class="positionlabel"><?= $pos?></div>
																			<div class="positionname">
																				<?= "<input id=\"". $usefield ."\" onclick=\"ChangePlayer('". $usefield ."','". $league ."',".$cpfields.");\" class=\"textname\" readonly type=\"text\" name=\"txtLine[". $usefield ."]\" value=\"". $row[$usefield] ."\">";?>
																			</div>
																		</div><?php 
																	}
																}
															}?>
														</div><!-- end blockcontainer-->
													</div><!-- end linesection <?= api_MakeCSSClass($i)?> penaltyshots--><?php 
												}
											}?>
										</div><!-- end tabs-<?= $count ?>--><?php 
									}
								}?>
							</form>
						</div><!-- end tabs-->
					</div><!-- end linetabs--><?php 
				}// end if a team is selected?>
			</div><!-- end pagewrapper-->
		</div><!-- End $id div--><?php 
	}
	function api_make_blocks($row,$blocks,$positions,$strategy,$i,$availableplayers,$cpfields,$league){
		$bcount = 0;
		foreach($blocks[$i] AS $bid=>$block){?>
			<div class="linesection id<?= api_MakeCSSClass($i)?> id<?= api_MakeCSSClass($bid)?>">
				<h4><?= $block ?></h4>
				<div class="blockcontainer">
					<div class="positionwrapper">
						<?php 	// Depending on which page you are on sets up how many blocks are needed.
							// If its anything but 5vs5
							if($i == "PP" || $i == "PK4" || $i == "4VS4" || $i == "PK3"){
								if($bid == strtolower($i) . "line1" || $bid == strtolower($i) . "line2"){
									if($i == "PP"){
										$posit = $positions["Forward"];
									}elseif($i == "PK3"){
										$posit = $positions["Forward3"];
									}else{
										$posit = $positions["Forward4"];
									}
									$exp = explode("line",$bid);
									$field = "Line". $exp[1] ."". $i ."Forward";
								}else{
									$exp = explode("pair",$bid);
									$posit = $positions["Defense"];
									$field = "Line". $exp[1] ."". $i ."Defense";
								}
							// else its 5vs5
							}else{
								$field = "Line". ++$bcount ."5vs5" . $i;
								$posit = $positions[$i];
							}?>
						<?php foreach($posit AS $pid=>$pos){?>
							<div class="positionline">
								<div class="positionlabel"><?= $pos?></div>
								<div class="positionname">
									<?php  $row[$field . $pid] = (isset($availableplayers[api_MakeCSSClass($row[$field . $pid])])) ? $row[$field . $pid]: "";?>
									<?= "<input id=\"". $field . $pid ."\" onclick=\"ChangePlayer('". $field . $pid ."','". $league ."',".$cpfields.");\" class=\"textname\" readonly type=\"text\" name=\"txtLine[". $field . $pid ."]\" value=\"".  $row[$field . $pid] ."\">";?>
								</div>
							</div>
						<?php }?>
					</div><!-- end positionwrapper-->
					<div class="sliders">
						<div class="strategywrapper">
							<div class="strategy">
								<?php foreach($strategy AS $sid=>$strat){?>
									<div class="strats">
										<div class="stratlabel"><?= $sid?> : </div>
										<div class="stratvalue">
											<?php api_make_strategies($row,$field,$sid,"Strat",$cpfields);?>
										</div>
									</div>
								<?php }?>
							</div><!-- end strategy-->
						</div><!-- end strategywrapper-->
						<div class="timewrapper">
							<div class="time">
								<div class="timelabel">Time%: </div>
								<div class="timevalue">
									<?php api_make_strategies($row,$field,"Time","Time",$cpfields);?>
								</div>
							</div>
						</div><!-- end timerwrapper-->
					</div><!-- end sliders-->
				</div><!-- end blockcontainer-->
			</div><!-- end linesection <?= api_MakeCSSClass($i)?> <?= api_MakeCSSClass($bid)?>--><?php 
		}
	}
	function api_make_strategies($row,$field,$sid,$strat="Strat",$cpfields=""){
		$id = $field . $sid; 
		$size = 0;
		if($strat == "Strat"){$size = 1;}
		elseif($strat == "Time"){$size=3;}
		else{
			$exp = explode("-",$strat);
			if($exp[1] <= 10){$size = 2;}
			else{$size = 3;}
		}
		?>
		<input class="updown down" onclick="valChange('<?= $id ?>','<?= $strat ?>','<?=$field?>','down',<?=$cpfields?>);" type="button" name="btnDown" value="&#160;">
		<input readonly size="<?= $size ?>" id="<?= $id?>" class="stratval" type="text" name="txtStrategies[<?= $id ?>]" value="<?= $row[$id] ?>">
		<input class="updown up" onclick="valChange('<?= $id ?>','<?= $strat ?>','<?=$field?>','up',<?=$cpfields?>);" type="button" name="btnUp" value="&#160;"><?php 
	}
	function api_get_fields($db,$customOTlines,$league){
		// Make an array of field names in the DB.
		$sql = "PRAGMA table_info(Team". $league ."Lines);";
		$oRS = $db->query($sql);
		$count = 0;
		$addfield = false;
		while($row = $oRS->fetchArray()){
			if($row["name"] != "TeamNumber" && $row["name"] != "Day"){
				$fields[$count++] = $row["name"];
			}
		}
		if(!$customOTlines){$fields = array_diff($fields,api_get_line_arrays("otfields"));}
		return $fields;
	}
	function api_get_line_arrays($type="blocks"){
		// This returns an array of needed information.
		$arr["tabs"] = array("Forward"=>"Forward","Defense"=>"Defense","PP"=>"PP","4VS4"=>"4vs4","PK4"=>"PK4","PK3"=>"PK3","Others"=>"Others","LastMin"=>"Last Min","OT"=>"Overtime","Strategy"=>"Strategy");
		$arr["blocks"]["Forward"] = array("line1"=>"Lines #1","line2"=>"Lines #2","line3"=>"Lines #3","line4"=>"Lines #4");
		$arr["blocks"]["Defense"] = array("pair1"=>"Pair #1","pair2"=>"Pair #2","pair3"=>"Pair #3","pair4"=>"Pair #4");
		$arr["blocks"]["PP"] = array("ppline1"=>"PP Lines #1","ppline2"=>"PP Lines #2","pppair1"=>"PP Pair #1","pppair2"=>"PP Pair #2");
		$arr["blocks"]["4VS4"] = array("4vs4line1"=>"4 vs 4 Lines #1","4vs4line2"=>"4 vs 4 Lines #2","4vs4pair1"=>"4 vs 4 Pair #1","4vs4pair2"=>"4 vs 4 Pair #2");
		$arr["blocks"]["PK4"] = array("pk4line1"=>"PK4 Lines #1","pk4line2"=>"PK4 Lines #2","pk4pair1"=>"PK4 Pair #1","pk4pair2"=>"PK4 Pair #2");
		$arr["blocks"]["PK3"] = array("pk3line1"=>"PK3 Lines #1","pk3line2"=>"PK3 Lines #2","pk3pair1"=>"PK3 Pair #1","pk3pair2"=>"PK3 Pair #2");
		$arr["blocks"]["OT"] = array("forwards"=>"Forwards","defense"=>"Defense");

		$arr["positions"]["Forward"] = array("Center"=>"C","LeftWing"=>"LW","RightWing"=>"RW");
		$arr["positions"]["Forward3"] = array("Center"=>"F");
		$arr["positions"]["Forward4"] = array("Center"=>"C","Wing"=>"W");
		$arr["positions"]["Defense"] = array("Defense1"=>"LD","Defense2"=>"RD");
		
		$arr["strategy"] = array("Phy"=>"Phy","DF"=>"DF","OF"=>"OF");

		$arr["otherfield"]["start"] = array("Forward"=>"Forwards","Defense"=>"Defensemen");
		$arr["otherfield"]["end"] = array("N1","N2","N3","PP1","PP2","PK","PP","PK1","PK2");

		$arr["otfields"] = array("OTForward1", "OTForward2","OTForward3","OTForward4","OTForward5","OTForward6","OTForward7","OTForward8","OTForward9","OTForward10","OTDefense1","OTDefense2","OTDefense3","OTDefense4","OTDefense5");
		return $arr[$type];
	}
	function api_get_nextgames($db,$teamid){
		$nextgames = array();
		foreach(array("Pro","Farm") AS $league){
			$count = 0;
			$RS = $db->query(api_sql_next_games($teamid,$league));
			// Loop through next 10 games result to make an array of next games for both pro and farm
			while($row = $RS->fetchArray()){
				$nextgames[++$count][$league]["GameNumber"] = $row["GameNumber"];
				$nextgames[$count][$league]["Day"] = $row["Day"];
				$nextgames[$count][$league]["Opponent"] = $row["Opponent"];
				$nextgames[$count][$league]["AtVs"] = $row["AtVs"];
			} // End while for the schedule
		} // End foreach array(Pro,Farm)

		//Its possible that no schedule has been created yet. If this is the case, we don't need an accordion of rosters, just 1 using Status1.
		if(empty($nextgames)){
			foreach(array("Pro","Farm") AS $league){
				$nextgames[1][$league]["GameNumber"] = "";
				$nextgames[1][$league]["Day"] = "";
				$nextgames[1][$league]["Opponent"] = "";
				$nextgames[1][$league]["AtVs"] = "";
			}// End foreach Pro Farm
		}// End if Empty nextgame
		return $nextgames;
	}
	function api_make_nextgame($nextgame,$league){
		// Return a string of the next games. if its empty, say there is no schedule.
		return ($nextgame[$league]["Day"] != "") ? "Next Game: ". $league ." Day " . $nextgame[$league]["Day"] ." - " . $nextgame[$league]["AtVs"] . " " . $nextgame[$league]["Opponent"] ." " : $league . " - Currently No Schedule";
	}
	function api_has_saved_lines($db,$tid,$l){
		$oRS = $db->query("SELECT WebClientModify FROM Team". $l ."LinesNumberOnly WHERE TeamNumber = " . $tid . " AND Day = 1;");
		$row = $oRS->fetchArray();
		return ($row["WebClientModify"] == "True") ? true : false;
	}
}

function load_api_sql(){
	/*********************************************/
	// SQLite Snippets
	/********************************************/
		// Escape the characters in text that could break the PHP
		function api_sqlite_escape($text){
			$ret = str_replace("'","''",$text);
			return $ret;
		}
		// Returns Goalie Save Percentage
		function api_sql_sp($prefix=false){
			if($prefix){$p = $prefix . ".";}
			$sp = "ROUND((".$p."SA - ".$p."GA) / CAST(".$p."SA AS REAL),3)";
			return $sp;
		}
		// Returns Goalie Goals Against Average
		function api_sql_gaa($prefix=false){
			if($prefix){$p = $prefix . ".";}
			$gaa = "ROUND((". $p ."GA*60) / (". $p ."SecondPlay/60.00),2)";
			return $gaa;
		}
		// Returns a concatenate of position in order of CenterLeftwingRightingDefense, if its goalie 'FalseFalseFalseFalse'
		function api_sql_position($type="Player",$prefix=false){
			if($prefix){$p = $prefix . ".";}
			$pos = ($type == "Goaler") ? "'FalseFalseFalseFalse'" : $p."PosC || ". $p ."PosLW || ". $p ."PosRW || ". $p ."PosD" ;
			return $pos;
		}
		// Returns a number for position sorting 1 = C, 2=C,LW etc. 
		function api_sql_position_number($type="Player",$prefix=""){
			if($prefix != ""){$p = $prefix . ".";}
			if($type != "Goaler"){
				$pc = $p."PosC || ". $p ."PosLW || ". $p ."PosRW || ". $p ."PosD";
				$pos = "CASE ";
				$pos .= "WHEN " . $pc . " = 'TrueFalseFalseFalse' THEN 1 ";
				$pos .= "WHEN " . $pc . " = 'TrueTrueFalseFalse' THEN 2 ";
				$pos .= "WHEN " . $pc . " = 'TrueFalseTrueFalse' THEN 3 ";
				$pos .= "WHEN " . $pc . " = 'TrueTrueTrueFalse' THEN 4 ";
				$pos .= "WHEN " . $pc . " = 'TrueFalseFalseTrue' THEN 5 ";
				$pos .= "WHEN " . $pc . " = 'TrueTrueFalseTrue' THEN 6 ";
				$pos .= "WHEN " . $pc . " = 'TrueFalseTrueTrue' THEN 7 ";
				$pos .= "WHEN " . $pc . " = 'TrueTrueTrueTrue' THEN 8 ";
				
				$pos .= "WHEN " . $pc . " = 'FalseTrueFalseFalse' THEN 9 ";
				$pos .= "WHEN " . $pc . " = 'FalseTrueTrueFalse' THEN 10 ";
				$pos .= "WHEN " . $pc . " = 'FalseTrueFalseTrue' THEN 11 ";
				$pos .= "WHEN " . $pc . " = 'FalseTrueTrueTrue' THEN 12 ";

				$pos .= "WHEN " . $pc . " = 'FalseFalseTrueFalse' THEN 13 ";
				$pos .= "WHEN " . $pc . " = 'FalseFalseTrueTrue' THEN 14 ";
				$pos .= "WHEN " . $pc . " = 'FalseFalseFalseTrue' THEN 15 ";
				$pos .= "END ";
			}else{
				$pos = "16";
			}
			return $pos;
		}
		// Returns a CSV of a position string. 
		function api_sql_position_string($type="Player",$prefix=""){
			if($prefix != ""){$p = $prefix . ".";}
			if($type != "Goaler"){
				$pc = $p."PosC || ". $p ."PosLW || ". $p ."PosRW || ". $p ."PosD";
				$pos = "CASE ";
				$pos .= "WHEN " . $pc . " = 'TrueFalseFalseFalse' THEN 'C' ";
				$pos .= "WHEN " . $pc . " = 'TrueTrueFalseFalse' THEN 'C,LW' ";
				$pos .= "WHEN " . $pc . " = 'TrueFalseTrueFalse' THEN 'C,RW' ";
				$pos .= "WHEN " . $pc . " = 'TrueTrueTrueFalse' THEN 'C,LW,RW' ";
				$pos .= "WHEN " . $pc . " = 'TrueFalseFalseTrue' THEN 'C,D' ";
				$pos .= "WHEN " . $pc . " = 'TrueTrueFalseTrue' THEN 'C,LW,D' ";
				$pos .= "WHEN " . $pc . " = 'TrueFalseTrueTrue' THEN 'C,RW,D' ";
				$pos .= "WHEN " . $pc . " = 'TrueTrueTrueTrue' THEN 'C,LW,RW,D' ";
				
				$pos .= "WHEN " . $pc . " = 'FalseTrueFalseFalse' THEN 'LW' ";
				$pos .= "WHEN " . $pc . " = 'FalseTrueTrueFalse' THEN 'LW,RW' ";
				$pos .= "WHEN " . $pc . " = 'FalseTrueFalseTrue' THEN 'LW,D' ";
				$pos .= "WHEN " . $pc . " = 'FalseTrueTrueTrue' THEN 'LW,RW,D' ";

				$pos .= "WHEN " . $pc . " = 'FalseFalseTrueFalse' THEN 'RW' ";
				$pos .= "WHEN " . $pc . " = 'FalseFalseTrueTrue' THEN 'RW,D' ";
				$pos .= "WHEN " . $pc . " = 'FalseFalseFalseTrue' THEN 'D' ";
				$pos .= "END ";
			}else{
				$pos = "'G' ";
			}
			return $pos;
		}
		// Returns all regular positions in a concatenated string. Goalies are 'FalseFalseFalseFalse'
		function api_sql_position_all($type="Player",$prefix=false){
			if($prefix){$p = $prefix . ".";}
			$pos = ($type == "Goaler") ? "NULL AS PosC, NULL AS PosLW, NULL AS PosRW, NULL AS PosD" : $p."PosC AS PosC, ". $p ."PosLW AS PosLW, ". $p ."PosRW AS PosRW, ". $p ."PosD AS PosD " ;
			return $pos;
		}
		// Returns the players current salary based on Status and Salary
		function api_sql_currentSalary($prefix=false){
			if($prefix){$p = $prefix . ".";}
			$sal = "CASE ";
			$sal .= "WHEN " . $p . "ProSalaryinFarm = 'False' AND Status1 <= 1 THEN ". $p ."Salary1/10 ";
			$sal .= "ELSE ". $p ."Salary1 ";
			$sal .= "END ";
			return $sal;
		}
		// Returns current Streaks
		function api_sql_playerStreak($type="Player",$prefix=false){
			$streak = "";
			if($prefix){$p = $prefix . ".";}
			if($type == "Player"){
				$streak = "" . $p ."GameInRowWithAPoint AS GameInRowWithAPoint, " . $p ."GameInRowWithAGoal AS GameInRowWithAGoal ";
			}else{
				$streak .= "NULL AS GameInRowWithAPoint, NULL AS GameInRowWithAGoal ";
			}
			return $streak;
		}
		// Returns attributes for players pending on "Player", "Goalie", "Common"
		// Common returns all attributes that are the same between player and goalie.
		function api_sql_attributes($type="Common",$prefix=false){
			if($prefix){$p = $prefix . ".";}
			if($type == "Common"){
				$attribs = $p . "SK AS SK, ". $p ."DU AS DU, ". $p ."EN AS EN, ". $p ."SC AS SC, ". $p ."PH AS PH, ". $p ."PS AS PS, ". $p ."EX AS EX, ". $p ."LD AS LD, ". $p ."PO AS PO, ". $p ."Overall AS Overall";
			}elseif($type == "Player"){
				$attribs = $p."CK AS CK, ". $p ."FG AS FG, ". $p ."DI AS DI, ". $p ."ST AS ST, ". $p ."FO AS FO, ". $p ."PA AS PA, ". $p ."DF AS DF, NULL AS SZ, NULL AS AG, NULL AS RB, NULL AS HS, NULL AS RT";
			}else{
				$attribs = "'' AS CK, NULL AS FG, NULL AS DI, NULL AS ST, NULL AS FO, NULL AS PA, NULL AS DF," . $p . "SZ AS SZ, ". $p ."AG AS AG, ". $p ."RB AS RB, ". $p ."HS AS HS, ". $p ."RT AS RT";
			}
			return $attribs;
		}
		// Returns statistics for players pending on "Player", "Goalie", "Common"
		// Common returns all statistics that are the same between player and goalie.
		function api_sql_statistics($type="Common",$prefix=false){
			if($prefix){$p = $prefix . ".";}
			if($type == "Common"){
				$stats = $p . "GP AS GP, ". $p ."SecondPlay AS SecondPlay , ". $p ."Secondplay/60 AS MinutesPlay, ". $p ."Pim AS Pim, ". $p ."Star1 AS Star1, ". $p ."Star2 AS Star2, ". $p ."Star3 AS Star3, ". $p ."EmptyNetGoal AS EmptyNetGoal, ". $p ."A AS A,";
				$stats .= "CASE WHEN ". $p ."GP > 0 THEN 1 WHEN ". $p ."GP = 0 THEN 0 END AS PlayOrder ";
			}elseif($type == "Player"){
				$stats =  "". $p ."Shots AS Shots, ". $p ."G AS G, ". $p ."P AS P, ". $p ."PlusMinus AS PlusMinus, ". $p ."Pim5 AS Pim5, ";
				$stats .= "". $p ."ShotsBlock AS ShotsBlock, ". $p ."OwnShotsBlock AS OwnShotsBlock, ". $p ."OwnShotsMissGoal AS OwnShotsMissGoal, ". $p ."Hits AS Hits, ". $p ."HitsTook AS HitsTook, ";
				$stats .= "". $p ."GW AS GW, ". $p ."GT AS GT, ". $p ."FaceOffWon AS FaceOffWon, ". $p ."FaceOffTotal AS FaceOffTotal, ROUND(". $p ."FaceOffWon/CAST(". $p ."FaceOffTotal AS REAL) * 100,2) AS FaceOffPercent, ";
				$stats .= "". $p ."PenalityShotsTotal AS PenalityShotsTotal, ". $p ."PenalityShotsScore AS PenalityShotsScore, ROUND(PenalityShotsScore/CAST(PenalityShotsTotal AS REAL),2) AS PenalityShotsPercent, ";
				$stats .= "". $p ."HatTrick AS HatTrick, " . $p ."PPG AS PPG, ". $p ."PPShots AS PPShots, ". $p ."PPSecondPlay AS PPSecondPlay, ". $p ."PKG AS PKG, ". $p ."PKShots AS PKShots, ". $p ."PKSecondPlay AS PKSecondPlay, ";
				$stats .= "". $p ."GiveAway AS GiveAway, ". $p ."TakeAway AS TakeAway, " . $p ."PPA AS PPA, ". $p ."PKA AS PKA, ";
				$stats .= "". $p ."PuckPossesionTime AS PuckPossesionTime, ". $p ."FightW AS FightW, ". $p ."FightL AS FightL, ". $p ."FightT AS FightT, ". $p ."FightW + ". $p ."FightL + ". $p ."FightT AS FightTotal, ";
				$stats .= "ROUND(". $p ."G / CAST(". $p ."Shots AS REAL),3) * 100 AS ShotsPercent, ROUND((". $p ."SecondPlay / CAST(60 AS REAL)) / CAST(". $p ."GP AS REAL),1) AS MinutesPerGame,";
				$stats .= "NULL AS W, NULL AS L, NULL AS OTL, NULL AS Shootout, NULL AS GA, NULL AS SA, NULL AS SARebound, NULL AS PenalityShotsShots, NULL AS PenalityShotsGoals, NULL AS StartGoaler, NULL AS BackupGoaler, ";
				$stats .= "NULL AS SavePer, NULL AS GAA ";
			}else{
				$stats = "NULL AS Shots, NULL AS G, NULL AS P, NULL AS PlusMinus, NULL AS Pim5, NULL AS ShotsBlock, NULL AS OwnShotsBlock, NULL AS OwnShotsMissGoal, NULL AS Hits, NULL AS HitsTook, NULL AS GW, NULL AS GT, ";
				$stats .= "NULL AS FaceOffWon, NULL AS FaceOffTotal, NULL AS FaceOffPercent, NULL AS PenalityShotsTotal, NULL AS PenalityShotsScore,  ROUND((PenalityShotsShots-PenalityShotsGoals)/CAST(PenalityShotsShots AS REAL),2) AS PenalityShotsPercent, NULL AS HatTrick, ";
				$stats .= "NULL AS PPG, NULL AS PPShots, NULL AS PPSecondPlay, NULL AS PKG, NULL AS PKShots, NULL AS PKSecondPlay, NULL AS GiveAway, NULL AS TakeAway, NULL AS PPA, NULL AS PKA, ";
				$stats .= "NULL AS PuckPossesionTime, NULL AS FightW, NULL AS FightL, NULL AS FightT, NULL AS FightTotal, ";
				$stats .= "NULL AS ShotsPercent, NULL AS MinutesPerGame, "; 
				$stats .= "". $p ."W AS W, ". $p ."L AS L, ". $p ."OTL AS OTL, ". $p ."Shootout AS Shootout, ". $p ."GA AS GA, ". $p ."SA AS SA, ". $p ."SARebound AS SARebound, ";
				$stats .= "". $p ."PenalityShotsShots AS PenalityShotsShots, ". $p ."PenalityShotsGoals AS PenalityShotsGoals, ". $p ."StartGoaler AS StartGoaler, ". $p ."BackupGoaler AS BackupGoaler, ";
				$stats .= "" . api_sql_sp($prefix) ." AS SavePer, ". api_sql_gaa($prefix) ." AS GAA ";
			}
			return $stats;
		}
		// Returns fields for captains.
		function api_sql_captains(){
			return "c.Captain AS Captain, a1.Assistant1 AS Assistant1, a2.Assistant2 AS Assistant2 ";
		}
		// Returns basic fields for team info. 
		function api_sql_player_teaminfo(){
			return "i.Name AS TeamName, pi.Name AS ProTeamName, pi.Abbre AS ProTeamAbbre, i.Abbre AS Abbre, i.City AS City ";
		}

	// Select Calls for players. $type = "Player" or "Goaler" 
	function api_sql_players_select($type="Player"){
		$t = $type . "Info.";
		$sql = "SELECT " . $t ."Number AS Number, " . $t ."Name AS Name, ";
		$sql .= "" . api_sql_position($type,$type . "Info") ." AS Position, ". api_sql_position_number($type,$type . "Info") ." AS PositionNumber, ". api_sql_position_string($type,$type . "Info") ." AS PositionString, ". api_sql_position_all($type,$type . "Info") .", ";
		$sql .= "" . $t ."Country AS Country, " . $t ."Team AS Team, " . $t ."Age AS Age, " . $t ."AgeDate AS AgeDate, " . $t ."Weight AS Weight, " . $t ."Height AS Height, ";
		$sql .= "" . $t ."Contract AS Contract, " . $t ."Rookie AS Rookie, " . $t ."Injury AS Injury, " . $t ."NumberOfInjury AS NumberOfInjury, ";
		$sql .= "" . $t ."ForceWaiver AS ForceWaiver, ". $t ."CanPlayPro AS CanPlayPro, ". $t ."CanPlayFarm AS CanPlayFarm, ";
		$sql .= "" . $t ."Condition AS Condition, " . $t ."Suspension AS Suspension, " . $t ."Jersey AS Jersey, " . $t ."ProSalaryinFarm AS ProSalaryinFarm, " . api_sql_currentSalary($type . "Info") . " AS CurrentSalary, ";
		$sql .= "" . $t ."Salary1 AS Salary1, " . $t ."Salary2 AS Salary2, " . $t ."Salary3 AS Salary3, " . $t ."Salary4 AS Salary4, " . $t ."Salary5 AS Salary5, ";
		$sql .= "" . $t ."Salary6 AS Salary6, " . $t ."Salary7 AS Salary7, " . $t ."Salary8 AS Salary8, " . $t ."Salary9 AS Salary9, " . $t ."Salary10 AS Salary10, ";
		$sql .= "" . $t ."Status1 AS Status1, " . $t ."Status2 AS Status2, " . $t ."Status3 AS Status3, " . $t ."Status4 AS Status4, " . $t ."Status5 AS Status5, ";
		$sql .= "" . $t ."Status6 AS Status6, " . $t ."Status7 AS Status7, " . $t ."Status8 AS Status8, " . $t ."Status9 AS Status9, " . $t ."Status10 AS Status10, ";
		$sql .= api_sql_playerStreak($type,$type . "Info") . ", ";
		$sql .= api_sql_attributes("Common",$type . "Info") . ", ";
		$sql .= api_sql_attributes($type,$type . "Info") . ", ";
		$sql .= api_sql_statistics("Common","s") . ", ";
		$sql .= api_sql_statistics($type,"s") . ", ";
		$sql .= api_sql_captains() . ", ";
		$sql .= api_sql_player_teaminfo() . "";
		return $sql;	
	}
	// Joins for player calls
	function api_sql_players_joins($type="Players",$isPro=true){
		$t = (!$isPro) ? "Farm" : "Pro";
		$sql = "LEFT JOIN Team". $t ."Info AS i ON i.Number = " . $type . "Info.Team ";
		$sql .= "LEFT JOIN TeamProInfo AS pi ON pi.Number = " . $type . "Info.Team ";
		$sql .= "LEFT JOIN Team". $t ."Info AS c ON c.Captain = " .$type ."Info.Number ";
		$sql .= "LEFT JOIN Team". $t ."Info AS a1 ON a1.Assistant1 = " .$type ."Info.Number ";
		$sql .= "LEFT JOIN Team". $t ."Info AS a2 ON a2.Assistant2 = " .$type ."Info.Number ";
		$sql .= "LEFT JOIN " . $type . $t . "Stat AS s ON s.Number = " . $type . "Info.Number ";
		return $sql;
	}
	// Base call for all players, 
	function api_sql_players_base($type="Player",$isPro=true){
		$sql = api_sql_players_select($type);
		$sql .= "FROM ";
		$sql .= $type . "Info ";
		$sql .= api_sql_players_joins($type, $isPro);
		return $sql;
	}
	// Call to make a recordset of players based on a teamID or playerID
	// This will be changing with future projects along the way as I can
	// Envision more parameters needed for other things.
	function api_sql_players($teamid=false,$playerid=false){
		foreach(array("Player","Goaler") AS $type){
			$sql .= sql_players_base($type);
			$sql .= "WHERE " . $type . "Info.Name IS NOT NULL ";
			
			if($teamid)$sql .= "AND Team = " . $teamid . " ";
			if($playerid)$sql .= "AND " . $type . "Info.Name = '" . api_sqlite_escape($playerid) . "' ";
			$sql .= "UNION ";
		} 
		$sql = rtrim($sql,"UNION ") . " ";
		return $sql;
	}
	// Select all the fields needed for the roster editor.
	function api_sql_roster_editor_fields($teamid){
		$fields = api_fields_roster_editor_setup();
		$sql = "SELECT ";
		foreach($fields AS $f){
			if($f == "isAfterTradeDeadline"){
				$sql .= "(SELECT CASE WHEN ScheduleNextDay/ProScheduleTotalDay*100 >= TradeDeadline THEN 'True' ELSE 'False' END FROM LeagueGeneral) AS ". $f .", ";
			}elseif($f == "isWaivers"){
				$sql .= "(SELECT CASE WHEN (SELECT l.WaiversEnable FROM LeagueSimulation AS l) = 'True' AND ScheduleNextDay/ProScheduleTotalDay*100 <= WaiverDeadline THEN 'True' ELSE 'False' END FROM LeagueGeneral) AS ". $f .", ";
			}elseif($f == "isEliminated"){
				$sql .= "(SELECT PlayOffEliminated FROM TeamProInfo WHERE Number = " . $teamid . ") AS ". $f .",";
			}elseif($f == "GamesLeft"){
				$sql .= "(CASE WHEN (SELECT COUNT(GameNumber) FROM SchedulePro WHERE VisitorTeam = ". $teamid ." AND Play = 'False' OR HomeTeam = ". $teamid ." AND Play = 'False') > 0 THEN 10 WHEN (SELECT COUNT(GameNumber) FROM SchedulePro WHERE VisitorTeam = ". $teamid ." AND Play = 'False' OR HomeTeam = ". $teamid ." AND Play = 'False') < 1 THEN 1 ELSE (SELECT COUNT(GameNumber) FROM SchedulePro WHERE VisitorTeam = ". $teamid ." AND Play = 'False' OR HomeTeam = ". $teamid ." AND Play = 'False') END) AS ". $f .",";
			}elseif($f == "FullFarmEnable"){
				$sql .= "(SELECT FullFarmEnable FROM LeagueSimulation) AS ". $f .",";
			}else{
				$sql .= $f . ",";
			}
		}
		$sql = rtrim($sql,",") . " ";
		$sql .= "FROM LeagueWebClient;";
		return $sql;
	}
	function api_sql_line_editor_fields(){
		$fields = api_fields_line_editor_setup();
		$sql = "SELECT ";
		foreach($fields AS $f){
			if($f == "isAfterTradeDeadline"){
				$sql .= "";
			}elseif($f == "isWaivers"){
				$sql .= "";
			}else{
				$sql .= $f . ",";
			}
		}
		$sql = rtrim($sql,",") . " ";
		$sql .= "FROM LeagueWebClient;";
		return $sql;
	}

	function api_sql_next_games($teamid,$league){
		$sql = "SELECT GameNumber, Day, VisitorTeamName, HomeTeamName, VisitorTeam, ";
		$sql .= "CASE WHEN VisitorTeam = ". $teamid ." THEN 'at' ELSE 'vs' END AS AtVs, ";
		$sql .= "CASE WHEN VisitorTeam = ". $teamid ." THEN HomeTeamName ELSE VisitorTeamName END AS Opponent ";
		$sql .= "FROM Schedule" . $league . " ";
		$sql .= "WHERE VisitorTeam = ". $teamid ." AND Play = 'False' ";
		$sql .= "OR HomeTeam = ". $teamid ." AND Play = 'False' ";
		$sql .= "LIMIT 10 ";
		return $sql;
	}
}
?>