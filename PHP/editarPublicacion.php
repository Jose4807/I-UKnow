<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["id_usuario"])) {
    $id_publicacion = $_POST["id_publicacion"];
    $titulo = $_POST["titulo"];
    $contenido = $_POST["contenido"];

    // Verifica que el usuario sea el dueño de la publicación
    $sql = "SELECT id_usuario FROM Publicaciones WHERE id_publicacion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_publicacion);
    $stmt->execute();
    $stmt->bind_result($id_usuario_publicacion);
    $stmt->fetch();
    $stmt->close();

    if ($id_usuario_publicacion == $_SESSION["id_usuario"]) {
        // Actualiza la publicación
        $sql = "UPDATE Publicaciones SET titulo = ?, contenido = ? WHERE id_publicacion = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $titulo, $contenido, $id_publicacion);

        if ($stmt->execute()) {
            header("Location: ../foro.php?mensaje=editado");
        } else {
            echo "Error al actualizar la publicación.";
        }
        $stmt->close();
    } else {
        echo "No tienes permiso para editar esta publicación.";
    }
}

$conn->close();
?>
