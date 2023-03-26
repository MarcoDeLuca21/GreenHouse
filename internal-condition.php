<?php
$dbname = 'my_greenhous3';
$dbuser = 'greenhous3';
$dbpass = '8F8PZrBj2zss';
$dbhost = 'ftp.greenhous3.altervista.org';


$connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$connect) {
    echo "error: " . mysqli_connect_error();
    exit();
}
$query = "SELECT * FROM PPM_project ORDER BY id DESC LIMIT 1";
$query_button = "SELECT * FROM buttons";
$result_button = mysqli_query($connect, $query_button);
$result = mysqli_query($connect, $query);
$i = 0;
if($result_button){
	while($row = mysqli_fetch_array($result_button)) {
		$arrayButtons [$i] = $row['value'];
        $i++;
    }
}else {
   echo 'Non sono riuscito a recuperare i valori';
die();
}
if ($result) {
    $status = mysqli_fetch_array($result);
    $temperatura = $status['temperatura'];
    $umidita_aria = $status['umidita_aria'];
    $umidita_terra = $status['umidita_terra'];
    $livello_acqua = $status['livello_acqua'];
    
   
    $queryLuce = "SELECT * FROM buttons WHERE description = 'luce' ORDER BY id DESC LIMIT 1";
    $resultLuce = mysqli_query($connect, $queryLuce);

    if($resultLuce){
    $statusLuce= mysqli_fetch_array($resultLuce)['value'];}

    $queryVentola = "SELECT * FROM buttons WHERE description = 'ventola' ORDER BY id DESC LIMIT 1";
    $resultventola = mysqli_query($connect, $queryVentola);

    if($resultventola){
    $statusVentola= mysqli_fetch_array($resultventola)['value'];}

    /*if (!$livello_acqua) {
        $stile_liv_acqua = 'style="color:red"';
    } else {
        $stile_liv_acqua = 'style="color:green"';
    }

    if ($temperatura <= 5 || $temperatura >= 30) {
$stile_temp = 'style="color:red"';
} else {
$stile_temp = 'style="color:green"';
}

if ($umidita_aria <= 5 || $umidita_aria >= 30) {
$stile_umi_aria = 'style="color:red"';
} else {
$stile_umi_aria = 'style="color:green"';
}

if ($umidita_aria <= 5 || $umidita_terra >= 30) {
$stile_umi_terra = 'style="color:red"';
} else {
$stile_umi_terra = 'style="color:green"';
}*/

if ($statusLuce) {
    $check_luce = 'checked';
} else {
    $check_luce = '';
}

if ($statusVentola) {
    $check_ventola = 'checked';
} else {
    $check_ventola = '';
}

} else {
echo 'Non sono riuscito a recuperare i valori';
die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Green House</title>
    <meta name="description" content="Green House site">
    <meta name="keywords" content="ppm elaborato, green house, plants, arduino">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maxiumum=1.0">
    <link rel="stylesheet" href="style.css" type="text/css">
    <link rel="stylesheet" href="stylebutton.css" type="text/css">
    <link rel="stylesheet" href="styleMobile.css" type="text/css">
    <link rel="stylesheet" href="styleTable.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        
        var valueVentola = <?php echo $arrayButtons[0]?> == 1;
        var valueLuce = <?php echo $arrayButtons[1]?> == 1;    
        
        function onClickCheckButton(elem, id) {
                var value = elem.checked ? 1 : 0;
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function(){
                    if (xmlhttp.readyState==4 && xmlhttp.status==200){
                        alert("Valore aggiornato nel db");
                    }
                }
                xmlhttp.open("GET", "http://greenhous3.altervista.org/server/action_webpage.php?id=" + id + "&value=" + value, true);
                xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                xmlhttp.send(); 
         }
        
    </script>

</head>

<body>
<nav class="navbar">
    <div class="logo">
        <a href="index.html" title="Green House" id="brand">
            <img src="image/white-leaf.png" alt="logo Green House" width="50" height="50">
        </a>
        <a href="index.html" title="Green House" id="title">
            Green House
        </a>
    </div>

    <div class="container1">
        <div class="link">
            <a href="index.html" title="home">
                <img src="image/home-logo.png" alt="home" width="15" height="15">
                Home
            </a>
        </div>
        <div class="link">
            <a href="internal-condition.php" title="internal condition">
                <img src="image/white-cloud.png" alt="internal condition" width="20px" height="20px">
                Internal condition
            </a>
        </div>
        <!--<div class="link">
            <a href="summary.html" title="summary">
                <img src="image/summary.png" alt="summary" width="20" height="20" id="brandsummary">
                Summary
            </a>
        </div>-->
    </div>
</nav>

<section class="container-table">
    <div class="block-home">
        <div class="subcontainer2">
            <div id="title-blockinternal">
                <h3>
                    COME FUNZIONA GREEN HOUSE?
                </h3>
                <hr>
            </div>
            <div id="para-internal">
                Attraverso questa pagina puoi gestire la serra in base alle tue esigenze.<br>
                Nelle tabelle presenti potrai visualizzare i valori attuali dei sensori. Inoltre potrai effetturare azioni come
                accensione e spegnimento di una ventola e una lampada con un semplice click così da modificare le condizioni
                climatiche all'interno della tua serra.
            </div>
            <div class="Action-table">
                <table class="styled-table">
                    <thead>
                    <tr class="title-table"><td>SENSORI</td><td>AZIONI</td></tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <div class="loghi-tabella">
                                <img src="image/ventola.png" alt="logo ventola" width="50" height="45">
                                <p>Ventola</p>
                            </div>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" id = "check_ventola" onclick = "onClickCheckButton(this, 1)">
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="loghi-tabella">
                                <img src="image/luce.png" alt="logo Luce" width="50" height="50">
                                <p>Luce</p>
                            </div>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" id = "check_luce" onclick = "onClickCheckButton(this, 2)">
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="Sensor-table">
            <table class="styled-table">
                <thead>
                <tr class="title-table">
                    <td>SENSORI</td>
                    <td>VALORI ATTUALI</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div class="loghi-tabella">
                            <img src="image/humidity.png" alt="logo Umidità" width="50" height="45">
                            <p>Umidità Aria</p>
                        </div>
                    </td>
                    <td <?php echo $stile_umi_aria ?>><?php echo $umidita_aria ?> %</td>
                </tr>
                <tr>
                    <td>
                        <div class="loghi-tabella">
                            <img src="image/umiditaterra.png" alt="logo Umidità" width="50" height="45">
                            <p>Umidità Terra</p>
                        </div>
                    </td>
                    <td <?php echo $stile_umi_terra ?>><?php echo $umidita_terra ?> %</td>
                </tr>
                <tr>
                    <td>
                        <div class="loghi-tabella">
                            <img src="image/temp-png.jpg" alt="logo Temperatura" width="50" height="50">
                            <p>Temperatura</p>
                        </div>
                    </td>
                    <td <?php echo $stile_temp ?>><?php echo $temperatura ?> °C</td>
                </tr>
                <tr>
                    <td>
                        <div class="loghi-tabella">
                            <img src="image/water-level.png" alt="logo Livello Acqua" width="50" height="50">
                            <p id="livelloAcqua">Livello acqua</p>
                        </div>
                    </td>
                    <td <?php echo $stile_liv_acqua ?>><?php echo $livello_acqua ?> %</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
	//aggiorno i checkbox rispetto ai valori letti da DB
	document.getElementById("check_ventola").checked = valueVentola;
    document.getElementById("check_luce").checked = valueLuce;
    
    //refresh della pagina ogni tot 25k millisecondi
    setTimeout(function(){
      window.location.reload(1);
	}, 25000);
</script>

<section class="imagine">
    <img src="image/possibile_sfondo2.jpg">
</section>

<footer class="copyright">
    <div>
        <p>
            Copyright © 2022
            <br>
            Marco De Luca, Matteo Ciucani
        </p>
    </div>
    <div class="logoUNI">
        <img src="image/Logo_universita_firenze.png" width="200" height="100">
    </div>
</footer>
</body>

</html>