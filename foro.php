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
    <link href="CSS/style.css" rel="stylesheet">

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-ligth bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="foro.php">I-UKNOW</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION["id_usuario"])): ?>
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
                <?php if (isset($_SESSION["id_usuario"])): ?>
                    <div class="mb-4">
                        <form action="PHP/nuevaPublicacion.php" method="POST" enctype="multipart/form-data">
                            <h1>BIENVENIDO</h1>
                            <h4>Comienza a publicar!</h4>
                            <div class="mb-3">
                                <input type="text" name="titulo" class="form-control" placeholder="Título de la publicación" required>
                            </div>
                            <div class="mb-3">
                                <textarea name="contenido" class="form-control" rows="3" placeholder="Contenido de la publicación" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="FELIZOMETRO" class="form-label"></label>
                                <input type="number" name="FELIZOMETRO" id="FELIZOMETRO" class="form-control" placeholder="Felizómetro" required min="0" max="100">
                            </div>
                            <div class="mb-3">
                                <label for="SUERTE" class="form-label"></label>
                                <input type="text" name="SUERTE" id="SUERTE" class="form-control" placeholder="Número de la suerte" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            </div>
                            <div class="mb-3">
                                <input type="file" name="imagen" class="form-control" accept="image/*"> 
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Crear publicación</button>
                        </form>
                    </div>
                <?php else: ?>
                    <p class="text-center">Inicia sesión para crear una publicación.</p>
                <?php endif; ?>

                <?php
                $sql = "SELECT p.id_publicacion, p.titulo, p.contenido, p.fecha_creacion, p.fecha_actualizacion, p.imagen, p.id_usuario, u.nombre_usuario, p.FELIZOMETRO, p.SUERTE 
                        FROM Publicaciones p 
                        JOIN Usuarios u ON p.id_usuario = u.id_usuario 
                        ORDER BY p.fecha_creacion DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="card mb-3">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($row["titulo"]) . '</h5>';
                        echo '<p class="card-text">' . htmlspecialchars($row["contenido"]) . '</p>';
                        echo '<p class="card-text"><strong>Felizometro:</strong> ' . htmlspecialchars($row["FELIZOMETRO"]) . '</p>';
                        echo '<p class="card-text"><strong>Numero de la suerte:</strong> ' . htmlspecialchars($row["SUERTE"]) . '</p>';
                        
                        if ($row["imagen"]) {
                            echo '<img src="uploads/' . htmlspecialchars($row["imagen"]) . '" class="img-fluid mb-2" alt="Imagen de la publicación">';
                        }
                        echo '<br>';
                        echo '<small>Publicado por <strong>' . htmlspecialchars($row["nombre_usuario"]) . '</strong> en ' . $row["fecha_creacion"];
                        if ($row["fecha_creacion"] != $row["fecha_actualizacion"]) {
                            echo ' (editado el ' . $row["fecha_actualizacion"] . ')';
                        }
                        echo '</small>';

                        if ($row['id_usuario'] == $_SESSION['id_usuario']): ?>
                            <button class="btn btn-warning btn-sm" onclick="editarPublicacion('<?php echo $row['id_publicacion']; ?>', '<?php echo htmlspecialchars($row['titulo']); ?>', '<?php echo htmlspecialchars($row['contenido']); ?>')">Editar</button>
                            <form action="PHP/eliminarPublicacion.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id_publicacion" value="<?php echo $row['id_publicacion']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta publicación?');">Eliminar</button>
                            </form>
                        <?php endif;
                        

                        // Mostrar comentarios
                        echo '<div class="card-footer">';
                        $sqlComentarios = "SELECT c.contenido, c.imagen, c.fecha_comentario, u.nombre_usuario FROM Comentarios c JOIN Usuarios u ON c.id_usuario = u.id_usuario WHERE c.id_publicacion = " . $row['id_publicacion'];
                        $resultComentarios = $conn->query($sqlComentarios);

                        if ($resultComentarios->num_rows > 0) {
                            while ($comentario = $resultComentarios->fetch_assoc()) {
                                echo '<div class="mb-2">';
                                echo '<strong>' . htmlspecialchars($comentario['nombre_usuario']) . '</strong>: ' . htmlspecialchars($comentario['contenido']);
                                if ($comentario['imagen']) {
                                    echo '<br><img src="uploads/' . htmlspecialchars($comentario['imagen']) . '" class="img-thumbnail" alt="Imagen del comentario">';
                                }
                                echo '<br><small>' . $comentario['fecha_comentario'] . '</small>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No hay comentarios.</p>';
                        }

                        // Formulario para agregar comentario
                        if (isset($_SESSION['id_usuario'])) {
                            echo '<form action="PHP/nuevoComentario.php" method="POST" enctype="multipart/form-data" class="mt-3">';
                            echo '<input type="hidden" name="id_publicacion" value="' . $row['id_publicacion'] . '">';
                            echo '<textarea name="contenido" class="form-control mb-2" rows="2" placeholder="Escribe un comentario..." required></textarea>';
                            echo '<input type="file" name="imagen" class="form-control mb-2" accept="image/*">';
                            echo '<button type="submit" class="btn btn-primary btn-sm">Comentar</button>';
                            echo '</form>';
                        }

                        echo '</div>';
                        echo '</div>';
                        echo '<br>';
                        echo '<br>';echo '<br>';
                    }
                } else {
                    echo "<p>No hay publicaciones aún.</p>";
                }
                ?>
            </div>
        </div>
    </div>
            <!-- Modal para editar publicación -->
        <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarModalLabel">Editar Publicación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <form id="formEditar" action="PHP/editarPublicacion.php" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="id_publicacion" id="id_publicacion">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" name="titulo" id="titulo" required>
                            </div>
                            <div class="mb-3">
                                <label for="contenido" class="form-label">Contenido</label>
                                <textarea class="form-control" name="contenido" id="contenido" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/scripts.js"></script>
</body>
</html>
