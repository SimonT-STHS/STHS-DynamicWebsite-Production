<?php
include 'LanguageSTHSOutput.php';

switch ($lang){ 
case 'fr': /* Start FR Language Pack */

$DatabaseNotFound = "La base de donnée n'a pas été trouvé";

$TransactionLang = array(
'LeagueTitle'		=> 'Transactions de Ligue',
'TeamTitle'			=> 'Transactions pour ',
);

$Footer = "";

break; /* End FR Language Pack */
	
default: /* Start EN Language Pack */

$DatabaseNotFound = "Database File Not Found";

$TransactionLang = array(
'LeagueTitle'		=> 'League Transactions',
'TeamTitle'			=> 'Transactions for ',
);

$Footer = "";

break; 	/* End FR Language Pack */
} 

?>