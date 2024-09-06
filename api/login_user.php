<?php
include('../includes/db.php');

$email = $_POST['email'];
$password = $_POST['password'];

// Consulta para obtener el usuario con el correo proporcionado
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verificar si la contraseña ingresada coincide con la almacenada
    if (password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];  // Guardar el ID del usuario en la sesión
        header("Location: ../views/dashboard.php");
        exit();
    } else {
        header("Location: ../views/login.php?error=Contraseña incorrecta");
        exit();
    }
} else {
    header("Location: ../views/login.php?error=No existe un usuario con ese correo electrónico");
    exit();
}

$conn->close();
?>

