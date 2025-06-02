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

// Get selected plant from POST request
$selectedPlant = $_POST['plant'];

// Fetch departments based on the selected plant
$deptQuery = "SELECT DISTINCT dept FROM [Complaint].[dbo].[mob_safety_peptalk] WHERE plant = ?";
$params = array($selectedPlant);
$deptStmt = sqlsrv_query($conn, $deptQuery, $params);
if ($deptStmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Generate options for the dropdown
$options = "<option value=''>Select Department</option>";
while ($deptRow = sqlsrv_fetch_array($deptStmt, SQLSRV_FETCH_ASSOC)) {
    $options .= "<option value='".$deptRow['dept']."'>".$deptRow['dept']."</option>";
}

// Return the options
echo $options;

// Close the database connection
sqlsrv_close($conn);
?>
