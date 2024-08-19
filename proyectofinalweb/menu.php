<?php
// Conectar a la base de datos
$mysqli = new mysqli("localhost", "root", "", "reservas_db");

// Verificar la conexión
if ($mysqli->connect_error) {
    // Si hay un error de conexión, detiene la ejecución y muestra un mensaje
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Obtener el menú
$sql = "SELECT nombre_plato, precio, descripcion, imagen, tipo FROM menu";
$result = $mysqli->query($sql);

// Cerrar la conexión
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú del Restaurante</title>
    <style>
        /* Estilos CSS para la página del menú */
        body {
            font-family: Arial, sans-serif; /* Fuente para el cuerpo de la página */
            background-color: #f4f4f4; /* Color de fondo de la página */
            color: #333; /* Color del texto */
            margin: 0; /* Elimina el margen por defecto */
            padding: 0; /* Elimina el padding por defecto */
        }

        .container {
            width: 90%; /* Ancho del contenedor principal */
            max-width: 1200px; /* Ancho máximo del contenedor */
            margin: auto; /* Centra el contenedor */
            padding: 20px; /* Espaciado interno del contenedor */
        }

        h1 {
            text-align: center; /* Centra el texto del título */
            color: #4CAF50; /* Color del título */
            margin-bottom: 20px; /* Espacio debajo del título */
        }

        .section-title {
            color: #fff; /* Color del texto del título de la sección */
            background-color: #4CAF50; /* Color de fondo del título de la sección */
            border-radius: 5px; /* Bordes redondeados */
            padding: 10px; /* Espaciado interno del título de la sección */
            margin-bottom: 20px; /* Espacio debajo del título de la sección */
        }

        .menu-grid {
            display: grid; /* Usa una cuadrícula para los elementos del menú */
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* Define las columnas de la cuadrícula */
            gap: 20px; /* Espacio entre los elementos de la cuadrícula */
            margin-top: 20px; /* Espacio arriba de la cuadrícula del menú */
        }

        .menu-item {
            background-color: #fff; /* Color de fondo del elemento del menú */
            border-radius: 8px; /* Bordes redondeados */
            overflow: hidden; /* Oculta el contenido que desborda */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra alrededor del elemento */
            transition: transform 0.3s, box-shadow 0.3s; /* Transiciones suaves para los efectos de hover */
        }

        .menu-item:hover {
            transform: translateY(-10px); /* Eleva el elemento al pasar el ratón sobre él */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Sombra más pronunciada en hover */
        }

        .menu-item img {
            width: 100%; /* La imagen ocupa todo el ancho del contenedor */
            height: auto; /* Mantiene la proporción de la imagen */
            object-fit: cover; /* Ajusta la imagen para cubrir el contenedor sin distorsionarse */
        }

        .menu-info {
            padding: 15px; /* Espaciado interno del contenedor de información del menú */
            background-color: #f9f9f9; /* Color de fondo del contenedor de información del menú */
        }

        .menu-info h3 {
            margin: 0; /* Elimina el margen del título dentro del contenedor de información del menú */
            color: #333; /* Color del texto del título del elemento del menú */
        }

        .menu-info p {
            margin: 5px 0; /* Espacio arriba y abajo de los párrafos */
            color: #555; /* Color del texto del párrafo */
        }

        .menu-info p:first-of-type {
            font-weight: bold; /* Hace el primer párrafo más negrita */
        }

        .btn {
            display: inline-block; /* Muestra el botón en línea como un bloque */
            padding: 10px 20px; /* Espaciado interno del botón */
            margin-top: 20px; /* Espacio arriba del botón */
            background-color: #4CAF50; /* Color de fondo del botón */
            color: #fff; /* Color del texto del botón */
            text-align: center; /* Centra el texto dentro del botón */
            text-decoration: none; /* Elimina el subrayado del texto del botón */
            border-radius: 5px; /* Bordes redondeados del botón */
            font-weight: bold; /* Texto en negrita */
        }

        .btn:hover {
            background-color: #45a049; /* Color de fondo del botón en hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Menú del Restaurante</h1>
        <?php if ($result && $result->num_rows > 0): ?>
            <!-- Si hay resultados en la consulta, muestra las secciones de comida y bebida -->
            <h2 class="section-title">Comidas</h2>
            <div class="menu-grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <!-- Itera sobre cada fila del resultado -->
                    <?php if ($row['tipo'] == 'comida'): ?>
                        <!-- Si el tipo es comida, muestra el elemento del menú -->
                        <div class="menu-item">
                            <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['nombre_plato']); ?>">
                            <div class="menu-info">
                                <h3><?php echo htmlspecialchars($row['nombre_plato']); ?></h3>
                                <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
                                <p>Precio: Q<?php echo htmlspecialchars($row['precio']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            </div>

            <h2 class="section-title">Bebidas</h2>
            <div class="menu-grid">
                <?php
                // Reinicia el puntero de resultados para volver a recorrer el conjunto
                $result->data_seek(0); 
                ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <!-- Itera sobre cada fila del resultado para bebidas -->
                    <?php if ($row['tipo'] == 'bebida'): ?>
                        <!-- Si el tipo es bebida, muestra el elemento del menú -->
                        <div class="menu-item">
                            <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['nombre_plato']); ?>">
                            <div class="menu-info">
                                <h3><?php echo htmlspecialchars($row['nombre_plato']); ?></h3>
                                <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
                                <p>Precio: Q<?php echo htmlspecialchars($row['precio']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <!-- Mensaje cuando no hay elementos en el menú -->
            <p>No hay elementos en el menú.</p>
        <?php endif; ?>

        <!-- Botón para regresar al formulario -->
        <a href="index.html" class="btn">Regresar al Inicio</a>
        
        <!-- Botón para ver ubicación -->
        <a href="ubicacion.html" class="btn">Ver Ubicación</a>
    </div>
</body>
</html>
