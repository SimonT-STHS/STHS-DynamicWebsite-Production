<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Active = 1; /* Show Webpage Top Menu */
$LeagueName = (string)"";
$NewsID = -1;
$NewsTeam = (integer)0;
$NewsTitle = (string)"";
$NewsMessage = (string)"";
$InformationMessage = (string)"";
$Owner = (string)"";
$PasswordIncorrect = (boolean)FALSE;
$HashMatch = (boolean)FALSE;

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$LeagueNews = Null;
}else{
	$db = new SQLite3($DatabaseFile);
	mb_internal_encoding("UTF-8");
	$Query = "Select Name,LeagueWebPassword FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	/* Get Team who have the WebPassword Setup */
	$Query = "SELECT Number, Name, GMName FROM TeamProInfo WHERE WebPassword <> \"\" ORDER BY Name";
	$TeamName = $db->query($Query);
	
	/* Get NewsID by Get */
	if(isset($_GET['NewsID'])){
		$NewsID = filter_var($_GET['NewsID'], FILTER_SANITIZE_NUMBER_INT);
	}
	
	/* Get NewsID by Post / For New NewsID or Save Current One / Overwrite the Get */
	if (isset($_POST["NewsID"]) && !empty($_POST["NewsID"])) {
		$NewsID = filter_var($_POST["NewsID"], FILTER_SANITIZE_NUMBER_INT);
	}
	
	if ($NewsID >= 0 && isset($_POST["Erase"]) && isset($_POST["Password"]) && !empty($_POST["Password"])) {
		/* Process Delete Button */
		$Password = filter_var($_POST["Password"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);
		
		/* Check if News Exist Exist */
		$Query = "SELECT LeagueNews.TeamNumber, LeagueNews.Title, TeamProInfo.GMName, TeamProInfo.WebPassword FROM LeagueNews LEFT JOIN TeamProInfo ON LeagueNews.TeamNumber = TeamProInfo.Number WHERE LeagueNews.Number = " . $NewsID;	
		$NewsOwner = $db->querySingle($Query,true);
		
		If ($NewsOwner != Null){
			/* Delete From Database if the News exist */
			
			/* Get Hash */
			If ($NewsOwner['TeamNumber'] > 0){
				/* GM Hash */
				$GMCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($NewsOwner['GMName'] . $Password), 'ASCII')));
				$GMDatabaseHash = $NewsOwner['WebPassword'];
				If ($GMCalculateHash == $GMDatabaseHash && $GMDatabaseHash != ""){$HashMatch = True;}
			}
			
			If ($HashMatch == False){
				/* League Management Hash for League and also GM News */
				$LeagueCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($LeagueName . $Password), 'ASCII')));
				$LeagueDatabaseHash = $LeagueGeneral['LeagueWebPassword'];
				If ($LeagueCalculateHash == $LeagueDatabaseHash && $LeagueDatabaseHash != "" && $LeagueGeneral['LeagueWebPassword'] != ""){$HashMatch = True;} /* Can only match if LeagueWebPassword is not empty */
			}
			
			If ($HashMatch == True){
				/* Delete From Database if Password Match */
				$InformationMessage = $News['News'] . "\" " . $NewsOwner['Title'] . " \"" . $News['WasErase'];
				$sql = "Delete from LeagueNews WHERE LeagueNews.Number = " . $NewsID;
				$db->exec($sql);
			}else{
				/* Password Hash do not Match */
				$InformationMessage = $News['IncorrectPassword'];
			}
		}else{
			/* Didn't find the News */
			$InformationMessage = $News['ErrorErase'];
		}
	}elseif (isset($_POST["editor1"]) && !empty($_POST["editor1"]) && isset($_POST["Title"]) && !empty($_POST["Title"]) && isset($_POST["Password"]) && !empty($_POST["Password"])) {
		/* Process Submit Button */
		$Password = filter_var($_POST["Password"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);
		If ($NewsID >= 0){
			/* News Already Exist */
			
			$Query = "SELECT LeagueNews.TeamNumber, TeamProInfo.GMName, TeamProInfo.WebPassword FROM LeagueNews LEFT JOIN TeamProInfo ON LeagueNews.TeamNumber = TeamProInfo.Number WHERE LeagueNews.Number = " . $NewsID;	
			$NewsOwner = $db->querySingle($Query,true);
			
			/* Get Hash */
			If ($NewsOwner['TeamNumber'] > 0){
				/* GM Hash */
				$GMCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($NewsOwner['GMName'] . $Password), 'ASCII')));
				$GMDatabaseHash = $NewsOwner['WebPassword'];
				If ($GMCalculateHash == $GMDatabaseHash && $GMDatabaseHash != ""){$HashMatch = True;}
			}
			
			If ($HashMatch == False){
				/* League Management Hash for League and also GM News */
				$LeagueCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($LeagueName . $Password), 'ASCII')));
				$LeagueDatabaseHash = $LeagueGeneral['LeagueWebPassword'];
				If ($LeagueCalculateHash == $LeagueDatabaseHash && $LeagueDatabaseHash != "" && $LeagueGeneral['LeagueWebPassword'] != ""){$HashMatch = True;} /* Can only match if LeagueWebPassword is not empty */
			}
			
			If ($HashMatch == True){
				/* Update Existing NewsID if Password Hash Match */
				$sql = "UPDATE LeagueNews SET Title = '" . filter_var($_POST["Title"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH) . "',Message = '" . $_POST["editor1"] . "',WebClientModify = 'True' WHERE Number = " . $NewsID;
				$db->exec($sql);
				$InformationMessage = $News['SaveSuccessfully'];
			}else{
				/* Password Hash do not Match */
				$InformationMessage = $News['IncorrectPassword'];
				$PasswordIncorrect = TRUE; /* Important to Post Variable are resend to user */
			}
		}else{
			/* New News */
			
			/* Get Hash and Owner/Writer Information*/
			if(isset($_POST["Team"]) && !empty($_POST["Team"])){
				/* Get GM Name and Password Hash in Database */
				$NewsTeam = filter_var($_POST["Team"], FILTER_SANITIZE_NUMBER_INT);
				$Query = "SELECT GMName, WebPassword FROM TeamProInfo WHERE Number = '" . $NewsTeam . "'";
				$TeamGM = $db->querySingle($Query,true);
				$Owner = $TeamGM['GMName'];
				$GMCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($Owner . $Password), 'ASCII')));
				$GMDatabaseHash = $TeamGM['WebPassword'];
				If ($GMCalculateHash == $GMDatabaseHash && $GMDatabaseHash != ""){$HashMatch = True;}
			}else{
				/* Setup Information Required Later */
				$NewsTeam = 0;
				$Owner = $News['LeagueManagement'];
			}
			
			/* If League Management Wrote News OR Allow League Management Master Password to create News on Behalf of GM */
			If ($NewsTeam == 0 || $HashMatch == False){
				$LeagueCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($LeagueName . $Password), 'ASCII')));
				$LeagueDatabaseHash = $LeagueGeneral['LeagueWebPassword'];
				If ($LeagueCalculateHash == $LeagueDatabaseHash && $LeagueDatabaseHash != "" && $LeagueGeneral['LeagueWebPassword'] != ""){$HashMatch = True;} /* Can only match if LeagueWebPassword is not empty */
			}
			
			If ($HashMatch == True){
				/* Create a new record if Password Hash Match */
				$sql = "INSERT INTO LeagueNews (Time,TeamNumber,TeamNewsNumber,Owner,Title,Message,Remove,WebClientModify) VALUES('" . gmdate('Y-m-d H:i:s') . "','" . filter_var($_POST["Team"], FILTER_SANITIZE_NUMBER_INT) . "','0','" . $Owner . "','" . filter_var($_POST["Title"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH) . "','" . $_POST["editor1"] . "','False','True')";
				$db->exec($sql);
				$InformationMessage = $News['SaveSuccessfully'];
				
				/* Get the Current News from Incredement to Load the News Normally like your press the edit link */
				$Query = "Select LeagueNews.Number FROM LeagueNews ORDER BY Number DESC LIMIT 1";
				$LastLeagueNewsNumber = $db->querySingle($Query,true);
				$NewsID = $LastLeagueNewsNumber['Number'];
			}else{
				/* Password Hash do not Match */
				$InformationMessage = $News['IncorrectPassword'];
				$PasswordIncorrect = TRUE; /* Important to Post Variable are resend to user */
			}				
		}
	}
	
	If ($NewsID >= 0){
		/* Load the News Request */
		$Query = "Select LeagueNews.* FROM LeagueNews WHERE LeagueNews.Number = " . $NewsID;
		$LeagueNews = $db->querySingle($Query,true);
		
		If ($LeagueNews == Null){
			/* Reset Data if News ID doesn't exist */
			$NewsID = -1;
		}else{
			/* Get the Current News TeamNumber for the Team Select*/
			$NewsTeam = $LeagueNews['TeamNumber'];
			$NewsTitle = $LeagueNews['Title'];
			$NewsMessage = $LeagueNews['Message'];
		}
	}
		
	If($PasswordIncorrect == TRUE){
		/* If the Password was incorrect, put the data from the Post into the Title and News Input */
		$NewsTitle = filter_var($_POST["Title"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);
		$NewsMessage = $_POST["editor1"];
	}
}
echo "<title>" . $LeagueName . " - " . $News['LeagueNews'] . "</title>";

?>
<style type="text/css">
form { display: inline; }
</style>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
</head><body>
<?php include "Menu.php";?>
<h1>
<?php echo $News['LeagueNews'] . " - " ;If ($NewsID >= 0){echo $News['EditNews'];}else{echo $News['CreateNews'];}?>
</h1>
<br />
<?php if ($InformationMessage != ""){echo "<div style=\"color:#FF0000; font-weight: bold;padding:1px 1px 1px 5px;text-align:center;\">" . $InformationMessage . "<br /><br /></div>";}?>
<div style="width:95%;margin:auto;">

	<form data-sample="1" action="NewsEditor.php<?php If ($lang == "fr"){echo "?Lang=fr";}?>" method="post" data-sample-short="">
		
		<strong><?php echo $News['NewsFrom'];?></strong>
		<?php 
		/* Show Default Option Correctly and Disable it Edit News */
		echo "<select name=\"Team\" style=\"width:500px;\"";
		if($NewsID >= 0){echo " disabled";}  
		echo ">";
		if ($LeagueGeneral['LeagueWebPassword'] != ""){	
			echo "<option value=\"0\"";
			if($NewsTeam == 0){echo " selected=\"selected\"";}
			echo ">" . $News['LeagueManagement'] . "</option>";
		}
		if (empty($TeamName) == false){while ($Row = $TeamName ->fetchArray()) {
			echo "<option value=\"" . $Row['Number'] . "\"";
			if($NewsTeam == $Row['Number']){echo "selected=\"selected\"";}
			echo ">" . $Row['Name'] . " - " . $Row['GMName'] . "</option>"; 
		}}
		?>
		</select><br />
		<br />
	
		<strong><?php echo $News['NewsTitle'];?></strong><input type="text" name="Title" value="<?php If ($NewsTitle != ""){echo $NewsTitle;}?>" size="80" required><br>
		<br />
		<strong><?php echo $News['News'];?></strong>
        <textarea name="editor1">
		<?php If ($NewsMessage != ""){echo $NewsMessage;} ?>
		</textarea><br />
		<strong><?php echo $News['Password'];?></strong><input type="password" name="Password" size="20" value="" required><br /><br />
		<input type="hidden" name="NewsID" value="<?php echo $NewsID;?>">
		<input type="submit" style="padding-left:20px;padding-right:20px" value="<?php echo $News['Save'];?>">
        <script>
            CKEDITOR.replace( 'editor1' );
        </script>
		<?php
		If ($NewsID >= 0){
			echo "<div style=\"display: inline;padding: 0px 50px 0px 50px\">";
			echo "<input style=\"padding-left:20px;padding-right:20px\" type=\"submit\" name=\"Erase\" value=\"" . $News['Erase'] . "\"></div>";
		}
		?>
		</form>
	
	<br />
	<h1><a href="NewsManagement.php"><?php echo $News['ReturnLeagueNewsManagementPage'];?></a></h1>
	<br /><strong>Note:</strong><br /><em><?php echo $News['TeamNotePassword1'] . "<br />" . $News['TeamNotePassword2'];?></em>
</div>

<?php include "Footer.php";?>
