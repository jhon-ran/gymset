<?php
include('../includes/db.php');
session_start();

// Obtener todas las rutinas semanales del usuario actual
$stmt = $conn->prepare("SELECT * FROM weekly_routines WHERE user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$routines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../includes/header.php');?>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <!-- Barra de navegación aquí (igual que en el ejemplo anterior) -->
</nav>

<div class="container mt-5">
    <h2>Gestionar Rutinas Semanales</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fecha de Inicio</th>
                <th>Cambio de Peso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($routines as $routine): ?>
            <tr>
                <td><?php echo $routine['week_start_date']; ?></td>
                <td><?php echo $routine['weight_change']; ?> kg</td>
                <td>
                    <a href="edit_routine.php?id=<?php echo $routine['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                    <a href="../api/delete_routine.php?id=<?php echo $routine['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta rutina?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
</body>
