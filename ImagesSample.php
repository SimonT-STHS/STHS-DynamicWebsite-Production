<?php include "Header.php";?>
<?php
$HistoryOutput = (boolean)False;
$ExtraH1 = (string)"";
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	echo "<style>Div{display:none}</style>";
	$Title = $DatabaseNotFound;
	$LeagueSimulationMenu = Null;
}else{
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, OutputName from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Title = $LeagueName . " - Image Sample";
}
echo "<title>" . $Title  . "</title>";
?>
<style>
</style>
</head><body>
<?php include "Menu.php";?>

<div style="width:95%;margin:auto;">
<h1>Image Sample</h1>
Note 
http://profinhl.cz/
https://teamcolorcodes.com/nhl-team-color-codes/

<table>
<tr>
<th style="width:200px;">Name</th>
<th style="width:100px;">Value</th>
<th style="width:100px;">Logo</th>
<th>BackGround Color</th>
<th>ForeFront Color</th>
<th>Border Color</th>
<th>TabMain Color</th>
</tr>

<tr><td>Pittsburgh Penguins</td><td>1</td><td><img src="./images/1.png" /></td><td style="background:#111">#111</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#FFB81C">#FFB81C</td><td style="Background:#010101">#010101</td></tr>
<tr><td>New York Islanders</td><td>2</td><td><img src="./images/2.png" /></td><td style="background:#00468B">#00468B</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#FC4C02">#FC4C02</td><td style="Background:#00468B">#00468B</td></tr>
<tr><td>New York Rangers</td><td>3</td><td><img src="./images/3.png" /></td><td style="background:#00428d">#00428d</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#00428d">#00428d</td><td style="Background:#C8102E">#C8102E</td></tr>
<tr><td>New Jersey Devils</td><td>4</td><td><img src="./images/4.png" /></td><td style="background:#ffffff">#ffffff</td><td style="Background:#000000">#000000</td><td style="Background:#C8102E">#C8102E</td><td style="Background:#010101">#010101</td></tr>
<tr><td>Philadelphia Flyers</td><td>5</td><td><img src="./images/5.png" /></td><td style="background:#000">#000</td><td style="Background:#FA4616">#FA4616</td><td style="Background:#FA4616">#FA4616</td><td style="Background:#010101">#010101</td></tr>
<tr><td>Carolina Hurricanes</td><td>6</td><td><img src="./images/6.png" /></td><td style="background:#C8102E">#C8102E</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#C8102E">#C8102E</td><td style="Background:#010101">#010101</td></tr>
<tr><td>Tampa Bay Lightning</td><td>7</td><td><img src="./images/7.png" /></td><td style="background:#ffffff">#ffffff</td><td style="Background:#00205B">#00205B</td><td style="Background:#003D7C">#003D7C</td><td style="Background:#fffffffff">#fffffffff</td></tr>
<tr><td>Winnipeg Jets</td><td>8</td><td><img src="./images/8.png" /></td><td style="background:#ffffff;">#ffffff;</td><td style="Background:#002d52">#002d52</td><td style="Background:#041E42">#041E42</td><td style="Background:#041E42">#041E42</td></tr>
<tr><td>Washington Capitals</td><td>9</td><td><img src="./images/9.png" /></td><td style="background:#041E42">#041E42</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#041E42">#041E42</td><td style="Background:#041E42">#041E42</td></tr>
<tr><td>Florida Panthers</td><td>10</td><td><img src="./images/10.png" /></td><td style="background:#c8102e">#c8102e</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#C51230">#C51230</td><td style="Background:#041E42">#041E42</td></tr>
<tr><td>Boston Bruins</td><td>11</td><td><img src="./images/11.png" /></td><td style="background:#111">#111</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#FFB81C">#FFB81C</td><td style="Background:#010101">#010101</td></tr>
<tr><td>Ottawa Senators</td><td>12</td><td><img src="./images/12.png" /></td><td style="background:#000">#000</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#C8102E">#C8102E</td><td style="Background:#010101">#010101</td></tr>
<tr><td>Montreal Canadiens</td><td>13</td><td><img src="./images/13.png" /></td><td style="background:#032366">#032366</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#032366">#032366</td><td style="Background:#032366">#032366</td></tr>
<tr><td>Buffalo Sabres</td><td>14</td><td><img src="./images/14.png" /></td><td style="background:#fdba31">#fdba31</td><td style="Background:#041E42">#041E42</td><td style="Background:#041E42">#041E42</td><td style="Background:#FFB81C">#FFB81C</td></tr>
<tr><td>Toronto Maple Leafs</td><td>15</td><td><img src="./images/15.png" /></td><td style="background:#000">#000</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#00205B">#00205B</td><td style="Background:#000">#000</td></tr>
<tr><td>St.Louis Blues</td><td>16</td><td><img src="./images/16.png" /></td><td style="background:#002f87">#002f87</td><td style="Background:#FFB81C">#FFB81C</td><td style="Background:#003087">#003087</td><td style="Background:#FFB81C">#FFB81C</td></tr>
<tr><td>Detroit Red Wings</td><td>17</td><td><img src="./images/17.png" /></td><td style="background:#ffffff">#ffffff</td><td style="Background:#C8102E">#C8102E</td><td style="Background:#C8102E">#C8102E</td><td style="Background:#fffffffff">#fffffffff</td></tr>
<tr><td>Chicago Blackhawks</td><td>18</td><td><img src="./images/18.png" /></td><td style="background:#CE1126">#CE1126</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#C8102E">#C8102E</td><td style="Background:#010101">#010101</td></tr>
<tr><td>Columbus Blue Jackets</td><td>19</td><td><img src="./images/19.png" /></td><td style="background:#ffffff">#ffffff</td><td style="Background:#041E42">#041E42</td><td style="Background:#041E42">#041E42</td><td style="Background:#C8102E">#C8102E</td></tr>
<tr><td>Nashville Predators</td><td>20</td><td><img src="./images/20.png" /></td><td style="background:#041E42">#041E42</td><td style="Background:#FFB81C">#FFB81C</td><td style="Background:#002D62">#002D62</td><td style="Background:#002d62">#002d62</td></tr>
<tr><td>Minnesota Wild</td><td>21</td><td><img src="./images/21.png" /></td><td style="background:#154734">#154734</td><td style="Background:#E2D6B5">#E2D6B5</td><td style="Background:#004F30">#004F30</td><td style="Background:#C51230">#C51230</td></tr>
<tr><td>Edmonton Oilers</td><td>22</td><td><img src="./images/22.png" /></td><td style="background:#ffffff">#ffffff</td><td style="Background:#091f40">#091f40</td><td style="Background:#e14505">#e14505</td><td style="Background:#041E41">#041E41</td></tr>
<tr><td>Calgary Flames</td><td>23</td><td><img src="./images/23.png" /></td><td style="background:#C8102E">#C8102E</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#C8102E">#C8102E</td><td style="Background:#F1BE48">#F1BE48</td></tr>
<tr><td>Vancouver Canucks</td><td>24</td><td><img src="./images/24.png" /></td><td style="background:#eee">#eee</td><td style="Background:#002d55">#002d55</td><td style="Background:#003E7E">#003E7E</td><td style="Background:#008852">#008852</td></tr>
<tr><td>Colorado Avalanche</td><td>25</td><td><img src="./images/25.png" /></td><td style="background:#70263e">#70263e</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#70263e">#70263e</td><td style="Background:#165788">#165788</td></tr>
<tr><td>Los Angeles Kings</td><td>26</td><td><img src="./images/26.png" /></td><td style="background:#A2AAAD">#A2AAAD</td><td style="Background:#111">#111</td><td style="Background:#010101">#010101</td><td style="Background:#A2AAAD">#A2AAAD</td></tr>
<tr><td>Arizona Coyotes</td><td>27</td><td><img src="./images/27.png" /></td><td style="background:#000">#000</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#98012E">#98012E</td><td style="Background:#EEE3C7">#EEE3C7</td></tr>
<tr><td>Dallas Stars</td><td>28</td><td><img src="./images/28.png" /></td><td style="background:#111">#111</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#016F4A">#016F4A</td><td style="Background:#010101">#010101</td></tr>
<tr><td>Anaheim Ducks</td><td>29</td><td><img src="./images/29.png" /></td><td style="background:#111">#111</td><td style="Background:#FC4C02">#FC4C02</td><td style="Background:#FC4C02">#FC4C02</td><td style="Background:#FC4C02">#FC4C02</td></tr>
<tr><td>San Jose Sharks</td><td>30</td><td><img src="./images/30.png" /></td><td style="background:#000">#000</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#006272">#006272</td><td style="Background:#E57200">#E57200</td></tr>
<tr><td>Vegas Golden Knights</td><td>32</td><td><img src="./images/32.png" /></td><td style="background:#333F48">#333F48</td><td style="Background:#ffffff">#ffffff</td><td style="Background:#333F48">#333F48</td><td style="Background:#333F48">#333F48</td></tr>

<tr><td>Wilkes-Barre/Scranton Penguins</td><td>101</td><td><img src="./images/101.png" /></td><td style="background:#000000">#000000</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Bridgeport Sound Tigers</td><td>102</td><td><img src="./images/102.png" /></td><td style="background:#00468B">#00468B</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Hartford Wolf Pack</td><td>103</td><td><img src="./images/103.png" /></td><td style="background:#00428d">#00428d</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Binghamton Devils</td><td>104</td><td><img src="./images/104.png" /></td><td style="background:#C8102E;">#C8102E;</td><td style="Background:#000000">#000000</td><td></td<td></td></tr>
<tr><td>Lehigh Valley Phantoms</td><td>105</td><td><img src="./images/105.png" /></td><td style="background:#FA4616">#FA4616</td><td style="Background:#FA4616">#FA4616</td><td></td<td></td></tr>
<tr><td>Charlotte Checkers</td><td>106</td><td><img src="./images/106.png" /></td><td style="background:#C8102E">#C8102E</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Syracuse Crunch</td><td>107</td><td><img src="./images/107.png" /></td><td style="background:#ffffff">#ffffff</td><td style="Background:#00205B">#00205B</td><td></td<td></td></tr>
<tr><td>Manitoba Moose</td><td>108</td><td><img src="./images/108.png" /></td><td style="background:#041E42">#041E42</td><td style="Background:#002d52">#002d52</td><td></td<td></td></tr>
<tr><td>Hershey Bears</td><td>109</td><td><img src="./images/109.png" /></td><td style="background:#C8102E">#C8102E</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Springfield Thunderbirds</td><td>110</td><td><img src="./images/110.png" /></td><td style="background:#c8102e">#c8102e</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Providence Bruins</td><td>111</td><td><img src="./images/111.png" /></td><td style="background:#111">#111</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Belleville Senators</td><td>112</td><td><img src="./images/112.png" /></td><td style="background:#e21a32">#e21a32</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Laval Rocket</td><td>113</td><td><img src="./images/113.png" /></td><td style="background:#ac1a2f">#ac1a2f</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Rochester Americans</td><td>114</td><td><img src="./images/114.png" /></td><td style="background:#041E42">#041E42</td><td style="Background:#041E42">#041E42</td><td></td<td></td></tr>
<tr><td>Toronto Marlies</td><td>115</td><td><img src="./images/115.png" /></td><td style="background:#ffffff">#ffffff</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>San Antonio Rampage</td><td>116</td><td><img src="./images/116.png" /></td><td style="background:#002f87">#002f87</td><td style="Background:#FFB81C">#FFB81C</td><td></td<td></td></tr>
<tr><td>Grand Rapids Griffins</td><td>117</td><td><img src="./images/117.png" /></td><td style="background:#C8102E">#C8102E</td><td style="Background:#C8102E">#C8102E</td><td></td<td></td></tr>
<tr><td>Rockford IceHogs</td><td>118</td><td><img src="./images/118.png" /></td><td style="background:#CE1126">#CE1126</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Cleveland Monsters</td><td>119</td><td><img src="./images/119.png" /></td><td style="background:#041E42">#041E42</td><td style="Background:#041E42">#041E42</td><td></td<td></td></tr>
<tr><td>Milwaukee Admirals</td><td>120</td><td><img src="./images/120.png" /></td><td style="background:#FFB81C">#FFB81C</td><td style="Background:#FFB81C">#FFB81C</td><td></td<td></td></tr>
<tr><td>Iowa Wild</td><td>121</td><td><img src="./images/121.png" /></td><td style="background:#154734">#154734</td><td style="Background:#E2D6B5">#E2D6B5</td><td></td<td></td></tr>
<tr><td>Bakersfield  Condors</td><td>122</td><td><img src="./images/122.png" /></td><td style="background:#091f40">#091f40</td><td style="Background:#091f40">#091f40</td><td></td<td></td></tr>
<tr><td>Stockton Heat</td><td>123</td><td><img src="./images/123.png" /></td><td style="background:#C8102E">#C8102E</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Utica Comets</td><td>124</td><td><img src="./images/124.png" /></td><td style="background:#003f7e">#003f7e</td><td style="Background:#002d55">#002d55</td><td></td<td></td></tr>
<tr><td>Colorado Eagles</td><td>125</td><td><img src="./images/125.png" /></td><td style="background:#236093">#236093</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Ontario Reign</td><td>126</td><td><img src="./images/126.png" /></td><td style="background:#111">#111</td><td style="Background:#111">#111</td><td></td<td></td></tr>
<tr><td>Tucson Roadrunners</td><td>127</td><td><img src="./images/127.png" /></td><td style="background:#ae3142">#ae3142</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Texas Stars</td><td>128</td><td><img src="./images/128.png" /></td><td style="background:#006341">#006341</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>San Diego Gulls</td><td>129</td><td><img src="./images/129.png" /></td><td style="background:#231f20">#231f20</td><td style="Background:#FC4C02">#FC4C02</td><td></td<td></td></tr>
<tr><td>San Jose Barracuda</td><td>130</td><td><img src="./images/130.png" /></td><td style="background:#006272">#006272</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>
<tr><td>Chicago Wolves</td><td>132</td><td><img src="./images/132.png" /></td><td style="background:#333F48">#333F48</td><td style="Background:#ffffff">#ffffff</td><td></td<td></td></tr>



</table>
<br />
</div>

<?php include "Footer.php";?>
