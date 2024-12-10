<?php
// Configuración de conexión a la base de datos
$servername = "localhost"; // Cambia a tu servidor de base de datos
$username = "root";        // Usuario de la base de datos
$password = "";            // Contraseña de la base de datos
$dbname = "galeria"; // Nombre de tu base de datos

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("<script>alert('Error al conectar con la base de datos');</script>");
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Capturar datos del formulario
    $nombre_usuario = $_POST['nombre_usuario'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $confirmar_contraseña = $_POST['confirmar_contraseña'];

    // Validar que las contraseñas coincidan
    if ($contraseña !== $confirmar_contraseña) {
        echo "<script>
                alert('Las contraseñas no coinciden');
                window.location.href = 'registro.php';
              </script>";
        exit;
    }

    // Encriptar la contraseña
    $contraseña_encriptada = password_hash($contraseña, PASSWORD_BCRYPT);

    // Insertar usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre_usuario, correo, contraseña) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre_usuario, $correo, $contraseña_encriptada);

    // Ejecutar la consulta y verificar resultados
    if ($stmt->execute()) {
        echo "<script>
                alert('¡Registro exitoso! Ahora puedes iniciar sesión.');
                window.location.href = './login.php';
              </script>";
    } else {
        if ($conn->errno === 1062) { // Código de error para duplicados (UNIQUE)
            echo "<script>
                    alert('El nombre de usuario o correo ya está en uso');
                    window.location.href = './registro.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error al registrar: {$conn->error}');
                    window.location.href = './registro.php';
                  </script>";
        }
    }

    // Cerrar la consulta
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>