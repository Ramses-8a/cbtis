<?php
include('../controller/recursos/contar_recursos.php');
include_once('header.php'); 
?>
<head>
    <link rel="stylesheet" href="../css/list_proyecto.css">
</head>
<body>
    <div class="con_volver">
        <a href="index.php" class="volver">
            <img src="../img/volver.webp" alt="Volver">
        </a>
        <h3>Recursos Registrados</h3>
    </div>
   <div class="contenedor">
     <table>
     <tr>
        <th>Imagen</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Tipo de Recurso</th>
        <th>URL</th>
        <th>Estatus</th>
        <th>Acciones</th>
        <!-- <th>Eliminar</th> -->
    </tr>
    <?php if (empty($recursos)): ?>
    <tr>
        <td colspan="8" style="text-align: center; padding: 20px; background-color: #f5f5f5; color: #666;">
            <strong>No hay recursos disponibles actualmente.</strong><br>
            Cuando se agreguen recursos, aparecerán aquí.
        </td>
    </tr>
<?php else: ?>
    <?php foreach ($recursos as $recurso): ?>
    <tr>
        <td><img src="../uploads/<?= $recurso['img'] ?>" width="50px"></td>
        <td><?= $recurso['nom_recurso'] ?></td>
        <td><?= $recurso['descripcion'] ?></td>
        <td><?= $recurso['nom_tipo'] ?? 'Sin tipo' ?></td>
        <td><a href="<?= $recurso['url'] ?>"><?= $recurso['url'] ?></a></td>
         <td class="estatus <?= $recurso['estatus'] == 1 ? 'activo' : 'inactivo' ?>">
                <?= $recurso['estatus'] == 1 ? 'Activo' : 'Inactivo' ?>
                </td>
        <td>
                    <div class="botones-accion-en-linea">
                        <a class="btn-editar" href="editar_recurso.php?pk_recurso=<?= $recurso['pk_recurso'] ?>" title="Editar">
                        <img src="../img/boton-editar.png" alt=""></a>
                    <a class="btn-eliminar" href="#" onclick="confirmAction(event, '<?= $recurso['pk_recurso'] ?>', '<?= $recurso['estatus'] ?>')" title="Dar de baja">
                        <img src="../img/basura-bln.png" alt=""></a>
                    </div>
                </td>
        <!-- <td>
            <a class="btn-eliminar" href="#" onclick="confirmDelete(event, '<?= $recurso['pk_recurso'] ?>')">
                Eliminar <i class="fas fa-trash-alt"></i>
            </a>
        </td> -->
    </tr>
    <?php endforeach; ?>
<?php endif; ?>
</table>
</div>

<script>
function confirmDelete(event, resourceId) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../controller/recursos/eliminar_recurso.php?id=${resourceId}`;
        }
    });
}

function confirmAction(event, resourceId, currentStatus) {
    event.preventDefault();

    let actionText = currentStatus == 1 ? 'Desactivar' : 'Activar';
    let confirmMessage = `¿Estás seguro de ${actionText.toLowerCase()} este recurso?`;

    Swal.fire({
        title: 'Confirmar Acción',
        text: confirmMessage,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../controller/recursos/baja_recurso.php?id=${resourceId}`;
        }
    });
}

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('deleted')) {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'El recurso ha sido eliminado correctamente',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = window.location.pathname;
        });
    } else if (urlParams.has('status_changed')) {
        const mensaje = urlParams.get('message');
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: `El recurso ha sido ${mensaje} correctamente`,
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = window.location.pathname;
        });
    } else if (urlParams.has('error')) {
        const errorMsg = urlParams.get('error');
        let mensaje = 'Ha ocurrido un error';
        
        switch(errorMsg) {
            case 'no_id':
                mensaje = 'No se proporcionó el ID del recurso';
                break;
            case 'not_found':
                mensaje = 'Recurso no encontrado';
                break;
            case 'update_failed':
                mensaje = 'Error al actualizar el estado del recurso';
                break;
            default:
                mensaje = 'Error: ' + errorMsg;
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: mensaje,
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = window.location.pathname;
        });
    }
};
</script>
</body>