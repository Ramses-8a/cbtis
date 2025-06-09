<?php
require_once 'controller/conexion.php';
require_once 'header.php';


$tipo = $_GET['tipo'] ?? '';

if ($tipo) {
    $sql = "SELECT t.pk_torneo, t.nom_torneo, t.descripcion, t.detalles, t.img, tt.nom_tipo
            FROM torneos t
            INNER JOIN tipo_torneos tt ON t.fk_tipo_torneo = tt.pk_tipo_torneo
            WHERE t.estatus = 1 AND tt.estatus = 1 AND LOWER(tt.nom_tipo) = LOWER(?)
            ORDER BY t.pk_torneo DESC";

    $stmt = $connect->prepare($sql);
    $stmt->execute([$tipo]);
} else {
    $sql = "SELECT t.pk_torneo, t.nom_torneo, t.descripcion, t.detalles, t.img, tt.nom_tipo
            FROM torneos t
            INNER JOIN tipo_torneos tt ON t.fk_tipo_torneo = tt.pk_tipo_torneo
            WHERE t.estatus = 1 AND tt.estatus = 1
            ORDER BY t.pk_torneo DESC";

    $stmt = $connect->prepare($sql);
    $stmt->execute();
}


$torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Torneos</title>
    <link rel="stylesheet" href="css/torneo_vista.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .torneo {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .torneo:hover {
            background-color: #f0f0f0;
        }
        .torneo a {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .torneo img {
            max-width: 200px;
            height: auto;
            margin-top: 10px;
            display: block;
        }
   /* contenedor para volver */
        .con_volver {
          display: flex;
          align-items: center;
          gap: 10px;
          padding: 15px 20px;
          background-color: white;
        }
        
        .con_volver .volver img {
          width: 28px;
          height: 28px;
          object-fit: contain;
          cursor: pointer;
        }
        
        .con_volver h3 {
          font-size: 2.5rem;
          font-weight: bold;
          margin: 0;
        }
    </style>
</head>

<body>

<div class="con_volver">
    <a href="index.php" class="volver">
        <img src="img/volver.webp" alt="Volver">
    </a>
    <h3>Torneos</h3>
</div>

<?php if ($tipo): ?>
    <h1>Lista de Torneos de <?= htmlspecialchars(ucfirst($tipo)) ?></h1>
<?php else: ?>
    <h1>Lista de Todos los Torneos</h1>
<?php endif; ?>

<div class="grid_torneos">

    <?php foreach ($torneos as $torneo): ?>
        <div class="cont_torneo">
            <a href="detalle_torneo.php?id=<?= urlencode($torneo['pk_torneo']) ?>">
                <h2><?= htmlspecialchars($torneo['nom_torneo']) ?></h2>
                <p><strong>Tipo:</strong> <?= htmlspecialchars($torneo['nom_tipo']) ?></p>
                <p><strong>Descripción:</strong> <?= htmlspecialchars($torneo['descripcion']) ?></p>
                <p><strong>Detalles:</strong> <?= htmlspecialchars($torneo['detalles']) ?></p>

                <?php if (!empty($torneo['img'])): ?>
                    <p><strong>Imagen Principal:</strong><br>
                        <img src="../../uploads/<?= urlencode($torneo['img']) ?>" alt="Imagen principal">
                    </p>
                <?php endif; ?>
            </a>
        </div>
    <?php endforeach; ?>

</div>
</body>
</html>




