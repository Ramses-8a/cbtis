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
                require_once(__DIR__ . '/../controller/recursos/mostrar_recursos.php');
                require_once(__DIR__ . '/../controller/cursos/mostrar_cursos.php');

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
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>52</td>
                        <td>Aplicación Móvil CBTis</td>
                        <td>App para estudiantes con información académica</td>
                        <td><span class="status-active">Activo</span></td>
                        <td class="actions">
                            <div class="action-btn view-btn" title="Ver">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="action-btn edit-btn" title="Editar">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="action-btn delete-btn" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>50</td>
                        <td>Portal de Eventos</td>
                        <td>Sistema de gestión de eventos escolares</td>
                        <td><span class="status-inactive">Inactivo</span></td>
                        <td class="actions">
                            <div class="action-btn view-btn" title="Ver">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="action-btn edit-btn" title="Editar">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="action-btn delete-btn" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </div>
                        </td>
                    </tr>
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
                    <tr>
                        <td>12</td>
                        <td>Competencia de Programación 2025</td>
                        <td>Programación</td>
                        <td>42</td>
                        <td><span class="status-active">Activo</span></td>
                        <td class="actions">
                            <div class="action-btn view-btn" title="Ver">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="action-btn edit-btn" title="Editar">
                                <i class="fas fa-edit"></i>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td>Torneo de Robótica</td>
                        <td>Tecnología</td>
                        <td>28</td>
                        <td><span class="status-active">Activo</span></td>
                        <td class="actions">
                            <div class="action-btn view-btn" title="Ver">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="action-btn edit-btn" title="Editar">
                                <i class="fas fa-edit"></i>
                            </div>
                        </td>
                    </tr>
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
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>51</td>
                        <td>Sistema de Gestión Escolar</td>
                        <td>Plataforma integral para administración escolar</td>
                        <td><span class="status-active">Activo</span></td>
                        <td class="actions">
                            <div class="action-btn view-btn" title="Ver">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="action-btn edit-btn" title="Editar">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="action-btn delete-btn" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>52</td>
                        <td>Aplicación Móvil CBTis</td>
                        <td>App para estudiantes con información académica</td>
                        <td><span class="status-active">Activo</span></td>
                        <td class="actions">
                            <div class="action-btn view-btn" title="Ver">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="action-btn edit-btn" title="Editar">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="action-btn delete-btn" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>50</td>
                        <td>Portal de Eventos</td>
                        <td>Sistema de gestión de eventos escolares</td>
                        <td><span class="status-inactive">Inactivo</span></td>
                        <td class="actions">
                            <div class="action-btn view-btn" title="Ver">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="action-btn edit-btn" title="Editar">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="action-btn delete-btn" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </div>
                        </td>
                    </tr>
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