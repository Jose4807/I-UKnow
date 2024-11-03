<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['id_usuario'])) {
    $id_publicacion = $_POST['id_publicacion'];

    $stmt = $conn->prepare("DELETE FROM Publicaciones WHERE id_publicacion = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id_publicacion, $_SESSION['id_usuario']);
    $stmt->execute();

    header("Location: ../foro.php");
    exit();
}
?>
