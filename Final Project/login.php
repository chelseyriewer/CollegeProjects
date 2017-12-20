<?php 
session_start();
?>
<?php
/*
 * login.php
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
	<title>Log in</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.23.1" />
	<link href="final.css" type="text/css"rel="stylesheet"/>
</head>

<body>
		<?php 
	extract($_REQUEST);




if (empty($username)){
	echo "Please enter in your user name.";
}
if (empty($password)){
	echo "<br>Please enter in your password.";
}
if ($username== "Admin" && $password== "admin123"){
	$_SESSION['username']= $_POST['username'];
	header('Location: admin.php');
	}


if (isset($username) && isset($password)){
  $mySQL_Host="localhost";
  $mySQL_User="criew142";
  $mySQL_Pass="mgwtzgi0H8gub";
  
  $_SESSION['username']= $_POST['username'];

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
$database = "criew_Final";
$query = "SELECT pass_word FROM Volunteer WHERE user_name = '$username'";
$pass= "SELECT user_name FROM Volunteer WHERE pass_word = '$password'";
$result = send_sql($query, $link,$database, $pass);
$rows_affected=mysql_affected_rows($link);
if ($rows_affected==0){
	die("User not found");
}



if ($result){?>
	<h1>Welcome <?php echo $_SESSION['username']?></h1>
	<h1>Please enter in the hours you have volunteered today</h1>
	<?php
	extract($_REQUEST);
	$date=date(" D F j, Y");
	echo "<center>Today is $date</center>";
	#Checkbox
	$checkbox="<input type='checkbox' name='cb[]' value='1'/>"
	?>
	<html>
	<form action="tablechecking.php" method="POST">
		<?php
$link = connect();
$database = "criew_Final";
$sql="SELECT * FROM Hours WHERE time=time"; 
$result = send_sql($sql, $link, $database);
		echo "<table style='Width:50%' border='1px'>";
	  while ($recorddata = mysql_fetch_array($result, MYSQL_BOTH)){
		 echo "<tr>";

		 #Table Contents 
		echo "<td>" . $recorddata['time'] . "</td>";
		echo "<td>" . $checkbox . "</td>";

					
		    echo "</tr>";
		
	  }
	  echo "</table>";
	  ?>
	<center>Please enter in your user name:<input type='text' size="25" name="username"></center>
	<input type='submit' value='Save'>
	</form>
	</html>
<?php
echo "";
}

	  mysql_close( $link );


}

?>	

 
</body>

</html>
