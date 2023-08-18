<?php

header('Content-Type: text/plain; charset=utf-8');
require_once("STHSSetting.php");

If (file_exists($DatabaseFile) == false){
	echo "No Database File";
}else{try{
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name from TeamProInfo ORDER BY Name ";
	$TeamName = $db->query($Query);

	$BooFound = (boolean)false;
	if (isset($_FILES['file']) == True){
		try {
			$target_dir = "linesupload/";
			if (!file_exists($target_dir)) {
				mkdir($target_dir, 0755, true);
				$handle = fopen($target_dir . "index.html", 'w');
				fwrite($handle, '<html></html>');
				fclose($handle);
			}
			$target_file = $target_dir . basename($_FILES['file']["name"]);
			$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			if($FileType != "shl") {
				// Allow certain file formats
				echo "FAIL - Unknown Format";
			}elseif ($_FILES['file']["size"] > 500000 OR $_FILES['file']["size"] < 5000) {
				// Check file size
				echo "FAIL - Size to Small or to Large";
			} else {
			// Check if file match a team name
				if (empty($TeamName) == false){while ($Row = $TeamName ->fetchArray()) {
					If (str_replace(' ', '', $Row['Name']) . ".shl" == basename($_FILES['file']["name"])){$BooFound = True;}
				}}		
				
				If ($BooFound == True){
					if (move_uploaded_file($_FILES['file']["tmp_name"], $target_file)) {
						echo "OK";
					} else {
						echo "FAIL Upload";
					}
				}else{
					echo "FAIL Wrong File Name";
				}
			}
		} catch (Exception $e) {
			echo "FAIL Exception Code";
		}	
	}else{
		echo "No Input";
	}
} catch (Exception $e) {
echo "FAIL Exception Database";
}}
?>
