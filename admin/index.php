<?php
include_once('header.php');
require_once(__DIR__ . '/../controller/proyecto/mostrar_proyecto.php');

$proyectos_activos_count = 0;
foreach ($proyectos as $proyecto) {
    if (isset($proyecto['estatus']) && $proyecto['estatus'] == 1) {
        $proyectos_activos_count++;
    }
}

require_once(__DIR__ . '/../controller/torneo/mostrar_torneos.php');

$torneos_activos_count = 0;
foreach ($torneos as $torneo) {
    if (isset($torneo['estatus']) && $torneo['estatus'] == 1) {
        $torneos_activos_count++;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Coloca esto justo antes de cerrar la etiqueta <head> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css" integrity="sha384-CgRB3jUvPrxCGryXuCYwHJTEYjCnW7Ew3E6R1RDpRF0Xs+UEqK1mhxh0UjE4Xm1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js" integrity="sha384-qwJPPqm+vKKFh8LuD5XyVB0QZXNz2HGZ2w8AGGTXdh/Q8E1TNxx5EMQhWEwNiJ6" crossorigin="anonymous"></script>
    <style>
        .actions a {
            text-decoration: none;
        }
    </style>

    <title>CBTis 152</title>
</head>

<body>

    <div class="admin-dashboard">

        <!-- Stats -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon icon-1">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $proyectos_activos_count; ?></h3>
                    <p>Proyectos Activos</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-2">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $torneos_activos_count; ?></h3>
                    <p>Torneos Activos</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-3">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="stat-info">
                    <?php
                    $recursos = include(__DIR__ . '/../controller/recursos/mostrar_recursos.php');
                    $cursos = include(__DIR__ . '/../controller/cursos/mostrar_cursos.php');

                    $recursos_activos_count = 0;
                    if (isset($recursos) && is_array($recursos)) {
                        foreach ($recursos as $recurso) {
                            if (isset($recurso['estatus']) && $recurso['estatus'] == 1) {
                                $recursos_activos_count++;
                            }
                        }
                    }

                    $cursos_activos_count = 0;
                    if (isset($cursos) && is_array($cursos)) {
                        foreach ($cursos as $curso) {
                            if (isset($curso['estatus']) && $curso['estatus'] == 1) {
                                $cursos_activos_count++;
                            }
                        }
                    }

                    $total_recursos_tecnologicos = $recursos_activos_count + $cursos_activos_count;
                    ?>
                    <h3><?php echo $total_recursos_tecnologicos; ?></h3>
                    <p>Recursos Tecnológicos</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-4">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <?php
                    require_once(__DIR__ . '/../controller/torneo/mostrar_participantes_activos.php');
                    $estudiantes_participantes_count = $participantes_activos_count;
                    ?>
                    <h3><?php echo $estudiantes_participantes_count; ?></h3>
                    <p>Estudiantes Participantes</p>
                </div>
            </div>
        </div>

        <!-- Proyectos Recientes -->
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">Proyectos Recientes</h2>
                <a href="formulario_proyectos.php">
                    <button class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Proyecto
                    </button>
                </a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sort the projects by pk_proyecto in descending order and get the latest 3
                        usort($proyectos, function ($a, $b) {
                            return $b['pk_proyecto'] - $a['pk_proyecto'];
                        });
                        $latest_proyectos = array_slice($proyectos, 0, 3);
                        ?>
                        <?php if (!empty($latest_proyectos)): ?>
                            <?php foreach ($latest_proyectos as $proyecto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($proyecto['pk_proyecto']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['nom_proyecto']); ?></td>
                                    <td><?php echo htmlspecialchars($proyecto['descripcion']); ?></td>
                                    <td>
                                        <?php if ($proyecto['estatus'] == 1): ?>
                                            <span class="status-active">Activo</span>
                                        <?php else: ?>
                                            <span class="status-inactive">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <a href="editar_proyecto.php?pk_proyecto=<?= $proyecto['pk_proyecto'] ?>">
                                            <div class="action-btn edit-btn" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </div>
                                        </a>
                                        <a href="#" class="delete-btn" data-id="<?php echo htmlspecialchars($proyecto['pk_proyecto']); ?>" data-type="proyecto">
                                            <div class="action-btn delete-btn" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No hay proyectos recientes para mostrar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Torneos Activos -->
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">Torneos Activos</h2>
                <a href="formulario_torneos.php">
                    <button class="btn btn-success">
                        <i class="fas fa-plus"></i> Nuevo Torneo
                    </button>
                </a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Participantes</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sort the tournaments by pk_torneo in descending order and get the latest 3
                        usort($torneos, function ($a, $b) {
                            return $b['pk_torneo'] - $a['pk_torneo'];
                        });
                        $latest_torneos = array_slice($torneos, 0, 3);
                        ?>
                        <?php if (!empty($latest_torneos)): ?>
                            <?php foreach ($latest_torneos as $torneo): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($torneo['pk_torneo']); ?></td>
                                    <td><?php echo htmlspecialchars($torneo['nom_torneo']); ?></td>
                                    <td><?php echo htmlspecialchars($torneo['nom_tipo']); ?></td>
                                    <td><?php echo htmlspecialchars($torneo['total_participantes']); ?></td>
                                    <td>
                                        <?php if ($torneo['estatus'] == 1): ?>
                                            <span class="status-active">Activo</span>
                                        <?php else: ?>
                                            <span class="status-inactive">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <a href="lista_participantes.php?pk_torneo=<?= $torneo['pk_torneo'] ?>">
                                            <div class="action-btn view-btn" title="Ver Participantes">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                        </a>
                                        <a href="editar_torneo.php?pk_torneo=<?= $torneo['pk_torneo'] ?>">
                                            <div class="action-btn edit-btn" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </div>
                                        </a>
                                        <a href="#" class="delete-btn" data-id="<?php echo htmlspecialchars($torneo['pk_torneo']); ?>" data-type="torneo">
                                            <div class="action-btn delete-btn" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No hay torneos recientes para mostrar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recursos Recientes -->
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">Recursos Tegnologicos Recientes</h2>
                <div>

                </div>
                <a href="formulario_recursos.php">
                    <button class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Recurso
                    </button>
                </a>
                <a href="formulario_cursos.php">
                    <button class="btn btn-success">
                        <i class="fas fa-plus"></i> Nuevo Curso
                    </button>
                </a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Combine resources and courses
                        $combined_items = [];

                        if (isset($recursos) && is_array($recursos)) {
                            foreach ($recursos as $recurso) {
                                $recurso['type'] = 'Recurso';
                                $recurso['id'] = $recurso['pk_recurso'];
                                $recurso['name'] = $recurso['nom_recurso'];
                                $combined_items[] = $recurso;
                            }
                        }

                        if (isset($cursos) && is_array($cursos)) {
                            foreach ($cursos as $curso) {
                                $curso['type'] = 'Curso';
                                $curso['id'] = $curso['pk_curso'];
                                $curso['name'] = $curso['nom_curso'];
                                $combined_items[] = $curso;
                            }
                        }

                        // Sort combined items by their primary key (id) in descending order
                        usort($combined_items, function ($a, $b) {
                            return $b['id'] - $a['id'];
                        });

                        // Get the latest 4 items
                        $latest_items = array_slice($combined_items, 0, 4);
                        ?>
                        <?php if (!empty($latest_items)): ?>
                            <?php foreach ($latest_items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['type']); ?></td>
                                    <td>
                                        <?php if ($item['estatus'] == 1): ?>
                                            <span class="status-active">Activo</span>
                                        <?php else: ?>
                                            <span class="status-inactive">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <?php
                                        $edit_url = $item['type'] === 'Recurso' ? 'editar_recurso.php?pk_recurso=' : 'editar_cursos.php?pk_curso=';
                                        $edit_url .= $item['id'];
                                        ?>
                                        <a href="<?php echo $edit_url; ?>">
                                            <div class="action-btn edit-btn" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </div>
                                        </a>
                                        <a href="#" class="delete-btn" data-id="<?php echo htmlspecialchars($item['id']); ?>" data-type="<?php echo htmlspecialchars(strtolower($item['type'])); ?>">
                                            <div class="action-btn delete-btn" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </div>
                                        </a>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const deleteButtons = document.querySelectorAll('.delete-btn');
                                                deleteButtons.forEach(button => {
                                                    button.addEventListener('click', function(e) {
                                                        e.preventDefault();
                                                        const itemId = this.dataset.id;
                                                        const itemType = this.dataset.type;
                                                        let deleteUrl = '';

                                                        if (itemType === 'recurso') {
                                                            deleteUrl = '../controller/recursos/eliminar_recurso.php';
                                                        } else if (itemType === 'curso') {
                                                            deleteUrl = '../controller/cursos/eliminar_curso.php';
                                                        }

                                                        if (deleteUrl) {
                                                            Swal.fire({
                                                                title: '¿Estás seguro?',
                                                                text: "¡No podrás revertir esto!",
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#3085d6',
                                                                cancelButtonColor: '#d33',
                                                                confirmButtonText: 'Sí, eliminarlo!',
                                                                cancelButtonText: 'Cancelar'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    fetch(deleteUrl, {
                                                                        method: 'POST',
                                                                        headers: {
                                                                            'Content-Type': 'application/x-www-form-urlencoded',
                                                                        },
                                                                        body: `id=${itemId}`,
                                                                    })
                                                                    .then(response => response.json())
                                                                    .then(data => {
                                                                        if (data.status === 'success') {
                                                                            Swal.fire(
                                                                                '¡Eliminado!',
                                                                                'El elemento ha sido eliminado.',
                                                                                'success'
                                                                            ).then(() => {
                                                                                location.reload();
                                                                            });
                                                                        } else {
                                                                            Swal.fire(
                                                                                'Error',
                                                                                data.message || 'Hubo un error al eliminar el elemento.',
                                                                                'error'
                                                                            );
                                                                        }
                                                                    })
                                                                    .catch(error => {
                                                                        console.error('Error:', error);
                                                                        Swal.fire(
                                                                            'Error',
                                                                            'Hubo un error al procesar la solicitud.',
                                                                            'error'
                                                                        );
                                                                    });
                                                                }
                                                            });
                                                        }
                                                    });
                                                });
                                            });
                                        </script>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No hay recursos o cursos recientes para mostrar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <footer class="footer">
        <p>© 2025 CBTis No.152 </p>
        <p>Desarrollado Para el Departamento de Programación</p>

        <div class="redes-sociales">
            <a href="#" target="_blank" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" target="_blank" aria-label="Twitter">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="#" target="_blank" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="#" target="_blank" aria-label="YouTube">
                <i class="fab fa-youtube"></i>
            </a>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const itemId = this.dataset.id;
                    const itemType = this.dataset.type;
                    let deleteUrl = '';

                    switch (itemType) {
                        case 'proyecto':
                            deleteUrl = '../controller/proyecto/eliminar_proyecto.php';
                            break;
                        case 'recurso':
                            deleteUrl = '../controller/recursos/eliminar_recurso.php';
                            break;
                        case 'curso':
                            deleteUrl = '../controller/cursos/eliminar_curso.php';
                            break;
                        case 'torneo':
                            deleteUrl = '../controller/torneo/eliminar_torneo.php';
                            break;
                        default:
                            Swal.fire('Error', 'Tipo de elemento desconocido.', 'error');
                            return;
                    }

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminarlo!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(deleteUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: `id=${itemId}`,
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        Swal.fire(
                                            '¡Eliminado!',
                                            'El elemento ha sido eliminado.',
                                            'success'
                                        ).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire(
                                            'Error',
                                            data.message || 'Hubo un error al eliminar el elemento.',
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire(
                                        'Error',
                                        'Hubo un error de conexión.',
                                        'error'
                                    );
                                });
                        }
                    });
                });
            });
        });
    </script>


</body>

</html>

<style>
    .footer {
        background-color: #f8f8f8;
        color: #9d0707;
        text-align: center;
        padding: 20px 10px;
        font-size: 0.9rem;
        border-top: 2px solid #9d0707;
        margin-top: 40px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .footer p {
        margin: 5px 0;
    }

    .redes-sociales {
        margin-top: 10px;
    }

    .redes-sociales a {
        color: #9d0707;
        margin: 0 10px;
        font-size: 1.2rem;
        transition: transform 0.2s ease, color 0.2s ease;
    }

    .redes-sociales a:hover {
        color: #d10000;
        transform: scale(1.2);
    }
</style>