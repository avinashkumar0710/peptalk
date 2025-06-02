<?php
// Establish database connection
$serverName = "192.168.100.240";
$connectionInfo = array(
    "Database" => "complaint",
    "UID" => "sa",
    "PWD" => "Intranet@123"
);
$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch the least and the last date from the database
$dateRangeQuery = "SELECT MIN(cr_date) AS min_date, MAX(cr_date) AS max_date FROM [Complaint].[dbo].[mob_safety_peptalk]";
$dateRangeStmt = sqlsrv_query($conn, $dateRangeQuery);
if ($dateRangeStmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch the result
$dateRange = sqlsrv_fetch_array($dateRangeStmt, SQLSRV_FETCH_ASSOC);

// Format the dates as per your requirement
$minDate = $dateRange['min_date']->format('Y-m-d');
$maxDate = $dateRange['max_date']->format('Y-m-d');

// Return the date range as JSON
echo json_encode(array('minDate' => $minDate, 'maxDate' => $maxDate));

// Close the database connection
sqlsrv_close($conn);
?>
