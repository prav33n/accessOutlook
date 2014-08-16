<?PHP
global $UnreadMessagesInFolder;

class OutLook{

	public $db;
	//function for retreiving messages from the selected folder (Inbox or Outbox)
	public function __construct()
    { 
       $this->db = new db;
       var_dump($this->db);
    }

	function getMessages($folder,$fromDate,$toDate){
		//Setup the folder table,.there is 4 elements:

		//creating the COM instance for Outlook.application and MAPI session(access the outlook folders object)
		$oOutlook = new COM("Outlook.Application");
		//$session= new COM("MAPI.Session") or die('cannot open mapi session');
		echo $oOutlook->Version;
		
		$session= $oOutlook->GetNamespace("MAPI");


		//Log into the session like default user 
		$session->Logon();

		//selecting working folder Inbox ot Outbox
 		if($folder == 'Inbox') {
 			$inb = $session->GetDefaultFolder(6);	
 		} else if ($folder == 'Outbox')  {
 			$inb = $session->GetDefaultFolder(4);	
 		}

 		//get the total messages in Folder
		$messages=$inb->Items->Count();

		//get the elements of the message object
		//check custom folders 
		$customFolder = $inb->Folders;
		$count =  $customFolder->Count();
		//$name =  $inb->Folders(12);
		
		for ($i = 1; $i <= $count; $i++) {
			$custom = $inb->Folders($i);
			//echo "<b>Folder Name : </b>". $custom->Name() ."<p>Count :".$custom->Items->Count()."</p>";
			if(isset($fromDate) && isset($toDate)) {
				echo 'filter set';
				$this->getMessageFromFolder($custom, $i, $fromDate, $toDate);
			} else {
				echo 'filter not set';
				$this->getMessageFromFolder($custom, $i);	
			}
			
		}
		$this->getMessageFromFolder($inb, 6);
	}

	 

	//view mesage from selected folder (Inbox or Outbox)


	function ViewMessageFromFolder($id,$folder){
		//create new instance of the COM Objects
		$oOutlook = new COM("Outlook.Application");
		$session= $oOutlook->GetNamespace("MAPI");
		//Log into the current working session
		$session->Logon();

		//get default folder
		if($folder == 'Inbox') {
 			$inb = $session->GetDefaultFolder(6);	
 		} else if ($folder == 'Outbox')  {
 			$inb = $session->GetDefaultFolder(4);	
 		}

		if($id==""){
			echo "<font face=verdana size=2 color=darkblue>Message Viewer</font><br><font face=verdana size=2 color=red><center>No Messages Selected</center></font>";
		}
		else{
			$idint=(int)$id;

			//get the messages in the selested folder
			$items=$inb->Items($idint);
			//make message status read= true
			$items->Unread = "false";
			//Update the message status into Outlooks Inbox
			//$items->Update(true);
			//display the message

			echo"<font face=verdana size=2 color=darkblue>Message Viewer</font>";
			echo"<table width=100%><tr><td><font face=verdana size=2 color=darkblue>$id</td><td><font face=verdana size=2 color=darkblue>
			<b>$items->Subject</b></td><td><font face=verdana size=2 color=darkblue>$items->SenderEmailType</td><td></td></font><tr>
			<tr><td colspan=4><pre><font face=verdana size=2 color=darkblue>$items->HTMLBody</pre></td></tr>";
		}
	}

	function getContacts() {
		//creating the COM instance for Outlook.application and MAPI session(access the outlook folders object)
		$oOutlook = new COM("Outlook.Application");
		//$session= new COM("MAPI.Session") or die('cannot open mapi session');
		
		$session= $oOutlook->GetNamespace("MAPI");


		//Log into the session like default user 
		$session->Logon();

		$myFolder = $session->GetDefaultFolder(10);
 		
 		echo ('Contacts count '.$myFolder->Items->Count. '<br>');
 		
 		//get the total messages in Folder
		$contactCount = $myFolder->Items->Count();

		/*INSERT INTO `contacts`(`id`, `name`, `primaryemail`, `secondaryemail`, `phonenumber`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5])*/
		
		for($i=1;$i<=($contactCount);$i++){
			$myItem = $myFolder->Items($i); 
			try {
				//get the elements of the message object
				$contactObject->emailAddress = mysql_escape_string($myItem->Email1Address);
				$contactObject->secondaryEmail = mysql_escape_string($myItem->Email2Address);
				$contactObject->name = mysql_escape_string($myItem->FullName);
				$contactObject->phone = mysql_escape_string($myItem->PrimaryTelephoneNumber);
			    $sql = 'INSERT INTO `contacts`(`name`, `primaryemail`, `secondaryemail`, `phonenumber`) VALUES (\''.$contactObject->name.'\',\''.$contactObject->emailAddress.'\',\''.$contactObject->secondaryEmail.'\',\''.$contactObject->phone.'\')';
			    var_dump($sql);
			    $this->db->db_query($sql) or die ('Insertion error');
			   	//break;
			} catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
			
 		}
	}

	function getUnreadinInbox(){
		//get unread messages from the Inbox Folder
		$oOutlook = new COM("Outlook.Application");
		echo $oOutlook->Version;

		$oNs = $oOutlook->GetNamespace("MAPI");
		$oFldr = $oNs->GetDefaultFolder(6);


		$UnreadMessagesInFolder = $oFldr->UnReadItemCount;
		return $UnreadMessagesInFolder;
	}

	function getUnreadinOutbox(){
		//get unread messages from the Outbox Folder
		$oOutlook = new COM("Outlook.Application");
		$oNs = $oOutlook->GetNamespace("MAPI");
		$oFldr = $oNs->GetDefaultFolder(4);
		$UnreadMessagesInFolder = $oFldr->UnReadItemCount;
		return $UnreadMessagesInFolder;
	}

	function getMessageFromFolder($folder, $id, $fromDate, $toDate) {
		//message number,message subject ,message type and date received
		$name = $folder->Name();
		$count =  $folder->Items->Count();
		for($i=1;$i<= ($count);$i++){
			$item=$folder->Items($i);
			//date string
			$timeres=$item->ReceivedTime;

			$emailObject->senderName =  htmlspecialchars ($item->SenderName,ENT_QUOTES,'UTF-8',true);
			$emailObject->senderEmail = htmlspecialchars ($item->SenderEmailAddress,ENT_QUOTES,'UTF-8',true);
			$emailObject->cc = htmlspecialchars ($item->Cc,ENT_QUOTES,'UTF-8',true);
			$emailObject->bcc = htmlspecialchars ($item->Bcc,ENT_QUOTES,'UTF-8',true);
			//$htmlEncode = htmlspecialchars ($item->Subject,ENT_QUOTES,'UTF-8',true);
			$emailObject->subject = htmlspecialchars ($item->Subject,ENT_QUOTES,'UTF-8',true);
			//$htmlEncode = htmlentities($item->HTMLBody,ENT_QUOTES,'UTF-8',true);
			$htmlEncode = htmlspecialchars ($item->Body,ENT_QUOTES,'UTF-8',true);
			//$htmlEncode = preg_replace('/\'/', ' ', $htmlEncode);
			$emailObject->html_content = $htmlEncode;
			$emailObject->folderId = $id;
			$emailObject->folderName = $name;
			$emailObject->timestamp = strtotime($timeres);
			//mysql_real_escape_string($emailObject->html_content)
			//$emailObject->html_content = str_replace('\'',"\"",$emailObject->html_content);

			$attachments = $item->Attachments;
			if($attachments->Count > 0) {
				for ($j=1; $j < $attachments->Count; $j++ ) {
					$attachment = $item->Attachments($j);
					$pattern = '/.*?ics|.*?vcf/';
					if(preg_match($pattern, $attachment->FileName, $matches, PREG_OFFSET_CAPTURE)) {
						try {
							//echo $attachment->FileName.'//'.$attachment->Size.'//'.$attachment->Type.'<br>';
							$emailObject->attachment = 'C:/attachment/'.$attachment->FileName;
							$attachment->SaveAsFile($emailObject->attachment);

						} catch (Exception $e) {
							echo $e->getMessage();
						}
					}
					
				}
			}

			if(isset($fromDate) && isset($toDate)) {
				if($timeres >= $fromDate && $timeres <= $toDate) {
					$sql = 'INSERT INTO `outlook`.`email` (`senderName`, `senderEmail`, `cc`, `bcc`, `subject`, `html_content`, `folderid`, `foldername`, `timestamp`) 
					VALUES (\''.$emailObject->senderName.'\',\''.$emailObject->senderEmail.'\',\''.$emailObject->cc.'\',\''.$emailObject->bcc.'\',\''.$emailObject->subject.'\',\''.$emailObject->html_content.'\',\''.$emailObject->folderId.'\',\''. $emailObject->folderName.'\','.$emailObject->timestamp.')';
					$this->db->db_query($sql) or die ('Insertion error');
				} else {
					echo 'mail received outside  the date limit';
				}
			}  else {
				$sql = 'INSERT INTO `outlook`.`email` (`senderName`, `senderEmail`, `cc`, `bcc`, `subject`, `html_content`, `folderid`, `foldername`, `timestamp`) 
				VALUES (\''.$emailObject->senderName.'\',\''.$emailObject->senderEmail.'\',\''.$emailObject->cc.'\',\''.$emailObject->bcc.'\',\''.$emailObject->subject.'\',\''.$emailObject->html_content.'\',\''.$emailObject->folderId.'\',\''. $emailObject->folderName.'\','.$emailObject->timestamp.')';
				//var_dump($sql);
				//echo '<br>';
				$this->db->db_query($sql) or die ('Insertion error');
			}
			//break;
		}
	}

	function staticFolders(){
		// List of the avaailable folders (static !!!)
		//$session= new COM("MAPI.Session");
		$unread=$this->getUnreadinInbox();
		$out_unr=$this->getUnreadinOutbox();
		echo"<font color=blue face = verdana size=1>Available folders in this version are:
		<a href=comunread.php?folder=Inbox>Inbox(<font color=red>$unread</font>)</a>
		and <a href=comunread.php?folder=Outbox>Outbox(<font color=red>$out_unr</font>)</a></font>";
	}

//end of classs
}
?>