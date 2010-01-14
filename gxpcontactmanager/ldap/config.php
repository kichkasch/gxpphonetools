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



Apply your ldap settings here
*/

$accountIndex = "0";		// Default line to use for dialing out - count starting with 0

$ldapHost = "192.168.200.20";
$ldapPort = 389;
$ldapDomain = "dc=kichkasch,dc=local";
$ldapFilter = "mozillaCustom4=*gxp*";

$localCountryCode = '+49'; // configure this in order to enable the GXP phone to pick up names from address book for incoming calls

/* 
entries from ldap may come with several phone numbes attached to one name; so we need to 
resolve types of phones some how; I did this by suffixing a single letter in brackets to
the name of the entry - you may configure this here.
Take care of the leading space - otherwise the suffix is directly attached to the name - not nice.
*/ 
$homePhoneSuffix = ' [H]';
$workPhoneSuffix = ' [W]';
$mobilePhoneSuffix = ' [M]';

/* !--
end configuration
*/
?>