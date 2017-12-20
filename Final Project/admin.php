<?php
session_start();
$_SESSION['username']="Admin";
?>
<!--
   admin.html
   
   Copyright 2016 Chelsey <Chelsey@CHELSEY-VAIO>
   
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>untitled</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.25" />
	<link href="final.css" type="text/css"rel="stylesheet"/>
</head>

<body>

	<p><h1>Welcome <?php echo $_SESSION['username']; ?></h1>
	<center>If you would like to add new volunteers please click <a href="adminadd.php"> here</a>.
	<br>If you want to delete a volunteer please click <a href="admindelete.php"> here </a>.
	<br>If you want to change the times volunteers can work please click <a href="adminchange.php">here</a>.
	<br> If you want to print off a volunteers hours please click <a href="adminprint.php">here</a>.
	<br> If you want to see how many times an animal has been viewed please click <a href="animalview.php">here</a>.
		<form action="login.html" method="post">
		<?php unset($_SESSION['username']);?>
		<p><input type="submit" value="logout"></p>
	</form>

	</center>
	
	</p>
	
</body>

</html>
