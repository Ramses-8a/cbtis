<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/login.css">
  <link rel="shortcut icon" href="../img/logo_sf.png">
  <title>Restablecer Contraseña</title>
</head>
<body>
  <div class="login-container">
    <img src="../img/logo_cbtis.png" alt="DGETI Logo">
    
    <?php
    require_once '../controller/conexion.php';

    $token = $_GET['token'] ?? '';
    $tokenValido = false;
    $mensaje = '';

    if ($token) {
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE token_ver = ? AND activo = 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $tokenValido = $result->num_rows > 0;
    }

    if (!$token || !$tokenValido) {
        echo '<div class="alert alert-danger">El enlace no es válido o ha expirado.</div>';
        echo '<div class="forgot-password"><a href="formulario_login.php">Volver al inicio de sesión</a></div>';
    } else {
    ?>
    <form id="resetForm" action="../controller/usuario/reset_password.php" method="POST">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      
      <div class="form-group">
        <label for="password">Nueva contraseña</label>
        <input type="password" id="password" name="password" required minlength="8" />
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirmar contraseña</label>
        <input type="password" id="confirm_password" name="confirm_password" required minlength="8" />
      </div>

      <button type="submit" class="btn" id="resetBtn">Cambiar contraseña</button>
    </form>
    <?php } ?>
  </div>

  <script>
    const form = document.getElementById('resetForm');
    if (form) {
      form.addEventListener('submit', function(event) {
        event.preventDefault();

        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
          alert('Las contraseñas no coinciden');
          return;
        }

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              alert('Tu contraseña ha sido actualizada correctamente.');
              window.location.href = 'formulario_login.php';
            } else {
              alert(data.message);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al intentar cambiar la contraseña.');
          });
      });
    }
  </script>
</body>
</html>