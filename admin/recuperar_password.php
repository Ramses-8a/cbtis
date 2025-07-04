<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/login.css">
  <link rel="shortcut icon" href="../img/logo_sf.png">
  <title>Recuperar Contraseña</title>
  <style>
    .back-link {
      position: absolute;
      top: 20px;
      left: 20px;
      color: #dc3545;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <a href="formulario_login.php" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
    </svg>
    Volver al inicio de sesión
  </a>

  <div class="login-container">
    <img src="../img/logo_cbtis.png" alt="DGETI Logo">
    
    <form id="recoveryForm" action="../controller/usuario/recuperar_password.php" method="POST">
      <div class="form-group">
        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" required />
      </div>

      <button type="submit" class="btn" id="recoveryBtn">Enviar instrucciones</button>
    </form>
  </div>

  <script>
    document.getElementById('recoveryForm').addEventListener('submit', function(event) {
      event.preventDefault();

      const formData = new FormData(this);

      fetch(this.action, {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            alert('Se han enviado las instrucciones a tu correo electrónico.');
            window.location.href = 'formulario_login.php';
          } else {
            alert(data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Ocurrió un error al intentar recuperar la contraseña.');
        });
    });
  </script>
</body>
</html>