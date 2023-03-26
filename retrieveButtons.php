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


$query = "SELECT * FROM buttons";

$result = mysqli_query($connect, $query);

while($row = mysqli_fetch_array($result)){
    if ($row['description']=='luce'){
        $luce = $row['value'];
    }else{
        $ventola = $row['value'];
    }
}
echo "ventola=".$ventola."&luce=".$luce;

?>