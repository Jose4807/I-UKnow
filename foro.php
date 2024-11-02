<?php
include 'PHP/config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro - Página Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
        <a class="navbar-brand" href="#">Foro</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Categorías</a>
            </li>
            <li class="nav-item">
                <?php if(isset($_SESSION["id_usuario"])): ?>
                    <a class="nav-link" href="PHP/logout.php">Cerrar sesión</a>
                <?php else: ?>
                    <a class="nav-link" href="login.html">Iniciar sesión</a>
                <?php endif; ?>
            </li>
            </ul>
        </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
        <div class="col-lg-8">
            <?php if(isset($_SESSION["id_usuario"])): ?>
                <div class="mb-4">
                    <form action="PHP/nuevaPublicacion.php" method="POST">
                        <div class="mb-3">
                            <input type="text" name="titulo" class="form-control" placeholder="Título de la publicación" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="contenido" class="form-control" rows="3" placeholder="Contenido de la publicación" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Crear publicación</button>
                    </form>
                </div>
            <?php else: ?>
                <p class="text-center">Inicia sesión para crear una publicación.</p>
            <?php endif; ?>

            <?php
            // Obtener todas las publicaciones
            $sql = "SELECT p.titulo, p.contenido, p.fecha_creacion, u.nombre_usuario FROM Publicaciones p JOIN Usuarios u ON p.id_usuario = u.id_usuario ORDER BY p.fecha_creacion DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="card mb-3">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row["titulo"]) . '</h5>';
                    echo '<p class="card-text">' . htmlspecialchars($row["contenido"]) . '</p>';
                    echo '<div class="d-flex justify-content-between">';
                    echo '<small>Publicado por <strong>' . htmlspecialchars($row["nombre_usuario"]) . '</strong> en ' . $row["fecha_creacion"] . '</small>';
                    echo '<a href="#" class="btn btn-link">Comentarios</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>No hay publicaciones aún.</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
