<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["id_usuario"])) {
    $titulo = $_POST["titulo"];
    $contenido = $_POST["contenido"];
    $valor1 = intval($_POST["FELIZOMETRO"]);
    $valor2 = intval($_POST["SUERTE"]);
    $id_usuario = $_SESSION["id_usuario"];
    $imagen = '';

    // Manejo de la imagen si se sube
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {
        $nombreImagen = time() . '_' . $_FILES["imagen"]["name"];
        move_uploaded_file($_FILES["imagen"]["tmp_name"], "../uploads/" . $nombreImagen);
        $imagen = $nombreImagen;
    }

    $sql = "INSERT INTO Publicaciones (id_usuario, titulo, contenido, imagen, FELIZOMETRO, SUERTE) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssii", $id_usuario, $titulo, $contenido, $imagen, $valor1, $valor2);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: ../foro.php");
    exit();
}
?>
