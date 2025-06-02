<?php 
session_start();
if (!isset($_SESSION["emp_num"])) {   
        header("location:login.php");
    }

    $sessionemp= $_SESSION["emp_num"];

    // Add '00' in front if session value has only 6 digits
    if(strlen($sessionemp) == 6) {
        $sessionemp = '00' . $sessionemp;
    }


    //echo 'empno' .$sessionemp;

    // Database Connection
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

    $name = "SELECT emp_name, access, dept_code FROM EA_webuser_tstpp WHERE emp_num = ?";    //for user name show in header
    $params = array($_SESSION['emp_num']);
    $stmt = sqlsrv_query($conn, $name, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($stmt)) {
        // Get the user name from the result set
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $username = $row['emp_name'];
        $access = $row['access'];
        $deptcode =$row['dept_code'];
    } 

    // Fetch data from the database
$sql = "SELECT * FROM [Complaint].[dbo].[mob_safety_peptalk] order by cr_date desc";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PepTalk | Home</title>
    <link rel="icon" href="">
    
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include jQuery library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Include Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style>
        /* Styling for fixed header and scrollable body */
        .table-container {
            max-height: 600px; /* Set the maximum height for the table */
            overflow-y: auto; /* Enable vertical scrollbar */
        }
        th, td {
            padding: 8px; /* Add padding to table cells */
        }
        thead th {
            position: sticky;
            top: 0; /* Stick the header to the top of the container */
            background-color: #f9f9f9; /* Background color for the header */
            z-index: 1; /* Ensure the header stays above other content */
        }
        .data-row {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding :20px;
    }

    .image-column,
    .details-column {
        flex: 1;
        background-color: white;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .image-column {
        margin-right: 20px;
    }
    </style>
</head>
<body>
    <div class='card text-center'>
        <div class='card-header'>
            <b> Welcome to Peptalk <i><span style='background-color:yellow'><?php echo $username; ?></span></i></b>&nbsp;&nbsp;
            <a href='signout.php'><i class="far fa-power-off"></i><input type='submit' class='btn btn-success btn-sm' value='LOGOUT'></a>&nbsp;
        </div>
    </div>

    <div class="container mt-4">

    <h2>Filter Data</h2>
        <form method="post" action="">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="plant">Plant:</label>
                    <select class="form-select" id="plant" name="plant">
                        <option value="">Select Plant</option>
                        <?php
                        // Fetch plants from the database
                        $plantQuery = "SELECT DISTINCT plant FROM [Complaint].[dbo].[mob_safety_peptalk]";
                        $plantStmt = sqlsrv_query($conn, $plantQuery);
                        if ($plantStmt === false) {
                            die(print_r(sqlsrv_errors(), true));
                        }
                        while ($plantRow = sqlsrv_fetch_array($plantStmt, SQLSRV_FETCH_ASSOC)) {
                            echo "<option value='".$plantRow['plant']."'>".$plantRow['plant']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="dept">Department:</label>
                    <select class="form-select" id="dept" name="dept">
                        <option value="">Select Department</option>
                        <!-- Options will be populated based on the selected plant using JavaScript -->
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fromDate">From Date:</label>
                    <input type="text" class="form-control datepicker" id="fromDate" name="fromDate">
                </div>
                <div class="col-md-3">
                    <label for="toDate">To Date:</label>
                    <input type="text" class="form-control datepicker" id="toDate" name="toDate">
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

        
<?php

$imageBaseUrl = "http://192.168.100.13/peptalk/";
// Database Connection
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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data based on selected values and date range
    $plant = isset($_POST['plant']) ? $_POST['plant'] : '';
    $dept = isset($_POST['dept']) ? $_POST['dept'] : '';
    $fromDate = isset($_POST['fromDate']) ? $_POST['fromDate'] : '';
    $toDate = isset($_POST['toDate']) ? $_POST['toDate'] : '';

    // Build the SQL query based on the selected values
    $sql = "SELECT 
                plant,
                orgeh,
                cr_date,
                nos_participient,
                dept,
                cr_empno,
                plant_desc,
                file1
            FROM 
                [Complaint].[dbo].[mob_safety_peptalk]
            WHERE 1=1";

    if (!empty($plant)) {
        $sql .= " AND plant = '$plant'";
    }

    if (!empty($dept)) {
        $sql .= " AND dept = '$dept'";
    }

    if (!empty($fromDate) && !empty($toDate)) {
        $sql .= " AND cr_date BETWEEN '$fromDate' AND '$toDate'";
    }

    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
}

?>
    <h2 class="mt-4">Peptalk Data</h2>
        <div class="table-container">
        <table class="table table-bordered border-success" border="3">
            <!-- <thead style='position: sticky; top: 0; background-color: beige;z-index: 1;'>
                <tr>
                <th>SL No.</th> 
                    <th>Plant</th>
                    <th>Orgeh</th>
                    <th>Creation Date</th>                    
                    <th>No. of Participants</th>
                    <th>Department</th>
                    <th>Employee Number</th>
                    <th>Plant Description</th>
                    <th>File</th>
                </tr>
            </thead> -->
            <tbody>
                <?php
                // Fetch and display data rows
                $serialNo = 1; // Initialize serial number
                $stmt = sqlsrv_query($conn, $sql);
                if ($stmt === false) {
                    // Handle query execution error
                    die(print_r(sqlsrv_errors(), true));
                } else {
                    // Proceed with fetching and displaying data
                    // Fetch and display data rows
                    $serialNo = 1; // Initialize serial number
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                     
                        echo "<div class='data-row'>";
    
    // Image column
    echo "<div class='image-column'>";
    // Construct complete image path using base URL
    $imagePath = $imageBaseUrl.$row['file1'];
    // Display image with specified height and width
    echo "<img src='".$imagePath."' height='100' width='100' />";
    echo "</div>"; // Close image-column

                // Details column
echo "<div class='details-column'>";
// Display "created by", "empno", and "no of participants"
echo "<h4><b><u><i>Created by:</b></i></u></h4>";

// Remove '00' from the beginning of cr_empno if it exists
$cr_empno = $row['cr_empno'];
if (substr($cr_empno, 0, 2) === '00') {
    $cr_empno = substr($cr_empno, 2);
}

echo "<p><span style='color:blue'><b>Employee No: </b> </span>".$cr_empno."</p>";

// Fetch employee name based on cr_empno
$empNameQuery = "SELECT emp_name FROM [Complaint].[dbo].[EA_webuser_tstpp] WHERE emp_num = ?";
$params = array($cr_empno);
$empNameStmt = sqlsrv_query($conn, $empNameQuery, $params);

if ($empNameStmt === false) {
    die(print_r(sqlsrv_errors(), true)); // Print SQL errors
}

$empName = "";
if (sqlsrv_has_rows($empNameStmt)) {
    $empRow = sqlsrv_fetch_array($empNameStmt, SQLSRV_FETCH_ASSOC);
    $empName = $empRow['emp_name'];
} else {
    $empName = "Employee not found"; // If no rows returned
}

echo "<p><span style='color:green'><b>Employee Name:</b></span> ".$empName."</p>";
echo "<p><span style='color:#01497c'><b>No of Participants: </b></span> ".$row['nos_participient']."</p>";
echo "</div>";



    echo "</div>"; // Close data-row
    $serialNo++; // Increment serial number for next row
                    }
                }
                ?>
            </tbody>
        </table>
        </div>
    </div>

     <!-- Include Bootstrap Datepicker JS -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        // Add JavaScript code for dynamic dept dropdown and datepickers
        $(document).ready(function(){
            // Initialize Datepickers
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            // Populate Dept dropdown based on selected Plant
            $('#plant').change(function(){
                var selectedPlant = $(this).val();
                $.ajax({
                    url: 'get_departments.php', // Assuming you have a separate PHP file to fetch departments based on plant
                    type: 'post',
                    data: {plant: selectedPlant},
                    success: function(response) {
                        $('#dept').html(response);
                    }
                });
            });
        });
    </script>
</body>
<?php include 'footer.php';?>
</html>
<?php
// Close connection
sqlsrv_close($conn);
?>
