<?php
/*
 * finalgame.php
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
	<title>Player 1</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.23.1" />

	<link href="finalgame.css" type="text/css"rel="stylesheet"/>
	<script type='text/javascript' src='playersetup.js' charset='utf-8'></script>
	</head>

<body>
	<?php
	extract($_REQUEST);
if (isset($_POST['endturn'])){		

  
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
$turn;

turncheck();


function turncheck(){
$link = connect();
$db = "criewer";
	global $turn;
	$turn = $turn + 1;
if ($turn == 1){


  

	$sql = ("SELECT id, player_name, donations, state_count, ad_level FROM final WHERE id = 1");
	$res = send_sql($sql, $link, $db);
	
	//echo $result;
echo "<table>";	
	while($row = mysql_fetch_array($res)){
	 echo"<tr><td>" . $row['id'] . "</td>"; 
	 echo"<td>" . $row['player_name'] . "</td>"; 
	 echo"<td>" . $row['donations'] . "</td>"; 
	 echo"<td>" . $row['state_count'] . "</td>"; 
	 echo"<td>" . $row['ad_level'] . "</td>"; 

}
echo "</table>";
		
			
		
		if (isset($_POST['endturn'])){
			echo "You ended your turn";
			$turn = $turn + 1;

	}


 
}

if ($turn == 2){
	
  

	$sql2 = ("SELECT id, player_name, donations, state_count, ad_level FROM final WHERE id = 2");
	$res2 = send_sql($sql2, $link, $db);
echo "<table>";	
	while($row = mysql_fetch_array($res2)){
	 echo"<tr><td>" . $row['id'] . "</td>"; 
	 echo"<td>" . $row['player_name'] . "</td>"; 
	 echo"<td>" . $row['donations'] . "</td>"; 
	 echo"<td>" . $row['state_count'] . "</td>"; 
	 echo"<td>" . $row['ad_level'] . "</td>"; 
}
echo "</table>";



		if (isset($_POST['endturn'])){
			$turn = $turn + 1;
}



}

if ($turn == 3){

  

	$sql3 = ("SELECT id, player_name, donations, state_count, ad_level FROM final WHERE id = 3");
	$res3 = send_sql($sql3, $link, $db);
	
		if($res3 == null){
			$turn = 0;
	}
 
echo "<table>";	
	while($row = mysql_fetch_array($res3)){
	 echo"<tr><td>" . $row['id'] . "</td>"; 
	 echo"<td>" . $row['player_name'] . "</td>"; 
	 echo"<td>" . $row['donations'] . "</td>"; 
	 echo"<td>" . $row['state_count'] . "</td>"; 
	 echo"<td>" . $row['ad_level'] . "</td>"; 
}
echo "</table>";
	



		if (isset($_POST['endturn'])){
			$turn = $turn + 1;
	}
}

if ($turn == 4){
	
  

	$sql4 = ("SELECT id, player_name, donations, state_count, ad_level FROM final WHERE id = 4");
	$res4 = send_sql($sql4, $link, $db);
	
	if($res4 == null){
			$turn = 0;
	}
echo "<table>";	
	while($row = mysql_fetch_array($res4)){
	 echo"<tr><td>" . $row['id'] . "</td>"; 
	 echo"<td>" . $row['player_name'] . "</td>"; 
	 echo"<td>" . $row['donations'] . "</td>"; 
	 echo"<td>" . $row['state_count'] . "</td>"; 
	 echo"<td>" . $row['ad_level'] . "</td>"; 
}
echo "</table>";

	

	if (isset($_POST['endturn'])){
			$turn = 1;
}

	
}
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

	<button id="movestate" class="MOVE" value="submitmove">Move To State</button>
	
	
	<button id="endturn" class="endT" value="endturn">End Turn</button>








</body>

</html>
<?php }?>
