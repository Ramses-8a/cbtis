<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/login.css">
  <link rel="shortcut icon" href="../img/logo_sf.png">
  <title>Enviar Correo de Prueba</title>
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
    
    <form id="emailForm" action="../controller/mail/send_mail.php" method="POST">
      <div class="form-group">
         <label for="correo">Ingresa tu correo electrónico</label>
        <input type="email" id="email" name="email" class="form-control" required placeholder="ejemplo@correo.com">
      </div>

      <button type="submit" class="btn" id="sendBtn">Enviar correo de recuperacion</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#emailForm').on('submit', function(event) {
        event.preventDefault();
        var submitBtn = $('#sendBtn');
        submitBtn.prop('disabled', true).text('Enviando...');
        var formData = new FormData(this);
        $.ajax({
          url: $(this).attr('action'),
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(data) {
            if (typeof data === 'string') {
              try { data = JSON.parse(data); } catch (e) { data = {}; }
            }
            if (data.status === 'success') {
              $('.login-container').hide();
              Swal.fire({
                icon: 'success',
                title: '¡Correo enviado!',
                text: 'Si el correo es válido, recibirás un mensaje en tu bandeja de entrada.',
                confirmButtonText: 'Aceptar'
              }).then(() => {
                window.location.href = 'formulario_login.php';
              });
            } else {
              $('.login-container').hide();
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Ocurrió un error desconocido',
                confirmButtonText: 'Aceptar'
              }).then(() => {
                setTimeout(function() {
                  $('.login-container').show();
                }, 200);
              });
            }
          },
          error: function() {
            $('.login-container').hide();
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Ocurrió un error al intentar enviar el correo. Por favor, verifica la configuración del correo.',
              confirmButtonText: 'Aceptar'
            }).then(() => {
              setTimeout(function() {
                $('.login-container').show();
              }, 200);
            });
          },
          complete: function() {
            submitBtn.prop('disabled', false).text('Enviar correo de prueba');
          }
        });
      });
    });
  </script>
</body>
</html>