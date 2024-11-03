<?php
include 'config.php'; // Incluye el archivo de configuración
session_start();

if (isset($_GET['id_publicacion']) && isset($_SESSION['id_usuario'])) {
    $id_publicacion = $_GET['id_publicacion'];

    // Eliminar la publicación
    $sql = "DELETE FROM Publicaciones WHERE id_publicacion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_publicacion);

    if ($stmt->execute()) {
        // Redirigir después de eliminar
        header("Location: ../foro.php"); // Cambia a la ruta de tu página principal
        exit;
    } else {
        echo "Error al eliminar la publicación: " . $conn->error;
    }
}
?>
