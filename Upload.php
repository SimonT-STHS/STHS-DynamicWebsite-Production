<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
$LeagueName = (string)"";
$UploadLineAssumeName = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorUpload;

}else{try{
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name, OutputName from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If ($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100){
		$Query = "Select Number, Name from TeamProInfo Where Number = " . $CookieTeamNumber . " ORDER BY Name";
		$TeamName = $db->querySingle($Query,true);	
		$UploadLineAssumeName = str_replace(' ', '', $TeamName['Name']) . ".shl";
	}
	If ($CookieTeamNumber == 102){$DoNotRequiredLoginDynamicWebsite = TRUE;} // Commish is allow to upload anything so we are using the code from the 'Do Not Required Login Dynamic Website' to achieve this goal.
} catch (Exception $e) {
STHSErrorUpload:
	$LeagueName = $DatabaseNotFound;
	echo "<style>Div{display:none}</style>";
	$Title = $DatabaseNotFound;
}}
echo "<title>" . $LeagueName . " - " . $UploadLang['UploadLine'] . "</title>";
?>
<style>
input[type="file"] {
    display: none;
}
<?php If (($CookieTeamNumber == 0 OR $CookieTeamNumber > 100) AND $DoNotRequiredLoginDynamicWebsite == FALSE){echo "#FormName {display : none;}";}?>
</style>
</head><body>
<?php include "Menu.php";?>
<br />

<div style="width:95%;margin:auto;">
<h1><?php echo $UploadLang['UploadLine'];?></h1>
<?php If ($CookieTeamNumber == 0 AND $DoNotRequiredLoginDynamicWebsite == FALSE){echo "<div class=\"STHSDivInformationMessage\">" . $NoUserLogin . "<br /><br /></div>";}?>

<?php
if(isset($_POST["submit"]) AND isset($_FILES["fileToUpload"]) == True) {
	try {
		 // Check if Folder Exist, if not create it with empty index.html page.
		$target_dir = "linesupload/";
		if (!file_exists($target_dir)) {
			mkdir($target_dir, 0755, true);
			$handle = fopen($target_dir . "index.html", 'w');
			fwrite($handle, '<html></html>');
			fclose($handle);
		}
		
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		if($FileType != "shl") {
			// Allow certain file formats
			echo "<br /><h2>" . $UploadLang['FileFormat'] . "</h2><hr />";
		}elseif ($_FILES["fileToUpload"]["size"] > 500000 OR $_FILES["fileToUpload"]["size"] < 5000) {
			// Check file size
			echo "<br /><h2>" . $UploadLang['FileSize']. "</h2><hr />";
		} else {
			// Check if file match a team name
			If ($UploadLineAssumeName == basename($_FILES["fileToUpload"]["name"]) OR $DoNotRequiredLoginDynamicWebsite == TRUE){
				// if everything is ok, try to upload file
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo "<br /><h2>" . $UploadLang['TheFile'] . basename( $_FILES["fileToUpload"]["name"]). $UploadLang['BeenUploaded']. "</h2><hr />";
				} else {
					echo "<br /><h2>" . $UploadLang['Error']. "</h2><hr />";
				}
			}else{
				echo "<br /><h2>" . $UploadLang['WrongTeamFile']. "</h2><hr />";
			}				
		}
	} catch (Exception $e) {
		echo "<br /><h2>" . $UploadLang['Error']. "</h2><hr />";
	}		
}
?>
<br /><br />
<form id="FormName" action="Upload.php<?php If ($lang == "fr"){echo "?Lang=fr";}?>" method="post" enctype="multipart/form-data">
	<label for="fileToUpload" class="SubmitButton"><?php echo $UploadLang['Selectfile'];?></label>
    <input type="file" name="fileToUpload" id="fileToUpload" size="100" accept=".shl"><br /><br /><div id="file-upload-filename" style="font-size:18px;"></div><br />
    <input class="SubmitButton" id="submit" type="submit" value="<?php echo $UploadLang['UploadLine'];?>" name="submit">
</form>
</div>
<script>
var input = document.getElementById('fileToUpload');
var infoArea = document.getElementById('file-upload-filename');

input.addEventListener( 'change', showFileName );

function showFileName( event ) {
  
  // the change event gives us the input it occurred in 
  var input = event.srcElement;
  
  // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
  var fileName = input.files[0].name;
  
  // use fileName however fits your app best, i.e. add it into a div
  infoArea.textContent = 'File name: ' + fileName;
}
</script>
<br />
</div>

<?php include "Footer.php";?>
