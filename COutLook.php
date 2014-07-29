<?PHP

global $UnreadMessagesInFolder;
class COutLook{

	//function for retreiving messages from the selected folder (Inbox or Outbox)
	function getMessages($folder){
		//Setup the folder table,.there is 4 elements:

		//message number,message subject ,message type and date received
		echo"<body text=darkblue>
		<br><font color=red face=verdana size=3><b>$folder</b></font>
		<table width=100%>
		<TR bgcolor=#EEEFFF><td><font face=verdana size=2>N:</td><td>
		<font face=verdana size=2> Subject</td><TD>
		<font face=verdana size=2 >Type</TD><TD><font face=verdana size=2> Date</TD></TR>";

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
		$name =  $inb->Folders(12);

		var_dump($count,$name->Name());

		for ($i = 1; $i <= $count; $i++) {
			$custom = $inb->Folders($i);
			var_dump($custom->Name(), $custom->Items->Count());
		}

		for($i=1;$i<($messages+1);$i++){
			$item=$inb->Items($i);
			//date string
			$timeres=$item->ReceivedTime;

			//$date_vb=getdate($timeres);
			//date elements
			//$year=$date_vb['year'];
			//$month=$date_vb['mon'];
			//$day=$date_vb['mday'];
			//entering the folder elements
			//$item->ConversationIndex.'//'.$item->ConversationId
			$attachments = $item->Attachments;
			echo "<tr bgcolor=#F0F0F0><td><font face=verdana size=2 color=darkblue>$attachments->Count";
			if($attachments->Count > 0) {
				for ($j=1; $j < $attachments->Count; $j++ ) {
					$attachment = $item->Attachments($j);
					echo $attachment->FileName.'//'.$attachment->Size.'//'.$attachment->Type.'<br>';
					$pattern = '/.*?ics|.*?vcf/';
					if(preg_match($pattern, $attachment->FileName, $matches, PREG_OFFSET_CAPTURE)) {
						try {
							$attachment->SaveAsFile('C:/attachment/'.$attachment->FileName);	
						} catch (Exception $e) {
							echo $e->getMessage();
						}
						
					}
					
				}
			}
			echo "</td>
			<td><font face=verdana size=2 color=darkblue><a href=view.php?id=$i&folder=$folder target=bottomFrame><font face=verdana size=2 color=#FF6666>$item->Subject</font></td>
			<td><font face=verdana size=2 color=darkblue>$item->SenderEmailType</td>
			<td><font face=verdana size=1 color=darkblue>$timeres</td>
			<td><font face=verdana size=1 color=darkblue></td></font><tr>";
		}
		echo"</table>";
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

		echo"<body text=darkblue>
		<br><font color=red face=verdana size=3><b>Contacts</b></font>
		<table width=100%>
		<TR bgcolor=#EEEFFF><td><font face=verdana size=2>N:</td><td>
		<font face=verdana size=2> Subject</td><TD>
		<font face=verdana size=2 >Type</TD><TD><font face=verdana size=2> Date</TD></TR>";

		//creating the COM instance for Outlook.application and MAPI session(access the outlook folders object)
		$oOutlook = new COM("Outlook.Application");
		//$session= new COM("MAPI.Session") or die('cannot open mapi session');
		echo $oOutlook->Version;
		
		$session= $oOutlook->GetNamespace("MAPI");


		//Log into the session like default user 
		$session->Logon();

		$myFolder = $session->GetDefaultFolder(10);
 		
 		echo ('Contacts count '.$myFolder->Items->Count. '<br>');
 		
 		//get the total messages in Folder
		$messages= $myFolder->Items->Count();

		//get the elements of the message object

		for($i=1;$i<($messages+1);$i++){
			$myItem = $myFolder->Items($i); 
			try {
			    echo ($myItem->FullName . ':'.$myItem->Email1Address .'<br>');
			} catch (Exception $e) {
			    //echo 'Caught exception: ',  $e->getMessage(), "\n";
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