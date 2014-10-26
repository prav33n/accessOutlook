<!DOCTYPE html>
<?php
	//previous class
	set_time_limit (0);
	error_reporting(E_ERROR | E_PARSE);
	require("OutLook.php");
	require("db.php");
	$db = new db;
	$outlook = new Outlook;
?>
<html lang="en">
  <head>
    <title>Outlook setup</title>
    <!-- Loading Flat UI -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/flat-ui.css" rel="stylesheet">
    <link href="css/outlook.css" rel="stylesheet">
  </head>
  <body>
  	<div class="centerDiv">
  	<div class = "formContainer">
  	<?php
  		if(count($_POST)>0) {
  			//var_dump($_POST);
  			if($_POST['submit'] == 'Delete') {
  				$iniVal['database']['name'] = '';
  				$iniVal['database']['host'] = '';
  				$iniVal['database']['user'] = '';
  				$iniVal['database']['pass'] = '';
  				put_ini_file('config.php',$iniVal);
  				$outlook->emptyDataBase();
  			} else if($_POST['submit'] == 'Extract Mails') {
  				try{
  					ExtractEmailInbox (true);
  				} catch (Exception $e) {
  					echo '<div>'.$e->getMessage().'</div>';
  				}
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
  			echo '<form action="index.php" method="post" role="form" class="form-horizontal" >
				 <div class="form-group"><h6 class="col-sm-4">Database Name:</h6><input class="col-sm-8" type="text" name="name"><p></div>
				 <div class="form-group"><h6 class="col-sm-4">Host:</h6> <input class="col-sm-8" type="text" name="host" text="localhost"><p></div>
				 <div class="form-group"><h6 class="col-sm-4">UserName:</h6> <input class="col-sm-8" type="text" name="user"><p></div>
				 <div class="form-group"><h6 class="col-sm-4">Password:</h6> <input class="col-sm-8" type="text" name="pass"><p></div>
				 <input class="btn btn-lg btn-primary" type="submit" name="submit" value="Save">
				 </form>';
  		} else {
  			echo '<form action="index.php" method="post" role="form-vertical">
				 <input class= "btn btn-lg btn-danger" type="submit" name="submit" value="Delete"/>
				 <input class= "btn btn-lg btn-success" type="submit" name="submit" value="Extract Mails"/>
                 <a class= "btn btn-lg btn-success" href="showExtractedMails.php">View Extracted Mails</a></div>
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
			echo '<div>Loading mail to database, Please wait....</div>';
			$db = new db;
			if($db->connectStatus) {
				$db->db_schema_setup();
				$class = new OutLook;
				$class->getMessages('Inbox');
				$class->getContacts();
				echo '<div>Loading complete.<br></div>';
			} else {
				echo '<div>Database connection error <br> enter the right credentials</div>';
			}
		}
	?>
	</div>
	</div>
  </body>
</html>