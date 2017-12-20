<!--
   egyptianmau.html
   
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
	<title>Egyptian Mau</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.25" />
	<link href="final.css" type="text/css"rel="stylesheet"/>
</head>

<body>
			<?php
$page = 'egyptianmau.php';

if ($page){
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
$query="UPDATE Views SET amount_viewed = amount_viewed+1 WHERE web_address = '$page'";
$result = send_sql($query, $link, $database );
if ($result){
	echo "Welcome to the page";
}
echo $page;
}
?>
	
</body>

</html>
