<?php 
require_once "STHSSetting.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$LeagueName = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorBlankPage;
}else{try{
	$LeagueName = (string)"";
		
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If ($CookieTeamNumber > 0 AND file_exists($NewsDatabaseFile) == true){
		$dbNews = new SQLite3($NewsDatabaseFile);
		
		if (isset($_POST["SaveCustomTheme"]) && !empty($_POST["SaveCustomTheme"]) && (file_exists($NewsDatabaseFile) == true)){
			
			$Query = "SELECT name FROM sqlite_master WHERE type='table' AND name='LeagueTheme'";
			$Result = $dbNews->querySingle($Query);

			if ($Result <> Null) {
				/* Database exist */
			}else{
				/* Database doesn't exist. Create it using a Sequence Starting at 2000 */
				
				$Query = "CREATE TABLE IF NOT EXISTS LeagueTheme (Number INTEGER PRIMARY KEY AUTOINCREMENT,TeamOwner integer,ThemeName string,CssCode string)";
				$LeagueNewsCreate = $dbNews->query($Query);	
				$Query = "INSERT INTO sqlite_sequence (name, seq) VALUES ('LeagueTheme', 2000);";
				$LeagueNewsCreate = $dbNews->exec($Query);
			}		
			if (isset($_POST["cssOutput"]) && !empty($_POST["cssOutput"]) && isset($_POST["ThemeName"]) && !empty($_POST["ThemeName"])){	
			
				$cssOutput = (string)"";
				$ThemeName = (string)"";
				$EditTheme = (int)0;
				
				if(isset($_POST['cssOutput'])){$cssOutput = filter_var($_POST['cssOutput'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}
				if(isset($_POST['ThemeName'])){$ThemeName = filter_var($_POST['ThemeName'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}
				if(isset($_POST['EditCustomThemeSubmit']) && isset($_POST["EditCustomTheme"]) && !empty($_POST["EditCustomTheme"])){$EditTheme = filter_var($_POST['EditCustomTheme'], FILTER_SANITIZE_NUMBER_INT);}
				
				if (empty($cssOutput) == false){
					If ($EditTheme > 0){
						/* Update Current Theme */
						$sql = "UPDATE LeagueTheme SET ThemeName = '" . $ThemeName . "' ,CssCode = '" . $cssOutput . "' WHERE Number = " . $EditTheme;
						$dbNews->exec($sql);
						$InformationMessage = $ThemeEditorLang['CustomThemeName'] . "\"" . $ThemeName . "\"" . $ThemeEditorLang['SaveSuccessfully'];	
						
					}else{
						/* New Theme */
						$Query = "INSERT INTO LeagueTheme (TeamOwner,ThemeName,CssCode) VALUES('" . $CookieTeamNumber . "','" . $ThemeName . "','" . $cssOutput . "')";
						$dbNews->exec($Query);
						
						$InformationMessage = $ThemeEditorLang['CustomThemeName'] . "\"" . $ThemeName . "\"" . $ThemeEditorLang['SaveSuccessfully'];					

						$Query = "SELECT seq FROM sqlite_sequence WHERE name='LeagueTheme'";
						$SequenceResult = $dbNews->querySingle($Query);
		
						If ($SequenceResult > 0){
							/* Apply New Theme*/ 
							
							$CookieTeamWebsiteThemeID = $SequenceResult;
							$CookieArray['TeamNumber'] = $CookieTeamNumber;
							$CookieArray['TeamName'] = $CookieTeamName;
							$CookieArray['TeamGM'] = $CookieTeamGM;
							$CookieArray['TeamWebsiteLang'] = $CookieTeamWebsiteLang;
							$CookieArray['TeamWebsiteThemeID'] = $CookieTeamWebsiteThemeID;		
							$encryption_key = base64_decode($CookieTeamNumberKey);
							$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
							$encrypted = openssl_encrypt(serialize($CookieArray), 'aes-256-cbc', $encryption_key, 0, $iv);
							$CookieArrayDetail = array(
								'expires' => time() + (86400 * 180),
								'path' => '/',
								'domain' => $_SERVER['HTTP_HOST'],
								'secure' => isset($_SERVER['HTTPS']),
								'httponly' => true,
								'samesite' => 'Strict'
							);
							setcookie($Cookie_Name, base64_encode($encrypted . '::' . $iv),$CookieArrayDetail);	
							
						}	
					}
				}
			}
		}
		
		if(isset($_GET['DeleteTheme'])){
			$DeleteTheme = (int)0;
			$DeleteTheme = filter_var($_GET['DeleteTheme'], FILTER_SANITIZE_NUMBER_INT);
			If ($DeleteTheme > 0){		
				$HashMatch = (bool)FALSE; /* Cookie Match User Select */

				/* Check if News Exist Exist */
				$Query = "Select * FROM LeagueTheme WHERE Number = " . $DeleteTheme;	
				$ThemeSelection = $dbNews->querySingle($Query,true);
				
				If ($ThemeSelection != Null){
					/* Delete From Database if theTheme Exit exist */
					
					/* Get Confirm User */
					If ($ThemeSelection['TeamOwner'] > 0){
						If ($CookieTeamNumber == $ThemeSelection['TeamOwner']){$HashMatch = True;}
					}
					
					If ($HashMatch == False){
						/* League Management User for League and also GM News */
						If ($CookieTeamNumber == 102){$HashMatch = True;}
					}
					
					If ($HashMatch == True){
						/* Delete From Database */
						$InformationMessage = $ThemeEditorLang['CustomThemeName'] . "\"" . $ThemeSelection['Name'] . "\"" . $ThemeEditorLang['WasErase'];
												
						$sql = "DELETE from LeagueTheme WHERE LeagueTheme.Number = " . $DeleteTheme;
						$dbNews->exec($sql); 
					}else{;
						/* Hash do not Match */
						$InformationMessage = $ThemeEditorLang['IllegalAction'];
					}						
				}
			}			
		}
		
		If ($CookieTeamNumber == 102){
			$Query = "Select * FROM LeagueTheme ORDER BY Number";
			$LeagueTheme = $dbNews->query($Query);
		}elseif($CookieTeamNumber > 0){
			$Query = "Select * FROM LeagueTheme Where TeamOwner = " . $CookieTeamNumber ." ORDER BY Number";
			$LeagueTheme = $dbNews->query($Query);
		}else{
			$LeagueTheme = Null;
		}
	}
	
} catch (Exception $e) {
STHSErrorBlankPage:
	$LeagueName = $DatabaseNotFound;
	echo "<style>.STHSBlankPage_MainDiv{display:none}</style>";
}}
include "Header.php";
echo "<title>" . $LeagueName . " - " . $ThemeEditorLang['ThemeEditor'] . "</title>";
?>
<style>
  .page{
    max-width:1200px;
    margin:18px auto;
    padding:18px;
    display:grid;
    grid-template-columns:minmax(0,1fr) 600px;
    gap:18px;
  }
  @media(max-width:980px){ .page{ grid-template-columns:1fr } }

  .preview{
    background-color:var(--STHS-body-background);
	color:var(--STHS-body-color);
    border-radius:10px;
    padding:18px;
    box-shadow:0 6px 18px rgba(0,0,0,.06);
    border:1px solid #e6e6e6;
  }
  .preview td{ padding:18px; text-align:center; font-size:18px; border-bottom:1px solid rgba(0,0,0,.04) }

  .editor{
    background-color:var(--STHS-body-background);
	color:var(--STHS-body-color);
    border-radius:10px;
    padding:14px;
    box-shadow:0 6px 18px rgba(0,0,0,.06);
    border:1px solid #e6e6e6;
    font-size:14px;
  }
  .editor__header{ display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
  .editor__title{ font-weight:bold;font-size:16px; }
  .editor__grid{
    display:grid;
    grid-template-columns: 1fr 120px 160px;
    gap:5px 6px;
    align-items:center;
    overflow:auto;
    padding-right:6px;
  }
  @media(max-width:640px){ .editor__grid{ grid-template-columns:1fr 96px } }

  .editor__label{ font-size:13px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
  .editor input[type="color"]{ width:64px; height:36px; border:none; padding:0; border-radius:8px; cursor:pointer; }
  .editor input[type="text"]{ width:100%; padding:10px 12px; border:1px solid #d0d0d0; border-radius:8px; font-size:14px; box-sizing:border-box; }

  .editor__controls{ margin-top:12px; display:flex; gap:8px; flex-wrap:wrap; }
  .modifiedtheme{ font-size:14px; margin-top:8px; }
  
  .editor__text{width:100%;box-sizing:border-box;margin-top:8px;border:1px solid #ddd;padding:8px;border-radius:6px;}
  
  .top {
	grid-column: 1 / -1;
	min-height: 20px;
	font-weight:bold;font-size: 1.2em;
	padding-bottom: 9px
   }
   
  .bottom {
	grid-column: 1 / -1;
	min-height: 200px;
   }   
  
  table {margin: 0 auto;}
  table > caption{font-size:25px;padding-bottom: 25px;}
</style>
</head><body>
<?php include "Menu.php";?>
<div class="STHSBlankPage_MainDiv" style="width:99%;margin:auto;">
<h1><?php echo $ThemeEditorLang['ThemeEditor'];?></h1>
<?php 
if ($InformationMessage != ""){echo "<div class=\"STHSDivInformationMessage\">" . $InformationMessage . "<br><br></div>";}

If ($CookieTeamNumber > 0 AND file_exists($NewsDatabaseFile) == true){
	echo "<div class=\"page\">";
	echo "<section class=\"top\">" . $ThemeEditorLang ['EditionNote']. "</section>\n";
	echo "<section class=\"preview\" aria-label=\"Preview\">";
	echo "<table>\n";
	echo "<caption>" . $ThemeEditorLang['PreviewTheme'] . "</caption>";
	echo "<tr><td style=\"background-color: var(--main-menu-background-color); color:var(--main-menu-text-color);font-size: 20px;  text-align: center;padding:25px;\">Main Menu Text</td></tr>\n";
	echo "<tr><td style=\"background-color: var(--main-menu-background-color-hover); color:var(--main-menu-text-color-hover);font-size: 20px;  text-align: center;padding:25px;\">Main Menu Text Hover</td></tr>\n";
	echo "<tr><td style=\"background-color: var(--main-submenu-background-color); color:var(--main-submenu-text-color);font-size: 20px;  text-align: center;padding:25px;\">SubMenu Text </td></tr>\n";
	echo "<tr><td style=\"background-color: var(--main-submenu-background-color-hover); color:var(--main-submenu-text-color-hover);font-size: 20px;  text-align: center;padding:25px;\">SubMenu Text Hover</td></tr>\n";
	echo "<tr><td style=\"background-color: var(--main-table-background-head); color:var(--main-table-background-head-text);font-size: 20px;  text-align: center;padding:25px;\">Table Header</td></tr>\n";
	echo "<tr><td style=\"background-color: var(--main-table-odd); color:var(--main-table-text-color);font-size: 20px;  text-align: center;padding:25px;\">Table Odd</td></tr>\n";
	echo "<tr><td style=\"background-color: var(--main-table-even); color:var(--main-table-text-color);font-size: 20px;  text-align: center;padding:25px;\">Table Even</td></tr>\n";
	echo "<tr><td style=\"background-color: var(--main-table-background-color-hover); color:var(--main-table-text-color-hover);font-size: 20px;  text-align: center;padding:25px;\">Table Hover</td></tr>\n";
	echo "<tr><td style=\"background-color: var(--main-filter-background-color); color:var(--main-filter-text-color);font-size: 20px;  text-align: center;padding:25px;\">Table Filter Row</td></tr>\n";
	echo "<tr><td style=\"background-color: var(--main-sort-background-color); color:var(--main-sort-text-color);font-size: 20px;  text-align: center;padding:25px;\">Table Sorting Activate Header</td></tr>\n";
	echo "<tr><td style=\"background-color: var(--main-button-background); color:var(--main-button-text);-moz-border-radius: 5px;  -webkit-border-radius: 5px;  border-radius: 6px; font-family: 'Oswald';  font-size: 20px;  text-decoration: none;  border: none; border: var(--main-button-border) 1px solid; border-radius: 5px; padding:25px;text-align: center;\">Button</td></tr>\n";
	echo "<tr><td style=\"background-color: var(--main-button-hover); color:var(--main-button-text);-moz-border-radius: 5px;  -webkit-border-radius: 5px;  border-radius: 6px; font-family: 'Oswald';  font-size: 20px;  text-decoration: none;  border: none; border: var(--main-button-border) 1px solid; border-radius: 5px; padding:25px;text-align: center;\">Button Select</td></tr>\n";
	echo "<tr><td style=\"background-color: var(--STHS-Index-Table-background); color:var(--STHS-Index-Table-color);-moz-border-radius: 5px;  -webkit-border-radius: 5px;  border-radius: 6px; font-family: 'Oswald';  font-size: 20px;  text-decoration: none;  border: none; border: var(--STHS-Index-Table-border) 1px solid; border-radius: 5px; padding:25px;text-align: center;\">Index Table</td></tr>\n";
	echo "<tr><td style=\"color:var(--STHS-HyperLink);font-size: 20px;  text-align: center;padding:25px;\">HyperLink</td></tr>\n";
	echo "</table>\n";
	echo "</section>\n";
	
	
	echo "<aside class=\"editor\" aria-label=\"Theme editor\">";
	echo "<div class=\"editor__header\">";
	echo "<div><div class=\"editor__title\">" . $ThemeEditorLang['VariablesEditor'] . "</div></div>";
	echo "</div>\n";

	echo "<div class=\"editor__grid\" id=\"editorGrid\"></div>\n";

	echo "<div class=\"editor__controls\">";
	echo "<button id=\"resetBtn\" class=\"SubmitButton\">" . $ThemeEditorLang['Reset'] . "</button>";
	echo "<button id=\"importBtn\" class=\"SubmitButton\">" . $ThemeEditorLang['Import']. "</button>";
	echo "</div>\n";
	  
	echo "<div class=\"modifiedtheme\">". $ThemeEditorLang['ModifiedThemeCode:'] . "</div>\n";
	echo "<form data-sample=\"1\" action=\"ThemeEditor.php";If ($lang == "fr"){echo "?Lang=fr";}; echo "\" method=\"post\" data-sample-short=\"\">\n";
	echo "<textarea id=\"cssOutput\" name=\"cssOutput\" rows=\"28\" class=\"editor__text\"></textarea><br><br>\n";

	echo "<div class=\"editor__title\">" . $ThemeEditorLang['CustomThemeName'] . "<input type=\"text\" name=\"ThemeName\" ";
	If ($CookieTeamWebsiteThemeID > 2000){
		$Query = "Select * FROM LeagueTheme WHERE Number = " . $CookieTeamWebsiteThemeID;	
		$ThemeSelection = $dbNews->querySingle($Query,true);
		echo "value=\"" . $ThemeSelection['ThemeName'] . "\" ";
		echo "size=\"25\" style=\"width:50%;padding:5px 5px 5px 10px;margin-left:15px;\" required></div>\n";
		echo "<input type=\"hidden\" name=\"EditCustomTheme\" value=\"" . $CookieTeamWebsiteThemeID . "\">\n";
		echo "<input type=\"submit\" name=\"EditCustomThemeSubmit\" class=\"SubmitButton\" value=\"" . $ThemeEditorLang['EditTheme'] . "\">\n";
	}else{
		echo "value=\"\" ";
		echo "size=\"25\" style=\"width:50%;padding:0px;margin-left:5px;\" required></div>\n";
	}	
	echo "<input type=\"hidden\" name=\"SaveCustomTheme\" value=\"SaveCustomTheme\">\n";
	echo "<input type=\"submit\" name=\"SaveCustomThemeSubmit\" class=\"SubmitButton\" style=\"margin-top:10px\" value=\"" . $ThemeEditorLang['SaveCustomTheme'] . "\"></form>\n";		
	
	echo "</aside>\n";
	if (empty($LeagueTheme) == false){ // Allow Deletion of Theme Owned by GM
		$firstRowProcess = false;
		while ($row = $LeagueTheme ->fetchArray()) {
			If ($firstRowProcess == false){
				$firstRowProcess = true;
				echo "<section class=\"bottom\"><br ><hr><h1>" . $ThemeEditorLang['ThemeOwner'] . "</h1>\n";
				echo "<table class=\"tablesorter STHSThemeEditor_MainTable\">\n";
				echo "<thead><tr>\n";
				echo "<th class=\"STHSThemeEditor_Team\">" .$ThemeEditorLang['ThemeName'] . "</th>\n";
				echo "<th class=\"STHSThemeEditor_Action\">" . $ThemeEditorLang['ThemeAction'] . "</th>\n";
				echo "</tr></thead><tbody>\n";	
			}
			echo "<tr><td>" . $row['ThemeName'] . "</td><td><a class=\"SubmitButton\" href=\"ThemeEditor.php?DeleteTheme=" . $row['Number'] . "\">" . $ThemeEditorLang['EraseCustomTheme'] . "</a></td></tr>\n";
		}
		If ($firstRowProcess == true){echo "</tbody></table></section>";}
	}	
	echo "</div>";


}elseif($CookieTeamNumber > 0 AND file_exists($NewsDatabaseFile) == false){
	echo "<div class=\"STHSDivInformationMessage\">" . $NewsDatabaseNotFound . "<br><br></div>";	
}else{
	echo "<div class=\"STHSDivInformationMessage\">" . $NoUserLogin . "<br><br></div>";	
}
?>


<script>
(function(){
  const root = document.documentElement;
  const grid = document.getElementById('editorGrid');
  const resetBtn = document.getElementById('resetBtn');
  const importBtn = document.getElementById('importBtn'); 
  const cssOutputEl = document.getElementById('cssOutput');

  const HARD_CODED_ORDER = [
    '--STHS-body-background',
    '--STHS-body-color',
    '--main-menu-background-color',
    '--main-menu-text-color',
    '--main-menu-background-color-hover',
    '--main-menu-text-color-hover',
    '--main-submenu-background-color',
    '--main-submenu-text-color',
    '--main-submenu-background-color-hover',
    '--main-submenu-text-color-hover',
    '--main-table-background-head',
    '--main-table-background-head-text',
    '--main-table-odd',
    '--main-table-even',
    '--main-table-text-color',
    '--main-table-background-color-hover',
    '--main-table-text-color-hover',
    '--main-filter-background-color',
    '--main-filter-text-color',
    '--main-sort-background-color',
    '--main-sort-text-color',
    '--main-button-background',
    '--main-button-hover',
    '--main-button-text',
    '--main-button-border',
    '--STHS-Index-Table-background',
    '--STHS-Index-Table-color',
    '--STHS-Index-Table-border',  	
    '--STHS-HyperLink'
  ];

  // capture initial values for reset
  const initialValues = {};
  (function captureInitial(){
    const cs = getComputedStyle(root);
    HARD_CODED_ORDER.forEach(name => {
      initialValues[name] = cs.getPropertyValue(name).trim() || '';
    });
  })();

  // normalize rgb(...) to hex for color inputs when possible
  function normalizeHex(value){
    if(!value) return '';
    value = value.trim();
    if(value.startsWith('rgb')){
      const nums = value.replace(/rgba?|\(|\)|\s/g,'').split(',').map(n=>parseFloat(n));
      const r = Math.round(nums[0]||0), g = Math.round(nums[1]||0), b = Math.round(nums[2]||0);
      return '#' + [r,g,b].map(x => x.toString(16).padStart(2,'0')).join('');
    }
    return value;
  }

  // Build the editor UI
  function buildEditor(){
    grid.innerHTML = '';
    HARD_CODED_ORDER.forEach(name => {
      const label = document.createElement('div');
      label.className = 'editor__label';
      label.textContent = name;
      label.title = name;

      const colorInput = document.createElement('input');
      colorInput.type = 'color';
      colorInput.setAttribute('aria-label', name + ' color');

      const textInput = document.createElement('input');
      textInput.type = 'text';
      textInput.placeholder = '#rrggbb or rgb(...)';
      textInput.setAttribute('aria-label', name + ' value');

      // set initial/current values
      const computed = getComputedStyle(root).getPropertyValue(name).trim();
      const cur = normalizeHex(computed || initialValues[name]);
      if(cur){
        try{ colorInput.value = cur; } catch(e){ colorInput.value = '#000000'; }
        textInput.value = cur;
      } else {
        colorInput.value = '#000000';
        textInput.value = '';
      }

      // apply value to :root
      function applyValue(val){
        if(!val) root.style.removeProperty(name);
        else root.style.setProperty(name, val);
      }

      // color input change
      colorInput.addEventListener('input', e=>{
        const v = e.target.value;
        textInput.value = v;
        applyValue(v);
        updateCssOutput(); // update textarea on every change
      });

      // text input change
      textInput.addEventListener('change', e=>{
        let v = e.target.value.trim();
        if(v && !v.startsWith('#') && /^([0-9a-f]{3}|[0-9a-f]{6})$/i.test(v)) v = '#'+v;
        textInput.value = v;
        if(/^#([0-9a-f]{6}|[0-9a-f]{3})$/i.test(v)){
          try{ colorInput.value = v; } catch(err){}
        }
        applyValue(v);
        updateCssOutput(); // update textarea on every change
      });

      // store data-name on inputs for later lookup (useful for import)
      colorInput.dataset.name = name;
      textInput.dataset.name = name;

      grid.appendChild(label);
      grid.appendChild(colorInput);
      grid.appendChild(textInput);
    });

    // update textarea after building UI so it reflects current values
    updateCssOutput();
  }

  // Update the textarea with current :root variables
  function updateCssOutput(){
    if (!cssOutputEl) return;
    let css = ':root {\n';
    HARD_CODED_ORDER.forEach(name => {
      const val = getComputedStyle(root).getPropertyValue(name).trim();
      css += `  ${name}: ${val || ''};\n`;
    });
    css += '}';
    cssOutputEl.value = css;
  }

  // Update editor inputs to reflect current computed :root values
  function refreshEditorInputs(){
    const rows = Array.from(grid.querySelectorAll('input'));
    rows.forEach(input => {
      const name = input.dataset.name;
      if(!name) return;
      const computed = getComputedStyle(root).getPropertyValue(name).trim();
      const cur = normalizeHex(computed);
      if(input.type === 'color'){
        if(cur){
          try{ input.value = cur; } catch(e){}
        } else {
          try{ input.value = '#000000'; } catch(e){}
        }
      } else if(input.type === 'text'){
        input.value = cur || '';
      }
    });
  }

  // Parse CSS text from textarea and apply variables to :root
  function importFromTextarea(){
    if(!cssOutputEl) return;
    const text = cssOutputEl.value || '';
    // Extract declarations inside braces or whole text: match --var: value;
    const regex = /(--[A-Za-z0-9\-_]+)\s*:\s*([^;]+);/g;
    let m;
    let applied = 0;
    while((m = regex.exec(text)) !== null){
      const name = m[1].trim();
      const value = m[2].trim();
      // Only apply if the variable is in our hard-coded list (developer-controlled)
      if(HARD_CODED_ORDER.includes(name)){
        root.style.setProperty(name, value);
        applied++;
      }
    }
    // After applying, refresh editor inputs and textarea
    refreshEditorInputs();
    updateCssOutput();
    return applied;
  }

  // Reset to initial values captured on load
  resetBtn.addEventListener('click', ()=>{
    HARD_CODED_ORDER.forEach(name => {
      const val = initialValues[name] || '';
      if(val) root.style.setProperty(name, val);
      else root.style.removeProperty(name);
    });
    buildEditor();
    updateCssOutput();
  });

  // Import button: read textarea and apply variables
  if(importBtn){
    importBtn.addEventListener('click', ()=>{
      const applied = importFromTextarea();
      // small visual feedback on the button
      const old = importBtn.textContent;
      importBtn.textContent = applied ? `Imported (${applied})` : 'Imported (0)';
      setTimeout(()=> importBtn.textContent = old, 1200);
    });
  }

  // Initial render
<?php If ($CookieTeamNumber > 0 AND file_exists($NewsDatabaseFile) == true){echo "buildEditor();";}?>

  // Expose a small API for dev console if you want to change order at runtime
  window.__hardcodedThemeEditor = {
    setOrder(arr){
      if(!Array.isArray(arr)) throw new Error('Provide an array of variable names');
      HARD_CODED_ORDER.length = 0;
      arr.forEach(v => HARD_CODED_ORDER.push(v));
      // recapture initial values for new list
      const cs = getComputedStyle(root);
      arr.forEach(name => initialValues[name] = cs.getPropertyValue(name).trim() || '');
      buildEditor();
      updateCssOutput();
    },
    rebuild(){ buildEditor(); updateCssOutput(); },
    importFromTextarea // expose for console use
  };
})();

</script>
</div>
<?php include "Footer.php";?>
