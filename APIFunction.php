<?php
// Source : https://support.liveagent.com/061754-How-to-make-REST-calls-in-PHP

Function APIGet($WebsiteURL, $QueryString){
$service_url =  $WebsiteURL . "/API.php?";
$curl = curl_init($service_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_TIMEOUT,15);
$curl_response = curl_exec($curl);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
	return null;
}else{
	curl_close($curl);
	$decoded = json_decode($curl_response, true);
	return $decoded;
}
}

Function APIPost($WebsiteURL, $curl_post_data){
$service_url =  $WebsiteURL . "/API.php?";
$curl = curl_init($service_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
curl_setopt($curl, CURLOPT_TIMEOUT,15);
$curl_response = curl_exec($curl);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
	return null;
}else{
	curl_close($curl);
	$decoded = json_decode($curl_response, true);
	return $decoded;	
}
}

?>