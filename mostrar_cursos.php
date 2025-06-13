<?php
require_once 'controller/conexion.php'; // ajusta la ruta si es diferente

try {
    $sql = "SELECT pk_curso, nom_curso, fk_tipo_curso, fk_lenguaje, link, estatus FROM cursos";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al consultar los cursos: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Cursos</title>
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
    </style>
</head>
<body>
    <h1>Listado de Cursos</h1>
    <table>
        <thead>
            <tr>
                <th>ID Curso</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Lenguaje</th>
                <th>Link</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cursos)): ?>
                <?php foreach ($cursos as $curso): ?>
                    <tr>
                        <td><?= htmlspecialchars($curso['pk_curso']) ?></td>
                        <td><?= htmlspecialchars($curso['nom_curso']) ?></td>
                        <td><?= htmlspecialchars($curso['fk_tipo_curso']) ?></td>
                        <td><?= htmlspecialchars($curso['fk_lenguaje']) ?></td>
                        <td><a href="<?= htmlspecialchars($curso['link']) ?>" target="_blank">Ver</a></td>
                        <td><?= $curso['estatus'] == 1 ? 'Activo' : 'Inactivo' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No hay cursos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
