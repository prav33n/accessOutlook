<?php


//previous class
set_time_limit (0);
error_reporting(E_ERROR | E_PARSE);
require("OutLook.php");
require("db.php");



function BuildDBSkeleton () {
	$db = new db;
	$db->db_schema_setup();
}

function ExtractEmailInbox ($dbcreateflag) {
	echo 'Loading mail to database, Please wait....';
	sleep(1);
	if (true)
		BuildDBSkeleton ();
	$class = new OutLook;
	$class->getMessages('Inbox');
	$class->getContacts();
	echo 'Loading complete.';
}

function main() {
// main function
	
echo ("came to main\n");
//die();	

	global $cfg;
	$action = $cfg['action'];
	if ($action == "extract_email_inbox")
		ExtractEmailInbox ($cfg['dbcreateflag']);
}

$cfg = array();
$cfg['dbhost'] 		= "localhost";	// host on which mysql server works
$cfg['dbport'] 		= "3306";		// port of mysqlserver (3306 by default)
$cfg['dbuser'] 		= "root";		// user we use to connect to server
$cfg['dbpassword']	= "titans";	// password of the connected user
$cfg['email_db'] 	= "outLook";
$cfg['dbcreateflag'] = false;
$cfg['action'] = "extract_email_inbox";
/*if ($cfg['action'] == "extract_email_inbox") {
	$cfg['dbcreateflag']= $argv[2];
	if ($argc != 3) {
		print ("Email\n");
		die ("Usage: php -f extractMails.php extract_email_inbox <dbcreateflag>");
	}
}*/

main();










/*	
$i = 0;
while ($i < mysql_num_fields($result)) {
    echo "Information for column $i:<br />\n";
    $meta = mysql_fetch_field($result, $i);
    if (!$meta) {
        echo "No information available<br />\n";
    }
    echo "<pre>
blob:         $meta->blob
max_length:   $meta->max_length
multiple_key: $meta->multiple_key
name:         $meta->name
not_null:     $meta->not_null
numeric:      $meta->numeric
primary_key:  $meta->primary_key
table:        $meta->table
type:         $meta->type
unique_key:   $meta->unique_key
unsigned:     $meta->unsigned
zerofill:     $meta->zerofill
</pre>";
    $i++;
}
mysql_free_result($result);
*/







?>
