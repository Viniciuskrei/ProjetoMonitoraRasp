<?php

function connection(){
	$username = "root";
	$password = "";
	try {
		return $conn = new PDO('mysql:host=localhost;dbname=basedados', $username, $password);
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}
}

function closeConnection(){
	return $conn = null;
}

?>