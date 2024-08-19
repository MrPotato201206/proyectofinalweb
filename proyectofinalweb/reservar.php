<?php
// Conectar a la base de datos
$mysqli = new mysqli("localhost", "root", "", "reservas_db");

// Verificar la conexión
if ($mysqli->connect_error) {
    // Si hay un error en la conexión, muestra el mensaje de error y detiene la ejecución del script
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Procesar la reservación si se ha enviado un formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $email = $_POST['email']; // Email del usuario que hace la reserva
    $fecha_reserva = $_POST['fecha_reserva']; // Fecha de la reserva
    $hora_reserva = $_POST['hora_reserva']; // Hora de la reserva
    $cantidad_personas = $_POST['cantidad_personas']; // Cantidad de personas para la reserva

    // Obtener el ID del usuario basándose en el email proporcionado
    $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email); // Vincular el parámetro del email a la consulta
    $stmt->execute(); // Ejecutar la consulta
    $stmt->bind_result($usuario_id); // Vincular el resultado de la consulta al variable $usuario_id
    $stmt->fetch(); // Obtener el resultado
    $stmt->close(); // Cerrar la declaración preparada

    // Verificar si se obtuvo un ID de usuario válido
    if ($usuario_id) {
        // Preparar la consulta SQL para insertar la reserva en la tabla 'reservas'
        $stmt = $mysqli->prepare("INSERT INTO reservas (usuario_id, fecha_reserva, hora_reserva, cantidad_personas) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $usuario_id, $fecha_reserva, $hora_reserva, $cantidad_personas); // Vincular los parámetros a la consulta

        // Ejecutar la consulta de inserción
        if ($stmt->execute()) {
            // Si la ejecución es exitosa, muestra un mensaje de éxito y enlaces para ver el menú y las reservas
            echo "<p>¡Reservación exitosa!</p>";
            echo "<div style='margin-top: 20px;'>";
            echo "<a href='menu.php?email=" . urlencode($email) . "' style='padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; margin-right: 10px;'>Ver Menú</a>";
            echo "<a href='ver_reservas.php?email=" . urlencode($email) . "' style='padding: 10px 20px; background-color: #2196F3; color: white; text-decoration: none;'>Ver Reservaciones</a>";
            echo "</div>";
        } else {
            // Si hay un error durante la ejecución, muestra el mensaje de error
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close(); // Cerrar la declaración preparada
    } else {
        // Si no se encontró el usuario, muestra un mensaje de error
        echo "<p>Error: No se encontró el usuario.</p>";
    }
}

// Cerrar la conexión a la base de datos
$mysqli->close();
?>
