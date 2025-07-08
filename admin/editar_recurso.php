<?php
include_once('header.php');
include('../controller/recursos/buscar_recurso.php');

if (!isset($_GET['pk_recurso'])) {
    header('Location: lista_recursos.php');
    exit;
}

$recurso = $recurso ?? null;

?>
<head>
    <link rel="stylesheet" href="../css/form_proyecto.css">
    <title>Editar Recurso</title>
</head>

<form id="formRecurso" enctype="multipart/form-data" class="form-proyectos">
    <input type="hidden" id="pk_recurso" name="pk_recurso" value="<?= $recurso['pk_recurso'] ?? '' ?>">
    <div>
        <label for="nom_recurso">Nombre del recurso:</label>
        <input type="text" id="nom_recurso" name="nom_recurso" value="<?= $recurso['nom_recurso'] ?? '' ?>" required placeholder="Escribe el nombre del recurso">
    </div>

    <div>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required placeholder="Escribe la descripción del recurso"><?= $recurso['descripcion'] ?? '' ?></textarea>
    </div>

    <div>
        <label for="pk_tipo_recurso">Tipo de recurso:</label>
        <select name="pk_tipo_recurso" id="pk_tipo_recurso" required>
            <?php
            include_once '../controller/conexion.php';
            $stmt = $connect->prepare("SELECT * FROM tipo_recursos WHERE estatus = 1");
            $stmt->execute();
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $selected = (isset($recurso['fk_tipo_recurso']) && $recurso['fk_tipo_recurso'] == $row['pk_tipo_recurso']) ? 'selected' : '';
                echo "<option value='{$row['pk_tipo_recurso']}' {$selected}>{$row['nom_tipo']}</option>";
            }
            ?>
        </select>
    </div>

    <div>
        <label for="url">URL:</label>
        <input type="url" id="url" name="url" value="<?= $recurso['url'] ?? '' ?>" required placeholder="https://ejemplo.com/recurso">
    </div>

    <div>
        <label for="img">Imagen principal:</label>
        <?php if (isset($recurso['img']) && $recurso['img']): ?>
            <img src="../uploads/<?= $recurso['img'] ?>" width="100px"><br>
            <input type="hidden" name="current_img" value="<?= $recurso['img'] ?>">
        <?php endif; ?>
        <input type="file" id="img" name="img" accept="image/*">
    </div>

    <div class="button-container">
        <button class="guardar" type="submit">Actualizar Recurso</button>
        <button class="cancelar" type="button" onclick="window.location.href='lista_recursos.php'">Cancelar</button>
    </div>
</form>

<script>
console.log('PK Recurso recibido:', <?php echo json_encode($_GET['pk_recurso'] ?? 'No PK Recurso'); ?>);

document.getElementById('formRecurso').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('../controller/recursos/actualizar_recurso.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        console.log('Respuesta del servidor:', text);
        try {
            const data = JSON.parse(text);
            console.log('Datos parseados:', data);
            
            Swal.fire({
                icon: data.status === 'success' ? 'success' : data.status === 'warning' ? 'warning' : 'error',
                title: data.status === 'success' ? '¡Éxito!' : data.status === 'warning' ? 'Advertencia' : 'Error',
                text: data.message,
                confirmButtonColor: '#9d0707'
            }).then(() => {
                if (data.status === 'success' && data.redirect_url) {
                    window.location.href = data.redirect_url;
                }
            });
        } catch (e) {
            console.error('Error al parsear JSON:', e);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al procesar la respuesta del servidor',
                html: `<pre>${text}</pre>`,
                confirmButtonColor: '#9d0707'
            });
        }
    })
    .catch(error => {
        console.error('Error en la petición:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al comunicarse con el servidor',
            confirmButtonColor: '#9d0707'
        });
    });
});
</script>