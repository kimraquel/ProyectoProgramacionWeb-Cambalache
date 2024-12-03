<?php
// Configuración de la conexión a la base de datos
$host = 'localhost'; // Cambia según tu configuración
$dbname = 'pcambalache';
$username = 'root';
$password = '';

try {
    // Crear conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;pcambalache=$dbdame;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Comprobar si el formulario fue enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Capturar datos del formulario
        $nombre = $_POST['user-first-name'];
        $id_articulo = $_POST['user-last-name'];
        $descripcion = $_POST['company-name'];
        $direccion = $_POST['user-address'];
        $categoria = $_POST['cat'];
        $fecha_publicacion = $_POST['fecha'];
        
        $imagen = $_POST['archivo'];

        // Subir archivo de imagen
        $imagen = null;
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            $directorio = 'uploads/';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }
            $nombre_imagen = basename($_FILES['archivo']['name']);
            $ruta_imagen = $directorio . $nombre_imagen;

            if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_imagen)) {
                $imagen = $ruta_imagen;
            }
        }

        // Insertar datos en la base de datos
        $query = "INSERT INTO almacen (nombre_articulo, id_articulo, descripcion, imagen, direccion, categoria, fecha_publicacion, estado) 
                  VALUES (:nombre_articulo, :id_articulo, :descripcion, :imagen, :direccion, :categoria, :fecha_publicacion)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nombre_articulo', $nombre);
        $stmt->bindParam(':id_articulo', $id_articulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':imagen', $imagen);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':fecha_publicacion', $fecha_publicacion);
        $stmt->execute();

        echo "Producto registrado con éxito.";
    }
} catch (PDOException $e) {
    echo "Error en la conexión o consulta: " . $e->getMessage();
}
?>