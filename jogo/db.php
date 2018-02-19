<?php

	try{
		//localhost
		//$pdo = new PDO("mysql:host=localhost;dbname=ayrto925_battleweb","ayrto925_ayrton","080594");
		//online
		$pdo = new PDO("mysql:host=localhost;dbname=battleweb","root","");
	}catch(PDOException $e){
		echo "Erro ao conectar ao sistema"; 	
	}
?>