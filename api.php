<?php
header("Content-Type: application/json");
$servername = "mysql-container:3306";
$username = "root";
$password = "rootpassword";
$dbname = "students_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

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

$conn->close();
?>