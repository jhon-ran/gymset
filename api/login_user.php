<?php
include('../includes/db.php');

$email = $_POST['email'];
$password = $_POST['password'];

try {
    // Preparar la consulta SQL con PDO
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Obtener el usuario correspondiente
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verificar la contraseña ingresada
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
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
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


