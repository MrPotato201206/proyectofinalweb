<?php
// Conectar a la base de datos
$mysqli = new mysqli("localhost", "root", "", "reservas_db");

// Verificar la conexión
if ($mysqli->connect_error) {
    // Si hay un error en la conexión, muestra el mensaje de error y detiene la ejecución del script
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Procesar el registro si se ha enviado un formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre']; // Nombre del usuario
    $email = $_POST['email']; // Email del usuario
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Encriptar la contraseña utilizando un algoritmo de hash seguro

    // Preparar la consulta SQL para insertar los datos en la tabla 'usuarios'
    $stmt = $mysqli->prepare("INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)");
    // Vincular los parámetros a la consulta preparada. "sss" indica que se están vinculando tres cadenas de texto
    $stmt->bind_param("sss", $nombre, $email, $contraseña);

    // Ejecutar la consulta preparada
    if ($stmt->execute()) {
        // Si la ejecución es exitosa, muestra un mensaje de éxito y un enlace para volver al formulario
        echo "<p>¡Registro exitoso!</p>";
        echo "<a href='index.html'>Volver al formulario</a>";
    } else {
        // Si hay un error durante la ejecución, muestra el mensaje de error
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    // Cerrar la declaración preparada
    $stmt->close();
}

// Cerrar la conexión a la base de datos
$mysqli->close();
?>
