<?PHP
	//previous class 
	require("COutLook.php");
	$class= new COutLook;
	$id = $_GET['id'];
	$folder = $_GET['folder'];
	//if no messages selected
	if ($id=="" || $folder== ""){
		echo "<font face=verdana size=2 color=darkblue>Message Viewer</font>
		<br><font face=verdana size=2 color=red>
		<center>No Messages Selected</center></font>";
	}
	else{
		//get the message
		$class->ViewMessageFromFolder($id,$folder);
	}
?>