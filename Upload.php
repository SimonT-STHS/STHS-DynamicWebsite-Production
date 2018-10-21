<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$LeagueName = (string)"";
$Active = 1; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	echo "<style>Div{display:none}</style>";
	$Title = $DatabaseNotFound;
}else{
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name, OutputName from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
}
echo "<title>" . $LeagueName . " - " . $UploadLang['UploadLine'] . "</title>";
?>
</head><body>
<?php include "Menu.php";?>
<br />

<div style="width:95%;margin:auto;">
<h1><?php echo $UploadLang['UploadLine'];?></h1>

<?php
 
if(isset($_POST["submit"])) {
	
	 // Check if Folder Exist, if not create it with empty index.html page.
	$target_dir = "linesupload/";
	if (!file_exists($target_dir)) {
		mkdir($target_dir, 0772, true);
		$handle = fopen($target_dir . "index.html", 'w');
		fwrite($handle, '<html></html>');
		fclose($handle);
	}
	
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	if($FileType != "shl" and $FileType != "stc") {
		// Allow certain file formats
		echo "<br /><h2>" . $UploadLang['FileFormat'] . "</h2><hr />";
	}elseif ($_FILES["fileToUpload"]["size"] > 500000) {
		// Check file size
		echo "<br /><h2>" . $UploadLang['FileSize']. "</h2><hr />";
	} else {
		// if everything is ok, try to upload file
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo "<br /><h2>" . $UploadLang['TheFile'] . basename( $_FILES["fileToUpload"]["name"]). $UploadLang['BeenUploaded']. "</h2><hr />";
		} else {
			echo "<br /><h2>" . $UploadLang['Error']. "</h2><hr />";
		}
	}	
}

?>
<br /><br />
<form action="Upload.php<?php If ($lang == "fr"){echo "?Lang=fr";}?>" method="post" enctype="multipart/form-data">
    <h2><?php echo $UploadLang['Selectfile'];?></h2>
    <input type="file" class="btn" name="fileToUpload" id="fileToUpload" size="100" accept=".stc"><br /><br /><br />
    <input class="SubmitButton" id="submit" type="submit" value="<?php echo $UploadLang['UploadLine'];?>" name="submit">
</form>
</div>

<br />
</div>

<?php include "Footer.php";?>
