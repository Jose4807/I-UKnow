<?php
// Incluye tu archivo de conexión a la base de datos
include 'config.php';

// Verifica si se enviaron los datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura los datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashea la contraseña

    // Prepara la consulta para verificar si el correo ya existe
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? OR nombre_usuario = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica si hay resultados
    if ($result->num_rows > 0) {
        echo "El correo electrónico o el nombre de usuario ya están registrados. Por favor, intenta con otro.";
        echo '<meta http-equiv="refresh" content="3;url=/I-UKnow/registro.html">';
    } else {
        // Si no existe, procede a insertar el nuevo usuario
        $stmt = $conn->prepare("INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            echo "Registro exitoso.";
            // Espera un segundo antes de redirigir
            echo '<meta http-equiv="refresh" content="1;url=/I-UKnow/login.html">';
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }
    }

    // Cierra la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
