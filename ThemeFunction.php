<?php 
Function NHLTeamThemeFunction($TeamThemeID){ /* For specific pages, we want the TableSorter Function to use the STHS Theme ID select in the Team Windows */
echo "
--main-table-background-head: var(--TeamNameColor_Background_" . $TeamThemeID . ");
--main-table-background-head-text: var(--TeamNameColor_TextColor_" . $TeamThemeID . "); 
--main-table-odd: #ffffff;
--main-table-even: #efefef;
--main-table-text-color: #000000;
--main-table-backgroud-color-hover: var(--TeamNameColor_SecondBackgroud_" . $TeamThemeID . ");
--main-table-text-color-hover: var(--TeamNameColor_TextColor_" . $TeamThemeID . "); 
--main-table-backgroud-disabled: #dddddd;  
--main-sort-background-color: var(--TeamNameColor_SecondBackgroud_" . $TeamThemeID . ");
--main-sort-text-color: var(--TeamNameColor_TextColor_" . $TeamThemeID . "); 
--main-filter-background-color: #eeeeee;
--main-filter-text-color: #333333;
--main-button-background: var(--TeamNameColor_Background_" . $TeamThemeID . ");
--main-button-hover: var(--TeamNameColor_SecondBackgroud_" . $TeamThemeID . ");
--main-button-text: var(--TeamNameColor_TextColor_" . $TeamThemeID . ");
";}

Function EchoNHLThemeCSSLink($CSSJSCDNPath){
echo "<link href=\"" . $CSSJSCDNPath . "STHSThemeStyleA.css\" rel=\"stylesheet\" type=\"text/css\">\n";
echo "<link href=\"" . $CSSJSCDNPath . "STHSTeam.css\" rel=\"stylesheet\" type=\"text/css\">\n";
}

Function GetThemeFunction($ThemeID,$CSSJSCDNPath ){switch ($ThemeID){
	
case 1: // All Black Theme //
echo "<style>
:root {
  --main-menu-background-color: #333333;
  --main-menu-text-color: #ffffff;
  --main-menu-background-color-hover: #4169e1;
  --main-menu-text-color-hover: #ffffff;
  --main-submenu-background-color: #474747;
  --main-submenu-text-color: #ffffff;
  --main-submenu-background-color-hover: #4169e1; 
  --main-submenu-text-color-hover: #ffffff; 
  --main-table-background-head: #3C3C3C;
  --main-table-background-head-text: #ffffff;  
  --main-table-odd: #8D8D8D;
  --main-table-even: #6D6D6D;
  --main-table-text-color: #ffffff;
  --main-table-backgroud-color-hover: #135185;
  --main-table-text-color-hover: #ffffff;
  --main-table-backgroud-disabled: #dddddd;   /* Not Use in this Theme */
  --main-sort-background-color: #135185;
  --main-sort-text-color: #dddddd; 
  --main-filter-background-color: #eeeeee; /* Not Change from Default Theme */
  --main-filter-text-color: #000000; /* Not Change from Default Theme */
  --main-button-background: #333333;
  --main-button-hover: #848484;
  --main-button-text: #ffffff;   
}
</style>\n";
echo "<link href=\"" . $CSSJSCDNPath . "STHSThemeStyleA.css\" rel=\"stylesheet\" type=\"text/css\">\n";
break;

case 1001: /* Pittsburgh Penguins Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #000000;
--main-menu-text-color: #FCB514;
--main-menu-background-color-hover: #FCB514;
--main-menu-text-color-hover: #000000;
--main-submenu-background-color: #CFC493;
--main-submenu-text-color: #000000;
--main-submenu-background-color-hover: #FCB514;
--main-submenu-text-color-hover : #000000;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1002: /* New York Islanders Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #00529B;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #F47D30;
--main-menu-text-color-hover:#FFFFFF;
--main-submenu-background-color: #00529B;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #F47D30; 
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1003: /* New York Islanders Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #0038A8;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #CE1126;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #0038A8;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #CE1126;
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1004: /* New Jersey Devils Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #CE1126;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #000000;
--main-menu-text-color-hover: #CE1126;
--main-submenu-background-color: #CE1126;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #000000;
--main-submenu-text-color-hover: #CE1126;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1005: /* Philadelphia Flyers Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #F74902;;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #000000;
--main-menu-text-color-hover: #F74902;
--main-submenu-background-color: #F74902;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #000000;
--main-submenu-text-color-hover: #F74902;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1006: /* Carolina Hurricanes Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #CE1126;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #000000;
--main-menu-text-color-hover: #CE1126;
--main-submenu-background-color: #A4A9AD;
--main-submenu-text-color: #000000;
--main-submenu-background-color-hover: #000000;
--main-submenu-text-color-hover: #CE1126;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1007: /* Tampa Bay Lightning Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #002868;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #FFFFFF;
--main-menu-text-color-hover: #002868;
--main-submenu-background-color: #002868;
--main-submenu-text-color: #FFFFFF; 
--main-submenu-background-color-hover: #FFFFFF;  
--main-submenu-text-color-hover: #002868;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1008: /* Winnipeg Jets Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #004C97;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #AC162C;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #55565A; 
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #AC162C;
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1009: /* Washington Capitals Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #041E42;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #C8102E;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #041E42;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #C8102E;  
--main-submenu-text-color-hover:#FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1010: /* Florida Panthers Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #041E42;
--main-menu-text-color: #FFFFFF;;
--main-menu-background-color-hover: #C8102E;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #B9975B;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #C8102E; 
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1011: /* Boston Bruins Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #000000;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #FFB81C;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #000000;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #FFB81C;
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1012: /* Ottawa Senators Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #DA1A32;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #B79257;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #000000;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #B79257;
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1013: /* Montreal Canadiens Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #AF1E2D;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #192168;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #AF1E2D;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #192168; 
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1014: /* Buffalo Sabres Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #003087;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #FFB81C;
--main-menu-text-color-hover: #003087;
--main-submenu-background-color: #003087;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #FFB81C; 
--main-submenu-text-color-hover: #003087;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1015: /* Toronto Maple Leafs Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #00205b;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #FFFFFF;
--main-menu-text-color-hover: #00205b;
--main-submenu-background-color: #00205b;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #FFFFFF;
--main-submenu-text-color-hover: #00205b;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1016: /* St. Louis Blues Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #002F87;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #FCB514;
--main-menu-text-color-hover: #041E42;
--main-submenu-background-color: #041E42;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #FCB514; 
--main-submenu-text-color-hover: #002F87;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1017: /* Detroit Red Wings Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #CE1126;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #FFFFFF;
--main-menu-text-color-hover: #CE1126;
--main-submenu-background-color: #CE1126;
--main-submenu-text-color: #FFFFFF; 
--main-submenu-background-color-hover: #FFFFFF;  
--main-submenu-text-color-hover: #CE1126;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1018: /* Chicago Blackhawks Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #CF0A2C;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #000000;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #CF0A2C;
--main-submenu-text-color:#FFFFFF;
--main-submenu-background-color-hover: #000000;
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1019: /* Columbus Blue Jackets Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #002654;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #CE1126;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #A4A9AD;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #CE1126; 
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1020: /* Nashville Predators Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color:  #FFB81C;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #041e42;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color:  #FFB81C;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #041e42; 
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1021: /* Minnesota Wild Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #154734;
--main-menu-text-color: #DDCBA4;
--main-menu-background-color-hover: #A6192E;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #DDCBA4;
--main-submenu-text-color: #154734;
--main-submenu-background-color-hover: #A6192E; 
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1022: /* Edmonton Oilers Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #041e41;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover:  #FF4C00;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color:  #041e41;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover:  #FF4C00;  
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1023: /* Edmonton Oilers Theme based on Style A*/
echo "<style>:root {
--main-menu-background-color: #D2001C;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #FAAF19;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #D2001C;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #FAAF19;
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1024: /* Vancouver Canucks Theme on Style A*/
echo "<style>:root {
--main-menu-background-color: #00205B;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #00843d;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #041C2C;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #00843d; 
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1025: /* Colardo Avalanche Theme based on Style A*/
echo "<style>
:root {
--main-menu-background-color: #6F263D;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #236192;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #A9B2BA;
--main-submenu-text-color: #000000;
--main-submenu-background-color-hover: #236192;  
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1026: /* Los Angeles Kings Theme based on Style A*/
echo "<style>
:root {
--main-menu-background-color: #111111;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #A2AAAD;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #111111;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #A2AAAD;
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1027: /* Arizona Coyotes Theme based on Style A*/
echo "<style>
:root {
--main-menu-background-color: #8C2633;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #e2d6b5;
--main-menu-text-color-hover:#000000;
--main-submenu-background-color: #8C2633;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #e2d6b5;
--main-submenu-text-color-hover: #000000;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1028: /* Dallas Stars Theme based on Style A*/
echo "<style>
:root {
--main-menu-background-color: #006847;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #8F8F8C;
--main-menu-text-color-hover:  #FFFFFF;
--main-submenu-background-color: #006847;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #8F8F8C;
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1029: /* Anaheim Ducks Theme based on Style A*/
echo "<style>
:root {
--main-menu-background-color: #F47A38;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #000000;
--main-menu-text-color-hover:  #FFFFFF;
--main-submenu-background-color: #B9975B;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #000000;
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1030: /* San Jose Sharks Theme based on Style A*/
echo "<style>
:root {
--main-menu-background-color: #006D75;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #EA7200;
--main-menu-text-color-hover:#FFFFFF;
--main-submenu-background-color: #006D75;
--main-submenu-text-color:#FFFFFF;
--main-submenu-background-color-hover: #EA7200; 
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1032: /* Vegas Golden Knights Theme based on Style A*/
echo "<style>
:root {
--main-menu-background-color: #B4975A;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover:#333f42;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #c8102E;
--main-submenu-text-color: #FFFFFF;
--main-submenu-background-color-hover: #333f42;
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1033: /* Seattle Kraken Theme Theme based on Style A*/
echo "<style>
:root {
--main-menu-background-color: #001628;
--main-menu-text-color: #99d9d9;
--main-menu-background-color-hover: #e9072b;
--main-menu-text-color-hover: #99d9d9;
--main-submenu-background-color: #68a2b9;
--main-submenu-text-color: #001628;
--main-submenu-background-color-hover: #355464;
--main-submenu-text-color-hover: #99d9d9;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;

case 1034: /* Utah Hockey Team Theme based on Style A*/
echo "<style>
:root {
--main-menu-background-color: #71AFE5;
--main-menu-text-color: #FFFFFF;
--main-menu-background-color-hover: #090909;
--main-menu-text-color-hover: #FFFFFF;
--main-submenu-background-color: #71AFE5;
--main-submenu-text-color: ##FFFFFF;
--main-submenu-background-color-hover: #090909; 
--main-submenu-text-color-hover: #FFFFFF;
"; NHLTeamThemeFunction($ThemeID - 1000);
echo "}</style>\n";
EchoNHLThemeCSSLink($CSSJSCDNPath);
break;


default:
echo "<style>
:root {
  --main-menu-background-color: #ffffff;
  --main-menu-text-color: #000000;
  --main-menu-background-color-hover: #ffffff; /* Not Use in Default Theme */
  --main-menu-text-color-hover: #191919;
  --main-submenu-background-color: #f2f2f2;
  --main-submenu-text-color: #000000;
  --main-submenu-background-color-hover: #dedede; 
  --main-submenu-text-color-hover: #000000;
  --main-table-background-head: #dedede;
  --main-table-background-head-text: #000000;
  --main-table-odd: #ffffff;
  --main-table-even: #efefef;
  --main-table-text-color: #000000;
  --main-table-backgroud-color-hover: #ebf2fa;
  --main-table-text-color-hover: #000000;
  --main-table-backgroud-disabled: #dddddd;  
  --main-sort-background-color: #8dbdd8;
  --main-sort-text-color: #333333;
  --main-filter-background-color: #eeeeee;
  --main-filter-text-color: #333333;
  --main-button-background: #99bfe6;
  --main-button-hover: #5797d7;  
  --main-button-text: #000000;
}
</style>\n";
break;

}}?>