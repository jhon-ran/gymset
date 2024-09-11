<?php 
include('../includes/db.php'); 
session_start();
?>
<?php include('../includes/header.php');?>

<body>
    <div class="container">
        <h2>Crear Rutina Semanal</h2>
        <form action="../api/create_routine.php" method="POST">
            <div class="form-group">
                <label for="week_start_date">Fecha de inicio de la semana:</label>
                <input type="date" class="form-control" id="week_start_date" name="week_start_date" required>
            </div>

            <!-- Iteración sobre los días de la semana -->
            <?php 
            $days_of_week = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
            $stmt = $conn->prepare("SELECT * FROM exercises");
            $stmt->execute();
            $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($days_of_week as $index => $day): ?>
                <h4><?php echo $day; ?></h4>
                
                <!-- Opción para marcar día de descanso -->
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rest_day_<?php echo $index; ?>" name="rest_days[]" value="<?php echo $index; ?>" onchange="toggleDayInputs(this, <?php echo $index; ?>)">
                    <label class="form-check-label" for="rest_day_<?php echo $index; ?>">Día de descanso</label>
                </div>

                <!-- Contenedor de Ejercicios (oculto si es día de descanso) -->
                <div class="exercise-container" id="exercise_container_<?php echo $index; ?>">
                    <?php foreach ($exercises as $exercise): ?>
                        <div class="exercise-item">
                            <label>
                                <input type="checkbox" name="exercises[<?php echo $index; ?>][]" value="<?php echo $exercise['id']; ?>">
                                <?php echo htmlspecialchars($exercise['name']); ?>
                            </label>

                            <div class="planned-inputs">
                                <label for="planned_sets_<?php echo $index; ?>_<?php echo $exercise['id']; ?>">Sets Planeados:</label>
                                <input type="number" name="planned_sets[<?php echo $index; ?>][<?php echo $exercise['id']; ?>]" min="1">

                                <label for="planned_repetitions_<?php echo $index; ?>_<?php echo $exercise['id']; ?>">Repeticiones Planeadas:</label>
                                <input type="number" name="planned_repetitions[<?php echo $index; ?>][<?php echo $exercise['id']; ?>]" min="1">

                                <label for="planned_weight_<?php echo $index; ?>_<?php echo $exercise['id']; ?>">Peso Planeado:</label>
                                <input type="number" step="0.1" name="planned_weight[<?php echo $index; ?>][<?php echo $exercise['id']; ?>]" min="0">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <hr>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-primary">Crear Rutina</button>
        </form>
    </div>

    <script>
        // Función para mostrar u ocultar los ejercicios si es día de descanso
        function toggleDayInputs(checkbox, dayIndex) {
            const container = document.getElementById(`exercise_container_${dayIndex}`);
            container.style.display = checkbox.checked ? 'none' : 'block';
        }
    </script>
</body>

<?php include('../includes/footer.php'); ?>