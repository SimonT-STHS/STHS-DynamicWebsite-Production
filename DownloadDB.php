<?php
require_once "STHSSetting.php"; 
$Hash = (string)"";
foreach (getallheaders() as $name => $value) {
	if ($name == "Hash"){$Hash = $value;}
}
if(isset($_GET['Key'])){$Hash = filter_var($_GET["Key"], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
if (file_exists($DatabaseFile) AND ($CookieTeamNumber == 102 OR $DownloadDBHash == $Hash)){
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($DatabaseFile).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($DatabaseFile));
    readfile($DatabaseFile);
    exit;
}
?>