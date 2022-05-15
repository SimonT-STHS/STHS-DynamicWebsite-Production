<?php
require_once "STHSSetting.php";
$TeamInput = (integer)0;
$Password = (string)"";
$HashMatch = (boolean)FALSE;
$Title = (string)"";
$InformationMessage = (string)"";
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Title = $DatabaseNotFound;
	$EntryDraft = Null;
	$EntryDraftProspectAvailable = Null;
}else{
	$LeagueName = (string)"";
		
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, LeagueWebPassword, LeagueWebGuestPassword FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
		
	/* Get Team who have the WebPassword Setup */
	$Query = "SELECT Number, Name, GMName FROM TeamProInfo WHERE WebPassword <> \"\" ORDER BY Name";
	$TeamName = $db->query($Query);	

	if (isset($_POST["Team"]) && !empty($_POST["Team"]) && isset($_POST["Password"]) && !empty($_POST["Password"])) {
		$TeamInput = filter_var($_POST["Team"], FILTER_SANITIZE_NUMBER_INT);
		$Password = filter_var($_POST["Password"], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);
		
		/* Get Hash */
		If ($TeamInput > 0){
			If ($TeamInput == 101){
				/* League Guest Hash */
				$LeagueGuestCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($LeagueName . $Password), 'ASCII')));
				$LeagueDatabaseGuestHash = $LeagueGeneral['LeagueWebGuestPassword'];
				If ($LeagueGuestCalculateHash == $LeagueDatabaseGuestHash && $LeagueDatabaseGuestHash != "" && $LeagueGeneral['LeagueWebGuestPassword'] != ""){ 
					$HashMatch = True;/* Can only match if LeagueWebGuestPassword is not empty */	
					$CookieArray = array(
						'TeamNumber'		=> 101,
						'TeamName'			=> $News['Guest'],
						'TeamGM'			=> '',			
					);						
				}
			}ElseIf ($TeamInput == 102){
				/* League Management Hash */
				$LeagueCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($LeagueName . $Password), 'ASCII')));
				$LeagueDatabaseHash = $LeagueGeneral['LeagueWebPassword'];
				If ($LeagueCalculateHash == $LeagueDatabaseHash && $LeagueDatabaseHash != "" && $LeagueGeneral['LeagueWebPassword'] != ""){
					$HashMatch = True;
					$CookieArray = array(
						'TeamNumber'		=> 102,
						'TeamName'			=> $News['LeagueManagement'],
						'TeamGM'			=> '',			
					);						
				}/* Can only match if LeagueWebPassword is not empty */
			}else{
				/* GM Hash */
				$Query = "SELECT Number, Name, GMName, WebPassword FROM TeamProInfo WHERE Number = " . $TeamInput;
				$TeamPasswordCookie = $db->querySingle($Query,true);
				
				/* Confirm GM Hash */
				$GMCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($TeamPasswordCookie['GMName'] . $Password), 'ASCII')));
				$GMDatabaseHash = $TeamPasswordCookie['WebPassword'];
				If ($GMCalculateHash == $GMDatabaseHash && $GMDatabaseHash != ""){
					$HashMatch = True;
					$CookieArray = array(
						'TeamNumber'		=> $TeamPasswordCookie['Number'],
						'TeamName'			=> $TeamPasswordCookie['Name'],
						'TeamGM'			=> $TeamPasswordCookie['GMName'],			
					);						
				}
			}
		}
		If ($HashMatch == True){
			// setcookie($Cookie_Name, $TeamInput, time() + (86400 * 30), "/");
			
			$CookieTeamNumber = $CookieArray['TeamNumber'];
			$CookieTeamName = $CookieArray['TeamName'];

			$CurrentLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			if (strpos($_SERVER['REQUEST_URI'],'?') !== false) {
				$LoginLink = $CurrentLink . "&Logoff";
			}else{
				$LoginLink = $CurrentLink . "?Logoff";
			}			
			
			$encryption_key = base64_decode($CookieTeamNumberKey);
			$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
			$encrypted = openssl_encrypt(serialize($CookieArray), 'aes-256-cbc', $encryption_key, 0, $iv);
			
			if(PHP_VERSION_ID < 70300) {
				setcookie($Cookie_Name, base64_encode($encrypted . '::' . $iv), time() + (86400 * 180), "/");
			} else {
				$CookieArray = array(
					'expires' => time() + (86400 * 180),
					'path' => '/',
					'domain' => $_SERVER['HTTP_HOST'],
					'secure' => false,
					'httponly' => true,
					'samesite' => 'Strict'
				);
				setcookie($Cookie_Name, base64_encode($encrypted . '::' . $iv),$CookieArray);
			}			
			
			
			$_COOKIE[$Cookie_Name] = $TeamInput;

			
		}else{
			$InformationMessage = $News['IncorrectPassword'];
		}
	}
}
include "Header.php";
echo "<title>" . $LeagueName . " - " . $IndexLang ['Login'] . "</title>";
?>
</head><body>
<?php include "Menu.php";?>
<div id="FormID" style="width:95%;margin:auto;">
<?php 
echo "<h1>" . $IndexLang['Login'] . "</h1>";
if ($InformationMessage != ""){echo "<div style=\"color:#FF0000; font-weight: bold;padding:1px 1px 1px 5px;text-align:center;\">" . $InformationMessage . "<br /><br /></div>";}?>

<?php
if(!isset($_COOKIE[$Cookie_Name]) AND $LeagueName != $DatabaseNotFound) {
	$page = "" . $_SERVER["REQUEST_URI"] . "";
	echo "<form data-sample=\"1\" data-sample-short=\"\" name=\"frmLogin\" method=\"POST\" action=\"Login.php";If ($lang == "fr"){echo "?Lang=fr";}echo "\">";
	echo "<table class=\"STHSPHPLogin_Table\"><tr><td>";
	echo "<strong>" . $TeamLang['GM'] ."</strong></td><td>";
	echo "<select name=\"Team\" style=\"width:500px;\">";
	if ($LeagueGeneral['LeagueWebPassword'] != ""){	
		echo "<option value=\"102\">" . $News['LeagueManagement'] . "</option>";
	}
	if ($LeagueGeneral['LeagueWebGuestPassword'] != ""){	
		echo "<option value=\"101\">" . $News['Guest'] . "</option>";
	}		
	if (empty($TeamName) == false){while ($Row = $TeamName ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>"; 
	}}
	echo "</select></td></tr><tr><td>";
	echo "<strong>" .  $News['Password'] . "</strong></td><td><input type=\"password\" name=\"Password\" size=\"20\" style=\"width:200px;\" value=\"\" required></td></tr>";
	echo "<tr><td></td><td><input class=\"SubmitButton\" type=\"submit\" value=\"" . $IndexLang['Login'] . "\">";
	echo "</td></tr></table></form>";
} else {
	echo "<form data-sample=\"1\" data-sample-short=\"\" name=\"frmLogout\" method=\"POST\" action=\"Login.php";If ($lang == "fr"){echo "?Lang=fr";}echo "\">";
	echo "<input type=\"hidden\" name=\"Logoff\" value=\"STHS\">";
	echo "<table class=\"STHSPHPLogin_Table\"><tr><td>";
	echo "<h2>" . $IndexLang['CurrentLogin'] . $CookieTeamName ."</h2></td></tr>";
	echo "<tr><td><input class=\"SubmitButton\" type=\"submit\" value=\"" . $IndexLang['Logout'] . "\"></td></tr>";
	echo "</table></form>";
}

?>



</div>

<?php include "Footer.php";?>
