<?php
require_once 'controller/conexion.php';
include_once('header.php');

try {
    $sql = "SELECT r.pk_recurso, tr.nom_tipo AS tipo_recurso, r.nom_recurso, r.url, r.estatus, r.img 
            FROM recursos r
            INNER JOIN tipo_recursos tr ON r.fk_tipo_recurso = tr.pk_tipo_recurso";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $recursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al consultar los recursos: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recursos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/proyecto.css">
</head>
<body>
    <div class="con_volver">
        <a href="index.php" class="volver">
            <img src="img/regresar.png" alt="Volver">
        </a>
        <h3>Recursos</h3>
    </div>

    <main class="proyectos">
    <?php foreach ($recursos as $recurso):
        if (isset($recurso['estatus']) && $recurso['estatus'] == 1):
    ?>
        <a href="controller/recursos/mostrar_recursos.php?pk_recurso=<?= $recurso['pk_recurso'] ?>" class="card">
            <?php if (!empty($recurso['img'])): ?>
                <img src="uploads/<?= htmlspecialchars($recurso['img']) ?>" alt="Imagen recurso">
            <?php else: ?>
                <div style="width:100%;height:200px;display:flex;align-items:center;justify-content:center;background:#f2f2f2;color:#666;">
                    Sin imagen
                </div>
            <?php endif; ?>
            <p><strong><?= htmlspecialchars($recurso['nom_recurso']) ?></strong></p>
            <p><?= htmlspecialchars($recurso['tipo_recurso']) ?></p>
        </a>
    <?php 
        endif;
    endforeach; 
    ?>
    </main>
</body>
</html>
