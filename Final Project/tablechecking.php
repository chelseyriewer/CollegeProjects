<?php 
session_start();
?>

<?php
/*
 * tablechecking.php
 * 
 * Copyright 2016 Chelsey Riewer <riew1che@HS104L25>
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>untitled</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.23.1" />
	<link href="final.css" type="text/css"rel="stylesheet"/>
</head>

<body>
	<?php
	extract($_REQUEST);
	$mySQL_Host="localhost";
	$mySQL_User="criew142";
	$mySQL_Pass="mgwtzgi0H8gub";
	$_SESSION['username']= $_POST['username'];
	function connect(){
		global $mySQL_Host, $mySQL_User,$mySQL_Pass;

		if ( ! $linkid = mysql_connect("$mySQL_Host", "$mySQL_User","$mySQL_Pass")){
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
		#Error checking for a max of 3 hours
		if (isset($cb) && is_array($cb)){
		foreach ( $cb as $key=>$value){
			if ($key > 3){
				print "<center>Sorry, you have selected more than 3 hours, you can only volunteer 3 hours a day.</center>";
			}
			$date=date(" D F j, Y");
			$database = "criew_Final";
			$link = connect();
			$sqlcheck="SELECT day_volunteered FROM Volunteer WHERE day_volunteered='$date' ";
			$result = send_sql($sqlcheck,$link,$database);
			$rows_affected=mysql_affected_rows($link);
			#Error checking for more than 2 volunteers
			if ($rows_affected>2){
				die("Sorry, there are already two volunteers working today.");
			}
			#End of error checking for more than 2 volunteers
			if ($key <= 3){ 
				$hours= count($cb);
				$totalhours=$hours;
				if ($hours >= 1){
					$totalhours= $hours;
					$sql1 ="Update Volunteer SET hours_volunteered='$totalhours' WHERE user_name= '$username'";
					$sql2 = "Update Volunteer SET day_volunteered='$date' WHERE user_name= '$username'";
					$rows_affected=mysql_affected_rows($link);
					$result = send_sql($sql1,$link,$database);
					$result = send_sql($sql2,$link,$database);
				echo "<center>You have volunteered for $totalhours hours today.</center>";
				#End of error checking
				}	
			}
		break;}
	
}


	  mysql_close( $link );
	
	
	
	
	?>
	<form action="login.php" method="post">
	<p><input type="submit" value="edit hours"></p>
	</form>
	<form action="deletehours.php" method="post">
	<p>Input your user name to delete your hours: <input type="text" name="username" size="25">
	<br>Now press delete.
		<br><input type="submit" value="Delete" name="delete">
	
	</p>
	</form>
	<form action="login.html" method="post">
		<?php unset($_SESSION['username']);?>
		<p><input type="submit" value="logout"></p>
	</form>
	
</body>

</html>
