<?php

header('Content-Type: text/plain; charset=utf-8');

if (isset($_FILES['file']) == True){
	try {
		$target_dir = "linesupload/";
		if (!file_exists($target_dir)) {
			mkdir($target_dir, 0772, true);
			$handle = fopen($target_dir . "index.html", 'w');
			fwrite($handle, '<html></html>');
			fclose($handle);
		}
		$target_file = $target_dir . basename($_FILES['file']["name"]);
		$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		if($FileType != "shl" and $FileType != "stc") {
			// Allow certain file formats
			echo "FAIL - Unknown Format";
		}elseif ($_FILES['file']["size"] > 500000 OR $_FILES['file']["size"] < 5000) {
			// Check file size
			echo "FAIL - Size to Small or to Large";
		} else {
			// if everything is ok, try to upload file
			if (move_uploaded_file($_FILES['file']["tmp_name"], $target_file)) {
				echo "OK";
			} else {
				echo "FAIL Upload";
			}
		}						
	} catch (Exception $e) {
		echo "FAIL Exception";
	}	
}else{
	echo "No Input";
}
?>
