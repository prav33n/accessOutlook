<?php 
    /*

    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    */


    $mysqli = 0; 
    
    /*

    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    */

class db {

    public $connectStatus;  
    public function __construct()
    { 

      global $mysqli, $server, $username, $password, $database;
      // ERROR CODES
      // 1: success!
      // 3: database settings are wrong 
      // 4: launch setup.php
      $settings  = parse_ini_file('config.php', true);
      $GLOBALS['server']    = $settings['database']['host'];  // host on which mysql server works
      $GLOBALS['username']    = $settings['database']['user'];   // user we use to connect to server
      $GLOBALS['password']  =  $settings['database']['pass']; // password of the connected user
      $GLOBALS['database']  = $settings['database']['name'];

      // Lets try to connect
      $mysqli = new mysqli($server, $username, $password, $database);
      //var_dump($mysqli->connect_errno);
      if ($mysqli->connect_errno > 0) 
        $this->connectStatus = false;
      else {
        $this->connectStatus = true;
      }
      /*else
      {
        $result = $this->db_query("SELECT count(table_schema) from information_schema.tables WHERE table_schema = '$database'");
        $row = $this->db_fetch_array($result);
                     
        if ($row[0])
          return 1;
        else
          return 4;

      }*/
    }

  function db_query($query)
  {
    $ret = $GLOBALS['mysqli']->query($query);
    if ($ret == false) {echo $GLOBALS['mysqli']->error;}
    return $ret;
  }

  function db_fetch_array($result)
  {
    $ret = $result->fetch_array();
    if ($ret == false) {echo $GLOBALS['mysqli']->error;}
    return $ret;
  }

  function db_fetch_object($result)
  {
    $ret = $result->fetch_object();
    if ($ret == false) {echo $GLOBALS['mysqli']->error;}
    return $ret;
  }

  function db_num_rows($result)
  {
    return $result->num_rows;
  }

  function db_real_escape_string($string)
  {
    return $GLOBALS['mysqli']->real_escape_string($string);
  }

  function db_insert_id()
  {
    return $GLOBALS['mysqli']->insert_id;
  }

  function table_exists($tablename)
  {
    $result = $this->db_query('SELECT DATABASE()');
    $row = $this->db_fetch_array($result);
    $database = $row[0];

    $result = $this->db_query("
        SELECT COUNT(*) AS count 
        FROM information_schema.tables 
        WHERE table_schema = '$database' 
        AND table_name = '$tablename'
    ");

    $row = $this->db_fetch_array($result);
    return $row[0];
  }

  function field_exists($tablename,$field)
  {
    $field_exists = 0;
    $result = $this->db_query("SHOW COLUMNS FROM $tablename");
    while( $row = $this->db_fetch_array($result) ){
      if ($row['Field']==$field) $field_exists = 1;
    }
    return $field_exists;
  }

  function db_schema_setup()
  {
      if ($this->table_exists('email')){
        $this->db_query("DROP TABLE `outlook`.`email`");
      }
      $this->db_query("
        CREATE TABLE `outlook`.`email` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `senderName` LONGTEXT NULL ,
            `senderEmail` LONGTEXT NULL ,
            `cc` LONGTEXT NULL ,
            `bcc` LONGTEXT NULL ,
            `subject` LONGTEXT NULL ,
            `html_content` LONGTEXT NULL ,
            `folderid` LONGTEXT NULL ,
            `foldername` LONGTEXT NULL ,
            `timestamp` BIGINT NULL ,
            `is_primary` TINYINT(1) NULL ,
            `begtime` DATETIME NULL ,
            `endtime` DATETIME NULL ,
            PRIMARY KEY (`id`) ,
            UNIQUE INDEX `id_UNIQUE` (`id` ASC) 
        ) ENGINE = InnoDB");

      if ($this->table_exists('contacts')){
        $this->db_query("DROP TABLE `outlook`.`contacts`");
      }
      $this->db_query("
        CREATE TABLE `outlook`.`contacts` (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `name` LONGTEXT NULL ,
            `primaryemail` LONGTEXT NULL ,
            `secondaryemail` LONGTEXT NULL ,
            `phonenumber` LONGTEXT NULL ,
            PRIMARY KEY (`id`) ,
            UNIQUE INDEX `id_UNIQUE` (`id` ASC) 
        ) ENGINE = InnoDB");
      
  }
}   
?>
