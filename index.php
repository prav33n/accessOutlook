<?php
	//previous class
	set_time_limit (0);
	error_reporting(E_ERROR | E_PARSE);
	require("OutLook.php");
	require("db.php");
?>

<html>
  <head>
    <title>"Outlook setup"</title>
  </head>
  <body bgcolor=white>
  	<?php
  		if(count($_POST)>0) {
  			//var_dump($_POST);
  			if($_POST['submit'] == 'Delete') {
  				$iniVal['database']['name'] = '';
  				$iniVal['database']['host'] = '';
  				$iniVal['database']['user'] = '';
  				$iniVal['database']['pass'] = '';
  				put_ini_file('config.php',$iniVal);
  			} else if($_POST['submit'] == 'Extract Mails') {
				ExtractEmailInbox (true);
  			}else if($_POST['submit'] == 'Save') {
  				$iniVal['database']['name'] = $_POST['name'];
  				$iniVal['database']['host'] = $_POST['host'];
  				$iniVal['database']['user'] = $_POST['user'];
  				$iniVal['database']['pass'] = $_POST['pass'];
  				put_ini_file('config.php',$iniVal);
  			}
  		}

  		$settings  = parse_ini_file('config.php', true);
  		if($settings['database']['name'] == '' 
  			&& $settings['database']['host'] == '' 
  			&& $settings['database']['user'] == '' 
  			&& $settings['database']['pass'] == '') {
  			echo '<form action="index.php" method="post">
				 Database Name:<input type="text" name="name"><p><br>
				 Host: <input type="text" name="host" text="localhost"><p><br> 
				 UserName: <input type="text" name="user"><p><br>
				 Password: <input type="text" name="pass"><p><br>
				 <input type="submit" name="submit" value="Save">
				 </form>';
  		} else {
  			echo '<form action="index.php" method="post">
				 <input type="submit" name="submit" value="Delete">
				 <input type="submit" name="submit" value="Extract Mails">
				 </form>';
  		}
		

  		function put_ini_file($file, $array, $i = 0){
		  $str="";
		  foreach ($array as $k => $v){
		    if (is_array($v)){
		      $str.=str_repeat(" ",$i*2)."[$k]\r\n";
		      $str.=put_ini_file("",$v, $i+1);
		    }else
		      $str.=str_repeat(" ",$i*2)."$k = $v\r\n";
		  }
		  
		  $phpstr = "<?PHP\r\n\r\n".$str."\r\n?>";
		  
		  if($file)
		    return file_put_contents($file,$phpstr);
		  else
		    return $str;
		}

		function ExtractEmailInbox ($dbcreateflag) {
			echo 'Loading mail to database, Please wait....<br>';
			sleep(1);
			if (true) {
				$db = new db;
				if($db->connectStatus) {
					$db->db_schema_setup();
					$class = new OutLook;
					$class->getMessages('Inbox');
					$class->getContacts();
					echo 'Loading complete.<br>';
				} else {
					echo 'Database connection error <br> enter the right credentials';
				}
			}
		}
	?>
  </body>
</html>