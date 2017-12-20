<!DOCTYPE html>
<html lang="en">
<!--
   playersetup.php
   
   Copyright 2017 chels <chels@DESKTOP-1P6QNG4>
   
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.
   
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
   MA 02110-1301, USA.
   
   
-->

<head>
<meta charset="utf-8" />
<title>Player 1</title>
<meta name="generator" content="Geany 1.31" />

<link href="finalgame.css" type="text/css"rel="stylesheet"/>
</head>

<body>
	<?php
	
		extract($_REQUEST);
		
	

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
  
$link = connect();
$db = "criewer";


	//Put end state check here
	$sql = "SELECT player_name FROM final WHERE id=1";
	$result = send_sql($sql, $link, $db);
		
	$player = mysql_fetch_array($result);
		
	$playername = $player[0];
	
	
	//Getting the current player electoral votes amount
	$sqlEVC = "SELECT num_locked FROM final WHERE player_name = '$playername'";
	$result = send_sql($sqlEVC, $link, $db);
	$EV = mysql_fetch_array($result);
	$ElectoralVotes = $EV[0];
	
	//Getting from the states table to check if all 50 states have been locked.
	#SELECT * FROM locking player, if it returns 0  then all 50 states are locked in. refur to website.
	$sqlLocked = "SELECT * FROM states WHERE locking_player != 'nothing'";
	$resultL = send_sql($sqlLocked, $link, $db);
	$Locked = mysql_num_rows($resultL); 

	
	
	if($ElectoralVotes >= 270 || $Locked == 50 ){
		
		if($ElectoralVotes >= 270){
		echo"Congratulations!!! You have won the game!<br>";
		echo"You have $ElectoralVotes Electoral Votes. <br>"; 
	}
		if($Locked == 50){
			
			$sqlW = "SELECT MAX(num_locked) FROM final";
			$result = send_sql($sqlW, $link, $db);
			$WinNum = mysql_fetch_array($result);
			$Winner = $WinNum[0];
			
			$sqlPlayer = "SELECT player_name FROM final WHERE num_locked = $Winner";
			$winresult = send_sql($sqlPlayer, $link, $db);
			$win = mysql_fetch_array($winresult);
			$PlayerWin = $win[0];
			
			echo"All 50 states were locked in...<br>";
			echo"$PlayerWin has won the game!";
			
		}
		
		
		//Clearing the DB
		
		//Dropping table used for player 2
		$sqlPL1 = "SELECT player_name FROM final WHERE id = 2";
		$result2 = send_sql($sqlPL1, $link, $db);
		$player2 = mysql_fetch_array($result2);
		$playertwo = $player2[0];
		
		
		
		//Dropping table used for player 1
		$sqlPL1 = "SELECT player_name FROM final WHERE id = 1";
		$result2 = send_sql($sqlPL1, $link, $db);
		$player1 = mysql_fetch_array($result2);
		$playerone = $player1[0]; 
		
		$sql2 = "DROP TABLE $playertwo";
		$result = send_sql($sql2, $link, $db);
	
		$sql1 = "DROP TABLE $playerone";
		$result = send_sql($sql1, $link, $db);
		
		$sql = "DELETE FROM final WHERE id = 1";
		$result = send_sql($sql, $link,  $db);
		
		$sql = "DELETE FROM final WHERE id = 2";
		$result = send_sql($sql, $link,  $db);
		
		
		//Maybe just drop table 3 and 4 even if they arent there and dont care.
		
		
		
			//Checking for players 3 and 4
			
			//Player 3 Check
			$sqlPL1 = "SELECT player_name FROM final WHERE id = 3";
			$result3 = send_sql($sqlPL1, $link, $db);
			$player3 = mysql_fetch_array($result3);
			$playerthree = $player3[0]; 
			
				if($result3 && mysql_num_rows($result3) >0){
					$sql = "DROP TABLE $playerthree";
					$result = send_sql($sql, $link, $db);
					
					$sql = "DELETE FROM final WHERE id = 3";
					$result = send_sql($sql, $link,  $db);
				}
				
				//Player 4 Check
				
				$sqlPL1 = "SELECT player_name FROM final WHERE id = 4";
				$result3 = send_sql($sqlPL1, $link, $db);
				$player3 = mysql_fetch_array($result3);
				$playerthree = $player3[0]; 
			
				if($result3 && mysql_num_rows($result3) >0){
					$sql = "DROP TABLE $playerthree";
					$result = send_sql($sql, $link, $db);
					
					$sql = "DELETE FROM final WHERE id = 4";
					$result = send_sql($sql, $link,  $db);
				}
				
				//Setting the locking_player column back to "nothing"
				$sqlUD = "UPDATE states SET locking_player = 'nothing'";
				$result = send_sql($sqlUD, $link, $db);
		
		
		
		
	}else{//The game is not over, Show the other stats.
	
	
	
	
	
$sql = "SELECT * FROM final";
$result = send_sql($sql, $link, $db);
$rownum = mysql_num_rows($result);

if($rownum > 0){

//Ad level definitions	
	$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
	$curstate = send_sql($sqlCS, $link, $db);
			
	$state = mysql_fetch_array($curstate);
	$cur_state = $state[0];

	
	$sql = "SELECT donations FROM final WHERE id = 1";
	$result = send_sql($sql, $link, $db);
	
	$donations = mysql_fetch_array($result);
	$donations = $donations[0];
	
	
	$sql = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
	$result = send_sql($sql, $link, $db);
	
	$cur_ad = mysql_fetch_array($result);
	$cur_ad = $cur_ad[0];
	
if($cur_ad < 10){
	$cost = $AdLevel *100;

if($AdLevel > 0){	
	if($donations >= $cost){
		echo "You bought an ad level of $AdLevel";
		echo "<br>It will cost you $cost dollars";
		
		$sqlUD = "UPDATE final SET donations = donations - $cost WHERE id = 1";
		$UD = send_sql($sqlUD, $link, $db);
		
		
		$sqlUD = "UPDATE $playername SET state_ad = state_ad + $AdLevel WHERE state_ab = '$cur_state'";
		$UD = send_sql($sqlUD, $link, $db);
		
		
		$sql = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
		$send = send_sql($sql, $link, $db);
		
		$ad = mysql_fetch_array($send);
		$adlevel = $ad[0];
		
		
		
		if($adlevel > 10){
			echo"<br>You purchased more ad level than you needed!!!!!<br>";
			
			if($adlevel == 20){
				echo"You have been refunded 1000 dollars<br>";
				
						$sqlUD = "UPDATE final SET donations = donations + 1000 WHERE id = 1";
						$UD = send_sql($sqlUD, $link, $db);
		
						$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
						$UD = send_sql($sqlUD, $link, $db);
					}
					
			if($adlevel == 19){
				echo"You have been refunded 900 dollars<br>";
				
						$sqlUD = "UPDATE final SET donations = donations + 900 WHERE id = 1";
						$UD = send_sql($sqlUD, $link, $db);
		
						$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
						$UD = send_sql($sqlUD, $link, $db);
					}
					
					
					
			if($adlevel == 18){
				echo"You have been refunded 800 dollars<br>";
				
						$sqlUD = "UPDATE final SET donations = donations + 800 WHERE id = 1";
						$UD = send_sql($sqlUD, $link, $db);
		
						$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
						$UD = send_sql($sqlUD, $link, $db);
					}
					
			if($adlevel == 17){
				echo"You have been refunded 700 dollars<br>";
				
						$sqlUD = "UPDATE final SET donations = donations + 700 WHERE id = 1";
						$UD = send_sql($sqlUD, $link, $db);
		
						$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
						$UD = send_sql($sqlUD, $link, $db);
					}
					
			if($adlevel == 16){
				echo"You have been refunded 600 dollars<br>";
				
						$sqlUD = "UPDATE final SET donations = donations + 600 WHERE id = 1";
						$UD = send_sql($sqlUD, $link, $db);
		
						$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
						$UD = send_sql($sqlUD, $link, $db);
					}
					
			if($adlevel == 15){
				echo"You have been refunded 500 dollars<br>";
				
						$sqlUD = "UPDATE final SET donations = donations + 500 WHERE id = 1";
						$UD = send_sql($sqlUD, $link, $db);
		
						$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
						$UD = send_sql($sqlUD, $link, $db);
					}
			if($adlevel == 14){
				echo"You have been refunded 400 dollars<br>";
				
						$sqlUD = "UPDATE final SET donations = donations + 400 WHERE id = 1";
						$UD = send_sql($sqlUD, $link, $db);
		
						$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
						$UD = send_sql($sqlUD, $link, $db);
					}	
					
					
			if($adlevel == 13){
				echo"You have been refunded 300 dollars<br>";
				
						$sqlUD = "UPDATE final SET donations = donations + 300 WHERE id = 1";
						$UD = send_sql($sqlUD, $link, $db);
		
						$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
						$UD = send_sql($sqlUD, $link, $db);
					}
					
			if($adlevel == 12){
				echo"You have been refunded 200 dollars<br>";
				
						$sqlUD = "UPDATE final SET donations = donations + 200 WHERE id = 1";
						$UD = send_sql($sqlUD, $link, $db);
		
						$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
						$UD = send_sql($sqlUD, $link, $db);
					}
					
			if($adlevel == 11){
				echo"You have been refunded 100 dollars<br>";
				
						$sqlUD = "UPDATE final SET donations = donations + 100 WHERE id = 1";
						$UD = send_sql($sqlUD, $link, $db);
		
						$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
						$UD = send_sql($sqlUD, $link, $db);
					}	
					
			}		
					
	
}else{
	echo"<br>Sorry, you did not have enough donations to by that ad level.";
}
}
}else{
	echo"Your ad level is already 10!<br>";
}


//Locking in check.
        $sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
		$curstate = send_sql($sqlCS, $link, $db);
			
		$state = mysql_fetch_array($curstate);
		$cur_state = $state[0];
		
		$sql1 = "SELECT donations FROM final WHERE id = 1";
		$result1 = send_sql($sql1, $link, $db);
		$donations  = mysql_fetch_array($result1);
		$DonationAmount = $donations[0];
		
		$sql2 = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
		$result2 = send_sql($sql2, $link, $db);
		$State = mysql_fetch_array($result2);
		$StateInfluence = $State[0];
		
if($DonationAmount >= 1000 && $StateInfluence >= 100){

		if($Lock == "YES" || $Lock == "Yes" || $Lock == "yes"){
				$sql = "SELECT locked_in FROM $playername WHERE state_ab = '$cur_state'";
				$result = send_sql($sql, $link, $db);
				
				$Locked = mysql_fetch_array($result);
				$LockedIn = $Locked[0];
				
				//Checking if a player has locked the state in already
				$sqlPC = "SELECT locking_player FROM states WHERE state_ab = '$cur_state'";
				$resultPC = send_sql($sqlPC, $link, $db);
				$PC = mysql_fetch_array($resultPC);
				$PlayerCheck = $PC[0];
				
				if($PlayerCheck != "nothing"){
					
					echo"Sorry! Another player has locked in this state and got the Electoral Votes. <br>";
					
				}else{ if($LockedIn == "YES"){
					
					echo"You've already locked this state in!<br>";
					
				}else{
					$sql1 = "UPDATE $playername SET locked_in = 'YES' WHERE state_ab = '$cur_state'";
					$result = send_sql($sql1, $link, $db);
					
					$sql2 = "UPDATE final SET donations = donations - 1000 WHERE player_name = '$playername'";
					$result = send_sql($sql2, $link, $db);
				
					
					//Sending player name over to states DB
					$sql4 = "UPDATE states SET locking_player = '$playername' WHERE state_ab = '$cur_state'";
					$result = send_sql($sql4, $link, $db);
					
					$sql = "SELECT electoral_votes FROM states WHERE state_ab = '$cur_state'";
					$result = send_sql($sql, $link, $db);
					
					$stateElec = mysql_fetch_array($result);
					$ElectoralAmount = $stateElec[0];
					
					$sqlUD = "UPDATE final SET num_locked = num_locked + $ElectoralAmount WHERE player_name = '$playername'";
					$result = send_sql($sqlUD, $link, $db);  
						
					
					
					echo"You have locked in $cur_state!<br>";
					
				}
				
			}
			
		}
		}else{
				echo"You do/did not have enough state influence and/or donations to lock this state in.... Silly!<br>";
			
		}

	echo "You ended your turn <br>";
	echo "Here are your final stats: <br>";







	$sql = ("SELECT id, player_name, donations, num_locked, cur_state, home_state FROM final WHERE id = 1");
	$res = send_sql($sql, $link, $db);
	
	//echo $result;
echo "<table>";	
	while($row = mysql_fetch_array($res)){
	 echo"<tr>";
	 echo"<th> Player ID </th>";
	 echo"<th> Player Name </th>";
	 echo"<th> Donations </th>"; 
	 echo"<th> Electoral Votes </th>";
	 echo"<th> Current State </th>";
	 echo"<th> Home State </th>";
	 echo"</tr>";
	 echo"<tr>";
	 echo"<td>" . $row['id'] . "</td>"; 
	 echo"<td>" . $row['player_name'] . "</td>";
	 echo"<td>" . $row['donations'] . "</td>"; 
	 echo"<td>" . $row['num_locked'] . "</td>"; 
	 echo"<td>" . $row['cur_state'] . "</td>"; 
	 echo"<td>" . $row['home_state'] . "</td>"; 
	 echo"</tr>";

}
echo "</table>";


	$sql = "SELECT player_name FROM final WHERE id=1";
	$result = send_sql($sql, $link, $db);
		
	$player = mysql_fetch_array($result);
		
	$playername = $player[0];

	$sql = "SELECT * FROM $playername";
	$result = send_sql($sql, $link, $db);
	
	 
 
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
	 echo"<td>" . $playername . "</td>";
	 echo"<td>" . $row['state_id'] . "</td>"; 
	 echo"<td>" . $row['state_ab'] . "</td>";
	 echo"<td>" . $row['stateinfluence'] . "</td>"; 
	 echo"<td>" . $row['state_ad'] . "</td>"; 
	 echo"</tr><br>";

}
	echo"</table>";
echo"<button id='np'>Next Player</button>";
	echo'<script type="text/javascript">
	btn = document.getElementById("np");
	btn.addEventListener("click", function() {
  document.location.href = "player2.php";
});
	
	</script>';


}
}
	 
	 
	}
	
	else{?>	
	<head>
	<title>Player 1</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.23.1" />
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

<?php

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
  
$link = connect();
$db = "criewer";

	$sql = ("SELECT id, player_name, donations, num_locked, cur_state, home_state FROM final WHERE id = 1");
	$res = send_sql($sql, $link, $db);
	
	
	//echo $result;
echo "<table>";	
	while($row = mysql_fetch_array($res)){
	 echo"<tr>";
	 echo"<th> Player ID </th>";
	 echo"<th> Player Name </th>";
	 echo"<th> Donations </th>"; 
	 echo"<th> Electoral Votes </th>";
	 echo"<th> Current State </th>";
	 echo"<th> Home State </th>";
	 echo"</tr>";
	 echo"<tr>";
	 echo"<td>" . $row['id'] . "</td>"; 
	 echo"<td>" . $row['player_name'] . "</td>";
	 echo"<td>" . $row['donations'] . "</td>"; 
	 echo"<td>" . $row['num_locked'] . "</td>"; 
	 echo"<td>" . $row['cur_state'] . "</td>"; 
	 echo"<td>" . $row['home_state'] . "</td>"; 
	 echo"</tr>";

}
echo "</table>";

		$sql = "SELECT player_name FROM final WHERE id=1";
		$result = send_sql($sql, $link, $db);
		
		$player = mysql_fetch_array($result);
		
		$playername = $player[0];
		
		
	echo"Which state would you like to move to?  ";
	echo"Movement costs 100 dollars per state travelled through.<br>";
	echo"<input type='text' name='movestate' id='MOVE'><br>";
	


?>
	<p>
		
		<div class="cards">
		
		</div>
	
	<input type='submit' name='submitmove' id='submitmove' value='Move To State'>
	<?php
	//State Movement
	if(isset($_POST['submitmove'])){
		
		$submitmove = $_POST['movestate'];
		echo"<br>You chose to move to $submitmove <br>";
		//Calculate Cost
		//Get Current State
		$sqlGetCurrentState = "SELECT cur_state FROM final WHERE player_name = '$playername'";
		$getCurStateResult = send_sql($sqlGetCurrentState, $link, $db);
		$curStateArr = mysql_fetch_array($getCurStateResult);
		$current_state = $curStateArr[0];
		//Find Distance To New State
		$sqlGetTravelCost = "SELECT `$current_state` FROM travelDistances WHERE state = '$submitmove'";
		$getTravelCostResult = send_sql($sqlGetTravelCost, $link, $db);
		$travelDistanceArr = mysql_fetch_array($getTravelCostResult);
		$travelDistance = $travelDistanceArr[0];
		
		$costOfMovement = $travelDistance * 100;
		
		//Get Current Donation Amount
		$sqlGetDonations = "SELECT donations FROM final WHERE player_name ='$playername'";
		$getDonationsResult = send_sql($sqlGetDonations, $link, $db);
		$donationsAmountArr = mysql_fetch_array($getDonationsResult);
		$donationsAmount = $donationsAmountArr[0];
		
		$newDonationsAmount = $donationsAmount - $costOfMovement;
		
		//Do they have enough money?
		if($newDonationsAmount >= 0){
			echo"You have moved to $submitmove <br>";
			echo"You paid $costOfMovement dollars for the trip and now have $newDonationsAmount dollars.<br>";
			$sqlUpdateDonations = "UPDATE final SET donations = $newDonationsAmount WHERE player_name = '$playername'";
			$result = send_sql($sqlUpdateDonations, $link, $db);
			$sqlUpdateCurrentState = "UPDATE final SET cur_state = '$submitmove' WHERE player_name = '$playername'";
			$result = send_sql($sqlUpdateCurrentState, $link, $db);
		}
		else
		{
			echo"You do not have enough money to move to that state, silly.<br>";
			echo"You will remain where you are.<br>";
		}
	}
	?>
	
	<div class="ButtonCenter">
	
	<input type='submit' name='Submit' value='End Turn'>
<span id="picSpot">


</span>
	<input type='submit' id='submitpic' name='submitpic' value='Draw Card'><br>
	
	<?php
	extract($_REQUEST);
	if(isset($_POST['submitpic'])){
		
	echo'<script type="text/javascript">
	btn = document.getElementById("submitpic");
	btn.addEventListener("click", function() {
	btn.disabled = true;
});
	
	</script>';
		
		$img1 = "badPress1.PNG";
		$img2 = "badPress2.PNG";
		$img3 = "badPress3.PNG";
		$img4 = "badPress5.PNG";
		$img5 = "badPress6.PNG";
		$img6 = "cancellation1.PNG";
		$img7 = "cancellation2.PNG";
		$img8 = "cancellation3.PNG";
		$img9 = "cancellation4.PNG";
		$img10 = "cancellation5.PNG";
		$img11 = "cancellation6.PNG";
		$img12 = "cancellation7.PNG";
		$img13 = "cancellation8.PNG";
		$img14 = "celebrity1.PNG";
		$img15 = "celebrity2.PNG";
		$img16 = "celebrity3.PNG";
		$img17 = "celebrity4.PNG";
		$img18 = "celebrity5.PNG";
		$img19 = "celebrity6.PNG";
		$img20 = "celebrity7.PNG";
		$img21 = "celebrity8.PNG";
		$img22 = "emergency1.PNG";
		$img23 = "emergency2.PNG";
		$img24 = "grassroots1.PNG";
		$img25 = "grassroots2.PNG";
		$img26 = "grassroots3.PNG";
		$img27 = "grassroots4.PNG";
		$img28 = "naturaldisaster1.PNG";
		$img29 = "naturaldisaster2.PNG";
		$img30 = "saidsomething1.PNG";
		$img31 = "saidsomething2.PNG";
		$img32 = "saidsomething3.PNG";
		$img33 = "saidsomething4.PNG";
		$img34 = "scandal1.PNG";
		$img35 = "scandal2.PNG";
		$img36 = "scandal3.PNG";
		$img37 = "scandal4.PNG";
		$img38 = "scandal5.PNG";
		$img39 = "scandal6.PNG";
		$img40 = "scandal7.PNG";
		$img41 = "scandal8.PNG";
		$img42 = "talkshowColbert1.PNG";
		$img43 = "talkshowColbert2.PNG";
		$img44 = "talkshowColbert3.PNG";
		$img45 = "talkshowColbert4.PNG";
		$img46 = "talkshowFallon1.PNG";
		$img47 = "talkshowFallon2.PNG";
		$img48 = "talkshowFallon3.PNG";
		$img49 = "talkshowFallon4.PNG";
		$img50 = "talkshowGMA1.PNG";
		$img51 = "talkshowGMA2.PNG";
		$img52 = "talkshowGMA3.PNG";
		$img53 = "talkshowGMA4.PNG";
		$img54 = "volunteer1.PNG";
		$img55 = "volunteer2.PNG";
		$img56 = "volunteer3.PNG";
		$img57 = "volunteer4.PNG";
		$img58 = "volunteer5.PNG";
		$img59 = "volunteer6.PNG";
		$img60 = "badPress4.PNG";
		
		
		$pictures = array($img1, $img2, $img3, $img4, $img5, $img6, $img7, $img8, $img9, $img10, $img11, $img12, $img13, $img14, $img15, $img16, $img17, $img18, $img19, $img20, $img21, $img22, $img23, $img24, $img25, $img26, $img27, $img28, $img29, $img30, $img31, $img32, $img33, $img34, $img35, $img36, $img37, $img38, $img39, $img40, $img41, $img42, $img43, $img44, $img45, $img46, $img47,$img48, $img49, $img50, $img51, $img52, $img53, $img54, $img55, $img56, $img57, $img58, $img59, $img60);
		shuffle($pictures);
		
		$finalimage = $pictures[0];
		
		//50 States
		$states = array("AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY");
		shuffle($states);
		
		$state1 = $states[0];
		$state2 = $states[1];
		$state3 = $states[2];
		$state4 = $states[3];
		
		//Advertisment Buying
		
		echo "Would you like to buy advertisment for the state you are currently in? <br>";
		echo "Remember, each level costs 100 dollars, i.e. level 4 will cost 400 dollars <br>";
		echo "Enter in the level: ";
		echo "<input type='text' name='AdLevel'> <br>";
		
		
		
		//Locking in the state
		$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
		$curstate = send_sql($sqlCS, $link, $db);
			
		$state = mysql_fetch_array($curstate);
		$cur_state = $state[0];
		
		$sql1 = "SELECT donations FROM final WHERE id = 1";
		$result1 = send_sql($sql1, $link, $db);
		$donations  = mysql_fetch_array($result1);
		$DonationAmount = $donations[0];
		
		$sql2 = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
		$result2 = send_sql($sql2, $link, $db);
		$State = mysql_fetch_array($result2);
		$StateInfluence = $State[0];
		
		echo"Locking in Box: ";
		
		echo"<input type='text' name='Lock' id='Lock'><br>";
		
		if($DonationAmount >= 1000 && $StateInfluence == 100){
			
			echo "You can lock $cur_state in!<br>";
			echo "If you'd like to lock it in, type YES in the box!!: ";
			

			
			
		}
		
		
		echo"$state1, $state2, $state3, $state4";
		
		echo"<img src='$finalimage'>";
		
  
$link = connect();
$db = "criewer";
		
		//Card Events
		
		//badPress1.PNG
		if($finalimage == $img1){
			
			//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			//Math for card
			$Donations = 100 * $AdLevel;
			$Donations = $Donations/2 ; 
			
			//Getting the current amount of donations for the player
			$sqlDU = "SELECT donations FROM final WHERE player_name = '$playername'";
			$CurDonations = send_sql($sqlDU, $link, $db);
			
			$Cur_Donations = mysql_fetch_array($CurDonations);
			$CurrentDonations = $Cur_Donations[0];
			
			//Updating the donations value
			$DonationUpdate = $CurrentDonations + $Donations;
			

			
			$sqlUD = "UPDATE final SET donations = $DonationUpdate WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//badPress2.PNG
		if($finalimage == $img2){
			
			//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			//Math for card
			$Donations = 100 * $AdLevel;
			$Donations = $Donations/2 ; 
			
			//Getting the current amount of donations for the player
			$sqlDU = "SELECT donations FROM final WHERE player_name = '$playername'";
			$CurDonations = send_sql($sqlDU, $link, $db);
			
			$Cur_Donations = mysql_fetch_array($CurDonations);
			$CurrentDonations = $Cur_Donations[0];
			
			//Updating the donations value
			$DonationUpdate = $CurrentDonations + $Donations;
			echo"$DonationUpdate";
			
			$sqlUD = "UPDATE final SET donations = $DonationUpdate WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//badPress3.PNG
		if($finalimage == $img3){
			
			//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			//Math for card
			$Donations = 100 * $AdLevel;
			$Donations = $Donations/2 ; 
			
			//Getting the current amount of donations for the player
			$sqlDU = "SELECT donations FROM final WHERE player_name = '$playername'";
			$CurDonations = send_sql($sqlDU, $link, $db);
			
			$Cur_Donations = mysql_fetch_array($CurDonations);
			$CurrentDonations = $Cur_Donations[0];
			
			//Updating the donations value
			$DonationUpdate = $CurrentDonations + $Donations;

			
			$sqlUD = "UPDATE final SET donations = $DonationUpdate WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//badPress5.PNG
		if($finalimage == $img4){
			
			//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			//Math for card
			$Donations = 100 * $AdLevel;
			$Donations = $Donations/2 ; 
			
			//Getting the current amount of donations for the player
			$sqlDU = "SELECT donations FROM final WHERE player_name = '$playername'";
			$CurDonations = send_sql($sqlDU, $link, $db);
			
			$Cur_Donations = mysql_fetch_array($CurDonations);
			$CurrentDonations = $Cur_Donations[0];
			
			//Updating the donations value
			$DonationUpdate = $CurrentDonations + $Donations;

			
			$sqlUD = "UPDATE final SET donations = $DonationUpdate WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//badPress6.PNG
		if($finalimage == $img5){
			
			//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			//Math for card
			$Donations = 100 * $AdLevel;
			$Donations = $Donations/2 ; 
			
			//Getting the current amount of donations for the player
			$sqlDU = "SELECT donations FROM final WHERE player_name = '$playername'";
			$CurDonations = send_sql($sqlDU, $link, $db);
			
			$Cur_Donations = mysql_fetch_array($CurDonations);
			$CurrentDonations = $Cur_Donations[0];
			
			//Updating the donations value
			$DonationUpdate = $CurrentDonations + $Donations;

			
			$sqlUD = "UPDATE final SET donations = $DonationUpdate WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}		
		//cancellation1.PNG
		if($finalimage == $img6){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlGetAdjacents = "SELECT state FROM travelDistances WHERE `$cur_state` = 1";
			$adj = send_sql($sqlGetAdjacents, $link, $db);
			$adjacents = mysql_fetch_array($adj);
			$adjacentState = $adjacents[array_rand($adjacents)];
			
			$sqlUS = "UPDATE final SET cur_state = '$adjacentState' WHERE player_name = '$playername'";
			$US = send_sql($sqlUS, $link, $db);	
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//cancellation2.PNG
		if($finalimage == $img7){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlGetAdjacents = "SELECT state FROM travelDistances WHERE `$cur_state` = 1";
			$adj = send_sql($sqlGetAdjacents, $link, $db);
			$adjacents = mysql_fetch_array($adj);
			$adjacentState = $adjacents[array_rand($adjacents)];
			
			$sqlUS = "UPDATE final SET cur_state = '$adjacentState' WHERE player_name = '$playername'";
			$US = send_sql($sqlUS, $link, $db);	
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//cancellation3.PNG
		if($finalimage == $img8){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlGetAdjacents = "SELECT state FROM travelDistances WHERE `$cur_state` = 1";
			$adj = send_sql($sqlGetAdjacents, $link, $db);
			$adjacents = mysql_fetch_array($adj);
			$adjacentState = $adjacents[array_rand($adjacents)];
			
			$sqlUS = "UPDATE final SET cur_state = '$adjacentState' WHERE player_name = '$playername'";
			$US = send_sql($sqlUS, $link, $db);	
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
		}
		
		//cancellation4.PNG
		if($finalimage == $img9){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlGetAdjacents = "SELECT state FROM travelDistances WHERE `$cur_state` = 1";
			$adj = send_sql($sqlGetAdjacents, $link, $db);
			$adjacents = mysql_fetch_array($adj);
			$adjacentState = $adjacents[array_rand($adjacents)];
			
			$sqlUS = "UPDATE final SET cur_state = '$adjacentState' WHERE player_name = '$playername'";
			$US = send_sql($sqlUS, $link, $db);	
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//cancellation5.PNG
		if($finalimage == $img10){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlGetAdjacents = "SELECT state FROM travelDistances WHERE `$cur_state` = 1";
			$adj = send_sql($sqlGetAdjacents, $link, $db);
			$adjacents = mysql_fetch_array($adj);
			$adjacentState = $adjacents[array_rand($adjacents)];
			
			$sqlUS = "UPDATE final SET cur_state = '$adjacentState' WHERE player_name = '$playername'";
			$US = send_sql($sqlUS, $link, $db);	
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//cancellation6.PNG
		if($finalimage == $img11){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlGetAdjacents = "SELECT state FROM travelDistances WHERE `$cur_state` = 1";
			$adj = send_sql($sqlGetAdjacents, $link, $db);
			$adjacents = mysql_fetch_array($adj);
			$adjacentState = $adjacents[array_rand($adjacents)];
			
			$sqlUS = "UPDATE final SET cur_state = '$adjacentState' WHERE player_name = '$playername'";
			$US = send_sql($sqlUS, $link, $db);	
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//cancellation7.PNG
		if($finalimage == $img12){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlGetAdjacents = "SELECT state FROM travelDistances WHERE `$cur_state` = 1";
			$adj = send_sql($sqlGetAdjacents, $link, $db);
			$adjacents = mysql_fetch_array($adj);
			$adjacentState = $adjacents[array_rand($adjacents)];
			
			$sqlUS = "UPDATE final SET cur_state = '$adjacentState' WHERE player_name = '$playername'";
			$US = send_sql($sqlUS, $link, $db);	
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//cancellation8.PNG
		if($finalimage == $img13){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlGetAdjacents = "SELECT state FROM travelDistances WHERE `$cur_state` = 1";
			$adj = send_sql($sqlGetAdjacents, $link, $db);
			$adjacents = mysql_fetch_array($adj);
			$adjacentState = $adjacents[array_rand($adjacents)];
			
			$sqlUS = "UPDATE final SET cur_state = '$adjacentState' WHERE player_name = '$playername'";
			$US = send_sql($sqlUS, $link, $db);	
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//celebrity1.PNG
		if($finalimage == $img14){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlIL = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$IL = send_sql($sqlIL, $link, $db);
			
			$Influence= mysql_fetch_array($IL);
			$InLevel = $Influence[0];
			
			$newIL = $InLevel + 10;
			
			$sqlUD = "UPDATE $playername SET stateinfluence = $newIL WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went above 100
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence > 100){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 100 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
			
		}
		
		//celebrity2.PNG
		if($finalimage == $img15){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlIL = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$IL = send_sql($sqlIL, $link, $db);
			
			$Influence= mysql_fetch_array($IL);
			$InLevel = $Influence[0];
			
			$newIL = $InLevel + 10;
			
			$sqlUD = "UPDATE $playername SET stateinfluence = $newIL WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went above 100
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence > 100){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 100 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}

		//celebrity3.PNG
		if($finalimage == $img16){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlIL = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$IL = send_sql($sqlIL, $link, $db);
			
			$Influence= mysql_fetch_array($IL);
			$InLevel = $Influence[0];
			
			$newIL = $InLevel + 10;
			
			$sqlUD = "UPDATE $playername SET stateinfluence = $newIL WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went above 100
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence > 100){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 100 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//celebrity4.PNG
		if($finalimage == $img17){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlIL = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$IL = send_sql($sqlIL, $link, $db);
			
			$Influence= mysql_fetch_array($IL);
			$InLevel = $Influence[0];
			
			$newIL = $InLevel + 10;
			
			$sqlUD = "UPDATE $playername SET stateinfluence = $newIL WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went above 100
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence > 100){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 100 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//celebrity5.PNG
		if($finalimage == $img18){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlIL = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$IL = send_sql($sqlIL, $link, $db);
			
			$Influence= mysql_fetch_array($IL);
			$InLevel = $Influence[0];
			
			$newIL = $InLevel + 10;
			
			$sqlUD = "UPDATE $playername SET stateinfluence = $newIL WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went above 100
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence > 100){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 100 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//celebrity6.PNG
		if($finalimage == $img19){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlIL = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$IL = send_sql($sqlIL, $link, $db);
			
			$Influence= mysql_fetch_array($IL);
			$InLevel = $Influence[0];
			
			$newIL = $InLevel + 10;
			
			$sqlUD = "UPDATE $playername SET stateinfluence = $newIL WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went above 100
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence > 100){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 100 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//celebrity7.PNG
		if($finalimage == $img20){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlIL = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$IL = send_sql($sqlIL, $link, $db);
			
			$Influence= mysql_fetch_array($IL);
			$InLevel = $Influence[0];
			
			$newIL = $InLevel + 10;
			
			$sqlUD = "UPDATE $playername SET stateinfluence = $newIL WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			
			//Checking if the influence level went above 100
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence > 100){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 100 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//celebrity8.PNG
		if($finalimage == $img21){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlIL = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$IL = send_sql($sqlIL, $link, $db);
			
			$Influence= mysql_fetch_array($IL);
			$InLevel = $Influence[0];
			
			$newIL = $InLevel + 10;
			
			$sqlUD = "UPDATE $playername SET stateinfluence = $newIL WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went above 100
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence > 100){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 100 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//emergency1.PNG
		if($finalimage == $img22){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlHS = "SELECT home_state FROM final where player_name = '$playername'";
			$home_state = send_sql($sqlHS, $link, $db);
			
			$homestate = mysql_fetch_array($home_state);
			$HomeState = $homestate[0];
			
			$sqlDist = "SELECT $cur_state FROM travelDistances WHERE state = '$HomeState'";
			$distanceR = send_sql($sqlDist, $link, $db); 
			$distance = mysql_fetch_array($distanceR);
			$Distance = $distance[0];
			
			$travelCost = $Distance * 100 / 2;
			
			$sqlDon = "SELECT donations FROM final WHERE player_name = '$playername'";
			$don = send_sql($sqlDon, $link, $db);
			$curdonations = mysql_fetch_array($don);
			$curDonations = $curdonations[0];
			
			$newDonations = $curDonations - min($travelCost, $curDonations);
			
			$sqlUD = "UPDATE final SET donations = $newDonations WHERE player_name = '$playername'";
			
			$sqlUD = "UPDATE final SET cur_state = '$HomeState' WHERE player_name = '$playername'";
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);	
			
		}
		
		//emergency2.PNG
		if($finalimage == $img23){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlHS = "SELECT home_state FROM final where player_name = '$playername'";
			$home_state = send_sql($sqlHS, $link, $db);
			
			$homestate = mysql_fetch_array($home_state);
			$HomeState = $homestate[0];
			
			$sqlDist = "SELECT $cur_state FROM travelDistances WHERE state = '$HomeState'";
			$distanceR = send_sql($sqlDist, $link, $db); 
			$distance = mysql_fetch_array($distanceR);
			$Distance = $distance[0];
			
			$travelCost = $Distance * 100 / 2;
			
			$sqlDon = "SELECT donations FROM final WHERE player_name = '$playername'";
			$don = send_sql($sqlDon, $link, $db);
			$curdonations = mysql_fetch_array($don);
			$curDonations = $curdonations[0];
			
			$newDonations = $curDonations - min($travelCost, $curDonations);
			
			$sqlUD = "UPDATE final SET donations = $newDonations WHERE player_name = '$playername'";
			
			$sqlUD = "UPDATE final SET cur_state = '$HomeState' WHERE player_name = '$playername'";
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);	
			
		}
		
		//grassroots1.PNG
		if($finalimage == $img24){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			
			$NewLevel = $AdLevel + 2;
			
			$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = '$cur_state'"; 
			$result = send_sql($sqlUD, $link, $db);
			
			
			//If the current states ad level is greater than 10 set it to 10.
			if($NewLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
				
				
				
			
			
			//Calculating Adjacentcies
			
			//Alabama
			if($cur_state == "AL"){
				//florida, Georgia, Tennesse, Mississippi
				
				//Florida
				$sqlFL = "SELECT state_ad FROM $playername WHERE state_ab = 'FL'";
				$FL_Result = send_sql($sqlFL, $link, $db);
				$FL = mysql_fetch_array($FL_Result);
				
				$FL_Ad = $FL[0];
				$NewLevel = $FL_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'FL'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Georgia
				$sqlGA = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$GA_Result = send_sql($sqlGA, $link, $db);
				$GA = mysql_fetch_array($GA_Result);
				
				$GA_Ad = $GA[0];
				$NewLevel = $GA_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Tennesse
				$sqlTN = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$TN_Result = send_sql($sqlTN, $link, $db);
				$TN = mysql_fetch_array($TN_Result);
				
				$TN_Ad = $TN[0];
				$NewLevel = $TN_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Mississipi
				$sqlMS = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$MS_Result = send_sql($sqlMS, $link, $db);
				$MS = mysql_fetch_array($MS_Result);
				
				$MS_Ad = $MS[0];
				$NewLevel = $MS_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
		
			}
			
			//Alaska
			if($cur_state == "AK"){
				//Washington, Oregon, Idaho, Montana
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);				
				
				
			}
			
			//Arizona
			if($cur_state == "AZ"){
				//California, Nevada, Utah, Colorado, New Mexico
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Arkansas AR
			if($cur_state == "AR"){
				//Louisiana, Texas, Oklahoma, Missouri, Tennesse, Mississipi
				
				//Louisiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'LA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Mississippi
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//California
			if($cur_state == "CA"){
				//Hawaii, Arizona, Nevada, Oregon
				
				//Hawaii
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'HI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Colorado
			if($cur_state == "CO"){
				//New Mexico, Arizona, Utah, Wyoming, Nebraska, Kansas, Oklahoma
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NM'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				
			}
			
			//Connecticut CT
			if($cur_state == "CT"){
				//New York, Rhode Island, Massachusetts
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Rhode Island
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'RI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'RI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachusetts
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Delaware
			if($cur_state == "DE"){
				//Maryland, Pennsylvania, New Jersey
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennsylvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Jersey
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NJ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Florida
			if($cur_state == "FL"){
				//Alabama, Georgia
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Georgia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			
			//Georgia
			if($cur_state == "GA"){
				//Florida, Alabama, Tennesse, North Carolina, South Carolina
				
				//Florida
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'FL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'FL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//South Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Hawaii
			if($cur_state == "HI"){
				//California, Oregon, Washington
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Idaho
			if($cur_state == "ID"){
				//Utah, Nevada, Oregon, Washington, Alaska, Montana, Wyoming
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Illinois
			if($cur_state == "IL"){
				//Iowa, Wisconsin, Indiana , Kentucky, Missouri
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Indiana
			if($cur_state == "IN"){
				//Illinois, Michigan, Ohio, Kentucky
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);
					
			}
			
			//Iowa
			if($cur_state == "IA"){
				//Minnesota, Wisconsin, Illinois, Missouri, Nebraska, South Dakota
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Kansas
			if($cur_state == "KS"){
				//Oklahoma, Colorado, Nebraska, Missouri
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Kentucky
			if($cur_state == "KY"){
				//Tennesse, Missouri, Illinois, Indiana, Ohio, West Virgina, Virgina
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Louisiana
			if($cur_state == "LA"){
				//Texas, Arkansas, Mississippi
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Mississippi
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Maine
			if($cur_state == "ME"){
				//New Hampshire
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Maryland
			if($cur_state == "MD"){
				//Pensylvania, Delaware, Virgina, West Virgina
				
				//Penslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Delaware
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'DE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Massachusetts
			if($cur_state == "MA"){
				//New Hampshire, Vermont, New York, Connecticut, Rhode Island
				
				//New Hampshire
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NH'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Vermont
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Conneticut
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CT'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				//Rhode Island
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'RI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'RI'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//Michigan
			if($cur_state == "MI"){
				//Ohio, Indiana, Wisconsin
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	 			
				
				}
			//Minnesota
			if($cur_state == "MN"){
				//Iowa, South Dakota, North Dakota, Wisconsin
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ND'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Mississippi
			if($cur_state == "MS"){
				//Louisianna, Arkansas, Tennesse, Alabama
				
				//Louisianna
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'LA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Missouri
			if($cur_state == "MO"){
				//Arkansas, Oklahoma, Kansas, Nebraska, Iowa, Illinois, Kentucky, Tennesse
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Montana
			if($cur_state == "MT"){
				//Alaska, Idaho, Wyoming, South Dakota, North Dakota
				
				//Alaska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ND'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Nebraska
			if($cur_state == "NE"){
				//Kansas, Colorado, Wyoming, South Dakota, Iowa, Missouri
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Nevada
			if($cur_state == "NV"){
				//Arizona, California, Oregon, Idaho, Utah
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//New Hampshire
			if($cur_state == "NH"){
				//Vermont, Michigan, Maine
				
				//Vermont
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Maine
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ME'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ME'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//New Jersey
			if($cur_state == "NJ"){
				//Delaware, Pennslyvania, New York,
				
				//Delaware
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'DE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//New Mexico
			if($cur_state == "NM"){
				//Arizona, Colorado, Oklahoma, Texas
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				
			}
			
			
			//New York
			if($cur_state == "NY"){
				//Vermont, Massachuesetts, Conneticut, New Jersey, Pennslavania
				
				//Vermont
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachuesetts
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Conneticut
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Jersey
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NJ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				
			}
			
			//North Carolina
			if($cur_state == "NC"){
				//South Carolina, Gerogia, Tennesse, Virgina
				
				//South Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Gerogia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//North Dakota
			if($cur_state == "ND"){
				//Minnesota, South Dakota, Montana
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Ohio
			if($cur_state == "OH"){
				//Kentucky, Indiana,Michigan, Penslyvania, West Virgina
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	  
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Oklahoma
			if($cur_state == "OK"){
				//Texas, New Mexico, Colorado, Kansas, Missouri, Arkansas
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NM'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);			
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Oregon
			if($cur_state == "OR"){
				//Hawaii, Alaska, Washington Idaho, Nevada, California
				
				//Hawaii
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'HI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alaska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Pennsylvania
			if($cur_state == "PA"){
				//New York, New Jersey, Delaware, Maryland, West Virgina, Ohio
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Jersey
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NJ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Delaware
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'DE'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);		
					
			}
			
			//Rhode Island
			if($cur_state == "RI"){
				//Conneticut, Massachusetts
				
				//Conneticut
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachusetts
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//South Carolina
			if($cur_state == "SC"){
				//Gerogia, North Carolina
				
				//Gerogia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//South Dakota
			if($cur_state == "SD"){
				//Nebraska, Wyoming, Montana, North Dakota, Minnesota, Iowa
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ND'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	 
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Tennessee
			if($cur_state == "TN"){
				//Georgia, Alabama, Mississippi, Arkansas, Missouri, Kentucky, Virgina, North Carolina
				
				//Georgia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Mississippi
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
						
			}
			
			//Texas
			if($cur_state == "TX"){
				//New Mexico, Oklahoma, Louisiana
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NM'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Louisiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'LA'"; 		
				$result = send_sql($sqlUD, $link, $db);			
				
			}
			
			//Utah
			if($cur_state == "UT"){
				//Arizona, Nevada, Idaho, Wyoming, Colorado
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
					
				
			}
			
			//Vermont
			if($cur_state == "VT"){
				//New Hampshire, Massachusets, New York
				
				//New Hampshire
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachuesets
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Virgina
			if($cur_state == "VA"){
				//North Carolina, Tennessee, Kentucky, West Virgina, Maryland
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennessee
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//Washington
			if($cur_state == "WA"){
				//Alaska, Hawaii, Idaho, Oregon
				
				//Alaska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Hawaii
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'HI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//West Virgina
			if($cur_state == "WV"){
				//Virgina, Kentucky, Ohio, Pennsylvania, Maryland
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Pensylvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//Wisconsin
			if($cur_state == "WI"){
				//Illinois, Iowa, Minnesota, Michigan
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);			
				
			}
			
			//Wyoming
			if($cur_state == "WY"){
				//Colorado, Utah, Idaho, Montana, South Dakota, Nebraska
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				
			}
			
			//Checking 50 states if the ad level is over 10
			
			//Alabama
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AL'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Alaska
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AK'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Arizona
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AZ'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Arkansas
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AR'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//California
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'CA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Colorado
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'CO'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Conneticut
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'CT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Delaware
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'DE'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Florida
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'FL'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'FL'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Georgia
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'GA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Hawaii
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'HI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Idaho
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'ID'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Illinois
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'IL'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Indiana
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'IN'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Iowa
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'IA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Kansas
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'KS'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Kentucky
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'KY'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Louisiana
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'LA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Maine
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'ME'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'ME'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Maryland
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MD'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Massachusetts
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Michigan
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Minnesota
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MN'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Mississippi
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MS'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Missouri
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MO'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Montana
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Nebraska
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NE'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Nevada
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NV'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New Hampshire
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NH'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New Jersey
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NJ'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New Mexico
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NM'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New York
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NY'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//North Carolina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NC'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//North Dakota
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'ND'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Ohio
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'OH'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Oklahoma
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'OK'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Oregon
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'OR'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Pennsylvania
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'PA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Rhode Island
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'RI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'RI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//South Carolina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'SC'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'SC'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//South Dakota
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'SD'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Tennessee
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'TN'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Texas
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'TX'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Utah
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'UT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Vermont
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'VT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Virgina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'VA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Washington
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//West Virgina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WV'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Wisconsin
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Wyoming
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WY'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
		
			
		}
		
		//grassroots2.PNG
		if($finalimage == $img25){
			
						$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$NewLevel = $AdLevel + 2;
			
			$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = '$cur_state'"; 
			$result = send_sql($sqlUD, $link, $db);
			
			
			//Calculating Adjacentcies
			
			//Alabama
			if($cur_state == "AL"){
				//florida, Georgia, Tennesse, Mississippi
				
				//Florida
				$sqlFL = "SELECT state_ad FROM $playername WHERE state_ab = 'FL'";
				$FL_Result = send_sql($sqlFL, $link, $db);
				$FL = mysql_fetch_array($FL_Result);
				
				$FL_Ad = $FL[0];
				$NewLevel = $FL_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'FL'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Georgia
				$sqlGA = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$GA_Result = send_sql($sqlGA, $link, $db);
				$GA = mysql_fetch_array($GA_Result);
				
				$GA_Ad = $GA[0];
				$NewLevel = $GA_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Tennesse
				$sqlTN = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$TN_Result = send_sql($sqlTN, $link, $db);
				$TN = mysql_fetch_array($TN_Result);
				
				$TN_Ad = $TN[0];
				$NewLevel = $TN_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Mississipi
				$sqlMS = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$MS_Result = send_sql($sqlMS, $link, $db);
				$MS = mysql_fetch_array($MS_Result);
				
				$MS_Ad = $MS[0];
				$NewLevel = $MS_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
		
			}
			
			//Alaska
			if($cur_state == "AK"){
				//Washington, Oregon, Idaho, Montana
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);				
				
				
			}
			
			//Arizona
			if($cur_state == "AZ"){
				//California, Nevada, Utah, Colorado, New Mexico
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Arkansas AR
			if($cur_state == "AR"){
				//Louisiana, Texas, Oklahoma, Missouri, Tennesse, Mississipi
				
				//Louisiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'LA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Mississippi
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//California
			if($cur_state == "CA"){
				//Hawaii, Arizona, Nevada, Oregon
				
				//Hawaii
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'HI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Colorado
			if($cur_state == "CO"){
				//New Mexico, Arizona, Utah, Wyoming, Nebraska, Kansas, Oklahoma
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NM'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				
			}
			
			//Connecticut CT
			if($cur_state == "CT"){
				//New York, Rhode Island, Massachusetts
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Rhode Island
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'RI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'RI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachusetts
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Delaware
			if($cur_state == "DE"){
				//Maryland, Pennsylvania, New Jersey
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennsylvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Jersey
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NJ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Florida
			if($cur_state == "FL"){
				//Alabama, Georgia
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Georgia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			
			//Georgia
			if($cur_state == "GA"){
				//Florida, Alabama, Tennesse, North Carolina, South Carolina
				
				//Florida
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'FL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'FL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//South Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Hawaii
			if($cur_state == "HI"){
				//California, Oregon, Washington
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Idaho
			if($cur_state == "ID"){
				//Utah, Nevada, Oregon, Washington, Alaska, Montana, Wyoming
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Illinois
			if($cur_state == "IL"){
				//Iowa, Wisconsin, Indiana , Kentucky, Missouri
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Indiana
			if($cur_state == "IN"){
				//Illinois, Michigan, Ohio, Kentucky
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);
					
			}
			
			//Iowa
			if($cur_state == "IA"){
				//Minnesota, Wisconsin, Illinois, Missouri, Nebraska, South Dakota
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Kansas
			if($cur_state == "KS"){
				//Oklahoma, Colorado, Nebraska, Missouri
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Kentucky
			if($cur_state == "KY"){
				//Tennesse, Missouri, Illinois, Indiana, Ohio, West Virgina, Virgina
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Louisiana
			if($cur_state == "LA"){
				//Texas, Arkansas, Mississippi
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Mississippi
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Maine
			if($cur_state == "ME"){
				//New Hampshire
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Maryland
			if($cur_state == "MD"){
				//Pensylvania, Delaware, Virgina, West Virgina
				
				//Penslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Delaware
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'DE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Massachusetts
			if($cur_state == "MA"){
				//New Hampshire, Vermont, New York, Connecticut, Rhode Island
				
				//New Hampshire
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NH'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Vermont
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Conneticut
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CT'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				//Rhode Island
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'RI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'RI'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//Michigan
			if($cur_state == "MI"){
				//Ohio, Indiana, Wisconsin
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	 			
				
				}
			//Minnesota
			if($cur_state == "MN"){
				//Iowa, South Dakota, North Dakota, Wisconsin
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ND'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Mississippi
			if($cur_state == "MS"){
				//Louisianna, Arkansas, Tennesse, Alabama
				
				//Louisianna
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'LA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Missouri
			if($cur_state == "MO"){
				//Arkansas, Oklahoma, Kansas, Nebraska, Iowa, Illinois, Kentucky, Tennesse
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Montana
			if($cur_state == "MT"){
				//Alaska, Idaho, Wyoming, South Dakota, North Dakota
				
				//Alaska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ND'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Nebraska
			if($cur_state == "NE"){
				//Kansas, Colorado, Wyoming, South Dakota, Iowa, Missouri
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Nevada
			if($cur_state == "NV"){
				//Arizona, California, Oregon, Idaho, Utah
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//New Hampshire
			if($cur_state == "NH"){
				//Vermont, Michigan, Maine
				
				//Vermont
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Maine
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ME'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ME'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//New Jersey
			if($cur_state == "NJ"){
				//Delaware, Pennslyvania, New York,
				
				//Delaware
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'DE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//New Mexico
			if($cur_state == "NM"){
				//Arizona, Colorado, Oklahoma, Texas
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				
			}
			
			
			//New York
			if($cur_state == "NY"){
				//Vermont, Massachuesetts, Conneticut, New Jersey, Pennslavania
				
				//Vermont
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachuesetts
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Conneticut
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Jersey
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NJ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				
			}
			
			//North Carolina
			if($cur_state == "NC"){
				//South Carolina, Gerogia, Tennesse, Virgina
				
				//South Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Gerogia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//North Dakota
			if($cur_state == "ND"){
				//Minnesota, South Dakota, Montana
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Ohio
			if($cur_state == "OH"){
				//Kentucky, Indiana,Michigan, Penslyvania, West Virgina
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	  
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Oklahoma
			if($cur_state == "OK"){
				//Texas, New Mexico, Colorado, Kansas, Missouri, Arkansas
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NM'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);			
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Oregon
			if($cur_state == "OR"){
				//Hawaii, Alaska, Washington Idaho, Nevada, California
				
				//Hawaii
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'HI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alaska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Pennsylvania
			if($cur_state == "PA"){
				//New York, New Jersey, Delaware, Maryland, West Virgina, Ohio
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Jersey
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NJ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Delaware
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'DE'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);		
					
			}
			
			//Rhode Island
			if($cur_state == "RI"){
				//Conneticut, Massachusetts
				
				//Conneticut
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachusetts
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//South Carolina
			if($cur_state == "SC"){
				//Gerogia, North Carolina
				
				//Gerogia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//South Dakota
			if($cur_state == "SD"){
				//Nebraska, Wyoming, Montana, North Dakota, Minnesota, Iowa
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ND'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	 
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Tennessee
			if($cur_state == "TN"){
				//Georgia, Alabama, Mississippi, Arkansas, Missouri, Kentucky, Virgina, North Carolina
				
				//Georgia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Mississippi
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
						
			}
			
			//Texas
			if($cur_state == "TX"){
				//New Mexico, Oklahoma, Louisiana
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NM'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Louisiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'LA'"; 		
				$result = send_sql($sqlUD, $link, $db);			
				
			}
			
			//Utah
			if($cur_state == "UT"){
				//Arizona, Nevada, Idaho, Wyoming, Colorado
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
					
				
			}
			
			//Vermont
			if($cur_state == "VT"){
				//New Hampshire, Massachusets, New York
				
				//New Hampshire
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachuesets
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Virgina
			if($cur_state == "VA"){
				//North Carolina, Tennessee, Kentucky, West Virgina, Maryland
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennessee
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//Washington
			if($cur_state == "WA"){
				//Alaska, Hawaii, Idaho, Oregon
				
				//Alaska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Hawaii
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'HI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//West Virgina
			if($cur_state == "WV"){
				//Virgina, Kentucky, Ohio, Pennsylvania, Maryland
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Pensylvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//Wisconsin
			if($cur_state == "WI"){
				//Illinois, Iowa, Minnesota, Michigan
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);			
				
			}
			
			//Wyoming
			if($cur_state == "WY"){
				//Colorado, Utah, Idaho, Montana, South Dakota, Nebraska
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				
			}
			
			//Checking 50 states if the ad level is over 10
			
			//Alabama
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AL'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Alaska
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AK'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Arizona
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AZ'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Arkansas
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AR'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//California
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'CA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Colorado
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'CO'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Conneticut
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'CT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Delaware
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'DE'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Florida
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'FL'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'FL'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Georgia
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'GA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Hawaii
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'HI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Idaho
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'ID'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Illinois
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'IL'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Indiana
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'IN'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Iowa
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'IA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Kansas
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'KS'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Kentucky
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'KY'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Louisiana
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'LA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Maine
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'ME'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'ME'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Maryland
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MD'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Massachusetts
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Michigan
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Minnesota
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MN'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Mississippi
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MS'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Missouri
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MO'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Montana
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Nebraska
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NE'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Nevada
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NV'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New Hampshire
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NH'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New Jersey
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NJ'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New Mexico
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NM'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New York
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NY'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//North Carolina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NC'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//North Dakota
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'ND'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Ohio
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'OH'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Oklahoma
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'OK'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Oregon
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'OR'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Pennsylvania
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'PA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Rhode Island
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'RI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'RI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//South Carolina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'SC'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'SC'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//South Dakota
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'SD'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Tennessee
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'TN'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Texas
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'TX'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Utah
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'UT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Vermont
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'VT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Virgina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'VA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Washington
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//West Virgina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WV'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Wisconsin
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Wyoming
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WY'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//grassroots3.PNG
		if($finalimage == $img26){
			
						$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$NewLevel = $AdLevel + 2;
			
			$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = '$cur_state'"; 
			$result = send_sql($sqlUD, $link, $db);
			
			
			//Calculating Adjacentcies
			
			//Alabama
			if($cur_state == "AL"){
				//florida, Georgia, Tennesse, Mississippi
				
				//Florida
				$sqlFL = "SELECT state_ad FROM $playername WHERE state_ab = 'FL'";
				$FL_Result = send_sql($sqlFL, $link, $db);
				$FL = mysql_fetch_array($FL_Result);
				
				$FL_Ad = $FL[0];
				$NewLevel = $FL_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'FL'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Georgia
				$sqlGA = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$GA_Result = send_sql($sqlGA, $link, $db);
				$GA = mysql_fetch_array($GA_Result);
				
				$GA_Ad = $GA[0];
				$NewLevel = $GA_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Tennesse
				$sqlTN = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$TN_Result = send_sql($sqlTN, $link, $db);
				$TN = mysql_fetch_array($TN_Result);
				
				$TN_Ad = $TN[0];
				$NewLevel = $TN_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Mississipi
				$sqlMS = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$MS_Result = send_sql($sqlMS, $link, $db);
				$MS = mysql_fetch_array($MS_Result);
				
				$MS_Ad = $MS[0];
				$NewLevel = $MS_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
		
			}
			
			//Alaska
			if($cur_state == "AK"){
				//Washington, Oregon, Idaho, Montana
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);				
				
				
			}
			
			//Arizona
			if($cur_state == "AZ"){
				//California, Nevada, Utah, Colorado, New Mexico
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Arkansas AR
			if($cur_state == "AR"){
				//Louisiana, Texas, Oklahoma, Missouri, Tennesse, Mississipi
				
				//Louisiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'LA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Mississippi
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//California
			if($cur_state == "CA"){
				//Hawaii, Arizona, Nevada, Oregon
				
				//Hawaii
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'HI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Colorado
			if($cur_state == "CO"){
				//New Mexico, Arizona, Utah, Wyoming, Nebraska, Kansas, Oklahoma
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NM'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				
			}
			
			//Connecticut CT
			if($cur_state == "CT"){
				//New York, Rhode Island, Massachusetts
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Rhode Island
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'RI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'RI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachusetts
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Delaware
			if($cur_state == "DE"){
				//Maryland, Pennsylvania, New Jersey
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennsylvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Jersey
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NJ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Florida
			if($cur_state == "FL"){
				//Alabama, Georgia
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Georgia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			
			//Georgia
			if($cur_state == "GA"){
				//Florida, Alabama, Tennesse, North Carolina, South Carolina
				
				//Florida
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'FL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'FL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//South Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Hawaii
			if($cur_state == "HI"){
				//California, Oregon, Washington
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Idaho
			if($cur_state == "ID"){
				//Utah, Nevada, Oregon, Washington, Alaska, Montana, Wyoming
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Illinois
			if($cur_state == "IL"){
				//Iowa, Wisconsin, Indiana , Kentucky, Missouri
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Indiana
			if($cur_state == "IN"){
				//Illinois, Michigan, Ohio, Kentucky
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);
					
			}
			
			//Iowa
			if($cur_state == "IA"){
				//Minnesota, Wisconsin, Illinois, Missouri, Nebraska, South Dakota
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Kansas
			if($cur_state == "KS"){
				//Oklahoma, Colorado, Nebraska, Missouri
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Kentucky
			if($cur_state == "KY"){
				//Tennesse, Missouri, Illinois, Indiana, Ohio, West Virgina, Virgina
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Louisiana
			if($cur_state == "LA"){
				//Texas, Arkansas, Mississippi
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Mississippi
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Maine
			if($cur_state == "ME"){
				//New Hampshire
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Maryland
			if($cur_state == "MD"){
				//Pensylvania, Delaware, Virgina, West Virgina
				
				//Penslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Delaware
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'DE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Massachusetts
			if($cur_state == "MA"){
				//New Hampshire, Vermont, New York, Connecticut, Rhode Island
				
				//New Hampshire
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NH'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Vermont
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Conneticut
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CT'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				//Rhode Island
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'RI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'RI'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//Michigan
			if($cur_state == "MI"){
				//Ohio, Indiana, Wisconsin
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	 			
				
				}
			//Minnesota
			if($cur_state == "MN"){
				//Iowa, South Dakota, North Dakota, Wisconsin
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ND'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Mississippi
			if($cur_state == "MS"){
				//Louisianna, Arkansas, Tennesse, Alabama
				
				//Louisianna
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'LA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Missouri
			if($cur_state == "MO"){
				//Arkansas, Oklahoma, Kansas, Nebraska, Iowa, Illinois, Kentucky, Tennesse
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Montana
			if($cur_state == "MT"){
				//Alaska, Idaho, Wyoming, South Dakota, North Dakota
				
				//Alaska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ND'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Nebraska
			if($cur_state == "NE"){
				//Kansas, Colorado, Wyoming, South Dakota, Iowa, Missouri
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Nevada
			if($cur_state == "NV"){
				//Arizona, California, Oregon, Idaho, Utah
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//New Hampshire
			if($cur_state == "NH"){
				//Vermont, Michigan, Maine
				
				//Vermont
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Maine
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ME'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ME'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//New Jersey
			if($cur_state == "NJ"){
				//Delaware, Pennslyvania, New York,
				
				//Delaware
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'DE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//New Mexico
			if($cur_state == "NM"){
				//Arizona, Colorado, Oklahoma, Texas
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				
			}
			
			
			//New York
			if($cur_state == "NY"){
				//Vermont, Massachuesetts, Conneticut, New Jersey, Pennslavania
				
				//Vermont
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachuesetts
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Conneticut
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Jersey
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NJ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				
			}
			
			//North Carolina
			if($cur_state == "NC"){
				//South Carolina, Gerogia, Tennesse, Virgina
				
				//South Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Gerogia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//North Dakota
			if($cur_state == "ND"){
				//Minnesota, South Dakota, Montana
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Ohio
			if($cur_state == "OH"){
				//Kentucky, Indiana,Michigan, Penslyvania, West Virgina
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	  
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Oklahoma
			if($cur_state == "OK"){
				//Texas, New Mexico, Colorado, Kansas, Missouri, Arkansas
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NM'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);			
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Oregon
			if($cur_state == "OR"){
				//Hawaii, Alaska, Washington Idaho, Nevada, California
				
				//Hawaii
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'HI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alaska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Pennsylvania
			if($cur_state == "PA"){
				//New York, New Jersey, Delaware, Maryland, West Virgina, Ohio
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Jersey
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NJ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Delaware
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'DE'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);		
					
			}
			
			//Rhode Island
			if($cur_state == "RI"){
				//Conneticut, Massachusetts
				
				//Conneticut
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachusetts
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//South Carolina
			if($cur_state == "SC"){
				//Gerogia, North Carolina
				
				//Gerogia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//South Dakota
			if($cur_state == "SD"){
				//Nebraska, Wyoming, Montana, North Dakota, Minnesota, Iowa
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ND'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	 
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Tennessee
			if($cur_state == "TN"){
				//Georgia, Alabama, Mississippi, Arkansas, Missouri, Kentucky, Virgina, North Carolina
				
				//Georgia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Mississippi
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
						
			}
			
			//Texas
			if($cur_state == "TX"){
				//New Mexico, Oklahoma, Louisiana
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NM'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Louisiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'LA'"; 		
				$result = send_sql($sqlUD, $link, $db);			
				
			}
			
			//Utah
			if($cur_state == "UT"){
				//Arizona, Nevada, Idaho, Wyoming, Colorado
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
					
				
			}
			
			//Vermont
			if($cur_state == "VT"){
				//New Hampshire, Massachusets, New York
				
				//New Hampshire
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachuesets
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Virgina
			if($cur_state == "VA"){
				//North Carolina, Tennessee, Kentucky, West Virgina, Maryland
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennessee
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//Washington
			if($cur_state == "WA"){
				//Alaska, Hawaii, Idaho, Oregon
				
				//Alaska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Hawaii
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'HI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//West Virgina
			if($cur_state == "WV"){
				//Virgina, Kentucky, Ohio, Pennsylvania, Maryland
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Pensylvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//Wisconsin
			if($cur_state == "WI"){
				//Illinois, Iowa, Minnesota, Michigan
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);			
				
			}
			
			//Wyoming
			if($cur_state == "WY"){
				//Colorado, Utah, Idaho, Montana, South Dakota, Nebraska
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				
			}
			
			//Checking 50 states if the ad level is over 10
			
			//Alabama
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AL'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Alaska
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AK'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Arizona
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AZ'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Arkansas
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AR'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//California
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'CA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Colorado
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'CO'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Conneticut
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'CT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Delaware
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'DE'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Florida
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'FL'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'FL'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Georgia
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'GA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Hawaii
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'HI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Idaho
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'ID'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Illinois
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'IL'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Indiana
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'IN'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Iowa
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'IA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Kansas
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'KS'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Kentucky
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'KY'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Louisiana
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'LA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Maine
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'ME'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'ME'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Maryland
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MD'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Massachusetts
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Michigan
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Minnesota
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MN'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Mississippi
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MS'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Missouri
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MO'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Montana
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Nebraska
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NE'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Nevada
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NV'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New Hampshire
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NH'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New Jersey
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NJ'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New Mexico
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NM'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New York
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NY'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//North Carolina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NC'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//North Dakota
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'ND'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Ohio
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'OH'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Oklahoma
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'OK'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Oregon
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'OR'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Pennsylvania
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'PA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Rhode Island
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'RI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'RI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//South Carolina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'SC'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'SC'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//South Dakota
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'SD'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Tennessee
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'TN'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Texas
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'TX'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Utah
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'UT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Vermont
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'VT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Virgina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'VA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Washington
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//West Virgina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WV'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Wisconsin
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Wyoming
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WY'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//grassroots4.PNG
		if($finalimage == $img27){
			
						$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$NewLevel = $AdLevel + 2;
			
			$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = '$cur_state'"; 
			$result = send_sql($sqlUD, $link, $db);
			
			
			//Calculating Adjacentcies
			
			//Alabama
			if($cur_state == "AL"){
				//florida, Georgia, Tennesse, Mississippi
				
				//Florida
				$sqlFL = "SELECT state_ad FROM $playername WHERE state_ab = 'FL'";
				$FL_Result = send_sql($sqlFL, $link, $db);
				$FL = mysql_fetch_array($FL_Result);
				
				$FL_Ad = $FL[0];
				$NewLevel = $FL_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'FL'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Georgia
				$sqlGA = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$GA_Result = send_sql($sqlGA, $link, $db);
				$GA = mysql_fetch_array($GA_Result);
				
				$GA_Ad = $GA[0];
				$NewLevel = $GA_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Tennesse
				$sqlTN = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$TN_Result = send_sql($sqlTN, $link, $db);
				$TN = mysql_fetch_array($TN_Result);
				
				$TN_Ad = $TN[0];
				$NewLevel = $TN_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Mississipi
				$sqlMS = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$MS_Result = send_sql($sqlMS, $link, $db);
				$MS = mysql_fetch_array($MS_Result);
				
				$MS_Ad = $MS[0];
				$NewLevel = $MS_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
		
			}
			
			//Alaska
			if($cur_state == "AK"){
				//Washington, Oregon, Idaho, Montana
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);				
				
				
			}
			
			//Arizona
			if($cur_state == "AZ"){
				//California, Nevada, Utah, Colorado, New Mexico
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Arkansas AR
			if($cur_state == "AR"){
				//Louisiana, Texas, Oklahoma, Missouri, Tennesse, Mississipi
				
				//Louisiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'LA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Mississippi
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//California
			if($cur_state == "CA"){
				//Hawaii, Arizona, Nevada, Oregon
				
				//Hawaii
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'HI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Colorado
			if($cur_state == "CO"){
				//New Mexico, Arizona, Utah, Wyoming, Nebraska, Kansas, Oklahoma
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NM'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				
			}
			
			//Connecticut CT
			if($cur_state == "CT"){
				//New York, Rhode Island, Massachusetts
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Rhode Island
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'RI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'RI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachusetts
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Delaware
			if($cur_state == "DE"){
				//Maryland, Pennsylvania, New Jersey
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennsylvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Jersey
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NJ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Florida
			if($cur_state == "FL"){
				//Alabama, Georgia
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Georgia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			
			//Georgia
			if($cur_state == "GA"){
				//Florida, Alabama, Tennesse, North Carolina, South Carolina
				
				//Florida
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'FL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'FL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//South Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Hawaii
			if($cur_state == "HI"){
				//California, Oregon, Washington
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Idaho
			if($cur_state == "ID"){
				//Utah, Nevada, Oregon, Washington, Alaska, Montana, Wyoming
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Illinois
			if($cur_state == "IL"){
				//Iowa, Wisconsin, Indiana , Kentucky, Missouri
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Indiana
			if($cur_state == "IN"){
				//Illinois, Michigan, Ohio, Kentucky
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);
					
			}
			
			//Iowa
			if($cur_state == "IA"){
				//Minnesota, Wisconsin, Illinois, Missouri, Nebraska, South Dakota
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Kansas
			if($cur_state == "KS"){
				//Oklahoma, Colorado, Nebraska, Missouri
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Kentucky
			if($cur_state == "KY"){
				//Tennesse, Missouri, Illinois, Indiana, Ohio, West Virgina, Virgina
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Louisiana
			if($cur_state == "LA"){
				//Texas, Arkansas, Mississippi
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Mississippi
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Maine
			if($cur_state == "ME"){
				//New Hampshire
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Maryland
			if($cur_state == "MD"){
				//Pensylvania, Delaware, Virgina, West Virgina
				
				//Penslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Delaware
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'DE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Massachusetts
			if($cur_state == "MA"){
				//New Hampshire, Vermont, New York, Connecticut, Rhode Island
				
				//New Hampshire
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NH'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Vermont
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Conneticut
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CT'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				//Rhode Island
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'RI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'RI'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//Michigan
			if($cur_state == "MI"){
				//Ohio, Indiana, Wisconsin
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	 			
				
				}
			//Minnesota
			if($cur_state == "MN"){
				//Iowa, South Dakota, North Dakota, Wisconsin
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ND'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				//Wisconsin
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Mississippi
			if($cur_state == "MS"){
				//Louisianna, Arkansas, Tennesse, Alabama
				
				//Louisianna
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'LA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Missouri
			if($cur_state == "MO"){
				//Arkansas, Oklahoma, Kansas, Nebraska, Iowa, Illinois, Kentucky, Tennesse
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Montana
			if($cur_state == "MT"){
				//Alaska, Idaho, Wyoming, South Dakota, North Dakota
				
				//Alaska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ND'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Nebraska
			if($cur_state == "NE"){
				//Kansas, Colorado, Wyoming, South Dakota, Iowa, Missouri
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);		
			}
			
			//Nevada
			if($cur_state == "NV"){
				//Arizona, California, Oregon, Idaho, Utah
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//New Hampshire
			if($cur_state == "NH"){
				//Vermont, Michigan, Maine
				
				//Vermont
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Maine
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ME'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ME'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//New Jersey
			if($cur_state == "NJ"){
				//Delaware, Pennslyvania, New York,
				
				//Delaware
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'DE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//New Mexico
			if($cur_state == "NM"){
				//Arizona, Colorado, Oklahoma, Texas
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				
			}
			
			
			//New York
			if($cur_state == "NY"){
				//Vermont, Massachuesetts, Conneticut, New Jersey, Pennslavania
				
				//Vermont
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachuesetts
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Conneticut
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Jersey
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NJ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				
			}
			
			//North Carolina
			if($cur_state == "NC"){
				//South Carolina, Gerogia, Tennesse, Virgina
				
				//South Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Gerogia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennesse
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//North Dakota
			if($cur_state == "ND"){
				//Minnesota, South Dakota, Montana
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Ohio
			if($cur_state == "OH"){
				//Kentucky, Indiana,Michigan, Penslyvania, West Virgina
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	  
				
				//Indiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Pennslyvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Oklahoma
			if($cur_state == "OK"){
				//Texas, New Mexico, Colorado, Kansas, Missouri, Arkansas
				
				//Texas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TX'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NM'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Kansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KS'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);			
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
			}
			
			//Oregon
			if($cur_state == "OR"){
				//Hawaii, Alaska, Washington Idaho, Nevada, California
				
				//Hawaii
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'HI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alaska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Washington
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//California
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Pennsylvania
			if($cur_state == "PA"){
				//New York, New Jersey, Delaware, Maryland, West Virgina, Ohio
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New Jersey
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NJ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Delaware
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'DE'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);		
					
			}
			
			//Rhode Island
			if($cur_state == "RI"){
				//Conneticut, Massachusetts
				
				//Conneticut
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachusetts
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//South Carolina
			if($cur_state == "SC"){
				//Gerogia, North Carolina
				
				//Gerogia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//South Dakota
			if($cur_state == "SD"){
				//Nebraska, Wyoming, Montana, North Dakota, Minnesota, Iowa
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ND'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	 
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Tennessee
			if($cur_state == "TN"){
				//Georgia, Alabama, Mississippi, Arkansas, Missouri, Kentucky, Virgina, North Carolina
				
				//Georgia
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'GA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Alabama
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AL'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Mississippi
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MS'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Arkansas
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Missouri
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
						
			}
			
			//Texas
			if($cur_state == "TX"){
				//New Mexico, Oklahoma, Louisiana
				
				//New Mexico
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NM'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Oklahoma
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OK'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Louisiana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'LA'"; 		
				$result = send_sql($sqlUD, $link, $db);			
				
			}
			
			//Utah
			if($cur_state == "UT"){
				//Arizona, Nevada, Idaho, Wyoming, Colorado
				
				//Arizona
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AZ'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nevada
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Wyoming
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
					
				
			}
			
			//Vermont
			if($cur_state == "VT"){
				//New Hampshire, Massachusets, New York
				
				//New Hampshire
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NH'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Massachuesets
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//New York
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//Virgina
			if($cur_state == "VA"){
				//North Carolina, Tennessee, Kentucky, West Virgina, Maryland
				
				//North Carolina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NC'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Tennessee
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'TN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//West Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'WV'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//Washington
			if($cur_state == "WA"){
				//Alaska, Hawaii, Idaho, Oregon
				
				//Alaska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'AK'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Hawaii
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'HI'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Oregon
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OR'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
			}
			
			//West Virgina
			if($cur_state == "WV"){
				//Virgina, Kentucky, Ohio, Pennsylvania, Maryland
				
				//Virgina
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'VA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Kentucky
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'KY'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Ohio
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'OH'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Pensylvania
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'PA'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Maryland
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MD'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
			}
			
			//Wisconsin
			if($cur_state == "WI"){
				//Illinois, Iowa, Minnesota, Michigan
				
				//Illinois
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IL'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Iowa
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'IA'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//Minnesota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MN'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Michigan
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MI'"; 		
				$result = send_sql($sqlUD, $link, $db);			
				
			}
			
			//Wyoming
			if($cur_state == "WY"){
				//Colorado, Utah, Idaho, Montana, South Dakota, Nebraska
				
				//Colorado
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'CO'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Utah
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'UT'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Idaho
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'ID'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Montana
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'MT'"; 		
				$result = send_sql($sqlUD, $link, $db);
				
				//South Dakota
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'SD'"; 		
				$result = send_sql($sqlUD, $link, $db);	
				
				//Nebraska
				$sql = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
				$State_Result = send_sql($sql, $link, $db);
				$State = mysql_fetch_array($State_Result);
				
				$State_Ad = $State[0];
				$NewLevel = $State_Ad + 2;
				
				$sqlUD = "UPDATE $playername SET state_ad = $NewLevel WHERE state_ab = 'NE'"; 		
				$result = send_sql($sqlUD, $link, $db);		
				
				
			}
			
			//Checking 50 states if the ad level is over 10
			
			//Alabama
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AL'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AL'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Alaska
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AK'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AK'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Arizona
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AZ'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AZ'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Arkansas
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'AR'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'AR'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//California
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'CA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'CA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Colorado
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'CO'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'CO'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Conneticut
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'CT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'CT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Delaware
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'DE'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'DE'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Florida
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'FL'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'FL'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Georgia
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'GA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'GA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Hawaii
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'HI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'HI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Idaho
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'ID'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'ID'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Illinois
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'IL'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'IL'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Indiana
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'IN'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'IN'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Iowa
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'IA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'IA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Kansas
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'KS'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'KS'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Kentucky
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'KY'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'KY'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Louisiana
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'LA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'LA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Maine
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'ME'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'ME'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Maryland
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MD'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MD'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Massachusetts
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Michigan
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Minnesota
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MN'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MN'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Mississippi
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MS'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MS'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Missouri
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MO'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MO'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Montana
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'MT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'MT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Nebraska
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NE'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NE'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Nevada
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NV'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NV'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New Hampshire
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NH'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NH'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New Jersey
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NJ'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NJ'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New Mexico
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NM'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NM'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//New York
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NY'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NY'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//North Carolina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'NC'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'NC'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//North Dakota
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'ND'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'ND'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Ohio
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'OH'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'OH'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Oklahoma
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'OK'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'OK'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Oregon
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'OR'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'OR'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Pennsylvania
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'PA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'PA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Rhode Island
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'RI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'RI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//South Carolina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'SC'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'SC'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//South Dakota
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'SD'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'SD'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Tennessee
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'TN'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'TN'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Texas
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'TX'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'TX'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Utah
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'UT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'UT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Vermont
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'VT'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'VT'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Virgina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'VA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'VA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Washington
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WA'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WA'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//West Virgina
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WV'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WV'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Wisconsin
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WI'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WI'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			//Wyoming
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = 'WY'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 10){
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = 'WY'"; 
				$result = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//naturaldisaster1.PNG
		if($finalimage == $img28){
			
			// Update Influence
			
			$newInfluence = rand(10,90);
			$sqlUD = "UPDATE $playername SET stateInfluence = $newInfluence WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//naturaldisaster2.PNG
		if($finalimage == $img29){
			
			//Update Influence
			
			$newInfluence = rand(10,90);
			$sqlUD = "UPDATE $playername SET stateInfluence = $newInfluence WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			
			//Donation Updating
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//saidsomething1.PNG
		if($finalimage == $img30){
			
						//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 0){
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
			
		}
			
			//Getting 4 random states add levels and increasing them if they're less than 10.
			
			//state one
			$sqlST1 = "SELECT state_ad FROM $playername WHERE state_ab = '$state1'";
			$ad1 = send_sql($sqlST1, $link, $db);
			
			$StateAd1 = mysql_fetch_array($ad1);
			$AdState1 = $StateAd1[0];
			
			if($AdState1 > 0){
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
			}
			
			
			//state two
			$sqlST2 = "SELECT state_ad FROM $playername WHERE state_ab = '$state2'";
			$ad2 = send_sql($sqlST2, $link, $db);
			
			$StateAd2 = mysql_fetch_array($ad2);
			$AdState2 = $StateAd2[0];

			if($AdState2 > 0){
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
			}
			
			//state three
			$sqlST3 = "SELECT state_ad FROM $playername WHERE state_ab = '$state3'";
			$ad3 = send_sql($sqlST3, $link, $db);
			
			$StateAd3 = mysql_fetch_array($ad3);
			$AdState3 = $StateAd3[0];
				
			if($AdState3 > 0){	
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
			}
		
			
			//state four
			$sqlST4 = "SELECT state_ad FROM $playername WHERE state_ab = '$state4'";
			$ad4 = send_sql($sqlST4, $link, $db);
			
			$StateAd4 = mysql_fetch_array($ad4);
			$AdState4 = $StateAd4[0];
				
			if($AdState4 > 0){	
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
			}
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//saidsomething2.PNG
		if($finalimage == $img31){
			
									//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 0){
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
			
		}
			
			//Getting 4 random states add levels and increasing them if they're less than 10.
			
			//state one
			$sqlST1 = "SELECT state_ad FROM $playername WHERE state_ab = '$state1'";
			$ad1 = send_sql($sqlST1, $link, $db);
			
			$StateAd1 = mysql_fetch_array($ad1);
			$AdState1 = $StateAd1[0];
			
			if($AdState1 > 0){
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
			}
			
			
			//state two
			$sqlST2 = "SELECT state_ad FROM $playername WHERE state_ab = '$state2'";
			$ad2 = send_sql($sqlST2, $link, $db);
			
			$StateAd2 = mysql_fetch_array($ad2);
			$AdState2 = $StateAd2[0];

			if($AdState2 > 0){
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
			}
			
			//state three
			$sqlST3 = "SELECT state_ad FROM $playername WHERE state_ab = '$state3'";
			$ad3 = send_sql($sqlST3, $link, $db);
			
			$StateAd3 = mysql_fetch_array($ad3);
			$AdState3 = $StateAd3[0];
				
			if($AdState3 > 0){	
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
			}
		
			
			//state four
			$sqlST4 = "SELECT state_ad FROM $playername WHERE state_ab = '$state4'";
			$ad4 = send_sql($sqlST4, $link, $db);
			
			$StateAd4 = mysql_fetch_array($ad4);
			$AdState4 = $StateAd4[0];
				
			if($AdState4 > 0){	
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
			}
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//saidsomething3.PNG
		if($finalimage == $img32){
			
									//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 0){
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
			
		}
			
			//Getting 4 random states add levels and increasing them if they're less than 10.
			
			//state one
			$sqlST1 = "SELECT state_ad FROM $playername WHERE state_ab = '$state1'";
			$ad1 = send_sql($sqlST1, $link, $db);
			
			$StateAd1 = mysql_fetch_array($ad1);
			$AdState1 = $StateAd1[0];
			
			if($AdState1 > 0){
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
			}
			
			
			//state two
			$sqlST2 = "SELECT state_ad FROM $playername WHERE state_ab = '$state2'";
			$ad2 = send_sql($sqlST2, $link, $db);
			
			$StateAd2 = mysql_fetch_array($ad2);
			$AdState2 = $StateAd2[0];

			if($AdState2 > 0){
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
			}
			
			//state three
			$sqlST3 = "SELECT state_ad FROM $playername WHERE state_ab = '$state3'";
			$ad3 = send_sql($sqlST3, $link, $db);
			
			$StateAd3 = mysql_fetch_array($ad3);
			$AdState3 = $StateAd3[0];
				
			if($AdState3 > 0){	
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
			}
		
			
			//state four
			$sqlST4 = "SELECT state_ad FROM $playername WHERE state_ab = '$state4'";
			$ad4 = send_sql($sqlST4, $link, $db);
			
			$StateAd4 = mysql_fetch_array($ad4);
			$AdState4 = $StateAd4[0];
				
			if($AdState4 > 0){	
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
			}
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
				
			
		}
		
		//saidsomething4.PNG
		if($finalimage == $img33){
			
									//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel > 0){
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
			
		}
			
			//Getting 4 random states add levels and increasing them if they're less than 10.
			
			//state one
			$sqlST1 = "SELECT state_ad FROM $playername WHERE state_ab = '$state1'";
			$ad1 = send_sql($sqlST1, $link, $db);
			
			$StateAd1 = mysql_fetch_array($ad1);
			$AdState1 = $StateAd1[0];
			
			if($AdState1 > 0){
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
			}
			
			
			//state two
			$sqlST2 = "SELECT state_ad FROM $playername WHERE state_ab = '$state2'";
			$ad2 = send_sql($sqlST2, $link, $db);
			
			$StateAd2 = mysql_fetch_array($ad2);
			$AdState2 = $StateAd2[0];

			if($AdState2 > 0){
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
			}
			
			//state three
			$sqlST3 = "SELECT state_ad FROM $playername WHERE state_ab = '$state3'";
			$ad3 = send_sql($sqlST3, $link, $db);
			
			$StateAd3 = mysql_fetch_array($ad3);
			$AdState3 = $StateAd3[0];
				
			if($AdState3 > 0){	
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
			}
		
			
			//state four
			$sqlST4 = "SELECT state_ad FROM $playername WHERE state_ab = '$state4'";
			$ad4 = send_sql($sqlST4, $link, $db);
			
			$StateAd4 = mysql_fetch_array($ad4);
			$AdState4 = $StateAd4[0];
				
			if($AdState4 > 0){	
				$sqlUD = "UPDATE $playername SET state_ad = state_ad - 1 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
			}
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
		}
		
		//scandal.PNG
		if($finalimage == $img34){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlUD = "UPDATE $playername SET stateinfluence = stateinfluence - 10 WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went negative, set it to 0 if did.
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence <= 0){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 0 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
			
		}
		
		//scandal2.PNG
		if($finalimage == $img35){
			
						$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlUD = "UPDATE $playername SET stateinfluence = stateinfluence - 10 WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went negative, set it to 0 if did.
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence <= 0){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 0 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//scandal3.PNG
		if($finalimage == $img36){
			
						$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlUD = "UPDATE $playername SET stateinfluence = stateinfluence - 10 WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went negative, set it to 0 if did.
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence <= 0){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 0 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//scandal4.PNG
		if($finalimage == $img37){
			
						$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlUD = "UPDATE $playername SET stateinfluence = stateinfluence - 10 WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			
			//Checking if the influence level went negative, set it to 0 if did.
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence <= 0){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 0 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//scandal5.PNG
		if($finalimage == $img38){
			
						$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlUD = "UPDATE $playername SET stateinfluence = stateinfluence - 10 WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went negative, set it to 0 if did.
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence <= 0){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 0 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//scandal6.PNG
		if($finalimage == $img39){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlUD = "UPDATE $playername SET stateinfluence = stateinfluence - 10 WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went negative, set it to 0 if did.
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence <= 0){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 0 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//scandal7.PNG
		if($finalimage == $img40){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlUD = "UPDATE $playername SET stateinfluence = stateinfluence - 10 WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went negative, set it to 0 if did.
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence <= 0){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 0 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//scandal8.PNG
		if($finalimage == $img41){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlUD = "UPDATE $playername SET stateinfluence = stateinfluence - 10 WHERE state_ab = '$cur_state'";
			$UD = send_sql($sqlUD, $link, $db);
			
			//Checking if the influence level went negative, set it to 0 if did.
			
			$sqlSI = "SELECT stateinfluence FROM $playername WHERE state_ab = '$cur_state'";
			$sqlSIUD = send_sql($sqlSI, $link, $db);
			
			$arraySI = mysql_fetch_array($sqlSIUD);
			$cur_influence = $arraySI[0];
			
			if($cur_influence <= 0){
				
				$sqlUD = "UPDATE $playername SET stateinfluence = 0 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//talkshowColbert1.PNG
		if($finalimage == $img42){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$sqlAD_result = send_sql($sqlAD, $link, $db);
			
			$ad = mysql_fetch_array($sqlAD_result);
			$adLevel = $ad[0];
			
			$donations = $adLevel * 100;
			
			$doubleDonations = $donations * 2;
			
			$sqlUD = "UPDATE final SET donations = donations + $doubleDonations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
			
		}
		
		//talkshowColbert2.PNG
		if($finalimage == $img43){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$sqlAD_result = send_sql($sqlAD, $link, $db);
			
			$ad = mysql_fetch_array($sqlAD_result);
			$adLevel = $ad[0];
			
			$donations = $adLevel * 100;
			
			$doubleDonations = $donations * 2;
			
			$sqlUD = "UPDATE final SET donations = donations + $doubleDonations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//talkshowColbert3.PNG
		if($finalimage == $img44){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$sqlAD_result = send_sql($sqlAD, $link, $db);
			
			$ad = mysql_fetch_array($sqlAD_result);
			$adLevel = $ad[0];
			
			$donations = $adLevel * 100;
			
			$doubleDonations = $donations * 2;
			
			$sqlUD = "UPDATE final SET donations = donations + $doubleDonations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//talkshowColbert4.PNG
		if($finalimage == $img45){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$sqlAD_result = send_sql($sqlAD, $link, $db);
			
			$ad = mysql_fetch_array($sqlAD_result);
			$adLevel = $ad[0];
			
			$donations = $adLevel * 100;
			
			$doubleDonations = $donations * 2;
			
			$sqlUD = "UPDATE final SET donations = donations + $doubleDonations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//talkshowFallon1.PNG
		if($finalimage == $img46){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$sqlAD_result = send_sql($sqlAD, $link, $db);
			
			$ad = mysql_fetch_array($sqlAD_result);
			$adLevel = $ad[0];
			
			$donations = $adLevel * 100;
			
			$doubleDonations = $donations * 2;
			
			$sqlUD = "UPDATE final SET donations = donations + $doubleDonations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//talkshowFallon2.PNG
		if($finalimage == $img47){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$sqlAD_result = send_sql($sqlAD, $link, $db);
			
			$ad = mysql_fetch_array($sqlAD_result);
			$adLevel = $ad[0];
			
			$donations = $adLevel * 100;
			
			$doubleDonations = $donations * 2;
			
			$sqlUD = "UPDATE final SET donations = donations + $doubleDonations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//talkshowFallon3.PNG
		if($finalimage == $img48){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$sqlAD_result = send_sql($sqlAD, $link, $db);
			
			$ad = mysql_fetch_array($sqlAD_result);
			$adLevel = $ad[0];
			
			$donations = $adLevel * 100;
			
			$doubleDonations = $donations * 2;
			
			$sqlUD = "UPDATE final SET donations = donations + $doubleDonations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//talkshowFallon4.PNG
		if($finalimage == $img49){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$sqlAD_result = send_sql($sqlAD, $link, $db);
			
			$ad = mysql_fetch_array($sqlAD_result);
			$adLevel = $ad[0];
			
			$donations = $adLevel * 100;
			
			$doubleDonations = $donations * 2;
			
			$sqlUD = "UPDATE final SET donations = donations + $doubleDonations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//talkshowGMA1.PNG
		if($finalimage == $img50){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$sqlAD_result = send_sql($sqlAD, $link, $db);
			
			$ad = mysql_fetch_array($sqlAD_result);
			$adLevel = $ad[0];
			
			$donations = $adLevel * 100;
			
			$doubleDonations = $donations * 2;
			
			$sqlUD = "UPDATE final SET donations = donations + $doubleDonations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//talkshowGMA2.PNG
		if($finalimage == $img51){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$sqlAD_result = send_sql($sqlAD, $link, $db);
			
			$ad = mysql_fetch_array($sqlAD_result);
			$adLevel = $ad[0];
			
			$donations = $adLevel * 100;
			
			$doubleDonations = $donations * 2;
			
			$sqlUD = "UPDATE final SET donations = donations + $doubleDonations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//talkshowGMA3.PNG
		if($finalimage == $img52){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$sqlAD_result = send_sql($sqlAD, $link, $db);
			
			$ad = mysql_fetch_array($sqlAD_result);
			$adLevel = $ad[0];
			
			$donations = $adLevel * 100;
			
			$doubleDonations = $donations * 2;
			
			$sqlUD = "UPDATE final SET donations = donations + $doubleDonations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//talkshowGMA4.PNG
		if($finalimage == $img53){
			
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$sqlAD_result = send_sql($sqlAD, $link, $db);
			
			$ad = mysql_fetch_array($sqlAD_result);
			$adLevel = $ad[0];
			
			$donations = $adLevel * 100;
			
			$doubleDonations = $donations * 2;
			
			$sqlUD = "UPDATE final SET donations = donations + $doubleDonations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//volunteer1.PNG
		if($finalimage == $img54){
			
						//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Getting 4 random states add levels and increasing them if they're less than 10.
			
			//state one
			$sqlST1 = "SELECT state_ad FROM $playername WHERE state_ab = '$state1'";
			$ad1 = send_sql($sqlST1, $link, $db);
			
			$StateAd1 = mysql_fetch_array($ad1);
			$AdState1 = $StateAd1[0];
			
			if($AdState1 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state two
			$sqlST2 = "SELECT state_ad FROM $playername WHERE state_ab = '$state2'";
			$ad2 = send_sql($sqlST2, $link, $db);
			
			$StateAd2 = mysql_fetch_array($ad2);
			$AdState2 = $StateAd2[0];
			
			if($AdState2 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state three
			$sqlST3 = "SELECT state_ad FROM $playername WHERE state_ab = '$state3'";
			$ad3 = send_sql($sqlST3, $link, $db);
			
			$StateAd3 = mysql_fetch_array($ad3);
			$AdState3 = $StateAd3[0];
			//Change them all to be greater than or equal to. 
			if($AdState3 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state four
			$sqlST4 = "SELECT state_ad FROM $playername WHERE state_ab = '$state4'";
			$ad4 = send_sql($sqlST4, $link, $db);
			
			$StateAd4 = mysql_fetch_array($ad4);
			$AdState4 = $StateAd4[0];
			
			if($AdState4 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//volunteer2.PNG
		if($finalimage == $img55){
			
			//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Getting 4 random states add levels and increasing them if they're less than 10.
			
			//state one
			$sqlST1 = "SELECT state_ad FROM $playername WHERE state_ab = '$state1'";
			$ad1 = send_sql($sqlST1, $link, $db);
			
			$StateAd1 = mysql_fetch_array($ad1);
			$AdState1 = $StateAd1[0];
			
			if($AdState1 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state two
			$sqlST2 = "SELECT state_ad FROM $playername WHERE state_ab = '$state2'";
			$ad2 = send_sql($sqlST2, $link, $db);
			
			$StateAd2 = mysql_fetch_array($ad2);
			$AdState2 = $StateAd2[0];
			
			if($AdState2 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state three
			$sqlST3 = "SELECT state_ad FROM $playername WHERE state_ab = '$state3'";
			$ad3 = send_sql($sqlST3, $link, $db);
			
			$StateAd3 = mysql_fetch_array($ad3);
			$AdState3 = $StateAd3[0];
			
			if($AdState3 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state four
			$sqlST4 = "SELECT state_ad FROM $playername WHERE state_ab = '$state4'";
			$ad4 = send_sql($sqlST4, $link, $db);
			
			$StateAd4 = mysql_fetch_array($ad4);
			$AdState4 = $StateAd4[0];
			
			if($AdState4 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
		}
		
		//volunteer3.PNG
		if($finalimage == $img56){
								//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Getting 4 random states add levels and increasing them if they're less than 10.
			
			//state one
			$sqlST1 = "SELECT state_ad FROM $playername WHERE state_ab = '$state1'";
			$ad1 = send_sql($sqlST1, $link, $db);
			
			$StateAd1 = mysql_fetch_array($ad1);
			$AdState1 = $StateAd1[0];
			
			if($AdState1 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state two
			$sqlST2 = "SELECT state_ad FROM $playername WHERE state_ab = '$state2'";
			$ad2 = send_sql($sqlST2, $link, $db);
			
			$StateAd2 = mysql_fetch_array($ad2);
			$AdState2 = $StateAd2[0];
			
			if($AdState2 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state three
			$sqlST3 = "SELECT state_ad FROM $playername WHERE state_ab = '$state3'";
			$ad3 = send_sql($sqlST3, $link, $db);
			
			$StateAd3 = mysql_fetch_array($ad3);
			$AdState3 = $StateAd3[0];
			
			if($AdState3 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state four
			$sqlST4 = "SELECT state_ad FROM $playername WHERE state_ab = '$state4'";
			$ad4 = send_sql($sqlST4, $link, $db);
			
			$StateAd4 = mysql_fetch_array($ad4);
			$AdState4 = $StateAd4[0];
			
			if($AdState4 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
		}
		
		//volunteer4.PNG
		if($finalimage == $img57){
			//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Getting 4 random states add levels and increasing them if they're less than 10.
			
			//state one
			$sqlST1 = "SELECT state_ad FROM $playername WHERE state_ab = '$state1'";
			$ad1 = send_sql($sqlST1, $link, $db);
			
			$StateAd1 = mysql_fetch_array($ad1);
			$AdState1 = $StateAd1[0];
			
			if($AdState1 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state two
			$sqlST2 = "SELECT state_ad FROM $playername WHERE state_ab = '$state2'";
			$ad2 = send_sql($sqlST2, $link, $db);
			
			$StateAd2 = mysql_fetch_array($ad2);
			$AdState2 = $StateAd2[0];
			
			if($AdState2 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state three
			$sqlST3 = "SELECT state_ad FROM $playername WHERE state_ab = '$state3'";
			$ad3 = send_sql($sqlST3, $link, $db);
			
			$StateAd3 = mysql_fetch_array($ad3);
			$AdState3 = $StateAd3[0];
			
			if($AdState3 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state four
			$sqlST4 = "SELECT state_ad FROM $playername WHERE state_ab = '$state4'";
			$ad4 = send_sql($sqlST4, $link, $db);
			
			$StateAd4 = mysql_fetch_array($ad4);
			$AdState4 = $StateAd4[0];
			
			if($AdState4 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
		}
		
		//volunteer5.PNG
		if($finalimage == $img58){
			
			//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Getting 4 random states add levels and increasing them if they're less than 10.
			
			//state one
			$sqlST1 = "SELECT state_ad FROM $playername WHERE state_ab = '$state1'";
			$ad1 = send_sql($sqlST1, $link, $db);
			
			$StateAd1 = mysql_fetch_array($ad1);
			$AdState1 = $StateAd1[0];
			
			if($AdState1 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state two
			$sqlST2 = "SELECT state_ad FROM $playername WHERE state_ab = '$state2'";
			$ad2 = send_sql($sqlST2, $link, $db);
			
			$StateAd2 = mysql_fetch_array($ad2);
			$AdState2 = $StateAd2[0];
			
			if($AdState2 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state three
			$sqlST3 = "SELECT state_ad FROM $playername WHERE state_ab = '$state3'";
			$ad3 = send_sql($sqlST3, $link, $db);
			
			$StateAd3 = mysql_fetch_array($ad3);
			$AdState3 = $StateAd3[0];
			
			if($AdState3 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state four
			$sqlST4 = "SELECT state_ad FROM $playername WHERE state_ab = '$state4'";
			$ad4 = send_sql($sqlST4, $link, $db);
			
			$StateAd4 = mysql_fetch_array($ad4);
			$AdState4 = $StateAd4[0];
			
			if($AdState4 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//volunteer6.PNG
		if($finalimage == $img59){
			//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			if($AdLevel >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$cur_state'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Getting 4 random states add levels and increasing them if they're less than 10.
			
			//state one
			$sqlST1 = "SELECT state_ad FROM $playername WHERE state_ab = '$state1'";
			$ad1 = send_sql($sqlST1, $link, $db);
			
			$StateAd1 = mysql_fetch_array($ad1);
			$AdState1 = $StateAd1[0];
			
			if($AdState1 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state1'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state two
			$sqlST2 = "SELECT state_ad FROM $playername WHERE state_ab = '$state2'";
			$ad2 = send_sql($sqlST2, $link, $db);
			
			$StateAd2 = mysql_fetch_array($ad2);
			$AdState2 = $StateAd2[0];
			
			if($AdState2 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state2'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state three
			$sqlST3 = "SELECT state_ad FROM $playername WHERE state_ab = '$state3'";
			$ad3 = send_sql($sqlST3, $link, $db);
			
			$StateAd3 = mysql_fetch_array($ad3);
			$AdState3 = $StateAd3[0];
			
			if($AdState3 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state3'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//state four
			$sqlST4 = "SELECT state_ad FROM $playername WHERE state_ab = '$state4'";
			$ad4 = send_sql($sqlST4, $link, $db);
			
			$StateAd4 = mysql_fetch_array($ad4);
			$AdState4 = $StateAd4[0];
			
			if($AdState4 >= 10){

			
				$sqlUD = "UPDATE $playername SET state_ad = 10 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
			
			}else{
				
				$sqlUD = "UPDATE $playername SET state_ad = state_ad + 1 WHERE state_ab = '$state4'";
				$UD = send_sql($sqlUD, $link, $db);
				
			}
			
			//Donation Updaiting
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			$donations = $AdLevel * 100;
			
			$sqlUD = "UPDATE final SET donations = donations + $donations WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		//badPress4.PNG
		if($finalimage == $img60){
			
			//Getting the values needed for the card
			$sqlCS = "SELECT cur_state FROM final where player_name = '$playername'";
			$curstate = send_sql($sqlCS, $link, $db);
			
			$state = mysql_fetch_array($curstate);
			$cur_state = $state[0];
			
			$sqlAD = "SELECT state_ad FROM $playername WHERE state_ab = '$cur_state'";
			$AD = send_sql($sqlAD, $link, $db);
			
			$AD_Level= mysql_fetch_array($AD);
			$AdLevel = $AD_Level[0];
			
			//Math for card
			$Donations = 100 * $AdLevel;
			$Donations = $Donations/2 ; 
			
			//Getting the current amount of donations for the player
			$sqlDU = "SELECT donations FROM final WHERE player_name = '$playername'";
			$CurDonations = send_sql($sqlDU, $link, $db);
			
			$Cur_Donations = mysql_fetch_array($CurDonations);
			$CurrentDonations = $Cur_Donations[0];
			
			//Updating the donations value
			$DonationUpdate = $CurrentDonations + $Donations;
			
			echo"$DonationUpdate";
			
			$sqlUD = "UPDATE final SET donations = $DonationUpdate WHERE player_name = '$playername'";
			$UD = send_sql($sqlUD, $link, $db);
			
		}
		
		
		$sqlUpdateInfluence = "UPDATE `$playername` SET stateinfluence = stateinfluence+state_ad";
		$updateInfluenceResult = send_sql($sqlUpdateInfluence, $link, $db);
		
		$sqlGetCurrentState = "SELECT cur_state FROM final WHERE player_name = '$playername'";
		$getCurStateRes = send_sql($sqlGetCurrentState, $link, $db);
		$curStateArr = mysql_fetch_array($getCurStateRes);
		$curState = $curStateArr[0];
		
		$sqlUpdateCurStateInfluence = "UPDATE `$playername` SET stateinfluence = stateinfluence + 10 WHERE state_ab = '$curState'";
		$updateCurStateInfluenceRes = send_sql($sqlUpdateCurStateInfluence, $link, $db);
		
		$sqlKeepInfluenceBelowMax = "UPDATE `$playername` SET stateinfluence = 100 WHERE stateinfluence > 100";
		$keepInfluenceBelowMaxRes = send_sql($sqlKeepInfluenceBelowMax, $link, $db);
		
		
	}
	
	
	?>
	</div>
	</p>
	

</body>

</html>
<?php } ?>
