<?php

include "includes/dbconnection.php";
include "includes/functions.php";

//These functions are explained in the includes/functions.php script
$OTNeeds = getTodaysNeeds();
$OTSubmissions = getTodaysSubmissions();
$Employees = getAllEmployees();

//Match OTNeeds with Submissions
foreach($OTNeeds as $need)
{
    $shiftCode = calSchedule($need['OTDate'], $need['Shift']);
    $eligibleEmps = getEligibleEmps($need['JobCode'], $shiftCode);
    
    $submittedEmps = [];
    $applicableSubmissions = [];

    foreach($OTSubmissions as $submission)
    {
        if($submission['EmpSubmission'] == $need['Slot'])
        {
            array_push($applicableSubmissions, $submission);
        }
    }

    $mostEligibleEmployee;
    $mostEligibleSubmission;

    print_r($eligibleEmps);

    foreach($applicableSubmissions as $submission)
    {
        $employee = getEmployee($submission['EmpID']);
        if($mostEligibleEmployee == NULL)
        {
            $mostEligibleEmployee = $employee;
        }
        else
        {
            if($mostEligibleEmployee['OTHoursWorked']+$mostEligibleEmployee['OPOTHours'] > $employee['OTHoursWorked']+$employee['OPOTHours'])
            {

            }
        }
    }
}



