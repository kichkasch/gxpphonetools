<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">

<?php 
/* !--
  GXP Contact Manager - V 0.1
  by Michael Pilgermann (kichkasch@gmx.de / http://www.kichkasch.de)
  
    Copyright (C) 2008  Michael Pilgermann

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
  
  GXP Contact Manager is a PHP-Interface to the phone book entries in a MySQL-database, which
  may be read by another PHP-script (gs_phonebook.php by Shane Steinbeck - see 
  http://www.voip-info.org/wiki/view/GXP-2000+XML+Phonebook for more details),
  which in turn is accessed by Grandstream SIP telephones for loading their contacts from a web server.
  
  The whole infrastructure is working the following way:
	- you are running a MySQL-Server in your local network keeping all your contact details
	  (see http://www.voip-info.org/wiki/view/GXP-2000+XML+Phonebook for installation instructions)
	- you are running a PHP-enabled Web-Server in your local network, hosting the PHP-Scripts
	- you have the following two PHP-Scripts installed on this Web server:
		* gs_phonebook.php - will be read by your SIP phone on request. Contact details will only
		  be read through this interface and stored on the phone. 
		* contactmanager.php - will be accessed by you using a Web browser (such us Mozilla Firefox)
		  for maintaining your contacts
-->
*/
?>

<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" media="all" href="styles.css">
<title>GXP Contact Manager (v0.1)</title>
</head>

<body>

<?php
include 'config.php';
/* 

	Processing

*/



$perform=$_REQUEST['perform'];
$prepare_change=$_REQUEST['prepare_change'];
$NAME=$_REQUEST['NAME'];
$FIRSTNAME=$_REQUEST['FIRSTNAME'];
$PHONE=$_REQUEST['PHONE'];
$ACCOUNT=$_REQUEST['ACCOUNT'];
$TYPE=$_REQUEST['TYPE'];

$ID=$_REQUEST['ID'];

$status = $_REQUEST['status'];

$text="No action requested.<p>&nbsp;</p>";
if (isset ($perform)) {
	if (! empty ($perform)) {
		if (! strcmp($perform, "add")){
			$text = "<p>" . addEntry() . "</p>";
		}
		if (! strcmp($perform, "delete")){
			$text = "<p>" . deleteEntry() . "</p>";
		}
		if (! strcmp($perform, "change")){
			$text = "<p>" . changeEntry() . "</p>";
		}
	}
}

if (isset ($status))
	if (! empty ($status))
		$text = "<p>" . $status . "</p>" . $text;

/* !--

END processing

*/
?>

<table width="1000" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td width="20%" rowspan="5">&nbsp;</td>
    <td align="center"><h2>GXP2000 Contact Manager </h2></td>
    <td width="20%" rowspan="5">&nbsp;</td>
  </tr>

  <tr>
    <td width="50%" valign="top">
      <table width="100%"  border="1" cellpadding="0" cellspacing="0" bordercolor="#666666">
        <tr>
          <td bgcolor="#CCCCCC">
		<?php print ($text); ?>
          <p>&nbsp;</p></td>
        </tr>
      </table>      </td>
  </tr>

  

<tr><td>
<?php
if (isset($prepare_change)) {
print ('<table width="100%"  border="1" cellpadding="10" cellspacing="0" bordercolor="#666666" bgcolor="#EEEEAA">');
} else {
print ('<table width="100%"  border="1" cellpadding="10" cellspacing="0" bordercolor="#666666">');
}
?>

<tr>
  <td>

<?php
if (isset($prepare_change)) {
print ("<h4>Modify contact</h4>");
} else {
print ("<h4>Add new contact</h4>");
}
?>

<form action="contactmanager.php" method="post" enctype="multipart/form-data" name="form1">
<table width="50%"  border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100">Name<br>            </td>
<?php
if (isset($prepare_change)) {
    print('<td width="330"><input type="text" name="NAME" value="' . $NAME . '"></td>');
} else {
    print('<td width="330"><input type="text" name="NAME"></td>');
}
?>
  </tr>
  <tr>
    <td width="100">First Name<br>            </td>
<?php
if (isset($prepare_change)) {
    print('<td width="330"><input type="text" name="FIRSTNAME" value="' . $FIRSTNAME . '"></td>');
} else {
    print('<td width="330"><input type="text" name="FIRSTNAME"></td>');
}
?>
  </tr>
  <tr>
    <td width="100">Phone<br>            </td>
<?php
if (isset($prepare_change)) {
    print('<td width="330"><input type="text" name="PHONE" value="' . $PHONE . '"></td>');
} else {
    print('<td width="330"><input type="text" name="PHONE"></td>');
}
?>
    
  </tr>
  <tr>
    <td width="100">Account<br>            </td>
	<td>
	<select size="1" name="ACCOUNT">
<?php
if (isset($prepare_change)) {
	foreach ($ACCOUNT_NAMES as $index => $accountName)
	{
    if (! strcmp($ACCOUNT, $index)){ 
	print('<option selected value="' . $index . '">[' . $index . '] ' . $accountName . '</option>');
    } else {
	print('<option value="' . $index . '">[' . $index . '] ' . $accountName . '</option>');
    }	
	}
} else {
	foreach ($ACCOUNT_NAMES as $index => $accountName)
	{
	print('<option value="' . $index . '">[' . $index . '] ' . $accountName . '</option>');
	}
}
?>
	</select> 
    	</td>
  </tr>
  <tr>
    <td width="100">Type<br>            </td>
	<td>
	<select size="1" name="TYPE">
<?php
if (isset($prepare_change)) {

	foreach ($NUMBER_TYPES as $index => $typeName)
	{
	 if (! strcmp($TYPE, $index)){ 
		print('<option selected value="' . $index . '">[' . $index . '] ' . $typeName . '</option>');	 
	 } else {
		print('<option value="' . $index . '">[' . $index . '] ' . $typeName . '</option>');
		}
	}
} else {
	foreach ($NUMBER_TYPES as $index => $typeName)
	{
	print('<option value="' . $index . '">[' . $index . '] ' . $typeName . '</option>');
	}
	/*

*/
}
?>
	</select> 
    </td>
  </tr>

  <tr><td colspan="2">
<?php
if (isset($prepare_change)) {
    print('<input type="hidden" name="perform" value="change">');
    print('<input type="hidden" name="ID" value=' . $ID . '>');
    print('<input type="submit" name="Submit" value="Submit modified contact"> <a href="contactmanager.php?status=Update of contact cancelled by user.">Cancel</a> </td>');
} else {
    print('<input type="hidden" name="perform" value="add">');
    print('<input type="submit" name="Submit" value="Submit new contact"></td>');
}
?>
    </td>
  </tr>
</table>
</form>     </td>

</td>
</tr>
</table>      
</td></tr>


<tr><td>
<?php
print("<h4>Existent contacts</h4>");

$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");

$query = "SELECT id,first_name,last_name,account_index,phone_number,type FROM gxp_phonebook ORDER BY last_name ASC,first_name";
$resultID = mysql_query($query, $linkID) or die("Data not found.");

?>
<table width="100%"  border='1'>
<tr>
<th>Name</th>
<th>First name</th>
<th>Phone</th>
<th>Account</th>
<th>Type</th>
<th>Action</th>
</tr>

<?php
for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
 print("<tr>");
 $row = mysql_fetch_assoc($resultID);
 $edit_link = "contactmanager.php?prepare_change=true&ID=" . $row['id'] . "&NAME=" . $row['last_name'] . "&FIRSTNAME=" . $row['first_name']. "&ACCOUNT=" . $row['account_index'] . "&PHONE=" . $row['phone_number'] . "&TYPE=" . $row['type'];
 $remove_link = "contactmanager.php?perform=delete&ID=" . $row['id'];


 print("<td>" . $row['last_name'] . "</td>");
 print("<td>" . $row['first_name']  . "</td>");
 print("<td>" . $row['phone_number'] . "</td>");
 print("<td>" . $row['account_index'] . "</td>");
 print("<td>" . $row['type'] . "</td>");
 print("<td><a href='" . $edit_link . "'><img src='" . 'ressources/edit.png' . "' width='16' height='16' border='0'></a>&nbsp;<a href='" . $remove_link . "'><img src='" . 'ressources/trash.png' . "' width='16' height='16' border='0'></a></td>");
 print("</tr>\n");
}

print("</table>");

?>
</td></tr>
</table>

<?php

/*

FUNCTIONS 

	addEntry
	deleteEntry	
	changeEntry

*/

function addEntry() {
	global $NAME;
	global $FIRSTNAME;
	global $PHONE;
	global $ACCOUNT;
	global $TYPE;

	global $host;
	global $user;
	global $pass;
	global $database;

	$query="insert into gxp_phonebook (last_name, first_name, phone_number, account_index, type) values ('" . $NAME . "','" . $FIRSTNAME . "','" . $PHONE . "','" . $ACCOUNT . "','" . $TYPE . "')";

	$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
	mysql_select_db($database, $linkID) or die("Could not find database.");

	//print ($query);
	$result = mysql_query($query) or  die ('Could not update.' . mysql_error());
	$number_rows = mysql_affected_rows();

	// Free resultset
	//mysql_free_result($result);

	// Closing connection
	mysql_close($linkID);

	if ($number_rows > 0)
		return "Insertion Sucessful. <br>Entry was added to database.";
	else
		return "Insertion not sucessful. <br>An unknown error occured.";
}

function deleteEntry() {
	global $host;
	global $user;
	global $pass;
	global $database;
	global $ID;

	if (!isset ($ID))
		return "Delete not Sucessful!<br/>No ID defined";
	if (empty ($ID))
		return "Delete not Sucessful!<br/>No ID defined";

	$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
	mysql_select_db($database, $linkID) or die("Could not find database.");

	$query = "DELETE FROM gxp_phonebook WHERE ID = '" . $ID . "'";
	//print ($query);
	$result = mysql_query($query) or  die ('Could not update.' . mysql_error());
	$number_rows = mysql_affected_rows();

	// Free resultset
	//mysql_free_result($result);

	// Closing connection
	mysql_close($linkID);

	if ($number_rows > 0)
		return "Delete Sucessful. <br>Entry with ID " . $ID . " deleted.";
	else
		return "Delete unsucessful. <br>No Entry with ID " . $ID . " in database.";
}

function changeEntry() {
	global $ID;	
	global $NAME;
	global $FIRSTNAME;
	global $PHONE;
	global $ACCOUNT;
	global $TYPE;

	global $host;
	global $user;
	global $pass;
	global $database;


	$query="update gxp_phonebook set last_name='" . $NAME . "',first_name='" . $FIRSTNAME . "', phone_number='" . $PHONE . "', account_index='" . $ACCOUNT . "', type='" . $TYPE . "' where ID='" . $ID . "'";
	//print ($query);

	$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
	mysql_select_db($database, $linkID) or die("Could not find database.");

	//print ($query);
	$result = mysql_query($query) or  die ('Could not update.' . mysql_error());
	$number_rows = mysql_affected_rows();

	// Free resultset
	//mysql_free_result($result);

	// Closing connection
	mysql_close($linkID);

	if ($number_rows > 0)
		return "Update Sucessful. <br>Entry was modifiled in database.";
	else
		return "Update not sucessful. <br>An unknown error occured.";
}

/*  END functions  */

?>

</body>
</html>
