<br /><br /><br /><a href="#" class="scrollup">Back to the Top</a><div class="footer">
<?php echo $Footer . "Simon Tremblay";
If ($LangOverwrite == TRUE){
	If ($lang == "fr" ){
		echo " - <a id=\"IgnoreLang\" href=\"" . str_replace('Lang=fr','Lang=en',basename($_SERVER['REQUEST_URI'])) . "\">English Version of the Website</a>";
		echo "<script type=\"text/javascript\">\$(document).ready(function(){\$('a[href*=\".php\"').each(function(){if (this.id == 'IgnoreLang') {}else if (this.href.indexOf('?') != -1) {this.href = this.href.replace('.php?', '.php?Lang=fr&');}else{this.href = this.href.replace('.php', '.php?Lang=fr');}});});</script>";
	}else{
		echo " - <a id=\"IgnoreLang\" href=\"" . str_replace('Lang=en','Lang=fr',basename($_SERVER['REQUEST_URI'])) . "\">Version Française du Site Web</a>";
		echo "<script type=\"text/javascript\">\$(document).ready(function(){\$('a[href*=\".php\"').each(function(){if (this.id == 'IgnoreLang') {}else if (this.href.indexOf('?') != -1) {this.href = this.href.replace('.php?', '.php?Lang=en&');}else{this.href = this.href.replace('.php', '.php?Lang=en');}});});</script>";
	}
}else{
	If(substr($_SERVER['REQUEST_URI'],-1) == "/"){echo " - <a href=\"";}else{echo " - <a href=\"" . basename($_SERVER['REQUEST_URI']);}
	If ($lang == "fr"){
		If (strpos(basename($_SERVER['REQUEST_URI']),'?') !== false){ echo "&Lang=en\">";}else{echo "?Lang=en\">";}
		echo "English Version of the Website</a>";
	}else{
		If (strpos(basename($_SERVER['REQUEST_URI']),'?') !== false){ echo "&Lang=fr\">";}else{echo "?Lang=fr\">";}
		echo "Version Française du Site Web</a>";
	}
}
echo " - " . $DatabaseCreate . $LeagueGeneralMenu['DatabaseCreationDate'];?></div>
</body></html>
