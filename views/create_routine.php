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

            <!-- Iteración sobre los días de la rutina, numerados del 1 al 7 -->
            <?php 
            // Números de día en lugar de nombres
            $days_of_routine = range(1, 7); // 1 al 7 representando los días de la rutina
            $stmt = $conn->prepare("SELECT * FROM exercises");
            $stmt->execute();
            $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($days_of_routine as $day_number): ?>
                <h4>Día <?php echo $day_number; ?></h4>
                
                <!-- Opción para marcar día de descanso -->
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rest_day_<?php echo $day_number; ?>" name="rest_days[]" value="<?php echo $day_number; ?>" onchange="toggleDayInputs(this, <?php echo $day_number; ?>)">
                    <label class="form-check-label" for="rest_day_<?php echo $day_number; ?>">Día de descanso</label>
                </div>

                <!-- Contenedor de Ejercicios (oculto si es día de descanso) -->
                <div class="exercise-container" id="exercise_container_<?php echo $day_number; ?>">
                    <?php foreach ($exercises as $exercise): ?>
                        <div class="exercise-item">
                            <label>
                                <input type="checkbox" name="exercises[<?php echo $day_number; ?>][]" value="<?php echo $exercise['id']; ?>">
                                <?php echo htmlspecialchars($exercise['name']); ?>
                            </label>

                            <div class="planned-inputs">
                                <label for="planned_sets_<?php echo $day_number; ?>_<?php echo $exercise['id']; ?>">Sets Planeados:</label>
                                <input type="number" name="planned_sets[<?php echo $day_number; ?>][<?php echo $exercise['id']; ?>]" min="1">

                                <label for="planned_repetitions_<?php echo $day_number; ?>_<?php echo $exercise['id']; ?>">Repeticiones Planeadas:</label>
                                <input type="number" name="planned_repetitions[<?php echo $day_number; ?>][<?php echo $exercise['id']; ?>]" min="1">

                                <label for="planned_weight_<?php echo $day_number; ?>_<?php echo $exercise['id']; ?>">Peso Planeado:</label>
                                <input type="number" step="0.1" name="planned_weight[<?php echo $day_number; ?>][<?php echo $exercise['id']; ?>]" min="0">
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
