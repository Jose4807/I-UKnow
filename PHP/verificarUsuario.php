<?php
include 'config.php'; // Asegúrate de que el archivo de configuración esté correcto

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Consulta para verificar el email en la base de datos
    $sql = "SELECT id_usuario, password FROM usuarios WHERE email = ?"; // Asegúrate que el nombre de la tabla y columnas son correctos
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Usuario encontrado, ahora verificar la contraseña
        $stmt->bind_result($id_usuario, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Contraseña correcta, iniciar sesión y redirigir
            session_start(); // Asegúrate de que la sesión se inicia antes de usarla
            $_SESSION["id_usuario"] = $id_usuario;
            $_SESSION["email"] = $email;
            echo "BIENVENID@!!!";
            // Espera un segundo antes de redirigir
            echo '<meta http-equiv="refresh" content="1;url=/I-UKnow/foro.php">';
            exit();
        } else {
            // Contraseña incorrecta
            echo "Contraseña incorrecta. <a href='/I-UKnow/login.html'>Inténtalo de nuevo</a> o <a href='/I-UKnow/registro.html'>Regístrate</a>.";
        }
    } else {
        // Usuario no encontrado
        echo "Usuario no encontrado. <a href='/I-UKnow/login.html'>Inténtalo de nuevo</a> o <a href='/I-UKnow/registro.html'>Regístrate</a>.";
    }

    // Cerrar la consulta
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>
