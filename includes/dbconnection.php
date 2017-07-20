<?php

try
{
	
	//Connect to db
	$pdo = new PDO("mysql:host=localhost;dbname=evoss", "root", "");
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	$myfile = fopen("dbfile.txt", "w");
	fwrite($myfile, $e);
	exit();
}