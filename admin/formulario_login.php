<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/login.css">
  <link rel="shortcut icon" href="../img/logo_sf.png">
  <title>Inicio de Sesión</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <div class="login-container">
    <img src="../img/logo_cbtis.png" alt="DGETI Logo">
    
    <form id="loginForm" action="../controller/usuario/auth.php" method="POST">
      <div class="form-group">
        <label for="correo">Correo</label>
        <input type="email" id="correo" name="correo" required/>
      </div>

      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required/>
      </div>

      <button type="submit" class="btn" id="loginBtn">Entrar</button>

      <a href="../index.php" class="btn btn-secondary-custom">Regresar a la página principal</a>

      <div class="forgot-password">
        <a href="recuperar_password.php">¿Has olvidado la contraseña?</a>
      </div>

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
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.message,
              willOpen: () => {
                document.querySelector('.login-container').style.display = 'none';
              },
              didClose: () => {
                setTimeout(() => {
                  document.querySelector('.login-container').style.display = 'block';
                }, 200);
              }
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al intentar iniciar sesión.',
            willOpen: () => {
              document.querySelector('.login-container').style.display = 'none';
            },
            didClose: () => {
              setTimeout(() => {
                document.querySelector('.login-container').style.display = 'block';
              }, 200);
            }
          });
        });
    });
  </script>
</body>
</html>
