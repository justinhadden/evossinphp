<?php

include "includes/dbconnection.php";

try
{
    $sql = "SELECT concat(OTDate,'-',Shift,'-',JobCode) AS Slot,OTDate,Shift,JobCode,OTNeedID
        FROM overtimeneed
        WHERE OTDate <= CURDATE() + 2";
    $OTNeeds = $pdo->query($sql);    
}
catch(PDOException $e)
{
	$myfile = fopen("firstfile.txt", "w");
	fwrite($myfile, $e);
	exit();
}