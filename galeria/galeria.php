<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../coections/login.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "galeria";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Subir archivos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivo"])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $tipo_archivo = '';

    // Obtener el archivo
    $archivo = $_FILES['archivo'];
    $nombre_archivo = $archivo['name'];
    $ruta_archivo = 'uploads/' . basename($nombre_archivo);
    $tipo_archivo = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));

    // Validar tipo de archivo
    if ($tipo_archivo === 'jpg' || $tipo_archivo === 'png' || $tipo_archivo === 'jpeg') {
        $tipo_archivo = 'imagen';
    } elseif ($tipo_archivo === 'mp4' || $tipo_archivo === 'avi') {
        $tipo_archivo = 'video';
    } else {
        echo "<script>alert('Solo se permiten imágenes o videos');</script>";
    }

    if (move_uploaded_file($archivo['tmp_name'], $ruta_archivo)) {
        // Insertar en la base de datos
        $sql = "INSERT INTO galeria (id_usuario, titulo, descripcion, ruta_archivo, tipo_archivo) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $_SESSION['id_usuario'], $titulo, $descripcion, $ruta_archivo, $tipo_archivo);
        $stmt->execute();
        echo "<script>alert('Archivo subido exitosamente');</script>";
    } else {
        echo "<script>alert('Error al subir el archivo');</script>";
    }
}

// Obtener las imágenes y videos de la base de datos
$sql = "SELECT * FROM galeria WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id_usuario']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galería</title>
  <link rel="stylesheet" href="../css/galeria.css">
  <style>
    /* General */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #214f619a, #0e4635);
    }

    .header {
      background-color: #333;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
    }

    .header h2 {
      margin: 0;
    }

    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      color: white;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .logout-btn {
      background-color: #e63946;
    }

    .logout-btn:hover {
      background-color: #c02735;
    }

    .upload-btn {
      background-color: #2a9d8f;
      width: 30%;
    }

    .upload-btn:hover {
      background-color: #21867a;
    }

    .gallery {
  display: flex;
  flex-wrap: wrap;
  gap: 15px; /* Espacio entre tarjetas */
  justify-content: center; /* Centra las tarjetas */
}

.gallery-item {
  width: 250px; /* Aumenta el tamaño de las tarjetas */
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  margin: 10px; /* Reduce el margen para que estén más juntas */
}

.gallery-item img,
.gallery-item video {
  width: 100%;
  height: 200px; /* Ajusta la altura para imágenes y videos */
  object-fit: cover; /* Ajusta para que las imágenes se vean bien */
}

.gallery-item-info {
  padding: 15px;
  text-align: center;
}

.gallery-item-info h4 {
  margin: 10px 0;
  font-size: 18px;
  font-weight: bold;
}

.gallery-item-info p {
  font-size: 14px;
  color: #666;
}

    /* Modal Styles */
    .modal {
      display: none; /* Ocultar por defecto */
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8); /* Fondo oscuro */
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }
    .modal img {
  max-width: 90%;
  max-height: 90%;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.modal .close {
  position: absolute;
  top: 20px;
  right: 20px;
  font-size: 30px;
  color: #fff;
  cursor: pointer;
  font-weight: bold;
  background: none;
  border: none;
}
    .modal.active {
      display: flex;
    }

    .modal-content {
      background: white;
      padding: 20px;
      border-radius: 8px;
      width: 90%;
      max-width: 500px;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .modal-header h3 {
      margin: 0;
    }

    .close-btn {
      background-color: transparent;
      border: none;
      font-size: 20px;
      cursor: pointer;
    }

    .close-btn:hover {
      color: red;
    }
  </style>
</head>
<body>
<div class="header">
    <h2>Galería de <?php echo $_SESSION['nombre_usuario']; ?></h2>
    <a href="../coections/logout.php" class="btn logout-btn">Cerrar Sesión</a>
  </div>

  <!-- Botón para abrir modal -->
  <div class="upload-container">
    <button id="open-modal-btn" class="btn upload-btn">Subir Nuevo Archivo</button>
  </div>

  <!-- Modal para subir archivos -->
  <div id="upload-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Subir Nuevo Archivo</h3>
        <button class="close-btn" id="close-modal-btn">&times;</button>
      </div>
      <form action="galeria.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="titulo" placeholder="Título" required><br>
        <textarea name="descripcion" placeholder="Descripción" required></textarea><br>
        <input type="file" name="archivo" accept="image/*,video/*" required><br>
        <button type="submit" class="btn upload-btn">Subir</button>
      </form>
    </div>
  </div>

  <!-- Galería -->
  <div class="gallery">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="gallery-item">
        <?php if ($row['tipo_archivo'] == 'imagen'): ?>
          <img src="<?php echo $row['ruta_archivo']; ?>" alt="<?php echo $row['titulo']; ?>">
        <?php elseif ($row['tipo_archivo'] == 'video'): ?>
          <video controls>
            <source src="<?php echo $row['ruta_archivo']; ?>" type="video/<?php echo pathinfo($row['ruta_archivo'], PATHINFO_EXTENSION); ?>">
          </video>
        <?php endif; ?>
        <div class="gallery-item-info">
          <h4><?php echo $row['titulo']; ?></h4>
          <p><?php echo $row['descripcion']; ?></p>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Modal para mostrar imágenes en grande -->
  <div id="image-modal" class="modal">
    <span class="close-btn" id="image-modal-close">&times;</span>
    <img id="image-modal-content" src="" alt="Imagen en grande">
  </div>

  <script>
    const modal = document.getElementById('upload-modal');
    const openModalBtn = document.getElementById('open-modal-btn');
    const closeModalBtn = document.getElementById('close-modal-btn');

    openModalBtn.addEventListener('click', () => {
      modal.classList.add('active');
    });

    closeModalBtn.addEventListener('click', () => {
      modal.classList.remove('active');
    });

    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.remove('active');
      }
    });

    document.querySelectorAll('.gallery-item img').forEach(image => {
  image.addEventListener('click', () => {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    modalImage.src = image.src;
    modal.style.display = 'flex';
  });
});

function closeModal() {
  const modal = document.getElementById('imageModal');
  modal.style.display = 'none';
}
  </script>
</body>
</html>


<?php $conn->close(); ?>