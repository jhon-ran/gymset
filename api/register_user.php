<?php
include('../includes/db.php');

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$initial_weight = $_POST['initial_weight'] ?? null;

$sql = "INSERT INTO users (name, email, password, initial_weight) VALUES ('$name', '$email', '$password', '$initial_weight')";

if ($conn->query($sql) === TRUE) {
    echo "Usuario registrado exitosamente";
    header("Location: ../views/login.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
