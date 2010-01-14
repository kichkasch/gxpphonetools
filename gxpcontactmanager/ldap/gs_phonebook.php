<?php
/*

GXP Contact Manager (ldap)
by Michael Pilgermann (kichkasch@gmx.de)
(origionally based on database version from Shane Steinbeck http://www.steinbeckconsulting.com)
Copyright (C) 2010 Michael Pilgermann

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
The purpose of this PHP-Script is simply accessing a database, which contains recipe information
for reading purposes only in order to display them in a web browser. The accessed database must
be in the design given by the Anymeal (http://www.wedesoft.demon.co.uk/anymeal-api/) application.
This application will propably also be used for adding and modifying content in the database as
this web frontend is not capable of supporting this.


Contact information often is to be hold centrally nowadays in a ldap directory in order to intergrate several 
applications.
This version of gs_phonebook is an alternative to the MySQL database based version - same general idea: the XML file is created
on the fly when requested from the phone; however, the information source for the XML file is now an LDAP directory.
*/

header("Content-type: text/xml");

include 'config.php';

$ds=ldap_connect($ldapHost, $ldapPort);  // must be a valid LDAP server!

if (ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3)) {
if ($ds) { 
    $r=ldap_bind($ds);
    $sr=ldap_search($ds, $ldapDomain, $ldapFilter);  
    $info = ldap_get_entries($ds, $sr);
    $xml_output = "<?xml version=\"1.0\"?>\n";
    $xml_output .= "<AddressBook>\n";
    for ($i=0; $i<$info["count"]; $i++) {
	if ($info[$i]["homephone"][0]) { // if home phone is set, add a new entry in the xml file with a trailing [H] in the first name
		$xml_output .= "\t<Contact>\n";
		$xml_output .= "\t\t<LastName>" . $info[$i]["sn"][0] . "</LastName>\n";
		$xml_output .= "\t\t<FirstName>" . $info[$i]["givenname"][0] . $homePhoneSuffix . "</FirstName>\n";
		$xml_output .= "\t\t\t<Phone>\n\t\t\t\t<phonenumber>" . noLocalCountryCode($info[$i]["homephone"][0], $localCountryCode) . "</phonenumber>\n";
		$xml_output .= "\t\t\t\t<accountindex>" . $accountIndex . "</accountindex>\n";
		$xml_output .= "\t\t\t</Phone>\n";
		$xml_output .= "\t</Contact>\n"; }
	if ($info[$i]["telephonenumber"][0]) { // if work phone number is set, add a new entry in the xml file with a trailing [W] in the first name
		$xml_output .= "\t<Contact>\n";
		$xml_output .= "\t\t<LastName>" . $info[$i]["sn"][0] . "</LastName>\n";
		$xml_output .= "\t\t<FirstName>" . $info[$i]["givenname"][0] . $workPhoneSuffix . "</FirstName>\n";
		$xml_output .= "\t\t\t<Phone>\n\t\t\t\t<phonenumber>" . noLocalCountryCode($info[$i]["telephonenumber"][0], $localCountryCode) . "</phonenumber>\n";
		$xml_output .= "\t\t\t\t<accountindex>" . $accountIndex . "</accountindex>\n";
		$xml_output .= "\t\t\t</Phone>\n";
		$xml_output .= "\t</Contact>\n"; }
	if ($info[$i]["mobile"][0]) { // if mobile phone is set, add a new entry in the xml file with a trailing [M] in the first name
		$xml_output .= "\t<Contact>\n";
		$xml_output .= "\t\t<LastName>" . $info[$i]["sn"][0] . "</LastName>\n";
		$xml_output .= "\t\t<FirstName>" . $info[$i]["givenname"][0] . $mobilePhoneSuffix . "</FirstName>\n";
		$xml_output .= "\t\t\t<Phone>\n\t\t\t\t<phonenumber>" . noLocalCountryCode($info[$i]["mobile"][0], $localCountryCode) . "</phonenumber>\n";
		$xml_output .= "\t\t\t\t<accountindex>" . $accountIndex . "</accountindex>\n";
		$xml_output .= "\t\t\t</Phone>\n";
		$xml_output .= "\t</Contact>\n";
	}
    }
    ldap_close($ds);
    $xml_output .= "</AddressBook>";

    $fp = fopen('gs_phonebook-dyn.xml', 'wb');
    fwrite($fp, $xml_output);
    fclose($fp);

    print($xml_output);
}

}

/* 
Incoming national calls do not have the country code prefixed on the GXP
So in oder to recognize the phone book entry on incoming calls, local country
code has to be removed 
Also leading "+" will be converted to 00
*/
function noLocalCountryCode($number, $localCountryCode)
{
$pos = strpos($number, $localCountryCode);
if (!strcmp($pos,'0'))
{
        return '0' . substr($number, 3);
} else {
// replace leading '+' by double zero
        if (!strcmp(strpos($number, '+'), '0'))
        {
                return '00' . substr($number, 1);
        } else {
                return $number;
        }
}

}

?>
