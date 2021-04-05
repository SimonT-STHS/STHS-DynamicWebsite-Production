<?php
$Delete = False;
if(isset($_GET['DeleteFiles'])){$Delete = True;} 
$LoopCount = (integer)0;
$dir = 'linesupload';
if (is_dir($dir) == True){
	$files = scandir($dir);
	if (empty($files) == false){
		foreach ($files as &$value) {
			if (strtolower(substr($value, -3)) == "shl"){
				If ($Delete == True){
					unlink($dir . "/" . $value);
					$LoopCount +=1;
				}else{
					echo "@".$value;
					$LoopCount +=1;
				}
			}
		}		
	}
	If ($LoopCount == 0){
		echo "NoFileFound";
	}elseif($Delete == True){
		echo "DELETE OK";
	}
}else{
	echo "ERROR - Folder Not Found";
}
?>
