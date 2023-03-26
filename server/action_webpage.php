<?php
$dbname = 'my_greenhous3';
$dbuser = 'greenhous3';
$dbpass = '8F8PZrBj2zss';
$dbhost = 'ftp.greenhous3.altervista.org';


$connect = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if(!$connect){
    echo"error: ".mysqli_connect_error();
    exit();
}

echo"connection success! <br><br>";


$id = $_GET['id'];
$value = $_GET['value'];


$sql = "UPDATE buttons SET value=$value WHERE id=$id";
if ($connect->query($sql) === TRUE) {
  echo "Valore aggiornato correttamente";
} else {
  echo "Errore durante l'aggiornamento del valore: ";
}



?>