<?php
// File: gs_phonebook.php
// version: 1.0
// Date: 07-17-2006
// Author: Shane Steinbeck http://www.steinbeckconsulting.com
// Description: Realtime XML phonebook from MySQL database for Grandstream GXP-2000 firmware 1.1.1.17

header("Content-type: text/xml");

include 'config.php';

$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");

$query = "SELECT first_name
,last_name
,account_index
,phone_number
,type
FROM gxp_phonebook
ORDER BY last_name ASC
,first_name";
$resultID = mysql_query($query, $linkID) or die("Data not found.");

$xml_output = "<?xml version=\"1.0\"?>\n";
$xml_output .= "<AddressBook>\n";
for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++)
{
$row = mysql_fetch_assoc($resultID);
$xml_output .= "\t<Contact>\n";
$xml_output .= "\t\t<LastName>" . $row['last_name'] . "</LastName>\n";
$xml_output .= "\t\t<FirstName>" . $row['first_name'] . " [" . $row['type'] . "]</FirstName>\n";
$xml_output .= "\t\t\t<Phone>\n\t\t\t\t<phonenumber>" . $row['phone_number'] . "</phonenumber>\n";
$xml_output .= "\t\t\t\t<accountindex>" . $row['account_index'] . "</accountindex>\n";
$xml_output .= "\t\t\t</Phone>\n";
$xml_output .= "\t</Contact>\n";
}
$xml_output .= "</AddressBook>";

$fp = fopen('gs_phonebook-dyn.xml', 'wb');
fwrite($fp, $xml_output);
fclose($fp);

print($xml_output);
?>

