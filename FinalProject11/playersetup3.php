<?php
/*
 * playersetup3.php
 * 
 * Copyright 2017 chels <chels@DESKTOP-1P6QNG4>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

?>
<head>
<meta charset="utf-8" />
<title>untitled</title>
<meta name="generator" content="Geany 1.31" />
<script type="text/javascript" src="playersetup.js" charset="utf-8"></script>
</head>

<body>
	<?php
	
		extract($_REQUEST);
		
	


	/*if ( isset($_POST['Submit'])){
	//$playerOne = $_GET['player1'];
	//$playerTwo = $_GET['player2'];
	echo"Player one's name:  $player1 <br>";
	echo"Player two's name:  $player2 <br>" ;
	echo"Player three's name: $player3 <br>";
	$players = array($player1, $player2, $player3);
	$numbers = array(0, 0);
	shuffle($players);
	$firstplayer = $players[0];
	$secondplayer = $players[1];
	$thirdplayer = $players[2];
	
	$fourthplayer = $firstplayer;
	$fifthplayer = $secondplayer;
	$sixthplayer = $thirdplayer;
	
	$seventhplayer = $firstplayer;
	$eighthplayer = $secondplayer;
	$ninthplayer = $thirdplayer;
	
	$tenthplayer = $firstplayer;
	$eleventhplayer = $secondplayer;
	$twelfthplayer = $thirdplayer;
	echo"The player to go first is $firstplayer <br>";
	
	echo"The player to get second is $secondplayer <br>";
	
	echo"The player to go thrid is $thirdplayer <br>";
	
	echo"<Button id='Play' name='Play'>Play Game!</Button>";
	echo'<script type="text/javascript">
	btn = document.getElementById("Play");
	btn.addEventListener("click", function() {
  document.location.href = "finalgame.html";
});
	
	</script>';
		//print_r($shuffle);
}*/

if (isset($_POST['Submit'])){
  $mySQL_Host="localhost";
  $mySQL_User="criewer";
  $mySQL_Pass="11777466";

  function connect(){
    global $mySQL_Host, $mySQL_User,$mySQL_Pass;

    if ( ! $linkid = mysql_connect("$mySQL_Host",
                                   "$mySQL_User","$mySQL_Pass")){
      echo "Impossible to connect to ", $mySQL_Host, "<br />";
      exit;
    }
    return $linkid;
  }

  function send_sql( $sql, $link, $db ) {
    if ( ! ($succ = mysql_select_db( $db))) {
      echo mysql_error();
      exit;
    }
    if ( ! ($res = mysql_query ( $sql, $link))) {
      echo  mysql_error();
      exit;
    }
    return $res;
  }
  //Deciding turn order
	$players = array($player1, $player2, $player3);
	$numbers = array(0, 0);
	shuffle($players);
	$firstplayer = $players[0];
	$secondplayer = $players[1];
	$thirdplayer = $players[2];
	$id1= 1;
	$id2= 2;
	$id3 = 3;
	$donations = 500;
	
	//Keeping track of the states the players entered.
	if ($firstplayer == $player1){
		$stateone = $state1;
	}
	if ($firstplayer == $player2){
		$stateone = $state2;
	}
	if ($firstplayer == $player3){
		$stateone = $state3;
	}
	
	if ($secondplayer == $player1){
		$statetwo = $state1;
	}
	if ($secondplayer == $player2){
		$statetwo = $state2;
	}
	if ($secondplayer == $player3){
		$statetwo = $state3;
	}
	
	if ($thirdplayer == $player1){
		$statethree = $state1;
	}
	if ($thirdplayer == $player2){
		$statethree = $state2;
	}
	if ($thirdplayer == $player3){
		$statethree = $state3;
	}
	
	

$link = connect();
$database = "criewer";
$sql= "INSERT INTO final (id, player_name, donations, num_locked, cur_state, home_state) VALUES ('$id1', '$firstplayer', '$donations', 0, '$stateone', '$stateone')";
$sql2= "INSERT INTO final (id, player_name, donations, num_locked, cur_state, home_state) VALUES ('$id2', '$secondplayer', '$donations', 0, '$statetwo', '$statetwo')";
$sql3= "INSERT INTO final (id, player_name, donations, num_locked, cur_state, home_state) VALUES ('$id3', '$thirdplayer', '$donations', 0, '$statethree', '$statethree')";
$result = send_sql($sql, $link, $database);
$result2 = send_sql($sql2, $link, $database);
$result3 = send_sql($sql3, $link, $database);

if ($result){
	
	echo"The player to go first is $firstplayer <br>";
	
	echo"The player to get second is $secondplayer <br>";
	
	echo"The player to go third is $thirdplayer <br>";
	
//creating a seperate table for each player to keep track of the influence and ad level in each state.	
	$sql = "CREATE table $firstplayer (state_id INT(6), state_ab VARCHAR(2), stateinfluence INT(100), state_ad INT(10), locked_in VARCHAR(3))";
	$sql2 = "CREATE table $secondplayer (state_id INT(6), state_ab VARCHAR(2), stateinfluence INT(100), state_ad INT(10), locked_in VARCHAR(3))";
	$sql3 = "CREATE table $thirdplayer (state_id INT(6), state_ab VARCHAR(2), stateinfluence INT(100), state_ad INT(10), locked_in VARCHAR(3))";
	$result = send_sql($sql, $link, $database);
	$result2 = send_sql($sql2, $link, $database);
	$result3 = send_sql($sql3, $link, $database);


    //updating first player DB with states and the current values.
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (1, 'AL', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (2, 'AK', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (3, 'AZ', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (4, 'AR', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (5, 'CA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (6, 'CO', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (7, 'CT', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (8, 'DE', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (9, 'FL', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (10, 'GA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (11, 'HI', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (12, 'ID', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (13, 'IN', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (14, 'IA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (15, 'KS', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (16, 'KY', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (17, 'LA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (18, 'ME', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (19, 'MD', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (20, 'MA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (21, 'MI', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (22, 'MN', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (23, 'MS', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (24, 'MO', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (25, 'MT', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (26, 'NE', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (27, 'NV', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (28, 'NH', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (29, 'NJ', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (30, 'NM', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (31, 'NY', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (32, 'NC', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (33, 'ND', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (34, 'OH', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (35, 'OK', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (36, 'OR', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (37, 'PA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (38, 'RI', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (39, 'SC', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (40, 'SD', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (41, 'TN', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (42, 'TX', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (43, 'UT', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (44, 'VT', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (45, 'VA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (46, 'WA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (47, 'WV', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (48, 'WI', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (49, 'WY', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $firstplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (50, 'IL', 0, 1)";
	$result = send_sql($sql, $link, $database);
	
	$sql = "UPDATE $firstplayer SET stateinfluence = 35 WHERE state_ab = '$stateone'";
	$result = send_sql($sql, $link, $database);
	
	$sql = "UPDATE $firstplayer SET state_ad = 1 WHERE state_ab = '$stateone'";
	$result = send_sql($sql, $link, $database);
	
	$sql = "SELECT * FROM $firstplayer";
	$result = send_sql($sql, $link, $database);
	
	 
	 
while($row = mysql_fetch_array($result)){
	 echo"<table>";
	 echo"<tr>";
	 echo"<th> Player </th>";
	 echo"<th> State ID </th>";
	 echo"<th> State </th>";
	 echo"<th> State Influence </th>"; 
	 echo"<th> State Advertisment </th>";
	 echo"</tr>";
	 echo"<tr>";
	 echo"<td>" . $firstplayer . "</td>";
	 echo"<td>" . $row['state_id'] . "</td>"; 
	 echo"<td>" . $row['state_ab'] . "</td>";
	 echo"<td>" . $row['stateinfluence'] . "</td>"; 
	 echo"<td>" . $row['state_ad'] . "</td>"; 
	 echo"</tr><br>";

}

	echo"</table>";
	
	
    //updating second player DB with states and the current values.
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (1, 'AL', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (2, 'AK', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (3, 'AZ', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (4, 'AR', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (5, 'CA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (6, 'CO', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (7, 'CT', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (8, 'DE', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (9, 'FL', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (10, 'GA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (11, 'HI', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (12, 'ID', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (13, 'IN', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (14, 'IA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (15, 'KS', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (16, 'KY', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (17, 'LA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (18, 'ME', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (19, 'MD', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (20, 'MA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (21, 'MI', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (22, 'MN', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (23, 'MS', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (24, 'MO', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (25, 'MT', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (26, 'NE', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (27, 'NV', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (28, 'NH', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (29, 'NJ', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (30, 'NM', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (31, 'NY', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (32, 'NC', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (33, 'ND', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (34, 'OH', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (35, 'OK', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (36, 'OR', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (37, 'PA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (38, 'RI', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (39, 'SC', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (40, 'SD', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (41, 'TN', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (42, 'TX', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (43, 'UT', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (44, 'VT', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (45, 'VA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (46, 'WA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (47, 'WV', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (48, 'WI', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (49, 'WY', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $secondplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (50, 'IL', 0, 1)";
	$result = send_sql($sql, $link, $database);
	
	$sql = "UPDATE $secondplayer SET stateinfluence = 35 WHERE state_ab = '$statetwo'";
	$result = send_sql($sql, $link, $database);
	
	$sql = "UPDATE $secondplayer SET state_ad = 1 WHERE state_ab = '$statetwo'";
	$result = send_sql($sql, $link, $database);
	
	$sql = "SELECT * FROM $secondplayer";
	$result = send_sql($sql, $link, $database);
	
	
	 
	
while($row = mysql_fetch_array($result)){
	echo"<table>";
	 echo"<tr>";
	 echo"<th> Player </th>";
	 echo"<th> State ID </th>";
	 echo"<th> State </th>";
	 echo"<th> State Influence </th>"; 
	 echo"<th> State Advertisment </th>";
	 echo"</tr>";
	 echo"<tr>";
	 echo"<td>" . $secondplayer . "</td>";
	 echo"<td>" . $row['state_id'] . "</td>"; 
	 echo"<td>" . $row['state_ab'] . "</td>";
	 echo"<td>" . $row['stateinfluence'] . "</td>"; 
	 echo"<td>" . $row['state_ad'] . "</td>"; 
	 echo"</tr><br>";

}
	echo"</table>";
	
// Updating third player with states and current values. 
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (1, 'AL', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (2, 'AK', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (3, 'AZ', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (4, 'AR', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (5, 'CA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (6, 'CO', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (7, 'CT', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (8, 'DE', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (9, 'FL', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (10, 'GA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (11, 'HI', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (12, 'ID', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (13, 'IN', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (14, 'IA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (15, 'KS', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (16, 'KY', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (17, 'LA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (18, 'ME', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (19, 'MD', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (20, 'MA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (21, 'MI', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (22, 'MN', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (23, 'MS', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (24, 'MO', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (25, 'MT', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (26, 'NE', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (27, 'NV', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (28, 'NH', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (29, 'NJ', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (30, 'NM', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (31, 'NY', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (32, 'NC', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (33, 'ND', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (34, 'OH', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (35, 'OK', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (36, 'OR', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (37, 'PA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (38, 'RI', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (39, 'SC', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (40, 'SD', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (41, 'TN', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (42, 'TX', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (43, 'UT', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (44, 'VT', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (45, 'VA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (46, 'WA', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (47, 'WV', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (48, 'WI', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (49, 'WY', 0, 1)";
	$result = send_sql($sql, $link, $database);
	$sql = "INSERT INTO $thirdplayer (state_id, state_ab, stateinfluence, state_ad) VALUES (50, 'IL', 0, 1)";
	$result = send_sql($sql, $link, $database);
	
	$sql = "UPDATE $thirdplayer SET stateinfluence = 35 WHERE state_ab = '$statethree'";
	$result = send_sql($sql, $link, $database);
	
	$sql = "UPDATE $thirdplayer SET state_ad = 1 WHERE state_ab = '$statethree'";
	$result = send_sql($sql, $link, $database);
	
	$sql = "SELECT * FROM $thirdplayer";
	$result = send_sql($sql, $link, $database);
	
	 
	
while($row = mysql_fetch_array($result)){
	 echo"<table>";
	 echo"<tr>";
	 echo"<th> Player </th>";
	 echo"<th> State ID </th>";
	 echo"<th> State </th>";
	 echo"<th> State Influence </th>"; 
	 echo"<th> State Advertisment </th>";
	 echo"</tr>";
	 echo"<tr>";
	 echo"<td>" . $thirdplayer . "</td>";
	 echo"<td>" . $row['state_id'] . "</td>"; 
	 echo"<td>" . $row['state_ab'] . "</td>";
	 echo"<td>" . $row['stateinfluence'] . "</td>"; 
	 echo"<td>" . $row['state_ad'] . "</td>"; 
	 echo"</tr><br>";

}
	echo"</table>";
	
	
	
	
	
	
	
	
	echo"<Button id='Play' name='Play'>Play Game!</Button>";
	echo'<script type="text/javascript">
	btn = document.getElementById("Play");
	btn.addEventListener("click", function() {
  document.location.href = "player1.php";
});
	
	</script>';
	
	//echo "<center>The username is $player.</center>";
	//echo "<form action='admin.html' method='post'> <input type='submit' value='Back'> </form>";

}
	  mysql_close( $link );



}
	 
	 
	
	
	else{?>	
	<head>
	<title>untitled</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.23.1" />
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<script type='text/javascript' src='playersetup.js' charset='utf-8'></script>
	<p>
	You picked three players<br>
	When entering in the states, use their 2 letter abbreviation.<br>
	What is the first players name: 

	<input type='text' name='player1'><br>
	What state does player 1 want to start in?
	<input type='text' name='state1'>
	<br>What is the second players name:

	<input type='text' name='player2'> <br>
	What state does player 2 want to start in?
	<input type='text' name='state2'><br>

	What is the third players name:

	<input type='text' name='player3'> <br>
	What state does player 3 want to start in?
	<input type='text' name='state3'> <br>
	<input type='submit' name='Submit' value='Submit'> <br>
	
	
	</p>
	
	
	
	

</body>

</html>
<?php } ?>
