<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/login.css">

  <link rel="shortcut icon" href="../img/logo_sf.png">

  <title>Inicio de Sesión</title>
</head>
<body>

  <div class="login-container">
    <img src="../img/logo_cbtis.png" alt="DGETI Logo">
    
    <form id="loginForm" action="../controller/usuario/auth.php" method="POST">
      <div class="form-group">
        <label for="correo">Correo</label>
        <input type="text" id="correo" name="correo" />
      </div>

      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" />
      </div>

      <button type="submit" class="btn" id="loginBtn">Entrar</button>
    </form>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
      event.preventDefault();

      const formData = new FormData(this);

      fetch(this.action, {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            window.location.href = '../admin/index.php';
          } else {
            alert(data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Ocurrió un error al intentar iniciar sesión.');
        });
    });
  </script>
</body>
</html>
