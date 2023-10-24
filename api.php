<?php
header("Content-Type: application/json");

// Azure SQL Server Connection
$servername = "r-modul-346-server.database.windows.net";
$username = "ronanski11";
$password = "LandesweitTier187";
$dbname = "db_students";

try {
    $conn = new PDO("sqlsrv:server=$servername;Database=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $data = json_decode(file_get_contents("php://input"), true);
  $firstname = $data["firstname"];
  $lastname = $data["lastname"];
  $age = $data["age"];
  $year = $data["year"];

  $sql = "INSERT INTO students (firstname, lastname, age, year) VALUES ('$firstname', '$lastname', '$age', '$year')";
  $result = $conn->query($sql);

  echo json_encode(["status" => $result ? "success" : "error"]);
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
  $sql = "SELECT * FROM students";
  $result = $conn->query($sql);
  $students = [];

  while ($row = $result->fetch_assoc()) {
    $students[] = $row;
  }

  echo json_encode($students);
}
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$conn = null;
?>