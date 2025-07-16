<?php
session_start();
require_once __DIR__ . '/../controller/conexion.php';

$token = $_GET['token'] ?? '';
$error = '';
$usuario = null;

if ($token) {
    $stmt = $connect->prepare('SELECT pk_usuario, correo, password FROM usuarios WHERE token_ver = ? LIMIT 1');
    $stmt->execute([$token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$usuario) {
        $error = 'Token inválido o expirado.';
    }
} else {
    $error = 'No se proporcionó token.';
}

// Procesar formulario vía AJAX
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["password"]) && isset($_POST["confirm_password"])) {
    header('Content-Type: application/json');

    $newPassword = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if (!$usuario) {
        echo json_encode(["status" => "error", "message" => "Token inválido o expirado."]);
        exit;
    }

    if (strlen($newPassword) < 6) {
        echo json_encode(["status" => "error", "message" => "La contraseña debe tener al menos 6 caracteres."]);
        exit;
    } elseif ($newPassword !== $confirmPassword) {
        echo json_encode(["status" => "error", "message" => "Las contraseñas no coinciden."]);
        exit;
    } elseif (password_verify($newPassword, $usuario["password"])) {
        echo json_encode(["status" => "error", "message" => "No puedes usar la misma contraseña anterior."]);
        exit;
    }

    // Cifrar nueva contraseña
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Generar nuevo token_ver como hash del correo
    $nuevoTokenVer = hash('sha256', $usuario["correo"]);

    // Actualizar en la base de datos
    $updateStmt = $connect->prepare("UPDATE usuarios SET password = ?, token_ver = ? WHERE pk_usuario = ?");
    if ($updateStmt->execute([$hashedPassword, $nuevoTokenVer, $usuario["pk_usuario"]])) {
        echo json_encode(["status" => "success", "message" => "Contraseña actualizada correctamente."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al actualizar la contraseña."]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="shortcut icon" href="../img/logo_sf.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .login-container {
            max-width: 400px;
            margin: 60px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 2.5rem 2rem 2rem 2rem;
        }
        .logo {
            display: block;
            margin: 0 auto 1.5rem auto;
            width: 90px;
        }
        .btn {
            background: #c62828;
            color: #fff;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            margin-top: 1rem;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #a31515;
        }
        .error {
            color: #c62828;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body style="background: #f5f5f5;">
    <div class="login-container">
        <img src="../img/logo_cbtis.png" alt="Logo CBTIS" class="logo">

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php elseif ($usuario): ?>
            <h2 style="text-align:center; margin-bottom:1.2rem;">Cambiar contraseña</h2>
            <form id="cambiarPasswordForm" method="POST">
                <input type="hidden" name="token" id="token" value="<?php echo htmlspecialchars($token); ?>">
                <div style="margin-bottom:1rem;">
                    <label for="password">Nueva contraseña</label>
                    <input type="password" name="password" id="password" required placeholder="Nueva contraseña" minlength="6">
                </div>
                <div style="margin-bottom:1rem;">
                    <label for="confirm_password">Confirmar contraseña</label>
                    <input type="password" name="confirm_password" id="confirm_password"  required placeholder="Confirmar contraseña" minlength="6">
                </div>
                <button type="submit" class="btn">Cambiar contraseña</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
    document.getElementById('cambiarPasswordForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            Swal.fire({
                icon: data.status === 'success' ? 'success' : 'error',
                title: data.status === 'success' ? '¡Éxito!' : 'Error',
                text: data.message
            }).then(() => {
                if (data.status === 'success') {
                    window.location.href = 'formulario_login.php';
                }
            });
        })
        .catch(() => {
            Swal.fire({icon: 'error', title: 'Error', text: 'Error del servidor o de red.'});
        });
    });
    </script>
</body>
</html>
