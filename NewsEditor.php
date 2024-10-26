<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
$LeagueName = (string)"";
$NewsID = -1;
$ReplyNews = (integer)0;
$NewsTeam = (integer)0;
$NewsTitle = (string)"";
$NewsMessage = (string)"";
$InformationMessage = (string)"";
$Owner = (string)"";
$IncorrectLoginCookie = (boolean)FALSE;
$HashMatch = (boolean)FALSE; /* Cookie Match User Select */

If (file_exists($DatabaseFile) == false){
	Goto STHSErrorNewsEditor;
}elseIf (file_exists($NewsDatabaseFile) == false){
	Goto STHSErrorNewsEditor;
}else{try{
	$db = new SQLite3($DatabaseFile);
	$dbNews = new SQLite3($NewsDatabaseFile);
	mb_internal_encoding("UTF-8");
	$Query = "Select Name FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If ($CookieTeamNumber == 0){$InformationMessage = $NoUserLogin;}
	
	/* Get NewsID by Get */
	if(isset($_GET['NewsID'])){
		$NewsID = filter_var($_GET['NewsID'], FILTER_SANITIZE_NUMBER_INT);
	}
	
	/* Get NewsID by Post / For New NewsID or Save Current One / Overwrite the Get */
	if (isset($_POST["NewsID"]) && !empty($_POST["NewsID"])) {
		$NewsID = filter_var($_POST["NewsID"], FILTER_SANITIZE_NUMBER_INT);
	}
	
	/* Get NewsID Reply by Get for New Reply */
	if(isset($_GET['ReplyNews'])){
		$ReplyNews = filter_var($_GET['ReplyNews'], FILTER_SANITIZE_NUMBER_INT);
	}	
	
	/* Get NewsID Reply by Post for Reply Saved */
	if (isset($_POST["ReplyNews"]) && !empty($_POST["ReplyNews"])) {
		$ReplyNews = filter_var($_POST["ReplyNews"], FILTER_SANITIZE_NUMBER_INT);
	}	
				
	if ($NewsID >= 0 && isset($_POST["Erase"]) && $CookieTeamNumber > 0) {
		/* Process Delete Button */

		/* Check if News Exist Exist */
		$Query = "SELECT TeamNumber, Title FROM LeagueNews WHERE Number = " . $NewsID;	
		$NewsOwner = $dbNews->querySingle($Query,true);
				
		If ($NewsOwner != Null){
			/* Delete From Database if the News exist */
			
			/* Get Confirm User */
			If ($NewsOwner['TeamNumber'] > 0){
				If ($CookieTeamNumber == $NewsOwner['TeamNumber']){$HashMatch = True;}
			}
			
			If ($HashMatch == False){
				/* League Management User for League and also GM News */
				If ($CookieTeamNumber == 102){$HashMatch = True;}
			}
			
			If ($HashMatch == True){
				/* Delete From Database */
				$InformationMessage = $NewsLang['News'] . "\"" . $NewsOwner['Title'] . "\"" . $NewsLang['WasErase'];
				
				$sql = "DELETE from LeagueNews WHERE LeagueNews.AnswerNumber = " . $NewsID;
				$dbNews->exec($sql);
				
				$sql = "DELETE from LeagueNews WHERE LeagueNews.Number = " . $NewsID;
				$dbNews->exec($sql);
			}else{
				/* Hash do not Match */
				$InformationMessage = $NewsLang['IllegalAction'];
			}
		}else{
			/* Didn't find the News */
			$InformationMessage = $NewsLang['ErrorErase'];
		}
	}elseif (isset($_POST["editor1"]) && !empty($_POST["editor1"]) && isset($_POST["Title"]) && !empty($_POST["Title"]) && $CookieTeamNumber > 0) {
		/* Process Submit Button */

		If ($NewsID >= 0){
			/* News Already Exist */
			
			$Query = "SELECT TeamNumber FROM LeagueNews WHERE Number = " . $NewsID;	
			$NewsOwner = $dbNews->querySingle($Query,true);
			If ($NewsOwner != Null){
			
				/* Get Confirm User */
				If ($NewsOwner['TeamNumber'] > 0){
					If ($CookieTeamNumber == $NewsOwner['TeamNumber']){$HashMatch = True;}
				}
				
				If ($HashMatch == False){
					/* League Management User for League and also GM News */
					If ($CookieTeamNumber == 102){$HashMatch = True;}
				}
				
				If ($HashMatch == True){
					/* Update Existing NewsID */
					$sql = "UPDATE LeagueNews SET Title = '" . filter_var($_POST["Title"], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK) . "',Message = '" . str_replace("'","''",$_POST["editor1"]) . "',WebClientModify = 'True' WHERE Number = " . $NewsID;
					$dbNews->exec($sql);
					$InformationMessage = $NewsLang['SaveSuccessfully'];
				}else{
					/* Hash do not Match */
					$InformationMessage = $NewsLang['IllegalAction'];
					$IncorrectLoginCookie = TRUE; /* Important to Post Variable are resend to user */
				}
			}else{
				/* Didn't find the News */
				$InformationMessage = $NewsLang['ErrorErase'];
			}				
		}else{
			/* New News */
			/* Get Hash and Owner/Writer Information*/
			if(isset($_POST["Team"]) && !empty($_POST["Team"])){
				$NewsTeam = filter_var($_POST["Team"], FILTER_SANITIZE_NUMBER_INT);
				If ($CookieTeamNumber == $NewsTeam){
					$HashMatch = True;
					If ($NewsTeam == 101){
						$Owner = $NewsLang['Guest'];
					}elseIf ($NewsTeam == 102){
						$Owner = $NewsLang['LeagueManagement'];				
					}else{
						/* Get GM Name in Database */
						$Query = "SELECT GMName FROM TeamProInfo WHERE Number = '" . $NewsTeam . "'";
						$TeamGM = $db->querySingle($Query,true);
						$Owner = $TeamGM['GMName'];
					}
				}
			}else{
				/* Setup Information Required Later */
				$NewsTeam = 0;
			}
			
			/* League Management User for League and also GM News */
			If ($NewsTeam == 0 || $HashMatch == False){
				If ($CookieTeamNumber == 102){
					$HashMatch = True;
					$Owner = $NewsLang['LeagueManagement'];
				}
			}
			
			If ($HashMatch == True){
				/* Create a new record  */
				$Query = "INSERT INTO LeagueNews (Time,TeamNumber,TeamNewsNumber,Owner,Title,Message,Remove,WebClientModify,AnswerNumber) VALUES('" . gmdate('Y-m-d H:i:s') . "','" . $NewsTeam . "','0','" . filter_var($Owner, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK) . "','" . filter_var($_POST["Title"], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK) . "','" . str_replace("'","''",$_POST["editor1"]) . "','False','True'," . $ReplyNews . ")";
				$dbNews->exec($Query);
				$InformationMessage = $NewsLang['SaveSuccessfully'];
				
				/* Get the Current News from Incredement to Load the News Normally like your press the edit link */
				$Query = "Select LeagueNews.Number FROM LeagueNews ORDER BY Number DESC LIMIT 1";
				$LastLeagueNewsNumber = $dbNews->querySingle($Query,true);
				$NewsID = $LastLeagueNewsNumber['Number'];
			}else{
				/* Hash do not Match */
				$InformationMessage = $NewsLang['IllegalAction'];
				$IncorrectLoginCookie = TRUE; /* Important to Post Variable are resend to user */
			}				
		}
	}
	
	If ($NewsID >= 0){
		/* Load the News Request */
		$Query = "Select LeagueNews.* FROM LeagueNews WHERE LeagueNews.Number = " . $NewsID;
		$LeagueNews = $dbNews->querySingle($Query,true);
		
		If ($LeagueNews == Null){
			/* Reset Data if News ID doesn't exist */
			$NewsID = -1;
		}else{
			/* Get the Current News TeamNumber for the Team Select*/
			$NewsTeam = $LeagueNews['TeamNumber'];
			$NewsTitle = $LeagueNews['Title'];
			$NewsMessage = $LeagueNews['Message'];
			If($LeagueNews['AnswerNumber'] > 0){$ReplyNews = $LeagueNews['AnswerNumber'];}
		}
	}elseIf ($ReplyNews > 0){
		/* Following Section Should Only Apply when Creating new Reply - If $NewsID is fill, it had priority to the ReplyNews Variable*/
				
		/* Load the News Request */
		$Query = "Select LeagueNews.* FROM LeagueNews WHERE LeagueNews.Number = " . $ReplyNews;
		$LeagueNews = $dbNews->querySingle($Query,true);
		
		If ($LeagueNews == Null){
			/* Reset Data if News ID doesn't exist */
			$ReplyNews = 0;
		}else{
			$NewsTitle = $LeagueNews['Title'];
		}	
	}
		
	If($IncorrectLoginCookie == TRUE){
		/* If the Hash was incorrect, put the data from the Post into the Title and News Input */
		$NewsTitle = filter_var($_POST["Title"], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);
		$NewsMessage = $_POST["editor1"];
	}
} catch (Exception $e) {
STHSErrorNewsEditor:	
	$LeagueName = $NewsDatabaseNotFound;
	$LeagueNews = Null;
	$LeagueGeneral = Null;
	$InformationMessage = $NewsDatabaseNotFound;	
	echo "<title>" . $DatabaseNotFound . "</title>";
	echo "<style>.STHSNewsEditor_MainDiv {display:none;}</style>";	
}}
echo "<title>" . $LeagueName . " - " . $NewsLang['LeagueNews'] . "</title>";

?>
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<style>

<?php if($LeagueName == $DatabaseNotFound || $LeagueName == $NewsDatabaseNotFound || $CookieTeamNumber == 0){echo "#FormID {display : none;}";}?>
@import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap');
@media print {
	body {
		margin: 0 !important;
	}
}
.main-container {
	font-family: 'Lato';
	width: fit-content;
	margin-left: auto;
	margin-right: auto;
}
.ck-content {
	font-family: 'Lato';
	line-height: 1.6;
	word-break: break-word;
}
.editor-container_classic-editor .editor-container__editor {
	min-width: 795px;
	max-width: 795px;
}
.ck-editor__editable {
    min-height: 200px;
}
form { display: inline; }
</style>
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.0/"
        }
    }
</script>
</head><body>
<?php include "Menu.php";?>
<div class="STHSNewsEditor_MainDiv">
<h1>
<?php 
echo $NewsLang['LeagueNews'] . " - ";
If ($NewsID >= 0){
	If ($ReplyNews > 0){echo $NewsLang['EditComment'];}else{echo $NewsLang['EditNews'];}
}else{
	If ($ReplyNews > 0){echo $NewsLang['CreateComment'];}else{echo $NewsLang['CreateNews'];}
}
?>
 - <a href="NewsManagement.php"><?php echo $NewsLang['ReturnLeagueNewsManagement'];?></a>
</h1>
<br>
<?php if ($InformationMessage != ""){echo "<div class=\"STHSDivInformationMessage\">" . $InformationMessage . "<br><br></div>";}?>
<div id="FormID" style="width:95%;margin:auto;">

	<form data-sample="1" action="NewsEditor.php<?php If ($lang == "fr"){echo "?Lang=fr";}?>" method="post" data-sample-short="">
		<strong><?php echo $NewsLang['NewsFrom'] . $CookieTeamName;?></strong>
		<br><br>
		<?php 
		echo "<input type=\"hidden\" name=\"Team\" value=\"" . $CookieTeamNumber . "\">";
		echo "<strong>" . $NewsLang['NewsTitle'] . "</strong>";
		If ($ReplyNews > 0){
			/* Reply News, can't edit title but required in the input so hidden input */
			echo "<input type=\"hidden\" name=\"Title\" value=\"" . $NewsTitle . "\">";
			echo $NewsTitle . "<br>";
		}else{
			/* Regular Code that show title textbox */
			echo "<input type=\"text\" name=\"Title\" value=\"";
			If ($NewsTitle != ""){echo $NewsTitle;}
			echo "\" size=\"80\" required><br>";
		}
		?>
		<br>
		<strong><?php echo $NewsLang['News'];?></strong>
        <textarea name="editor1" id="editor1">
		</textarea><br>
		<input type="hidden" name="NewsID" value="<?php echo $NewsID;?>">
		<?php If ($ReplyNews > 0){echo "<input type=\"hidden\" name=\"ReplyNews\" value=\"" . $ReplyNews . "\">";}?>
		<input type="submit" class="SubmitButton" value="<?php echo $NewsLang['Save'];?>">
<script type="module">
import {
	ClassicEditor,
	AccessibilityHelp,
	Alignment,
	AutoImage,
	Autosave,
	Bold,
	Code,
	Essentials,
	FindAndReplace,
	FontBackgroundColor,
	FontColor,
	FontFamily,
	FontSize,
	Heading,
	Highlight,
	HorizontalLine,
	ImageBlock,
	ImageCaption,
	ImageInline,
	ImageInsertViaUrl,
	ImageResize,
	ImageStyle,
	ImageTextAlternative,
	ImageToolbar,
	Indent,
	IndentBlock,
	Italic,
	Link,
	LinkImage,
	Paragraph,
	RemoveFormat,
	SelectAll,
	SourceEditing,
	SpecialCharacters,
	SpecialCharactersArrows,
	SpecialCharactersCurrency,
	SpecialCharactersEssentials,
	SpecialCharactersLatin,
	SpecialCharactersMathematical,
	SpecialCharactersText,
	Strikethrough,
	Table,
	TableCaption,
	TableCellProperties,
	TableColumnResize,
	TableProperties,
	TableToolbar,
	Underline,
	Undo
} from 'ckeditor5';

const editorConfig = {
	toolbar: {
		items: [
			'undo',
			'redo',
			'|',
			'sourceEditing',
			'findAndReplace',
			'selectAll',
			'|',
			'heading',
			'|',
			'fontSize',
			'fontFamily',
			'fontColor',
			'fontBackgroundColor',
			'|',
			'bold',
			'italic',
			'underline',
			'strikethrough',
			'code',
			'removeFormat',
			'|',
			'specialCharacters',
			'horizontalLine',
			'link',
			'insertImageViaUrl',
			'insertTable',
			'highlight',
			'|',
			'alignment',
			'|',
			'indent',
			'outdent',
			'|',
			'accessibilityHelp'
		],
		shouldNotGroupWhenFull: true
	},
	plugins: [
		AccessibilityHelp,
		Alignment,
		AutoImage,
		Autosave,
		Bold,
		Code,
		Essentials,
		FindAndReplace,
		FontBackgroundColor,
		FontColor,
		FontFamily,
		FontSize,
		Heading,
		Highlight,
		HorizontalLine,
		ImageBlock,
		ImageCaption,
		ImageInline,
		ImageInsertViaUrl,
		ImageResize,
		ImageStyle,
		ImageTextAlternative,
		ImageToolbar,
		Indent,
		IndentBlock,
		Italic,
		Link,
		LinkImage,
		Paragraph,
		RemoveFormat,
		SelectAll,
		SourceEditing,
		SpecialCharacters,
		SpecialCharactersArrows,
		SpecialCharactersCurrency,
		SpecialCharactersEssentials,
		SpecialCharactersLatin,
		SpecialCharactersMathematical,
		SpecialCharactersText,
		Strikethrough,
		Table,
		TableCaption,
		TableCellProperties,
		TableColumnResize,
		TableProperties,
		TableToolbar,
		Underline,
		Undo
	],
	fontFamily: {
		supportAllValues: true
	},
	fontSize: {
		options: [10, 12, 14, 'default', 18, 20, 22],
		supportAllValues: true
	},
	heading: {
		options: [
			{
				model: 'paragraph',
				title: 'Paragraph',
				class: 'ck-heading_paragraph'
			},
			{
				model: 'heading1',
				view: 'h1',
				title: 'Heading 1',
				class: 'ck-heading_heading1'
			},
			{
				model: 'heading2',
				view: 'h2',
				title: 'Heading 2',
				class: 'ck-heading_heading2'
			},
			{
				model: 'heading3',
				view: 'h3',
				title: 'Heading 3',
				class: 'ck-heading_heading3'
			},
			{
				model: 'heading4',
				view: 'h4',
				title: 'Heading 4',
				class: 'ck-heading_heading4'
			},
			{
				model: 'heading5',
				view: 'h5',
				title: 'Heading 5',
				class: 'ck-heading_heading5'
			},
			{
				model: 'heading6',
				view: 'h6',
				title: 'Heading 6',
				class: 'ck-heading_heading6'
			}
		]
	},
	image: {
		toolbar: [
			'toggleImageCaption',
			'imageTextAlternative',
			'|',
			'imageStyle:inline',
			'imageStyle:wrapText',
			'imageStyle:breakText',
			'|',
			'resizeImage'
		]
	},
	initialData: '<?php If ($NewsMessage != ""){echo addslashes(preg_replace('/(\r\n)|\r|\n/','', $NewsMessage));} ?>',
	link: {
		addTargetToExternalLinks: true,
		defaultProtocol: 'https://',
		decorators: {
			toggleDownloadable: {
				mode: 'manual',
				label: 'Downloadable',
				attributes: {
					download: 'file'
				}
			}
		}
	},
	placeholder: 'Type or paste your content here!',
	table: {
		contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
	}
};

ClassicEditor.create(document.querySelector('#editor1'), editorConfig);
		
		</script>


		<?php
		If ($NewsID >= 0){
			echo "<div style=\"display: inline;padding: 0px 50px 0px 50px\">";
			echo "<input class=\"SubmitButton\" type=\"submit\" name=\"Erase\" value=\"" . $NewsLang['Erase'] . "\"></div>";
		}
		?>
		</form>
	
	<br>
	<br><strong>Note:</strong><em><?php echo  $NewsLang['TeamNotePassword2'];?></em>
</div>
</div>

<?php include "Footer.php";?>
