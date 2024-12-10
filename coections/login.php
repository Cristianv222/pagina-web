<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio de Sesión</title>
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <div class="login-container">
    <div class="login-box">
      <div class="icon">
        <img src="../images/user.svg" alt="User Icon" />
      </div>
      <h2>Inicia Sesión</h2>
      <form id="login-form" action="./process_login.php" method="POST">
  <div class="input-group">
    <input type="email" id="email" name="correo" placeholder="Correo electrónico" required>
  </div>
  <div class="input-group">
    <input type="password" id="password" name="contraseña" placeholder="Contraseña" required>
  </div>
  <div class="options">
    <label><input type="checkbox" id="remember-me" name="recordarme"> Recordarme</label>
  </div>
  <button type="submit" class="btn login-btn">Iniciar Sesión</button>
  <a href="./registro.php" class="btn register-btn">Registrarse</a>
</form>
    </div>
  </div>
  <script src="scripts.js"></script>
</body>
</html>