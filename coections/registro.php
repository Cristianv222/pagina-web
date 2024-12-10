
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro</title>
  <link rel="stylesheet" href="../css/registro.css">
</head>
<body>
  <div class="login-container">
    <div class="login-box">
      <div class="icon">
        <img src="../images/user_register.svg" alt="User Icon" />
      </div>
      <h2>Regístrate</h2>
      <form id="register-form" action="./process_register.php" method="POST">
        <div class="input-group">
          <input type="text" id="username" name="nombre_usuario" placeholder="Nombre de usuario" required>
        </div>
        <div class="input-group">
          <input type="email" id="email" name="correo" placeholder="Correo electrónico" required>
        </div>
        <div class="input-group">
          <input type="password" id="password" name="contraseña" placeholder="Contraseña" required>
        </div>
        <div class="input-group">
          <input type="password" id="confirm-password" name="confirmar_contraseña" placeholder="Confirmar contraseña" required>
        </div>
        <button type="submit" class="btn login-btn">Registrarse</button>
        <a href="./login.php" class="btn register-btn">Volver</a>
      </form>
    </div>
  </div>
</body>
</html>