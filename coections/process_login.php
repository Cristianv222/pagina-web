<?php
// Configuración de conexión a la base de datos
$servername = "localhost"; // Cambia según tu configuración
$username = "root";        // Usuario de la base de datos
$password = "";            // Contraseña de la base de datos
$dbname = "galeria"; // Nombre de la base de datos

// Conectar a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("<script>alert('Error al conectar con la base de datos');</script>");
}

// Procesar formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    // Buscar usuario en la base de datos
    $sql = "SELECT id, nombre_usuario, contraseña FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    // Validar credenciales
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verificar contraseña
        if (password_verify($contraseña, $user['contraseña'])) {
            // Inicio de sesión exitoso
            session_start();
            $_SESSION['id_usuario'] = $user['id'];
            $_SESSION['nombre_usuario'] = $user['nombre_usuario'];

            echo "<script>
                    alert('Inicio de sesión exitoso');
                    window.location.href = '../galeria/galeria.php'; // Redirigir a la página de usuario
                  </script>";
        } else {
            // Contraseña incorrecta
            echo "<script>
                    alert('Contraseña incorrecta');
                    window.location.href = './login.php';
                  </script>";
        }
    } else {
        // Usuario no encontrado
        echo "<script>
                alert('No se encontró un usuario con ese correo');
                window.location.href = './login.php';
              </script>";
    }

    $stmt->close();
}

$conn->close();
?>
