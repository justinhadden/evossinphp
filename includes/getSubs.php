<?php

include "includes/dbconnection.php";

try
{
    $sql = "SELECT concat(SubmissionDate,'-',Shift,'-',submission.JobCode) AS EmpSubmission,
        SubmissionDate,EmpComment,employee.EmpID,OTHoursWorked,OPOTHours,Shift,submission.JobCode,OTBlock,SubID
        FROM employee,submission
        WHERE employee.EmpID=submission.EmpID
        AND Awarded != 1
        AND SubmissionDate <= CURDATE() + 2";
    $OTSubmissions = $pdo->query($sql);    
}
catch(PDOException $e)
{
	$myfile = fopen("secondfile.txt", "w");
	fwrite($myfile, $e);
	exit();
}