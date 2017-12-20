<?php
/*
 * admin.php
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
	<title>Adding a Volunteer</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.23.1" />
	<link href="final.css" type="text/css"rel="stylesheet"/>
<body>

<?php
extract($_REQUEST);

if (isset($_POST['submit'])){
  $mySQL_Host="localhost";
  $mySQL_User="criew142";
  $mySQL_Pass="mgwtzgi0H8gub";

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
$sql= "INSERT INTO Volunteer (user_name, pass_word, email_address) VALUES ('$username', '$password', '$email')"; 
$result = send_sql($sql, $link, $database);

if ($result){
	echo "<center>The username is $username.</center>";
	echo "<form action='admin.html' method='post'> <input type='submit' value='Back'> </form>";

}
	  mysql_close( $link );



}




else {?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<h1>Create your Username and Password</h1>
	<p>Please enter the volunteer's valid email address: <input type="text" size="25" name="email"/><br>
	Enter a Username: <input type="text" size="25" name="username"/><br>
	Enter a password: <input type="password" size="25" name="password"/><br>
	<input type=submit value="submit" name="submit"><input type=reset value="Clear"></p>
</form>
</body>

</html>
<?php } ?>
