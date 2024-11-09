<?php
// Incluye tu archivo de conexión a la base de datos
include 'config.php';

// Verifica si se enviaron los datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura los datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if($password != $password2){
        echo "Las contraseñas no coinciden. Intente de nuevo!";
        echo '<meta http-equiv="refresh" content="4;url=http://i-uknow.online/registro.html">';
        exit();
    }
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashea la contraseña

    // Verifica que los datos no estén vacíos
    if (empty($username) || empty($email) || empty($_POST['password'])) {
        echo "Todos los campos son obligatorios.";
        exit();
    }

    // Prepara la consulta para verificar si el correo ya existe
    $stmt = $conn->prepare("SELECT * FROM Usuarios WHERE email = ? OR nombre_usuario = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica si hay resultados
    if ($result->num_rows > 0) {
        echo "El correo electrónico o el nombre de usuario ya están registrados. Por favor, intenta con otro.";
        echo '<meta http-equiv="refresh" content="3;url=http://i-uknow.online/registro.html">';
    } else {
        // Si no existe, procede a insertar el nuevo usuario
        $stmt = $conn->prepare("INSERT INTO Usuarios (nombre_usuario, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        // Verifica si la consulta fue exitosa
        if ($stmt->execute()) {
            echo "Registro exitoso.";
            // Espera un segundo antes de redirigir
            echo '<meta http-equiv="refresh" content="1;url=http://i-uknow.online/login.html">';
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;  // Imprime el error
        }
    }

    // Cierra la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
