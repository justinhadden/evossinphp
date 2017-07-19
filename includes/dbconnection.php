<?php

try
{
	
	//Connect to db
	$pdo = new PDO("mysql:host=localhost;dbname=justhrlm_EVOSS", "justhrlm_Justin", "grav1949");
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	$myfile = fopen("dbfile.txt", "w");
	fwrite($myfile, $e);
	exit();
}