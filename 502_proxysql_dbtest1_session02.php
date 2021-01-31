<?php
date_default_timezone_set('UTC');
$mysqli = new mysqli('127.0.0.1', 'app_user', 'test123', 'dbtest1', 3306);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

/* Setup */
echo date('Y-m-d H:i:s') . " Starting to SETUP the dbtest1\n";
$mysqli->query("DROP TABLE IF EXISTS joinit1");
$mysqli->query("CREATE TABLE IF NOT EXISTS `dbtest1`.`joinit1` (
  `i` bigint(11) NOT NULL AUTO_INCREMENT,
  `s` char(255) DEFAULT NULL,
  `t` datetime NOT NULL,
  `g` bigint(11) NOT NULL,
  KEY(`i`, `t`),
  PRIMARY KEY(`i`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");
$date1=date('Y-m-d H:i:s');
$mysqli->query("INSERT INTO dbtest1.joinit1 VALUES (NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )));");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");
$mysqli->query("INSERT INTO dbtest1.joinit1 SELECT NULL, uuid(), time('$date1'),  (FLOOR( 1 + RAND( ) *60 )) FROM dbtest1.joinit1;");

echo date('Y-m-d H:i:s') . " Starting to RUN the test on dbtest1.joinit1\n";

$result = $mysqli->query("SELECT MAX(i) FROM joinit1");
$row = $result->fetch_row();
sleep(2);
$date2=date('Y-m-d H:i:s');

for ($i=1; $i<$row[0]; $i++)
{
  $result = $mysqli->query("SELECT i FROM joinit1 WHERE i = $i");
  if($result->num_rows == 0)
    continue;

  $mysqli->query("UPDATE joinit1 SET t = '$date2' WHERE i = $i");

  $result = $mysqli->query("SELECT i FROM joinit1 WHERE t = '$date2' AND i = $i");
  if($result->num_rows == 0)
  {
      echo date('Y-m-d H:i:s') . " Dirty Read Detected on i $i . . .";
      usleep(500000);
      $result = $mysqli->query("SELECT i FROM joinit1 WHERE t = '$date2' AND i = $i");
      echo " After 500ms rows found $result->num_rows \n";
  } else {
    echo date('Y-m-d H:i:s') . " i $i is ok\n";
  }
}
$mysqli->close();
?>
