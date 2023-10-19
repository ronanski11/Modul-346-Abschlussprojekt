<?php
header("Content-Type: application/json");

// Initialize MySQLi
$con = mysqli_init();

// Include Azure Storage Blob Client Library
require 'vendor/autoload.php';
use MicrosoftAzure\Storage\Blob\BlobRestProxy;

// Get SAS URL and Token from environment variables
$sas_url = getenv('SAS_URL');
$sas_token = getenv('SAS_TOKEN');

// Full URL to the blob
$blob_url = $sas_url . "?" . $sas_token;

// Download CA certificate
$ca_cert_content = file_get_contents($blob_url);

// Specify a local path to store the downloaded CA certificate
$local_path = "D:\\home\\site\\wwwroot\\ca_cert.pem";

// Save the CA certificate to the local path
file_put_contents($local_path, $ca_cert_content);

// Set SSL for Azure MySQL using the downloaded CA certificate
mysqli_ssl_set($con, NULL, NULL, $local_path, NULL, NULL);

// Connect to Azure MySQL database
if (!mysqli_real_connect($con, "rcsql.mysql.database.azure.com", "rcadmin", "LandesweitTier187", "students_db", 3306, MYSQLI_CLIENT_SSL)) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $firstname = $data["firstname"];
    $lastname = $data["lastname"];
    $age = $data["age"];
    $year = $data["year"];

    $sql = "INSERT INTO students (firstname, lastname, age, year) VALUES ('$firstname', '$lastname', '$age', '$year')";
    $result = mysqli_query($con, $sql);

    echo json_encode(["status" => $result ? "success" : "error"]);
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT * FROM students";
    $result = mysqli_query($con, $sql);
    $students = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }

    echo json_encode($students);
}

// Close the connection
mysqli_close($con);
?>
