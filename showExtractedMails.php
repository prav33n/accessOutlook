<?php
  require("OutLook.php");
  require("db.php");
  set_time_limit (0);
?>

<html lang="en">
  <head>
    <title>Outlook Mail view</title>
    <meta charset="UTF-8">
    <!-- Loading Flat UI -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/flat-ui.css" rel="stylesheet">
    <link href="css/outlook.css" rel="stylesheet">
  </head>
  <body>
    <a href="index.php"><h4>&larr; Back<h4><br></a>
    <?php
    $db = new db;
    $sql = "SELECT * FROM email ORDER BY folderid";
    $result = $db->db_query($sql);
    echo '<div class="formContainer">
            <table class="table table-striped table-hover">
              <tbody>';
    echo '<thead>
          <tr>
          <th>Sender Name</th>
          <th>CC/BCC</th>
          <th>Subject</th>
          <th>Folder</th>
          </tr>
          </thead>';
    while($resutlArray = $result->fetch_object()){
        echo '<tr>';
        $row = $resutlArray->senderName !== '' ? $resutlArray->senderName : $resutlArray->senderEmail;
        echo '<td id = "name">'.$row.'</td>';
        echo '<td id = "cc">'.$resutlArray->cc.'<br>'.$resutlArray->bcc.'</td>'; 
        echo '<td id = "subject">'.$resutlArray->subject.'</td>';
        echo '<td id = "folderName">'.$resutlArray->foldername.'</td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';
    ?>
  </body>
</html>