<?php
// Verificar si el email está presente en la URL, si no está presente, se asigna una cadena vacía
$email = $_GET['email'] ?? '';

// Conectar a la base de datos
$mysqli = new mysqli("localhost", "root", "", "reservas_db");

// Verificar la conexión
if ($mysqli->connect_error) {
    // Si hay un error en la conexión, muestra el mensaje de error y detiene la ejecución del script
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Inicializar el array de reservas
$reservas = [];

// Verificar si el email no está vacío
if (!empty($email)) {
    // Preparar la consulta SQL para obtener el ID del usuario basado en el email proporcionado
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $email); // Vincular el parámetro del email a la consulta
    $stmt->execute(); // Ejecutar la consulta
    $result = $stmt->get_result(); // Obtener el resultado

    // Verificar si se obtuvo un ID de usuario
    if ($row = $result->fetch_assoc()) {
        $usuario_id = $row['id'];

        // Preparar la consulta SQL para obtener las reservas del usuario basándose en el ID
        $sql = "SELECT * FROM reservas WHERE usuario_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $usuario_id); // Vincular el parámetro del ID de usuario a la consulta
        $stmt->execute(); // Ejecutar la consulta
        $result = $stmt->get_result(); // Obtener el resultado

        // Recoger todas las reservas en el array $reservas
        while ($row = $result->fetch_assoc()) {
            $reservas[] = $row;
        }
    } else {
        // Si el email no se encuentra, muestra un mensaje de error y detiene la ejecución
        echo "Email no encontrado.";
        exit();
    }

    $stmt->close(); // Cerrar la declaración preparada
} else {
    // Si el email no está presente, muestra un mensaje de error
    echo "Email no proporcionado.";
}

// Cerrar la conexión a la base de datos
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="styles.css"> <!-- Enlazar el archivo de estilos CSS -->
</head>
<body>
    <div class="container">
        <h1>Mis Reservas</h1>
        <?php if (!empty($reservas)): ?>
            <!-- Si hay reservas, se muestra una tabla con los detalles -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Fecha de Reserva</th>
                        <th>Hora de Reserva</th>
                        <th>Cantidad de Personas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <!-- Mostrar cada reserva en una fila de la tabla -->
                        <tr>
                            <td><?php echo htmlspecialchars($reserva['id']); ?></td> <!-- ID de la reserva -->
                            <td><?php echo htmlspecialchars($email); ?></td> <!-- Email del usuario -->
                            <td><?php echo htmlspecialchars($reserva['fecha_reserva']); ?></td> <!-- Fecha de la reserva -->
                            <td><?php echo htmlspecialchars($reserva['hora_reserva']); ?></td> <!-- Hora de la reserva -->
                            <td><?php echo htmlspecialchars($reserva['cantidad_personas']); ?></td> <!-- Cantidad de personas -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <!-- Si no hay reservas, se muestra un mensaje -->
            <p>No tienes reservas.</p>
        <?php endif; ?>
        <!-- Enlace para volver al menú -->
        <a href="menu.php?email=<?php echo urlencode($email); ?>" class="back-button">Volver al Menú</a>
    </div>
</body>
</html>
