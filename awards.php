<?php

include "includes/dbconnection.php";
include "includes/functions.php";

//These functions are explained in the includes/functions.php script
$OTNeeds = getTodaysNeeds();
$chargedEmployees = [];

//Match OTNeeds with Submissions
foreach($OTNeeds as $need)
{
    $shiftCode = calSchedule($need['OTDate'], $need['Shift']);
    $eligibleEmps = getEligibleEmployees($need['JobCode'], $shiftCode);
    
    $applicableSubmissions = getApplicableSubmissions($need['Slot']);

    $mostEligibleEmployee;
    $mostEligibleSubmission;

    print_r($eligibleEmps);

    foreach($applicableSubmissions as $submission)
    {
        $employee = getEmployee($submission['EmpID']);
		foreach($OTNeeds as $thisNeed)
		{
			if($submission['SubID'] == $thisNeed['submissionid']
			{
				
			}
			
		}
        if(!isset($mostEligibleEmployee))
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



