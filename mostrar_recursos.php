<?php
require_once 'controller/conexion.php'; // Ajusta si tu ruta es diferente

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
    <title>Lista de Recursos</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
            color: blue;
        }

        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Listado de Recursos</h1>
    <table>
        <thead>
            <tr>
                <th>Tipo de Recurso</th>
                <th>Nombre</th>
                <th>URL</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recursos)): ?>
                <?php foreach ($recursos as $recurso): ?>
                    <tr>
                        <td><?= htmlspecialchars($recurso['tipo_recurso']) ?></td>
                        <td><?= htmlspecialchars($recurso['nom_recurso']) ?></td>
                        <td><a href="<?= htmlspecialchars($recurso['url']) ?>" target="_blank">Visitar</a></td>
                        <td>
                            <?php if (!empty($recurso['img'])): ?>
                                <img src="uploads/<?= htmlspecialchars($recurso['img']) ?>" alt="Imagen recurso">
                            <?php else: ?>
                                Sin imagen
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No hay recursos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
