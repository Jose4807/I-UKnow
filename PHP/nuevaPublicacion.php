<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION["id_usuario"])) {
        echo "Debe iniciar sesión para crear una publicación.";
        exit();
    }

    // Obtener el título y contenido de la publicación
    $titulo = $_POST["titulo"];
    $contenido = $_POST["contenido"];
    $id_usuario = $_SESSION["id_usuario"];

    // Insertar la publicación en la base de datos
    $sql = "INSERT INTO Publicaciones (titulo, contenido, id_usuario) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $titulo, $contenido, $id_usuario);
    if ($stmt->execute()) {
        header("Location: ../foro.php"); // Redirige al foro principal después de crear la publicación
        exit();
    } else {
        echo "Error al crear la publicación.";
    }
}
?>
