<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['id_usuario'])) {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $id_usuario = $_SESSION['id_usuario'];

    // Procesar la imagen si se envió
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $nombreImagen = uniqid() . "_" . basename($_FILES["imagen"]["name"]);
        $ruta_imagen = "../uploads/" . $nombreImagen;

        if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta_imagen)) {
            echo "Error al subir la imagen. Por favor, revisa la ruta y los permisos.";
            exit();
        }
    } else {
        $ruta_imagen = null;
    }

    // Insertar la publicación en la base de datos
    $stmt = $conn->prepare("INSERT INTO Publicaciones (titulo, contenido, id_usuario, imagen) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $titulo, $contenido, $id_usuario, $ruta_imagen);
    $stmt->execute();

    // Redirige a la página principal
    header("Location: ../foro.php");
    exit();
}
?>

