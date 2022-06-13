<?php

include 'conexao.php';

#shell_exec("script.sh")

$arquivo = fopen ('/var/www/html/leitura.txt', 'r');
$insere = array();

while(!feof($arquivo)){
    	$insere[0] = explode('=',fgets($arquivo));
    	$insere[1] = explode(' ',fgets($arquivo));
    	$insere[2] = fgets($arquivo);
    }

$temp = substr($insere[0][1], 0, -3);
$total = substr($insere[1][8], 0, -1);
$livre = substr($insere[1][13], 0, -1);
$dia = new DateTime();
$hora = new DateTime();

echo $temp;echo "<br>";echo "<br>";
echo $total;echo "<br>";echo "<br>";
echo $livre;echo "<br>";echo "<br>";
echo $dia->format('Y/m/d');echo "<br>";echo "<br>";
echo $hora->format('H:i:s');echo "<br>";echo "<br>";

fclose($arquivo);

$conn = connection();

$sql = "INSERT INTO dados (temp, memTotal, memLivre, dia, hora) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$temp, $total, $livre, $dia->format('Y/m/d'), $hora->format('H:i:s')]);

$conn = closeConnection();

?>