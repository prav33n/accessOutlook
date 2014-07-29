<?PHP
	//previous class
	set_time_limit (0);
	require("COutLook.php");
	//make new instance of the class

	$class= new COutLook;

	if ($folder==""){
		//$class->getMessages('test');
		$class->staticFolders();
		$class->getContacts();
		$class->getMessages('Inbox');
		$class->getMessages('Outbox');
	}
	else {
		$class->staticFolders();
		$class->getMessages('Inbox');
		$class->getMessages('Outbox');
	}

?>