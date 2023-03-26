<?php
$dbname = 'my_greenhous3';
$dbuser = 'greenhous3';
$dbpass = '8F8PZrBj2zss';
$dbhost = 'ftp.greenhous3.altervista.org';


$connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if(!$connect){
    echo"error: ".mysqli_connect_error();
    exit(); 
}

echo"connessione effettuata<br><br>"; 

$temperatura = mysqli_real_escape_string($connect, $_REQUEST["temperatura"] ) ;
$umidita_aria = mysqli_real_escape_string($connect, $_REQUEST["umidita_aria"] );
$umidita_terra = mysqli_real_escape_string($connect, $_REQUEST["umidita_terra"] );
$livello_acqua = mysqli_real_escape_string($connect, $_REQUEST["livello_acqua"] );

$query = "INSERT INTO PPM_project (temperatura, umidita_aria, umidita_terra, livello_acqua) VALUES ('$temperatura', '$umidita_aria', '$umidita_terra', '$livello_acqua')";

$result = mysqli_query($connect, $query);
if($result){
echo 'inserimento effettuato';
}else{
  echo'è andata male';
}
?>