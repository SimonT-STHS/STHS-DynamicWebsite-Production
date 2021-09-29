<?php include "Header.php";?>
<?php
$LeagueName = (string)"";
$NewsID = -1;
$ReplyNews = (integer)0;
$NewsTeam = (integer)0;
$NewsTitle = (string)"";
$NewsMessage = (string)"";
$InformationMessage = (string)"";
$Owner = (string)"";
$IncorrectLoginCookie = (boolean)FALSE;
$HashMatch = (boolean)FALSE; /* Cookie Match User Select */

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$LeagueNews = Null;
	$LeagueGeneral = Null;
	$InformationMessage = $DatabaseNotFound;
}elseIf (file_exists($NewsDatabaseFile) == false){
	$LeagueName = $NewsDatabaseNotFound;
	$LeagueNews = Null;
	$LeagueGeneral = Null;
	$InformationMessage = $NewsDatabaseNotFound;	
}else{
	$db = new SQLite3($DatabaseFile);
	$dbNews = new SQLite3($NewsDatabaseFile);
	mb_internal_encoding("UTF-8");
	$Query = "Select Name FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If ($CookieTeamNumber == 0){$InformationMessage = $NoUserLogin;}
	
	/* Get NewsID by Get */
	if(isset($_GET['NewsID'])){
		$NewsID = filter_var($_GET['NewsID'], FILTER_SANITIZE_NUMBER_INT);
	}
	
	/* Get NewsID by Post / For New NewsID or Save Current One / Overwrite the Get */
	if (isset($_POST["NewsID"]) && !empty($_POST["NewsID"])) {
		$NewsID = filter_var($_POST["NewsID"], FILTER_SANITIZE_NUMBER_INT);
	}
	
	/* Get NewsID Reply by Get for New Reply */
	if(isset($_GET['ReplyNews'])){
		$ReplyNews = filter_var($_GET['ReplyNews'], FILTER_SANITIZE_NUMBER_INT);
	}	
	
	/* Get NewsID Reply by Post for Reply Saved */
	if (isset($_POST["ReplyNews"]) && !empty($_POST["ReplyNews"])) {
		$ReplyNews = filter_var($_POST["ReplyNews"], FILTER_SANITIZE_NUMBER_INT);
	}	
	
	if ($NewsID >= 0 && isset($_POST["Erase"]) && $CookieTeamNumber > 0) {
		/* Process Delete Button */

		/* Check if News Exist Exist */
		$Query = "SELECT TeamNumber, Title FROM LeagueNews WHERE Number = " . $NewsID;	
		$NewsOwner = $dbNews->querySingle($Query,true);
				
		If ($NewsOwner != Null){
			/* Delete From Database if the News exist */
			
			/* Get Confirm User */
			If ($NewsOwner['TeamNumber'] > 0){
				If ($CookieTeamNumber == $NewsOwner['TeamNumber']){$HashMatch = True;}
			}
			
			If ($HashMatch == False){
				/* League Management User for League and also GM News */
				If ($CookieTeamNumber == 102){$HashMatch = True;}
			}
			
			If ($HashMatch == True){
				/* Delete From Database */
				$InformationMessage = $News['News'] . "\"" . $NewsOwner['Title'] . "\"" . $News['WasErase'];
				
				$sql = "DELETE from LeagueNews WHERE LeagueNews.AnswerNumber = " . $NewsID;
				$dbNews->exec($sql);
				
				$sql = "DELETE from LeagueNews WHERE LeagueNews.Number = " . $NewsID;
				$dbNews->exec($sql);
			}else{
				/* Hash do not Match */
				$InformationMessage = $News['IllegalAction'];
			}
		}else{
			/* Didn't find the News */
			$InformationMessage = $News['ErrorErase'];
		}
	}elseif (isset($_POST["editor1"]) && !empty($_POST["editor1"]) && isset($_POST["Title"]) && !empty($_POST["Title"]) && $CookieTeamNumber > 0) {
		/* Process Submit Button */

		If ($NewsID >= 0){
			/* News Already Exist */
			
			$Query = "SELECT TeamNumber FROM LeagueNews WHERE Number = " . $NewsID;	
			$NewsOwner = $dbNews->querySingle($Query,true);
			If ($NewsOwner != Null){
			
				/* Get Confirm User */
				If ($NewsOwner['TeamNumber'] > 0){
					If ($CookieTeamNumber == $NewsOwner['TeamNumber']){$HashMatch = True;}
				}
				
				If ($HashMatch == False){
					/* League Management User for League and also GM News */
					If ($CookieTeamNumber == 102){$HashMatch = True;}
				}
				
				If ($HashMatch == True){
					/* Update Existing NewsID */
					$sql = "UPDATE LeagueNews SET Title = '" . filter_var($_POST["Title"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK) . "',Message = '" . $_POST["editor1"] . "',WebClientModify = 'True' WHERE Number = " . $NewsID;
					$dbNews->exec($sql);
					$InformationMessage = $News['SaveSuccessfully'];
				}else{
					/* Hash do not Match */
					$InformationMessage = $News['IllegalAction'];
					$IncorrectLoginCookie = TRUE; /* Important to Post Variable are resend to user */
				}
			}else{
				/* Didn't find the News */
				$InformationMessage = $News['ErrorErase'];
			}				
		}else{
			/* New News */
			/* Get Hash and Owner/Writer Information*/
			if(isset($_POST["Team"]) && !empty($_POST["Team"])){
				$NewsTeam = filter_var($_POST["Team"], FILTER_SANITIZE_NUMBER_INT);
				If ($CookieTeamNumber == $NewsTeam){
					$HashMatch = True;
					If ($NewsTeam == 101){
						$Owner = $News['Guest'];
					}elseIf ($NewsTeam == 102){
						$Owner = $News['LeagueManagement'];				
					}else{
						/* Get GM Name in Database */
						$Query = "SELECT GMName FROM TeamProInfo WHERE Number = '" . $NewsTeam . "'";
						$TeamGM = $db->querySingle($Query,true);
						$Owner = $TeamGM['GMName'];
					}
				}
			}else{
				/* Setup Information Required Later */
				$NewsTeam = 0;
			}
			
			/* League Management User for League and also GM News */
			If ($NewsTeam == 0 || $HashMatch == False){
				If ($CookieTeamNumber == 102){
					$HashMatch = True;
					$Owner = $News['LeagueManagement'];
				}
			}
			
			If ($HashMatch == True){
				/* Create a new record  */
				$Query = "INSERT INTO LeagueNews (Time,TeamNumber,TeamNewsNumber,Owner,Title,Message,Remove,WebClientModify,AnswerNumber) VALUES('" . gmdate('Y-m-d H:i:s') . "','" . $NewsTeam . "','0','" . $Owner . "','" . filter_var($_POST["Title"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK) . "','" . $_POST["editor1"] . "','False','True'," . $ReplyNews . ")";
				$dbNews->exec($Query);
				$InformationMessage = $News['SaveSuccessfully'];
				
				/* Get the Current News from Incredement to Load the News Normally like your press the edit link */
				$Query = "Select LeagueNews.Number FROM LeagueNews ORDER BY Number DESC LIMIT 1";
				$LastLeagueNewsNumber = $dbNews->querySingle($Query,true);
				$NewsID = $LastLeagueNewsNumber['Number'];
			}else{
				/* Hash do not Match */
				$InformationMessage = $News['IllegalAction'];
				$IncorrectLoginCookie = TRUE; /* Important to Post Variable are resend to user */
			}				
		}
	}
	
	If ($NewsID >= 0){
		/* Load the News Request */
		$Query = "Select LeagueNews.* FROM LeagueNews WHERE LeagueNews.Number = " . $NewsID;
		$LeagueNews = $dbNews->querySingle($Query,true);
		
		If ($LeagueNews == Null){
			/* Reset Data if News ID doesn't exist */
			$NewsID = -1;
		}else{
			/* Get the Current News TeamNumber for the Team Select*/
			$NewsTeam = $LeagueNews['TeamNumber'];
			$NewsTitle = $LeagueNews['Title'];
			$NewsMessage = $LeagueNews['Message'];
			If($LeagueNews['AnswerNumber'] > 0){$ReplyNews = $LeagueNews['AnswerNumber'];}
		}
	}elseIf ($ReplyNews > 0){
		/* Following Section Should Only Apply when Creating new Reply - If $NewsID is fill, it had priority to the ReplyNews Variable*/
				
		/* Load the News Request */
		$Query = "Select LeagueNews.* FROM LeagueNews WHERE LeagueNews.Number = " . $ReplyNews;
		$LeagueNews = $dbNews->querySingle($Query,true);
		
		If ($LeagueNews == Null){
			/* Reset Data if News ID doesn't exist */
			$ReplyNews = 0;
		}else{
			$NewsTitle = $LeagueNews['Title'];
		}	
	}
		
	If($IncorrectLoginCookie == TRUE){
		/* If the Hash was incorrect, put the data from the Post into the Title and News Input */
		$NewsTitle = filter_var($_POST["Title"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);
		$NewsMessage = $_POST["editor1"];
	}
}
echo "<title>" . $LeagueName . " - " . $News['LeagueNews'] . "</title>";

?>
<style>
form { display: inline; }
<?php if($LeagueName == $DatabaseNotFound || $LeagueName == $NewsDatabaseNotFound || $CookieTeamNumber == 0){echo "#FormID {display : none;}";}?>
</style>
<script src="//cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>
</head><body>
<?php include "Menu.php";?>
<h1>
<?php 
echo $News['LeagueNews'] . " - ";
If ($NewsID >= 0){
	If ($ReplyNews > 0){echo $News['EditComment'];}else{echo $News['EditNews'];}
}else{
	If ($ReplyNews > 0){echo $News['CreateComment'];}else{echo $News['CreateNews'];}
}
?>
 - <a href="NewsManagement.php"><?php echo $News['ReturnLeagueNewsManagement'];?></a>
</h1>
<br />
<?php if ($InformationMessage != ""){echo "<div style=\"color:#FF0000; font-weight: bold;padding:1px 1px 1px 5px;text-align:center;\">" . $InformationMessage . "<br /><br /></div>";}?>
<div id="FormID" style="width:95%;margin:auto;">

	<form data-sample="1" action="NewsEditor.php<?php If ($lang == "fr"){echo "?Lang=fr";}?>" method="post" data-sample-short="">
		<strong><?php echo $News['NewsFrom'] . $CookieTeamName;?></strong>
		<br /><br />
		<?php 
		echo "<input type=\"hidden\" name=\"Team\" value=\"" . $CookieTeamNumber . "\">";
		echo "<strong>" . $News['NewsTitle'] . "</strong>";
		If ($ReplyNews > 0){
			/* Reply News, can't edit title but required in the input so hidden input */
			echo "<input type=\"hidden\" name=\"Title\" value=\"" . $NewsTitle . "\">";
			echo $NewsTitle . "<br />";
		}else{
			/* Regular Code that show title textbox */
			echo "<input type=\"text\" name=\"Title\" value=\"";
			If ($NewsTitle != ""){echo $NewsTitle;}
			echo "\" size=\"80\" required><br>";
		}
		?>
		<br />
		<strong><?php echo $News['News'];?></strong>
        <textarea name="editor1">
		<?php If ($NewsMessage != ""){echo $NewsMessage;} ?>
		</textarea><br />
		<input type="hidden" name="NewsID" value="<?php echo $NewsID;?>">
		<?php If ($ReplyNews > 0){echo "<input type=\"hidden\" name=\"ReplyNews\" value=\"" . $ReplyNews . "\">";}?>
		<input type="submit" class="SubmitButton" value="<?php echo $News['Save'];?>">
        <script>
            CKEDITOR.replace( 'editor1' );
        </script>
		<?php
		If ($NewsID >= 0){
			echo "<div style=\"display: inline;padding: 0px 50px 0px 50px\">";
			echo "<input class=\"SubmitButton\" type=\"submit\" name=\"Erase\" value=\"" . $News['Erase'] . "\"></div>";
		}
		?>
		</form>
	
	<br />
	<br /><strong>Note:</strong><em><?php echo  $News['TeamNotePassword2'];?></em>
</div>

<?php include "Footer.php";?>
